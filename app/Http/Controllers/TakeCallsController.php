<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;

class TakeCallsController extends Controller
{
    public function show(Request $request)
    {
        $callTypes = $this->getCallTypesForUser($request->user());
        $onlineCallType = $this->getOnlineCallTypeForUser($request->user());

        $callTypes->map(function ($callType) use ($request) {
            $bid = Bid::getForUserAndCallType($request->user(), $callType);

            $callType->bid = $bid ?? null;
            $callType->bidAmount = $bid ? $bid->amount : null;

            // Fetch the highest bid amount for this call type
            $topBid = Bid::where('call_type_id', $callType->id)
                ->orderBy('amount', 'desc')
                ->first();

            $callType->topBid = $topBid ? $topBid->amount : null;

            // Fetch the average bid amount for this call type
            $averageBid = Bid::where('call_type_id', $callType->id)
                ->avg('amount');
            $callType->averageBid = number_format($averageBid, 2, '.', '');

            return $callType;
        });

        return Inertia::render('TakeCalls/Show', compact('callTypes', 'onlineCallType'));
    }

    protected function getOnlineCallTypeForUser($user)
    {
        $onlineCallTypes = OnlineUser::where('user_id', $user->id)->get();
        $count = $onlineCallTypes->count();

        if ($count > 1) {
            $firstRecord = $onlineCallTypes->first();
            $firstId = $firstRecord->id; // Get the ID of the first record.
            OnlineUser::where('user_id', $user->id)->where('id', '!=', $firstId)->delete();
        }

        return ($count >= 1) ? $onlineCallTypes[0] : null;
    }

    protected function getCallTypesForUser(User $user)
    {

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

        return $callTypes;
    }
}
