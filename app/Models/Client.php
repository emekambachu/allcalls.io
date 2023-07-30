<?php

namespace App\Models;

use App\Models\CallType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    public function callType()
    {
        return $this->belongsTo(CallType::class);
    }
}
