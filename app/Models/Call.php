<?php

namespace App\Models;

use App\Models\CallType;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;
    protected $table = "calls";
    protected $guarded = ['id'];

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
}