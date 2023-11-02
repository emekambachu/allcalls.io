<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableNumber extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function callType()
    {
        return $this->belongsTo(CallType::class, 'call_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
