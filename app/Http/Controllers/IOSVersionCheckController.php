<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IOSVersionCheckController extends Controller
{
    public function checkVersion()
    {
        return response()->json([
            'latest_version' => '1.0.4'
        ]);
    }
}
