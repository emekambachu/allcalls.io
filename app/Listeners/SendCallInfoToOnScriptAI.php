<?php

namespace App\Listeners;

use App\Models\Call;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCallInfoToOnScriptAI
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
        Log::debug('SendCallInfoToOnScriptAI:', [
            'call' => Call::whereUniqueCallId($event->uniqueCallId)->first(),
            'agent' => $event->user,
        ]);
    }
}
