<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCallTypeStateUpdated
{
    use SerializesModels, Dispatchable;

    public $userId;
    public $toDelete;
    public $toInsert;


    /**
     * Create a new event instance.
     */
    public function __construct(int $userId, array $toDelete, array $toInsert)
    {
        $this->userId = $userId;
        $this->toDelete = $toDelete;
        $this->toInsert = $toInsert;
    }
}
