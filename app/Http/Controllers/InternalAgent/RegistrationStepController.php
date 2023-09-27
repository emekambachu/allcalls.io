<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentAdditionalInfo;
use App\Models\InternalAgentAddress;
use App\Models\InternalAgentBankingInfo;
use App\Models\InternalAgentLegalQuestion;
use App\Models\InternalAgentRegInfo;
use App\Models\InternalAgentResidentLicense;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegistrationStepController extends Controller
{
    public function contractSteps()
    {
        $userData =  auth()->user();
        $states = State::all();
        return Inertia::render('InternalAgent/ContractSteps', [
            'userData' => $userData,
            'states' => $states,
        ]);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $step1Validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'ssn' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'martial_status' => 'required',
            'cell_phone' => 'required',
            'email' => 'required',
            'driver_license_no' => 'required',
            'driver_license_state' => 'required',
            'address' => 'required',
            'city_state' => 'required',
            'zip' => 'required',
            'move_in_date' => 'required',
            'move_in_address' => 'required',
            'move_in_city_state' => 'required',
            'move_in_zip' => 'required',
            'resident_insu_license_no' => 'required',
            'resident_insu_license_state' => 'required',
            'business_name' => 'nullable|required_with:business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_tax_id' => 'nullable|required_with:business_name,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_agent_name' => 'nullable|required_with:business_name,business_tax_id,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_agent_title' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_company_type' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_insu_license_no' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_office_fax' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_office_phone' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_email,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_email' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_website,business_address,business_city_state,business_zip,business_move_in_date',
            'business_website' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_address,business_city_state,business_zip,business_move_in_date',
            'business_address' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_city_state,business_zip,business_move_in_date',
            'business_city' => 'nullable|required_with:business_name,business_tate,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_zip,business_move_in_date',
            'business_state' => 'nullable|required_with:business_name,business_city,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_zip,business_move_in_date',
            'business_zip' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_move_in_date',
            'business_move_in_date' => 'nullable|required_with:business_name,business_tax_id,business_agent_name,business_agent_title,business_company_type,business_insu_license_no,business_office_fax,business_office_phone,business_email,business_website,business_address,business_city_state,business_zip',
        ], [
            'business_name.required_with' => 'The business name is required.',
            'business_tax_id.required_with' => 'The business tax ID is required.',
            'business_agent_name.required_with' => 'The business agent name is required.',
            'business_agent_title.required_with' => 'The business agent title is required.',
            'business_company_type.required_with' => 'The business company type is required.',
            'business_insu_license_no.required_with' => 'The business insurance license number is required.',
            'business_office_fax.required_with' => 'The business office fax is required.',
            'business_office_phone.required_with' => 'The business office phone is required.',
            'business_email.required_with' => 'The business email is required.',
            'business_website.required_with' => 'The business website is required.',
            'business_address.required_with' => 'The business address is required.',
            'business_city.required_with' => 'The business city is required.',
            'business_state.required_with' => 'The business state is required.',
            'business_zip.required_with' => 'The business ZIP code is required.',
            'business_move_in_date.required_with' => 'The business move-in date is required.',
        ]);
        if ($step1Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 1,
                'errors' => $step1Validation->errors(),
            ], 400);
        }


        $step1SubStep2Validation = Validator::make($request->all(), [
            'convicted_checkbox_1' => 'required',
            'convicted_checkbox_1a' => 'required',
            'convicted_checkbox_1b' => 'required',
            'convicted_checkbox_1c' => 'required',
        ], [
            'omissions_insurance.required' => 'This field is required.',
            'convicted_checkbox_1.required' => 'This field is required.',
            'convicted_checkbox_1a.required' => 'This field is required.',
            'convicted_checkbox_1b.required' => 'This field is required.',
            'convicted_checkbox_1c.required' => 'This field is required.',
        ]);
        if ($step1SubStep2Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 2,
                'errors' => $step1SubStep2Validation->errors(),
            ], 400);
        }

        $step1SubStep3Validation = Validator::make($request->all(), [
            'bankruptcy_checkbox_15b' => 'required',
            'liens_against_checkbox_16' => 'required',
            'connected_checkbox_17' => 'required',
            'aliases_checkbox_18' => 'required',
            'unresolved_matter_checkbox_19' => 'required',
        ], [
            'bankruptcy_checkbox_15b.required' => 'This field is required.',
            'liens_against_checkbox_16.required' => 'This field is required.',
            'connected_checkbox_17.required' => 'This field is required.',
            'aliases_checkbox_18.required' => 'This field is required.',
            'unresolved_matter_checkbox_19.required' => 'This field is required.',
        ]);
        if ($step1SubStep3Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 3,
                'errors' => $step1SubStep3Validation->errors(),
            ], 400);
        }


        $step1SubStep4Validation = Validator::make($request->all(), [
            // 'resident_country' => 'required',
            // 'resident_your_home' => 'required',
            // 'resident_city_state' => 'required',
            // 'resident_maiden_name' => 'required',
        ]);
        if ($step1SubStep4Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 4,
                'errors' => $step1SubStep4Validation->errors(),
            ], 400);
        }

        $step2Validation = Validator::make($request->all(), [
            'aml_course' => 'required',
        ]);
        if ($step2Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 5,
                'errors' => $step2Validation->errors(),
            ], 400);
        }

        $step3Validation = Validator::make($request->all(), [
            'omissions_insurance' => 'required',
        ]);
        if ($step3Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 6,
                'errors' => $step3Validation->errors(),
            ], 400);
        }

        $step4Validation = Validator::make($request->all(), [
            'residentLicensePdf' => 'required|mimetypes:application/pdf|max:2048',
        ]);
        if ($step4Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 7,
                'errors' => $step4Validation->errors(),
            ], 400);
        }

        $step5Validation = Validator::make($request->all(), [
            'bankingInfoPdf' => 'required|mimetypes:application/pdf|max:2048',
        ]);
        if ($step5Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 8,
                'errors' => $step5Validation->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $basicInfo = InternalAgentRegInfo::where('user_id', auth()->user()->id)->first();
            if (!$basicInfo) {
                $basicInfo = InternalAgentRegInfo::create([
                    'user_id' => auth()->user()->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => isset($request->middle_name) ? $request->middle_name : null,
                    'ssn' => isset($request->ssn) ? $request->ssn : null,
                    'gender' => isset($request->gender) ? $request->gender : null,
                    'dob' => isset($request->dob) ? date('m/d/Y', strtotime($request->dob)) : null,
                    'martial_status' => isset($request->martial_status) ? $request->martial_status : null,
                    'cell_phone' => isset($request->cell_phone) ? $request->cell_phone : null,
                    'home_phone' => isset($request->home_phone) ? $request->home_phone : null,
                    'fax' => isset($request->fax) ? $request->fax : null,
                    'email' => isset($request->email) ? $request->email : null,
                    'driver_license_no' => isset($request->driver_license_no) ? $request->driver_license_no : null,
                    'driver_license_state' => isset($request->driver_license_state) ? $request->driver_license_state : null,
                    'address' => isset($request->address) ? $request->address : null,
                    'city_state' => isset($request->city_state) ? $request->city_state : null,
                    'zip' => isset($request->zip) ? $request->zip : null,
                    'move_in_date' => isset($request->move_in_date) ? date('m/d/Y', strtotime($request->move_in_date)) : null,
                    'move_in_address' => isset($request->move_in_address) ? $request->move_in_address : null,
                    'move_in_city_state' => isset($request->move_in_city_state) ? $request->move_in_city_state : null,
                    'move_in_zip' => isset($request->move_in_zip) ? $request->move_in_zip : null,
                    'resident_insu_license_no' => isset($request->resident_insu_license_no) ? $request->resident_insu_license_no : null,
                    'resident_insu_license_state' => isset($request->resident_insu_license_state) ? $request->resident_insu_license_state : null,
                    'doing_business_as' => isset($request->doing_business_as) ? $request->doing_business_as : null,
                    'business_name' => isset($request->business_name) ? $request->business_name : null,
                    'business_tax_id' => isset($request->business_tax_id) ? $request->business_tax_id : null,
                    'business_agent_name' => isset($request->business_agent_name) ? $request->business_agent_name : null,
                    'business_agent_title' => isset($request->business_agent_title) ? $request->business_agent_title : null,
                    'business_company_type' => isset($request->business_company_type) ? $request->business_company_type : null,
                    'business_insu_license_no' => isset($request->business_insu_license_no) ? $request->business_insu_license_no : null,
                    'business_office_fax' => isset($request->business_office_fax) ? $request->business_office_fax : null,
                    'business_office_phone' => isset($request->business_office_phone) ? $request->business_office_phone : null,
                    'business_email' => isset($request->business_email) ? $request->business_email : null,
                    'business_website' => isset($request->business_website) ? $request->business_website : null,
                    'business_address' => isset($request->business_address) ? $request->business_address : null,
                    'business_city_state' => isset($request->business_city_state) ? $request->business_city_state : null,
                    'business_zip' => isset($request->business_zip) ? $request->business_zip : null,
                    'business_move_in_date' => isset($request->business_move_in_date) ? date('m/d/Y', strtotime($request->business_move_in_date)) : null,
                    'aml_course' => isset($request->aml_course) ? $request->aml_course : null,
                    'omissions_insurance' => isset($request->omissions_insurance) ? $request->omissions_insurance : null,
                ]);
                $basicInfoId = $basicInfo->id;
            } else {
                $basicInfoId = $basicInfo->id;
                $basicInfo->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => isset($request->middle_name) ? $request->middle_name : null,
                    'ssn' => isset($request->ssn) ? $request->ssn : null,
                    'gender' => isset($request->gender) ? $request->gender : null,
                    'dob' => isset($request->dob) ? date('m/d/Y', strtotime($request->dob)) : null,
                    'martial_status' => isset($request->martial_status) ? $request->martial_status : null,
                    'cell_phone' => isset($request->cell_phone) ? $request->cell_phone : null,
                    'home_phone' => isset($request->home_phone) ? $request->home_phone : null,
                    'fax' => isset($request->fax) ? $request->fax : null,
                    'email' => isset($request->email) ? $request->email : null,
                    'driver_license_no' => isset($request->driver_license_no) ? $request->driver_license_no : null,
                    'driver_license_state' => isset($request->driver_license_state) ? $request->driver_license_state : null,
                    'address' => isset($request->address) ? $request->address : null,
                    'city_state' => isset($request->city_state) ? $request->city_state : null,
                    'zip' => isset($request->zip) ? $request->zip : null,
                    'move_in_date' => isset($request->move_in_date) ? date('m/d/Y', strtotime($request->move_in_date)) : null,
                    'move_in_address' => isset($request->move_in_address) ? $request->move_in_address : null,
                    'move_in_city_state' => isset($request->move_in_city_state) ? $request->move_in_city_state : null,
                    'move_in_zip' => isset($request->move_in_zip) ? $request->move_in_zip : null,
                    'resident_insu_license_no' => isset($request->resident_insu_license_no) ? $request->resident_insu_license_no : null,
                    'resident_insu_license_state' => isset($request->resident_insu_license_state) ? $request->resident_insu_license_state : null,
                    'doing_business_as' => isset($request->doing_business_as) ? $request->doing_business_as : null,
                    'business_name' => isset($request->business_name) ? $request->business_name : null,
                    'business_tax_id' => isset($request->business_tax_id) ? $request->business_tax_id : null,
                    'business_agent_name' => isset($request->business_agent_name) ? $request->business_agent_name : null,
                    'business_agent_title' => isset($request->business_agent_title) ? $request->business_agent_title : null,
                    'business_company_type' => isset($request->business_company_type) ? $request->business_company_type : null,
                    'business_insu_license_no' => isset($request->business_insu_license_no) ? $request->business_insu_license_no : null,
                    'business_office_fax' => isset($request->business_office_fax) ? $request->business_office_fax : null,
                    'business_office_phone' => isset($request->business_office_phone) ? $request->business_office_phone : null,
                    'business_email' => isset($request->business_email) ? $request->business_email : null,
                    'business_website' => isset($request->business_website) ? $request->business_website : null,
                    'business_address' => isset($request->business_address) ? $request->business_address : null,
                    'business_city_state' => isset($request->business_city_state) ? $request->business_city_state : null,
                    'business_zip' => isset($request->business_zip) ? $request->business_zip : null,
                    'business_move_in_date' => isset($request->business_move_in_date) ? date('m/d/Y', strtotime($request->business_move_in_date)) : null,
                    'aml_course' => isset($request->aml_course) ? $request->aml_course : null,
                    'omissions_insurance' => isset($request->omissions_insurance) ? $request->omissions_insurance : null,
                ]);
            }

            InternalAgentLegalQuestion::updateOrCreate(['reg_info_id' => $basicInfoId], [
                'convicted_checkbox_1' => isset($request->convicted_checkbox_1) ? $request->convicted_checkbox_1 : null,
                'convicted_checkbox_1a' => isset($request->convicted_checkbox_1a) ? $request->convicted_checkbox_1a : null,
                'convicted_checkbox_1b' => isset($request->convicted_checkbox_1b) ? $request->convicted_checkbox_1b : null,
                'convicted_checkbox_1c' => isset($request->convicted_checkbox_1c) ? $request->convicted_checkbox_1c : null,
                'convicted_checkbox_1d' => isset($request->convicted_checkbox_1d) ? $request->convicted_checkbox_1d : null,
                'convicted_checkbox_1e' => isset($request->convicted_checkbox_1e) ? $request->convicted_checkbox_1e : null,
                'convicted_checkbox_1f' => isset($request->convicted_checkbox_1f) ? $request->convicted_checkbox_1f : null,
                'convicted_checkbox_1g' => isset($request->convicted_checkbox_1g) ? $request->convicted_checkbox_1g : null,
                'convicted_checkbox_1h' => isset($request->convicted_checkbox_1h) ? $request->convicted_checkbox_1h : null,
                'lawsuit_checkbox_2' => isset($request->lawsuit_checkbox_2) ? $request->lawsuit_checkbox_2 : null,
                'lawsuit_checkbox_2a' => isset($request->lawsuit_checkbox_2a) ? $request->lawsuit_checkbox_2a : null,
                'lawsuit_checkbox_2b' => isset($request->lawsuit_checkbox_2b) ? $request->lawsuit_checkbox_2b : null,
                'lawsuit_checkbox_2c' => isset($request->lawsuit_checkbox_2c) ? $request->lawsuit_checkbox_2c : null,
                'lawsuit_checkbox_2d' => isset($request->lawsuit_checkbox_2d) ? $request->lawsuit_checkbox_2d : null,
                'alleged_engaged_checkbox_3' => isset($request->alleged_engaged_checkbox_3) ? $request->alleged_engaged_checkbox_3 : null,
                'found_engaged_checkbox_4' => isset($request->found_engaged_checkbox_4) ? $request->found_engaged_checkbox_4 : null,
                'terminate_contract_checkbox_5' => isset($request->terminate_contract_checkbox_5) ? $request->terminate_contract_checkbox_5 : null,
                'terminate_contract_checkbox_5a' => isset($request->terminate_contract_checkbox_5a) ? $request->terminate_contract_checkbox_5a : null,
                'terminate_contract_checkbox_5b' => isset($request->terminate_contract_checkbox_5b) ? $request->terminate_contract_checkbox_5b : null,
                'terminate_contract_checkbox_5c' => isset($request->terminate_contract_checkbox_5c) ? $request->terminate_contract_checkbox_5c : null,
                'cancel_insu_cause_checkbox_6' => isset($request->cancel_insu_cause_checkbox_6) ? $request->cancel_insu_cause_checkbox_6 : null,
                'insurer_checkbox_7' => isset($request->insurer_checkbox_7) ? $request->insurer_checkbox_7 : null,
                'lawsuit_checkbox_8' => isset($request->lawsuit_checkbox_8) ? $request->lawsuit_checkbox_8 : null,
                'lawsuit_checkbox_8a' => isset($request->lawsuit_checkbox_8a) ? $request->lawsuit_checkbox_8a : null,
                'lawsuit_checkbox_8b' => isset($request->lawsuit_checkbox_8b) ? $request->lawsuit_checkbox_8b : null,
                'license_denied_checkbox_9' => isset($request->license_denied_checkbox_9) ? $request->license_denied_checkbox_9 : null,
                'regulatory_checkbox_10' => isset($request->regulatory_checkbox_10) ? $request->regulatory_checkbox_10 : null,
                'regulatory_revoked_checkbox_11' => isset($request->regulatory_revoked_checkbox_11) ? $request->regulatory_revoked_checkbox_11 : null,
                'regulatory_found_checkbox_12' => isset($request->regulatory_found_checkbox_12) ? $request->regulatory_found_checkbox_12 : null,
                'interr_licensing_checkbox_13' => isset($request->interr_licensing_checkbox_13) ? $request->interr_licensing_checkbox_13 : null,
                'self_regularity_checkbox_14' => isset($request->self_regularity_checkbox_14) ? $request->self_regularity_checkbox_14 : null,
                'self_regularity_checkbox_14a' => isset($request->self_regularity_checkbox_14a) ? $request->self_regularity_checkbox_14a : null,
                'self_regularity_checkbox_14b' => isset($request->self_regularity_checkbox_14b) ? $request->self_regularity_checkbox_14b : null,
                'self_regularity_checkbox_14c' => isset($request->self_regularity_checkbox_14c) ? $request->self_regularity_checkbox_14c : null,
                'bankruptcy_checkbox_15' => isset($request->bankruptcy_checkbox_15) ? $request->bankruptcy_checkbox_15 : null,
                'bankruptcy_checkbox_15a' => isset($request->bankruptcy_checkbox_15a) ? $request->bankruptcy_checkbox_15a : null,
                'bankruptcy_checkbox_15b' => isset($request->bankruptcy_checkbox_15b) ? $request->bankruptcy_checkbox_15b : null,
                'bankruptcy_checkbox_15c' => isset($request->bankruptcy_checkbox_15c) ? $request->bankruptcy_checkbox_15c : null,
                'liens_against_checkbox_16' => isset($request->liens_against_checkbox_16) ? $request->liens_against_checkbox_16 : null,
                'connected_checkbox_17' => isset($request->connected_checkbox_17) ? $request->connected_checkbox_17 : null,
                'aliases_checkbox_18' => isset($request->aliases_checkbox_18) ? $request->aliases_checkbox_18 : null,
                'unresolved_matter_checkbox_19' => isset($request->unresolved_matter_checkbox_19) ? $request->unresolved_matter_checkbox_19 : null,
            ]);

            InternalAgentAdditionalInfo::updateOrCreate(['reg_info_id' => $basicInfoId], [
                'resident_country' => isset($request->resident_country) ? $request->resident_country : null,
                'resident_your_home' => isset($request->resident_your_home) ? $request->resident_your_home : null,
                'resident_city_state' => isset($request->resident_city_state) ? $request->resident_city_state : null,
                'resident_maiden_name' => isset($request->resident_maiden_name) ? $request->resident_maiden_name : null,
                'aml_provider' => isset($request->aml_provider) ? $request->aml_provider : null,
                'training_completion_date' => isset($request->training_completion_date) ? date('m/d/Y', strtotime($request->training_completion_date)) : null,
                'limra_password' => isset($request->limra_password) ? $request->limra_password : null,
            ]);

            $addresses = [];

            if (isset($request->history_address1)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address1['address']) ? $request->history_address1['address'] : null,
                    'city_state' => isset($request->history_address1['city']) ? $request->history_address1['city'] : null,
                    'zip' => isset($request->history_address1['zip_code']) ? $request->history_address1['zip_code'] : null,
                    'move_in_date' => isset($request->history_address1['move_in_date']) ? date('m/d/Y', strtotime($request->history_address1['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address1['move_out_date']) ? date('m/d/Y', strtotime($request->history_address1['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address2)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address2['address']) ? $request->history_address2['address'] : null,
                    'city_state' => isset($request->history_address2['city']) ? $request->history_address2['city'] : null,
                    'zip' => isset($request->history_address2['zip_code']) ? $request->history_address2['zip_code'] : null,
                    'move_in_date' => isset($request->history_address2['move_in_date']) ? date('m/d/Y', strtotime($request->history_address2['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address2['move_out_date']) ? date('m/d/Y', strtotime($request->history_address2['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address3)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address3['address']) ? $request->history_address3['address'] : null,
                    'city_state' => isset($request->history_address3['city']) ? $request->history_address3['city'] : null,
                    'zip' => isset($request->history_address3['zip_code']) ? $request->history_address3['zip_code'] : null,
                    'move_in_date' => isset($request->history_address3['move_in_date']) ? date('m/d/Y', strtotime($request->history_address3['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address3['move_out_date']) ? date('m/d/Y', strtotime($request->history_address3['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address4)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address4['address']) ? $request->history_address4['address'] : null,
                    'city_state' => isset($request->history_address4['city']) ? $request->history_address4['city'] : null,
                    'zip' => isset($request->history_address4['zip_code']) ? $request->history_address4['zip_code'] : null,
                    'move_in_date' => isset($request->history_address4['move_in_date']) ? date('m/d/Y', strtotime($request->history_address4['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address4['move_out_date']) ? date('m/d/Y', strtotime($request->history_address4['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address5)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address5['address']) ? $request->history_address5['address'] : null,
                    'city_state' => isset($request->history_address5['city']) ? $request->history_address5['city'] : null,
                    'zip' => isset($request->history_address5['zip_code']) ? $request->history_address5['zip_code'] : null,
                    'move_in_date' => isset($request->history_address5['move_in_date']) ? date('m/d/Y', strtotime($request->history_address5['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address5['move_out_date']) ? date('m/d/Y', strtotime($request->history_address5['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address6)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address6['address']) ? $request->history_address6['address'] : null,
                    'city_state' => isset($request->history_address6['city']) ? $request->history_address6['city'] : null,
                    'zip' => isset($request->history_address6['zip_code']) ? $request->history_address6['zip_code'] : null,
                    'move_in_date' => isset($request->history_address6['move_in_date']) ? date('m/d/Y', strtotime($request->history_address6['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address6['move_out_date']) ? date('m/d/Y', strtotime($request->history_address6['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->history_address7)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address7['address']) ? $request->history_address7['address'] : null,
                    'city_state' => isset($request->history_address7['city']) ? $request->history_address7['city'] : null,
                    'zip' => isset($request->history_address7['zip_code']) ? $request->history_address7['zip_code'] : null,
                    'move_in_date' => isset($request->history_address7['move_in_date']) ? date('m/d/Y', strtotime($request->history_address7['move_in_date'])) : null,
                    'move_out_date' => isset($request->history_address7['move_out_date']) ? date('m/d/Y', strtotime($request->history_address7['move_out_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (count($addresses)) {
                InternalAgentAddress::where('reg_info_id', $basicInfoId)->delete();
                DB::table('internal_agent_addresses')->insert($addresses);
            }

            if ($request->file('residentLicensePdf') && $request->file('residentLicensePdf')->isValid()) {

                $residentPDf = InternalAgentResidentLicense::where('reg_info_id', $basicInfoId)->first();
                if ($residentPDf) {
                    if (file_exists(asset('internal-agents/resident-license-pdf/' . $residentPDf->name))) {
                        unlink(asset('internal-agents/resident-license-pdf/' . $residentPDf->name));
                    }
                    $residentPDf->delete();
                }

                $name = $request->file('residentLicensePdf')->getClientOriginalName();
                $request->file('residentLicensePdf')->move(public_path('internal-agents/resident-license-pdf'), $name);
                $path = asset('internal-agents/resident-license-pdf/' . $name);
                InternalAgentResidentLicense::updateOrCreate(['reg_info_id' => $basicInfoId], [
                    'name' => $name,
                    'url' => $path,
                ]);
            }

            if ($request->file('bankingInfoPdf') && $request->file('bankingInfoPdf')->isValid()) {
                $bankingInfoPdf = InternalAgentBankingInfo::where('reg_info_id', $basicInfoId)->first();
                if ($bankingInfoPdf) {
                    if (file_exists(asset('internal-agents/banking-info/' . $bankingInfoPdf->name))) {
                        unlink(asset('internal-agents/banking-info/' . $bankingInfoPdf->name));
                    }
                    $bankingInfoPdf->delete();
                }

                $name = $request->file('bankingInfoPdf')->getClientOriginalName();
                $request->file('bankingInfoPdf')->move(public_path('internal-agents/banking-info'), $name);
                $path = asset('internal-agents/banking-info/' . $name);

                InternalAgentBankingInfo::updateOrCreate(['reg_info_id' => $basicInfoId], [
                    'name' => $name,
                    'url' => $path,
                ]);
            }

            DB::commit();
            return Inertia::render('InternalAgent/ContractSteps')
                ->with('message', 'Internal agent registration completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }
}
