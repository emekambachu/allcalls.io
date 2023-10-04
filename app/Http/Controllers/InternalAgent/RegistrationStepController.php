<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentAdditionalInfo;
use App\Models\InternalAgentAddress;
use App\Models\InternalAgentAmlCourse;
use App\Models\InternalAgentBankingInfo;
use App\Models\InternalAgentErrorAndEmission;
use App\Models\InternalAgentLegalQuestion;
use App\Models\InternalAgentRegInfo;
use App\Models\InternalAgentResidentLicense;
use App\Models\State;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegistrationStepController extends Controller
{
    public function contractSteps()
    {
        if(auth()->user()->legacy_key) {
            return redirect()->route('dashboard');
        }
        $userData = auth()->user();
        $states = State::all();
        return Inertia::render('InternalAgent/ContractSteps', [
            'userData' => $userData,
            'states' => $states,
        ]);
    }

    public function store(Request $request)
    {
        $step1Validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'ssn' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'marital_status' => 'required',
            'cell_phone' => 'required',
            'email' => 'required',
            'driver_license_no' => 'required',
            'driver_license_state' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'move_in_date' => 'required',
            'move_in_address' => 'required',
            'move_in_city' => 'required',
            'move_in_state' => 'required',
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
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',
            'middle_name.required' => 'This field is required.',
            'ssn.required' => 'This field is required.',
            'gender.required' => 'This field is required.',
            'dob.required' => 'This field is required.',
            'marital_status.required' => 'This field is required.',
            'cell_phone.required' => 'This field is required.',
            'email.required' => 'This field is required.',
            'driver_license_no.required' => 'This field is required.',
            'driver_license_state.required' => 'This field is required.',
            'address.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'zip.required' => 'This field is required.',
            'move_in_date.required' => 'This field is required.',
            'move_in_address.required' => 'This field is required.',
            'move_in_city.required' => 'This field is required.',
            'move_in_state.required' => 'This field is required.',
            'move_in_zip.required' => 'This field is required.',
            'resident_insu_license_no.required' => 'This field is required.',
            'resident_insu_license_state.required' => 'This field is required.',
            'business_name.required_with' => 'This field is required.',
            'business_tax_id.required_with' => 'This field is required.',
            'business_agent_name.required_with' => 'This field is required.',
            'business_agent_title.required_with' => 'This field is required.',
            'business_company_type.required_with' => 'This field is required.',
            'business_insu_license_no.required_with' => 'This field is required.',
            'business_office_fax.required_with' => 'This field is required.',
            'business_office_phone.required_with' => 'This field is required.',
            'business_email.required_with' => 'This field is required.',
            'business_website.required_with' => 'This field is required.',
            'business_address.required_with' => 'This field is required.',
            'business_city.required_with' => 'This field is required.',
            'business_state.required_with' => 'This field is required.',
            'business_zip.required_with' => 'This field is required.',
            'business_move_in_date.required_with' => 'This field is required.',
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
            'convicted_checkbox_1_text' => 'nullable|required_if:convicted_checkbox_1,=,YES',
            'convicted_checkbox_1a' => 'required',
            'convicted_checkbox_1a_text' => 'nullable|required_if:convicted_checkbox_1a,=,YES',
            'convicted_checkbox_1b' => 'required',
            'convicted_checkbox_1b_text' => 'nullable|required_if:convicted_checkbox_1b,=,YES',
            'convicted_checkbox_1c' => 'required',
            'convicted_checkbox_1c_text' => 'nullable|required_if:convicted_checkbox_1c,=,YES',
            'convicted_checkbox_1d' => 'required',
            'convicted_checkbox_1d_text' => 'nullable|required_if:convicted_checkbox_1d,=,YES',
            'convicted_checkbox_1e' => 'required',
            'convicted_checkbox_1e_text' => 'nullable|required_if:convicted_checkbox_1e,=,YES',
            'convicted_checkbox_1f' => 'required',
            'convicted_checkbox_1f_text' => 'nullable|required_if:convicted_checkbox_1f,=,YES',
            'convicted_checkbox_1g' => 'required',
            'convicted_checkbox_1g_text' => 'nullable|required_if:convicted_checkbox_1g,=,YES',
            'convicted_checkbox_1h' => 'required',
            'convicted_checkbox_1h_text' => 'nullable|required_if:convicted_checkbox_1h,=,YES',
            'lawsuit_checkbox_2' => 'required',
            'lawsuit_checkbox_2_text' => 'nullable|required_if:lawsuit_checkbox_2,=,YES',
            'lawsuit_checkbox_2a' => 'required',
            'lawsuit_checkbox_2a_text' => 'nullable|required_if:lawsuit_checkbox_2a,=,YES',
            'lawsuit_checkbox_2b' => 'required',
            'lawsuit_checkbox_2b_text' => 'nullable|required_if:lawsuit_checkbox_2b,=,YES',
            'lawsuit_checkbox_2c' => 'required',
            'lawsuit_checkbox_2c_text' => 'nullable|required_if:lawsuit_checkbox_2c,=,YES',
            'lawsuit_checkbox_2d' => 'required',
            'lawsuit_checkbox_2d_text' => 'nullable|required_if:lawsuit_checkbox_2d,=,YES',
            'alleged_engaged_checkbox_3' => 'required',
            'alleged_engaged_checkbox_3_text' => 'nullable|required_if:alleged_engaged_checkbox_3,=,YES',
            'found_engaged_checkbox_4' => 'required',
            'found_engaged_checkbox_4_text' => 'nullable|required_if:found_engaged_checkbox_4,=,YES',
            'terminate_contract_checkbox_5' => 'required',
            'terminate_contract_checkbox_5_text' => 'nullable|required_if:terminate_contract_checkbox_5,=,YES',
            'terminate_contract_checkbox_5a' => 'required',
            'terminate_contract_checkbox_5a_text' => 'nullable|required_if:terminate_contract_checkbox_5a,=,YES',
            'terminate_contract_checkbox_5b' => 'required',
            'terminate_contract_checkbox_5b_text' => 'nullable|required_if:terminate_contract_checkbox_5b,=,YES',
            'terminate_contract_checkbox_5c' => 'required',
            'terminate_contract_checkbox_5c_text' => 'nullable|required_if:terminate_contract_checkbox_5c,=,YES',
            'cancel_insu_cause_checkbox_6' => 'required',
            'cancel_insu_cause_checkbox_6_text' => 'nullable|required_if:cancel_insu_cause_checkbox_6,=,YES',
            'insurer_checkbox_7' => 'required',
            'insurer_checkbox_7_text' => 'nullable|required_if:insurer_checkbox_7,=,YES',
        ], [
            'convicted_checkbox_1.required' => 'This field is required.',
            'convicted_checkbox_1_text.required_if' => 'This field is required.',
            'convicted_checkbox_1a.required' => 'This field is required.',
            'convicted_checkbox_1a_text.required_if' => 'This field is required.',
            'convicted_checkbox_1b.required' => 'This field is required.',
            'convicted_checkbox_1b_text.required_if' => 'This field is required.',
            'convicted_checkbox_1c.required' => 'This field is required.',
            'convicted_checkbox_1c_text.required_if' => 'This field is required.',
            'convicted_checkbox_1d.required' => 'This field is required.',
            'convicted_checkbox_1d_text.required_if' => 'This field is required.',
            'convicted_checkbox_1e.required' => 'This field is required.',
            'convicted_checkbox_1e_text.required_if' => 'This field is required.',
            'convicted_checkbox_1f.required' => 'This field is required.',
            'convicted_checkbox_1f_text.required_if' => 'This field is required.',
            'convicted_checkbox_1g.required' => 'This field is required.',
            'convicted_checkbox_1g_text.required_if' => 'This field is required.',
            'convicted_checkbox_1h.required' => 'This field is required.',
            'convicted_checkbox_1h_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_2.required' => 'This field is required.',
            'lawsuit_checkbox_2_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_2a.required' => 'This field is required.',
            'lawsuit_checkbox_2a_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_2b.required' => 'This field is required.',
            'lawsuit_checkbox_2b_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_2c.required' => 'This field is required.',
            'lawsuit_checkbox_2c_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_2d.required' => 'This field is required.',
            'lawsuit_checkbox_2d_text.required_if' => 'This field is required.',
            'alleged_engaged_checkbox_3.required' => 'This field is required.',
            'alleged_engaged_checkbox_3_text.required_if' => 'This field is required.',
            'found_engaged_checkbox_4.required' => 'This field is required.',
            'found_engaged_checkbox_4_text.required_if' => 'This field is required.',
            'terminate_contract_checkbox_5.required' => 'This field is required.',
            'terminate_contract_checkbox_5_text.required_if' => 'This field is required.',
            'terminate_contract_checkbox_5a.required' => 'This field is required.',
            'terminate_contract_checkbox_5a_text.required_if' => 'This field is required.',
            'terminate_contract_checkbox_5b.required' => 'This field is required.',
            'terminate_contract_checkbox_5b_text.required_if' => 'This field is required.',
            'terminate_contract_checkbox_5c.required' => 'This field is required.',
            'terminate_contract_checkbox_5c_text.required_if' => 'This field is required.',
            'cancel_insu_cause_checkbox_6.required' => 'This field is required.',
            'cancel_insu_cause_checkbox_6_text.required_if' => 'This field is required.',
            'insurer_checkbox_7.required' => 'This field is required.',
            'insurer_checkbox_7_text.required_if' => 'This field is required.',
        ]);

        if ($step1SubStep2Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 2,
                'errors' => $step1SubStep2Validation->errors(),
            ], 400);
        }

        $step1SubStep3Validation = Validator::make($request->all(), [
            'lawsuit_checkbox_8' => 'required',
            'lawsuit_checkbox_8_text' => 'nullable|required_if:lawsuit_checkbox_8,=,YES',
            'lawsuit_checkbox_8a' => 'required',
            'lawsuit_checkbox_8a_text' => 'nullable|required_if:lawsuit_checkbox_8a,=,YES',
            'lawsuit_checkbox_8b' => 'required',
            'lawsuit_checkbox_8b_text' => 'nullable|required_if:lawsuit_checkbox_8b,=,YES',
            'license_denied_checkbox_9' => 'required',
            'license_denied_checkbox_9_text' => 'nullable|required_if:license_denied_checkbox_9,=,YES',
            'regulatory_checkbox_10' => 'required',
            'regulatory_checkbox_10_text' => 'nullable|required_if:regulatory_checkbox_10_text,=,YES',
            'regulatory_revoked_checkbox_11' => 'required',
            'regulatory_revoked_checkbox_11_text' => 'nullable|required_if:regulatory_revoked_checkbox_11,=,YES',
            'regulatory_found_checkbox_12' => 'required',
            'regulatory_found_checkbox_12_text' => 'nullable|required_if:regulatory_found_checkbox_12_text,=,YES',
            'interr_licensing_checkbox_13' => 'required',
            'interr_licensing_checkbox_13_text' => 'nullable|required_if:interr_licensing_checkbox_13_text,=,YES',
            'self_regularity_checkbox_14' => 'required',
            'self_regularity_checkbox_14_text' => 'nullable|required_if:self_regularity_checkbox_14_text,=,YES',
            'self_regularity_checkbox_14a' => 'required',
            'self_regularity_checkbox_14a_text' => 'nullable|required_if:self_regularity_checkbox_14a_text,=,YES',
            'self_regularity_checkbox_14b' => 'required',
            'self_regularity_checkbox_14b_text' => 'nullable|required_if:self_regularity_checkbox_14b_text,=,YES',
            'self_regularity_checkbox_14c' => 'required',
            'self_regularity_checkbox_14c_text' => 'nullable|required_if:self_regularity_checkbox_14c_text,=,YES',
            'bankruptcy_checkbox_15' => 'required',
            'bankruptcy_checkbox_15_text' => 'nullable|required_if:bankruptcy_checkbox_15,=,YES',
            'bankruptcy_checkbox_15a' => 'required',
            'bankruptcy_checkbox_15a_text' => 'nullable|required_if:bankruptcy_checkbox_15a,=,YES',
            'bankruptcy_checkbox_15b' => 'required',
            'bankruptcy_checkbox_15b_text' => 'nullable|required_if:bankruptcy_checkbox_15b,=,YES',
            'bankruptcy_checkbox_15c' => 'required',
            'bankruptcy_checkbox_15c_text' => 'nullable|required_if:bankruptcy_checkbox_15c,=,YES',
            'liens_against_checkbox_16' => 'required',
            'liens_against_checkbox_16_text' => 'nullable|required_if:liens_against_checkbox_16,=,YES',
            'connected_checkbox_17' => 'required',
            'connected_checkbox_17_text' => 'nullable|required_if:connected_checkbox_17,=,YES',
            'aliases_checkbox_18' => 'required',
            'aliases_checkbox_18_text' => 'nullable|required_if:aliases_checkbox_18,=,YES',
            'unresolved_matter_checkbox_19' => 'required',
            'unresolved_matter_checkbox_19_text' => 'nullable|required_if:unresolved_matter_checkbox_19,=,YES',

        ], [
            'lawsuit_checkbox_8.required' => 'This field is required.',
            'lawsuit_checkbox_8_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_8a.required' => 'This field is required.',
            'lawsuit_checkbox_8a_text.required_if' => 'This field is required.',
            'lawsuit_checkbox_8b.required' => 'This field is required.',
            'lawsuit_checkbox_8b_text.required_if' => 'This field is required.',
            'license_denied_checkbox_9.required' => 'This field is required.',
            'license_denied_checkbox_9_text.required_if' => 'This field is required.',
            'regulatory_checkbox_10.required' => 'This field is required.',
            'regulatory_checkbox_10_text.required_if' => 'This field is required.',
            'regulatory_revoked_checkbox_11.required' => 'This field is required.',
            'regulatory_revoked_checkbox_11_text.required_if' => 'This field is required.',
            'regulatory_found_checkbox_12.required' => 'This field is required.',
            'regulatory_found_checkbox_12_text.required_if' => 'This field is required.',
            'interr_licensing_checkbox_13.required' => 'This field is required.',
            'interr_licensing_checkbox_13_text.required_if' => 'This field is required.',
            'self_regularity_checkbox_14.required' => 'This field is required.',
            'self_regularity_checkbox_14_text.required_if' => 'This field is required.',
            'self_regularity_checkbox_14a.required' => 'This field is required.',
            'self_regularity_checkbox_14a_text.required_if' => 'This field is required.',
            'self_regularity_checkbox_14b.required' => 'This field is required.',
            'self_regularity_checkbox_14b_text.required_if' => 'This field is required.',
            'self_regularity_checkbox_14c.required' => 'This field is required.',
            'self_regularity_checkbox_14c_text.required_if' => 'This field is required.',
            'bankruptcy_checkbox_15.required' => 'This field is required.',
            'bankruptcy_checkbox_15_text.required_if' => 'This field is required.',
            'bankruptcy_checkbox_15a.required' => 'This field is required.',
            'bankruptcy_checkbox_15a_text.required_if' => 'This field is required.',
            'bankruptcy_checkbox_15b.required' => 'This field is required.',
            'bankruptcy_checkbox_15b_text.required_if' => 'This field is required.',
            'bankruptcy_checkbox_15c.required' => 'This field is required.',
            'bankruptcy_checkbox_15c_text.required_if' => 'This field is required.',
            'liens_against_checkbox_16.required' => 'This field is required.',
            'liens_against_checkbox_16_text.required_if' => 'This field is required.',
            'connected_checkbox_17.required' => 'This field is required.',
            'connected_checkbox_17_text.required_if' => 'This field is required.',
            'aliases_checkbox_18.required' => 'This field is required.',
            'aliases_checkbox_18_text.required_if' => 'This field is required.',
            'unresolved_matter_checkbox_19.required' => 'This field is required.',
            'unresolved_matter_checkbox_19_text.required_if' => 'This field is required.',
        ]);
        if ($step1SubStep3Validation->fails()) {
            return response()->json([
                'success' => false,
                'step' => 3,
                'errors' => $step1SubStep3Validation->errors(),
            ], 400);
        }


        $step1SubStep4Validation = Validator::make($request->all(), [
            'resident_country' => 'required',
            'resident_your_home' => 'required',
            'resident_city' => 'required',
            'resident_state' => 'required',
        ], [
            'resident_country.required' => 'This field is required.',
            'resident_your_home.required' => 'This field is required.',
            'resident_city.required' => 'This field is required.',
            'resident_state.required' => 'This field is required.',
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
            'uploadAmlPdf' => 'required|mimetypes:application/pdf|max:2048',
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
            'uploadOmmisionPdf' => 'required|mimetypes:application/pdf|max:2048',
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
                $user = auth()->user();
                $basicInfo = InternalAgentRegInfo::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => isset($request->middle_name) ? $request->middle_name : null,
                    'ssn' => isset($request->ssn) ? $request->ssn : null,
                    'gender' => isset($request->gender) ? $request->gender : null,
                    'dob' => isset($request->dob) ? date('m/d/Y', strtotime($request->dob)) : null,
                    'marital_status' => isset($request->marital_status) ? $request->marital_status : null,
                    'cell_phone' => isset($request->cell_phone) ? $request->cell_phone : null,
                    'home_phone' => isset($request->home_phone) ? $request->home_phone : null,
                    'fax' => isset($request->fax) ? $request->fax : null,
                    'email' => isset($request->email) ? $request->email : null,
                    'driver_license_no' => isset($request->driver_license_no) ? $request->driver_license_no : null,
                    'driver_license_state' => isset($request->driver_license_state) ? $request->driver_license_state : null,
                    'address' => isset($request->address) ? $request->address : null,
                    'city' => isset($request->city) ? $request->city : null,
                    'state' => isset($request->state) ? $request->state : null,
                    'zip' => isset($request->zip) ? $request->zip : null,
                    'move_in_date' => isset($request->move_in_date) ? date('m/d/Y', strtotime($request->move_in_date)) : null,
                    'move_in_address' => isset($request->move_in_address) ? $request->move_in_address : null,
                    'move_in_city' => isset($request->move_in_city) ? $request->move_in_city : null,
                    'move_in_state' => isset($request->move_in_state) ? $request->move_in_state : null,
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
                    'business_city' => isset($request->business_city) ? $request->business_city : null,
                    'business_state' => isset($request->business_state) ? $request->business_state : null,
                    'business_zip' => isset($request->business_zip) ? $request->business_zip : null,
                    'business_move_in_date' => isset($request->business_move_in_date) ? date('m/d/Y', strtotime($request->business_move_in_date)) : null,
                ]);
                $basicInfoId = $basicInfo->id;
                $user->legacy_key = true;
                $user->save();
            } else {
                $basicInfoId = $basicInfo->id;
                $basicInfo->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => isset($request->middle_name) ? $request->middle_name : null,
                    'ssn' => isset($request->ssn) ? $request->ssn : null,
                    'gender' => isset($request->gender) ? $request->gender : null,
                    'dob' => isset($request->dob) ? date('m/d/Y', strtotime($request->dob)) : null,
                    'marital_status' => isset($request->marital_status) ? $request->marital_status : null,
                    'cell_phone' => isset($request->cell_phone) ? $request->cell_phone : null,
                    'home_phone' => isset($request->home_phone) ? $request->home_phone : null,
                    'fax' => isset($request->fax) ? $request->fax : null,
                    'email' => isset($request->email) ? $request->email : null,
                    'driver_license_no' => isset($request->driver_license_no) ? $request->driver_license_no : null,
                    'driver_license_state' => isset($request->driver_license_state) ? $request->driver_license_state : null,
                    'address' => isset($request->address) ? $request->address : null,
                    'city' => isset($request->city) ? $request->city : null,
                    'state' => isset($request->state) ? $request->state : null,
                    'zip' => isset($request->zip) ? $request->zip : null,
                    'move_in_date' => isset($request->move_in_date) ? date('m/d/Y', strtotime($request->move_in_date)) : null,
                    'move_in_address' => isset($request->move_in_address) ? $request->move_in_address : null,
                    'move_in_city' => isset($request->move_in_city) ? $request->move_in_city : null,
                    'move_in_state' => isset($request->move_in_state) ? $request->move_in_state : null,
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
                    'business_city' => isset($request->business_city) ? $request->business_city : null,
                    'business_state' => isset($request->business_state) ? $request->business_state : null,
                    'business_zip' => isset($request->business_zip) ? $request->business_zip : null,
                    'business_move_in_date' => isset($request->business_move_in_date) ? date('m/d/Y', strtotime($request->business_move_in_date)) : null,
                ]);
            }

            $legalQuestions = [];
            if (isset($request->convicted_checkbox_1)) {
                $name = 'convicted_checkbox_1';
                $value = $request->convicted_checkbox_1;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1a)) {
                $name = 'convicted_checkbox_1a';
                $value = $request->convicted_checkbox_1a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1b)) {
                $name = 'convicted_checkbox_1b';
                $value = $request->convicted_checkbox_1b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1c)) {
                $name = 'convicted_checkbox_1c';
                $value = $request->convicted_checkbox_1c;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1c_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1d)) {
                $name = 'convicted_checkbox_1d';
                $value = $request->convicted_checkbox_1d;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1d_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1e)) {
                $name = 'convicted_checkbox_1e';
                $value = $request->convicted_checkbox_1e;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1e_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1f)) {
                $name = 'convicted_checkbox_1f';
                $value = $request->convicted_checkbox_1f;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1f_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1g)) {
                $name = 'convicted_checkbox_1g';
                $value = $request->convicted_checkbox_1g;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1g_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->convicted_checkbox_1h)) {
                $name = 'convicted_checkbox_1h';
                $value = $request->convicted_checkbox_1h;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->convicted_checkbox_1h_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_2)) {
                $name = 'lawsuit_checkbox_2';
                $value = $request->lawsuit_checkbox_2;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_2_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_2a)) {
                $name = 'lawsuit_checkbox_2a';
                $value = $request->lawsuit_checkbox_2a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_2a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_2b)) {
                $name = 'lawsuit_checkbox_2b';
                $value = $request->lawsuit_checkbox_2b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_2b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_2c)) {
                $name = 'lawsuit_checkbox_2c';
                $value = $request->lawsuit_checkbox_2c;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_2c_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_2d)) {
                $name = 'lawsuit_checkbox_2d';
                $value = $request->lawsuit_checkbox_2d;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_2d_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->alleged_engaged_checkbox_3)) {
                $name = 'alleged_engaged_checkbox_3';
                $value = $request->alleged_engaged_checkbox_3;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->alleged_engaged_checkbox_3_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->found_engaged_checkbox_4)) {
                $name = 'found_engaged_checkbox_4';
                $value = $request->found_engaged_checkbox_4;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->found_engaged_checkbox_4_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->terminate_contract_checkbox_5)) {
                $name = 'terminate_contract_checkbox_5';
                $value = $request->terminate_contract_checkbox_5;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->terminate_contract_checkbox_5_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->terminate_contract_checkbox_5a)) {
                $name = 'terminate_contract_checkbox_5a';
                $value = $request->terminate_contract_checkbox_5a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->terminate_contract_checkbox_5a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->terminate_contract_checkbox_5b)) {
                $name = 'terminate_contract_checkbox_5b';
                $value = $request->terminate_contract_checkbox_5b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->terminate_contract_checkbox_5b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->terminate_contract_checkbox_5c)) {
                $name = 'terminate_contract_checkbox_5c';
                $value = $request->terminate_contract_checkbox_5c;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->terminate_contract_checkbox_5c_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->cancel_insu_cause_checkbox_6)) {
                $name = 'cancel_insu_cause_checkbox_6';
                $value = $request->cancel_insu_cause_checkbox_6;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->cancel_insu_cause_checkbox_6_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->insurer_checkbox_7)) {
                $name = 'insurer_checkbox_7';
                $value = $request->insurer_checkbox_7;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->insurer_checkbox_7_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_8)) {
                $name = 'lawsuit_checkbox_8';
                $value = $request->lawsuit_checkbox_8;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_8_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_8a)) {
                $name = 'lawsuit_checkbox_8a';
                $value = $request->lawsuit_checkbox_8a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_8a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->lawsuit_checkbox_8b)) {
                $name = 'lawsuit_checkbox_8b';
                $value = $request->lawsuit_checkbox_8b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->lawsuit_checkbox_8b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->license_denied_checkbox_9)) {
                $name = 'license_denied_checkbox_9';
                $value = $request->license_denied_checkbox_9;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->license_denied_checkbox_9_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->regulatory_checkbox_10)) {
                $name = 'regulatory_checkbox_10';
                $value = $request->regulatory_checkbox_10;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->regulatory_checkbox_10_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->regulatory_revoked_checkbox_11)) {
                $name = 'regulatory_revoked_checkbox_11';
                $value = $request->regulatory_revoked_checkbox_11;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->regulatory_revoked_checkbox_11_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->regulatory_found_checkbox_12)) {
                $name = 'regulatory_found_checkbox_12';
                $value = $request->regulatory_found_checkbox_12;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->regulatory_found_checkbox_12_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->interr_licensing_checkbox_13)) {
                $name = 'interr_licensing_checkbox_13';
                $value = $request->interr_licensing_checkbox_13;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->interr_licensing_checkbox_13_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->self_regularity_checkbox_14)) {
                $name = 'self_regularity_checkbox_14';
                $value = $request->self_regularity_checkbox_14;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->self_regularity_checkbox_14_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->self_regularity_checkbox_14a)) {
                $name = 'self_regularity_checkbox_14a';
                $value = $request->self_regularity_checkbox_14a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->self_regularity_checkbox_14a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->self_regularity_checkbox_14b)) {
                $name = 'self_regularity_checkbox_14b';
                $value = $request->self_regularity_checkbox_14b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->self_regularity_checkbox_14b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->self_regularity_checkbox_14c)) {
                $name = 'self_regularity_checkbox_14c';
                $value = $request->self_regularity_checkbox_14c;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->self_regularity_checkbox_14c_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->bankruptcy_checkbox_15)) {
                $name = 'bankruptcy_checkbox_15';
                $value = $request->bankruptcy_checkbox_15;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->bankruptcy_checkbox_15_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->bankruptcy_checkbox_15a)) {
                $name = 'bankruptcy_checkbox_15a';
                $value = $request->bankruptcy_checkbox_15a;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->bankruptcy_checkbox_15a_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->bankruptcy_checkbox_15b)) {
                $name = 'bankruptcy_checkbox_15b';
                $value = $request->bankruptcy_checkbox_15b;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->bankruptcy_checkbox_15b_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->bankruptcy_checkbox_15c)) {
                $name = 'bankruptcy_checkbox_15c';
                $value = $request->bankruptcy_checkbox_15c;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->bankruptcy_checkbox_15c_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->liens_against_checkbox_16)) {
                $name = 'liens_against_checkbox_16';
                $value = $request->liens_against_checkbox_16;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->liens_against_checkbox_16_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->connected_checkbox_17)) {
                $name = 'connected_checkbox_17';
                $value = $request->connected_checkbox_17;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->connected_checkbox_17_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->aliases_checkbox_18)) {
                $name = 'aliases_checkbox_18';
                $value = $request->aliases_checkbox_18;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->aliases_checkbox_18_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($request->unresolved_matter_checkbox_19)) {
                $name = 'unresolved_matter_checkbox_19';
                $value = $request->unresolved_matter_checkbox_19;
                array_push($legalQuestions, [
                    'reg_info_id' => $basicInfoId,
                    'name' => $name,
                    'value' => $value,
                    'description' => $value == 'YES' ? $request->unresolved_matter_checkbox_19_text : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (count($legalQuestions)) {
                InternalAgentLegalQuestion::where('reg_info_id', $basicInfoId)->delete();
                DB::table('internal_agent_legal_questions')->insert($legalQuestions);
            }

            InternalAgentAdditionalInfo::updateOrCreate(['reg_info_id' => $basicInfoId], [
                'resident_country' => isset($request->resident_country) ? $request->resident_country : null,
                'resident_your_home' => isset($request->resident_your_home) ? $request->resident_your_home : null,
                'resident_city' => isset($request->resident_city) ? $request->resident_city : null,
                'resident_state' => isset($request->resident_state) ? $request->resident_state : null,
                'resident_maiden_name' => isset($request->resident_maiden_name) ? $request->resident_maiden_name : null,
            ]);

            $addresses = [];

            if (isset($request->history_address1)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfoId,
                    'address' => isset($request->history_address1['address']) ? $request->history_address1['address'] : null,
                    'city' => isset($request->history_address1['city']) ? $request->history_address1['city'] : null,
                    'state' => isset($request->history_address1['state']) ? $request->history_address1['state'] : null,
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
                    'city' => isset($request->history_address2['city']) ? $request->history_address2['city'] : null,
                    'state' => isset($request->history_address2['state']) ? $request->history_address2['state'] : null,
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
                    'city' => isset($request->history_address3['city']) ? $request->history_address3['city'] : null,
                    'state' => isset($request->history_address3['state']) ? $request->history_address3['state'] : null,
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
                    'city' => isset($request->history_address4['city']) ? $request->history_address4['city'] : null,
                    'state' => isset($request->history_address4['state']) ? $request->history_address4['state'] : null,
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
                    'city' => isset($request->history_address5['city']) ? $request->history_address5['city'] : null,
                    'state' => isset($request->history_address5['state']) ? $request->history_address5['state'] : null,
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
                    'city' => isset($request->history_address6['city']) ? $request->history_address6['city'] : null,
                    'state' => isset($request->history_address6['state']) ? $request->history_address6['state'] : null,
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
                    'city' => isset($request->history_address7['city']) ? $request->history_address7['city'] : null,
                    'state' => isset($request->history_address7['state']) ? $request->history_address7['state'] : null,
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

            if ($request->file('uploadAmlPdf') && $request->file('uploadAmlPdf')->isValid()) {
                $amlCoursePdf = InternalAgentAmlCourse::where('reg_info_id', $basicInfoId)->first();
                if ($amlCoursePdf) {
                    if (file_exists(asset('internal-agents/aml-course/' . $amlCoursePdf->name))) {
                        unlink(asset('internal-agents/aml-course/' . $amlCoursePdf->name));
                    }
                    $amlCoursePdf->delete();
                }
                $name = $request->file('uploadAmlPdf')->getClientOriginalName();
                $request->file('uploadAmlPdf')->move(public_path('internal-agents/aml-course'), $name);
                $path = asset('internal-agents/aml-course/' . $name);

                InternalAgentAmlCourse::updateOrCreate(['reg_info_id' => $basicInfoId], [
                    'name' => $name,
                    'aml_course' => $request->aml_course,
                    'url' => $path,
                ]);
            }

            if ($request->file('uploadOmmisionPdf') && $request->file('uploadOmmisionPdf')->isValid()) {
                $uploadOmmisionPdf = InternalAgentErrorAndEmission::where('reg_info_id', $basicInfoId)->first();
                if ($uploadOmmisionPdf) {
                    if (file_exists(asset('internal-agents/error-and-omission/' . $uploadOmmisionPdf->name))) {
                        unlink(asset('internal-agents/error-and-omission/' . $uploadOmmisionPdf->name));
                    }
                    $uploadOmmisionPdf->delete();
                }

                $name = $request->file('uploadOmmisionPdf')->getClientOriginalName();
                $request->file('uploadOmmisionPdf')->move(public_path('internal-agents/error-and-omission'), $name);
                $path = asset('internal-agents/error-and-omission/' . $name);

                InternalAgentErrorAndEmission::updateOrCreate(['reg_info_id' => $basicInfoId], [
                    'name' => $name,
                    'omissions_insurance' => $request->omissions_insurance,
                    'url' => $path,
                ]);
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
            return response()->json([
                'success' => true,
                'message' => 'Registration steps completed successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }

    public function pdf()
    {
        set_time_limit(0);

        $returnArr['contractData'] = User::where('id', 308)
            ->with('internalAgentContract.additionalInfo')
            ->with('internalAgentContract.addresses')
            ->with('internalAgentContract.amlCourse')
            ->with('internalAgentContract.bankingInfo')
            ->with('internalAgentContract.errorAndEmission')
            ->with('internalAgentContract.legalQuestion')
            ->with('internalAgentContract.residentLicense')->first();

        $pdf = PDF::loadView('PDF.agent-contract', $returnArr);
        return $pdf->stream();
        dd('ds');
        return $pdf->download('legal-question.pdf');

        //                $returnArr['questions'] = InternalAgentLegalQuestion::where('reg_info_id', $basicInfoId)->get(['name', 'value', 'description'])->toArray();
//                $pdf = PDF::loadView('PDF.legal-questions', $returnArr);
//                $pdfFileName = auth()->user()->email."_legal_questions.pdf";
//                $directory = public_path('internal-agents/legal-questions');
//                if(!File::exists($directory)) {
//                    File::makeDirectory($directory, $mode = 0755, true, true);
//                }
//                $pdf->save($directory . '/' . $pdfFileName);


//        dd('pdf');
//        return view('PDF.legal-questions');
    }
}
