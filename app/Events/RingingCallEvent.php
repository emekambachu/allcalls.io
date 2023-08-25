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

class RingingCallEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public $uniqueCallId;
    public $callTypeId;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $uniqueCallId, $callTypeId)
    {
        $this->user = $user;
        $this->uniqueCallId = $uniqueCallId;
        $this->callTypeId = $callTypeId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
