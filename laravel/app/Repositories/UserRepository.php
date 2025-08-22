<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Constants\Constants;
use App\Models\Role;
use App\Models\ServiceCategory;
use App\Models\User;

class UserRepository
{

    private int $paginationLimit;

    /**
     * Class constructor.
     *
     * Initializes the default pagination limit using the constant defined in the Constants class.
     */
    public function __construct(private User $model)
    {
        $this->paginationLimit = Constants::DEFAULT_PAGINATION_LIMIT;
    }

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByIdWithRelations(int $id, array $relations = []): ?User
    {
        /** @var User|null $user */
        $user = $this->model->query()->with($relations)->find($id);
        return $user;
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function refresh(User $user): User
    {
        return $user->refresh();
    }

    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user.
     * @return User The user instance.
     */
    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by their partner_id.
     *
     * @param string $partnerId
     * @return Collection The user instance.
     */
    public function findUsersByPartnerId(string $partnerId): Collection
    {
        return User::where('partner_id', $partnerId)->pluck('id');
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
    public function getListingsFilterOptions(): array
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

            // Apply search filter
            if (!empty($filters['search'])) {
                $query = $this->applySearchFilter($query, $filters['search']);
            }

            // Apply status filter through profile relationship
            if (!empty($filters['status'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('status', $filters['status']);
                });
            }

            // Apply role filter through profile relationship
            if (!empty($filters['role'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('role', $filters['role']);
                });
            }

            // Apply country and city filters through profile relationship
            if (!empty($filters['country']) || !empty($filters['city'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    // If city is provided (format: "City, Country"), use exact match
                    if (!empty($filters['city'])) {
                        $q->where('country', '=', $filters['city']);
                    }
                    // If only country is provided, use LIKE search
                    elseif (!empty($filters['country'])) {
                        $q->where('country', 'LIKE', '%' . $filters['country'] . '%');
                    }
                });
            }

            // Apply language filter through profile relationship
            if (!empty($filters['language'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('eng_level', $filters['language']);
                });
            }

            // Apply userType filter (directly on users table)
            if (!empty($filters['userType'])) {
                $query->where('usertype', $filters['userType']);
            }

            // Apply expertType filter through profile relationship
            if (!empty($filters['expertType'])) {
                $query->whereHas('profile', function ($q) use ($filters) {
                    $q->where('expert_type', $filters['expertType']);
                });
            }

            // Apply serviceCategory filter through serviceCategories relationship
            if (!empty($filters['serviceCategory'])) {
                $query->whereHas('serviceCategories', function ($q) use ($filters) {
                    $q->where('name', $filters['serviceCategory']);
                });
            }

            // Apply shopifyPlan filter (directly on users table)
            if (!empty($filters['shopifyPlan'])) {
                $query->where('shopify_plan', $filters['shopifyPlan']);
            }

            // Apply hourly rate filters through profile relationship
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
            return $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$search}%"]);
        } catch (Exception $e) {
            throw new Exception('Failed to apply search filter: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of an expert (activate or deactivate) and return updated expert.
     *
     * Finds the expert by ID (including soft-deleted ones) and updates the related profile's status
     * based on the specified action. Returns the updated expert with relationships loaded.
     *
     * @param int $expertId The ID of the expert to update
     * @param string $action The action to perform ('activate' or 'deactivate')
     * @return User The updated expert with relationships loaded
     *
     * @throws \Exception If updating expert status fails or expert not found
     */
    public function updateExpertStatus(int $expertId, string $action): User
    {
        try {
            $expert = User::with(['profile', 'serviceCategories', 'reviews', 'activeAssignments'])
                ->where('id', $expertId)
                ->firstOrFail();

            $newStatus = $action === 'activate' ? 'active' : 'inactive';
            
            if ($expert->profile) {
                $expert->profile->update(['status' => $newStatus]);
                $expert->load('profile');
            } else {
                throw new Exception('Expert profile not found');
            }

            return $expert;       
        } catch (Exception $e) {
            throw new Exception('Failed to update expert status: ' . $e->getMessage());
        }
    }


    /* Create a new user
     *
     * @param array $data
     * @return User
     */
    public function createUserForReview(array $data): User
    {
        $nameParts = explode(' ', trim($data['full_name']), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $clientRoleId = Role::where('name', 'client')->first()->id;

        return User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $data['email'],
            'password' => Hash::make('password123'), // Default password
            'role_id' => $clientRoleId,
            'url' => $data['website'] ?? null,
            //'company_name' => $data['company_name'] ?? null, //Todo: Needs to add company_name in the users table
            'is_hired_on_shopexperts' => $data['is_hired_on_shopexperts'] ?? false,
        ]);
    }

    /**
     * Format user data for response
     *
     * @param User $user
     * @return array
     */
    public function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'full_name' => trim($user->first_name . ' ' . $user->last_name),
            'email' => $user->email,
            'website' => $user->url ?? '',
            'company_name' => $user->company_name ?? '',
            'is_hired_on_shopexperts' => $user->is_hired_on_shopexperts ?? false,
        ];
    }

    /**
     * Search users by full name
     *
     * @param string $search
     * @param int $limit
     * @return Collection
     */
    public function searchByName(string $search, int $limit = 20): Collection
    {
        if (empty($search)) {
            return collect([]);
        }

        return User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
            ->where('role_id', Role::CLIENT)
            ->select('id', 'first_name', 'last_name', 'email', 'url')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'website' => $user->url ?? '',
                ];
            });
    }
}
