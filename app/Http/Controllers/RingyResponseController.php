<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RingyResponseController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('api-logs:ringy-response: Received request.');

        // Get the input parameters from the incoming request.
        $firstname = $request->input('first_name');
        $lastname  = $request->input('last_name');
        $phone     = $request->input('phone_number');
        $email     = $request->input('email');

        // Set up the payload for the Ringy API.
        $payload = [
            'sid'                 => env('RINGY_SID'),
            'authToken'           => env('RINGY_AUTH_TOKEN'),
            'phone_number'        => $phone,
            'vendor_reference_id' => uniqid(), // Replace with your actual vendor reference ID
            'first_name'          => $firstname,
            'last_name'           => $lastname,
            'email'               => $email,
        ];

        Log::debug('api-logs:ringy-response: Prepared payload: ' . json_encode($payload));

        // Make the HTTP POST request to the Ringy API endpoint.
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://app.ringy.com/api/public/leads/new-lead', $payload);

        // Handle the response based on the status code.
        $statusCode = $response->status();

        switch ($statusCode) {
            case 200:
                Log::debug('api-logs:ringy-response: API request successful. Response: ' . $response->body());
                return response()->json(['message' => 'Success', 'vendorResponseId' => $response->json('vendorResponseId')]);

            case 400:
                Log::debug('api-logs:ringy-response: Bad Request. Response: ' . $response->body());
                return response()->json(['message' => 'Bad Request'], 400);

            case 401:
                Log::debug('api-logs:ringy-response: Unauthorized. Response: ' . $response->body());
                return response()->json(['message' => 'Unauthorized'], 401);

            case 508:
                Log::debug('api-logs:ringy-response: Resource Limit Reached. Response: ' . $response->body());
                return response()->json(['message' => 'Resource Limit Reached'], 508);

            default:
                Log::debug('api-logs:ringy-response: Unexpected status code. Response: ' . $response->body());
                return response()->json(['message' => 'Unexpected Error'], 500);
        }
    }
}
