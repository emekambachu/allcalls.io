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

    public $agentName;
    public $url;
    public $timestamp;
    public $disposition;
    public $clientPhone;
    public $apiKey = null;
    // public $delay = 5; // Delay the job execution by 300 seconds (5 seconds)

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agentName, $url, $timestamp, $disposition, $clientPhone, $apiKey = null)
    {
        $this->agentName = $agentName;
        $this->url = $url;
        $this->timestamp = $timestamp;
        $this->disposition = $disposition;
        $this->clientPhone = $clientPhone;
        if ($apiKey) {
            $this->apiKey = $apiKey;
        }
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
            'url' => $this->url . '-all.mp3',
            'timestamp' => $this->timestamp,
            'disposition' => $this->disposition,
            'clientPhone' => $this->clientPhone,
        ]);

        $queryParams = [
            'agent_name' => $this->agentName,
            'url' => $this->url . '-all.mp3',
            'timestamp' => $this->timestamp,
            'disposition' => $this->disposition,
            'client_phone' => $this->clientPhone,
            'api_key' => $this->apiKey ?? '5483a4c8-1dbf-4ab2-af5d-3cb1db1e11a3',
        ];

        Log::debug('SendCallInfoIn5MinsFinalQueryParams:', $queryParams);

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
