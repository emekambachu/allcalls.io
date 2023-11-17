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
            'sendEmail' => 'required|boolean',
            'emailData.title' => 'required_if:sendEmail,true|string',
            'emailData.buttonText' => 'nullable|string', // Optional
            'emailData.buttonUrl' => 'nullable|url',     // Optional
            'emailData.description' => 'required_if:sendEmail,true|string',
        ]);

        // Extract request data
        $userIds = $request->user_ids;
        $title = $request->title;
        $message = $request->message;
        $zoomLink = $request->zoomLink;
        $sendEmail = $request->sendEmail;
        $emailData = $sendEmail ? $request->emailData : null;

        // Convert user_ids to an array if it's not already
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        // Validate each user ID
        foreach ($userIds as $userId) {
            if (!User::where('id', $userId)->exists()) {
                return response()->json(['message' => 'Invalid user ID: ' . $userId], 422);
            }
        }

        // Send notification to each user
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new ZoomMeeting($title, $message, $zoomLink, $emailData));
            }
        }

        return response()->json(['message' => 'Notifications sent successfully!']);
    }
}