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
        $timestamp = $request->query('timestamp');
        $disposition = $request->query('disposition');
        $agentId = $request->query('agent_id');
        $clientPhone = $request->query('client_phone');

        SendCallsToOnScriptAfterFiveMinutes::dispatch($agentName, $url, $timestamp, $disposition, $agentId, $clientPhone)
            ->delay(now()->addMinutes(5));

        return response()->json(['message' => 'Call info will be sent in 5 minutes.']);
    }
}
