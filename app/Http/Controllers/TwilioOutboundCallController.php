<?php

namespace App\Http\Controllers;

use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;

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

        if ($to) {
            $response->dial($to);
        } else {
            $response->say("No number provided", array("voice" => "alice"));
        }

        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}
