<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OnlineAgentsController extends Controller
{
    public function index()
    {
        $onlineUsers = OnlineUser::with('user')->get();

        return Inertia::render('Admin/OnlineAgents/Index', compact('onlineUsers'));
    }
}
