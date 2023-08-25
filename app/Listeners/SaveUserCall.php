<?php

namespace App\Listeners;

use App\Events\RingingCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveUserCall
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
    public function handle(RingingCallEvent $event): void
    {
        Log::debug('Save the call now');
        Log::debug($event->user->id);
        Log::debug($event->callSid);
    }
}
