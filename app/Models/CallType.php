<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function states()
    {
        return $this->belongsToMany(State::class, 'users_call_type_state')
                    ->withPivot('user_id');
    }
}
