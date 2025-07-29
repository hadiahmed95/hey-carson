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
     * Get all leads for the authenticated expert
     *
     * @return JsonResponse
     */
    public function leads(): JsonResponse
    {
        $user = \Auth::user();

        $query = $this->getBaseLeadQuery($user->id);

        if (request()->has('type')) {
            $query->where('type', request('type'));
        }

        return response()->json([
            'leads' => $query->get(),
        ]);
    }

    /**
     * Get a single lead by ID
     *
     * @param int $id Lead ID
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = \Auth::user();

        $lead = $this->getBaseLeadQuery($user->id)
            ->where('id', $id)
            ->first();

        if (!$lead) {
            return response()->json([
                'message' => 'Lead not found'
            ], 404);
        }

        return response()->json([
            'lead' => $lead,
        ]);
    }

    /**
     * Get base query for leads with common conditions and relationships
     *
     * @param int $expertId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseLeadQuery(int $expertId)
    {
        return Request::query()
            ->where('expert_id', $expertId)
            ->with(['project', 'client'])
            ->latest();
    }

    /**
     * Get expert statistics
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
     * Get project names for leads that haven't been reviewed
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
