<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;
use App\Models\CallType;
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
        Log::debug('All online users at this time'. [
            'onlineUsers' => json_encode(self::all()),
        ]);

        return $query->whereIn('user_id', $userIds)
            ->where('call_type_id', $callType->id);
    }

    /**
     * Scope a query to include online users who have a sufficient minimum balance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSufficientBalance($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->where('balance', '>=', self::$minimumBalance);
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
            $isInternalAgent = $onlineUser->user->roles->contains('id', 3) ? 1 : 0;

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
}
