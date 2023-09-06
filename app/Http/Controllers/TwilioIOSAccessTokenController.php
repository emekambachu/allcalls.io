<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioIOSAccessTokenController extends Controller
{
    public function show(Request $request)
    {
    
        // Use the user's ID as the identity
        $identity = $request->user()->id;

        // Retrieve the appSid and pushCredentialSid from the environment file
        $appSid = env('TWILIO_TWIML_APP_SID');
        $pushCredentialSid = env('TWILIO_PUSH_CREDENTIAL_SID');

        // The command to run
        $command = "twilio token:voice --identity=$identity --voice-app-sid=$appSid --push-credential-sid=$pushCredentialSid";
        
        // Run the command
        exec($command, $output, $returnVar);

        // Check if the command was successful
        if ($returnVar === 0) {
            // Log success
            Log::info('Successfully generated Twilio token for user', ['userId' => $identity, 'output' => $output]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'data' => $output
            ]);
        } else {
            // Log failure
            Log::error('Failed to generate Twilio token', ['command' => $command, 'returnVar' => $returnVar]);

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate token.'
            ], 500);
        }
 
    }
}
