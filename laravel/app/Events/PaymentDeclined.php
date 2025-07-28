<?php

namespace App\Events;

use App\Models\Offer;
use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentDeclined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Project $project;
    public Offer $offer;


    /**
     * Create a new event instance.
     *
     * @param Offer $offer
     * @param Project $project
     */
    public function __construct(Project $project, Offer $offer)
    {
        $this->project = $project;
        $this->offer = $offer;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('chat.' . $this->project->id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'PaymentDeclined';
    }
}
