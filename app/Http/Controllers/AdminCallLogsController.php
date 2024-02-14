<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class AdminCallLogsController extends Controller
{
    public function index(Request $request, Call $call)
    {
        $deviceLogs = $call->deviceActions()->with('device')->get();

        return response()->json([
            'call' => $call,
            'deviceLogs' => $deviceLogs
        ]);
    }
}
