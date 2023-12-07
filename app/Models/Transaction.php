<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Card;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            return Carbon::parse($value)->timezone($timezone);
        }

        return Carbon::parse($value);
    }
}
