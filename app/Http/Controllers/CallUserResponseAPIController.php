<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\CallAcceptedOrRejected;

class CallUserResponseAPIController extends Controller
{
    public function update(Request $request, $uniqueCallId)
    {
        Log::debug('CallUserResponseAPIController::update - endpoint hit');

        $request->validate([
            'user_response_time' => 'required',
        ]);

        $call = Call::whereUniqueCallId($uniqueCallId)->firstOrFail();
        Log::debug('CallUserResponseAPIController::update - call found');

        if ($call->user_id !== $request->user()->id) {
            Log::debug('CallUserResponseAPIController::update - user_id does not match');
            return response()->json([
                'message' => 'You are not authorized to update this call.',
            ], 403);
        }

        $call->user_response_time = Carbon::parse($request->user_response_time);
        $call->save();
        Log::debug('CallUserResponseAPIController::update - call updated successfully');

        CallAcceptedOrRejected::dispatch($request->user());

        return response()->json([
            'message' => 'Call updated successfully.',
        ], 200);
    }
}
