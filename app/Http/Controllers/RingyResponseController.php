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

        // Mandatory fields
        $payload = [
            'sid'                 => env('RINGY_SID'),
            'authToken'           => env('RINGY_AUTH_TOKEN'),
            'phone_number'        => $request->input('phone_number'),
            'vendor_reference_id' => uniqid(),
            'first_name'          => $request->input('first_name'),
            'last_name'           => $request->input('last_name'),
            'email'               => $request->input('email'),
        ];

        // Optional fields
        $optionalFields = [
            'street_address', 'city', 'state', 'zip_code', 'birthday',
            'height', 'weight', 'household_income', 'medicare',
            'coverage_type', 'family_size'
        ];

        foreach ($optionalFields as $field) {
            if (!is_null($request->input($field))) {
                $payload[$field] = $request->input($field);
            }
        }

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
                return response()->json(['message' => 'Success']);

            case 400:
                Log::debug('api-logs:ringy-response: Bad Request. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);

            case 401:
                Log::debug('api-logs:ringy-response: Unauthorized. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);

            case 508:
                Log::debug('api-logs:ringy-response: Resource Limit Reached. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);

            default:
                Log::debug('api-logs:ringy-response: Unexpected status code. Response: ' . $response->body());
                return response()->json(['message' => 'Failure'], 400);
        }
    }
}
