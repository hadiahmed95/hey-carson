<?php

namespace App\Http\Controllers\Expert;

use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Models\ExpertFund;
use App\Models\Message;
use App\Models\Payout;
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
                return $user->userEvents()
                    ->where('seen', false)
                    ->count();
            }
        );

        $projectId = $user->activeAssignments()->pluck('project_id');

        $newMessagesCount = Message::query()
            ->whereIn('project_id', $projectId)
            ->whereNot('user_id', $user->id)
            ->where('seen', 0)->count('id');

        $withdrawAmount = Cache::remember(
            CacheService::WITHDRAW_AMOUNT . '_' . $user->id,
            CacheService::TTL_ONE_HOUR,
            function() use ($user) {
                return Payout::query()
                    ->where('user_id', $user->id)
                    ->whereNot('status', 'declined')
                    ->sum('amount');
            }
        );

        $expertFunds = ExpertFund::query()->where('user_id', $user->id);

        $totalBalance = Cache::remember(
            CacheService::TOTAL_BALANCE . '_' . $user->id,
            CacheService::TTL_ONE_HOUR,
            function() use ($expertFunds) {
                if ($expertFunds->latest()->first()) {
                    return $expertFunds->sum('amount');
                } else {
                    return 0;
                }
            }
        );

        $currentBalance = $expertFunds->latest()->first() ?  $totalBalance - $withdrawAmount : 0;

        return response()->json([
            'new_events' => $newEventsCount,
            'new_messages' => $newMessagesCount,
            'current_balance' => $currentBalance,
        ]);
    }
}
