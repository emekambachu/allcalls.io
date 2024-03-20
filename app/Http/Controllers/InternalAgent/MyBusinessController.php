<?php

namespace App\Http\Controllers\InternalAgent;

use App\Events\AppSubmittedEvent;
use App\Events\PolicySubmitedEvent;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InternalAgentMyBusiness;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;

class MyBusinessController extends Controller
{
    public function index(Request $request)
    {
        $isClient = false;
        $clientdata = null;
        $userNotFound = null;
        $businessesFilter = null;

        if (isset($_GET['clientId']) && $_GET['clientId'] != '') {
            $clientdata = Client::find($_GET['clientId']);
            if ($clientdata) {
                $isClient = true;
            } else {
                $userNotFound = 'User Not Found.';
            }
        }

        $query = InternalAgentMyBusiness::whereIn('agent_id', getInviteeIds(auth()->user()));

        if ($isClient) {
            $query->where('client_id', $_GET['clientId']);
        }

        if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
            $startDate = Carbon::parse($request->from)->startOfDay();
            $endDate = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalAppsSubmitted = $query->count();
        $averageAPV = $query->average('premium_volumn');
        $totalAPV = $query->sum('premium_volumn');

        $businesses = $query->with(['client', 'client.call'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        $clients = Client::where('user_id', auth()->user()->id)
            ->where('unlocked', true)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $states = State::get();

        return Inertia::render('InternalAgent/MyBusiness/Index', [
            'businesses' => $businesses,
            'businessesFilter' => $businessesFilter,
            'states' => $states,
            'client' => $clientdata,
            'clients' => $clients,
            'is_client' => $isClient,
            'requestData' =>  $request->all(),
            'userNotFound' => $userNotFound,
            'totalAppsSubmitted' => $totalAppsSubmitted,
            'averageAPV' => $averageAPV,
            'totalAPV' => $totalAPV
        ]);
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'agent_full_name' => 'required',
            'agent_email' => 'required',
            'insurance_company' => 'required',
            'product_name' => 'required',
            'application_date' => 'required',
            'coverage_amount' => 'required|numeric',
            'coverage_length' => 'required|in:N/A,3 Years,5 Years,5 Years w/ 5 Year Guaranty,5 Years w/ ROP,7 Years,10 Years,15 Years,15 Years w/ ROP,20 Years,20 Years w/ 5 Year Guaranty,20 Years w/ ROP,25 Years,25 Years w/ ROP,30 Years,30 Years w/ 5 Year Guaranty,30 Years w/ ROP,Issue Ages,Whole Life',
            'premium_frequency' => 'required',
            'premium_amount' => 'required|numeric',
            'premium_volumn' => 'required|numeric',
            'this_app_from_lead' => 'required',
            'policy_draft_date' => 'required',
            'first_name' => 'required',
            'mi' => 'nullable',
            'last_name' => 'required',
            'beneficiary_name' => 'required',
            'beneficiary_relationship' => 'required',
            'dob' => 'required',
            'gender' => 'required | in:Male,Female,Other',
            'client_street_address_1' => 'nullable',
            'client_street_address_2' => 'nullable',
            'client_city' => 'nullable',
            'client_state' => 'nullable',
            'client_zipcode' => 'nullable',
            'client_phone_no' => 'required',
            'client_email' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors(),
            ], 400);
        }
        // if (isset($request->client_id)) {
            // $business = InternalAgentMyBusiness::where('client_id', $request->client_id)->first();
            // if ($business) {
            //     return response()->json([
            //         'success' => false,
            //         'error' => 'This client already contain a business. Please select new one.',
            //     ], 400);
            // }
        // }

