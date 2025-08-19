<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Repositories\ReviewRepository;
use App\Models\Review;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class ReviewController
 * 
 * Handles admin operations for reviews including filtering, status updates,
 * and data retrieval for the admin dashboard.
 * 
 * @package App\Http\Controllers\NewDashboard\Admin
 */
class ReviewController extends Controller
{
    /**
     * ReviewController constructor.
     * 
     * @param ReviewRepository $reviewRepository Repository for review operations
     */
    public function __construct(
        private ReviewRepository $reviewRepository
    ) {}

    /**
     * Get available filter options for reviews.
     * 
     * Retrieves all available filter options including statuses, ratings,
     * recommendations, project values, and review sources.
     * 
     * @return JsonResponse Filter options data or error response
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->reviewRepository->getReviewsFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all reviews with filtering and pagination.
     * 
     * Retrieves a paginated list of reviews based on provided filters.
     * Uses ReviewResource to apply business logic transformations.
     * 
     * @param Request $request The HTTP request containing filter parameters
     * @return JsonResponse Paginated reviews data or error response
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'rating',
                'recommendation',
                'project_value',
                'review_source',
                'per_page'
            ]);

            $reviews = $this->reviewRepository->getReviews($filters);
            
            return response()->json([
                'reviews' => ReviewResource::collection($reviews->items()),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update review status.
     * 
     * Updates the status of a specific review (approve, reject, hide).
     * 
     * @param Request $request The HTTP request containing the new status
     * @param Review $review The review model instance
     * @return JsonResponse Success or error response
     */
    public function updateStatus(Request $request, Review $review): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|string|in:pending,approved,rejected,hidden'
            ]);

            $success = $this->reviewRepository->updateStatus($review->id, $request->status);
            
            if ($success) {
                return response()->json([
                    'message' => 'Review status updated successfully',
                    'review' => new ReviewResource($review->fresh())
                ]);
            }

            return response()->json(['error' => 'Failed to update review status'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}