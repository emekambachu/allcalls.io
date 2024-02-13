<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use App\Models\CallDeviceAction;

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

        if ($request->user()->id !== $call->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $callDeviceAction = CallDeviceAction::create($validatedData);

        return [
            'call_device_action' => $callDeviceAction
        ];
    }
}
