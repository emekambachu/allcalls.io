<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class BrowserDeviceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'device_type' => 'required',
        ]);

        $devices = Device::whereDeviceType($request->device_type)->whereUserId($request->user()->id)->get();
        if ($devices->count() > 0) {
            return response()->json([
                'message' => 'Device already exists.'
            ], 422);
        }


        $device = Device::create([
            'device_type' => $request->device_type,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'device' => $device->toArray(),
        ]);
    }
}
