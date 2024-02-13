<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function calls()
    {
        return $this->belongsToMany(Call::class, 'call_device_actions')
                    ->withPivot('action')
                    ->withTimestamps();
    }
}
