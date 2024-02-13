<?php

namespace App\Http\Controllers;

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


        if ($request->user()->id !== $request->call->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $callDeviceAction = CallDeviceAction::create($validatedData);

        return [
            'call_device_action' => $callDeviceAction
        ];
    }
}
