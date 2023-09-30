<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalAgentAdditionalInfo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function agentDetail() {
        return $this->belongsTo(InternalAgentRegInfo::class);
    }
}
