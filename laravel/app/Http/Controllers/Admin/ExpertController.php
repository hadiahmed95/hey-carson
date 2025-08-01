<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendEmail;
use App\Http\Controllers\Controller;
use App\Mail\Expert\ApprovedMail;
use App\Mail\Expert\DeclinedMail;
use App\Models\Assignment;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Repositories\ExpertFundRepository;
use App\Repositories\PayoutRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ExpertUserService;
use Illuminate\Support\Facades\DB;

class ExpertController extends Controller
{
    private ExpertUserService $expertUserService;
    private PayoutRepository $payoutRepository;
    private ExpertFundRepository $expertFundRepository;
    public function __construct(ExpertUserService $expertUserService, PayoutRepository $payoutRepository, ExpertFundRepository $expertFundRepository)
    {
        $this->expertUserService = $expertUserService;
        $this->payoutRepository = $payoutRepository;
        $this->expertFundRepository = $expertFundRepository;
    }

    /**
     * Get filter options for experts listing
     */
    public function getFilterOptions()
    {
        try {
            // Get all experts with their profiles in one query
            $experts = User::where('role_id', 3)
                ->with('profile')
                ->get();

            // Extract unique values using collections
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
                ->map(function($location) {
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

            // Get unique countries
            $countries = $locationData->pluck('country')
                ->unique()
                ->sort()
                ->values();

            // Group cities by country
            $citiesByCountry = $locationData->groupBy('country')
                ->map(fn($locations) => $locations->pluck('city')->filter()->unique()->sort()->values())
                ->filter(fn($cities) => $cities->isNotEmpty());

            $serviceCategories = ServiceCategory::orderBy('name')->pluck('name');

            // Add Shopify Plans filter
            $shopifyPlans = $experts->pluck('shopify_plan')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            return response()->json([
                'statuses' => $statuses,
                'roles' => $roles,
                'countries' => $countries,
                'citiesByCountry' => $citiesByCountry,
                'languages' => $languages,
                'userTypes' => $userTypes,
                'expertTypes' => $expertTypes,
                'serviceCategories' => $serviceCategories,
                'shopifyPlans' => $shopifyPlans
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch filter options'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function all(Request $request)
    {
        $search = $request->get('search');
        $user_status = $request->get('status');
        $city = $request->get('city');
        $usertype = $request->get('usertype');
        $expert_type = $request->get('expert_type');
        $service_category = $request->get('service_category');
        $shopify_plan = $request->get('shopify_plan');
        $filters = $request->get('filter');
        $version = $request->get('version', 'v1');

        $experts = User::query()->where('role_id', 3)->with(['profile', 'reviews', 'serviceCategories', 'activeAssignments']);

        if ($search) {
            $experts = $experts->where(function($query) use ($search) {
                // Check if search contains space (likely first name + last name)
                if (strpos($search, ' ') !== false) {
                    $searchTerms = array_filter(explode(' ', trim($search)));
                    
                    if (count($searchTerms) >= 2) {
                        $firstName = $searchTerms[0];
                        $lastName = $searchTerms[1];
                        
                        $query->where(function($nameQuery) use ($firstName, $lastName, $search) {
                            // Try: "first_name last_name" combination
                            $nameQuery->where(function($q) use ($firstName, $lastName) {
                                $q->where('first_name', 'LIKE', "%{$firstName}%")
                                ->where('last_name', 'LIKE', "%{$lastName}%");
                            })
                            // Try: "last_name first_name" combination  
                            ->orWhere(function($q) use ($firstName, $lastName) {
                                $q->where('first_name', 'LIKE', "%{$lastName}%")
                                ->where('last_name', 'LIKE', "%{$firstName}%");
                            })
                            // Fallback: original full string search
                            ->orWhere('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                        });
                    } else {
                        // Single word after split - treat as original
                        $query->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    }
                } else {
                    // Single word search - original logic
                    $query->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                }
            });
        }

        // Status filter
        if ($user_status) {
            $experts = $experts->whereHas('profile', function($query) use ($user_status) {
                $query->where('status', $user_status);
            });
        }

        // City filter (full "City, Country" string)
        if ($city) {
            $experts = $experts->whereHas('profile', function($query) use ($city) {
                $query->where('country', $city);
            });
        }

        // Plan filter (usertype)
        if ($usertype) {
            $experts = $experts->where('usertype', $usertype);
        }

        // Expert type filter
        if ($expert_type) {
            $experts = $experts->whereHas('profile', function($query) use ($expert_type) {
                $query->where('expert_type', $expert_type);
            });
        }

        // Service category filter
        if ($service_category) {
            $experts = $experts->whereHas('serviceCategories', function($query) use ($service_category) {
                $query->where('service_categories.name', $service_category);
            });
        }

        // Shopify plan filter
        if ($shopify_plan) {
            $experts = $experts->where('shopify_plan', $shopify_plan);
        }

        if ($filters) {
            $experts = $experts->whereHas('profile', function ($query) use ($filters) {
                foreach($filters as $key => $filter) {
                    if (isset($filter['from']) || isset($filter['to'])) {
                        if (!isset($filter['from'])) {
                            $query->where($key, '<=', $filter['to']);
                        } elseif (!isset($filter['to'])) {
                            $query->where($key, '>=', $filter['from']);
                        } else {
                            $query->whereBetween($key, $filter);
                        }
                    } else {
                        $query->where($key, $filter);
                    }
                }
            });
        }

        if ($version === 'v2' && $request->has('role_filter')) {
            $experts->whereHas('profile', function($query) use ($request) {
                $query->where('role', $request->get('role_filter'));
            });
        }

        $expertsCount = $experts->count('id');
        $experts = $experts->latest()->paginate(15);

        $experts->getCollection()->transform(function ($expert) use ($version) {
            $totalEarnings = $this->expertFundRepository->getTotalEarnings($expert->id);
            $expert->totalEarnings = $totalEarnings;
            
            // Ensure services_offered is always an array for backward compatibility
            $expert->services_offered = $expert->serviceCategories ? $expert->serviceCategories->pluck('name')->toArray() : [];
            
            if ($version === 'v2') {
                $expert->display_name = $expert->first_name . ' ' . $expert->last_name;
                $expert->avatar_url = $expert->photo ? asset('storage/' . $expert->photo) : null;
                $expert->hourly_rate_formatted = '$' . number_format($expert->profile->hourly_rate ?? 0, 2) . '/hour';
                
                $expert->account_type = 'freelancer';
                $expert->company_type = 'Individual';
                
                $expert->status_info = [
                    'status' => $expert->profile->status ?? 'pending',
                    'updated_at' => $expert->updated_at ? $expert->updated_at->format('j M, Y') : 'N/A',
                ];

                $expert->stats = [
                    'total_reviews' => $expert->reviews ? $expert->reviews->count() : 0,
                    'average_rating' => $expert->reviews && $expert->reviews->count() > 0 ? round($expert->reviews->avg('rate'), 1) : 0,
                    'total_projects' => $expert->activeAssignments ? $expert->activeAssignments->count() : 0,
                ];
            }

            return $expert;
        });

        return response()->json([
            'experts' => $experts,
            'experts_count' => $expertsCount
        ]);
    }

    public function show(Request $request, User $user)
    {
        $user->load(['profile', 'reviews', 'reviews.project', 'reviews.client', 'activeAssignments.project' => function ($query) {
            $query->withTrashed();
        }, 'activeAssignments.project.client', 'activeAssignments.project.activeAssignment.expert' => function ($query) {
            $query->withTrashed();
        }, 'activeAssignments.project.activeAssignment.expert.profile']);

        $withdrawAmount = $this->payoutRepository->getWithdrawAmount($user->id);

        $totalEarnings = $this->expertFundRepository->getTotalEarnings($user->id);

        $currentBalance = $this->expertFundRepository->getCurrentBalance($user->id, $withdrawAmount);

        return response()->json([
            'expert' => $user,
            'totalEarning' => $totalEarnings,
            'currentBalance' => $currentBalance
        ]);
    }

    public function update(Request $request, User $user)
    {

        if ($request->get('status') === 'active') {
            $this->expertUserService->activate($user->id);
            SendEmail::dispatch($user, new ApprovedMail($user));
        }

        if ($request->get('status') === 'inactive') {
            $this->expertUserService->deactivate($user->id);
            SendEmail::dispatch($user, new DeclinedMail($user));
        }

        return response()->json(['message' => 'OK']);
    }
}
