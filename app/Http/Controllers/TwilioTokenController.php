<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwilioTokenController extends Controller
{
    public function getToken(Request $request)
    {
        $accountSid = env('TWILIO_SID');
        $apiKey = env('TWILIO_API_KEY_SID');
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');
        $outgoingApplicationSid = env('TWILIO_TWIML_APP_SID');

        // get the current logged in user
        $user = auth()->user();

        // set the identity to the user's id
        $identity = $user->id;

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