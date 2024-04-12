<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendCallsToOnScriptAfterFiveMinutes;

class OnScriptDelayedAPIController extends Controller
{
    public function delayDispatch(Request $request)
    {
        Log::debug('OnScriptDelayedAPIController:delayDispatch', $request->all());

        $agentName = $request->query('agent_name');
        $url = $request->query('url');

        // Parse the timestamp and format it
        try {
            $timestampDateTime = new DateTime($request->query('timestamp'));
            $formattedTimestamp = $timestampDateTime->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            // Handle the case where the timestamp format is invalid
            return response()->json(['error' => 'Invalid timestamp format'], 400);
        }

        $disposition = $request->query('disposition');
        $clientPhone = $request->query('client_phone');

        Log::debug('OnScriptData:', [
            'agentName' => $agentName,
            'url' => $url,
            'timestamp' => $formattedTimestamp,
            'disposition' => $disposition,
            'clientPhone' => $clientPhone,
        ]);

        $apiKey = $request->query('api_key') ?? null;

        SendCallsToOnScriptAfterFiveMinutes::dispatch($agentName, $url, $formattedTimestamp, $disposition, $clientPhone, $apiKey)
            ->delay(now()->addMinutes(5));

        return response()->json(['message' => 'Call info will be sent in 5 minutes.']);
    }
}
