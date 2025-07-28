<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\PackagedService;
use App\Models\ShopifyProductUpdate;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\ReviewRequestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function featuredServicesAndExperts(): JsonResponse
    {
        $featuredServices = PackagedService::query()
            ->where('is_featured', true)
            ->get();

        $featuredExperts = User::query()
            ->where('is_featured_expert', true)
            ->with(['profile', 'reviews'])
            ->get()
            ->map(function ($expert) {
                $reviews = $expert->reviews;

                $expert->reviews_stat = [
                    'rating' => round($reviews->avg('rate') ?? 0, 2),
                    'reviews_count' => $reviews->count(),
                ];

                return $expert;
            });

        $shopifyProductUpdates = ShopifyProductUpdate::query()
            ->orderByDesc('published_at')
            ->get();

        return response()->json([
            'featured_services' => $featuredServices,
            'featured_experts' => $featuredExperts,
            'shopify_product_updates' => $shopifyProductUpdates,
        ]);
    }

    /**
     * @param ProjectRepository $projectRepo
     * @return JsonResponse
     */
    public function latestRequests(ProjectRepository $projectRepo): JsonResponse
    {
        $requests = $projectRepo->getRequestsForClient(5);

        return response()->json([
            'latest_requests' => $requests
        ]);
    }
}
