<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ShouldBroadcast;

    public $user;
    public $info;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $info)
    {
        $this->user = $user;
        $this->info = $info;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('channel-name'),
            new Channel('active-users-events'),
        ];
    }
}
