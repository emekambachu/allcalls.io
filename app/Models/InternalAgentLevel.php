<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalAgentLevel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getAgentInvites() {
        return $this->hasMany(AgentInvite::class, 'level_id', 'id')->where('used', false);
    }

    // public function getRegisteredAgentInvites() {

    // }
}
