<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioForwardingController extends Controller
{
    public function ringba()
    {
        $callerId = '+13186978047'; // Make sure this is a Twilio number you own or a verified caller ID
        $forwardingNumber = '+18559740661'; // Ringba forwarding number

        $response = new VoiceResponse();
        $response->say('Thanks for the call. We are connecting you now.', ['voice' => 'woman']);
        $dial = $response->dial('', ['callerId' => $callerId]);
        $dial->number($forwardingNumber);

        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    public function retreaver()
    {
        $callerId = '+13186978047'; // Make sure this is a Twilio number you own or a verified caller ID
        $forwardingNumber = '+18662140491'; // Retreaver forwarding number

        $response = new VoiceResponse();
        $response->say('Thanks for the call. We are connecting you now.', ['voice' => 'woman']);
        $dial = $response->dial('', ['callerId' => $callerId]);
        $dial->number($forwardingNumber);

        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}