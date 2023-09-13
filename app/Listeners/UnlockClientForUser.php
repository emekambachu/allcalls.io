<?php

namespace App\Listeners;

use App\Models\Call;
use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnlockClientForUser
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

        if (!$call) {
            Log::debug('call not found');
            return;
        }

        if (!$call->client) {
            Log::debug('no client associated');
            return;
        }


        $call->client->unlocked = true;
        $call->save();

        Log::debug('client unlocked for user');
        return;
    }
}
