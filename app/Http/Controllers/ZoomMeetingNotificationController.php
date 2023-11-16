<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ZoomMeeting;

class ZoomMeetingNotificationController extends Controller
{
    public function sendZoomMeetingNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'message' => 'required|string',
            'zoomLink' => 'required|url',
        ]);

        $user = User::find($request->user_id);
        $title = $request->title;
        $message = $request->message;
        $zoomLink = $request->zoomLink;

        $user->notify(new ZoomMeeting($title, $message, $zoomLink));

        return response()->json(['message' => 'Notification sent successfully!']);
    }

}
