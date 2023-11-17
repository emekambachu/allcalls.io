<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function members() {
        return $this->hasMany(NotificationGroupMember::class);
    }
}
