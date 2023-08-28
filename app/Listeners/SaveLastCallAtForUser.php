<?php

namespace App\Listeners;

use App\Events\CallStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveLastCallAtForUser
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
    public function handle(CallStatusUpdated $event): void
    {
        $event->user->last_called_at = now();
        $event->user->save();
    }
}
