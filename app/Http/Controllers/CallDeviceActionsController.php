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
        Log::debug('CallDeviceActionsController@store', $request->all());

        $validatedData = $request->validate([
            'call_id' => 'required|integer|exists:calls,id',
            'device_id' => 'required|integer|exists:devices,id',
            'action' => 'required|string|max:255',
        ]);

        Log::debug('CallDeviceActionsController@store:validated');

        $call = Call::findOrFail($validatedData['call_id']);
        $device = Device::findOrFail($validatedData['device_id']);

        Log::debug('CallDeviceActionsController@store:call', $call->toArray());
        Log::debug('CallDeviceActionsController@store:device', $device->toArray());

        if ($request->user()->id !== $call->user_id && $request->user()->id !== $device->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $callDeviceAction = CallDeviceAction::create($validatedData);

        Log::debug('CallDeviceActionsController@store:callDeviceStored', $callDeviceAction->toArray());

        return [
            'call_device_action' => $callDeviceAction
        ];
    }
}
