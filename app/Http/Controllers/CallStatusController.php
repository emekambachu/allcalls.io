<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallType;
use Illuminate\Http\Request;
use App\Events\MissedCallEvent;
use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CallStatusController extends Controller
{

    public function update(Request $request)
    {


        if ( !$request->user_id || !$request->call_type_id || !$request->DialCallStatus) {
            Log::debug('Not enough query parameters to process the request.');
            Log::debug([
                'user_id' => $request->user_id ?? 'Not found',
                'call_type_id' => $request->call_type_id ?? 'Not found',
                'DialCallStatus' => $request->DialCallStatus ?? 'Not found',
            ]);

            return;
        }

        $callStatus = $request->input('DialCallStatus');
        $userId = $request->input('user_id');
        $callTypeId = $request->input('call_type_id');

        $user = User::findOrFail($userId);

        switch ($callStatus) {
            case 'ringing':
                Log::debug('Ringing event for user ' . $userId);

                if ($user->device_token) {
                    $response = Http::post(route('call.pushNotification'), [
                        'deviceToken' => $user->device_token,
                    ]);
                    Log::debug('Notification attempt from status callback:' . $response->body());
                    return;
                }

                Log::debug('Device token was not found for user_id ' . $user->id);
                break;

            case 'no-answer':
                Log::debug('no-answer event for user ' . $request->user_id);
                // Dispatch MissedCallEvent
                MissedCallEvent::dispatch($user);
                break;

            case 'completed':
                Log::debug('completed event for user ' . $request->user_id);
                $callDuration = $request->input('DialCallDuration');

                if ($callDuration > 60) {
                    // Dispatch CompletedCallEvent
                    CompletedCallEvent::dispatch($user, CallType::find($callTypeId), $callDuration);
                }
                break;

            default:
                Log::debug('Unhandled call status: ' . $callStatus);
                break;
        }

        return;
    }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required'
    //     ]);

    //     Log::debug('Ringing event for user ' . $request->user_id);

    //     $user = User::findOrFail($request->user_id);

    //     if ($user->device_token) {
    //         $response = Http::post(route('call.pushNotification'), [
    //             'deviceToken' => $user->device_token,
    //         ]);
    //         Log::debug('Notification attempt from status callback:' . $response->body());
    //         return;
    //     }

    //     Log::debug('Device token was not found for user_id ' . $user->id);
    //     return;
    // }
}