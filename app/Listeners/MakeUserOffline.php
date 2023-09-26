<?php

namespace App\Listeners;

use App\Models\OnlineUser;
use App\Events\MissedCallEvent;
use App\Events\OnlineUserListUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeUserOffline
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
    public function handle(MissedCallEvent $event): void
    {
        $userId = $event->user->id;

        // Find the record with user_id
        $record = OnlineUser::where('user_id', $userId)->first();

        // If the record exists, delete it
        if ($record) {
            $record->delete();

            // Dispatch the event
            OnlineUserListUpdated::dispatch();
        }
    }
}
