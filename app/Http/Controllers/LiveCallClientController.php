<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiveCallClientController extends Controller
{
    public function index(Request $request) 
    {
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
