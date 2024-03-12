<?php

namespace App\Http\Controllers;

use App\Models\Call;
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
        
        $conferenceName = $request->input('conferenceName', 'DefaultConferenceName');        
        // Directly dial into the conference room
        $dial = $response->dial();
        $dial->conference($conferenceName, [
            'waitUrl' => 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.classical'
        ]);

        // Return the TwiML as a string
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function convertToConference(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'callSid' => 'required|string',
            'otherCallSid' => 'nullable|string', // Second call SID to be joined
        ]);
    
        // Extract callSid from the request
        $callSid = $validated['callSid'];
        // $otherCallSid = $validated['otherCallSid'];

        // Attempt to retrieve the call from the database using callSid
        $call = Call::where('call_sid', $callSid)->first();

        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        // Use the parent_call_sid as otherCallSid if otherCallSid is not provided
        $otherCallSid = $validated['otherCallSid'] ?? $call->parent_call_sid;

        if (is_null($otherCallSid)) {
            return response()->json(['error' => 'Other call SID is required but not provided or available'], 400);
        }

        // Twilio credentials from .env file
        $accountSid = env('TWILIO_SID'); // Ensure this is 'TWILIO_ACCOUNT_SID' in your .env
        $authToken = env('TWILIO_AUTH_TOKEN');
    
        // Initialize Twilio client
        $client = new Client($accountSid, $authToken);
    
        // Name for the conference
        $conferenceName = 'MyConference'; // This should be the same for all calls you want to merge
    
        try {
            // Redirect the call to the conference TwiML endpoint
            // Update both calls to join the same conference
            $firstLeg = $client->calls($callSid)
                ->update(["url" => route('conference.direct', ['conferenceName' => $conferenceName])]);
            
            $secondLeg = $client->calls($otherCallSid)
                ->update(["url" => route('conference.direct', ['conferenceName' => $conferenceName])]);

            Log::info("Both calls updated to conference", [
                'firstCallSid' => $firstLeg->sid, 
                'secondCallSid' => $secondLeg->sid, 
                'conferenceName' => $conferenceName
            ]);
                
            // Log::info("Call made to: " . $call->to);
            Log::info("Call redirected to conference TwiML", ['callSid' => $callSid, 'conferenceName' => $conferenceName]);
    
            return response()->json(['message' => 'Call redirected to join the conference.']);
        } catch (\Exception $e) {
            Log::error("Error redirecting call to conference TwiML", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to redirect call to conference TwiML', 'details' => $e->getMessage()], 500);
        }
    }
    


    public function convertToConferenceWithNewNumber(Request $request)
    {
        // Extract callSid and phoneNumber from the request's JSON payload
        $callSid = $request->json('callSid');
        $phoneNumber = $request->json('phoneNumber');
    
        // Log the incoming request details
        Log::info("Converting call to conference", ['callSid' => $callSid, 'phoneNumber' => $phoneNumber]);
    
        // Twilio credentials
        $accountSid = env('TWILIO_SID');
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
