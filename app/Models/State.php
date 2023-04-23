<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_call_type_state')
                    ->withPivot('call_type_id')
                    ->withTimestamps();
    }

    public function callTypes()
    {
        return $this->belongsToMany(CallType::class, 'users_call_type_state')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }
}
