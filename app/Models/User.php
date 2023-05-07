<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\UserCallTypeState;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'states_info',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function callTypes()
    {
        return $this->belongsToMany(CallType::class, 'users_call_type_state')
                    ->withPivot(['state_id', 'call_type_id'])
                    ->withTimestamps();
    }

    public function states()
    {
        return $this->belongsToMany(State::class, 'users_call_type_state')
        ->using(UserCallTypeState::class)
        ->withPivot(['state_id', 'call_type_id'])
        ->withTimestamps();
    }

    public function getStatesInfo()
    {
        return json_decode($this->states_info, true);
    }
    
    public function setStatesInfo(array $statesInfo)
    {
        $this->states_info = json_encode($statesInfo);
        $this->save();
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
