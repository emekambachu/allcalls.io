<?php

namespace App\Listeners;

use Exception;
use App\Models\Call;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchPublisherInfoForCall
{

    /**
     * 
     * After 30 seconds of initiating the call, send the call info to OnScript AI
     */
    protected $delay = 60;

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
        Log::debug('FetchPublisherInfoForCall:start');

        $call = $event->call;

        // $callerId = $call->from;
        $callerId = '+18045172235';

        try {
            if ($call->updatePublisherInfo($callerId)) {
                Log::debug('FetchPublisherInfoForCall:info-updated', ['publisher_name' => $call->publisher_name, 'publisher_id' => $call->publisher_id]);
            } else {
                Log::debug('FetchPublisherInfoForCall:info-not-updated', ['call_id' => $call->id]);
            }
        } catch (Exception $e) {
            Log::debug('FetchPublisherInfoForCall:Exception: ' . $e->getMessage());
        }
    }
}
