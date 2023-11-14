<?php

namespace App\Http\Controllers\Admin;

use App\Events\OnlineUserListUpdated;
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
        $onlineUsers = OnlineUser::with(['user.states','user.latestActivity', 'callType'])
            ->orderBy("created_at", "DESC")
            ->get();
        return Inertia::render('Admin/OnlineAgents/Index', compact('onlineUsers'));
    }

    public function destroy($userId)
    {
        OnlineUser::whereUserId($userId)->delete();

        OnlineUserListUpdated::dispatch();

        return redirect()->back()->with([
            'message' => 'Agent removed from online list.'
        ]);
    }
}
