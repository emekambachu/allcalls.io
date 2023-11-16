<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ZoomMeeting;

class ZoomMeetingNotificationController extends Controller
{
    public function sendZoomMeetingNotification(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_ids' => 'required',
            'title' => 'required|string',
            'message' => 'required|string',
            'zoomLink' => 'nullable|url',
        ]);

        // Extract request data
        $userIds = $request->user_ids;
        $title = $request->title;
        $message = $request->message;
        $zoomLink = $request->zoomLink;

        // Check if user_ids is an array or a single value
        if (!is_array($userIds)) {
            $userIds = [$userIds]; // Convert to an array
        }

        // Validate each user ID (optional, depending on your requirements)
        foreach ($userIds as $userId) {
            if (!User::where('id', $userId)->exists()) {
                return response()->json(['message' => 'Invalid user ID: ' . $userId], 422);
            }
        }

        // Send notification to each user
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new ZoomMeeting($title, $message, $zoomLink));
            }
        }

        return response()->json(['message' => 'Notifications sent successfully!']);
    }
}
