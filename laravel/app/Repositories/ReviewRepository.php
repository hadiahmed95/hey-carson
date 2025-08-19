<?php

namespace App\Repositories;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Constants\Constants;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;

class ReviewRepository
{
    private int $paginationLimit;

    /**
     * Class constructor.
     *
     * Initializes the default pagination limit using the constant defined in the Constants class.
     */
    public function __construct()
    {
        $this->paginationLimit = Constants::DEFAULT_PAGINATION_LIMIT;
    }

    /**
     * Retrieve a paginated list of reviews based on the provided filters.
     *
     * Applies filtering, eager loads related models, and paginates the result.
     *
     * @param array $filters Filters to apply (e.g., per_page, search, status).
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated list of reviews with related data.
     *
     * @throws \Exception If fetching reviews fails.
     */
    public function getReviews(array $filters): LengthAwarePaginator
    {
        try {
            $query = $this->buildReviewsQuery($filters);
            $perPage = $filters['per_page'] ?? $this->paginationLimit;
            
            return $query->with(['client', 'expert'])
                        ->latest()
                        ->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Failed to fetch reviews: ' . $e->getMessage());
        }
    }

    /**
     * Build the query for retrieving reviews based on filters.
     *
     * @param array $filters The filters to apply.
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance.
     */
    private function buildReviewsQuery(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = Review::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('expert', function ($expertQuery) use ($search) {
                      $expertQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply rating filter
        if (!empty($filters['rating'])) {
            $query->where('rate', $filters['rating']);
        }

        // Apply recommendation filter
        if (!empty($filters['recommendation'])) {
            $query->where('recommendation', $filters['recommendation']);
        }

        // Apply project value filter
        if (!empty($filters['project_value'])) {
            $query->where('valueRange', $filters['project_value']);
        }

        // Apply review source filter
        if (!empty($filters['review_source'])) {
            $query->where('review_source', $filters['review_source']);
        }

        return $query;
    }

    /**
     * Retrieve available filter options for reviews.
     *
     * @return array An associative array containing filter options
     *
     * @throws \Exception If retrieving filter options fails.
     */
    public function getReviewsFilterOptions(): array
    {
        try {
            $reviews = Review::all();

            // Format statuses properly
            $statuses = $reviews->pluck('status')
                ->filter()
                ->unique()
                ->map(function($status) {
                    switch($status) {
                        case 'pending': return 'Pending Approval';
                        case 'approved': return 'Published';
                        case 'rejected': return 'Rejected';
                        case 'hidden': return 'Hidden';
                        default: return ucfirst($status);
                    }
                })
                ->sort()
                ->values();

            $ratings = $reviews->pluck('rate')
                ->filter()
                ->unique()
                ->map(function($rating) {
                    return $rating . ' Star' . ($rating != 1 ? 's' : '');
                })
                ->sort()
                ->values();

            // Format recommendations properly
            $recommendations = $reviews->pluck('recommendation')
                ->filter()
                ->unique()
                ->map(function($recommendation) {
                    // Convert snake_case to Title Case (very_likely -> Very Likely)
                    return ucwords(str_replace('_', ' ', strtolower($recommendation)));
                })
                ->sort()
                ->values();

            // Format project values properly
            $projectValues = $reviews->pluck('valueRange')
                ->filter()
                ->unique()
                ->map(function($projectValue) {
                    if (strpos($projectValue, '_') !== false) {
                        $parts = explode('_', $projectValue);
                        if (count($parts) === 2) {
                            $min = is_numeric($parts[0]) ? (float)$parts[0] : 0;
                            $max = is_numeric($parts[1]) ? (float)$parts[1] : 0;
                            return '$' . number_format($min, 0) . '-$' . number_format($max, 0);
                        }
                    }
                    return $projectValue;
                })
                ->sort()
                ->values();

            $reviewSources = $reviews->pluck('review_source')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            return [
                'statuses' => $statuses,
                'ratings' => $ratings,
                'recommendations' => $recommendations,
                'projectValues' => $projectValues,
                'reviewSources' => $reviewSources,
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve review filter options: ' . $e->getMessage());
        }
    }

    /**
     * Update review status.
     *
     * @param int $reviewId The review ID.
     * @param string $status The new status.
     * @return bool
     *
     * @throws \Exception If updating status fails.
     */
    public function updateStatus(int $reviewId, string $status): bool
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->status = $status;
            return $review->save();
        } catch (Exception $e) {
            throw new Exception('Failed to update review status: ' . $e->getMessage());
        }
    }
}