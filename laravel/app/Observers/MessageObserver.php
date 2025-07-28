<?php

namespace App\Observers;

use App\Events\Chat\MessageSent;
use App\Events\Chat\NewMessageNotificationToReceiver;
use App\Events\SendEmail;
use App\Mail\Client\NewMessageMail as NewMessageMailClient;
use App\Mail\Expert\NewMessageMail as NewMessageMailExpert;
use App\Models\Assignment;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class MessageObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(Message $message): void
    {
        try {
            if (!request()->runObserver) {
                return;
            }

            $message->load([
                'user',
                'project',
                'projectFile'
            ]);

            if (request()->inboundData) {
                $unSentMessageId = $message->id;
            } else {
                $unSentMessageId = request()->id;
            }

            if (!in_array($message->type, ['offer', 'banner', 'text'])) {
                if (request()->inboundData) {
                    $unSentMessageId = $message->id;
                } else {
                    $unSentMessageId = optional(json_decode(request()->get('data'), true))['id'] ?? null;
                }

                $file = request()->file('file') ?? null;

                if (!request()->inboundData) {
                    $filename = $message->content;
                    $project = $message->project;
                    $path = 'messages/' . $project->id . '/' . $message->id;

                    $project->projectFiles()->create([
                        'message_id' => $message->id,
                        'name' => $filename,
                        'url' => $path . '/' . urlencode($filename)
                    ]);
                }

                if ($file) {
                    $file->storeAs($path, $filename, 's3');
                }
            } else {
                $messageType = null;

                if ($message->type === 'offer') {
                    $messageType = 'offer';
                } elseif ($message->type === 'banner') {
                    $messageType = 'banner';
                }

                if ($messageType) {
                    $message->load([$messageType]);
                }
            }

            $receiverIds = $this->getReceivers($message->user_type, $message->project_id);
            $this->sendEmailToReceivers($message);

            $this->emit(new MessageSent($message, $receiverIds, 'created', $unSentMessageId));
            $this->emit(new NewMessageNotificationToReceiver($message, $receiverIds));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return;
        }
    }


    /**
     * Handle the User "updated" event.
     */
    public function updated(Message $message): void
    {
        $receiverIds = $this->getReceivers($message->user_type, $message->project_id);

        $this->emit(new MessageSent($message, $receiverIds, 'updated'));
    }


    /**
     * Handle the User "updated" event.
     */
    public function deleted(Message $message): void
    {
        $receiverIds = $this->getReceivers($message->user_type, $message->project_id);

        $this->emit(new MessageSent($message, $receiverIds, 'deleted'));
    }

    /**
     * @param $userType
     * @param $projectId
     * @return array
     */
    private function getReceivers($userType, $projectId): array
    {
        $receiverIds = [];

        if ($userType === 'client') {
            $expertAsReceiver = Assignment::where('project_id', $projectId)->value('expert_id');
            $receiverIds[] = $expertAsReceiver;
        } elseif ($userType === 'expert') {
            $clientAsReceiver = Project::where('id', $projectId)->value('client_id');
            $receiverIds[] = $clientAsReceiver;
        } else if ($userType === 'admin') {
            $clientAsReceiver = Project::where('id', $projectId)->value('client_id');
            $expertAsReceiver = Assignment::where('project_id', $projectId)->value('expert_id');
            $receiverIds[] = $clientAsReceiver;
            $receiverIds[] = $expertAsReceiver;
        }

        return $receiverIds;
    }

    /**
     * @param Message $message
     * @return void
     */
    private function sendEmailToReceivers(Message $message): void
    {
        if ($message->user_type === 'client') {

            if ($message->type === 'text') {
                $expert = $message->project->activeAssignment ? $message->project->activeAssignment->expert : null;
                if ($expert !== null) {
                    SendEmail::dispatch(
                        $expert,
                        new NewMessageMailExpert($expert, $message->project, $message)
                    );
                }
            }
        } elseif ($message->user_type === 'expert') {
            if ($message->type === 'text') {
                $client = $message->project->client;
                if ($client !== null) {
                    SendEmail::dispatch(
                        $client,
                        new NewMessageMailClient($client, $message->project, $message)
                    );
                }
            }
        } else if ($message->user_type === 'admin') {
            $expert = $message->project->activeAssignment ? $message->project->activeAssignment->expert : null;
            if ($expert !== null) {
                SendEmail::dispatch(
                    $expert,
                    new NewMessageMailExpert($expert, $message->project, $message)
                );
            }

            $client = $message->project->client;
            if ($client !== null) {
                SendEmail::dispatch(
                    $client,
                    new NewMessageMailClient($client, $message->project, $message)
                );
            }
        }
    }

    /**
     * @param ShouldBroadcast $message
     * @return void
     */
    private function emit(ShouldBroadcast $message): void
    {
        try {
            broadcast($message);
        } catch (\Exception $exception) {
        }
    }
}
