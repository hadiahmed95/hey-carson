<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessageNotificationToReceiver implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public ?array $receiver_ids;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param $receiver_ids
     */
    public function __construct(Message $message, $receiver_ids)
    {
        $this->message = $message;
        $this->receiver_ids = $receiver_ids;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        foreach ($this->receiver_ids as $id) {
            $channels[] = new PrivateChannel('notifications.' . $id);
        }
        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'NewMessageNotification';
    }
}
