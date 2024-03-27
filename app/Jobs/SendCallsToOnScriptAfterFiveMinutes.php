<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCallsToOnScriptAfterFiveMinutes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $agentName;
    public $url;
    public $timestamp;
    public $disposition;
    public $agentId;
    public $clientPhone;
    public $delay = 10; // Delay the job execution by 300 seconds (5 minutes)

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agentName, $url, $timestamp, $disposition, $agentId, $clientPhone)
    {
        $this->agentName = $agentName;
        $this->url = $url;
        $this->timestamp = $timestamp;
        $this->disposition = $disposition;
        $this->agentId = $agentId;
        $this->clientPhone = $clientPhone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('SendCallInfoIn5Minutes', [
            'agentName' => $this->agentName,
            'url' => $this->url,
            'timestamp' => $this->timestamp,
            'disposition' => $this->disposition,
            'agentId' => $this->agentId,
            'clientPhone' => $this->clientPhone,
        ]);

        $queryParams = [
            'agent_name' => $this->agentName,
            'url' => $this->url,
            'timestamp' => $this->timestamp,
            'disposition' => $this->disposition,
            'agent_id' => $this->agentId,
            'client_phone' => $this->clientPhone,
            // Include the API key if needed, assuming it's stored as an environment variable
            'api_key' => env('ONSCRIPT_AI_API_KEY'),
        ];

        // Filter out null values
        $queryParams = array_filter($queryParams, function ($value) {
            return !is_null($value);
        });

        try {
            // Send the request to the correct endpoint
            $response = Http::get('https://app.onscript.ai/api/create_process_dialog', $queryParams);

            Log::debug('Dispatched Call Info to OnScript AI:', [
                'response' => $response->body(),
            ]);

            // Handle the response as needed
        } catch (Exception $e) {
            Log::error('Failed to Dispatch Call Info to OnScript AI:', [
                'message' => $e->getMessage(),
            ]);
        }
    }

}
