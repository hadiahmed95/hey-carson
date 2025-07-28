<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public ?array $receiverIds;

    public string $status;

    public ?int $unSentMessageId;

    /**
     * Create a new event instance.
     * @param Message $message
     * @param array|null $receiverIds
     * @param string $status
     * @param int|null $unSentMessageId
     */
    public function __construct(Message $message, ?array $receiverIds, string $status, ?int $unSentMessageId = null)
    {
        $this->message = $message->load('reply');
        $this->receiverIds = $receiverIds;
        $this->unSentMessageId = $unSentMessageId;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('chat.' . $this->message->project_id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
