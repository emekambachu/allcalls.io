<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomingCallController extends Controller
{
    public function respond(Request $request)
    {
        // Check if the "To" attribute exists in the request and log it
        if ($request->has('To')) {
            $to = $request->input('To');
            Log::debug('To attribute: ' . $to);
        }

        $twiml = '<?xml version="1.0" encoding="UTF-8"?>';
        $twiml .= '<Response><Say voice="alice" language="en-US">Hello, this is a test of the Say verb. Hope you have a great day!</Say></Response>';

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
}
