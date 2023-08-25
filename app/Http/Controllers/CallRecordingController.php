<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallRecordingController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('Recording callback fired');
        Log::debug($request->all());
    }
}
