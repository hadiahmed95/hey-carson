<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\User;

class PaymentRepository
{
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
