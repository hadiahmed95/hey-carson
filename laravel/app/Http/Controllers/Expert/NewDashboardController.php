<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NewDashboardController extends Controller
{
    /**
     * @param ExpertStat $expertStat
     * @return JsonResponse
     */
    public function __invoke(ExpertStat $expertStat): JsonResponse
    {
        $expert = Auth::user();
        $leads = $expert->leads()
            ->with('client')
            ->get();

        return response()->json([
            'expert_stats' => $expertStat,
            'leads' => $leads,
        ]);
    }
}
