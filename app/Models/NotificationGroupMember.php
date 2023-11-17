<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationGroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['notification_group_id', 'user_id'];

    public function group() {
        return $this->belongsTo(NotificationGroup::class);
    }
}
