<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use App\Events\RecordingSaved;
use Illuminate\Support\Facades\Log;

class CallRecordingController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('Recording callback fired');
        Log::debug($request->all());
        $call = Call::whereUserId($request->user_id)->whereUniqueCallId($request->unique_call_id)->first();
        $call->recording_url = $request->RecordingUrl;
        $call->save();

        RecordingSaved::dispatch($call, $call->callType, $call->user);
    }
}
