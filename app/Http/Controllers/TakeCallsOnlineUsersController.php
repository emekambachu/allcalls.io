<?php

namespace App\Http\Controllers;

use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Events\OnlineUserListUpdated;

class TakeCallsOnlineUsersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'call_type_id' => 'required'
        ]);

        if ($request->user()->balance < 35) {
            return redirect()->back()->withErrors([
                'balance' => 'You must have at least $35 in your account to take calls.'
            ]);
        }

        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;

        // Retrieve the call_type_id from the request
        $callTypeId = $request->call_type_id;
        $callType = CallType::findOrFail($callTypeId);

        OnlineUser::where('user_id', $request->user()->id)->delete();

        OnlineUser::updateOrInsert(
            ['user_id' => $userId],
            ['call_type_id' => $callTypeId]
        );

        // Dispatch the event
        OnlineUserListUpdated::dispatch();

        // Return a response
        return redirect()->back()->with(['message' => "Listening to calls for {$callType->type}."]);
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

            // Dispatch the event
            OnlineUserListUpdated::dispatch();
        }

        return redirect()->back()->with(['message' => 'Stopped listening for calls.']);
    }
}
