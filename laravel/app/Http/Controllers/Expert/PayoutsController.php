<?php

namespace App\Http\Controllers\Expert;

use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Payout;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutsController extends Controller
{
    public function all(Request $request)
    {
        $user = Auth::user();

        $status =  $request->get('status');

        $payouts = Payout::query()
            ->where('user_id', $user->id);

        if ($status && $status !== 'all') {
            $payouts = $payouts->where('status', $status);
        }

        return response()->json(['payouts' => $payouts->latest()->paginate(15)]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        Payout::create([
            'user_id' => $user->id,
            'amount' => (float) $request->get('amount'),
            'type' => (string) $request->get('type')
        ]);
        CacheInvalidation::dispatch('cache_duration_key', CacheService::PAYOUTS_COUNT);
        CacheInvalidation::dispatch('cache_key', CacheService::WITHDRAW_REQUESTED . '_' . $user->id);
        CacheInvalidation::dispatch('cache_key', CacheService::WITHDRAW_AMOUNT . '_' . $user->id);

        return response()->json(['message' => 'OK']);
    }
}
