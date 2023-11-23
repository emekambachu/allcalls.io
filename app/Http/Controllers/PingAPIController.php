<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PingAPIController extends Controller
{
    public function show(Request $request)
    {
        // Log the request data
        Log::debug('api-logs:ping: Request Data', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'query_string' => $request->getQueryString(),
        ]);

        $callerId = $request->input('callerId');

        $formattedCallerId = $this->formatPhoneNumber($callerId);

        if ($formattedCallerId !== false) {
            $url = "https://enhance.tldcrm.com/api/public/dialer/ready/$formattedCallerId?erq=58-OC9YbjEwZURmQldBRHZKYng4UkNiRGhrdmlSeEtqak51dEY5MWZ3T0IvOExQd05YcnRYbzdQQURXTGRZRXJ5S3RhNkR3NGdoQ1gxbjhhVnZLb3RYL2c9PQ&key=downline";

            $responseContent = file_get_contents($url);

            $response = response()->json(json_decode($responseContent));

            // Log the response data
            Log::debug('api-logs:ping: Response Data', [
                'data' => json_decode($response->getContent(), true),
                'status' => $response->getStatusCode(),
            ]);

            return $response;
        }

        throw ValidationException::withMessages([
            'callerid' => ['Invalid callerid format.']
        ]);
    }

    public function formatPhoneNumber($callerid)
    {
        // Remove all non-numeric characters
        $callerid = preg_replace('/[^0-9]/', '', $callerid);

        // Check if the number is 10 digits long, which is the expected format
        if (strlen($callerid) == 10) {
            return $callerid;
        }
        // If the number is 11 digits and starts with '1', remove the first digit
        elseif (strlen($callerid) == 11 && $callerid[0] == '1') {
            return substr($callerid, 1);
        }

        // If none of the above conditions are met, the format is invalid
        return false;
    }
}
