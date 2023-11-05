<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AvailableAgentsAPIController extends Controller
{
    public function show()
    {
        $response = Http::get('https://www.winning.today/posting/purpose-health-availability-ping');

        Log::debug('api-logs:available-agents:response-body', ['responseBody' => $response->body()]);

        return [
            'available_agents' => json_decode($response->body())->purpose_health_available_agents
        ];
    }
}
