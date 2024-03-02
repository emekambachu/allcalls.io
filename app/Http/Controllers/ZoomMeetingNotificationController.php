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
use App\Notifications\TextMessageNotification;

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
        $batchSize = 35; // You can adjust this number based on your server capacity
       
        foreach ($users->chunk($batchSize) as $index => $batch) {
            $batchStart = microtime(true); // Get start time for the batch
            Log::info("Starting batch of notifications", [
                'batch_number' => $index + 1,
                'batch_size' => count($batch),
                'user_ids' => $batch->pluck('id')->toArray() // Log the user IDs in the batch
            ]);
        
            try {

                // Existing check for sending email notifications
                if ($sendEmail && $emailData) {
                    Log::info('EmailNotification queued', ['time' => now()->toDateTimeString()]);
                    // Send email notifications on 'emails' queue
                    Notification::send($batch, (new EmailNotification($emailData))->onQueue('emails'));
                }
                
                // Check if sendNotification is true before sending the ZoomMeeting notification
                if ($sendNotification) {
                    Log::info('ZoomMeetingNotification queued', ['time' => now()->toDateTimeString()]);
                    Notification::send($batch, new ZoomMeeting($title, $message, $sendNotification, $zoomLink)); // Assuming 'default' or specify your queue
                }

                // Check and send text messages separately
                if (!empty($textMessageString)) {
                    Log::info('TextMessageNotification queued', ['time' => now()->toDateTimeString()]);
                    // Send text message notifications on 'text-messages' queue
                    Notification::send($batch, (new TextMessageNotification($textMessageString))->onQueue('text-messages'));
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

             // Delay for 2 seconds before processing the next batch
            sleep(2);
        }
       
        return response()->json(['message' => 'Notifications queued for sending.']);
    }


    // public function sendBatchTextMessages(Request $request)
    // {
    //     // Log the incoming request data
    //     Log::info('Received request for sending batch text messages', [
    //         'requestPayload' => $request->all(), // Logs all request input data
    //     ]);
        
    //     $request->validate([
    //         'textMessageString' => 'required|string',
    //         'numbers' => 'required|array',
    //         'numbers.*' => 'required|string', // Validate each item in the array
    //     ]);

    //     $textMessageString = $request->textMessageString;
    //     $numbers = collect($request->numbers); // Convert to a collection for easy manipulation
    //     $batchSize = 50; // Define the batch size
    //     $sleepSeconds = 2; // Define the sleep duration between batches

    //     Log::info('Starting to send batch text messages', ['totalNumbers' => $numbers->count()]);

    //     // Process each batch of numbers
    //     $numbers->chunk($batchSize)->each(function ($batch, $index) use ($textMessageString, $sleepSeconds) {
    //         Log::info('Processing text message batch', ['batchIndex' => $index + 1, 'batchSize' => count($batch)]);
    //         $user = User::whereEmail('john@example.com')->first();

    //         foreach ($batch as $number) {
    //             Notification::send($user, (new TextMessageNotification($textMessageString, $number))->onQueue('text-messages'));
    //         }

    //         Log::info('Completed text message batch', ['batchIndex' => $index + 1]);
    //         Log::info('Sleeping between batches', ['sleepSeconds' => $sleepSeconds]);
    //         sleep($sleepSeconds);
    //     });

    //     return response()->json(['message' => 'Text messages queued for sending.']);
    // }
}