<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\CallDeviceAction;
use Illuminate\Support\Facades\Log;

class CallDeviceActionsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'call_id' => 'required|integer|exists:calls,id',
            'device_id' => 'required|integer|exists:devices,id',
            'action' => 'required|string|max:255',
        ]);

        $call = Call::findOrFail($validatedData['call_id']);
        $device = Device::findOrFail($validatedData['device_id']);

        if ($request->user()->id !== $call->user_id && $request->user()->id !== $device->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $callDeviceAction = CallDeviceAction::create($validatedData);

        return [
            'call_device_action' => $callDeviceAction
        ];
    }

    public function storeWithUniqueCallId(Request $request)
    {
        $validatedData = $request->validate([
            'call_unique_id' => 'required|integer',
            'device_id' => 'required|integer|exists:devices,id',
            'action' => 'required|string|max:255',
        ]);

        $call = Call::whereUniqueCallId($validatedData['call_uniuqe_id'])->firstOrFail();
        $device = Device::findOrFail($validatedData['device_id']);

        if ($request->user()->id !== $call->user_id && $request->user()->id !== $device->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $callDeviceAction = CallDeviceAction::create([
            'call_id' => $call->id,
            'device_id' => $device->id,
            'action' => $validatedData['action'],
        ]);

        return [
            'call_device_action' => $callDeviceAction
        ];
    }
}
