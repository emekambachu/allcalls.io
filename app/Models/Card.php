<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setAsDefault()
    {
        // Set all user's cards to non-default
        self::where('user_id', $this->user_id)->update(['default' => false]);

        // Set this card as default
        $this->default = true;
        $this->save();
    }
}