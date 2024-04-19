<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserCallTypeState;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function user(): User
    {
        return new User();
    }

    public function loggedUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }

    public function userTimeZone(): string
    {
        return $this->loggedUser() && !empty($this->loggedUser()->timezone) ? $this->loggedUser()->timezone : 'America/New_York';
    }

    public function withRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('cards', 'transactions', 'callTypes','states', 'activities', 'clients', 'roles', 'activeUser', 'internalAgentContract', 'additionalFiles', 'devices');
    }

    // Get only users with policies but optionally include calls and clients.
    public function usersWithPolicies(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('policies', 'calls', 'clients');
    }

    public function usersJoinCallsAndPolicies(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->usersWithPolicies()
            ->leftJoin('calls', 'users.id', '=', 'calls.user_id')
            ->leftJoin('internal_agent_my_businesses', 'users.id', '=', 'internal_agent_my_businesses.agent_id')
            ->leftJoin('clients', 'users.id', '=', 'clients.user_id')
            ->select(
                'users.*',
                'users.id AS user_id',
                'users.created_at AS user_created_at',

                'internal_agent_my_businesses.*',
                'internal_agent_my_businesses.id AS policy_id',
                'internal_agent_my_businesses.status AS policy_status',
                'internal_agent_my_businesses.created_at AS policy_created_at',

                'calls.*',
                'calls.id AS call_id',
                'calls.created_at AS call_created_at',

                'clients.*',
                'clients.id AS client_id',
                'clients.status AS client_status',
                'clients.created_at AS client_created_at',

                // total calls
                DB::raw('COUNT(calls.id) AS total_calls'),
                // count where amount_spend > 0
                DB::raw('SUM(IF(calls.amount_spent > 0, 1, 0)) AS paid_calls'),
                // sum of amount_spent
                DB::raw('SUM(calls.amount_spent) AS total_revenue'),
                // sum of call_duration_in_seconds
                DB::raw('SUM(calls.call_duration_in_seconds) AS total_call_length'),
                // count internal_agent_my_businesses
                DB::raw('SUM(IF(internal_agent_my_businesses.id IS NOT NULL, 1, 0)) AS total_policies')
            );
    }

    /**
     * @throws Exception
     */
    public function calculateCallsAndPoliciesFromUsers($query, $startDate, $endDate): Collection
    {
        // get timezone from logged user or default to America/New_York
        //$startDate = Carbon::parse($startDate)->shiftTimezone($this->userTimeZone())->startOfDay();
//        $startDate = Carbon::parse($startDate)->shiftTimezone($this->userTimeZone())->startOfDay();
//        $endDate = Carbon::parse($endDate)->shiftTimezone($this->userTimeZone())->endOfDay();

        Log::debug('UserService:DatesAfterSecondConversion:', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $query->map( function ($user) use ($startDate, $endDate){

            $totalCalls = $user->calls->filter(function($call) use ($startDate, $endDate){
                return $call->created_at >= $startDate && $call->created_at <= $endDate;
            })->count();

            //$paidCalls = $user->calls->where('amount_spent', '>', 0)->count();
            $paidCalls = $user->calls->filter(function($call) use ($startDate, $endDate){
                return ($call->created_at >= $startDate && $call->created_at <= $endDate) && $call->amount_spent > 0;
            })->count();

            //$totalRevenue = $this->sumByDateBetween($user->calls, 'amount_spent', $startDate, $endDate);
            $totalRevenue = $user->calls->filter(function($call) use ($startDate, $endDate){
                return $call->created_at >= $startDate && $call->created_at <= $endDate;
            })->sum('amount_spent');

            //$totalCallLength = $user->calls->sum('call_duration_in_seconds');
            $totalCallLength = $user->calls->filter(function($call) use ($startDate, $endDate){
                return $call->created_at >= $startDate && $call->created_at <= $endDate;
            })->sum('call_duration_in_seconds');

            $averageCallLength = $totalCalls > 0 ? $totalCallLength / $totalCalls : 0;

            //$totalApprovedPolicies = $user->policies->whereNotIn('status', ['Declined', 'Carrier Missing Information'])->count();
            $totalApprovedPolicies = $user->policies->filter(function ($policy) use ($startDate, $endDate){
                return ($policy->created_at >= $startDate && $policy->created_at <= $endDate) && !in_array($policy->status, ['Declined', 'Carrier Missing Information']);
            })->count();

            //$totalPolicies = $user->policies->where('created_at', '>=', $startDate)->count();
            $totalPolicies = $user->policies->filter(function ($policy) use ($startDate, $endDate){
                return $policy->created_at >= $startDate && $policy->created_at <= $endDate;
            })->count();

            //$totalPendingPolicies = $user->policies->where('status', 'Approved')->count();
            $totalPendingPolicies = $user->policies->filter(function ($policy) use ($startDate, $endDate){
                return ($policy->created_at >= $startDate && $policy->created_at <= $endDate) && $policy->status === 'Approved';
            })->count();

            $totalDeclinedPolicies = $user->policies->filter(function ($policy) use ($startDate, $endDate){
                return ($policy->created_at >= $startDate && $policy->created_at <= $endDate) && in_array($policy->status, ['Declined', 'Cancelled/Withdrawn']);
            })->count();

            //$totalDeclinedPolicies = $user->policies->whereIn('status', ['Declined', 'Cancelled/Withdrawn'])->get();

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
