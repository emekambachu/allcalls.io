<?php

namespace App\Models;

use App\Models\Call;
use App\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CallDeviceAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function call()
    {
        return $this->belongsTo(Call::class); // Assuming you have a Call model
    }

    public function device()
    {
        return $this->belongsTo(Device::class); // Assuming you have a Device model
    }
}
