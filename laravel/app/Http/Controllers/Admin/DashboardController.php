<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertFund;
use App\Models\MigratedMerchant;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $period = strtoupper($request->get('period'));
        $date = null;

        if ($period === 'WEEK') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'MONTH') {
            $date = Carbon::now()->subMonth();
        }

        $clientCount = Cache::remember( $period . '_' . CacheService::CLIENTS_COUNT, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $clientsQuery = User::query()->where('role_id', 2);
            $clientsQuery = $this->filterDate($clientsQuery, $date);
            return $clientsQuery->count('id');
        });

        $expertCount = Cache::remember($period . '_' . CacheService::EXPERTS_COUNT, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $expertsQuery = User::query()->where('role_id', 3);
            $expertsQuery = $this->filterDate($expertsQuery, $date);
            return $expertsQuery->count('id');
        });

        $projectCount = Cache::remember($period . '_' . CacheService::PROJECTS_COUNT, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $projectsQuery = Project::query();
            $projectsQuery = $this->filterDate($projectsQuery, $date);
            return $projectsQuery->count('id');
        });

        $completeProjectCount = Cache::remember($period . '_' . CacheService::COMPLETED_PROJECT_COUNT, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $projectsQuery = Project::query();
            $projectsQuery = $this->filterDate($projectsQuery, $date);
            return $projectsQuery->where('status', 'completed')->count('id');
        });

        $projectsQuery = Project::query()
            ->with(['client', 'activeAssignment', 'activeAssignment.expert', 'activeAssignment.expert.profile', 'preferredExpert', 'preferredExpert.profile']);
        $projectsQuery = $this->filterDate($projectsQuery, $date);
        $projects = $projectsQuery->latest()->paginate(15);

        $payoutsCount = Cache::remember($period . '_' . CacheService::PAYOUTS_COUNT, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $payoutsQuery =  Payout::query();
            $payoutsQuery = $this->filterDate($payoutsQuery, $date);
            return $payoutsQuery->count('id');
        });

        $expertEarning = Cache::remember($period . '_' . CacheService::EXPERT_EARNING, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $expertFunds = ExpertFund::query();
            $expertFunds = $this->filterDate($expertFunds, $date);
            return $expertFunds->sum('amount');
        });

        $ourCommissions = Cache::remember($period . '_' . CacheService::OUR_COMMISSION, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $expertFunds = ExpertFund::query();
            $expertFunds = $this->filterDate($expertFunds, $date);
            $expertEarning = $expertFunds->sum('amount');
            return $expertFunds->sum('total') - $expertEarning;
        });

        $sales = Cache::remember($period . '_' . CacheService::SALES, CacheService::TTL_ONE_HOUR, function() use ($date) {
            $sales = Payment::query();
            $sales = $this->filterDate($sales, $date);
            return $sales->where('status', 'succeeded')->sum('total');
        });

        $totalMerchants = MigratedMerchant::where('role_id', Role::CLIENT)
            ->whereNotNull('refer_token')
            ->where('status', '!=', 'Deleted')
            ->count();

        $migratedMerchants = User::where('is_migrated', true)
            ->count();

        $loggedInMerchants = User::where('is_migrated', true)
            ->whereNotNull('remember_token')
            ->count();

        return response()->json([
            'clients_count' => $clientCount,
            'experts_count' => $expertCount,
            'projects' => $projects,
            'projects_count' => $projectCount,
            'payouts_count' => $payoutsCount,

            'migrated_merchants_stats' => [
                [
                    'key' => 'Total Merchants',
                    'value' => $totalMerchants,
                ],
                [
                    'key' => 'Migrated Merchants',
                    'value' => $migratedMerchants,
                ],
                [
                    'key' => 'Merchants Who Logged in',
                    'value' => $loggedInMerchants,
                ],
            ],
            'stats' => [
                [
                    'key' => 'Clients',
                    'value' => $clientCount
                ],
                [
                    'key' => 'Experts',
                    'value' => $expertCount
                ],
                [
                    'key' => 'Submitted Projects',
                    'value' => $projectCount
                ],
                [
                    'key' => 'Conversion Rate',
                    'value' => '0.00%'
                ],
                [
                    'key' => 'Completed Projects',
                    'value' => $completeProjectCount,
                ],
                [
                    'key' => 'Sales',
                    'value' => Number::currency($sales)
                ],
                [
                    'key' => 'Our Commissions',
                    'value' => Number::currency($ourCommissions)
                ],
                [
                    'key' => 'Avg. Project Revenue',
                    'value' => '$0.00'
                ],
                [
                    'key' => 'Refund Rate',
                    'value' => '0.00%'
                ],
                [
                    'key' => 'Refunds',
                    'value' => '$0.00'
                ],
                [
                    'key' => 'Experts Earnings',
                    'value' => Number::currency($expertEarning)
                ],
                [
                    'key' => 'Project Ideas',
                    'value' => 0
                ],
                [
                    'key' => 'Questions',
                    'value' => 0
                ],
                [
                    'key' => 'Answers on Questions',
                    'value' => 0
                ],
            ]
        ]);
    }

    private function filterDate($builder, $date) {
        if ($date) {
            $builder = $builder->whereDate('created_at', '>', $date);
        }
        return $builder;
    }
}
