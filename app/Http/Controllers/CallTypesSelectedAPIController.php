<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\CallType;
use Illuminate\Http\Request;

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
        })->values();  // Reset array keys

        return compact('selectedCallTypes');
    }
}
