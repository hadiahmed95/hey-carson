<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class MessageRepository
{
    /**
     * Create a new message, or upload file to the S3
     *
     * @param Request $data
     * @param $project
     * @param $role
     * @return JsonResponse
     */
    public function create(Request $data, $project, $role): JsonResponse
    {
        $data->merge(['runObserver' => true]);
        $content = $data->get('content');
        $replyId = $data->get('reply_id');
        $isReceiverOnline = $data->get('is_receiver_online');
        $file = $data->file('file');

        if ($data->hasFile('file')) {
            $filename = $file->getClientOriginalName();
            $isReceiverOnline = json_decode($data->get('data'), true)['is_receiver_online'] ?? null;

            Message::create([
                'project_id' => $project->id,
                'user_id' => \Auth::user()->id,
                'user_type' => $role,
                'content' => $filename,
                'type' => $file->getClientOriginalExtension(),
                'seen' => $isReceiverOnline ?? false,
                'reply_id' => $replyId ?? null,
            ]);
        } else {
            if (
                Str::contains($content, 'img') &&
                Str::contains($content, 'data:image') &&
                Str::contains($content, 'base64')
            ) {
                preg_match_all('/<img [^>]*src="([^"]*)"/', $content, $matches);

                if (isset($matches[1])) {
                    $s3ImagesUrl = [];

                    foreach ($matches[1] as $imgSrc) {
                        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgSrc));
                        $filename = uniqid() . '.png';
                        $path = 'messages/' . $project->id . '/' . $filename;

                        Storage::disk('s3')->put($path, $imageData);

                        $s3Url = Storage::disk('s3')->url($path);
                        $s3ImagesUrl[$imgSrc] = $s3Url;
                    }

                    foreach ($s3ImagesUrl as $oldSrc => $newSrc) {
                        $content = str_replace($oldSrc, $newSrc, $content);
                    }
                }
            }
            Message::create([
                'project_id' => $project->id,
                'user_id' => \Auth::user()->id,
                'content' => $content,
                'type' => "text",
                'user_type' => $role,
                'seen' => $isReceiverOnline ?? false,
                'reply_id' => $replyId ?? null,
            ]);

        }
        return response()->json(['message' => 'OK']);
    }

    /**
     * @param string $filterTag
     * @param int $roleId
     * @return Collection|array
     */
    public function all(string $filterTag, int $roleId): Collection|array
    {
        $user = Auth::user();
        $projectIds = [];

        if ($roleId === Role::CLIENT) {
            $projectIds = $user->projects()->pluck('id');
        } elseif ($roleId === Role::EXPERT) {
            $projectIds = $user->activeAssignments()->pluck('project_id');
        }

        $messages = Message::query()
            ->with(['project', 'user'])
            ->whereIn('project_id', $projectIds)
            ->whereNot('user_id', $user->id);

        if ($filterTag === 'All') {
            return $messages->latest()->limit(20)->get();
        }

        return $messages->where('seen', false)->latest()->get();
    }

}
