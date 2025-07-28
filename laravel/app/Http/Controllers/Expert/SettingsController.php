<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertFund;
use App\Models\Payout;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $user = Auth::user();

        $reviews = Cache::remember(CacheService::REVIEWS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user) {
            return Auth::user()->reviews;
        });

        $projects = Cache::remember(CacheService::ACTIVE_ASSIGNMENTS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user) {
            return Auth::user()->activeAssignments->load('project');
        });

        $withdrawRequested = Cache::remember(CacheService::WITHDRAW_REQUESTED . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user) {
            return Payout::query()->where('user_id', $user->id)->where('status', 'created')->sum('amount');
        });

        $withdrawAmount = Cache::remember(CacheService::WITHDRAW_AMOUNT . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user) {
            return Payout::query()->where('user_id', $user->id)->whereNot('status', 'declined')->sum('amount');
        });

        $expertFunds = ExpertFund::query()->where('user_id', $user->id);
        $totalEarnings = Cache::remember(CacheService::TOTAL_EARNINGS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($expertFunds) {
            if ($expertFunds->latest()->first()) {
                return $expertFunds->sum('total');
            } else {
                return 0;
            }
        });

        $totalBalance = Cache::remember(CacheService::TOTAL_BALANCE . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($expertFunds) {
            if ($expertFunds->latest()->first()) {
                return $expertFunds->sum('amount');
            } else {
                return 0;
            }
        });
        $currentBalance = $expertFunds->latest()->first() ?  $totalBalance - $withdrawAmount : 0;

        $currentLevel = Cache::remember(CacheService::CURRENT_LEVEL . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($expertFunds) {
            if ($expertFunds->latest()->first()) {
                return $expertFunds->latest()->first()->expert_level;
            } else {
                return 1;
            }
        });

        return response()->json([
            'profile' => [
                'reviews' => $reviews,
                'projects' => $projects
            ],

            'total_earnings' => $totalEarnings,
            'total_balance' => $totalBalance,
            'current_balance' => $currentBalance,
            'withdraw_requested' => $withdrawRequested,
            'current_level' => $currentLevel,
        ]);
    }
    public function show(Request $request)
    {
        $user = Auth::user()->load('profile');

        return response()->json(['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        if (isset($data['profile'])) {
            $validateUser = Validator::make($data['profile'], [
                'country' => 'sometimes|required|string',
                'about' => 'sometimes|required|string',

                'role' => 'sometimes|required|string',
                'experience' => 'sometimes|required|string',
                'availability' => 'sometimes|required|string',
                'english_level' => 'sometimes|required|string',
                'hourly_rate' => 'sometimes|required|numeric',

                'paypal_email' => 'sometimes|required|email',
                'wise_email' => 'sometimes|required|email',
            ]);
        } else {
            $validateUser = Validator::make($data, [
                'first_name' => 'sometimes|required|string',
                'last_name' => 'sometimes|required|string',
                'email' => 'sometimes|required|email|unique:users,email,'.$user->id.',id',
                'url' => 'sometimes|required|string',
                'project_notifications' => 'sometimes|required|string|in:instant,daily',
                'new_messages' => 'sometimes|required|string|in:instant,daily',
                'timezone' => 'sometimes|required|string',
            ]);
        }

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (isset($data['profile'])) {
            $user->profile->update($data['profile']);
        } else {
            $user->update($data);
        }

        $user->refresh();

        return response()->json(['user' => $user->load('profile')]);
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load([
            'profile',
            'reviews',
            'reviews.client',
            'reviews.project',
            'activeAssignments.project.client',
            'activeAssignments.project.activeAssignment.expert',
            'activeAssignments.project.activeAssignment.expert.profile']);
        return response()->json(['expert' => $user]);
    }
}
