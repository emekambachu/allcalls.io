<?php

namespace App\Http\Controllers;

use App\Models\OnlineUser;
use Illuminate\Http\Request;

class OnlineUsersController extends Controller
{
    public function store(Request $request)
    {
        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;

        // Retrieve the call_type_id from the request
        $callTypeId = $request->call_type_id;

        // Check if there's already a record with user_id and call_type_id
        $existingRecord = OnlineUser::where('user_id', $userId)
            ->where('call_type_id', $callTypeId)
            ->first();

        // If no record found, create a new one
        if (!$existingRecord) {
            OnlineUser::create([
                'user_id' => $userId,
                'call_type_id' => $callTypeId,
            ]);

            // Return a response, if required
            return response()->json(['status' => 'success'], 200);
        }

        // Return a response, if required
        return response()->json(['status' => 'failed'], 200);
    }

    public function destroy(Request $request, $callTypeId)
    {
        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;
    
        // Find the record with user_id and call_type_id
        $record = OnlineUser::where('user_id', $userId)
            ->where('call_type_id', $callTypeId)
            ->first();
    
        // If the record exists, delete it
        if ($record) {
            $record->delete();
            return response()->json(['status' => 'success'], 200);
        }
    
        // If no record found, return a response indicating the absence of the record
        return response()->json(['message' => 'failed'], 200);
    }

}