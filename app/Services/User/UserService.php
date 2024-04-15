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

    public function withRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('cards', 'transactions', 'callTypes','states', 'activities', 'clients', 'roles', 'activeUser', 'internalAgentContract', 'additionalFiles', 'devices');
    }

    // Get only users with policies but optionally include calls and clients.
    public function usersWithPolicies(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('policies', 'calls', 'clients')->has('policies');
    }

    public function calculateCallsAndPoliciesFromUsers(): \Illuminate\Support\Collection
    {
        return $this->usersWithPolicies()->get()->map(function ($user) {

            $totalCalls = $user->calls->count();
            $paidCalls = $user->calls->where('amount_spent', '>', 0)->count();
            $totalRevenue = $user->calls->sum('amount_spent');
            $totalCallLength = $user->calls->sum('call_duration_in_seconds');
            $averageCallLength = $totalCalls > 0 ? $totalCallLength / $totalCalls : 0;

            $totalApprovedPolicies = $user->policies->whereNotIn('status', ['Declined', 'Carrier Missing Information'])->count();
            $totalPolicies = $user->policies->count();
            $totalPendingPolicies = $user->policies->where('status', 'Approved')->count();
            $totalDeclinedPolicies = $user->policies->whereIn('status', ['Declined', 'Cancelled/Withdrawn'])->count();

            // correct this, these status are from clients and not from InternalAgentMyBusiness
            $totalSiPolicies = $user->clients->where('status', 'Sale - Simplified Issue')->count();
            $totalGiPolicies = $user->clients->where('status', 'Sale - Guaranteed Issue')->count();

            $percentGiPolicies = $totalApprovedPolicies > 0 ? ($totalGiPolicies / $totalApprovedPolicies) * 100 : 0;
            $percentSiPolicies = $totalApprovedPolicies > 0 ? ($totalSiPolicies / $totalApprovedPolicies) * 100 : 0;

            return [
                'userId' => $user->id,
                'agentName' => $user->first_name . ' ' . $user->last_name,
                'agentEmail' => $user->email,
                'totalCalls' => $totalCalls,
                'paidCalls' => $paidCalls,
                'revenueEarned' => $totalRevenue,
                'revenuePerCall' => $totalCalls > 0 ? $totalRevenue / $totalCalls : 0,
                'totalCallLength' => $totalCallLength,
                'averageCallLength' => $averageCallLength,
                'totalPolicies' => $totalPolicies,
                'totalPendingPolicies' => $totalPendingPolicies,
                'totalDeclinedPolicies' => $totalDeclinedPolicies,
                'totalSiPolicies' => $totalSiPolicies,
                'totalGiPolicies' => $totalGiPolicies,
                'percentGiPolicies' => $percentGiPolicies,
                'percentSiPolicies' => $percentSiPolicies,
            ];
        });
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
