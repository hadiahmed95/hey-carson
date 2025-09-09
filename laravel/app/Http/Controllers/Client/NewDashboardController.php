<?php

namespace App\Http\Controllers\Client;

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
        $client = Auth::user();
        $requests = $client->requests()
            ->with('experts')
            ->get();

        return response()->json([
            'requests'                  => $requests,
            'featured_experts'          => [],
            'shopify_product_updates'   => [],
        ]);
    }
}
