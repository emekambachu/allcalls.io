<?php

namespace App\Models;

use App\Models\ConferenceCall;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConferenceParticipant extends Model
{
    use HasFactory;

    public function conferenceCall()
    {
        return $this->belongsTo(ConferenceCall::class);
    }
}
