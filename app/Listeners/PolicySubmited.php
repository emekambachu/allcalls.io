<?php

namespace App\Listeners;

use Exception;
use App\Models\User;
use App\Events\PolicySubmitedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
            // Assuming the business's client is accessible via $event->business->client
            $client = $event->business->client;

            // Check if client is attached and the status starts with "Sale"
            if ($client && strpos($client->status, 'Sale') === 0) {
                $agentId = $event->business->agent_id;

                $agent = User::find($agentId);

                if (!$agent) {
                    throw new Exception("Agent not found with ID: $agentId");
                }

                $response = Http::withHeaders([
                    'Api-Token' => env('SENDBIRD_API_TOKEN'),
                    'Content-Type' => 'application/json',
                ])->post('https://api-' . env('SENDBIRD_APPLICATION_ID') . '.sendbird.com/v3/group_channels/' . env('SENDBIRD_INTERNAL_AGENTS_GROUP_URL') . '/messages', [
                    'user_id' => env('SENDBIRD_ADMIN_ID'),
                    'message_type' => 'MESG',
                    'message' => "{$agent->first_name} {$agent->last_name} just made a sale!",
                ]);

                Log::debug("New Policy" . $response->body());
            } else {
                // Log or handle cases where client status does not start with "Sale" or client is not attached
                Log::info('PolicySubmitedEvent: Client status does not start with "Sale" or client is not attached.');
            }
        } catch (Exception $e) {
            Log::error('PolicySubmitedEvent: ' . $e->getMessage());
        }
    }
}
