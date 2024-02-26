<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class AdminCallLogsController extends Controller
{
    public function index(Call $call)
    {
        $groupedDeviceLogs = $call->deviceActionsByDevice();

        return response()->json([
            'call' => $call,
            'groupedDeviceLogs' => $groupedDeviceLogs
        ]);
    }
}
