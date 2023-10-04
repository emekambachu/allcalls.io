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

            return response()->json([
                'data' => $response
            ]);
        }

        throw ValidationException::withMessages([
            'callerid' => ['Invalid callerid format.']
        ]);
    }

    public function formatPhoneNumber($callerid)
    {
        $callerid = preg_replace('/[^0-9]/', '', $callerid);

        if (strlen($callerid) == 10) {
            return $callerid;
        } elseif (strlen($callerid) == 11 && $callerid[0] == '1') {
            return substr($callerid, 1);
        }

        return false;
    }
}
