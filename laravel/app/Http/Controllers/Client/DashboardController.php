<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientFund;
use App\Models\Message;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        $newEventsCount = Cache::remember(
            CacheService::NEW_EVENTS_COUNT . '_' . $user->id,
            CacheService::TTL_ONE_HOUR,
            function() use ($user) {
                return $user->userEvents()->where('seen', false)->count();
            }
        );

        $projectId = $user->projects()->pluck('id');

        $newMessagesCount = Message::query()
            ->whereIn('project_id', $projectId)
            ->whereNot('user_id', $user->id)
            ->where('seen', 0)->count('id');

        $hours = Cache::remember(
            CacheService::HOURS . '_' . $user->id,
            CacheService::TTL_ONE_HOUR,
            function() use ($user) {
                $clientFunds = ClientFund::query()
                    ->where('user_id', $user->id);

                $prepaidHours = $clientFunds->sum('prepaid_hours');
                $usedHours = $clientFunds->sum('used_hours');

                return $prepaidHours - $usedHours;
            }
        );

        return response()->json([
            'new_events'    => $newEventsCount,
            'new_messages'  => $newMessagesCount,
            'hours'         => $hours
        ]);
    }
}
