<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Device;
use Illuminate\Http\Request;

class AdminNotificationsController extends Controller
{
    public function create()
    {
        // Retrieve all devices from the database
        $devices = Device::all();

        // Pass the devices to the Inertia component
        return Inertia::render('Admin/Notifications/Create', [
            'devices' => $devices, // This will be available as a prop in your Vue component
        ]);
    }
}
