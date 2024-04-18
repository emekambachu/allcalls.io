<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Support\Facades\Log;

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

    // public function handleCall(Request $request)
    // {
    //     Log::info('Incoming request to handleCall:', $request->all());

    //     $to = $request->input('To');
    //     $response = new VoiceResponse();
    //     $callerId = env('TWILIO_PHONE_NUMBER');
    //     $statusCallbackUrl = 'https://staging.allcalls.io/api/call/outbound/callback';

    //     if ($to) {
    //         // Use the Dial verb and set the callerId attribute
    //         $dial = $response->dial('', [
    //             'callerId' => $callerId,
    //             'statusCallback' => $statusCallbackUrl,
    //             'statusCallbackEvent' => 'initiated ringing',
    //             'statusCallbackMethod' => 'POST',
    //         ]);
    //         $dial->number($to);
    //     } else {
    //         $response->say("No number provided", ['voice' => 'alice']);
    //     }

    //     // Convert the TwiML to a string and log it
    //     $twimlString = $response->asXML();
    //     Log::info('Generated outbound TwiML:', ['twiml' => $twimlString]);

    //     // Return the TwiML response
    //     return response($twimlString, 200)->header('Content-Type', 'text/xml');
    // }

    public function handleCall(Request $request)
    {
        Log::info('Incoming request to handleCall:', $request->all());

        $to = $request->input('To');
        $accountSid = env('TWILIO_SID'); 
        $authToken = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_PHONE_NUMBER');
        $statusCallbackUrl = 'https://staging.allcalls.io/api/call/outbound/callback';
        $twiml = "<Response><Dial>{$to}</Dial></Response>";

        if (!$to) {
            Log::error('No phone number provided for the outbound call.');
            return response()->json(['error' => 'No phone number provided'], 400);
        }

        // Initialize Twilio client
        $client = new Client($accountSid, $authToken);


        try {
            // Create an outbound call with the Twilio client
            $call = $client->calls->create(
                $to, // The number to call
                $from, // A valid Twilio number in your account
                [
                    'twiml' => $twiml, // URL to TwiML instructions for the call
                    'statusCallback' => $statusCallbackUrl,
                    'statusCallbackEvent' => ['initiated', 'ringing', 'answered', 'completed'],
                    'statusCallbackMethod' => 'POST'
                ]
            );

            Log::info('Outbound call created:', ['callSid' => $call->sid]);
            return response()->json(['message' => 'Call initiated successfully', 'callSid' => $call->sid]);
        } catch (\Exception $e) {
            Log::error('Failed to make an outbound call:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to make an outbound call', 'details' => $e->getMessage()], 500);
        }

    }



    public function logTwilioRequest(Request $request)
    {
        // Log the entire request
        Log::info('Received Twilio outbound callback', $request->all());

        // Optionally, you can log specific parts of the request
        Log::info('Twilio outbound Call SID: ' . $request->input('CallSid'));
        Log::info('Twilio outbound From: ' . $request->input('From'));
        Log::info('Twilio outbound To: ' . $request->input('To'));

        // Respond to Twilio to acknowledge receipt of the callback
        return response()->json(['message' => 'Request logged successfully']);
    }
}
