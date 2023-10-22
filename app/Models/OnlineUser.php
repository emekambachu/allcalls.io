<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;
use App\Models\CallType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnlineUser extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected static $minimumBalance = 35.0;

    /**
     * Scope a query to only include online users based on a specific call type and state.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\CallType  $callType
     * @param  \App\Models\State  $state
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCallTypeAndState($query, CallType $callType, State $state)
    {
        Log::debug('byCallTypeAndState Called');

        $userIds = UserCallTypeState::where('call_type_id', $callType->id)
            ->where('state_id', $state->id)
            ->pluck('user_id');

        Log::debug('User Ids associated with this call_type and state');
        Log::debug($userIds);

        Log::debug('Call type id is ' . $callType->id);
        Log::debug('All online users at this time', [
            'onlineUsers' => self::all(),
        ]);

        return $query->whereIn('user_id', $userIds)
            ->where('call_type_id', $callType->id);
    }

    public function scopeWithSufficientBalance($query, $callType)
    {
        Log::debug('Entering scopeWithSufficientBalance method');
    
        return $query->whereHas('user', function ($query) use ($callType) {
            Log::debug('Filtering by user relationship');
    
            $query->where(function ($query) {
                Log::debug('Checking for internal agents');
    
                // For internal agents, they should always have a balance of at least $35
                $query->whereHas('roles', function ($subQuery) {
                    Log::debug('Filtering by roles for internal-agent');
                    $subQuery->where('name', 'internal-agent');
                })->where('balance', '>=', 35);
            })->orWhere(function ($query) use ($callType) {
                Log::debug('Checking for normal users');
    
                $allBids = DB::table('bids')
                    ->where('call_type_id', $callType->id)
                    ->orderBy('amount', 'desc')
                    ->get();
    
                $userBid = $allBids->firstWhere('user_id', $query->getModel()->getAttribute('id'));
    
                $minimumRequiredBalance = 35; // Default
    
                if ($userBid) {
                    if ($allBids->where('amount', $userBid->amount)->count() > 1) {
                        $minimumRequiredBalance = $userBid->amount;
                    } elseif ($userBid->amount > 35 && $allBids->where('amount', '>', 35)->count() < 2) {
                        $minimumRequiredBalance = $allBids->count() == 1 ? 35 : 36;
                    } else {
                        $userBidIndex = $allBids->search(function ($bid) use ($userBid) {
                            return $bid->id == $userBid->id;
                        });
    
                        if (isset($allBids[$userBidIndex + 1])) {
                            $minimumRequiredBalance = $allBids[$userBidIndex + 1]->amount + 1;
                        }
                    }
                }
    
                Log::debug("Minimum required balance for user: {$minimumRequiredBalance}");
    
                $query->whereDoesntHave('roles', function ($subQuery) {
                    Log::debug('Filtering out internal-agent roles for normal users');
                    $subQuery->where('name', 'internal-agent');
                })->where('balance', '>=', $minimumRequiredBalance);
            });
        });
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function callType()
    {
        return $this->belongsTo(CallType::class);
    }

    /**
     * Prioritize internal agents and sort by last_called_at.
     *
     * This function sorts a collection of online users such that internal agents
     * come first, and within those, they are sorted by the "last_called_at" field
     * in ascending order (oldest date comes first). Non-internal agents will remain
     * in the original order.
     *
     * @param  \Illuminate\Support\Collection $onlineUsers Collection of online users.
     * @return \Illuminate\Support\Collection Sorted collection of online users.
     */
    public static function prioritizeInternalAgents(Collection $onlineUsers)
    {
        return $onlineUsers->sortBy(function ($onlineUser, $key) {
            // Determine if the user is an internal agent (1 for yes, 0 for no)
            $isInternalAgent = $onlineUser->user->roles->contains('name', 'internal-agent') ? 1 : 0;

            // Flip 1 and 0 to sort internal agents first
            $priorityForInternal = 1 - $isInternalAgent;

            // Handle nullable "last_called_at" by replacing it with a future date to ensure it comes last
            $lastCalledAt = $onlineUser->last_called_at ?? now()->addYears(10);

            return [$priorityForInternal, $lastCalledAt];
        })->values();
    }

    /**
     * Scope a query to include only those online users whose 'call_status' in the
     * User model is 'Waiting'.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCallStatusWaiting($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->where('call_status', 'Waiting');
        });
    }


    /**
     * Scope a query to include only those online users whose 'user_id' is also present in the
     * ActiveUser model with a status of 'Waiting'.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActiveUserWaitingStatus($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->whereHas('activeUser', function ($query) {
                $query->where('status', 'Waiting');
            });
        });
    }

    /**
     * Prioritize internal agents and then sort regular users by their bid amounts for a specific call type.
     *
     * @param  \Illuminate\Support\Collection $onlineUsers Collection of online users.
     * @param  \App\Models\CallType $callType The specific call type.
     * @return \Illuminate\Support\Collection Sorted collection of online users.
     */
    public static function sortByCallPriority(Collection $onlineUsers, CallType $callType)
    {
        return $onlineUsers->sortBy(function ($onlineUser, $key) use ($callType) {
            // Determine if the user is an internal agent (1 for yes, 0 for no)
            $isInternalAgent = $onlineUser->user->roles->contains('name', 'internal-agent') ? 1 : 0;

            // Handle nullable "last_called_at" by replacing it with a future date to ensure it comes last
            $lastCalledAt = $onlineUser->last_called_at ?? now()->addYears(10);

            // For internal agents, give them top priority and sort by last_called_at
            if ($isInternalAgent) {
                return [0, $lastCalledAt];
            }

            // For non-internal agents, fetch their bid amount for the specific call type
            $bidAmount = $onlineUser->user->bids
                ->where('call_type_id', $callType->id)
                ->first()
                ->amount ?? 0;

            // Return an array with:
            // - 1 to place them below internal agents
            // - negative bidAmount to sort in descending order by bid
            // - lastCalledAt to sort by time
            return [1, -$bidAmount, $lastCalledAt];

        })->values();
    }

}