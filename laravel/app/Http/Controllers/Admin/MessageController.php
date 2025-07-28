<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * @param Request $request
     * @param Project $project
     * @param MessageRepository $messageRepository
     * @return JsonResponse
     */
    public function create(Request $request, Project $project, MessageRepository $messageRepository): JsonResponse
    {
        return $messageRepository->create($request, $project, 'admin');
    }

    public function edit(Request $request, Project $project, Message $message)
    {
        $user = Auth::user();

        $content = $request->get('content');

        if ($message->user_id === $user->id) {
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

        if ($message->user_id === $user->id) {
            $message->delete();

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
