<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    const WEEK = 'WEEK';
    const MONTH = 'MONTH';
    const ALL = 'ALL';
    const CLIENTS_COUNT = 'CLIENTS_COUNT';
    const EXPERTS_COUNT = 'EXPERTS_COUNT';
    const PROJECTS_COUNT = 'PROJECTS_COUNT';
    const COMPLETED_PROJECT_COUNT = 'COMPLETED_PROJECT_COUNT';
    const PAYOUTS_COUNT = 'PAYOUTS_COUNT';
    const EXPERT_EARNING = 'EXPERT_EARNING';
    const OUR_COMMISSION = 'OUR_COMMISSION';
    const SALES = 'SALES';
    const TTL_ONE_HOUR = 3600;
    const HOURS = 'HOURS';
    const ALL_EVENTS = 'ALL_EVENTS';
    const NEW_EVENTS = 'NEW_EVENTS';
    const NEW_EVENTS_COUNT = 'NEW_EVENTS_COUNT';
    const REVIEWS = 'REVIEWS';
    const ACTIVE_ASSIGNMENTS = 'ACTIVE_ASSIGNMENTS';
    const WITHDRAW_REQUESTED = 'WITHDRAW_REQUESTED';
    const WITHDRAW_AMOUNT = 'WITHDRAW_AMOUNT';
    const TOTAL_EARNINGS = 'TOTAL_EARNINGS';
    const TOTAL_BALANCE = 'TOTAL_BALANCE';
    const CURRENT_LEVEL = 'CURRENT_LEVEL';


    public function forgetKeyByDuration($key): void
    {
        $this->forgetKey( self::WEEK . '_' . $key);
        $this->forgetKey(self::MONTH . '_' . $key);
        $this->forgetKey(self::ALL . '_' . $key);
    }

    public function forgetKey($key): void
    {
        Cache::forget($key);
    }

    public function forgetUserEvents($userId): void
    {
        $this->forgetKey(self::ALL_EVENTS . '_' . $userId);
        $this->forgetKey(self::NEW_EVENTS . '_' . $userId);
        $this->forgetKey(self::NEW_EVENTS_COUNT . '_' . $userId);
    }
}
