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
        $period =  $request->get('period');
        $projectId = $request->get('projectId');
        $date = null;

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

        $expertsCount = $experts->count('id');
        $experts = $experts->latest()->paginate(15);

        $experts->getCollection()->transform(function ($expert) {
            $totalEarnings = $this->expertFundRepository->getTotalEarnings($expert->id);
            $expert->totalEarnings = $totalEarnings;
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
