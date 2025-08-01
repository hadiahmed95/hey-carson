<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientFund;
use App\Models\Payment;
use App\Models\User;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PaymentRepository;

class ClientController extends Controller
{
    protected PaymentRepository $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }
    
    public function all(Request $request)
    {
        $search = $request->get('search');
        $period =  $request->get('period');
        $shopifyPlan = $request->get('shopify_plan'); // New filter for leads
        $date = null;

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $clients = User::query()->where('role_id', 2)->withCount('projects');
        
        // FIXED: Use requests table instead of projects table for quote requests
        $clients = $clients->withCount([
            'requests as quote_requests_count' => function ($query) {
                $query->where('type', 'Quote Request');
            }
        ]);
        
        // Add count for direct messages from requests table
        $clients = $clients->withCount([
            'requests as direct_messages_count' => function ($query) {
                $query->where('type', 'Direct Message');
            }
        ]);
        
        // Calculate lifetime spend from payments table
        $clients = $clients->addSelect([
            'lifetime_spend' => Payment::selectRaw('COALESCE(SUM(total), 0)')
                ->whereColumn('user_id', 'users.id')
                ->where('status', 'completed')
        ]);
        
        if ($date) {
            $clients = $clients->whereDate('created_at', '>', $date);
        }

        $clientsCount = $clients->count('id');

        if ($search) {
            $clients = $clients->where(function ($query) use ($search) {
                $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$search}%"])
                    ->orWhere('email', '=', $search)
                    ->orWhere('url', '=', $search);
            });
        }

        // New: Shopify plan filter for leads
        if ($shopifyPlan && $shopifyPlan !== '') {
            $clients = $clients->where('shopify_plan', $shopifyPlan);
        }

        return response()->json([
            'clients' => $clients->latest()->paginate(15),
            'clients_count' => $clientsCount
        ]);
    }

    /**
     * Get filter options for leads
     */
    public function getLeadFilterOptions()
    {
        $shopifyPlans = User::where('role_id', 2)
            ->whereNotNull('shopify_plan')
            ->where('shopify_plan', '!=', '')
            ->distinct()
            ->pluck('shopify_plan')
            ->sort()
            ->values()
            ->toArray();

        return response()->json([
            'shopifyPlans' => $shopifyPlans
        ]);
    }

    // Keep existing show method unchanged
    public function show(Request $request, User $user): JsonResponse
    {
        $balance = Cache::remember(
            CacheService::HOURS . '_' . $user->id,
            CacheService::TTL_ONE_HOUR,
            function() use ($user) {
                $clientFunds = ClientFund::query()
                    ->where('user_id', $user->id);

                $prepaidHours = $clientFunds->sum('prepaid_hours');
                $usedHours = $clientFunds->sum('used_hours');

                return $prepaidHours - $usedHours;
            }
        );

        $user->load([
            'projects',
            'projects.client',
            'projects.activeAssignment',
            'projects.activeAssignment.expert',
            'projects.activeAssignment.expert.profile'
        ]);

        $transactions = $this->paymentRepository->getTransactions($user);

        return response()->json([
            'client'    => $user,
            'balance'   => $balance,
            'transactions' => $transactions
        ]);
    }
}
