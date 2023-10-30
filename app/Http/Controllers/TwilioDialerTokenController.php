<?php

namespace App\Http\Controllers;

use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioDialerTokenController extends Controller
{
    public function show(Request $request)
    {
        $accountSid = env('TWILIO_SID');
        $apiKey = env('TWILIO_API_KEY_SID');
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');
        $outgoingApplicationSid = env('TWILIO_TWIML_APP_SID');

        // set the identity to the user's id
        $identity = uniqid();
        // $identity = '5736523170';

        $accessToken = new AccessToken(
            $accountSid,
            $apiKey,
            $apiKeySecret,
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);
        $voiceGrant->setIncomingAllow(true);

        $accessToken->addGrant($voiceGrant);

        return response()->json([
            'token' => $accessToken->toJWT(),
            'identity' => $identity
        ]);
    }
}
