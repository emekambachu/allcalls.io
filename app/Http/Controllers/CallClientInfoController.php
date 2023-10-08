<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class CallClientInfoController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'unique_call_id' => 'required'
        ]);

        $uniqueCallId = $request->input('unique_call_id');

        // Find the Call record by unique_call_id
        $call = Call::where('unique_call_id', $uniqueCallId)->first();

        if ($call) {
            // Fetch the related Client
            $client = $call->client;

            if ($client) {
                // Return the client as a JSON response
                return response()->json([
                    'status' => 'success',
                    'client' => $client
                ]);
            } else {
                // Handle situation where Client is not found
                return response()->json([
                    'status' => 'error',
                    'message' => 'Client not found'
                ], 404);
            }
        } else {
            // Handle situation where Call is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Call not found'
            ], 404);
        }
    }
}
