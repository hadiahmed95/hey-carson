<?php

namespace App\Observers;

use App\Models\Offer;
use Carbon\Carbon;

class OfferObserver
{
    /**
     * Handle the Offer "creating" event.
     */
    public function creating(Offer $offer): void
    {
        if (!$offer->status_updated_at) {
            $offer->status_updated_at = Carbon::now();
        }
    }

    /**
     * Handle the Offer "updating" event.
     */
    public function updating(Offer $offer): void
    {
        if ($offer->status !== $offer->getOriginal('status')) {
            $offer->status_updated_at = Carbon::now();
        }
    }
}
