<?php

namespace App\Events;

use App\Models\Call;
use App\Models\User;
use App\Models\CallType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecordingSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $call;
    public $callType;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Call $call, CallType $callType, User $user)
    {
        $this->call = $call;
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
