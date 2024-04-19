<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutboundCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'call_sid', 
        'parent_call_sid', 
        'from', 
        'to', 
        'duration', 
        'recording_url', 
        'twilio_call_token', 
        'cost'
    ];

    /**
     * Get the user that owns the outbound call.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
