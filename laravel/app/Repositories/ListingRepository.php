<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\Role;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class AdminListingRepository
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
     * Retrieve a paginated list of experts based on the provided filters.
     *
     * Applies filtering, eager loads related models, and paginates the result.
     *
     * @param array $filters Filters to apply (e.g., per_page, search, status).
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated list of experts with related data.
     *
     * @throws \Exception If fetching experts fails.
     */
    public function getExperts(array $filters): LengthAwarePaginator
    {
        try {
            $query = $this->buildExpertsQuery($filters);
            $perPage = $filters['per_page'] ?? $this->paginationLimit;
            
            return $query->with(['profile', 'reviews', 'serviceCategories', 'activeAssignments'])
                        ->latest()
                        ->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Failed to fetch experts: ' . $e->getMessage());
        }
    }

    /**
     * Get the total count of experts based on the provided filters.
     *
     * Uses the same filtering logic as getExperts to ensure consistency in results.
     *
     * @param array $filters Filters to apply when counting experts.
     * @return int The total number of experts matching the filters.
     *
     * @throws \Exception If counting experts fails.
     */
    public function getExpertsCount(array $filters): int
    {
        try {
            return $this->buildExpertsQuery($filters)->count('id');
        } catch (Exception $e) {
            throw new Exception('Failed to count experts: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve available filter options for experts listing.
     *
     * Gathers distinct values for various expert attributes such as status, role, country,
     * language level, user type, expert type, service categories, Shopify plans, and
     * city-country mapping based on expert profiles.
     *
     * @return array An associative array containing filter options:
     *               - statuses
     *               - roles
     *               - countries
     *               - citiesByCountry
     *               - languages
     *               - userTypes
     *               - expertTypes
     *               - serviceCategories
     *               - shopifyPlans
     *
     * @throws \Exception If retrieving filter options fails.
     */
    public function getFilterOptions(): array
    {
        try {
            $experts = User::where('role_id', Role::EXPERT)->with('profile')->get();

            $statuses = $experts->pluck('profile.status')
                ->filter()
                ->unique()
                ->map(fn($status) => ucfirst($status))
                ->sort()
                ->values();

            $roles = $experts->pluck('profile.role')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $languages = $experts->pluck('profile.eng_level')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $userTypes = $experts->pluck('usertype')
                ->filter()
                ->unique()
                ->map(fn($type) => ucfirst($type))
                ->sort()
                ->values();

            $expertTypes = $experts->pluck('profile.expert_type')
                ->filter()
                ->unique()
                ->map(fn($type) => ucfirst($type))
                ->sort()
                ->values();

            $locationData = $experts->pluck('profile.country')
                ->filter()
                ->map(function ($location) {
                    if (strpos($location, ',') !== false) {
                        $parts = array_map('trim', explode(',', $location));
                        return [
                            'city' => $parts[0] ?? '',
                            'country' => $parts[1] ?? '',
                            'full' => $location
                        ];
                    }
                    return [
                        'city' => '',
                        'country' => $location,
                        'full' => $location
                    ];
                })
                ->filter(fn($item) => !empty($item['country']));

            $countries = $locationData->pluck('country')
                ->unique()
                ->sort()
                ->values();

            $citiesByCountry = $locationData->groupBy('country')
                ->map(fn($locations) => $locations->pluck('city')->filter()->unique()->sort()->values())
                ->filter(fn($cities) => $cities->isNotEmpty());

            $serviceCategories = ServiceCategory::orderBy('name')->pluck('name');

            $shopifyPlans = $experts->pluck('shopify_plan')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            return [
                'statuses' => $statuses,
                'roles' => $roles,
                'countries' => $countries,
                'citiesByCountry' => $citiesByCountry,
                'languages' => $languages,
                'userTypes' => $userTypes,
                'expertTypes' => $expertTypes,
                'serviceCategories' => $serviceCategories,
                'shopifyPlans' => $shopifyPlans
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to fetch filter options: ' . $e->getMessage());
        }
    }

    /**
     * Build the base query for retrieving experts using provided filters.
     *
     * Applies various optional filters such as search term, status, role, country, city,
     * language, user type, expert type, service category, Shopify plan, and hourly rate range.
     *
     * This method is intended to be used internally by other methods like getExperts()
     * and getExpertsCount().
     *
     * @param array $filters Associative array of filters to apply:
     *                       - search
     *                       - status
     *                       - role
     *                       - country
     *                       - city
     *                       - language
     *                       - userType
     *                       - expertType
     *                       - serviceCategory
     *                       - shopifyPlan
     *                       - hourlyRateMin
     *                       - hourlyRateMax
     *
     * @return \Illuminate\Database\Eloquent\Builder The Eloquent query builder instance.
     *
     * @throws \Exception If building the query fails.
     */
    private function buildExpertsQuery(array $filters)
    {
        try {
            $query = User::where('role_id', Role::EXPERT);

            if (!empty($filters['search'])) {
                $this->applySearchFilter($query, $filters['search']);
            }

            if (!empty($filters['status'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('status', $filters['status']);
                });
            }

            if (!empty($filters['role'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('role', $filters['role']);
                });
            }

            if (!empty($filters['country'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('country', 'LIKE', '%' . $filters['country'] . '%');
                });
            }

            if (!empty($filters['city'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('country', 'LIKE', $filters['city'] . '%');
                });
            }

            if (!empty($filters['language'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('eng_level', $filters['language']);
                });
            }

            if (!empty($filters['userType'])) {
                $query->where('usertype', strtolower($filters['userType']));
            }

            if (!empty($filters['expertType'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('expert_type', strtolower($filters['expertType']));
                });
            }

            if (!empty($filters['serviceCategory'])) {
                $query->whereHas('serviceCategories', function ($q) use ($filters) {
                    $q->where('name', $filters['serviceCategory']);
                });
            }

            if (!empty($filters['shopifyPlan'])) {
                $query->where('shopify_plan', $filters['shopifyPlan']);
            }

            if (!empty($filters['hourlyRateMin']) || !empty($filters['hourlyRateMax'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    if (!empty($filters['hourlyRateMin'])) {
                        $q->where('hourly_rate', '>=', $filters['hourlyRateMin']);
                    }
                    if (!empty($filters['hourlyRateMax'])) {
                        $q->where('hourly_rate', '<=', $filters['hourlyRateMax']);
                    }
                });
            }

            return $query;
        } catch (Exception $e) {
            throw new Exception('Failed to build experts query: ' . $e->getMessage());
        }
    }

    /**
     * Apply search filter to the experts query.
     *
     * Searches by first name, last name, or email. If the search string contains a space,
     * it attempts to split it into first and last names to match against both fields
     * in multiple combinations (first-last, last-first).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query  The query builder instance to modify.
     * @param string $search The search keyword or phrase.
     *
     * @return \Illuminate\Database\Eloquent\Builder Modified query builder with applied search filters.
     *
     * @throws \Exception If applying the search filter fails.
     */
    private function applySearchFilter($query, string $search)
    {
        try {
            return $query->where(function ($query) use ($search) {
                if (strpos($search, ' ') !== false) {
                    $searchTerms = array_filter(explode(' ', trim($search)));

                    if (count($searchTerms) >= 2) {
                        $firstName = $searchTerms[0];
                        $lastName = $searchTerms[1];

                        $query->where(function ($nameQuery) use ($firstName, $lastName, $search) {
                            $nameQuery->where(function ($q) use ($firstName, $lastName) {
                                $q->where('first_name', 'LIKE', "%{$firstName}%")
                                    ->where('last_name', 'LIKE', "%{$lastName}%");
                            })
                                ->orWhere(function ($q) use ($firstName, $lastName) {
                                    $q->where('first_name', 'LIKE', "%{$lastName}%")
                                        ->where('last_name', 'LIKE', "%{$firstName}%");
                                })
                                ->orWhere('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        });
                    } else {
                        $query->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    }
                } else {
                    $query->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                }
            });
        } catch (Exception $e) {
            throw new Exception('Failed to apply search filter: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of an expert (activate or deactivate).
     *
     * Finds the expert by ID (including soft-deleted ones) and updates the related profile's status
     * based on the specified action.
     *
     * @param int $expertId The ID of the expert whose status is to be updated.
     * @param string $action The action to perform: 'activate' or 'deactivate'.
     *
     * @return void
     *
     * @throws \Exception If the expert is not found or the status update fails.
     */
    public function updateExpertStatus(int $expertId, string $action): void
    {
        try {
            $expert = User::where('role_id', Role::EXPERT)
                ->withTrashed()
                ->findOrFail($expertId);
            
            if ($action === 'activate') {
                $expert->profile->update(['status' => 'active']);
            } elseif ($action === 'deactivate') {
                $expert->profile->update(['status' => 'inactive']);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to update expert status: ' . $e->getMessage());
        }
    }
}
