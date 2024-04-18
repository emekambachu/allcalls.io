<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallType;
use Illuminate\Http\Request;
use App\Models\UserCallTypeState;

class FronterController extends Controller
{
    public function store(Request $request)
    {
        // First of all, get the user_id from the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // To make a user a fronter, first fetch it:
        $user = User::findOrFail($request->user_id);

        // Get the call type for fronter:
        $callType = CallType::whereType('fronter')->first();

        $callTypesWithStates = [];

        $statesToHave = ['NY', 'CA', 'TX', 'FL', 'PA', 'IL', 'OH', 'GA', 'NC', 'MI'];

        // Next update their call type:
        UserCallTypeState::updateUserCallTypeState($user, [
            'call_type_id' => $callType->id,
            'state_id' => $statesToHave,
        ]);
    }
}
