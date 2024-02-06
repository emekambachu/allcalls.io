<?php

namespace App\Listeners;

use Exception;
use App\Models\Call;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCallInfoToOnScriptAI implements ShouldQueue
{
    /**
     * 
     * After 30 seconds of initiating the call, send the call info to OnScript AI
     */
    protected $delay = 30;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $call = Call::whereUniqueCallId($event->uniqueCallId)->first();
        $agent = $event->user;

        Log::debug('SendCallInfoToOnScriptAIData:', [
            'agent' => $agent,
            'call' => $call,
            'client' => $call->client,
        ]);

        if ($call && $agent) {
            $params = [
                'agent_name' => $agent->first_name . ' ' . $agent->last_name,
                'api_key' => env('ONSCRIPT_AI_API_KEY'),
                'url' => $call->recording_url ?? 'N/A',
                'first_name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'call_timestamp' => $call->created_at->format('Y-m-d H:i:s'),
                'affiliate_name' => $call->publisher_name ?? null,
                'call_disposition' => ($call->client && $call->client->status) ? $call->client->status : null,
                'agent_id' => $agent->id, // Added parameter
                'first_name' => ($call->client && $call->client->first_name) ? $call->client->first_name: null,
                'last_name' => ($call->client && $call->client->last_name) ? $call->client->last_name: null,
                'client_phone' => $call->from,
                'client_address' => ($call->client && $call->client->address) ? $call->client->address : null,
                'client_zipcode' => ($call->client && $call->client->zipCode) ? $call->client->zipCode : null,
            ];

            // Filter out null values
            $queryParams = array_filter($params, function ($value) {
                return !is_null($value);
            });

            Log::debug('SendCallInfoToOnScriptAIQueryParams:', [
                'queryParams' => $queryParams,
            ]);

            try {
                $response = Http::get('https://app.onscript.ai/api/create_process_dialog', $queryParams);

                Log::info('SendCallInfoToOnScriptAI Success:', [
                    'response' => $response->body(),
                ]);
            } catch (Exception $e) {
                Log::error('SendCallInfoToOnScriptAI Error:', [
                    'message' => $e->getMessage(),
                ]);
            }
        } else {
            Log::warning('SendCallInfoToOnScriptAI: Call or Agent not found', [
                'uniqueCallId' => $event->uniqueCallId,
            ]);
        }
    }
}
