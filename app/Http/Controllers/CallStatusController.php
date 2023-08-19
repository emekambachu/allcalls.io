<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CallStatusController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        Log::debug('Ringing event for user ' . $request->user_id);

        $user = User::findOrFail($request->user_id);

        if ($user->device_token) {
            $response = Http::post(route('call.pushNotification'), [
                'deviceToken' => $user->device_token,
            ]);
            Log::debug('Notification attempt from status callback:' . $response->body());
            return;
        }

        Log::debug('Device token was not found for user_id ' . $user->id);
        return;
    }
}
