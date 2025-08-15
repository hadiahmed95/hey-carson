<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpertResource;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class ListingController
 * 
 * Handles admin operations for expert listings including filtering, status updates,
 * and data retrieval for the admin dashboard.
 * 
 * @package App\Http\Controllers\NewDashboard\Admin
 */
class ListingController extends Controller
{
    /**
     * ListingController constructor.
     * 
     * @param UserRepository $userRepository Repository for user operations
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Get available filter options for expert listings.
     * 
     * Retrieves all available filter options including statuses, roles, countries,
     * languages, user types, expert types, service categories, and Shopify plans.
     * 
     * @return JsonResponse Filter options data or error response
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->userRepository->getListingsFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all experts with filtering and pagination.
     * 
     * Retrieves a paginated list of experts based on provided filters.
     * Uses ExpertResource to apply business logic transformations.
     * 
     * @param Request $request HTTP request containing filter parameters
     * @return JsonResponse Paginated experts data with business metrics or error response
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'role',
                'country',
                'city',
                'language',
                'userType',
                'expertType',
                'serviceCategory',
                'shopifyPlan',
                'hourlyRateMin',
                'hourlyRateMax',
                'page',
                'per_page'
            ]);

            $experts = $this->userRepository->getExperts($filters);
            $expertsCount = $experts->total();

            // Transform using resource class
            $transformedExperts = $experts->setCollection(
                $experts->getCollection()->map(function ($expert) {
                    return new ExpertResource($expert);
                })
            );

            return response()->json([
                'experts' => $transformedExperts,
                'experts_count' => $expertsCount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update expert status (activate/deactivate).
     * 
     * Updates the status of an expert and returns the updated expert data
     * with recalculated business metrics using ExpertResource.
     * 
     * @param Request $request HTTP request containing the action parameter
     * @param User $user The expert user to update
     * @return JsonResponse Updated expert data or error response
     */
    public function updateStatus(Request $request, User $user): JsonResponse
    {
        try {
            $action = $request->get('action');
            if (!in_array($action, ['activate', 'deactivate'])) {
                return response()->json(['error' => 'Invalid action'], 400);
            }

            $updatedExpert = $this->userRepository->updateExpertStatus($user->id, $action);
            $expertResource = new ExpertResource($updatedExpert);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'expert' => $expertResource
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}