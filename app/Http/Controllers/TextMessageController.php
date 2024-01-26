<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Plivo\RestClient;

class TextMessageController extends Controller
{
    //
    public function sendMessage()
    {
        $client = new RestClient(env('COMMIO_AUTH_ID'), env('COMMIO_AUTH_TOKEN'));

        $message_created = $client->messages->create(
            '+14156667777', // Sender's phone number
            ['+14156667778'], // Receiver's phone number
            'Hello, this is a test message from Commio!' // Text of the message
        );

        return response()->json([
            'message' => 'SMS sent successfully',
            'message_id' => $message_created->getMessageUuid(0)
        ]);
    }
}
