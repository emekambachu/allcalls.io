<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CallUserResponseAPIController extends Controller
{
    public function update(Request $request, $uniqueCallId)
    {
        $request->validate([
            'user_response_time' => 'required',
        ]);

        $call = Call::whereUniqueCallId($uniqueCallId)->firstOrFail();

        if ($call->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to update this call.',
            ], 403);
        }

        $call->user_response_time = Carbon::parse($request->user_response_time);
        $call->save();

        return response()->json([
            'message' => 'Call updated successfully.',
        ], 200);
    }
}
