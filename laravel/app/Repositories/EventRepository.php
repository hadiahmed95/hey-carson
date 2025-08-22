<?php

namespace App\Repositories;

use App\Models\Role;
use App\Services\CacheService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EventRepository
{
    /**
     * @param string $filterTag
     * @param int $roleId
     * @return mixed
     */
    public function all(string $filterTag, int $roleId): mixed
    {
        $user = Auth::user();
        $loadRelations = [];

        if ($roleId === Role::CLIENT) {
            $loadRelations = ['project.activeAssignment.expert', 'project.activeAssignment.expert.profile', 'event'];
        } elseif ($roleId === Role::EXPERT) {
            $loadRelations = ['project.client', 'event'];
        }

        if ($filterTag === 'All') {
            return Cache::remember(CacheService::ALL_EVENTS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user, $loadRelations) {
                return $user
                    ->userEvents()
                    ->with($loadRelations)
                    ->latest()
                    ->limit(20)
                    ->get();
            });
        }

        return Cache::remember(CacheService::NEW_EVENTS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user, $loadRelations) {
            return $user
                ->userEvents()
                ->with($loadRelations)
                ->where('seen', false)
                ->latest()
                ->get();
        });
    }

    /**
     * @param string $filterTag
     * @param int $roleId
     * @return mixed
     */
    public function userEventsWithRequests(string $filterTag): mixed
    {
        $user = Auth::user();
        $loadRelations = ['project.request', 'event'];

        if ($filterTag === 'All') {
            return Cache::remember(CacheService::ALL_EVENTS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user, $loadRelations) {
                return $user
                    ->userEvents()
                    ->with($loadRelations)
                    ->whereHas('project.request')
                    ->latest()
                    ->limit(20)
                    ->get();
            });
        }

        return Cache::remember(CacheService::NEW_EVENTS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user, $loadRelations) {
            return $user
                ->userEvents()
                ->with($loadRelations)
                ->whereHas('project.request')
                ->where('seen', false)
                ->latest()
                ->get();
        });
    }
}
