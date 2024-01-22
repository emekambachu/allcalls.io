<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
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
    public function handle($event): void
    {
        // Check if 'user' and 'email' keys are available in the event data
        if (isset($event->data['user']['email'])) {
            $email = $event->data['user']['email'];
            Log::debug('Extracted email from event', ['email' => $email]);
        } else {
            Log::debug('Email key not found in event data');
        }
    }
}
