<?php

namespace App\Models;

use App\Models\CallType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bid extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function callType()
    {
        return $this->belongsTo(CallType::class);
    }


    /**
     * Get the bids for the user and call type.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallType  $callType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getForUserAndCallType($user, $callType)
    {
        return self::where('user_id', $user->id)
                    ->where('call_type_id', $callType->id)
                    ->first();
    }
}
