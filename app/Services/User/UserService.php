<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserCallTypeState;

class UserService
{
    public function user(): User
    {
        return new User();
    }

    public function withRelations(){
        $this->user()->with('cards', 'transactions', 'callTypes','states', 'activities', 'clients', 'roles', 'activeUser', 'internalAgentContract', 'additionalFiles', 'devices');
    }

    public function updateUser($request): array
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        $incomingData = $this->buildIncomingData($request->selected_states);

        UserCallTypeState::updateUserCallTypeState($user, $incomingData);

        return [
            'success' => true,
            'message' => 'Profile updated successfully.'
        ];
    }

    private function buildIncomingData($selectedStates): array
    {
        // Initialize an empty array to store the call type and state combinations.
        $incomingData = [];

        // For each selected state item in the provided list
        foreach ($selectedStates as $item) {
            // Extract the call type ID
            $typeId = $item['typeId'];
            // Extract the list of selected state IDs
            $stateIds = $item['selectedStateIds'];

            // For each state ID in the list of selected state IDs
            foreach ($stateIds as $stateId) {
                // Add a new entry to the incoming data with the current call type ID and state ID.
                // Each entry represents a combination of a call type and state that the user has selected.
                $incomingData[] = [
                    'call_type_id' => $typeId,
                    'state_id' => $stateId,
                ];
            }
        }

        // Return the populated incoming data array.
        return $incomingData;
    }
}
