<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCallTypeState extends Pivot
{
    use HasFactory;

    protected $table = 'users_call_type_state';
}
