<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalAgentRegInfo extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function additionalInfo() {
        return $this->hasOne(InternalAgentAdditionalInfo::class, 'reg_info_id', 'id');
    }

    public function addresses() {
        return $this->hasMany(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function amlCourse() {
        return $this->hasOne(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function bankingInfo() {
        return $this->hasOne(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function errorAndEmission() {
        return $this->hasOne(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function legalQuestion() {
        return $this->hasMany(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function residentLicense() {
        return $this->hasOne(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

}
