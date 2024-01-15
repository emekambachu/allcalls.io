<?php

namespace App\Listeners;

use App\Models\Call;
use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchDispositionUpdateNotification
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
    public function handle(CompletedCallEvent $event): void
    {
        $call = Call::where('unique_call_id', $event->uniqueCallId)->first();

        if (!$call || !$call->client) {
            return;
        }
    }
}
