<?php

namespace App\Listeners;

use App\Models\Call;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnlockClientForFronter implements ShouldQueue
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
        if ($event->callType->type === 'NO BUFFER - Final Expense - Fronter') {
            Log::debug('UnlockClientForFronter::handle');

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
            $result = $call->client->save();


            if (!$result) {
                Log::debug('Client unlock save failed for fronter', ['client' => $call->client->toArray()]);
            } else {
                Log::debug('Client unlock save succeeded for fronter', ['client' => $call->client->toArray()]);
            }
        }
    }
}
