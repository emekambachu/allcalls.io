<?php

namespace App\Listeners;

use App\Events\PolicySubmitedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PolicySubmited
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
    public function handle(PolicySubmitedEvent $event): void
    {
        try {
            $response = Http::withHeaders([
                'Api-Token' => env('SENDBIRD_API_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post('https://api-' . env('SENDBIRD_APPLICATION_ID') . '.sendbird.com/v3/group_channels/' . env('SENDBIRD_INTERNAL_AGENTS_GROUP_URL') . '/messages', [
                'user_id' => env('SENDBIRD_ADMIN_ID'),
                'message_type' => 'MESG',
                'message' => "{$event->business->client->first_name} {$event->business->client->last_name} just made a sale! Awais Testing",
            ]);
            dd($response);
            Log::debug("New Policy" . $response->json());
        } catch (Exception $e) {
            Log::error('PolicySubmitedEvent: ' . $e->getMessage());
        }
    }
}
