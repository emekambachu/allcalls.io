<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioConferenceCallController extends Controller
{
    /**
     * Connect the caller directly to the conference.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function directToConference(Request $request)
    {
        $response = new VoiceResponse();
        
        // Directly dial into the conference room
        $dial = $response->dial();
        $dial->conference('MyConferenceRoom', [
            'waitUrl' => 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.classical'
        ]);

        // Return the TwiML as a string
        return response($response)->header('Content-Type', 'text/xml');
    }
}
