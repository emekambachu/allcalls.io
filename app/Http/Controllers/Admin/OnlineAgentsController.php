<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OnlineAgentsController extends Controller
{
    public function index()
    {
        $onlineUsers = OnlineUser::with('user')->with('callType')->orderBy("created_at","DESC")->get();

        return Inertia::render('Admin/OnlineAgents/Index', compact('onlineUsers'));
    }
}
