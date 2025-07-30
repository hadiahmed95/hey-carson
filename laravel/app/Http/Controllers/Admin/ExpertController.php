<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendEmail;
use App\Http\Controllers\Controller;
use App\Mail\Expert\ApprovedMail;
use App\Mail\Expert\DeclinedMail;
use App\Models\Assignment;
use App\Models\User;
use App\Repositories\ExpertFundRepository;
use App\Repositories\PayoutRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ExpertUserService;

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
    
    public function all(Request $request)
    {
        $search = $request->get('search');
        $filters = $request->get('filter');
        $status = $request->get('status');
        $period = $request->get('period');
        $projectId = $request->get('projectId');
        $date = null;

        $version = $request->get('version', 'v1');

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $experts = User::query()->where('role_id', 3)
            ->with([
                'profile',
                'reviews',
                'activeAssignments.project' => function ($query) {
                    $query->withTrashed();
                },
            ])->withTrashed();

        if ($date) {
            $experts = $experts->whereDate('created_at', '>', $date);
        }

        if ($status) {
            $experts = $experts->whereHas('profile', function($query) {
                $query->where('status', 'active');
            });
        }

        if ($projectId) {
            $currentAssignedExperts = Assignment::query()
                ->where('project_id', $projectId)
                ->where('is_active', true)
                ->pluck('expert_id');

            if ($currentAssignedExperts) {
                $experts = $experts->whereNotIn('id', $currentAssignedExperts);
            }
        }

        if ($search) {
            $experts = $experts->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$search}%"]);
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
            
            if ($version === 'v2') {
                $expert->display_name = $expert->first_name . ' ' . $expert->last_name;
                $expert->avatar_url = $expert->photo ? asset('storage/' . $expert->photo) : null;
                $expert->hourly_rate_formatted = '$' . number_format($expert->profile->hourly_rate ?? 0, 2) . '/hour';
                
                $expert->account_type = 'freelancer';
                $expert->company_type = 'Individual';
                $expert->services_offered = [];
                
                $expert->status_info = [
                    'status' => $expert->profile->status ?? 'pending',
                    'updated_at' => $expert->updated_at ? $expert->updated_at->format('j M, Y') : 'N/A',
                ];

                $expert->stats = [
                    'total_reviews' => $expert->reviews ? $expert->reviews->count() : 0,
                    'average_rating' => $expert->reviews && $expert->reviews->count() > 0 ? round($expert->reviews->avg('rate'), 1) : 0,  // ✅ Correct column
                    'total_projects' => $expert->activeAssignments ? $expert->activeAssignments->count() : 0,
                ];
            }
            
            return $expert;
        });

        $response = [
            'experts' => $experts,
            'experts_count' => $expertsCount
        ];

        if ($version === 'v2') {
            $response['meta'] = [
                'version' => 'v2',
                'total_pages' => $experts->lastPage(),
                'current_page' => $experts->currentPage(),
                'per_page' => $experts->perPage(),
                'total_items' => $expertsCount,
            ];
            
            $response['available_filters'] = [
                'status' => ['pending', 'active', 'inactive'],
                'roles' => $this->getAvailableRoles(),
                'experience_levels' => $this->getExperienceLevels(),
                'english_levels' => $this->getEnglishLevels(),
            ];
        }

        return response()->json($response);
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

    private function getAvailableRoles()
    {
        return [
            'developer',
            'designer', 
            'consultant',
            'marketing_specialist',
            'project_manager'
        ];
    }

    private function getExperienceLevels()
    {
        return [
            '0-1 years',
            '1-3 years', 
            '3-5 years',
            '5-10 years',
            '10+ years'
        ];
    }

    private function getEnglishLevels()
    {
        return [
            'basic',
            'intermediate',
            'advanced',
            'native'
        ];
    }
}
