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

class InitiatedCallEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public $uniqueCallId;
    public $callTypeId;
    public $from;
    public $firstCallSid;
    public $secondCallSid;
    public $twilioCallToken;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $uniqueCallId, $callTypeId, $from, $firstCallSid = null, $secondCallSid = null, $twilioCallToken = null)
    {
        $this->user = $user;
        $this->uniqueCallId = $uniqueCallId;
        $this->callTypeId = $callTypeId;
        $this->from = $from;
        $this->firstCallSid = $firstCallSid;
        $this->secondCallSid = $secondCallSid;
        $this->twilioCallToken = $twilioCallToken;
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
