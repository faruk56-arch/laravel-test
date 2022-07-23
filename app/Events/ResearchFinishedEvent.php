<?php

namespace App\Events;

use App\Models\Research;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResearchFinishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $research;

    /**
     * Create a new event instance.
     *
     * @param Research $research
     */
    public function __construct(Research $research)
    {
        $this->research = $research;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
