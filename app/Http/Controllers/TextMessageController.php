<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Plivo\RestClient;

class TextMessageController extends Controller
{
    // //
    // public function sendMessage()
    // {
    //     $client = new RestClient(env('COMMIO_AUTH_ID'), env('COMMIO_AUTH_TOKEN'));

    //     $message_created = $client->messages->create(
    //         '+18006778036', // Sender's phone number
    //         ['+14156667778'], // Receiver's phone number
    //         'Hello, this is a test message from Commio!' // Text of the message
    //     );

    //     return response()->json([
    //         'message' => 'SMS sent successfully',
    //         'message_id' => $message_created->getMessageUuid(0)
    //     ]);
    // }

    public function sendMessage(Request $request)
    {
        $accountId = env('COMMIO_ACCOUNT_ID');  // Replace with your actual account ID
        // $username = 'your_username';      // Replace with your Commio username
        // $token = 'your_token';            // Replace with your Commio token
        // $authString = base64_encode("{$username}:{$token}");
        
        $authString = env('COMMIO_AUTH_STRING');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authString,
            'Content-Type' => 'application/json'
        ])->post("https://api.thinq.com/account/{$accountId}/product/origination/sms/send", [
            'from_did' => $request->input('from_did'),  // Sender's number
            'to_did' => $request->input('to_did'),      // Recipient's number
            'message' => $request->input('message')     // Message content
        ]);

        return $response->body();
    }
}
