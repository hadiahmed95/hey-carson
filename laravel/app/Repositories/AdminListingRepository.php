<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class AdminListingRepository
{
    private int $expertRoleId;
    private int $paginationLimit;

    public function __construct()
    {
        $this->expertRoleId = Constants::EXPERT_ROLE_ID;
        $this->paginationLimit = Constants::DEFAULT_PAGINATION_LIMIT;
    }

    public function getExperts(Request $request): LengthAwarePaginator
    {
        try {
            $query = $this->buildExpertsQuery($request);
            return $query->with(['profile', 'reviews', 'serviceCategories', 'activeAssignments'])
                        ->latest()
                        ->paginate($this->paginationLimit);
        } catch (Exception $e) {
            throw new Exception('Failed to fetch experts: ' . $e->getMessage());
        }
    }

    public function getExpertsCount(Request $request): int
    {
        try {
            return $this->buildExpertsQuery($request)->count('id');
        } catch (Exception $e) {
            throw new Exception('Failed to count experts: ' . $e->getMessage());
        }
    }

    public function getFilterOptions(): array
    {
        try {
            $experts = User::where('role_id', $this->expertRoleId)->with('profile')->get();

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

    private function buildExpertsQuery(Request $request)
    {
        try {
            $search = $request->get('search');
            $userStatus = $request->get('status');
            $city = $request->get('city');
            $usertype = $request->get('usertype');
            $expertType = $request->get('expert_type');
            $serviceCategory = $request->get('service_category');
            $shopifyPlan = $request->get('shopify_plan');
            $language = $request->get('eng_level');
            $role = $request->get('role');

            $experts = User::query()->where('role_id', $this->expertRoleId);

            if ($search) {
                $experts = $this->applySearchFilter($experts, $search);
            }

            if ($userStatus) {
                $experts = $experts->whereHas('profile', function ($query) use ($userStatus) {
                    $query->where('status', $userStatus);
                });
            }

            if ($city) {
                $experts = $experts->whereHas('profile', function ($query) use ($city) {
                    $query->where('country', $city);
                });
            }

            if ($usertype) {
                $experts = $experts->where('usertype', $usertype);
            }

            if ($expertType) {
                $experts = $experts->whereHas('profile', function ($query) use ($expertType) {
                    $query->where('expert_type', $expertType);
                });
            }

            if ($serviceCategory) {
                $experts = $experts->whereHas('serviceCategories', function ($query) use ($serviceCategory) {
                    $query->where('service_categories.name', $serviceCategory);
                });
            }

            if ($shopifyPlan) {
                $experts = $experts->where('shopify_plan', $shopifyPlan);
            }

            if ($language) {
                $experts = $experts->whereHas('profile', function ($query) use ($language) {
                    $query->where('eng_level', $language);
                });
            }

            if ($role) {
                $experts = $experts->whereHas('profile', function ($query) use ($role) {
                    $query->where('role', $role);
                });
            }

            return $experts;
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
}
