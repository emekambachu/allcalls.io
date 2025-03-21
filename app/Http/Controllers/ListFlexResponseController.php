<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ListFlexResponseController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('api-logs:listflex-response: Received request.');

        // 1. Get the input parameters from the incoming request.
        $firstname = $request->input('firstname');
        $lastname  = $request->input('lastname');
        $mobile    = $request->input('mobile');
        $email     = $request->input('email');

        // 2. Set up the payload.
        $payload = [
            'tenantname' => 'El Salvador BPO',
            'vendorName' => 'Pinnacle',
            'firstname'  => $firstname,
            'lastname'   => $lastname,
            'email'      => $email,
            'mobile'     => $mobile,
        ];

        Log::debug('api-logs:listflex-response: Prepared payload: ' . json_encode($payload));

        // 3. Make the HTTP request to the endpoint.
        $response = Http::withHeaders([
            'x-api-key' => env('BENEFIT_ALIGN_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api-mme-bpo.benefitalign.com/ui/vendorleads', $payload);

        if ($response->failed()) {
            Log::debug('api-logs:listflex-response: Failed API request. Response: ' . $response->body());
        }

        // Handle the response based on the Status
        if ($response->json('Status') === 'Success') {
            Log::debug('api-logs:listflex-response: API request successful.');
            return response()->json(['message' => 'Success']);
        } else {
            Log::debug('api-logs:listflex-response: API request returned a non-success status.');
            return response()->json(['message' => 'Failure'], 400);
        }
    }
}
