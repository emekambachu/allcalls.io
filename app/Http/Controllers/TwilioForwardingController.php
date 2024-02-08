<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioForwardingController extends Controller
{
    public function ringba()
    {
        // Define the caller ID and the forwarding number
        $callerId = '+16189120411';
        $forwardingNumber = '+18559740661'; // Ringba forwarding number

        // Generate TwiML response to forward the call
        $response = new VoiceResponse();
        $response->say('Thanks for the call. We are connecting you now.', ['voice' => 'woman']);
        $dial = $response->dial(['callerId' => $callerId]);
        $dial->number($forwardingNumber);

        // Return the TwiML as a response
        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    public function retreaver()
    {
        // Define the caller ID and the forwarding number
        $callerId = 'your_twilio_number';
        $forwardingNumber = '+18662140491'; // Retreaver forwarding number

        // Generate TwiML response to forward the call
        $response = new VoiceResponse();
        $response->say('Thanks for the call. We are connecting you now.', ['voice' => 'woman']);
        $dial = $response->dial(['callerId' => $callerId]);
        $dial->number($forwardingNumber);

        // Return the TwiML as a response
        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}