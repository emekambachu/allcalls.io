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
}
