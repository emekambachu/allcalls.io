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

        Log::debug('api-logs:available-agents:response-body', $response->body());

        return $response->body();
    }
}
