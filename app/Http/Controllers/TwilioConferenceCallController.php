<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Models\ConferenceCall;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Log;
use App\Models\ConferenceParticipant;
use Illuminate\Support\Facades\Cache;
use App\Events\ConferenceCallThirdPartyJoined;
use App\Events\ConferenceCallThirdPartyRinging;

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
            'statusCallbackEvent' => 'start join leave end',
            'endConferenceOnExit' => 'false'
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
        $conferenceName = 'MyConference' . uniqid(); // This should be the same for all calls you want to merge
    
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

            // Attempt to find or create the conference call
            $conferenceCall = ConferenceCall::firstOrCreate([
                'name' => $conferenceName,
            ], [
                'status' => 'initiated',
                'call_id' => $call->id,
            ]);

            Log::info("Conference Call saved to database: " . $conferenceCall); 
            
            $specialCallToken = Cache::get('specialCallToken');
            $storedCallerId = Cache::get("incoming_caller_id");

            // If a phone number is provided, dial out to this number and add to the conference
            if ($phoneNumber) {
                $twiml = "<Response><Dial><Conference>{$conferenceName}</Conference></Dial></Response>";

                $newCallResponse = $client->calls->create(
                    $phoneNumber, // The phone number to call and add to the conference
                    $twilioNumber, // Your Twilio number
                    // ["twiml" => $twiml]
                    [
                        "twiml" => $twiml,
                        "callToken" => $specialCallToken,
                        "StatusCallback" => route('conference.statusCallback'),
                        "StatusCallbackEvent" => ["initiated", "ringing", "answered", "completed"],
                        // "callerId" => $call->from
                        "callerId" => $storedCallerId
                    ]
                );

                Log::info("New participant added to conference", ['fromNumber' => $call->from, 'phoneNumber' => $phoneNumber, 'conferenceName' => $conferenceName, 'newCallSid' => $newCallResponse->sid]);
                Log::info("Response from conversion to conference call: ", ['response' => $newCallResponse]);
            }

            // Adding first and second legs with a default 'connected' status
            foreach ([$firstLeg, $secondLeg] as $leg) {
                if ($leg) {
                    $conferenceCall->participants()->create([
                        'sid' => $leg->sid,
                        'status' => 'connected', // Default status for existing participants                        
                    ]);
                }
            }

            // Adding the third party with a 'ringing' status, if applicable
            if (isset($newCallResponse)) {
                $thirdPartyParticipant = $conferenceCall->participants()->create([
                    'sid' => $newCallResponse->sid,
                    'status' => 'ringing', // Specific status for the new call
                    'phone_number' => $phoneNumber,
                    'is_third_party' => true
                ]);

                Log::info('Third Party is set to true and saved to DB ' . $thirdPartyParticipant);
            } else {
                Log::info('Third Party not found ');
            }

            // ConferenceCallThirdPartyRinging::dispatch($thirdPartyParticipant);
            try {
                // Dispatch the event
                ConferenceCallThirdPartyRinging::dispatch($thirdPartyParticipant);
                
                // Log successful dispatch
                Log::info('ConferenceCallThirdPartyRinging event dispatched successfully.', [
                    'participant_id' => $thirdPartyParticipant->id,
                ]);
            } catch (\Exception $e) {
                // Log any exceptions that occur during dispatch
                Log::error('Error dispatching ConferenceCallThirdPartyRinging event.', [
                    'error_message' => $e->getMessage(),
                    'participant_id' => isset($thirdPartyParticipant) ? $thirdPartyParticipant->id : 'N/A',
                ]);
            }
                
            // Log::info("Call made to: " . $call->to);
            Log::info("Call redirected to conference TwiML", ['callSid' => $callSid, 'conferenceName' => $conferenceName]);
    
            return response()->json([
                'message' => 'Conference call setup complete.',
                'conferenceName' => $conferenceName,
                'thirdPartySid' => $newCallResponse->sid,
            ]);
            
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
        $conferenceName = $request->input('FriendlyName');
        $conferenceSid = $request->input('ConferenceSid');
        $status = $request->input('StatusCallbackEvent');
        $callSid = $request->input('CallSid'); // SID of the participant who triggered the event
        $statusCallbackEvent = $request->input('StatusCallbackEvent');
        $participantCallStatus = $request->input('ParticipantCallStatus');
        $reasonParticipantLeft = $request->input('ReasonParticipantLeft');
        $muted = $request->boolean('Muted') ? 1 : 0;
        $hold = $request->boolean('Hold') ? 1 : 0;
        $coaching = $request->boolean('Coaching') ? 1 : 0;

        Log::info("Conference SID: {$conferenceSid}, Status: {$status}, Call SID: {$callSid}");

        // Perform any other actions based on the status callback event
        switch ($status) {
            case 'conference-start':
                // The conference has started
                $conference = ConferenceCall::where('name', $conferenceName)->first();
                if ($conference) {
                    $conference->update([
                        'conference_sid' => $conferenceSid,
                        'status' => 'active', // Assuming 'active' is a valid status in your application
                    ]);
                }
                break;

            case 'participant-join':
                // A participant has joined the conference
                $participant = ConferenceParticipant::where('sid', $callSid)->first();
                
                $this->updateParticipantStatus($callSid, [
                    'status' => 'joined', // Assuming you have a 'joined' status
                    'muted' => $muted,
                    'hold' => $hold,
                    'coaching' => $coaching,
                    'call_status' => $participantCallStatus // Or another appropriate status
                ]);

                // Check if the participant is the third-party participant
                if ($participant && $participant->is_third_party) {
                    // Dispatch your custom event for third-party participant join
                    ConferenceCallThirdPartyJoined::dispatch($participant);
                    Log::debug('Participant is third-party participant.' . $participant);
                } else {
                    // $participant is null or is_third_party is not true
                    // Handle this case accordingly. Maybe log an error or take some other action.
                    Log::debug('Participant is null or not a third-party participant.');
                }
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

    protected function updateParticipantStatus($callSid, $attributes)
    {
        $participant = ConferenceParticipant::where('sid', $callSid)->first();
        if ($participant) {
            $participant->update($attributes);
            Log::info("Participant status updated", ['sid' => $callSid, 'attributes' => $attributes]);
        } else {
            Log::error("Participant not found", ['sid' => $callSid]);
        }
    }

    public function hangUpThirdParty(Request $request)
    {
        // Your Twilio Account SID and Auth Token
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $client = new Client($accountSid, $authToken);
    
        $callSid = $request->input('callSid');
        $conferenceName = $request->input('conferenceName');
    
        if ($conferenceName) {
            // Retrieve the conference SID using the conference name
            $conference = ConferenceCall::where('name', $conferenceName)->first();
            if (!$conference) {
                return response()->json(['error' => 'Conference not found'], 404);
            }
            $conferenceSid = $conference->conference_sid;
        } else {
            $conferenceSid = $request->input('conferenceSid');
        }
    
        try {
            $client->conferences($conferenceSid)->participants($callSid)->delete();
            return response()->json(['message' => 'Call with third party ended successfully.']);
        } catch (\Exception $e) {
            Log::error('Error ending call with third party', [
                'conferenceSid' => $conferenceSid, 
                'callSid' => $callSid, 
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to end call with third party', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function endCall(Request $request)
    {
        // Your Twilio Account SID and Auth Token from twilio.com/console
        $accountSid = 'TWILIO_SID';
        $authToken = 'TWILIO_AUTH_TOKEN';
        // Twilio REST API client
        $client = new Client($accountSid, $authToken);
        
        // The Call SID of the call you want to end - usually passed to your application in some way
        $callSid = $request->input('callSid');

        try {
            $call = $client->calls($callSid)->update(["status" => "completed"]);
            return response()->json(['message' => 'Call ended successfully.', 'call' => $call]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
