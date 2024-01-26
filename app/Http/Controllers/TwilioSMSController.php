<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioSMSController extends Controller
{
    //
    public function receiveSMS(Request $request)
    {
        // Get the SMS data from the request
        $senderNumber = $request->input('From');
        $messageBody = $request->input('Body');

        // Log the details
        Log::debug("Received SMS from {$senderNumber}: {$messageBody}");

        // You can return a TwiML response or a simple confirmation
        return response()->json(['status' => 'Message received']);
    }
}
