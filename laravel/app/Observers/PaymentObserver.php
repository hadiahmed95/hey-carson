<?php

namespace App\Observers;

use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param Payment  $payment
     * @return void
     */
    public function creating(Payment $payment)
    {
        $offer = Offer::find($payment->offer_id);

        $existingPayment = Payment::where('user_id', $payment->user_id)
            ->where('offer_id', $payment->offer_id)
            ->where('status', 'succeeded')
            ->exists();

        if ($existingPayment && $offer && $offer->status !== 'send') {
            throw new \Exception('Already paid');
        }
    }

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        try {
//        TODO: use queue to reduce loading time.
            $tierGroup = 'standard';
            if ($payment->user->click_id && $payment->stripe_payment_id) {
                $customer = app('referral_api_account')->getCustomer($payment->user->email);

                $clickId = $customer ? $customer['click_id'] : $payment->user->click_id;

                $click = app('referral_api_account')->getClick($clickId);

                if (!$click) {
                    Log::error("{$click} Click not found!");
                    return;
                }

                $commissionTier = app('referral_api_account')->getCommissionTier($click['referral_id'], 'standard');

                if ($payment->user->payouts()->count() < 2 && $commissionTier['slug'] == 'level-4') {
                    $tierGroup = 'first-payment';
                }

                app('referral_api_public')->createConversion(
                    $payment->user->click_id,
                    $payment->user->email,
                    $payment->total,
                    'tier',
                    $tierGroup
                );
            }
        } catch (\Exception $exception) {
            Log::error("Payment Observer: " . $exception->getMessage());
            return;
        }
    }

}
