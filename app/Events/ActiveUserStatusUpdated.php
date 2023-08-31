<?php

namespace App\Events;

use App\Models\User;
use App\Models\ActiveUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActiveUserStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activeUser;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(ActiveUser $activeUser)
    {
        $this->activeUser = $activeUser;
        $this->user = User::find($activeUser->user_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('active-user-events'),
        ];
    }
}
