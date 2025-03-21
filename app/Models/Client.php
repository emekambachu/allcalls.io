<?php

namespace App\Models;

use App\Models\Call;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function policies()
    {
        return $this->hasMany(InternalAgentMyBusiness::class, 'client_id');
    }
}
