<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EquisAPIController extends Controller
{
    public function show()
    {
        $clientId = env('EQUIS_CLIENT_ID'); // Your client ID here
        $clientSecret = env('EQUIS_CLIENT_SECRET'); // Your client secret here
        $url = 'https://equisapipartner-uat.azurewebsites.net/Agent'; // The specific endpoint you want to access

        // First, retrieve the Bearer token
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://equisfinancialb2c.onmicrosoft.com/equis-partner-api-uat/.default'
        ]);


        // Log the response body and status
        Log::debug($tokenResponse->body());
        Log::debug($tokenResponse->status());

        if ($tokenResponse->successful()) {
            $accessToken = $tokenResponse->json()['access_token'];

            // Now, make the GET request to the API endpoint with the Bearer token
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withToken($accessToken)->post($url, [
                "address" => "123 Main St",
                "addressTwo" => "Apt. 42",
                "birthDate" => "1970-01-01",
                "city" => "Asheville",
                "currentlyLicensed" => true, // Note: true is a boolean in PHP
                "email" => "capn@serenity.com",
                "firstName" => "Malcolm",
                "languageId" => "en",
                "lastName" => "Reynolds",
                "npn" => "4FE274A",
                "partnerUniqueId" => "abc123",
                "preferredFirstName" => "Mal",
                "preferredLastName" => "Reynolds",
                "preferredSuffix" => "II",
                "role" => "Agent",
                "state" => "NC",
                "suffix" => "Jr",
                "uplineAgentEFNumber" => "222171",
                "zipCode" => "28801"
            ]);

            // Log the response body and status
            Log::debug($response->body());
            Log::debug($response->status());

            if ($response->successful()) {
                // Handle successful response
                $data = $response->json();
                return response()->json($data);
            } else {
                // Handle error
                return response()->json(['error' => 'Failed to retrieve data from Equis API'], 500);
            }
        } else {
            // Handle error in token retrieval
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }
    }
}
