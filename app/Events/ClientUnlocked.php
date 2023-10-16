<?php

namespace App\Events;

use App\Models\User;
use App\Models\Client;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ClientUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Client $client;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Client $client)
    {
        $this->user = $user;
        $this->client = $client;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->user->id . '.notifications'),
        ];
    }
}
