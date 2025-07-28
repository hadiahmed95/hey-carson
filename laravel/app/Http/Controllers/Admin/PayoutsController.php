<?php

namespace App\Http\Controllers\Admin;

use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Payout;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutsController extends Controller
{
    public function all(Request $request)
    {
        $status =  $request->get('status');
        $period =  $request->get('period');
        $date = null;

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $payouts = Payout::query()->with(['user' => function($query) {
            $query->withTrashed();
        }]);
        if ($date) {
            $payouts = $payouts->whereDate('created_at', '>', $date);
        }

        if ($status && $status !== 'all') {
            $payouts = $payouts->where('status', $status);
        }

        return response()->json(['payouts' => $payouts->latest()->paginate(15)]);
    }

    public function update(Request $request, Payout $payout)
    {
        $user = Auth::user();
        $status = $request->get('status');

        $payout->update(['status' => $status]);
        CacheInvalidation::dispatch('cache_key', CacheService::WITHDRAW_REQUESTED . '_' . $user->id);
        CacheInvalidation::dispatch('cache_key', CacheService::WITHDRAW_AMOUNT . '_' . $user->id);

        return response()->json(['message' => 'OK']);
    }
}
