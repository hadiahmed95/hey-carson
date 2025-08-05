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
        $version = $request->get('version', 'v1');
        $date = null;

        if ($period === 'week') {
            $date = Carbon::now()->subWeek();
        } elseif ($period === 'month') {
            $date = Carbon::now()->subMonth();
        }

        if ($version === 'v2') {
            // For v2 (quotes sent), get projects with offers/quotes
            $projects = Project::with([
                'client', 
                'activeAssignment', 
                'activeAssignment.expert', 
                'activeAssignment.expert.profile',
                'activeAssignment.offers' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->whereHas('activeAssignment.offers'); // Only projects with offers

            if ($date) {
                $projects = $projects->whereDate('created_at', '>', $date);
            }

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

            // Filter by offer status
            if ($status && $status !== 'all') {
                $projects = $projects->whereHas('activeAssignment', function($assignmentQuery) use ($status) {
                    $assignmentQuery->whereHas('offers', function($offerQuery) use ($status) {
                        $offerQuery->where('status', $status)
                                ->whereRaw('id = (SELECT MAX(id) FROM offers WHERE assignment_id = assignments.id)');
                    });
                });
            }

            return response()->json(['quotes' => $projects->latest()->paginate(15)]);
        }

        // Original v1 logic (unchanged)
        $projects = Project::with(['client', 'activeAssignment', 'activeAssignment.expert', 'activeAssignment.expert.profile', 'preferredExpert', 'preferredExpert.profile']);
        
        if ($date) {
            $projects = $projects->whereDate('created_at', '>', $date);
        }

        if ($status && $status !== 'all' && $status !== 'archived') {
            $projects = $projects->where('status', $status);
        }

        if ($request->get('limit')) {
            $projects = $projects->limit($request->get('limit'));
        }

        if ($search) {
            $projects = $projects->where(function($query) use ($search) {
                $search = '%' . $search . '%';
                $query->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhere('url', 'like', $search);
            });
        }

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
            // Get unique offer statuses from database
            $statuses = \App\Models\Offer::select('status')
                ->distinct()
                ->pluck('status')
                ->map(function($status) {
                    return [
                        'value' => $status,
                        'label' => $status === 'send' ? 'Pending Payment' : 
                                ($status === 'accepted' ? 'Paid' : 
                                ($status === 'declined' ? 'Rejected' : ucfirst($status)))
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

    // ... rest of your existing methods remain unchanged
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
            try {
                $isAssigned = $this->projectRepository->assignExpert($project, $expertIdToAssign);

                if ($isAssigned) {
                    return response()->json(['message' => 'OK']);
                } else {
                    return response()->json(['message' => 'Expert already matched']);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error occurred during assignment'], 500);
            }
        }

        return response()->json(['message' => 'Invalid action or missing expert ID'], 400);
    }

    public function show(Project $project)
    {
        $project->load([
            'client',
            'activeAssignment',
            'activeAssignment.expert',
            'activeAssignment.expert.profile',
            'activeAssignment.offers',
            'messages',
            'invoices',
            'history',
            'preferredExpert',
            'preferredExpert.profile'
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
