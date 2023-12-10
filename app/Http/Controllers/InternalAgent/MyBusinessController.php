<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentMyBusiness;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Carbon\Carbon;

class MyBusinessController extends Controller
{
        public function index(Request $request)
        {
            $businesses = InternalAgentMyBusiness::whereIn('agent_id', getInviteeIds(auth()->user()))
            ->where(function ($query) use ($request) {
                if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                    $startDate = Carbon::parse($request->from)->startOfDay();
                    $endDate = Carbon::parse($request->to)->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            })
            ->paginate(10);
            $states = State::get();
            // dd($businesses);
            return Inertia::render('InternalAgent/MyBusiness/Index', [
                'businesses' => $businesses,
                'states' => $states,
                'requestData' =>  $request->all()
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

        InternalAgentMyBusiness::create([
            'agent_id' => auth()->user()->id,
            'agent_full_name' => $request->agent_full_name,
            'agent_email' => $request->agent_email,
            'insurance_company' => $request->insurance_company,
            'product_name' => $request->product_name,
            'application_date' => Carbon::parse($request->application_date),
            'coverage_amount' => $request->coverage_amount,
            'coverage_length' => $request->coverage_length,
            'premium_frequency' => $request->premium_frequency,
            'premium_amount' => $request->premium_amount,
            'premium_volumn' => $request->premium_volumn,
            'carrier_writing_number' => $request->carrier_writing_number ,
            'this_app_from_lead' => $request->this_app_from_lead,
            'source_of_lead' => $request->this_app_from_lead == 'NO' ? null : $request->source_of_lead,
            'policy_draft_date' => Carbon::parse($request->policy_draft_date),
            'first_name' => $request->first_name,
            'mi' => $request->mi,
            'last_name' => $request->last_name,
            'dob' => Carbon::parse($request->dob),
            'gender' => $request->gender,
            'client_street_address_1' => $request->client_street_address_1,
            'client_street_address_2' => $request->client_street_address_2,
            'client_city' => $request->client_city,
            'client_state' => $request->client_state,
            'client_zipcode' => $request->client_zipcode,
            'client_phone_no' => $request->client_phone_no,
            'client_email' => $request->client_email,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Bussiness Added Successfully!',
        ], 200);
    }
}
