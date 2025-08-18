<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class LeadController
 * 
 * Handles admin operations for lead management including filtering, data retrieval,
 * and pagination for the admin dashboard leads section.
 * 
 * @package App\Http\Controllers\NewDashboard\Admin
 */
class LeadController extends Controller
{
    /**
     * LeadController constructor.
     * 
     * @param UserRepository $userRepository Repository for user operations
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Get available filter options for leads.
     * 
     * Retrieves available filter options including Shopify plans for leads filtering.
     * 
     * @return JsonResponse Filter options data or error response
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->userRepository->getLeadsFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all leads with filtering and pagination.
     * 
     * Retrieves a paginated list of leads (users with CLIENT role) based on provided filters.
     * Uses LeadResource to apply business logic transformations.
     * 
     * @param Request $request HTTP request containing filter parameters
     * @return JsonResponse Paginated leads data or error response
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'page',
                'per_page',
                'search',
                'shopify_plan'
            ]);

            $leads = $this->userRepository->getLeads($filters);

            return response()->json([
                'leads' => LeadResource::collection($leads)->response()->getData(),
                'leads_count' => $leads->total()
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}