<?php

namespace App\Http\Controllers\Expert;

use App\Events\SendEmail;
use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Mail\Client\ProjectMatchedMail as ProjectClientMatchedMail;
use App\Mail\Expert\ProjectCompletedMail as ProjectExpertCompletedMail;
use App\Mail\Client\ProjectCompletedMail as ProjectClientCompletedMail;
use App\Models\Assignment;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Project;
use App\Models\UserEvent;
use App\Repositories\ProjectRepository;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ProjectController extends Controller
{
    public function all(Request $request)
    {
        $user = Auth::user();

        /** Clear 'READ' assignments if the timer has run out */
        Assignment::query()
            ->where('is_active', true)
            ->whereDate('created_at', '<', Carbon::now()->subMinutes(5))
            ->whereHas('project', function($builder) {
                $builder->whereIn('status', ['available', 'claimed']);
            })->update(['deleted_at' => Carbon::now()]);

        CacheInvalidation::dispatch('cache_key', CacheService::ACTIVE_ASSIGNMENTS . '_' . $user->id);
        $status =  $request->get('status');

        $archivedProjectIds = Project::withTrashed()->whereNotNull('deleted_at')->pluck('id');

        $projectsQuery = Assignment::query()->where('expert_id', $user->id)
            ->where('is_active', true)
            ->whereNotIn('project_id', $archivedProjectIds)
            ->with([
                'project',
                'project.client',
                'project.activeAssignment',
                'project.messages' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]);

        if ($status && $status !== 'all') {
            $projectsQuery->whereHas('project', function($builder) use ($status) {
                $builder->where('status', $status);
            });
        }

        $search = $request->get('search');

        if ($search) {
            $projectsQuery->whereHas('project', function($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%');
            });
        }

        $projectsQuery->join('projects', 'assignments.project_id', '=', 'projects.id')
            ->orderByDesc(function ($query) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('project_id', 'projects.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            });

        return response()->json(['projects' => $projectsQuery->paginate(15)]);
    }

    /**
     * @param CreateProjectRequest $request
     * @param ProjectRepository $projectRepository
     * @return JsonResponse
     */
    public function create(CreateProjectRequest $request, ProjectRepository $projectRepository): JsonResponse
    {
        $user = Auth::user();

        try {
            $data = $request->validated();
            $projectRepository->create($data, $user);

            return response()->json(['message' => 'OK']);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param Project $project
     * @param ProjectRepository $projectRepository
     * @return JsonResponse
     */
    public function show(Request $request, Project $project, ProjectRepository $projectRepository): JsonResponse
    {
        $user = Auth::user();
        $tabName = $request->get('tabName');

        // Remove "read" projects from the list if timer has run out,
        // or it's at 0:00, and it didn't remove project automatically
        if ($project->status === 'available') {
            Assignment::query()->where('project_id', $project->id)
                ->where('expert_id', $user->id)
                ->where('is_active', true)
                ->delete();
            return response()->json(['message' => 'unauthorised'], 401);
        }

        $project = $projectRepository->show($project, $tabName, $user->role_id);

        $assignment = Assignment::query()->where('project_id', $project->id)
            ->where('expert_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($project->activeAssignment &&  $assignment &&  $project->activeAssignment->id === $assignment->id) {
            $project->load('history');
            return response()->json(['project' => $project]);
        } else {
            if($assignment) {
                $assignment->delete();
            }
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function delete(Project $project)
    {
        $user = Auth::user();

        if ($project->activeAssignment && $project->activeAssignment->expert_id === $user->id) {
            $project->activeAssignment()->update(['is_active' => 0]);

            $project->update(['status' => 'available']);
            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function completed(Request $request, Project $project)
    {
        $user = Auth::user();

        if ($project->activeAssignment && $project->activeAssignment->expert_id === $user->id) {
            $project->update(['status' => 'expert_completed']);

            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'expert',
                'banner_id' => Banner::INFO_EXPERT_COMPLETED,
                'seen' => 1
            ]);

            UserEvent::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'event_id' => Event::EXPERT_PROJECT_FINISHED,
            ]);
            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => Event::CLIENT_PROJECT_COMPLETE,
            ]);
            CacheInvalidation::dispatch('user_events', $user->id);
            CacheInvalidation::dispatch('user_events', $project->client_id);

            SendEmail::dispatch($user, new ProjectExpertCompletedMail($user, $project->client));
            SendEmail::dispatch($project->client, new ProjectClientCompletedMail($project->client, $user, $project));

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function available(Request $request)
    {
        $user = Auth::user();

        if ($user->is_disable) {
            return response()->json(['available_projects' => []]);
        }
        Assignment::query()
            ->where('is_active', true)
            ->whereDate('created_at', '<', Carbon::now()->subMinutes(5))
            ->whereHas('project', function($builder) {
                $builder->whereIn('status', ['available', 'claimed']);
            })->update(['deleted_at' => Carbon::now()]);

        $projectsId = Assignment::query()
            ->where('expert_id', $user->id)
            ->get()
            ->pluck('project_id');

        $projects = Project::query()->where('status', 'available')
            ->whereNotIn('id', $projectsId)->latest()->paginate(15);

        return response()->json(['available_projects' => $projects]);
    }

    public function claim(Request $request, Project $project)
    {
        $user = Auth::user();

        $assignments = Assignment::query()->where('project_id', $project->id)
            ->where('is_active', true);

        if ($assignments->exists()) {
            return response()->json(['message' => 'Not available'], 500);
        } elseif ($project->status === "available") {
            $user->assignments()->create([
                'project_id' => $project->id,
                'is_active' => true,
            ]);

            $project->update(['status' => 'claimed']);
            CacheInvalidation::dispatch('cache_key', CacheService::ACTIVE_ASSIGNMENTS . '_' . $user->id);

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'Not available'], 500);
        }
    }

    public function release(Request $request, Project $project)
    {
        $user = Auth::user();

        if ($project->activeAssignment && $project->activeAssignment->expert_id === $user->id) {
            $project->activeAssignment->update(['is_active' => false]);
            $project->update(['status' => 'available']);
            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function take(Request $request, Project $project)
    {
        $user = Auth::user();

        if ($project->status === 'claimed' && $project->activeAssignment && $project->activeAssignment->expert_id === $user->id) {
            $project->update(['status' => 'matched']);

            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'client',
                'banner_id' => Banner::INFO_EXPERT_MATCHED,
                'seen' => 1
            ]);

            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => Event::CLIENT_PROJECT_MATCHED,
            ]);

            SendEmail::dispatch($project->client, new ProjectClientMatchedMail($project->client, $project->activeAssignment->expert, $project));
            CacheInvalidation::dispatch('user_events', $project->client_id);

            return response()->json(['message' => 'OK']);
        } else {
            Assignment::query()
                ->where('project_id', $project->id)
                ->whereNot('expert_id', $user->id)
                ->where('is_active', true)->update(['status' => false]);

            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
