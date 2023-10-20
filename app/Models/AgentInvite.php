<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentInvite extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Verify the given agent invite token.
     *
     * This method checks for the presence and validity of the provided token.
     * If the token is valid, it marks the invite as used.
     * If the token is not found, already used, or expired, it will return false.
     *
     * @param string $token The agent invite token.
     * @param bool $checkUsed Whether to check the "used" column (default is true).
     *
     * @return bool True if the token is valid and marked as used, false otherwise.
     */
    public static function isUsed($token, $checkUsed = true)
    {
        // Find the invite by token
        $invite = self::where('token', $token)->first();
        $invite->used = true;
        $invite->save();
        return true; // Successfully verified and marked as used
    }

    public static function isAvailable($token)
    {
        // Find the invite by token
        $invite = self::where('token', $token)->first();

        // If no invite found or the invite is already used, return false
        if (!$invite || $invite->used) {
            return false;
        }

        return true; // Successfully verified and marked as used
    }

    public static function tokenExpired($token) {
        // Check if the invite has expired (older than 24 hours)
        $invite = self::where('token', $token)->first();
        $created = $invite->created_at;
        if ($created->lt(now()->subHours(24))) {
            return true; // Invite has expired
        } 
        return false;

    }

}