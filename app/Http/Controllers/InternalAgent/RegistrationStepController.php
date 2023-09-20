<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentAdditionalInfo;
use App\Models\InternalAgentLegalQuestion;
use App\Models\InternalAgentRegInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationStepController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $basicInfo = InternalAgentRegInfo::create([
            'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'ssn' => $request->ssn,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'martial_status' => $request->martial_status,
            'cell_phone' => $request->cell_phone,
            'home_phone' => $request->home_phone,
            'fax' => $request->fax,
            'email' => $request->email,
            'driver_license_no' => $request->driver_license_no,
            'driver_license_state' => $request->driver_license_state,
            'address' => $request->address,
            'city_state' => $request->city_state,
            'zip' => $request->zip,
            'move_in_date' => $request->move_in_date,
            'move_in_address' => $request->move_in_address,
            'move_in_city_state' => $request->move_in_city_state,
            'move_in_zip' => $request->move_in_zip,
            'resident_insu_license_no' => $request->resident_insu_license_no,
            'resident_insu_license_state' => $request->resident_insu_license_state,
            'doing_business_as' => $request->doing_business_as,
            'business_name' => $request->business_name,
            'business_tax_id' => $request->business_tax_id,
            'business_agent_name' => $request->business_agent_name,
            'business_agent_title' => $request->business_agent_title,
            'business_company_type' => $request->business_company_type,
            'business_insu_license_no' => $request->business_insu_license_no,
            'business_office_fax' => $request->business_office_fax,
            'business_office_phone' => $request->business_office_phone,
            'business_email' => $request->business_email,
            'business_website' => $request->business_website,
            'business_address' => $request->business_address,
            'business_city_state' => $request->business_city_state,
            'business_zip' => $request->business_zip,
            'business_move_in_date' => $request->business_move_in_date,
        ]);

        InternalAgentLegalQuestion::create([
            'reg_info_id' => $basicInfo->id,
            'convicted_checkbox_1' => $request->convicted_checkbox_1,
            'convicted_checkbox_1a' => $request->convicted_checkbox_1a,
            'convicted_checkbox_1b' => $request->convicted_checkbox_1b,
            'convicted_checkbox_1c' => $request->convicted_checkbox_1c,
            'convicted_checkbox_1d' => $request->convicted_checkbox_1d,
            'convicted_checkbox_1e' => $request->convicted_checkbox_1e,
            'convicted_checkbox_1f' => $request->convicted_checkbox_1f,
            'convicted_checkbox_1g' => $request->convicted_checkbox_1g,
            'convicted_checkbox_1h' => $request->convicted_checkbox_1h,
            'lawsuit_checkbox_2' => $request->lawsuit_checkbox_2,
            'lawsuit_checkbox_2a' => $request->lawsuit_checkbox_2a,
            'lawsuit_checkbox_2b' => $request->lawsuit_checkbox_2b,
            'lawsuit_checkbox_2c' => $request->lawsuit_checkbox_2c,
            'lawsuit_checkbox_2d' => $request->lawsuit_checkbox_2d,
            'alleged_engaged_checkbox_3' => $request->alleged_engaged_checkbox_3,
            'found_engaged_checkbox_4' => $request->found_engaged_checkbox_4,
            'terminate_contract_checkbox_5' => $request->terminate_contract_checkbox_5,
            'terminate_contract_checkbox_5a' => $request->terminate_contract_checkbox_5a,
            'terminate_contract_checkbox_5b' => $request->terminate_contract_checkbox_5b,
            'terminate_contract_checkbox_5c' => $request->terminate_contract_checkbox_5c,
            'cancel_insu_cause_checkbox_6' => $request->cancel_insu_cause_checkbox_6,
            'insurer_checkbox_7' => $request->insurer_checkbox_7,
            'lawsuit_checkbox_8' => $request->lawsuit_checkbox_8,
            'lawsuit_checkbox_8a' => $request->lawsuit_checkbox_8a,
            'lawsuit_checkbox_8b' => $request->lawsuit_checkbox_8b,
            'license_denied_checkbox_9' => $request->license_denied_checkbox_9,
            'regulatory_checkbox_10' => $request->regulatory_checkbox_10,
            'regulatory_revoked_checkbox_11' => $request->regulatory_revoked_checkbox_11,
            'regulatory_found_checkbox_12' => $request->regulatory_found_checkbox_12,
            'interr_licensing_checkbox_13' => $request->interr_licensing_checkbox_13,
            'self_regularity_checkbox_14' => $request->self_regularity_checkbox_14,
            'self_regularity_checkbox_14a' => $request->self_regularity_checkbox_14a,
            'self_regularity_checkbox_14b' => $request->self_regularity_checkbox_14b,
            'self_regularity_checkbox_14c' => $request->self_regularity_checkbox_14c,
            'bankruptcy_checkbox_15' => $request->bankruptcy_checkbox_15,
            'bankruptcy_checkbox_15a' => $request->bankruptcy_checkbox_15a,
            'bankruptcy_checkbox_15b' => $request->bankruptcy_checkbox_15b,
            'bankruptcy_checkbox_15c' => $request->bankruptcy_checkbox_15c,
            'liens_against_checkbox_16' => $request->liens_against_checkbox_16,
            'connected_checkbox_17' => $request->connected_checkbox_17,
            'aliases_checkbox_18' => $request->aliases_checkbox_18,
            'unresolved_matter_checkbox_19' => $request->unresolved_matter_checkbox_19,
        ]);

        InternalAgentAdditionalInfo::create([
            'reg_info_id' => $basicInfo->id,
            'resident_country' => $request->resident_country,
            'resident_your_home' => $request->resident_your_home,
            'resident_city_state' => $request->resident_city_state,
            'resident_maiden_name' => $request->resident_maiden_name,
            'aml_provider' => $request->aml_provider,
            'training_completion_date' => $request->training_completion_date,
            'limra_password' => $request->limra_password,
        ]);

    }
}
