<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Call;
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

            case 'busy':
                Log::debug('busy event for user ' . $request->user_id);
                // Dispatch MissedCallEvent
                MissedCallEvent::dispatch($user);
                break;

            case 'no-answer':
                Log::debug('no-answer event for user ' . $request->user_id);
                
                $call = Call::where('unique_call_id', $request->unique_call_id)->first();
                if (!$call) {
                    // Handle the case where there's no Call associated with the user.
                    return response()->json(['message' => 'Call not found for the user'], 404);
                }

                // 3. Fetch the `created_at` column of that `Call` model.
                $createdAt = $call->created_at;
                Log::debug("Fetching Ringing duration, call started ringing at: ", $createdAt);
                // 4. Calculate the difference between the current time and the `Call` model's `created_at`.
                $now = now();
                $difference = $now->diff($createdAt);

                Log::debug("Time Difference is: ", $difference);
                // 5. Display the result in a time format.
                $timeDifference = $difference->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
                // Return the result
                // return response()->json(['time_difference' => $timeDifference]);
                Log::debug('Time difference: ' . $timeDifference);

                // Dispatch MissedCallEvent
                MissedCallEvent::dispatch($user);
                break;

            case 'completed':
                Log::debug('completed event for user ' . $request->user_id);
                $callDuration = (int) $request->input('CallDuration');


                $call = Call::where('unique_call_id', $request->unique_call_id)->first();
                $call->completed_at = now();
                $call->save();
                Log::debug('Call completed_at updated.');


                Log::debug('Call duration: ' . $callDuration);



                // ========================================
                // START: Terminate Call Chain Block
                // Purpose: To terminate the entire call chain if the call duration exceeds 10 seconds.
                // ========================================

                // Initialize Twilio client
                $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

                // Extract ParentCallSid and CallSid from the request
                $parentCallSid = $request->ParentCallSid;
                $childCallSid = $request->CallSid;

                if ($childCallSid) {
                    Log::debug('childCallSid exists.');
                    $twilioCall = $client->calls($childCallSid)->fetch();
                    Log::debug('Call Info:');
                    Log::debug($twilioCall->toArray());
                }


                // If the call duration is more than 10 seconds
                if ($callDuration > 10) {
                    try {
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





                // ========================================
                // START: Save Call Duration
                // ========================================
                Log::debug('SaveCallDuration: start.');
                if ($call && $callDuration) {
                    Log::debug('SaveCallDuration: call && callDuration found.');
                    $call->call_duration_in_seconds = $callDuration;
                    $call->save();
                    Log::debug('SaveCallDuration: saved.');
                }
                Log::debug('SaveCallDuration: end.');
                // ========================================
                // END: Save Call Duration
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
