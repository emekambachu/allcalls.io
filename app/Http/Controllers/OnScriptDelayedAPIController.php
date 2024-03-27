<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendCallsToOnScriptAfterFiveMinutes;

class OnScriptDelayedAPIController extends Controller
{
    public function delayDispatch(Request $request)
    {
        $agentName = $request->query('agent_name');
        $url = $request->query('url');

        // Parse the timestamp and format it
        try {
            $timestampDateTime = new \DateTime($request->query('timestamp'));
            $formattedTimestamp = $timestampDateTime->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Handle the case where the timestamp format is invalid
            return response()->json(['error' => 'Invalid timestamp format'], 400);
        }

        $disposition = $request->query('disposition');
        $agentId = $request->query('agent_id');
        $clientPhone = $request->query('client_phone');

        SendCallsToOnScriptAfterFiveMinutes::dispatch($agentName, $url, $formattedTimestamp, $disposition, $agentId, $clientPhone)
            ->delay(now()->addMinutes(5));

        return response()->json(['message' => 'Call info will be sent in 5 minutes.']);
    }
}
