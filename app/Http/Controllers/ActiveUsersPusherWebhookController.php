<?php

namespace App\Http\Controllers;

use App\Models\ActiveUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\ActiveUserListUpdated;

class ActiveUsersPusherWebhookController extends Controller
{
    public function store(Request $request)
    {
        // You might want to validate that this request actually comes from Pusher

        $eventName = $request['events'][0]['name']; // could only be member_added or member_removed.
        $userId = $request['events'][0]['user_id']; // corresponds to the 'id' column in the users table.

        Log::debug('Event name: ');
        Log::debug($eventName ?? 'N/A');

        Log::debug('User ID: ');
        Log::debug($userId ?? 'N/A');

        if (!$eventName || !$userId) {
            return ''; // If either is missing, then do nothing.
        }

        // If it's 'member_added'
        if ($eventName === 'member_added') {
            // Add a new record to the database, if it doesn't already exist
            Log::debug('member adding');
            ActiveUser::firstOrCreate([
                'user_id' => $userId,
                'status'  => 'Waiting'
            ]);

            ActiveUserListUpdated::dispatch($userId);
            Log::debug('After dispatching ActiveUserListUpdated');
        }
        // If it's 'member_removed'
        else if ($eventName === 'member_removed') {
            // If exists, delete it.
            Log::debug('member removing');
            $activeUser = ActiveUser::where('user_id', $userId)->first();
            if ($activeUser) {
                Log::debug('Attempting to delete user');
                $activeUser->delete();
                Log::debug('Deleted the active user');
                ActiveUserListUpdated::dispatch($userId);
                Log::debug('After dispatching ActiveUserListUpdated');
            }
        }

        return 'OK!';
    }
}
