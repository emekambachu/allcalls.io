<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IOSLogController extends Controller
{
    public function log(Request $request)
    {
        $message = $request->input('message');
        $level = $request->input('level', 'info');

        Log::channel('ios_papertrail')->$level($message);

        return response()->json(['status' => 'success']);
    }
}
