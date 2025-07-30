<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertStat;
use App\Models\Request;
use App\Models\User;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    /**
     *
     * @return JsonResponse
     */
    public function leads(): JsonResponse
    {
        $user = \Auth::user();

        $query = Request::query()
            ->where('expert_id', $user->id)
            ->with(['project', 'client'])
            ->latest();

        if (request()->has('type')) {
            $query->where('type', request('type'));
        }

        return response()->json([
            'leads' => $query->get(),
        ]);
    }

    /**
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $user = \Auth::user();

        $expert_stats = ExpertStat::query()
            ->where('expert_id', $user->id)
            ->first();

        return response()->json([
            'expert_stats' => $expert_stats
        ]);
    }
}
