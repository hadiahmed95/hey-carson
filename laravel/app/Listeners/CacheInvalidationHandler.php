<?php

namespace App\Listeners;

use App\Events\CacheInvalidation;
use App\Services\CacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CacheInvalidationHandler
{
    protected CacheService $cacheService;

    /**
     * @param CacheService $cacheService
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the event.
     */
    public function handle(CacheInvalidation $event): void
    {
        switch ($event->type) {
            case 'user_events':
                $this->cacheService->forgetUserEvents($event->key);
                break;

            case 'cache_duration_key':
                $this->cacheService->forgetKeyByDuration($event->key);
                break;

            case 'cache_key':
                $this->cacheService->forgetKey($event->key);
                break;

            default:
                break;
        }
    }
}
