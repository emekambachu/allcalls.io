<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDNCListController extends Controller
{
    public function check(Request $request)
    {
        // Retrieve the 'phone' query parameter from the URL
        $phoneNumber = $request->query('phone');

        if (!$phoneNumber) {
            // Return an error response if the phone number is not provided
            return response()->json(['error' => 'Phone number is required'], 400);
        }

        // Set the database connection to 'mysql2'
        $connection = DB::connection('mysql2');

        // Check if the phone number exists in the dnc_table_gamma using the mysql2 connection
        $exists = $connection->table('dnc_table_gamma')->where('PhoneID', $phoneNumber)->exists();

        // Return a JSON response indicating whether the phone number is in the DNC list
        if ($exists) {
            return 1;
        } else {
            return 0;
        }
    }
}
