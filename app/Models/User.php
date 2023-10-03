<?php

namespace App\Models;

use App\Models\Card;
use App\Models\State;
use App\Models\Activity;
use App\Models\CallType;
use App\Models\ActiveUser;
use App\Models\Transaction;
use Laravel\Cashier\Billable;
use App\Models\UserCallTypeState;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

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
        'device_token',
        'banned',
        'call_status',
        'legacy_key'
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

    public function callTypes()
    {
        return $this->belongsToMany(CallType::class, 'users_call_type_state')
            ->withPivot('state_id')
            ->as('userCallTypeState')
            ->using(UserCallTypeState::class)
            ->withTimestamps();


        // return $this->belongsToMany(
        //     CallType::class,
        //     'users_call_type_state',
        //     'user_id',
        //     'call_type_id'
        // )->withPivot('state_id');
    }

    public function states()
    {
        return $this->belongsToMany(
            State::class,
            'users_call_type_state',
            'user_id',
            'state_id'
        )->withPivot('call_type_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role){
            foreach ($this->roles as $key => $value) {
                if($value->name==$role){
                    return true;
                }
            }
            return false;
    }

    public function activeUser()
    {
        return $this->hasOne(ActiveUser::class, 'user_id');
    }

    public function internalAgentContract() {
        return $this->hasOne(InternalAgentRegInfo::class);
    }
}
