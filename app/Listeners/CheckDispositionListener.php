<?php

namespace App\Listeners;

use App\Models\Call;
use App\Jobs\CheckDispositionJob;
use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckDispositionListener
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
        // Retrieve the call using the uniqueCallId
        // $user = $event->user;
        // $call = Call::where('unique_call_id', $event->uniqueCallId)->first();

        // if ($call) {
        //     // Assuming there's a 'client' relationship set up on your Call model
        //     $client = $call->client;

        //     if ($client && ($client->status === null || $client->status === '' || $client->status === 'not_sold')) {
        //         // The client's status is not set, dispatch the job to handle further checks and notifications
        //         CheckDispositionJob::dispatch($user, $client, $event->uniqueCallId);
        //     }
        // }


        try {
            Log::info("CompletedCallEvent triggered for unique call ID: {$event->uniqueCallId}");
            $user = $event->user;

            // Retrieve the call using the uniqueCallId
            $call = Call::where('unique_call_id', $event->uniqueCallId)->first();

            if ($call) {
                // Assuming there's a 'client' relationship set up on your Call model
                $client = $call->client;

                if ($client) {
                    Log::info("Found client {$client->id} for call {$event->uniqueCallId}. Checking disposition status.");

                    if ($client->status === null || $client->status === '' || $client->status == 'not_sold') {
                        // The client's status is not set, dispatch the job to handle further checks and notifications
                        CheckDispositionJob::dispatch($user, $client, $event->uniqueCallId)->delay(now()->addSeconds(35));
                        Log::info("Dispatched CheckDispositionJob for client {$client->id} with call {$event->uniqueCallId}");
                    } else {
                        Log::info("Client {$client->id} already has a disposition status set for call {$event->uniqueCallId}");
                    }
                } else {
                    Log::warning("No client associated with call {$event->uniqueCallId}");
                }
            } else {
                Log::warning("No call found with unique call ID: {$event->uniqueCallId}");
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur during the process
            Log::error("An error occurred in CheckDispositionListener: {$e->getMessage()}");
            
            // Optionally, re-throw the exception if you want it to bubble up:
            // throw $e;
        }
    }
}
