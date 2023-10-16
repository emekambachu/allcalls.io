<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\DocuSignTracker;
use App\Models\InternalAgentAdditionalInfo;
use App\Models\InternalAgentAddress;
use App\Models\InternalAgentAmlCourse;
use App\Models\InternalAgentBankingInfo;
use App\Models\InternalAgentContractSigned;
use App\Models\InternalAgentErrorAndEmission;
use App\Models\InternalAgentLegalQuestion;
use App\Models\InternalAgentQuestionSigned;
use App\Models\InternalAgentRegInfo;
use App\Models\InternalAgentResidentLicense;
use App\Models\State;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Clegginabox\PDFMerger\PDFMerger;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Model\CompositeTemplate;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\InlineTemplate;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\RecipientViewRequest;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegistrationStepController extends Controller
{
    private $accountId;
    private $baseUrl;


    public function __construct()
    {
        $this->accountId = env('DOCUSIGN_API_ACCOUNT_ID');
        $this->baseUrl = env('DOCUSIGN_ACCOUNT_BASE_URI_API');
    }


    public function contractSteps()
    {
        if (isset($_GET['event']) && $_GET['event'] == 'signing_complete') {
            $user = auth()->user();
            if (isset($_GET['position']) && $_GET['position'] == 'contract') {
                $envelopeId =  session()->get('envelope_id');
                $documentId =  session()->get('document_id');
                $url = "$this->baseUrl/v2.1/accounts/$this->accountId/envelopes/$envelopeId/documents/$documentId";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . session()->get('docusign_auth_code'),
                    'Content-Description' => 'File Transfer',
                    'Content-Type' => 'application/pdf',
                ])->get($url);

                //deleted PDF without sign for Accompanying Sign
                $pdfFileName = $user->id . '_contract.pdf';
                //deleted PDF without sign for Accompanying Sign
                if (file_exists(public_path() . '/internal-agents/contract/' . $pdfFileName)) {
                    unlink(public_path() . '/internal-agents/contract/' . $pdfFileName);
                }
                //End deleted PDF without sign for Accompanying Sign

                //store PDF signed for Accompanying Sign
                $contractedPdf = $user->id . '-contract.pdf';
                $pdfPath = public_path('internal-agents/contract/' . $contractedPdf);
                file_put_contents($pdfPath, $response->body());
                //End store signed PDF for Accompanying Sign

                //Track Signer
                DocuSignTracker::updateOrCreate(
                    ['user_id' => $user->id, 'sign_type' => 'contract'], // conditions
                    [
                        'envlope_id' => session()->get('envelope_id'),
                        'document_id' => session()->get('document_id'),
                    ]
                );
                //Track Signer

                InternalAgentContractSigned::updateOrCreate(['reg_info_id' => $user->internalAgentContract->id], [
                    'name' => $contractedPdf,
                    'sign_url' => asset('internal-agents/contract/' . $contractedPdf),
                ]);
                $user->legacy_key = true;
                $user->contract_step = 10;
                $user->save();
            }


            // if (isset($_GET['position']) && $_GET['position'] == 'accompanying_sign') {
            //     $envelopeId =  session()->get('envelope_id');
            //     $documentId =  session()->get('document_id');

            //     $url = "$this->baseUrl/v2.1/accounts/$this->accountId/envelopes/$envelopeId/documents/$documentId";

            //     $response = Http::withHeaders([
            //         'Authorization' => 'Bearer ' . session()->get('docusign_auth_code'),
            //         'Content-Description' => 'File Transfer',
            //         'Content-Type' => 'application/pdf',
            //     ])->get($url);

            //     //deleted PDF without sign for Accompanying Sign
            //     $accompanyingSign = 'accompanying-sign-' . $user->id . '.pdf';
            //     if (file_exists(asset('internal-agents/contract/' . $accompanyingSign))) {
            //         unlink(asset('internal-agents/contract/' . $accompanyingSign));
            //     }
            //     //End deleted PDF without sign for Accompanying Sign

            //     //store PDF signed for Accompanying Sign
            //     $pdfFileName = $user->id . '_accompanying_sign' . '.pdf';
            //     $pdfPath = public_path('internal-agents/contract/' . $pdfFileName);
            //     file_put_contents($pdfPath, $response->body());
            //     //End store signed PDF for Accompanying Sign

            //     //Track Signer
            //     DocuSignTracker::updateOrCreate(
            //         ['user_id' => $user->id, 'sign_type' => 'accompanying_sign'], // conditions
            //         [
            //             'envlope_id' => session()->get('envelope_id'),
            //             'document_id' => session()->get('document_id'),
            //         ]
            //     );
            //     //Track Signer

            //     InternalAgentQuestionSigned::updateOrCreate(['reg_info_id' => $user->internalAgentContract->id], [
            //         'name' => $pdfFileName,
            //         'sign_url' => asset('internal-agents/contract/' . $pdfFileName),
            //     ]);

            //     $user->contract_step = 6;
            //     $user->save();
            // }

            // if (isset($_GET['position']) && $_GET['position'] == 'signature_authorization') {
            //     $envelopeId =  session()->get('envelope_id');
            //     $documentId =  session()->get('document_id');

            //     $url = "$this->baseUrl/v2.1/accounts/$this->accountId/envelopes/$envelopeId/documents/$documentId";

            //     $response = Http::withHeaders([
            //         'Authorization' => 'Bearer ' . session()->get('docusign_auth_code'),
            //         'Content-Description' => 'File Transfer',
            //         'Content-Type' => 'application/pdf',
            //     ])->get($url);

            //     //deleted PDF without sign for Signature Authorization
            //     $firstStepPdf = 'first-step-sign-' . $user->id . '.pdf';
            //     if (file_exists(asset('internal-agents/contract/' . $firstStepPdf))) {
            //         unlink(asset('internal-agents/contract/' . $firstStepPdf));
            //     }
            //     //End deleted PDF without sign for Signature Authorization

            //     //store PDF signed  Signature Authorization
            //     $pdfFileName = $user->id . '_signature_authorization' . '.pdf';
            //     $pdfPath = public_path('internal-agents/contract/' . $pdfFileName);
            //     file_put_contents($pdfPath, $response->body());
            //     //End store signed PDF for Signature Authorization

            //     //Track Signer
            //     DocuSignTracker::updateOrCreate(
            //         ['user_id' => $user->id, 'sign_type' => 'signature_authorization'], // conditions
            //         [
            //             'envlope_id' => session()->get('envelope_id'),
            //             'document_id' => session()->get('document_id'),
            //         ]
            //     );
            //     //Track Signer

            //     InternalAgentContractSigned::updateOrCreate(['reg_info_id' => $user->internalAgentContract->id], [
            //         'name' => $pdfFileName,
            //         'sign_url' => asset('internal-agents/contract/' . $pdfFileName),
            //     ]);

            //     $user->contract_step = 11;
            //     $user->save();
            // }

            // if (isset($_GET['position']) && $_GET['position'] == 'agency_authorization') {
            //     dd('agency authorization');

            //     $user->contract_step = 11;
            //     $user->legacy_key = true;
            //     $user->save();
            // }
        }

        if (auth()->user()->legacy_key) {
            return redirect()->route('dashboard');
        }

        $user = User::where('id', auth()->user()->id)
            ->with('internalAgentContract.getState')
            ->with('internalAgentContract.getDriverLicenseState')
            ->with('internalAgentContract.getMoveInState')
            ->with('internalAgentContract.getResidentInsLicenseState')
            ->with('internalAgentContract.getBusinessState')
            ->with('internalAgentContract.additionalInfo.getState')
            ->with('internalAgentContract.addresses.getState')
            ->with('internalAgentContract.legalQuestion')
            ->with('internalAgentContract.amlCourse')
            ->with('internalAgentContract.bankingInfo')
            ->with('internalAgentContract.errorAndEmission')
            ->with('internalAgentContract.residentLicense')
            ->with('internalAgentContract.getQuestionSign')
            ->with('internalAgentContract.getContractSign')->first();
        $states = State::all();

        return Inertia::render('InternalAgent/ContractSteps', [
            'states' => $states,
            'userData' => $user,
            'docuSignAuthCode' => session()->get('docusign_auth_code'),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $basicInfo = InternalAgentRegInfo::where('user_id', $user->id)->first();

        if ($request->step == 1) {
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
                'move_in_address' => 'nullable',
                'move_in_city' => 'nullable',
                'move_in_state' => 'nullable',
                'move_in_zip' => 'nullable',
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

            DB::beginTransaction();
            try {
                if (!$basicInfo) {
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
                    $user->contract_step = 2;
                    $user->save();
                } else {
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
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 2) {
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

            DB::beginTransaction();
            try {
                $legalQuestions = [];
                if (isset($request->convicted_checkbox_1)) {
                    $name = 'convicted_checkbox_1';
                    $value = $request->convicted_checkbox_1;
                    array_push($legalQuestions, [
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
                        'name' => $name,
                        'value' => $value,
                        'description' => $value == 'YES' ? $request->insurer_checkbox_7_text : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if (count($legalQuestions)) {
                    $selectedItem = [
                        'convicted_checkbox_1',
                        'convicted_checkbox_1a',
                        'convicted_checkbox_1b',
                        'convicted_checkbox_1c',
                        'convicted_checkbox_1d',
                        'convicted_checkbox_1e',
                        'convicted_checkbox_1f',
                        'convicted_checkbox_1g',
                        'convicted_checkbox_1h',
                        'lawsuit_checkbox_2',
                        'lawsuit_checkbox_2a',
                        'lawsuit_checkbox_2b',
                        'lawsuit_checkbox_2c',
                        'lawsuit_checkbox_2d',
                        'alleged_engaged_checkbox_3',
                        'found_engaged_checkbox_4',
                        'terminate_contract_checkbox_5',
                        'terminate_contract_checkbox_5a',
                        'terminate_contract_checkbox_5b',
                        'terminate_contract_checkbox_5c',
                        'cancel_insu_cause_checkbox_6',
                        'insurer_checkbox_7',
                    ];
                    InternalAgentLegalQuestion::where('reg_info_id', $basicInfo->id)
                        ->whereIn('name', $selectedItem)->delete();
                    DB::table('internal_agent_legal_questions')->insert($legalQuestions);
                    $user->contract_step = 3;
                    $user->save();
                }
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 3) {
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
                'contract_commission_checkbox_20' => 'required',
                'contract_commission_checkbox_20_text' => 'nullable|required_if:contract_commission_checkbox_20,=,YES|array',

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
                'contract_commission_checkbox_20.required' => 'This field is required.',
                'contract_commission_checkbox_20_text.required_if' => 'This field is required.',
            ]);
            if ($step1SubStep3Validation->fails()) {
                return response()->json([
                    'success' => false,
                    'step' => 3,
                    'errors' => $step1SubStep3Validation->errors(),
                ], 400);
            }

            DB::beginTransaction();
            try {
                $legalQuestions = [];
                if (isset($request->lawsuit_checkbox_8)) {
                    $name = 'lawsuit_checkbox_8';
                    $value = $request->lawsuit_checkbox_8;
                    array_push($legalQuestions, [
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
                        'name' => $name,
                        'value' => $value,
                        'description' => $value == 'YES' ? $request->unresolved_matter_checkbox_19_text : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if (isset($request->contract_commission_checkbox_20)) {
                    $name = 'contract_commission_checkbox_20';
                    $value = $request->contract_commission_checkbox_20;
                    array_push($legalQuestions, [
                        'reg_info_id' => $basicInfo->id,
                        'name' => $name,
                        'value' => $value,
                        'description' => $value == 'YES' ? implode(',', $request->contract_commission_checkbox_20_text) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if (count($legalQuestions)) {
                    $selectedItem = [
                        'lawsuit_checkbox_8',
                        'lawsuit_checkbox_8a',
                        'lawsuit_checkbox_8b',
                        'license_denied_checkbox_9',
                        'regulatory_checkbox_10',
                        'regulatory_revoked_checkbox_11',
                        'regulatory_found_checkbox_12',
                        'interr_licensing_checkbox_13',
                        'self_regularity_checkbox_14',
                        'self_regularity_checkbox_14a',
                        'self_regularity_checkbox_14b',
                        'self_regularity_checkbox_14c',
                        'bankruptcy_checkbox_15',
                        'bankruptcy_checkbox_15a',
                        'bankruptcy_checkbox_15b',
                        'bankruptcy_checkbox_15c',
                        'liens_against_checkbox_16',
                        'connected_checkbox_17',
                        'aliases_checkbox_18',
                        'unresolved_matter_checkbox_19',
                    ];
                    InternalAgentLegalQuestion::where('reg_info_id', $basicInfo->id)
                        ->whereIn('name', $selectedItem)->delete();
                    DB::table('internal_agent_legal_questions')->insert($legalQuestions);
                    $user->contract_step = 4;
                    $user->save();
                }
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 4) {
            DB::beginTransaction();
            try {
                $addresses = [];
                if (isset($request->history_address1)) {
                    array_push($addresses, [
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                        'reg_info_id' => $basicInfo->id,
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
                    InternalAgentAddress::where('reg_info_id', $basicInfo->id)->delete();
                    DB::table('internal_agent_addresses')->insert($addresses);
                }
                $user->contract_step = 5;
                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'route' => route('internal.agent.connect.docusign'),
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 5) {
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
                    'step' => 5,
                    'errors' => $step1SubStep4Validation->errors(),
                ], 400);
            }
            DB::beginTransaction();
            try {
                InternalAgentAdditionalInfo::updateOrCreate(['reg_info_id' => $basicInfo->id], [
                    'resident_country' => isset($request->resident_country) ? $request->resident_country : null,
                    'resident_your_home' => isset($request->resident_your_home) ? $request->resident_your_home : null,
                    'resident_city' => isset($request->resident_city) ? $request->resident_city : null,
                    'resident_state' => isset($request->resident_state) ? $request->resident_state : null,
                    'resident_maiden_name' => isset($request->resident_maiden_name) ? $request->resident_maiden_name : null,
                ]);
                $user->contract_step = 6;
                $user->save();
                DB::commit();

                return response()->json([
                    'success' => true,
                ], 200);

                //Accompanying Signature
                // $returnArr['contractData'] = User::where('id', $user->id)
                //     ->with('internalAgentContract.getState')
                //     ->with('internalAgentContract.getDriverLicenseState')
                //     ->with('internalAgentContract.getMoveInState')
                //     ->with('internalAgentContract.getResidentInsLicenseState')
                //     ->with('internalAgentContract.getBusinessState')
                //     ->with('internalAgentContract.additionalInfo.getState')
                //     ->with('internalAgentContract.addresses.getState')
                //     ->with('internalAgentContract.legalQuestion')->first();

                // $pdf = PDF::loadView('pdf.internal-agent-contract.agent-contract', $returnArr);
                // $directory = public_path('internal-agents/contract/');
                // if (!file_exists($directory)) {
                //     mkdir($directory, 0777, true);
                // }
                // $accompanyingSign = 'accompanying-sign-' . $user->id . '.pdf';
                // if (file_exists(asset('internal-agents/contract/' . $accompanyingSign))) {
                //     unlink(asset('internal-agents/contract/' . $accompanyingSign));
                // }
                // $pdf->save($directory . $accompanyingSign);

                //Accompanying Signature

                // return response()->json([
                //     'success' => true,
                //     'key' => 'accompanying_sign',
                //     'message' => 'Document is prepared and ready to sign.',
                //     'step' => 5,
                // ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 6) {
            DB::beginTransaction();
            try {
                $amlCoursePdf = InternalAgentAmlCourse::where('reg_info_id', $basicInfo->id)->first();

                if (!$amlCoursePdf) {
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
                }

                if ($request->file('uploadAmlPdf') && $request->file('uploadAmlPdf')->isValid()) {
                    if ($amlCoursePdf) {
                        if (file_exists(asset('internal-agents/aml-course/' . $amlCoursePdf->name))) {
                            unlink(asset('internal-agents/aml-course/' . $amlCoursePdf->name));
                        }
                        $amlCoursePdf->delete();
                    }
                    $name = $request->file('uploadAmlPdf')->getClientOriginalName();
                    $request->file('uploadAmlPdf')->move(public_path('internal-agents/aml-course'), $user->id . $name);
                    $path = asset('internal-agents/aml-course/' . $user->id . $name);

                    InternalAgentAmlCourse::updateOrCreate(['reg_info_id' => $basicInfo->id], [
                        'name' => $user->id . $name,
                        'aml_course' => $request->aml_course,
                        'url' => $path,
                    ]);
                }
                $user->contract_step = 7;
                $user->save();

                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 7) {
            DB::beginTransaction();
            try {
                $uploadOmmisionPdf = InternalAgentErrorAndEmission::where('reg_info_id', $basicInfo->id)->first();
                if (!$uploadOmmisionPdf) {
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
                }

                if ($request->file('uploadOmmisionPdf') && $request->file('uploadOmmisionPdf')->isValid()) {
                    if ($uploadOmmisionPdf) {
                        if (file_exists(asset('internal-agents/error-and-omission/' . $uploadOmmisionPdf->name))) {
                            unlink(asset('internal-agents/error-and-omission/' . $uploadOmmisionPdf->name));
                        }
                        $uploadOmmisionPdf->delete();
                    }

                    $name = $request->file('uploadOmmisionPdf')->getClientOriginalName();
                    $request->file('uploadOmmisionPdf')->move(public_path('internal-agents/error-and-omission'), $user->id . $name);
                    $path = asset('internal-agents/error-and-omission/' . $user->id . $name);

                    InternalAgentErrorAndEmission::updateOrCreate(['reg_info_id' => $basicInfo->id], [
                        'name' => $user->id . $name,
                        'omissions_insurance' => $request->omissions_insurance,
                        'url' => $path,
                    ]);
                }
                $user->contract_step = 8;
                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 8) {
            DB::beginTransaction();
            try {
                $residentPDf = InternalAgentResidentLicense::where('reg_info_id', $basicInfo->id)->first();
                if (!$residentPDf) {
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
                }
                if ($request->file('residentLicensePdf') && $request->file('residentLicensePdf')->isValid()) {
                    if ($residentPDf) {
                        if (file_exists(asset('internal-agents/resident-license-pdf/' . $residentPDf->name))) {
                            unlink(asset('internal-agents/resident-license-pdf/' . $residentPDf->name));
                        }
                        $residentPDf->delete();
                    }
                    $name = $request->file('residentLicensePdf')->getClientOriginalName();
                    $request->file('residentLicensePdf')->move(public_path('internal-agents/resident-license-pdf'), $user->id . $name);
                    $path = asset('internal-agents/resident-license-pdf/' . $user->id . $name);
                    InternalAgentResidentLicense::updateOrCreate(['reg_info_id' => $basicInfo->id], [
                        'name' => $user->id . $name,
                        'url' => $path,
                    ]);
                }
                $user->contract_step = 9;
                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 9) {
            DB::beginTransaction();
            try {
                $bankingInfoPdf = InternalAgentBankingInfo::where('reg_info_id', $basicInfo->id)->first();
                if (!$bankingInfoPdf) {
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
                }
                if ($request->file('bankingInfoPdf') && $request->file('bankingInfoPdf')->isValid()) {
                    if ($bankingInfoPdf) {
                        if (file_exists(asset('internal-agents/banking-info/' . $bankingInfoPdf->name))) {
                            unlink(asset('internal-agents/banking-info/' . $bankingInfoPdf->name));
                        }
                        $bankingInfoPdf->delete();
                    }
                    $name = $request->file('bankingInfoPdf')->getClientOriginalName();
                    $request->file('bankingInfoPdf')->move(public_path('internal-agents/banking-info'), $user->id . $name);
                    $path = asset('internal-agents/banking-info/' . $user->id . $name);

                    InternalAgentBankingInfo::updateOrCreate(['reg_info_id' => $basicInfo->id], [
                        'name' => $user->id . $name,
                        'url' => $path,
                    ]);
                }

                //Generate DocuSign Code
                // $apiClient = new ApiClient();
                // $apiClient->getOAuth()->setOAuthBasePath("account-d.docusign.com");
                // $docuSignAuthCode = $this->getToken($apiClient);
                // $request->session()->put('docusign_auth_code', $docuSignAuthCode);
                //End Generate DocuSign Code

                $user->contract_step = 10;
                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    // 'docuSignAuthCode' => session()->get('docusign_auth_code'),
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }

        if ($request->step == 10) {
            try {
                //Signature Authorization
                // $returnArr['contractData'] = User::where('id', $user->id)
                //     ->with('internalAgentContract.amlCourse')
                //     ->with('internalAgentContract.bankingInfo')
                //     ->with('internalAgentContract.errorAndEmission')
                //     ->with('internalAgentContract.residentLicense')->first();

                //Contract Signature
                // $pdf = PDF::loadView('pdf.internal-agent-contract.signature-authorization', $returnArr);
                // $directory = public_path('internal-agents/contract/');
                // if (!file_exists($directory)) {
                //     mkdir($directory, 0777, true);
                // }
                // $signatureAuthorization = $user->id . '-signature-authorization.pdf';
                // $pdf->save($directory . $signatureAuthorization);
                //Contract Signature End

                // $pdfMerger = new PDFMerger;
                //signed Accompanying PDF
                // $accompnayingPDF = $user->id . '_accompanying_sign' . '.pdf';
                // $pdfMerger->addPDF(public_path() . '/internal-agents/contract/' . $accompnayingPDF, 'all');
                //end signed Accompanying PDF

                // $pdfMerger->addPDF(public_path() . '/internal-agents/aml-course/' . $returnArr['contractData']->internalAgentContract->amlCourse->name, 'all');
                // $pdfMerger->addPDF(public_path() . '/internal-agents/error-and-omission/' . $returnArr['contractData']->internalAgentContract->errorAndEmission->name, 'all');
                // $pdfMerger->addPDF(public_path() . '/internal-agents/resident-license-pdf/' . $returnArr['contractData']->internalAgentContract->residentLicense->name, 'all');
                // $pdfMerger->addPDF(public_path() . '/internal-agents/banking-info/' . $returnArr['contractData']->internalAgentContract->bankingInfo->name, 'all');
                // $pdfMerger->addPDF(public_path() . '/internal-agents/contract/' . $signatureAuthorization, 'all');
                // $storeAsPath = 'internal-agents/contract/' . $signatureAuthorization;
                // $pdfMerger->merge('file', $storeAsPath, 'P');

                //deleted PDF without sign for Signature Authorization
                // if (file_exists(asset('internal-agents/contract/' . $signatureAuthorization))) {
                //     unlink(asset('internal-agents/contract/' . $signatureAuthorization));
                // }
                //End deleted PDF without sign for Signature Authorization
                //Signature Authorization


                //Start Contract PDF
                $returnArr['contractData'] = User::where('id', $user->id)
                    ->with('internalAgentContract.getState')
                    ->with('internalAgentContract.getDriverLicenseState')
                    ->with('internalAgentContract.getMoveInState')
                    ->with('internalAgentContract.getResidentInsLicenseState')
                    ->with('internalAgentContract.getBusinessState')
                    ->with('internalAgentContract.additionalInfo.getState')
                    ->with('internalAgentContract.addresses.getState')
                    ->with('internalAgentContract.legalQuestion')->first();

                $pdf = PDF::loadView('pdf.internal-agent-contract.agent-contract', $returnArr);
                $directory = public_path('internal-agents/contract/');
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $fileName = auth()->user()->id.'_contract.pdf';

                //deleted PDF without sign for Accompanying Sign
                if (file_exists(public_path() . '/internal-agents/contract/' . $fileName)) {
                    unlink(public_path() . '/internal-agents/contract/' . $fileName);
                }
                $pdf->save($directory . $fileName);

                //End deleted PDF without sign for Accompanying Sign
                $apiClient = new ApiClient();
                $apiClient->getOAuth()->setOAuthBasePath("account-d.docusign.com");
                $docuSignAuthCode = $this->getToken($apiClient);
                $request->session()->put('docusign_auth_code', $docuSignAuthCode);

                //End Contract PDF
                return response()->json([
                    'success' => true,
                    'message' => 'Document ready to sign.',
                    'route' => route('internal.agent.docusign.sign'),
                    'docuSignAuthCode' => session()->get('docusign_auth_code'),
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage(),
                ], 400);
            }
        }
    }

    function registrationSignature(Request $request)
    {
        dd($request);
    }

    public function pdf()
    {
//        $jwt_token="eyJ0eXAiOiJNVCIsImFsZyI6IlJTMjU2Iiwia2lkIjoiNjgxODVmZjEtNGU1MS00Y2U5LWFmMWMtNjg5ODEyMjAzMzE3In0.AQoAAAABAAUABwAA5OLp5MvbSAgAAEynS-3L20gCAJgPjHagsedJmPsqwsgbcvQVAAEAAAAYAAIAAAAFAAAAHQAAAA0AJAAAADc1ZDk3NzE4LThhOTgtNGQyNy04ZGVmLTE3YzJmY2VlZDc5ZiIAJAAAADc1ZDk3NzE4LThhOTgtNGQyNy04ZGVmLTE3YzJmY2VlZDc5ZhIAAQAAAAYAAABqd3RfYnIjACQAAAA3NWQ5NzcxOC04YTk4LTRkMjctOGRlZi0xN2MyZmNlZWQ3OWY.xY21b5yOhNtUbh8eachI2_B6gMqibO-H89FlLdjkBdlF61149VcH-aIw9icDra_uK4rfL-M6DeqBN1XMiqsCuZnCa1yBYIgxZg6mhBER3E-9uVtcL0yYwKWsQyYNZAtz3l5rQJF_lgxLlEvsMIohR6EcGVGC03Oqn1GgvfpX-XbIweTtHBezS-wrjh0Iaep09eA3fCRmEKdGIul7bSuuCRAvKb6PlLEZQ434j2paUlkg4s5kFhI_hukHAKICtCGDxWZQMYshZrn9XAg1SZfsh7ykriybZ_83kXVNTQPZQ8rMS0tqToSqmJvSx1TV_3LWKet1p9zXpr0FqNTLQHP3Nw";
//        $config = new Configuration();
//        $config->setHost('<https://demo.docusign.net/restapi>');
//        $config->addDefaultHeader("Authorization", "Bearer ".$jwt_token);


        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath("account-d.docusign.com");
        $accessToken = $this->getToken($apiClient);


        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . '/restapi');
        /**
         *
         * Step 4
         * Build the envelope object
         *
         * Make an API call to create the envelope and display the response in the view
         *
         */
        $envelopeDefenition = $this->buildEnvelope();
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $envelopeSummary = $envelopeApi->createEnvelope($accountInfo[0]->getAccountId(), $envelopeDefenition);




            $viewRequest = new RecipientViewRequest([
                'return_url' => '<https://staging.allcalls.io/return-url>',
                'authentication_method' => 'jwt',
                'email' => 'awaisamir23@gmail.com',
                'user_name' => 'Faiz rana',
                'client_user_id' => '1'
            ]);
//            dd($envelopeApi->createRecipientView("7716918e-104d-4915-b7ca-eff79222ac45", $envelopeSummary->getEnvelopeId(), $viewRequest));
            $signingUrl = $envelopeApi->createRecipientView("7716918e-104d-4915-b7ca-eff79222ac45", $envelopeSummary->getEnvelopeId(), $viewRequest);

            redirect()->to($signingUrl['url']);


        } catch (\Exception $th) {
            return back()->withError($th->getMessage())->withInput();
        }
//        dd($result);
//        return view('contract.response')->with('result', $result);

    }

    public function pdfExport()
    {
        set_time_limit(0);

        $returnArr['contractData'] = User::where('id', 3)
            ->with('internalAgentContract')
            ->first();

        $pdf = PDF::loadView('pdf.internal-agent-contract.agent-contract', $returnArr);

        return $pdf->stream('signature-authorization.pdf');
    }

    private function getToken(ApiClient $apiClient) : string{
        try {
            $privateKey = file_get_contents(public_path('private.key'),true);
            $response = $apiClient->requestJWTUserToken(
                $ikey = env('DOCUSIGN_INTEGRATION_KEY'),
                $userId =  env('DOCUSIGN_USER_ID'),
                $key = $privateKey,
                $scope = env('DOCUSIGN_SCOPE')
            );
            $token = $response[0];
            $accessToken = $token->getAccessToken();
        } catch (\Exception $th) {
            throw $th;
        }
        return $accessToken;
    }

    private function buildEnvelope(): EnvelopeDefinition{

        $fileContent = file_get_contents(asset('/first-step-sign.pdf'));
        $fileName = "Sample Document";
        $fileExtension = 'pdf';
        $recipientEmail ="awaisamir23@gmail.com";
        $recipientName = "Faiz rana";

        $document = new Document([
            'document_id' => "16",
            'document_base64' => base64_encode($fileContent),
            'file_extension' => $fileExtension,
            'name' => $fileName
        ]);
        $sign_here_tab = new SignHere([
            'anchor_string' => "**signature**",
            'anchor_units' => "pixels",
            'anchor_x_offset' => "100",
            'anchor_y_offset' => "0"
        ]);
        $sign_here_tabs = [$sign_here_tab];
        $tabs1 = new Tabs([
            'sign_here_tabs' => $sign_here_tabs
        ]);
        $signer = new Signer([
            'email' => $recipientEmail,
            'name' =>  $recipientName,
            'recipient_id' => "1",
            'tabs' => $tabs1
        ]);
        $signers = [$signer];
        $recipients = new Recipients([
            'signers' => $signers
        ]);
        $inline_template = new InlineTemplate([
            'recipients' => $recipients,
            'sequence' => "1"
        ]);
        $inline_templates = [$inline_template];
        $composite_template = new CompositeTemplate([
            'composite_template_id' => "1",
            'document' => $document,
            'inline_templates' => $inline_templates
        ]);
        $composite_templates = [$composite_template];
        $envelope_definition = new EnvelopeDefinition([
            'composite_templates' => $composite_templates,
            'email_subject' => "Sample Please sign",
            'status' => "sent"
        ]);

        return $envelope_definition;

    }
}
