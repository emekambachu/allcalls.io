<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ZoomMeeting;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ZoomMeetingNotificationController extends Controller
{
    public function sendZoomMeetingNotification(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'title' => 'nullable|string',
            'message' => 'nullable|string',
            'sendNotification' => 'required|boolean',
            'textMessageString' => 'nullable|string',
            'zoomLink' => 'nullable|url',
            'sendEmail' => 'required|boolean',
            'emailData.subject' => 'nullable|string',    
            'emailData.title' => 'required_if:sendEmail,true|string',
            'emailData.buttonText' => 'nullable|string',
            'emailData.buttonUrl' => 'nullable|url',
            'emailData.description' => 'required_if:sendEmail,true|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Extract request data
        $userIds = $request->user_ids;
        $title = $request->title;
        $message = $request->message;
        $sendNotification = $request->sendNotification;
        $textMessageString = $request->textMessageString;
        $zoomLink = $request->zoomLink;
        $sendEmail = $request->sendEmail;
        $emailData = $sendEmail ? $request->emailData : null;

        // Use Eloquent's whereIn method to retrieve all valid users in a single query
        $users = User::whereIn('id', $userIds)->get();

        // Send notification in batches to avoid overloading the system
        $batchSize = 50; // You can adjust this number based on your server capacity
        foreach ($users->chunk($batchSize) as $batch) {
            // Queue notifications for each user in the batch
            Notification::send($batch, new ZoomMeeting($title, $message, $sendNotification, $textMessageString, $zoomLink, $emailData));
        }

        return response()->json(['message' => 'Notifications queued for sending.']);
    }
}