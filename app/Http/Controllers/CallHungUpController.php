<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\CallAcceptedOrRejected;

class CallHungUpController extends Controller
{
    public function update(Request $request, $uniqueCallId)
    {
        Log::debug('CallRejectedByAgent::reject - endpoint hit');
        Log::debug('All Requests:', $request->all());

        $call = Call::whereUniqueCallId($uniqueCallId)->firstOrFail();
        Log::debug('CallRejectedByAgent::reject - call found');

        if ($call->user_id !== $request->user()->id) {
            Log::debug('CallUserResponseAPIController::update - user_id does not match');
            return response()->json([
                'message' => 'You are not authorized to update this call.',
            ], 403);
        }

        $call->hung_up_by = 'Agent';
        $call->save();
        Log::debug('CallUserResponseAPIController::update - hung up by for the call updated successfully');

        return response()->json([
            'message' => 'Call updated successfully.',
        ], 200);
    }

    public function updateForWeb(Request $request, $uniqueCallId)
    {
        Log::debug('CallRejectedByAgent::updateForWeb - endpoint hit');
        Log::debug('All Requests:', $request->all());

        $call = Call::whereUniqueCallId($uniqueCallId)->firstOrFail();
        Log::debug('CallRejectedByAgent::updateForWeb - call found');

        if ($call->user_id !== $request->user()->id) {
            Log::debug('CallUserResponseAPIController::updateForWeb - user_id does not match');
            return response()->json([
                'message' => 'You are not authorized to update this call.',
            ], 403);
        }

        $call->hung_up_by = 'Agent';
        $call->save();
        Log::debug('CallUserResponseAPIController::updateForWeb - hung up by for the call updated successfully');

        return response()->json([
            'message' => 'Call updated successfully.',
        ], 200);
    }
}
