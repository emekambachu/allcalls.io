<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalAgentMyBusiness extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function clientState()
    {
        return $this->belongsTo(State::class, 'client_state', 'id');
    }

    public function getCall() {
        return $this->belongsTo(Call::class, 'call_id', 'id');
    }
}
