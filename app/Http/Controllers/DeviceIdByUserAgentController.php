<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceIdByUserAgentController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'device_type' => 'required|string',
        ]);

        $devices = $request->user()->devices()->where('device_type', $request->device_type)->get();

        if (!$devices->count()) {
            $device = $request->user()->devices()->create([
                'device_type' => $request->device_type,
            ]);

            return response()->json([
                'device_id' => $device->id,
            ]);
        }

        return response()->json([
            'device_id' => $devices->first()->id,
        ]);
    }
}
