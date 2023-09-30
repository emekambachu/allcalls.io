<?php

namespace App\Http\Controllers;

use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioAndroidAccessTokenController extends Controller
{
    public function show(Request $request)
    {
        // Load Twilio account settings from environment variables.
        $accountSid = env('TWILIO_SID');
        $apiKey = env('TWILIO_API_KEY_SID');
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');
        $outgoingApplicationSid = env('TWILIO_TWIML_APP_SID');
        $pushCredentialSid = env('TWILIO_ANDROID_PUSH_CREDENTIAL_SID');

        // Use the ID of the logged-in user as the identity for this Access Token.
        $identity = 'alice';
        // $identity = 'testneo';

        // Initialize a new Twilio Access Token.
        $accessToken = new AccessToken(
            $accountSid,
            $apiKey,
            $apiKeySecret,
            3600,
            $identity
        );

        // Initialize a new Voice Grant for the Access Token.
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);
        $voiceGrant->setIncomingAllow(true);
        $voiceGrant->setPushCredentialSid($pushCredentialSid);

        // Add the Voice Grant to the Access Token.
        $accessToken->addGrant($voiceGrant);

        // Return the Access Token and the user's identity as a JSON-encoded response.
        return response()->json([
            'token' => $accessToken->toJWT(),
            'identity' => $identity
        ]);
    }
}
