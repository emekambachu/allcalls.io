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
        return $this->belongsTo(Card::class );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        // Parse the timestamp
        $timestamp = Carbon::parse($value);

        // Check if there's an authenticated user
        if (auth()->user()) {
            // Get the user's timezone
            $timezone = auth()->user()->timezone;

            // Apply the timezone to the timestamp
            $timestamp->timezone($timezone);
        }
        return $timestamp->diffForHumans() . ' (' . $timestamp->format('H:i m/d/Y') . ')';
    }
}
