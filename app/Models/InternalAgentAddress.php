<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalAgentAddress extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "internal_agent_addresses";

    public function agentDetail() {
        return $this->belongsTo(InternalAgentRegInfo::class);
    }

    public function getState() {
        return $this->hasOne(State::class, 'id', 'state');
    }
}
