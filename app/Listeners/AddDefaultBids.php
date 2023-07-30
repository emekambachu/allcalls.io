<?php

namespace App\Listeners;

use App\Models\Bid;
use App\Models\CallType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddDefaultBids
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        foreach(CallType::all() as $callType) {
            Bid::create([
                'user_id' => $event->user->id,
                'call_type_id' => $callType->id,
                20
            ]);
        }
    }
}
