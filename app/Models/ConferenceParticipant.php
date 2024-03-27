<?php

namespace App\Models;

use App\Models\ConferenceCall;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConferenceParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_call_id', 'sid', 'status', 'phone_number', 'muted', 'hold', 'coaching', 'call_status', 'reason_left'
    ];
    

    public function conferenceCall()
    {
        return $this->belongsTo(ConferenceCall::class);
    }
}
