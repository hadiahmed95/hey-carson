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
        $shopifyPlan = $request->get('shopify_plan');
        $date = null;

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $clients = User::query()->where('role_id', 2);
        
        $clients = $clients->addSelect([
            // Direct Chats = requests where type='Direct Message'
            'direct_messages_count' => \DB::table('requests')
                ->select(\DB::raw('COUNT(*)'))
                ->where('type', 'Direct Message')
                ->whereColumn('client_id', 'users.id'),
                
            // Quote Requests = requests where type='Quote Request'
            'quote_requests_count' => \DB::table('requests')
                ->select(\DB::raw('COUNT(*)'))
                ->where('type', 'Quote Request')
                ->whereColumn('client_id', 'users.id'),
                
            // Lifetime spend
            'lifetime_spend' => \DB::table('payments')
                ->select(\DB::raw('COALESCE(SUM(total), 0)'))
                ->whereColumn('user_id', 'users.id')
                ->where('status', 'paid')
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
