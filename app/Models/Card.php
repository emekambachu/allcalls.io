<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $encrypt = ['number'];
    // protected $casts = [
    //     'number' => 'encrypted',
    // ];

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

    // public function getFormatedNumberAttribute() {
    //     $originalNumber = $this->attributes['number'];
    //     $originalNumber = Crypt::decryptString($originalNumber);
    //     // Check if the original number is not empty and has at least 16 characters
    //     if (!empty($originalNumber) && strlen($originalNumber) >= 16) {
    //         // Format the card number as "411111******1111"
    //         $maskedNumber = substr($originalNumber, 0, 6) . str_repeat('*', 6) . substr($originalNumber, -4);
    //         return $maskedNumber;
    //     }
    //     // Return the original number if it doesn't meet the conditions
    //     return $originalNumber;
    // }
}