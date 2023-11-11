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
        $url = 'https://equisapipartner-uat.azurewebsites.net/endpoint'; // The specific endpoint you want to access

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
            $response = Http::withToken($accessToken)->get($url);

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
