<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallTypeNumber;
use App\Models\AvailableNumber;
use Illuminate\Support\Facades\Log;

class IncomingCallController extends Controller
{
    public function respond(Request $request)
    {
        $twiml = '<?xml version="1.0" encoding="UTF-8"?>';

        // Check if the "To" attribute exists in the request and log it
        if ($request->has('To')) {
            $to = $request->input('To');
            Log::debug('To attribute: ' . $to);

            // Remove the "+1" from the beginning of the "To" number
            $to = substr($to, 2);

            // Check if the number exists in the AvailableNumber model
            $availableNumber = AvailableNumber::where('phone', $to)->first();
            if ($availableNumber) {
                Log::debug('Number found in AvailableNumber model: ' . $to);
                $twiml = $this->handleAvailableNumberCall($to);
            }

            // Check if the number exists in the CallTypeNumber model
            $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
            if ($callTypeNumber) {
                Log::debug('Number found in CallTypeNumber model: ' . $to);
                // You can handle CallTypeNumber logic here or continue with your previous TWiML
                $twiml .= '<Response><Say voice="alice" language="en-US">Hello, this is a test of the Say verb. Hope you have a great day!</Say></Response>';
            }
        } else {
            $twiml .= '<Response><Say voice="alice" language="en-US">Hello, this is a test of the Say verb. Hope you have a great day!</Say></Response>';
        }

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
    
    public function handleAvailableNumberCall($to)
    {
        // Assume that the user_id is associated with the number in AvailableNumber
        $availableNumber = AvailableNumber::where('phone', $to)->first();
        $userId = (string) $availableNumber->user_id; // Ensure user id is a string

        Log::debug('User ID associated with the number: ' . $userId);

        // Dial to a specific client in your Twilio client application with a specified callerId
        $twiml = '<Response><Dial callerId="+13186978047"><Client>' . $userId . '</Client></Dial></Response>';

        return $twiml;
    }
}
