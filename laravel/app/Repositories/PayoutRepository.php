<?php

namespace App\Repositories;

use App\Models\Payout;

class PayoutRepository
{
    /**
     * Get the total withdrawn amount for a user
     *
     * @param int $userId
     * @return float
     */
    public function getWithdrawAmount(int $userId): float
    {
        return Payout::query()
            ->where('user_id', $userId)
            ->whereNot('status', 'declined')
            ->sum('amount');
    }
}
