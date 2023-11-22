<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class IOSLogController extends Controller
{
    public function log(Request $request)
    {
        $message = $request->input('message');
        $level = $request->input('level', 'info');

        // Get the authenticated user's email if available
        $userEmail = Auth::check() ? Auth::user()->email : 'guest';
        // Append the user's email to the log message
        $fullMessage = "User: {$userEmail}, Message: {$message}";
        Log::channel('ios_papertrail')->$level($fullMessage);

        return response()->json(['status' => 'success']);
    }
}
