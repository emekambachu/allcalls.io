<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\CallType;
use Illuminate\Http\Request;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;

class CallTypesSelectedAPIController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's call types with states
        $userCallTypesWithStates = $user->callTypes()->with('states')->get();

        // For some reason it's returning the correct call types
        // (only the one's associated with the authenticated user)
        // but wrong states event the ones that do not belong to the current user.
        // So, I'm just filtering it down to currently authenticated user, might be a cleaner
        // way to go about it. Might update later. :)

        $userCallTypesWithStates = $userCallTypesWithStates->map(function ($type) use ($user) {
            $type->user_states = $type->states->filter(function ($state) use ($user) {
                return (int) $state->pivot->user_id === (int) $user->id;
            })->toArray();

            return $type;
        });

        $callTypes = CallType::all();
        $states = State::all();

        $callTypes = $callTypes->map(function ($callType) use ($userCallTypesWithStates, $states) {
            // Find the corresponding call type in $userCallTypesWithStates, if it exists
            $userCallType = $userCallTypesWithStates->firstWhere('id', $callType->id);

            // If a corresponding call type was found in $userCallTypesWithStates, set the call type's selected property
            $callType->selected = $userCallType !== null;

            $callType->statesWithSelection = $states->map(function ($state) use ($userCallType) {
                if ($userCallType !== null && in_array($state->id, array_column($userCallType->user_states, 'id'))) {
                    $state['selected'] = true;
                } else {
                    $state['selected'] = false;
                }

                return $state;
            })->toArray();

            return $callType;

        });

        $selectedCallTypes = $userCallTypesWithStates->filter(function ($type) {
            return $type->user_states && count($type->user_states) > 0;
        })->map(function ($type) {
            return [
                'id' => $type->id,
                'type' => $type->type,
            ];
        })->unique('id')->values();  // Use unique method to remove duplicates based on 'id'
        
        return $selectedCallTypes;
    }


    public function updateUserStates(Request $request)
{
    try {
        $user = $request->user();
        Log::info('Updating user states', ['user_id' => $user->id]);

        $incomingData = $this->buildIncomingData($request->input('selected_states'));
        Log::debug('Incoming data for state update', ['data' => $incomingData]);

        UserCallTypeState::updateUserCallTypeState($user, $incomingData);

        Log::info('User states updated successfully', ['user_id' => $user->id]);
        return response()->json(['message' => 'User states updated successfully']);
    } catch (\Exception $e) {
        Log::error('Error updating user states', [
            'user_id' => $user->id ?? null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['message' => 'Failed to update user states'], 500);
    }
}
}
