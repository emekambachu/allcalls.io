<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalAgentMyBusiness extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function client(){
        return $this->belongsTo(Client::class,'client_id', 'id');
    }

    public function getApplicationDateAttribute($value)
    {
        $date = Carbon::parse($value);
    
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            $date->timezone($timezone);
        }
    
        return $date->format('m/d/Y');
    }
    
    public function getPolicyDraftDateAttribute($value)
    {
        $date = Carbon::parse($value);
    
        if (auth()->user()) {
            $timezone = auth()->user()->timezone;
            $date->timezone($timezone);
        }
    
        return $date->format('m/d/Y');
    }
}
