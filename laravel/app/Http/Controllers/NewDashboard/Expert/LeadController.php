<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Request as Lead;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Repositories\LeadRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LeadController extends Controller
{
    public function __construct(
        private LeadRepository $leadRepository
    ) {}

    /**
     * Get all leads for the authenticated expert
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function leads(Request $request): JsonResponse
    {
        $type = $request->get('type');
        $leads = $this->leadRepository->getLeadsForExpert(type: $type);

        return response()->json([
            'leads' => $leads,
        ]);
    }

    /**
     * Get a single lead by ID
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function show(Lead $lead): JsonResponse
    {
        $currentUser = \Auth::user();

        if ($lead->expert_id !== $currentUser->id && $lead->project->status !== Project::PENDING_MATCH) {
            return response()->json([
                'error' => 'You do not have permission to view this lead'
            ], 403);
        }

        if ($lead->project->status === Project::PENDING_MATCH) {
            $additionalExperts = $lead->project->additional_experts ?? [];

            if ($lead->expert_id !== $currentUser->id && !in_array($currentUser->id, $additionalExperts)) {
                return response()->json([
                    'error' => 'You are not authorized to view this lead'
                ], 403);
            }
        }

        $leadWithRelations = $this->leadRepository->getLeadWithRelations($lead);

        return response()->json([
            'lead' => $leadWithRelations,
        ]);
    }

    /**
     * Get expert statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $expertStats = $this->leadRepository->getExpertStats();

        return response()->json([
            'expert_stats' => $expertStats
        ]);
    }

    /**
     * Get project names for leads that haven't been reviewed
     *
     * @param ProjectRepository $projectRepository
     * @return JsonResponse
     */
    public function projectNames(ProjectRepository $projectRepository): JsonResponse
    {
        $user = \Auth::user();
        $clientId = request('user_id');

        $projects = $projectRepository->getProjectNamesForExpert($user->id, $clientId);

        return response()->json([
            'project_names' => $projects,
        ]);
    }

    /**
     * Search users by full name
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function searchUsers(UserRepository $userRepository): JsonResponse
    {
        $search = request('search', '');

        $users = $userRepository->searchByName($search);

        return response()->json(['users' => $users]);
    }
}
