<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ping;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PingLogController extends Controller
{
    public function index(Request $request)
    {
        $pingLogs = Ping::paginate(100);

        return Inertia::render('Admin/PingLogs/Index', [
            'requestData' => $request->all(),
            'pingLogs' => $pingLogs,
        ]);
    }
}
