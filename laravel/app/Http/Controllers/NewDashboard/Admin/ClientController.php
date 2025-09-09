<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class ClientController
 * 
 * Handles admin operations for client management including filtering, data retrieval,
 * and pagination for the admin dashboard clients section.
 * 
 * @package App\Http\Controllers\NewDashboard\Admin
 */
class ClientController extends Controller
{
    /**
     * ClientController constructor.
     * 
     * @param UserRepository $userRepository Repository for user operations
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Get available filter options for clients.
     * 
     * Retrieves available filter options including Shopify plans for clients filtering.
     * 
     * @return JsonResponse Filter options data or error response
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->userRepository->getClientsFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all clients with filtering and pagination.
     * 
     * Retrieves a paginated list of clients (users with CLIENT role) based on provided filters.
     * Uses ClientResource to apply business logic transformations.
     * 
     * @param Request $request HTTP request containing filter parameters
     * @return JsonResponse Paginated clients data or error response
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

            $clients = $this->userRepository->getClients($filters);

            // Transform using resource class (same pattern as ListingController)
            $transformedClients = $clients->setCollection(
                $clients->getCollection()->map(function ($client) {
                    return new ClientResource($client);
                })
            );

            return response()->json([
                'clients' => $transformedClients,
                'clients_count' => $clients->total()
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}