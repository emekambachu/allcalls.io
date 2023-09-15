<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Twilio\Rest\Client;
use App\Models\CallType;
use Illuminate\Http\Request;
use App\Events\MissedCallEvent;
use App\Events\RingingCallEvent;
use App\Events\CallStatusUpdated;
use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CallStatusController extends Controller
{
    public function update(Request $request)
    {
        if (!$request->user_id || !$request->call_type_id || !$request->CallStatus) {
            Log::debug('Not enough query parameters to process the request.');
            Log::debug([
                'user_id' => $request->user_id ?? 'Not found',
                'call_type_id' => $request->call_type_id ?? 'Not found',
                'CallStatus' => $request->CallStatus ?? 'Not found',
            ]);

            return;
        }

        $callStatus = $request->input('CallStatus');
        $userId = $request->input('user_id');
        $callTypeId = $request->input('call_type_id');

        Log::debug('Webhook triggered.');
        Log::debug([
            'callStatus' => $callStatus,
            'userId' => $userId,
            'callTypeId' => $callTypeId,
        ]);

        $user = User::findOrFail($userId);

        Log::debug('Dispatching CallStatusUpdated');
        CallStatusUpdated::dispatch($user, $request->all());

        switch ($callStatus) {
            case 'ringing':
                Log::debug('Ringing event for user ' . $userId);
                Log::debug($request->all());
                RingingCallEvent::dispatch($user, $request->unique_call_id, $request->call_type_id, $request->from);

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
                $callDuration = (int) $request->input('CallDuration');

                Log::debug('Call duration: ' . $callDuration);




                // ========================================
                // START: Terminate Call Chain Block
                // Purpose: To terminate the entire call chain if the call duration exceeds 10 seconds.
                // ========================================

                // If the call duration is more than 10 seconds
                if ($callDuration > 10) {
                    try {
                        // Initialize Twilio client
                        $client = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

                        // Extract ParentCallSid and CallSid from the request
                        $parentCallSid = $request->ParentCallSid;
                        $childCallSid = $request->CallSid;

                        // End the parent call if it's still going
                        if ($parentCallSid) {
                            $client->calls($parentCallSid)->update(['status' => 'completed']);
                        }

                        // End the child call if it's still going
                        if ($childCallSid && $childCallSid !== $parentCallSid) {
                            $client->calls($childCallSid)->update(['status' => 'completed']);
                        }

                        Log::debug('Terminated call chain due to duration exceeding 10 seconds.');
                    } catch (Exception $e) {
                        // Log the exception for debugging
                        Log::debug('Error while trying to terminate call chain: ' . $e->getMessage());
                    }
                }
                // ========================================
                // END: Terminate Call Chain Block
                // ========================================



                // Check if DialCallStatus is available and if callDuration is greater than 60
                if ($callDuration && $callDuration > 60) {
                    // Dispatch CompletedCallEvent
                    CompletedCallEvent::dispatch($user, CallType::find($callTypeId), $request->unique_call_id);
                }


                break;


            default:
                Log::debug('Unhandled call status: ' . $callStatus);
                break;
        }

        return;
    }
}
