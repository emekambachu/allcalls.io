<?php

namespace App\Events;

use App\Models\User;
use App\Models\CallType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CompletedCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public CallType $callType;
    public $uniqueCallId;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, CallType $callType, $uniqueCallId)
    {
        $this->user = $user;
        $this->callType = $callType;
        $this->uniqueCallId = $uniqueCallId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('calls.' . $this->user->id),
        ];
    }
}
