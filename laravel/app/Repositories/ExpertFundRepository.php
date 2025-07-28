<?php

namespace App\Repositories;

use App\Models\ExpertFund;

class ExpertFundRepository
{
    /**
     * Get the total earnings for a user
     *
     * @param int $userId
     * @return float
     */
    public function getTotalEarnings(int $userId): float
    {
        $expertFunds = ExpertFund::query()->where('user_id', $userId);

        return $expertFunds->latest()->exists() ? $expertFunds->sum('total') : 0;
    }

    /**
     * Get the total balance for a user
     *
     * @param int $userId
     * @return float
     */
    public function getTotalBalance(int $userId): float
    {
        $expertFunds = ExpertFund::query()->where('user_id', $userId);

        return $expertFunds->latest()->exists() ? $expertFunds->sum('amount') : 0;
    }

    /**
     * Get the current balance for a user
     *
     * @param int $userId
     * @param float $withdrawAmount
     * @return float
     */
    public function getCurrentBalance(int $userId, float $withdrawAmount): float
    {
        $totalBalance = $this->getTotalBalance($userId);
        return $totalBalance - $withdrawAmount;
    }
}
