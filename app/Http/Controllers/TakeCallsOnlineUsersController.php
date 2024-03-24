<?php

namespace App\Http\Controllers;

use App\Models\CallType;
use App\Models\OnlineUser;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\OnlineUserListUpdated;

class TakeCallsOnlineUsersController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('Entering store function');

        $validationResult = $request->validate([
            'call_type_id' => 'required'
        ]);
        Log::debug('Validation result', ['result' => $validationResult]);
    
        if ($request->user()->balance < 35) {
            Log::debug('Insufficient balance', ['balance' => $request->user()->balance]);
            return redirect()->back()->withErrors([
                'balance' => 'You must have at least $35 in your account to take calls.'
            ]);
        }
    
        // Retrieve the authenticated user's ID
        $userId = $request->user()->id;
        Log::debug('Retrieved user ID', ['userId' => $userId]);
    
        // Retrieve the call_type_id from the request
        $callTypeId = $request->call_type_id;
        Log::debug('Retrieved call_type_id', ['callTypeId' => $callTypeId]);
    
        try {
            $callType = CallType::findOrFail($callTypeId);
            Log::debug('Found call type', ['callType' => $callType]);
        } catch (\Exception $e) {
            Log::error('Call type not found', ['callTypeId' => $callTypeId, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['call_type_id' => 'Call type not found.']);
        }
    
        try {
            OnlineUser::create([
                'user_id' => $userId,
                'call_type_id' => $callTypeId
            ]);
            Log::debug('Online user created', ['userId' => $userId, 'callTypeId' => $callTypeId]);
        } catch (\Exception $e) {
            Log::error('Failed to create online user', ['userId' => $userId, 'callTypeId' => $callTypeId, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['online_user' => 'Failed to create online user.']);
        }
    
        Log::debug('online-user-logs:online', [
            'user_id' => $userId,
            'full_name' => $request->user()->first_name . ' ' . $request->user()->last_name,
            'call_type' => $callType->type,
            'platform' => 'web',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    
        UserActivity::create([
            'action' => 'Online for vertical ' . $callType->type . '.',
            'data' => json_encode(['call_type_id' => $callTypeId]),
            'platform' => 'web',
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    
        $user = $request->user();
        $user->last_login_platform = 'web';
        $user->save();
        Log::debug('User last login platform updated', ['userId' => $userId, 'platform' => 'web']);
    
        // Dispatch the event
        // OnlineUserListUpdated::dispatch();
    
        // Return a response
        Log::debug('Exiting store function', ['message' => "Listening to calls for {$callType->type}."]);
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

            Log::debug('online-user-logs:offline', [
                'full_name' => $request->user()->first_name . ' ' . $request->user()->last_name,
                'call_type' => CallType::find($callTypeId)->type,
                'user_id' => $request->user()->id,
                'platform' => 'web',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            UserActivity::create([
                'action' => 'Offline for vertical ' . CallType::find($callTypeId)->type . '.',
                'data' => json_encode(['call_type_id' => $callTypeId]),
                'platform' => 'web',
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        return redirect()->back()->with(['message' => 'Stopped listening for calls.']);
    }
}
