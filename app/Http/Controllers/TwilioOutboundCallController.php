<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use App\Models\OutboundCall;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TwilioOutboundCallController extends Controller
{
    public function getToken(Request $request)
    {
        $accountSid = env('TWILIO_SID');
        $apiKey = env('TWILIO_API_KEY_SID');
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');
        $outgoingApplicationSid = env('TWILIO_OUTBOUND_TWIML_APP_SID');

        $identity = $request->user()->id;

        $accessToken = new AccessToken(
            $accountSid,
            $apiKey,
            $apiKeySecret,
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);
        // $voiceGrant->setIncomingAllow(true);

        $accessToken->addGrant($voiceGrant);

        return response()->json([
            'token' => $accessToken->toJWT(),
            'identity' => $identity
        ]);
    }

    public function handleCall(Request $request)
    {
        Log::info('Incoming request to handleCall:', $request->all());

        $to = $request->input('To');
        $response = new VoiceResponse();
        $callerId = env('TWILIO_PHONE_NUMBER');
        $statusCallbackUrl = 'https://staging.allcalls.io/api/call/outbound/callback';

        if ($to) {
            // Use the Dial verb and set the callerId attribute
            $dial = $response->dial('', [
                'callerId' => $callerId,
            ]);
            $dial->number($to, [
                'statusCallback' => $statusCallbackUrl,
                'statusCallbackEvent' => 'initiated ringing answered completed',
                'statusCallbackMethod' => 'POST',
            ]);
        } else {
            $response->say("No number provided", ['voice' => 'alice']);
        }

        // Convert the TwiML to a string and log it
        $twimlString = $response->asXML();
        Log::info('Generated outbound TwiML:', ['twiml' => $twimlString]);

        // Return the TwiML response
        return response($twimlString, 200)->header('Content-Type', 'text/xml');
    }


    public function logTwilioRequest(Request $request)
    {
        // Log the entire request
        Log::info('Received Twilio outbound callback', $request->all());
    
        // Extract call details from the request
        $callSid = $request->input('CallSid');
        $from = $request->input('From');
        $to = $request->input('To');
        $status = $request->input('CallStatus');
    
        // Log specific parts of the request
        Log::info('Twilio outbound Call SID:', ['Call SID' => $callSid]);
        Log::info('Twilio outbound From:', ['From' => $from]);
        Log::info('Twilio outbound To:', ['To' => $to]);
        Log::info('Twilio outbound Call Status:', ['Status' => $status]);

        // Extract data from the request
        $data = [
            'call_sid'          => $request->input('CallSid'),
            'parent_call_sid'   => $request->input('ParentCallSid'),
            'from'              => $request->input('Caller'),
            'to'                => $request->input('Called'),
            'status'            => $request->input('CallStatus'),
            'duration'          => $request->input('Duration'),
            'recording_url'     => $request->input('RecordingUrl', ''), // Assuming there might be a recording URL
            'twilio_call_token' => $request->input('CallToken', ''), // Assuming there is a token
            'cost'              => $request->input('Cost', 0) // Assuming there is a cost field
        ];

        // Log specific parts of the request
        Log::info('Twilio OutboundCall Details:', $data);

    
        // Switch statement to handle different call statuses
        switch ($status) {
            case 'initiated':
                Log::info("Call initiated", ['Call SID' => $callSid]);
                $this->handleInitiatedCall($data);
                break;
            case 'in-progress':
                Log::info("Call in progress", ['Call SID' => $callSid]);
                break;
            case 'busy':
                Log::info("Call is busy", ['Call SID' => $callSid]);
                break;
            case 'completed':
                // You could update the database with the call's completion status here
                // $this->updateCallRecord($callSid, $status);
                Log::info("Call completed", ['Call SID' => $callSid]);
                break;
            case 'no-answer':
                Log::info("Call received no answer", ['Call SID' => $callSid]);
                break;
            case 'ringing':
                Log::info("Call is ringing", ['Call SID' => $callSid]);
                break;
            default:
                Log::warning("Received an unknown call status", ['Status' => $status]);
                break;
        }
    
        // Respond to Twilio to acknowledge receipt of the callback
        return response()->json(['message' => 'Request logged successfully']);
    }

    /**
     * Handle saving the initiated call to the database.
     *
     * @param array $data
     */
    private function handleInitiatedCall(array $data)
    {
        if (!Auth::check()) {
            Log::error('No authenticated user for initiating call', ['Call SID' => $data['call_sid']]);
            return; // or handle this case as needed
        }
    
        // Add the current authenticated user's ID to the data array
        $data['user_id'] = Auth::id();
        
        try {
            OutboundCall::create($data);
            Log::info('Initiated call saved', ['Call SID' => $data['call_sid']]);
        } catch (\Exception $e) {
            Log::error('Error saving initiated call', ['error' => $e->getMessage(), 'Call SID' => $data['call_sid']]);
        }
    }
}
