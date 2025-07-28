<?php

namespace App\Http\Controllers\Client;

use App\Events\SendEmail;
use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Mail\Expert\ProjectNotThereYetMail;
use App\Models\Banner;
use App\Models\Project;
use App\Models\UserEvent;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ProjectController extends Controller
{
    private ProjectRepository $projectRepository;

    /**
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function all(Request $request)
    {
        $user = Auth::user();

        $status =  $request->get('status');

        $projects = Project::query()
            ->where('client_id', $user->id)
            ->whereDoesntHave('requests');;

        if (!$status || $status === 'all') {
            $projects = $projects->with(['activeAssignment.expert.profile']);
        } elseif ($status === 'pending_match') {
            $projects = $projects->with(['activeAssignment.expert.profile'])
                ->whereIn('status', ['pending_match', 'available', 'claimed']);
        } else {
            $projects = $projects->with(['activeAssignment.expert.profile'])
                ->where('status', $status);
        }

        return response()->json(['projects' => $projects->latest('updated_at')->paginate(15)]);
    }

    /**
     * @param CreateProjectRequest $request
     * @return JsonResponse
     */
    public function create(CreateProjectRequest $request): JsonResponse
    {
        $user = Auth::user();
        try {
            $data = $request->validated();

            $this->projectRepository->create($data, $user);

            return response()->json(['message' => 'OK']);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return JsonResponse
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        $user = Auth::user();
        $tabName = $request->get('tabName');

        if ($project->client_id === $user->id) {
            return response()->json(['project' => $this->projectRepository->show($project, $tabName, $user->role_id)]);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function delete(Project $project)
    {
        $user = Auth::user();

        if ($user->id === $project->client_id) {
            UserEvent::query()->where('project_id', $project->id)->delete();
            $project->delete();

            CacheInvalidation::dispatch('user_events', $project->client_id);
            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function notYet(Request $request, Project $project)
    {
        $user = Auth::user();

        if ($project->client_id === $user->id) {
            $project->messages()->where('type', 'banner')
                ->where('banner_id', 5)
                ->update(['content' => 'declined']);

            $project->update(['status' => 'in_progress']);

            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'client',
                'banner_id' => Banner::CRITICAL_CLIENT_COMPLETED,
                'seen' => 1
            ]);

            SendEmail::dispatch($project->activeAssignment->expert, new ProjectNotThereYetMail($project->activeAssignment->expert, $project->client, $project));

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return JsonResponse
     */
    public function complete(Request $request, Project $project): JsonResponse
    {
        $expertId = $request->get('expert_id');
        $rate = $request->get('rate');
        $comment = $request->get('comment');
        $communication = $request->get('communication');
        $quality = $request->get('quality');
        $recommendation = $request->get('recommendation');
        $timeToStart = $request->get('timeToStart');
        $valueForMoney = $request->get('valueForMoney');
        $valueRange = $request->get('valueRange');

        return $this->projectRepository->complete(
            $project,
            $expertId,
            Auth::user(),
            $rate,
            $comment,
            $communication,
            $quality,
            $recommendation,
            $timeToStart,
            $valueForMoney,
            $valueRange
        );
    }
}
