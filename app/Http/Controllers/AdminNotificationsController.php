<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminNotificationsController extends Controller
{
    public function create()
    {
        return Inertia::render('Admin/Notifications/Create');
    }
}
