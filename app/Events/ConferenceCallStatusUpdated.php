<?php

namespace App\Events;

use App\Models\ConferenceCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConferenceCallStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conferenceCall;

    /**
     * Create a new event instance.
     */
    public function __construct(ConferenceCall $conferenceCall)
    {
        $this->conferenceCall = $conferenceCall;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    // : array
    {
        // return [
        //     new PrivateChannel('channel-name'),
        // ];
        return new Channel('conference.' . $this->conferenceCall->id);
    }
}
