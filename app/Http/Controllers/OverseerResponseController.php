<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OverseerResponseController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('api-logs:overseer-response: Received request.');

        // 1. Get the input parameters from the incoming request.
        $zipcode   = $request->input('zipcode');
        $dob       = $request->input('dob');
        $firstname = $request->input('firstname');
        $lastname  = $request->input('lastname');
        $email     = $request->input('email');
        $phone     = $request->input('phone');

        // 2. Set up the payload.
        $payload = [
            'zipcode'   => $zipcode,
            'dob'       => $dob,
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email,
            'phone'     => $phone,
        ];

        Log::debug('api-logs:overseer-response: Prepared payload: ' . json_encode($payload));

        // 3. Make the HTTP request to the endpoint.
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://getoverseer.io/blues', $payload);

        // 4. Handle the response based on the status code.
        $statusCode = $response->status();

        switch ($statusCode) {
            case 200:
                Log::debug('api-logs:overseer-response: API request successful. Response: ' . $response->body());
                if ($response->body() === 'Success') {
                    return response()->json(['message' => 'Success']);
                }

                Log::debug('api-logs:overseer-response: Bad Request. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);
            case 400:
                Log::debug('api-logs:overseer-response: Bad Request. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);

            case 401:
                Log::debug('api-logs:overseer-response: Unauthorized. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);

            case 500:
                Log::debug('api-logs:overseer-response: Server Error. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 500);

            default:
                Log::debug('api-logs:overseer-response: Unexpected status code. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);
        }
    }
}
