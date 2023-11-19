<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserActivity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getCreatedAtAttribute($value)
    {
        $timestamp = Carbon::parse($value);
        return $timestamp->diffForHumans() . ' (' . $timestamp->format('H:i d/m/Y') . ')';
    }
}
