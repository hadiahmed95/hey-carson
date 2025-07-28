<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\PackagedService;
use Illuminate\Http\JsonResponse;

class PackagedServiceController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function packagedServices(): JsonResponse
    {
        $packagedServices = PackagedService::query()
            ->get();

        return response()->json([
            'packaged_services' => $packagedServices,
        ]);
    }
}
