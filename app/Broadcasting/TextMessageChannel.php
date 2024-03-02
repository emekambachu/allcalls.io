<?php

namespace App\Broadcasting;

use Exception;
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
        
        $userPhone = $data['toDID'];
        // $userPhone = $this->formatPhoneNumber($notifiable->phone);
        Log::info('Sending SMS via TextMessageChannel', ['to' => $userPhone, 'from' => $data['fromDID']]);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $authString,
                'Content-Type' => 'application/json'
            ])->post("https://api.thinq.com/account/{$accountId}/product/origination/sms/send", [
                'from_did' => $data['fromDID'],  // Sender's number
                'to_did' => $userPhone, // Assuming the notifiable has a 'phone' attribute
                'message' => $data['textMessageString'] // Message content
            ]);
    
            // Log the response from the SMS service
            Log::info('SMS sent via TextMessageChannel', ['response' => $response->body()]);
        } catch (\Exception $e) {
            // Log the exception if something goes wrong
            Log::error('Error sending SMS via TextMessageChannel', ['exception' => $e->getMessage()]);
        }
    }
    
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the number has more than 10 digits and starts with '1' (country code for the US)
        if (strlen($phoneNumber) > 10 && strpos($phoneNumber, '1') === 0) {
            // Remove the leading '1' and return the first 10 digits
            $phoneNumber = substr($phoneNumber, 1, 10);
        }

        return $phoneNumber;
    }
}