        if (isset($request->business_id)) {
            $internalAgentBusiness = InternalAgentMyBusiness::find($request->business_id);
            if (!$internalAgentBusiness) {
                return response()->json([
                    'success' => false,
                    'error' => 'This business is not found in the record.',
                ], 400);
            }
        } else {
            $internalAgentBusiness = new InternalAgentMyBusiness();
        }
        if (isset($internalAgentBusiness) && !$internalAgentBusiness->agent_id) {
            $internalAgentBusiness->agent_id = auth()->user()->id;
            $internalAgentBusiness->agent_full_name = $request->agent_full_name;
            $internalAgentBusiness->agent_email = $request->agent_email;
        }
        $internalAgentBusiness->client_id = $request->client_id;
        $internalAgentBusiness->label = $request->label ?? null;
        $internalAgentBusiness->status = $request->status;
        $internalAgentBusiness->insurance_company = $request->insurance_company;
        $internalAgentBusiness->product_name = $request->product_name;
        $internalAgentBusiness->application_date = $request->application_date;
        $internalAgentBusiness->coverage_amount = $request->coverage_amount;
        $internalAgentBusiness->coverage_length = $request->coverage_length;
        $internalAgentBusiness->premium_frequency = $request->premium_frequency;
        $internalAgentBusiness->premium_amount = $request->premium_amount;
        $internalAgentBusiness->premium_volumn = $request->premium_volumn;
        $internalAgentBusiness->carrier_writing_number = $request->carrier_writing_number;
        $internalAgentBusiness->this_app_from_lead = $request->this_app_from_lead;
        $internalAgentBusiness->source_of_lead = $request->this_app_from_lead == 'NO' ? null : $request->source_of_lead;
        $internalAgentBusiness->policy_draft_date = $request->policy_draft_date;
        $internalAgentBusiness->first_name = $request->first_name;
        $internalAgentBusiness->mi = $request->mi;
        $internalAgentBusiness->last_name = $request->last_name;
        $internalAgentBusiness->beneficiary_name = $request->beneficiary_name;
        $internalAgentBusiness->beneficiary_relationship = $request->beneficiary_relationship;
        $internalAgentBusiness->notes = $request->notes;
        $internalAgentBusiness->dob = $request->dob;
        $internalAgentBusiness->gender = $request->gender;
        $internalAgentBusiness->client_street_address_1 = $request->client_street_address_1;
        $internalAgentBusiness->client_street_address_2 = $request->client_street_address_2;
        $internalAgentBusiness->client_city = $request->client_city;
        $internalAgentBusiness->client_state = $request->client_state;
        $internalAgentBusiness->client_zipcode = $request->client_zipcode;
        $internalAgentBusiness->client_phone_no = $request->client_phone_no;
        $internalAgentBusiness->client_email = $request->client_email;
        $internalAgentBusiness->save();

        event(new AppSubmittedEvent($internalAgentBusiness));

        if(!isset($request->business_id)){
            dd("TestTest");
            if($internalAgentBusiness->client_id !='' && Str::startsWith($internalAgentBusiness->client->status, 'Sale')){
                event(new PolicySubmitedEvent($internalAgentBusiness));

            }
        }

        return response()->json([
            'success' => true,
            'message' => isset($request->business_id)? 'Client has been attached to the policy' : 'Business added successfully.',
        ], 200);
    }
    public function businessByLabel(Request $request)
    {
        $businesses = InternalAgentMyBusiness::whereIn('agent_id', getInviteeIds(auth()->user()))
            ->where(function ($query) use ($request) {
                if (isset($request->business_label) && $request->business_label != '') {
                    $query->where('agent_full_name', 'LIKE', '%' . $request->business_label . '%');
                }
            })
            ->get();
        return response()->json([
            'businesses' => $businesses
        ]);
    }

    public function getClientByName(Request $request)
    {
        $clients = Client::where('user_id', auth()->user()->id)
            ->where('unlocked', true)
            ->where(function ($query) use ($request) {
                if (isset($request->client_name) && $request->client_name != '') {
                    $query->where('phone', 'LIKE', '%' . $request->business_label . '%');
                    // $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->client_name . '%']);
                }
            })

            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'clients' => $clients
        ]);
    }
}
