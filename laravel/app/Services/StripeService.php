<?php

namespace App\Services;

use Stripe\StripeClient;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function detachPaymentMethod(string $paymentMethodId): void
    {
        $this->stripe->paymentMethods->detach($paymentMethodId);
    }
}
