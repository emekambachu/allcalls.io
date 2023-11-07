<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DevicesAPIController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'device_type' => 'required',
            'fcm_token' => 'required'
        ]);

        $device = Device::create([
            'device_type' => $request->device_type,
            'fcm_token' => $request->fcm_token,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'device' => $device->toArray(),
        ]);
    }

    public function update(Device $device, Request $request)
    {
        $request->validate([
            'device_type' => 'required',
            'fcm_token' => 'required'
        ]);

        if ( $device->user_id !== $request->user()->id ) {
            return abort(401);
        } 

        $device->update([
            'device_type' => $request->device_type,
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json([
            'message' => 'Device updated successfully.'
        ]);
    }

    public function destroy(Device $device, Request $request)
    {
        if ( $device->user_id !== $request->user()->id ) {
            return abort(401);
        }

        $device->delete();

        return response()->json([
            'message' => 'Device destroyed successfully.'
        ]);
    }
}
