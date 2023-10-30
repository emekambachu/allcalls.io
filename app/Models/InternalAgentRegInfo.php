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
        return $this->hasMany(InternalAgentAddress::class, 'reg_info_id', 'id');
    }

    public function driverLicense() {
        return $this->hasOne(InternalAgentDriverLicense::class, 'reg_info_id', 'id');
    }

    public function amlCourse() {
        return $this->hasOne(InternalAgentAmlCourse::class, 'reg_info_id', 'id');
    }

    public function bankingInfo() {
        return $this->hasOne(InternalAgentBankingInfo::class, 'reg_info_id', 'id');
    }

    public function errorAndEmission() {
        return $this->hasOne(InternalAgentErrorAndEmission::class, 'reg_info_id', 'id');
    }

    public function legalQuestion() {
        return $this->hasMany(InternalAgentLegalQuestion::class, 'reg_info_id', 'id');
    }

    public function residentLicense() {
        return $this->hasOne(InternalAgentResidentLicense::class, 'reg_info_id', 'id');
    }

    public function getQuestionSign() {
        return $this->hasOne(InternalAgentQuestionSigned::class, 'reg_info_id', 'id');
    }

    public function getContractSign() {
        return $this->hasOne(InternalAgentContractSigned::class, 'reg_info_id', 'id');
    }

    public function getState() {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function getDriverLicenseState() {
        return $this->hasOne(State::class, 'id', 'driver_license_state');
    }

    public function getMoveInState() {
        return $this->hasOne(State::class, 'id', 'move_in_state');
    }

    public function getResidentInsLicenseState() {
        return $this->hasOne(State::class, 'id', 'resident_insu_license_state');
    }

    public function getBusinessState() {
        return $this->hasOne(State::class, 'id', 'business_state');
    }
}
