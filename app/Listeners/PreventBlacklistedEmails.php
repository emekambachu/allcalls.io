<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use IlluminateMailEventsMessageSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreventBlacklistedEmails
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
    public function handle(IlluminateMailEventsMessageSending $event): void
    {
        Log::debug('PreventBlacklistedEmails listener fired', [
            'event' => $event,
        ]);
    }
}
