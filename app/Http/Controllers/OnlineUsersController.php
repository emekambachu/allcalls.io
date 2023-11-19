<?php

namespace App\Http\Controllers;

use App\Models\CallType;
use App\Models\OnlineUser;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\OnlineUserListUpdated;

class OnlineUsersController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all online users with the specified user ID.
        $onlineCallTypes = OnlineUser::where('user_id', $request->user()->id)->get();

        // Count the number of records
        $count = $onlineCallTypes->count();

        // Delete all except the first record if more than one exists.
        if ($count > 1) {
            // Delete all except the first one.
            $firstRecord = $onlineCallTypes->first();
            $firstId = $firstRecord->id; // Get the ID of the first record.
            OnlineUser::where('user_id', $request->user()->id)->where('id', '!=', $firstId)->delete();

            return ['onlineCallType' => $firstRecord->call_type_id];
        } elseif ($count === 1) {
            return ['onlineCallType' => $onlineCallTypes->first()->call_type_id];
        } else {
            // No matching records.
            return ['onlineCallType' => null];
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'call_type_id' => 'required|exists:call_types,id',
        ]);

        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;

        // Retrieve the call_type_id from the request
        $callTypeId = $request->call_type_id;

        OnlineUser::where('user_id', $request->user()->id)->delete();

        OnlineUser::updateOrInsert(
            ['user_id' => $userId],
            ['call_type_id' => $callTypeId]
        );

        Log::debug('Dispatching OnlineUserListUpdated');

        // Dispatch the event
        OnlineUserListUpdated::dispatch();

        Log::debug('online-user-logs:online', [
            'user_id' => $userId,
            'call_type_id' => $callTypeId,
            'platform' => 'mobile',
        ]);

        // Return a response
        return response()->json(['status' => 'success'], 200);
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

            Log::debug('online-user-logs:offline', [
                'user_id' => $request->user()->first_name . ' ' . $request->user()->last_name,
                'call_type' => CallType::find($callTypeId)->type,
                'platform' => 'mobile',
            ]);

            UserActivity::create([
                'action' => 'Offline for vertical ' . CallType::find($callTypeId)->type . '.',
                'data' => json_encode(['call_type_id' => $callTypeId]),
                'platform' => 'web',
                'user_id' => $request->user()->id,
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        // If no record found, return a response indicating the absence of the record
        return response()->json(['message' => 'failed'], 200);
    }

    public function destroyOnLogout(Request $request)
    {
        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;

        // Find the record with user_id and call_type_id
        $record = OnlineUser::where('user_id', $userId)
            ->first();

        // If the record exists, delete it
        if ($record) {
            $record->delete();

            // Dispatch the event
            OnlineUserListUpdated::dispatch();

            Log::debug('online-user-logs:offline', [
                'user_id' => $request->user()->first_name . ' ' . $request->user()->last_name,
                'call_type' => CallType::find($record->call_type_id)->type,
                'platform' => 'mobile',
            ]);

            UserActivity::create([
                'action' => 'Offline for vertical ' . CallType::find($record->call_type_id)->type . '.',
                'data' => json_encode(['call_type_id' => $record->call_type_id]),
                'platform' => 'web',
                'user_id' => $request->user()->id,
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        // If no record found, return a response indicating the absence of the record
        return response()->json(['message' => 'failed'], 200);
    }
}
