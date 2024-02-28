<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ZoomMeeting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

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
       
        foreach ($users->chunk($batchSize) as $index => $batch) {
            $batchStart = microtime(true); // Get start time for the batch
            Log::info("Starting batch of notifications", [
                'batch_number' => $index + 1,
                'batch_size' => count($batch),
                'user_ids' => $batch->pluck('id')->toArray() // Log the user IDs in the batch
            ]);
        
            try {

                if ($sendEmail && $emailData) {
                    Log::info('EmailNotification queued', ['time' => now()->toDateTimeString()]);
                    // Send email notifications on 'emails' queue
                    Notification::send($batch, (new EmailNotification($emailData))->onQueue('emails'));
                } else {
                    Notification::send($batch, new ZoomMeeting($title, $message, $sendNotification, $textMessageString, $zoomLink));
                }
                
                $batchEnd = microtime(true); // Get end time for the batch
                Log::info("Batch sent successfully", [
                    'batch_number' => $index + 1,
                    'duration' => $batchEnd - $batchStart // Log the duration of the batch processing
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to send batch", [
                    'batch_number' => $index + 1,
                    'error' => $e->getMessage()
                ]);
            }
        }
       
        return response()->json(['message' => 'Notifications queued for sending.']);
    }
}