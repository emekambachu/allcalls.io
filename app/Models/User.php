<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Bid;
use Filament\Panel;
use App\Models\Card;
use App\Models\State;
use App\Models\Activity;
use App\Models\CallType;
use App\Models\ActiveUser;
use App\Models\Transaction;
use App\Models\SendBirdUser;
use Laravel\Cashier\Billable;
use App\Models\AdditionalFile;
use App\Models\UserCallTypeState;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasName
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
        'legacy_key',
        'timezone',
        'progress',
        'phone_country',
        'phone_code',
        'upline_id',
        'level_id',
        'invited_by',
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

    public function latestActivity()
    {
        return $this->hasOne(Activity::class)->latest();
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

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function additionalFiles()
    {
        return $this->hasMany(AdditionalFile::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function onlineUser()
    {
        return $this->hasMany(OnlineUser::class);
    }


    // get created at attribute:
    public function getCreatedAtAttribute($value)
    {
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            return Carbon::parse($value)->timezone($timezone);
        }

        return Carbon::parse($value);
    }


    public function getLastCalledAtAttribute($value)
    {
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            return Carbon::parse($value)->timezone($timezone);
        }

        return Carbon::parse($value);
    }

    public function getAgentLevel(){
        return $this->belongsTo(InternalAgentLevel::class, 'level_id', 'id');
    }

    public function invitees()
    {
        return $this->hasMany(User::class, 'invited_by', 'id');
    }

    public function allInvitees()
    {
        //retrieve the entire hierarchy
        return $this->invitees()->with('allInvitees');
    }

    public function invitedBy()
    {
        return $this->hasOne(User::class, 'id', 'invited_by');
    }


    public function sendbirdUser()
    {
        return $this->hasOne(SendbirdUser::class);
    }


    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
