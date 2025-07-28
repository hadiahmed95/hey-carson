<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientFund;
use App\Models\User;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClientController extends Controller
{
    public function all(Request $request)
    {
        $search = $request->get('search');
        $period =  $request->get('period');
        $date = null;

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $clients = User::query()->where('role_id', 2)->withCount('projects');
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

        return response()->json([
            'clients' => $clients->latest()->paginate(15),
            'clients_count' => $clientsCount
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
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

        return response()->json([
            'client'    => $user,
            'balance'   => $balance
        ]);
    }
}
