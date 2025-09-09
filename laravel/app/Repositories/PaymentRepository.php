<?php

namespace App\Repositories;

use App\Events\CacheInvalidation;
use App\Models\Payment;
use App\Models\Project;
use App\Models\SavedCard;
use App\Models\User;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\ApiErrorException;

class PaymentRepository
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret', env('STRIPE_SECRET_KEY')));
    }

    /**
     * Save a new card for the user
     *
     * @param User $user
     * @param array $cardData
     * @return array
     */
    public function saveCard(User $user, array $cardData): array
    {
        try {
            return DB::transaction(function () use ($user, $cardData) {
                $existingCard = $user->savedCards->first();

                if ($existingCard) {
                    return $this->attachCardToExistingCustomer($user, $existingCard, $cardData);
                } else {
                    return $this->createNewCustomerWithCard($user, $cardData);
                }
            });
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error during card save', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'stripe_error_code' => $e->getStripeCode()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to save card: ' . $e->getMessage(),
                'status_code' => 400
            ];
        } catch (\Exception $e) {
            Log::error('General error during card save', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'An unexpected error occurred while saving the card.',
                'status_code' => 500
            ];
        }
    }

    /**
     * Attach card to existing Stripe customer
     *
     * @param User $user
     * @param SavedCard $existingCard
     * @param array $cardData
     * @return array
     * @throws ApiErrorException
     */
    private function attachCardToExistingCustomer(User $user, SavedCard $existingCard, array $cardData): array
    {
        // Check if card already exists
        if ($this->cardAlreadyExists($user, $cardData['last_digits'], $cardData['card_type'])) {
            return [
                'success' => false,
                'error' => 'This card is already saved.',
                'status_code' => 409
            ];
        }

        // Attach payment method to existing customer
        $this->stripe->paymentMethods->attach(
            $cardData['payment_id'],
            ['customer' => $existingCard->customer_id]
        );

        // Save card to database
        $savedCard = $user->savedCards()->create([
            'customer_id' => $existingCard->customer_id,
            'payment_id' => $cardData['payment_id'],
            'card_type' => $cardData['card_type'],
            'exp_date' => $cardData['exp_date'],
            'last_digits' => $cardData['last_digits'],
            'default' => false // New cards are not default unless it's the first one
        ]);

        return [
            'success' => true,
            'message' => 'Card saved successfully.',
            'saved_card' => $savedCard,
            'status_code' => 201
        ];
    }

    /**
     * Create new Stripe customer with card
     *
     * @param User $user
     * @param array $cardData
     * @return array
     * @throws ApiErrorException
     */
    private function createNewCustomerWithCard(User $user, array $cardData): array
    {
        // Create new Stripe customer
        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'payment_method' => $cardData['payment_id'],
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);

        // Save card to database
        $savedCard = $user->savedCards()->create([
            'customer_id' => $customer->id,
            'payment_id' => $cardData['payment_id'],
            'card_type' => $cardData['card_type'],
            'exp_date' => $cardData['exp_date'],
            'last_digits' => $cardData['last_digits'],
            'default' => true // First card is always default
        ]);

        return [
            'success' => true,
            'message' => 'Card saved successfully.',
            'saved_card' => $savedCard,
            'customer_id' => $customer->id,
            'status_code' => 201
        ];
    }

    /**
     * Check if card already exists for user
     *
     * @param User $user
     * @param string $lastDigits
     * @param string $cardType
     * @return bool
     */
    private function cardAlreadyExists(User $user, string $lastDigits, string $cardType): bool
    {
        return $user->savedCards()
            ->where('last_digits', $lastDigits)
            ->where('card_type', $cardType)
            ->exists();
    }

    /**
     * Process card payment
     *
     * @param $user
     * @param Project $project
     * @param array $selectedPack
     * @param SavedCard $selectedCard
     * @return array
     */
    public function processCardPayment($user, Project $project, array $selectedPack, SavedCard $selectedCard): array
    {
        try {
            // Create Stripe payment intent
            $paymentDetail = $this->stripe->paymentIntents->create([
                'amount' => $selectedPack['total'] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'customer' => $selectedCard->customer_id,
                'payment_method' => $selectedCard->payment_id,
                'error_on_requires_action' => true,
                'confirm' => true,
                'description' => "Project #{$project->id} - Offer Payment",
                'metadata' => [
                    'project_id' => $project->id,
                    'offer_id' => $selectedPack['offer_id'],
                    'user_id' => $user->id,
                ],
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id'            => $user->id,
                'project_id'         => $project->id,
                'offer_id'           => $selectedPack['offer_id'],
                'stripe_payment_id'  => $paymentDetail->id,
                'amount'             => $selectedPack['amount'],
                'price'              => $selectedPack['price'],
                'total'              => $selectedPack['total'],
                'status'             => $paymentDetail->status,
            ]);

            // Post-payment processing
            $this->handlePostPayment($payment, $selectedCard);

            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'OK'
            ];

        } catch (CardException $e) {
            return $this->handleCardException($e);
        } catch (RateLimitException $e) {
            return [
                'success' => false,
                'error' => 'You can only make one payment per minute.',
                'status_code' => 429
            ];
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'project_id' => $project->id
            ]);

            return [
                'success' => false,
                'error' => 'Payment processing failed. Please try again.',
                'status_code' => 500
            ];
        }
    }

    /**
     * Validate payment request data
     *
     * @param $user
     * @param Project|null $project
     * @param SavedCard|null $selectedCard
     * @return array
     */
    public function validatePaymentData($user, ?Project $project, ?SavedCard $selectedCard): array
    {
        if (!$project) {
            return [
                'success' => false,
                'error' => 'Project not found',
                'status_code' => 400
            ];
        }

        if (!$selectedCard) {
            return [
                'success' => false,
                'error' => 'Payment method not found',
                'status_code' => 400
            ];
        }

        if ($selectedCard->user_id !== $user->id) {
            return [
                'success' => false,
                'error' => 'Unauthorized payment method',
                'status_code' => 400
            ];
        }

        return ['success' => true];
    }

    /**
     * Handle post-payment processing
     *
     * @param Payment $payment
     * @param SavedCard $selectedCard
     * @return void
     */
    private function handlePostPayment(Payment $payment, SavedCard $selectedCard): void
    {
        // Uncomment when ready to use
        // Broadcast(new PaymentConfirmed($payment));

        CacheInvalidation::dispatch('cache_duration_key', CacheService::SALES);
        $selectedCard->update(['last_used' => $payment->created_at]);
    }

    /**
     * Handle Stripe card exceptions
     *
     * @param CardException $e
     * @return array
     */
    private function handleCardException(CardException $e): array
    {
        $declineCode = $e->getDeclineCode();

        $errorMessages = [
            'generic_decline' => 'Your card was declined. Please try a different payment method or contact your bank.',
            'insufficient_funds' => 'Insufficient funds. Please check your account balance.',
            'expired_card' => 'Your card has expired. Please update your payment method.',
            'incorrect_cvc' => 'Your card\'s security code is incorrect.',
            'processing_error' => 'An error occurred processing your card. Please try again.',
        ];

        $message = $errorMessages[$declineCode] ?? 'Your payment was declined. Please try a different payment method.';

        return [
            'success' => false,
            'error' => $message,
            'decline_code' => $declineCode,
            'status_code' => 400
        ];
    }

    /**
     * Get all transactions for the given user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactions(User $user)
    {
        return Payment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['succeeded', 'prepaid'])
            ->with(['project', 'offer', 'user'])
            ->latest('created_at')
            ->get();
    }
}
