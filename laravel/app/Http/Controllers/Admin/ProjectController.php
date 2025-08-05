<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    private ProjectService $projectService;
    private ProjectRepository $projectRepository;

    public function __construct(ProjectService $projectService, ProjectRepository $projectRepository)
    {
        $this->projectService = $projectService;
        $this->projectRepository = $projectRepository;
    }

    public function all(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        $search = $request->get('search');
        $period = $request->get('period');
        $date = null;

        // Handle period filtering
        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        $projects = Project::with([
            'client', 
            'activeAssignment', 
            'activeAssignment.expert', 
            'activeAssignment.expert.profile',
            'preferredExpert', 
            'preferredExpert.profile',
            // Add the missing V2 relationship for offers/quotes
            'activeAssignment.offers' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);
            
        // Date filtering
        if ($date) {
            $projects = $projects->whereDate('created_at', '>', $date);
        }

        // Status filtering
        if ($status && $status !== 'all' && $status !== 'archived') {
            $projects = $projects->where('status', $status);
        }

        // Limit filtering
        if ($request->get('limit')) {
            $projects = $projects->limit($request->get('limit'));
        }

        // Search filtering - comprehensive search across all relevant fields
        if ($search) {
            $projects = $projects->where(function($query) use ($search) {
                $search = '%' . $search . '%';
                $query->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhere('url', 'like', $search)
                    ->orWhereHas('client', function($clientQuery) use ($search) {
                        $clientQuery->where('first_name', 'like', $search)
                                ->orWhere('last_name', 'like', $search)
                                ->orWhere('email', 'like', $search);
                    })
                    ->orWhereHas('activeAssignment.expert', function($expertQuery) use ($search) {
                        $expertQuery->where('first_name', 'like', $search)
                                ->orWhere('last_name', 'like', $search)
                                ->orWhere('email', 'like', $search);
                    });
            });
        }

        // Handle archived projects
        if ($status && $status === 'archived') {
            $projects = $projects->whereNotNull('deleted_at');
            return response()->json(['projects' => $projects->withTrashed()->latest()->paginate(15)]);
        }

        return response()->json(['projects' => $projects->latest()->paginate(15)]);
    }

    /**
     * Get filter options for quotes (v2)
     */
    public function getQuoteFilterOptions()
    {
        try {
            // Get unique PROJECT statuses from database (same as old template)
            $statuses = \App\Models\Project::select('status')
                ->distinct()
                ->pluck('status')
                ->map(function($status) {
                    return [
                        'value' => $status,
                        'label' => $status === 'pending_payment' ? 'Pending Payment' : 
                                ($status === 'in_progress' ? 'In Progress' : 
                                ($status === 'expert_completed' ? 'Awaiting Approval' : 
                                ($status === 'completed' ? 'Completed' : 
                                ($status === 'pending_match' ? 'Pending Match' : 
                                ($status === 'claimed' ? 'Read' : 
                                ($status === 'available' ? 'In Available' : 
                                ($status === 'matched' ? 'Matched' : ucfirst($status))))))))
                    ];
                })
                ->values();

            return response()->json([
                'statuses' => $statuses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statuses' => []
            ], 500);
        }
    }

    public function update(Request $request, Project $project)
    {
        $action = $request->get('status');

        if ($action === 'move_pending_match_to_available') {
            $this->projectRepository->movetoAvailable($project);
        }

        if ($action === 'move_matched_to_available') {
            $expertIdToRemove = $request->get('expertIdToRemove');
            $this->projectRepository->deactivateAssignment($project, $expertIdToRemove);
            $this->projectRepository->movetoAvailable($project);
        }

        if ($action === 'preferred') {
            $expertIdToAssign = $project->preferred_expert_id;
        } else {
            $expertIdToAssign = $request->get('expertIdToAssign');
        }

        if ($expertIdToAssign) {
            $projectStatus = $project->status;
            $activeAssignment = $project->activeAssignment;

            if ($activeAssignment) {
                $assignedExpertId = $activeAssignment->expert_id;

                if ($projectStatus === 'matched' || $projectStatus === 'in_progress') {
                    if ($assignedExpertId !== $expertIdToAssign) {
                        $this->projectRepository->deactivateAssignment($project, $assignedExpertId);
                    } else {
                        return response()->json(['message' => 'Expert already matched']);
                    }
                }

                if ($projectStatus === 'claimed') {
                    $this->projectRepository->deactivateAssignment($project, $assignedExpertId);
                }
            }

            $this->projectRepository->assignPreferred($project, $expertIdToAssign);
        }

        return response()->json(['message' => 'OK']);
    }

    public function show(Request $request, $projectId)
    {
        $project = Project::withTrashed()->find($projectId);
        $project->load([
            'client',
            'invoices' => function ($query) {
                $query->withTrashed();
            },
            'messages',
            'messages.user',
            'messages.banner',
            'messages.offer',
            'messages.projectFile',
            'messages.reply',
            'messages.reply.user',
            'messages.reply.projectFile',
            'activeAssignment',
            'activeAssignment.expert',
            'activeAssignment.expert.profile',
            'activeAssignment.expert.reviews',
            'activeAssignment.expert.activeAssignments.project',
            'activeAssignment.offers',
            'preferredExpert',
            'preferredExpert.profile',
            'projectFiles',
            'review' => function ($query) {
                $query->withTrashed();
            },
            'invoices.offer',
            'invoices.user',
            'history' => function ($query) {
                $query->withTrashed();
            },
        ]);
        return response()->json(['project' => $project]);
    }

    public function archive(Request $request, $projectId): JsonResponse
    {
        try {
            $this->projectService->archive($projectId);

            return response()->json([
                'message' => 'Project archived successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to archive project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request, $projectId): JsonResponse
    {
        try {
            $this->projectService->restore($projectId);

            return response()->json([
                'message' => 'Project restored successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to restore project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
