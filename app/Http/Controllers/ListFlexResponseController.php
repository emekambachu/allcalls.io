<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ListFlexResponseController extends Controller
{
    public function store(Request $request)
    {
        // 1. Get the input parameters from the incoming request.
        $firstname = $request->input('firstname');
        $lastname  = $request->input('lastname');
        $mobile    = $request->input('mobile');
        $email     = $request->input('email');
        // If there are more parameters, you can fetch them similarly.

        // 2. Set up the payload.
        $payload = [
            'tenantname' => 'Lead Generator Center - Cochin',
            'vendorName' => 'Pinnacle',
            'firstname'  => $firstname,
            'lastname'   => $lastname,
            'email'      => $email,
            'mobile'     => $mobile,
        ];

        Log::debug('Payload: ', $payload);

        // 3. Make the HTTP request to the endpoint.
        $response = Http::withHeaders([
            'x-api-key' => env('BENEFIT_ALIGN_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api-mme-bpo.benefitalign.com/ui/vendorleads', $payload);

        // Handle the response based on the Status
        if ($response->json('Status') === 'Success') {
            return response()->json(['message' => 'Success']);
        } else {
            return response()->json(['message' => 'Failure'], 400);
        }
    }
}
