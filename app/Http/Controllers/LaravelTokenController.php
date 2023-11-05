<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaravelTokenController extends Controller
{
    //
    public function validateToken()
    {
        return response()->json(['status' => 'success', 'message' => 'Token is valid.']);
    }

}
