<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentAdditionalInfo;
use App\Models\InternalAgentAddress;
use App\Models\InternalAgentBankingInfo;
use App\Models\InternalAgentLegalQuestion;
use App\Models\InternalAgentRegInfo;
use App\Models\InternalAgentResidentLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegistrationStepController extends Controller
{
    public function contractSteps()
    {
        return Inertia::render('InternalAgent/ContractSteps');
    }

    public function store(Request $request)
        {

        $validator = Validator::make($request->contactDetailData, [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $basicInfo = InternalAgentRegInfo::create([
                'user_id' => auth()->user()->id,
                'first_name' => $request->contactDetailData->first_name,
                'last_name' => $request->contactDetailData->last_name,
                'middle_name' => isset($request->contactDetailData->middle_name) ? $request->contactDetailData->middle_name : null,
                'ssn' => isset($request->contactDetailData->ssn) ? $request->contactDetailData->ssn : null,
                'gender' => isset($request->contactDetailData->gender) ? $request->contactDetailData->gender : null,
                'dob' => isset($request->contactDetailData->dob) ? $request->contactDetailData->dob : null,
                'martial_status' => isset($request->contactDetailData->martial_status) ? $request->contactDetailData->martial_status : null,
                'cell_phone' => isset($request->contactDetailData->cell_phone) ? $request->contactDetailData->cell_phone : null,
                'home_phone' => isset($request->contactDetailData->home_phone) ? $request->contactDetailData->home_phone : null,
                'fax' => isset($request->contactDetailData->fax) ? $request->contactDetailData->fax : null,
                'email' => isset($request->contactDetailData->email) ? $request->contactDetailData->email : null,
                'driver_license_no' => isset($request->contactDetailData->driver_license_no) ? $request->contactDetailData->driver_license_no : null,
                'driver_license_state' => isset($request->contactDetailData->driver_license_state) ? $request->contactDetailData->driver_license_state : null,
                'address' => isset($request->contactDetailData->address) ? $request->contactDetailData->address : null,
                'city_state' => isset($request->contactDetailData->city_state) ? $request->contactDetailData->city_state : null,
                'zip' => isset($request->contactDetailData->zip) ? $request->contactDetailData->zip : null,
                'move_in_date' => isset($request->contactDetailData->move_in_date) ? $request->contactDetailData->move_in_date : null,
                'move_in_address' => isset($request->contactDetailData->move_in_address) ? $request->contactDetailData->move_in_address : null,
                'move_in_city_state' => isset($request->contactDetailData->move_in_city_state) ? $request->contactDetailData->move_in_city_state : null,
                'move_in_zip' => isset($request->contactDetailData->move_in_zip) ? $request->contactDetailData->move_in_zip : null,
                'resident_insu_license_no' => isset($request->contactDetailData->resident_insu_license_no) ? $request->contactDetailData->resident_insu_license_no : null,
                'resident_insu_license_state' => isset($request->contactDetailData->resident_insu_license_state) ? $request->contactDetailData->resident_insu_license_state : null,
                'doing_business_as' => isset($request->contactDetailData->doing_business_as) ? $request->contactDetailData->doing_business_as : null,
                'business_name' => isset($request->contactDetailData->business_name) ? $request->contactDetailData->business_name : null,
                'business_tax_id' => isset($request->contactDetailData->business_tax_id) ? $request->contactDetailData->business_tax_id : null,
                'business_agent_name' => isset($request->contactDetailData->business_agent_name) ? $request->contactDetailData->business_agent_name : null,
                'business_agent_title' => isset($request->contactDetailData->business_agent_title) ? $request->contactDetailData->business_agent_title : null,
                'business_company_type' => isset($request->contactDetailData->business_company_type) ? $request->contactDetailData->business_company_type : null,
                'business_insu_license_no' => isset($request->contactDetailData->business_insu_license_no) ? $request->contactDetailData->business_insu_license_no : null,
                'business_office_fax' => isset($request->contactDetailData->business_office_fax) ? $request->contactDetailData->business_office_fax : null,
                'business_office_phone' => isset($request->contactDetailData->business_office_phone) ? $request->contactDetailData->business_office_phone : null,
                'business_email' => isset($request->contactDetailData->business_email) ? $request->contactDetailData->business_email : null,
                'business_website' => isset($request->contactDetailData->business_website) ? $request->contactDetailData->business_website : null,
                'business_address' => isset($request->contactDetailData->business_address) ? $request->contactDetailData->business_address : null,
                'business_city_state' => isset($request->contactDetailData->business_city_state) ? $request->contactDetailData->business_city_state : null,
                'business_zip' => isset($request->contactDetailData->business_zip) ? $request->contactDetailData->business_zip : null,
                'business_move_in_date' => isset($request->contactDetailData->business_move_in_date) ? $request->contactDetailData->business_move_in_date : null,
                'aml_course' => isset($request->aml_course) ? $request->aml_course : null,
                'omissions_insurance' => isset($request->omissions_insurance) ? $request->omissions_insurance : null,
            ]);

            $addresses = [];

            if (isset($request->AddressHistoryData->history_address1)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address1->address) ? $request->AddressHistoryData->history_address1->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address1->city) ? $request->AddressHistoryData->history_address1->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address1->zip_code) ? $request->AddressHistoryData->history_address1->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address1->move_in_date) ? $request->AddressHistoryData->history_address1->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address1->move_out_date) ? $request->AddressHistoryData->history_address1->move_out_date : null,
                ]);
            }

            if (isset($request->AddressHistoryData->history_address2)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address2->address) ? $request->AddressHistoryData->history_address2->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address2->city) ? $request->AddressHistoryData->history_address2->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address2->zip_code) ? $request->AddressHistoryData->history_address2->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address2->move_in_date) ? $request->AddressHistoryData->history_address2->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address2->move_out_date) ? $request->AddressHistoryData->history_address2->move_out_date : null,
                ]);
            }

            if (isset($request->AddressHistoryData->history_address3)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address3->address) ? $request->AddressHistoryData->history_address3->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address3->city) ? $request->AddressHistoryData->history_address3->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address3->zip_code) ? $request->AddressHistoryData->history_address3->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address3->move_in_date) ? $request->AddressHistoryData->history_address3->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address3->move_out_date) ? $request->AddressHistoryData->history_address3->move_out_date : null,
                ]);
            }
            if (isset($request->AddressHistoryData->history_address4)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address4->address) ? $request->AddressHistoryData->history_address4->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address4->city) ? $request->AddressHistoryData->history_address4->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address4->zip_code) ? $request->AddressHistoryData->history_address4->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address4->move_in_date) ? $request->AddressHistoryData->history_address4->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address4->move_out_date) ? $request->AddressHistoryData->history_address4->move_out_date : null,
                ]);
            }
            if (isset($request->AddressHistoryData->history_address5)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address5->address) ? $request->AddressHistoryData->history_address5->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address5->city) ? $request->AddressHistoryData->history_address5->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address5->zip_code) ? $request->AddressHistoryData->history_address5->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address5->move_in_date) ? $request->AddressHistoryData->history_address5->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address5->move_out_date) ? $request->AddressHistoryData->history_address5->move_out_date : null,
                ]);
            }
            if (isset($request->AddressHistoryData->history_address6)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address6->address) ? $request->AddressHistoryData->history_address6->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address6->city) ? $request->AddressHistoryData->history_address6->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address6->zip_code) ? $request->AddressHistoryData->history_address6->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address6->move_in_date) ? $request->AddressHistoryData->history_address6->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address6->move_out_date) ? $request->AddressHistoryData->history_address6->move_out_date : null,
                ]);
            }
            if (isset($request->AddressHistoryData->history_address7)) {
                array_push($addresses, [
                    'reg_info_id' => $basicInfo->id,
                    'address' => isset($request->AddressHistoryData->history_address7->address) ? $request->AddressHistoryData->history_address7->address : null,
                    'city_state' => isset($request->AddressHistoryData->history_address7->city) ? $request->AddressHistoryData->history_address7->city : null,
                    'zip' => isset($request->AddressHistoryData->history_address7->zip_code) ? $request->AddressHistoryData->history_address7->zip_code : null,
                    'move_in_date' => isset($request->AddressHistoryData->history_address7->move_in_date) ? $request->AddressHistoryData->history_address7->move_in_date : null,
                    'move_out_date' => isset($request->AddressHistoryData->history_address7->move_out_date) ? $request->AddressHistoryData->history_address7->move_out_date : null,
                ]);
            }


            if (count($addresses)) {
                InternalAgentAddress::create($addresses);
            }

            InternalAgentLegalQuestion::create([
                'reg_info_id' => $basicInfo->id,
                'convicted_checkbox_1' => isset($request->legalFormData->convicted_checkbox_1) ? $request->legalFormData->convicted_checkbox_1 : null,
                'convicted_checkbox_1a' => isset($request->legalFormData->convicted_checkbox_1a) ? $request->legalFormData->convicted_checkbox_1a : null,
                'convicted_checkbox_1b' => isset($request->legalFormData->convicted_checkbox_1b) ? $request->legalFormData->convicted_checkbox_1b : null,
                'convicted_checkbox_1c' => isset($request->legalFormData->convicted_checkbox_1c) ? $request->legalFormData->convicted_checkbox_1c : null,
                'convicted_checkbox_1d' => isset($request->legalFormData->convicted_checkbox_1d) ? $request->legalFormData->convicted_checkbox_1d : null,
                'convicted_checkbox_1e' => isset($request->legalFormData->convicted_checkbox_1e) ? $request->legalFormData->convicted_checkbox_1e : null,
                'convicted_checkbox_1f' => isset($request->legalFormData->convicted_checkbox_1f) ? $request->legalFormData->convicted_checkbox_1f : null,
                'convicted_checkbox_1g' => isset($request->legalFormData->convicted_checkbox_1g) ? $request->legalFormData->convicted_checkbox_1g : null,
                'convicted_checkbox_1h' => isset($request->legalFormData->convicted_checkbox_1h) ? $request->legalFormData->convicted_checkbox_1h : null,
                'lawsuit_checkbox_2' => isset($request->legalFormData->lawsuit_checkbox_2) ? $request->legalFormData->lawsuit_checkbox_2 : null,
                'lawsuit_checkbox_2a' => isset($request->legalFormData->lawsuit_checkbox_2a) ? $request->legalFormData->lawsuit_checkbox_2a : null,
                'lawsuit_checkbox_2b' => isset($request->legalFormData->lawsuit_checkbox_2b) ? $request->legalFormData->lawsuit_checkbox_2b : null,
                'lawsuit_checkbox_2c' => isset($request->legalFormData->lawsuit_checkbox_2c) ? $request->legalFormData->lawsuit_checkbox_2c : null,
                'lawsuit_checkbox_2d' => isset($request->legalFormData->lawsuit_checkbox_2d) ? $request->legalFormData->lawsuit_checkbox_2d : null,
                'alleged_engaged_checkbox_3' => isset($request->legalFormData->alleged_engaged_checkbox_3) ? $request->legalFormData->alleged_engaged_checkbox_3 : null,
                'found_engaged_checkbox_4' => isset($request->legalFormData->found_engaged_checkbox_4) ? $request->legalFormData->found_engaged_checkbox_4 : null,
                'terminate_contract_checkbox_5' => isset($request->legalFormData->terminate_contract_checkbox_5) ? $request->legalFormData->terminate_contract_checkbox_5 : null,
                'terminate_contract_checkbox_5a' => isset($request->legalFormData->terminate_contract_checkbox_5a) ? $request->legalFormData->terminate_contract_checkbox_5a : null,
                'terminate_contract_checkbox_5b' => isset($request->legalFormData->terminate_contract_checkbox_5b) ? $request->legalFormData->terminate_contract_checkbox_5b : null,
                'terminate_contract_checkbox_5c' => isset($request->legalFormData->terminate_contract_checkbox_5c) ? $request->legalFormData->terminate_contract_checkbox_5c : null,
                'cancel_insu_cause_checkbox_6' => isset($request->legalFormData->cancel_insu_cause_checkbox_6) ? $request->legalFormData->cancel_insu_cause_checkbox_6 : null,
                'insurer_checkbox_7' => isset($request->legalFormData->insurer_checkbox_7) ? $request->legalFormData->insurer_checkbox_7 : null,
                'lawsuit_checkbox_8' => isset($request->legalFormData->lawsuit_checkbox_8) ? $request->legalFormData->lawsuit_checkbox_8 : null,
                'lawsuit_checkbox_8a' => isset($request->legalFormData->lawsuit_checkbox_8a) ? $request->legalFormData->lawsuit_checkbox_8a : null,
                'lawsuit_checkbox_8b' => isset($request->legalFormData->lawsuit_checkbox_8b) ? $request->legalFormData->lawsuit_checkbox_8b : null,
                'license_denied_checkbox_9' => isset($request->legalFormData->license_denied_checkbox_9) ? $request->legalFormData->license_denied_checkbox_9 : null,
                'regulatory_checkbox_10' => isset($request->legalFormData->regulatory_checkbox_10) ? $request->legalFormData->regulatory_checkbox_10 : null,
                'regulatory_revoked_checkbox_11' => isset($request->legalFormData->regulatory_revoked_checkbox_11) ? $request->legalFormData->regulatory_revoked_checkbox_11 : null,
                'regulatory_found_checkbox_12' => isset($request->legalFormData->regulatory_found_checkbox_12) ? $request->legalFormData->regulatory_found_checkbox_12 : null,
                'interr_licensing_checkbox_13' => isset($request->legalFormData->interr_licensing_checkbox_13) ? $request->legalFormData->interr_licensing_checkbox_13 : null,
                'self_regularity_checkbox_14' => isset($request->legalFormData->self_regularity_checkbox_14) ? $request->legalFormData->self_regularity_checkbox_14 : null,
                'self_regularity_checkbox_14a' => isset($request->legalFormData->self_regularity_checkbox_14a) ? $request->legalFormData->self_regularity_checkbox_14a : null,
                'self_regularity_checkbox_14b' => isset($request->legalFormData->self_regularity_checkbox_14b) ? $request->legalFormData->self_regularity_checkbox_14b : null,
                'self_regularity_checkbox_14c' => isset($request->legalFormData->self_regularity_checkbox_14c) ? $request->legalFormData->self_regularity_checkbox_14c : null,
                'bankruptcy_checkbox_15' => isset($request->legalFormData->bankruptcy_checkbox_15) ? $request->legalFormData->bankruptcy_checkbox_15 : null,
                'bankruptcy_checkbox_15a' => isset($request->legalFormData->bankruptcy_checkbox_15a) ? $request->legalFormData->bankruptcy_checkbox_15a : null,
                'bankruptcy_checkbox_15b' => isset($request->legalFormData->bankruptcy_checkbox_15b) ? $request->legalFormData->bankruptcy_checkbox_15b : null,
                'bankruptcy_checkbox_15c' => isset($request->legalFormData->bankruptcy_checkbox_15c) ? $request->legalFormData->bankruptcy_checkbox_15c : null,
                'liens_against_checkbox_16' => isset($request->legalFormData->liens_against_checkbox_16) ? $request->legalFormData->liens_against_checkbox_16 : null,
                'connected_checkbox_17' => isset($request->legalFormData->connected_checkbox_17) ? $request->legalFormData->connected_checkbox_17 : null,
                'aliases_checkbox_18' => isset($request->legalFormData->aliases_checkbox_18) ? $request->legalFormData->aliases_checkbox_18 : null,
                'unresolved_matter_checkbox_19' => isset($request->legalFormData->unresolved_matter_checkbox_19) ? $request->legalFormData->unresolved_matter_checkbox_19 : null,
            ]);

            InternalAgentAdditionalInfo::create([
                'reg_info_id' => $basicInfo->id,
                'resident_country' => isset($request->additionalInfo->resident_country) ? $request->additionalInfo->resident_country : null,
                'resident_your_home' => isset($request->additionalInfo->resident_your_home) ? $request->additionalInfo->resident_your_home : null,
                'resident_city_state' => isset($request->additionalInfo->resident_city_state) ? $request->additionalInfo->resident_city_state : null,
                'resident_maiden_name' => isset($request->additionalInfo->resident_maiden_name) ? $request->additionalInfo->resident_maiden_name : null,
                'aml_provider' => isset($request->additionalInfo->aml_provider) ? $request->additionalInfo->aml_provider : null,
                'training_completion_date' => isset($request->additionalInfo->training_completion_date) ? $request->additionalInfo->training_completion_date : null,
                'limra_password' => isset($request->additionalInfo->limra_password) ? $request->additionalInfo->limra_password : null,
            ]);

            if ($request->file('residentLicensePdf') && $request->file('residentLicensePdf')->isValid()) {
                $path = $request->file('residentLicensePdf')->store('internal-agents/resident-license-pdf', 'local');
                $name = $request->file('residentLicensePdf')->getClientOriginalName();

                InternalAgentResidentLicense::create([
                    'reg_info_id' => $basicInfo->id,
                    'name' => $name,
                    'url' => $path,
                ]);
            }

            if ($request->file('bankingInfoPdf') && $request->file('bankingInfoPdf')->isValid()) {
                $path = $request->file('bankingInfoPdf')->store('internal-agents/banking-info', 'local');
                $name = $request->file('bankingInfoPdf')->getClientOriginalName();

                InternalAgentBankingInfo::create([
                    'reg_info_id' => $basicInfo->id,
                    'name' => $name,
                    'url' => $path,
                ]);
            }

            DB::commit();

            return Inertia::render('InternalAgent/ContractSteps')
                ->with('success', 'Internal agent registration completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('error',$e);
        }


    }

}
