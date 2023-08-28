<?php

namespace App\Listeners;

use App\Events\CallStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCallStatusChange
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
        Log::debug('Call status was updated and here is the info:');


        Log::debug($event->user);
        Log::debug($event->info);
    }
}
