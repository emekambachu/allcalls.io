<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;

class TextMessageChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        $data = $notification->toTextMessage($notifiable);
    
        $accountId = env('COMMIO_ACCOUNT_ID'); // Replace with your actual account ID
        $authString = env('COMMIO_AUTH_STRING'); // Your encoded auth string
    
        Log::info('Sending SMS via TextMessageChannel', ['to' => $notifiable->phone, 'from' => $data['fromDID']]);
    
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $authString,
                'Content-Type' => 'application/json'
            ])->post("https://api.thinq.com/account/{$accountId}/product/origination/sms/send", [
                'from_did' => $data['fromDID'],  // Sender's number
                'to_did' => $notifiable->phone, // Assuming the notifiable has a 'phone' attribute
                'message' => $data['textMessageString'] // Message content
            ]);
    
            // Log the response from the SMS service
            Log::info('SMS sent via TextMessageChannel', ['response' => $response->body()]);
        } catch (\Exception $e) {
            // Log the exception if something goes wrong
            Log::error('Error sending SMS via TextMessageChannel', ['exception' => $e->getMessage()]);
        }
    }
    
}