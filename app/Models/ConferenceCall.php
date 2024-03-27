<?php

namespace App\Models;

use App\Models\Call;
use App\Models\ConferenceParticipant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConferenceCall extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'conference_sid', 'status', 'call_id'];


    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    public function participants()
    {
        return $this->hasMany(ConferenceParticipant::class);
    }
}
