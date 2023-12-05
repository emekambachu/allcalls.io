<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendBirdUser extends Model
{
    use HasFactory;
    protected $table = 'sendbird_users'; 
    
    protected $fillable = [
        'nickname', 'user_id', 'profile_url', 'access_token', 'is_online', 
        'is_active', 'is_created', 'phone_number', 'require_auth_for_profile_image', 
        'session_tokens', 'last_seen_at', 'discovery_keys', 
        'preferred_languages', 'has_ever_logged_in'
    ];

    protected $casts = [
        'session_tokens' => 'array',
        'discovery_keys' => 'array',
        'preferred_languages' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
