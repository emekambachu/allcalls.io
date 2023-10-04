<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PingAPIController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->validate([
            'callerid' => 'required|regex:/^1?\d{10}$/'
        ]);

        $formattedCallerid = $this->formatPhoneNumber($data['callerid']);

        if ($formattedCallerid !== false) {
            $url = "https://enhance.tldcrm.com/api/public/dialer/ready/$formattedCallerid?erq=58-OC9YbjEwZURmQldBRHZKYng4UkNiRGhrdmlSeEtqak51dEY5MWZ3T0IvOExQd05YcnRYbzdQQURXTGRZRXJ5S3RhNkR3NGdoQ1gxbjhhVnZLb3RYL2c9PQ&key=downline";

            $response = file_get_contents($url);

            return response($response, 200, ['Content-Type' => 'text/html']);
        }

        throw ValidationException::withMessages([
            'callerid' => ['Invalid callerid format.']
        ]);
    }
}
