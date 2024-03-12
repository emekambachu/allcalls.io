<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Log;

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


    public function convertToConference(Request $request)
    {
        // Extract callSid and phoneNumber from the request's JSON payload
        $callSid = $request->json('callSid');
        $phoneNumber = $request->json('phoneNumber');
    
        // Log the incoming request details
        Log::info("Converting call to conference", ['callSid' => $callSid, 'phoneNumber' => $phoneNumber]);
    
        // Twilio credentials
        $accountSid = env('TWILIO_TWIML_APP_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_PHONE_NUMBER'); // Your Twilio number that can make calls
        Log::info("Twilio credentials", ['accountSid' => $accountSid, 'authToken' => $authToken, 'twilioNumber' => $twilioNumber]);

        $client = new Client($accountSid, $authToken);
    
        // Unique name for the conference
        $conferenceName = 'Conference' . uniqid();
    
        // TwiML to join the ongoing call to a conference
        $twiml = '<Response><Dial><Conference>' . htmlspecialchars($conferenceName) . '</Conference></Dial></Response>';
    
        try {
            // Update the ongoing call to join the conference
            $callUpdateResponse = $client->calls($callSid)
                                          ->update(["twiml" => $twiml]);
    
            Log::info("Ongoing call updated to join conference", ['callSid' => $callSid, 'conferenceName' => $conferenceName]);
    
            // Create a new call to add the new participant to the same conference
            $newCallResponse = $client->calls->create(
                $phoneNumber, // The phone number to add to the conference
                $twilioNumber, // A number in your Twilio account that can make calls
                ["twiml" => $twiml]
            );
    
            Log::info("New participant added to conference", ['phoneNumber' => $phoneNumber, 'conferenceName' => $conferenceName]);
    
        } catch (\Exception $e) {
            // Log any errors
            Log::error("Error converting call to conference", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to convert call to conference', 'error' => $e->getMessage()], 500);
        }
    
        return response()->json(['message' => 'Call converted to conference and participant added']);
    }
    

}
