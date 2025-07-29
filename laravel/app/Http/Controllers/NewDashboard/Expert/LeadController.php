<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertStat;
use App\Models\Request;
use App\Models\Review;
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

    /**
     *
     * @return JsonResponse
     */
    public function projectNames(): JsonResponse
    {
        $user = \Auth::user();

        $reviewedProjectIds = Review::where('expert_id', $user->id)
            ->pluck('project_id')
            ->toArray();
        
        $query = Request::query()
            ->where('expert_id', $user->id)
            ->whereNotIn('project_id', $reviewedProjectIds)
            ->with(['project:id,name'])
            ->latest();

        $projectNames = $query->get()->map(function ($request) {
            return [
                'id' => $request->project?->id,
                'name' => $request->project?->name,
            ];
        })->filter(fn($p) => $p['id']); // filter null project

        return response()->json([
            'project_names' => $projectNames,
        ]);
    }
}
