<?php

namespace App\Models;

use App\Models\CallType;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;
    protected $table = "calls";
    protected $guarded = ['id'];

    protected $appends = ['ringing_duration', 'role'];

    public function callType()
    {
        return $this->belongsTo(CallType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getClient()
    {
        return $this->hasOne(Client::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the ringing duration for the call.
     *
     * @return int
     */
    public function getRingingDurationAttribute()
    {
        // If user_response_time is null, return 20
        if (is_null($this->user_response_time)) {
            return 20;
        }

        // Calculate the difference in seconds between created_at and user_response_time
        return $this->created_at->diffInSeconds($this->user_response_time);
    }

    public function getCallTakenAttribute($value)
    {
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            return Carbon::parse($value)->timezone($timezone)->format('F jS Y, g:i:s a');
        }

        return Carbon::parse($value)->format('F jS Y, g:i:s a');
    }

    /**
     * Get the role type for the call's user.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        if ($this->user && $this->user->roles->contains('name', 'internal-agent')) {
            return 'Internal Agent';
        }

        return 'Regular User';
    }



}
