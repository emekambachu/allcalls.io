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
            }

            // Check if the number exists in the CallTypeNumber model
            $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
            if ($callTypeNumber) {
                Log::debug('Number found in CallTypeNumber model: ' . $to);
            }
        }

        $twiml = '<?xml version="1.0" encoding="UTF-8"?>';
        $twiml .= '<Response><Say voice="alice" language="en-US">Hello, this is a test of the Say verb. Hope you have a great day!</Say></Response>';

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
}
