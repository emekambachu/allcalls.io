<?php

namespace App\Events;

use App\Models\Card;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FundsAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public $subTotal;
    public $processingFee;
    public $total;
    public $bonus;
    public Card $card;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $subTotal, $processingFee, $total, $bonus, $card)
    {
        $this->user = $user;
        $this->subTotal = $subTotal;
        $this->processingFee = $processingFee;
        $this->total = $total;
        $this->bonus = $bonus;
        $this->card = $card;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('some-private'),
        ];
    }
}
