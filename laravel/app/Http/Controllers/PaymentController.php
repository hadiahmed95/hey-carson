<?php

namespace App\Http\Controllers;

use App\Events\PaymentConfirmed;
use App\Events\SendEmail;
use App\Events\CacheInvalidation;
use App\Mail\Client\PurchasedMail;
use App\Models\ClientFund;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Referral;
use App\Models\SavedCard;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function saveCard(Request $request)
    {
        $user = Auth::user();
        $paymentId = $request->get('payment_id');
        $lastDigits = $request->get('last_digits');
        $cardType = $request->get('card_type');
        $expDate = $request->get('exp_date');

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        if ($user->savedCards->first()) {
            $savedCardCustomer = $user->savedCards->first()->customer_id;

            $stripe->paymentMethods->attach(
                $paymentId,
                ['customer' => $savedCardCustomer]
            );

            $user->savedCards()->create([
                'customer_id' => $savedCardCustomer,
                'payment_id' => $paymentId,
                'card_type' => $cardType,
                'exp_date' => $expDate,
                'last_digits' => $lastDigits,
            ]);
        } else {
            $customer = $stripe->customers->create([
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'payment_method' => $paymentId
            ]);

            $user->savedCards()->create([
                'customer_id' => $customer->id,
                'payment_id' => $paymentId,
                'card_type' => $cardType,
                'exp_date' => $expDate,
                'last_digits' => $lastDigits,
                'default' => true
            ]);
        }
    }

    public function buyHours(Request $request)
    {
        $user = Auth::user();

        $selectedPack = $request->get('selected_pack');
        $selectedCard = SavedCard::query()->find($request->get('selected_card_id'));

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        try {
            $paymentDetail = $stripe->paymentIntents->create([
                'amount' => $selectedPack['total'] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'customer' => $selectedCard->customer_id,
                'payment_method' => $selectedCard->payment_id,
                'error_on_requires_action' => true,
                'confirm' => true,
            ]);

            $payment = Payment::create([
                'user_id' => $user->id,
                'stripe_payment_id' => $paymentDetail->id,
                'amount' => $selectedPack['amount'],
                'price' => $selectedPack['price'],
                'total' => $selectedPack['total'],
                'status' => $paymentDetail->status
            ]);

            $selectedCard->update(['last_used' => $payment->created_at]);

            if ($payment->status === 'succeeded') {
                ClientFund::create([
                    'user_id' => $user->id,
                    'prepaid_hours' => $selectedPack['amount'],
                    'price' => $selectedPack['price'],
                ]);


                SendEmail::dispatch($user, new PurchasedMail($user));

                CacheInvalidation::dispatch('cache_duration_key', CacheService::SALES);
                CacheInvalidation::dispatch('cache_key', CacheService::HOURS . '_' . $user->id);
            }

            return response()->json(['message' => 'OK']);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function cardPayment(Request $request): JsonResponse
    {
        $user = Auth::user();

        $projectId = $request->get('project_id');
        $project = Project::find($projectId);

        $selectedPack = $request->get('selected_pack');
        $selectedCard = SavedCard::query()->find($request->get('selected_card_id'));

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        try {
            $paymentDetail = $stripe->paymentIntents->create([
                'amount' => $selectedPack['total'] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'customer' => $selectedCard->customer_id,
                'payment_method' => $selectedCard->payment_id,
                'error_on_requires_action' => true,
                'confirm' => true,
            ]);

            $payment = Payment::create([
                'user_id'            => $user->id,
                'project_id'         => $projectId,
                'offer_id'           => $selectedPack['offer_id'],
                'stripe_payment_id'  => $paymentDetail->id,
                'amount'             => $selectedPack['amount'],
                'price'              => $selectedPack['price'],
                'total'              => $selectedPack['total'],
                'status'             => $paymentDetail->status,
            ]);

            Broadcast(new PaymentConfirmed($payment));
            CacheInvalidation::dispatch('cache_duration_key', CacheService::SALES);

            $selectedCard->update(['last_used' => $payment->created_at]);

            return response()->json(['message' => 'OK']);

        } catch (Exception $e) {
            if ($e->getStatusCode() == 429) {
                return response()->json(['error' => 'You can only make one payment per minute.'], 429);
            }
            return response()->json([
                'error' => $e,
            ], 400);
        }
    }

    public function prepaid(Request $request)
    {
        $user = Auth::user();

        $projectId = $request->get('project_id');
        $project = Project::find($projectId);

        $amount = $request->get('amount');
        $offerId = $request->get('offer_id');

        do {
            $clientFund = ClientFund::query()
                ->where('used_pack', false)
                ->where('user_id', $user->id)
                ->oldest()
                ->first();

            $currentUsedHours = $clientFund->used_hours + $amount;

            if ($currentUsedHours > $clientFund->prepaid_hours) {
                $overflowHours = $currentUsedHours - $clientFund->prepaid_hours;
                $currentUsedHours = $clientFund->prepaid_hours;
            } else {
                $overflowHours = 0;
            }

            $amount = $amount - $overflowHours;

            $clientFund->used_hours = $currentUsedHours;

            $payment = Payment::create([
                'user_id'           => $user->id,
                'project_id'        => $projectId,
                'offer_id'          => $offerId,
                'status'            => Payment::PREPAID,
                'amount'            => $amount,
                'price'             => $clientFund->price,
                'total'             => $clientFund->price * $amount,
            ]);

            Broadcast(new PaymentConfirmed($payment));
            CacheInvalidation::dispatch('cache_duration_key', CacheService::SALES);

            if ($clientFund->used_hours === $clientFund->prepaid_hours) {
                $clientFund->used_pack = true;
            }

            $amount = $overflowHours;

            $clientFund->save();
            CacheInvalidation::dispatch('cache_key', CacheService::HOURS . '_' . $user->id);
            $clientFund->refresh();
        } while ($overflowHours > 0);

        return response()->json(['message' => 'OK']);
    }
}
