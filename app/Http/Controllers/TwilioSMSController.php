<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\MessagingResponse;


class TwilioSMSController extends Controller
{
    //
    public function receiveSMS(Request $request)
    {
        Log::debug('TwilioSMSController method receiveSMS is being executed');
        // Get the SMS data from the request

        Log::debug($request);

        $response = new MessagingResponse();
        Log::debug('Twilio new instance' . $response);

        $senderNumber = $request->input('From');
        $messageBody = $request->input('Body');

        // Log the details
        Log::debug("Received SMS from {$senderNumber}: {$messageBody}");

        // You can return a TwiML response or a simple confirmation
        return response()->json(['status' => 'Message received']);
    }
}
