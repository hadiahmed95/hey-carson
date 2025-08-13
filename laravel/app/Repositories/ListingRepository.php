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

    public function __construct()
    {
        $this->paginationLimit = Constants::DEFAULT_PAGINATION_LIMIT;
    }

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

    public function getExpertsCount(array $filters): int
    {
        try {
            return $this->buildExpertsQuery($filters)->count('id');
        } catch (Exception $e) {
            throw new Exception('Failed to count experts: ' . $e->getMessage());
        }
    }

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
