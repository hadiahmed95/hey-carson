<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PayoutsController extends Controller
{
    public function create(Request $request): JsonResponse {
        $user = Auth::user();

        $createPayout = Payout::create([
            'user_id' => $user->id,
            'amount' => (float) $request->get('amount'),
            'type' => (string) $request->get('type')
        ]);

        return response()->json([
            'message' => 'Payout created successfully.',
            'payout' => $createPayout
        ]);
    }
}
