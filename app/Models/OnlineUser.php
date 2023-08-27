<?php

namespace App\Models;

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

        return $query->whereIn('user_id', $userIds)
                     ->where('call_type_id', $callType->id);
    }
}
