<?php

namespace App\Http\Controllers;

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

    public function handleCall(Request $request)
    {
        $to = $request->input('To');
        $response = new VoiceResponse();
        $callerId = env('TWILIO_PHONE_NUMBER');
        $statusCallbackUrl = 'https://staging.allcalls.io/api/call/outbound/callback';

        if ($to) {
            // Use the Dial verb and set the callerId attribute
            $dial = $response->dial('', [
                'callerId' => $callerId,
                'statusCallbackMethod' => 'POST',
                'statusCallbackEvent' => 'initiated ringing answered completed',
                'statusCallback' => $statusCallbackUrl
            ]);
            $dial->number($to);
        } else {
            $response->say("No number provided", ['voice' => 'alice']);
        }

        return response($response, 200)->header('Content-Type', 'text/xml');
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
