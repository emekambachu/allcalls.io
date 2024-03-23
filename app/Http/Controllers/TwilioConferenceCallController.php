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
        $statusCallbackUrl = route('conference.statusCallback');        
        // Directly dial into the conference room
        $dial = $response->dial();
        $dial->conference($conferenceName, [
            'waitUrl' => 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.classical',
            'statusCallback' => $statusCallbackUrl,
            'statusCallbackEvent' => 'start join leave end'
        ]);

        Log::info("Generated TwiML", ['twiml' => (string) $response]);

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
    


    // public function convertToConferenceWithNewNumber(Request $request)
    // {
    //     // Extract callSid and phoneNumber from the request's JSON payload
    //     $callSid = $request->json('callSid');
    //     $phoneNumber = $request->json('phoneNumber');
    
    //     // Log the incoming request details
    //     Log::info("Converting call to conference", ['callSid' => $callSid, 'phoneNumber' => $phoneNumber]);
    
    //     // Twilio credentials
    //     $accountSid = env('TWILIO_SID');
    //     $authToken = env('TWILIO_AUTH_TOKEN');
    //     $twilioNumber = env('TWILIO_PHONE_NUMBER'); // Your Twilio number that can make calls
    //     Log::info("Twilio credentials", ['accountSid' => $accountSid, 'authToken' => $authToken, 'twilioNumber' => $twilioNumber]);

    //     $client = new Client($accountSid, $authToken);
    
    //     // Unique name for the conference
    //     $conferenceName = 'Conference' . uniqid();
    
    //     // TwiML to join the ongoing call to a conference
    //     $twiml = '<Response><Dial><Conference>' . htmlspecialchars($conferenceName) . '</Conference></Dial></Response>';
    
    //     try {
    //         // Update the ongoing call to join the conference
    //         $callUpdateResponse = $client->calls($callSid)
    //             ->update(["twiml" => $twiml]);
    
    //         Log::info("Ongoing call updated to join conference", ['callSid' => $callSid, 'conferenceName' => $conferenceName]);
    
    //         // Create a new call to add the new participant to the same conference
    //         $newCallResponse = $client->calls->create(
    //             $phoneNumber, // The phone number to add to the conference
    //             $twilioNumber, // A number in your Twilio account that can make calls
    //             ["twiml" => $twiml]
    //         );
    
    //         Log::info("New participant added to conference", ['phoneNumber' => $phoneNumber, 'conferenceName' => $conferenceName]);
    
    //     } catch (\Exception $e) {
    //         // Log any errors
    //         Log::error("Error converting call to conference", ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'Failed to convert call to conference', 'error' => $e->getMessage()], 500);
    //     }
    
    //     return response()->json(['message' => 'Call converted to conference and participant added']);
    // }
    
    public function convertToConferenceWithNewNumber(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'callSid' => 'required|string',
            'otherCallSid' => 'nullable|string', // Second call SID to be joined
            'phoneNumber' => 'nullable|string', // New parameter for the third participant's phone number
        ]);
    
        // Extract callSid from the request
        $callSid = $validated['callSid'];
        $phoneNumber = $validated['phoneNumber']; 

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
        $twilioNumber = env('TWILIO_PHONE_NUMBER');
        
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

            // If a phone number is provided, dial out to this number and add to the conference
            if ($phoneNumber) {
                $twiml = "<Response><Dial><Conference>{$conferenceName}</Conference></Dial></Response>";

                $newCallResponse = $client->calls->create(
                    $phoneNumber, // The phone number to call and add to the conference
                    $twilioNumber, // Your Twilio number
                    ["twiml" => $twiml]
                );

                Log::info("New participant added to conference", ['phoneNumber' => $phoneNumber, 'conferenceName' => $conferenceName, 'newCallSid' => $newCallResponse->sid]);
                Log::info("Response from conversion to conference call: ", ['response' => $newCallResponse]);
            }
                
            // Log::info("Call made to: " . $call->to);
            Log::info("Call redirected to conference TwiML", ['callSid' => $callSid, 'conferenceName' => $conferenceName]);
    
            return response()->json(['message' => 'Conference call setup complete.']);
        } catch (\Exception $e) {
            Log::error("Error setting up conference call", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to set up conference call', 'details' => $e->getMessage()], 500);
        }
    }

    public function convertToConferenceWithUniqueCallId(Request $request)
    {
        // Validate the request parameters to include unique_call_id
        $validated = $request->validate([
            'unique_call_id' => 'required|string', // Use unique_call_id to find the call
            'phoneNumber' => 'required|string', // New parameter for the third participant's phone number
        ]);

        // Extract unique_call_id from the request
        $uniqueCallId = $validated['unique_call_id'];
        $phoneNumber = $validated['phoneNumber']; 

        // Attempt to retrieve the call from the database using unique_call_id
        $call = Call::where('unique_call_id', $uniqueCallId)->first();

        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        // Extract call_sid and parent_call_sid from the retrieved call
        $callSid = $call->call_sid;
        $otherCallSid = $call->parent_call_sid ?? null; // Use parent_call_sid if available

        // Twilio credentials from .env file
        $accountSid = env('TWILIO_SID'); // Ensure this is 'TWILIO_ACCOUNT_SID' in your .env
        $authToken = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_PHONE_NUMBER');

        // Initialize Twilio client
        $client = new Client($accountSid, $authToken);

        // Name for the conference
        $conferenceName = 'MyConference'; // This should be the same for all calls you want to merge

        try {
            // Redirect the call to the conference TwiML endpoint
            // Update both calls to join the same conference if otherCallSid is available
            $client->calls($callSid)
                ->update(["url" => route('conference.direct', ['conferenceName' => $conferenceName])]);

            if ($otherCallSid) {
                $client->calls($otherCallSid)
                    ->update(["url" => route('conference.direct', ['conferenceName' => $conferenceName])]);
            }

            Log::info("Call(s) updated to conference", [
                'callSid' => $callSid, 
                'otherCallSid' => $otherCallSid, 
                'conferenceName' => $conferenceName
            ]);

            // If a phone number is provided, dial out to this number and add to the conference
            if ($phoneNumber) {
                $twiml = "<Response><Dial><Conference>{$conferenceName}</Conference></Dial></Response>";

                $newCallResponse = $client->calls->create(
                    $phoneNumber, // The phone number to call and add to the conference
                    $twilioNumber, // Your Twilio number
                    ["twiml" => $twiml]
                );

                Log::info("New participant added to conference", ['phoneNumber' => $phoneNumber, 'conferenceName' => $conferenceName, 'newCallSid' => $newCallResponse->sid]);
            }

            Log::info("Conference call setup complete", ['uniqueCallId' => $uniqueCallId, 'conferenceName' => $conferenceName]);

            return response()->json(['message' => 'Conference call setup complete.']);
        } catch (\Exception $e) {
            Log::error("Error setting up conference call", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to set up conference call', 'details' => $e->getMessage()], 500);
        }
    }

    public function handleConferenceStatusCallback(Request $request)
    {
        // Capture the entire request body for logging
        $requestData = $request->all();

        // Log the entire request data
        Log::info('Conference status callback received', $requestData);

        // You can also extract and log specific parts of the request, if needed
        $conferenceSid = $request->input('ConferenceSid');
        $status = $request->input('StatusCallbackEvent');
        $callSid = $request->input('CallSid'); // SID of the participant who triggered the event

        Log::info("Conference SID: {$conferenceSid}, Status: {$status}, Call SID: {$callSid}");

        // Perform any other actions based on the status callback event
        switch ($status) {
            case 'conference-start':
                // The conference has started
                break;
            case 'participant-join':
                // A participant has joined the conference
                break;
            case 'participant-leave':
                // A participant has left the conference
                break;
            case 'conference-end':
                // The conference has ended
                break;
            // ... handle other events
        }

        // Return a 200 OK response to Twilio
        return response()->json(['message' => 'Status callback received and logged']);
    }
}
