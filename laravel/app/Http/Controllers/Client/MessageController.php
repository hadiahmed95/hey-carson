<?php

namespace App\Http\Controllers\Client;

use App\Events\Chat\MessageSeen;
use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use App\Models\UserEvent;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private MessageRepository $messageRepository;

    /**
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $filterTag = $request->get('message');

        return response()->json(['messages' => $this->messageRepository->all($filterTag, Auth::user()->role_id)]);
    }

    public function create(Request $request, Project $project)
    {
        if ($project->client_id === \Auth::user()->id) {
            return $this->messageRepository->create($request, $project, 'client');
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        $userType = $request->get('type');

        $unSeenMessages = $project->messages()
            ->where('seen', 0)
            ->whereNot('user_type', $userType);

        $seenMessages = $unSeenMessages->pluck('id')->toArray();

        $unSeenMessages->update(['seen' => 1]);

        UserEvent::query()
            ->where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->update(['seen' => 1]);
        CacheInvalidation::dispatch('user_events', $user->id);

        broadcast(new MessageSeen($seenMessages, $project->id));

        return response()->json(['message' => 'OK']);
    }

    public function edit(Request $request, Project $project, Message $message)
    {
        $user = Auth::user();

        $content = $request->get('content');

        if (now()->diffInMinutes($message->created_at) > 20) {
            return response()->json(['message' => 'edit time expired'], 400);
        }

        if ($project->client_id === $user->id && $message->user_id === $user->id) {
            $message->update([
                'content' => $content,
                'edited' => true
            ]);

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function delete(Request $request, Project $project, Message $message)
    {
        $user = Auth::user();

        if ($project->client_id === $user->id && $message->user_id === $user->id) {
            $message->delete();

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
