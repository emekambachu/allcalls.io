<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Device;
use Illuminate\Http\Request;

class AdminNotificationsController extends Controller
{
    public function create()
    {
        // Retrieve all users with their devices
        $users = User::with('devices')->get();
    
        // Pass the users to the Inertia component
        return Inertia::render('Admin/Notifications/Create', [
            'users' => $users,
        ]);
    }
    
}
