<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentMyBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MyBusinessController extends Controller
{
    public function index()
    {
        $businesses = InternalAgentMyBusiness::whereIn('agent_id', getInviteeIds(auth()->user()))->paginate(10);
        // dd($businesses);
        return Inertia::render('InternalAgent/MyBusiness/Index', [
            'businesses' => $businesses,
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
        $validate = Validator::make($_GET, [
            'agent_full_name' => 'required',
            'agent_email' => 'required',
            'ef_number' => 'required',
            'upline_manager' => 'required',
            'split_sale' => 'required',
            'split_sale_type' => 'required',
            'split_agent_email' => 'required',
            'insurance_company' => 'required',
            'product_name' => 'required',
            'application_date' => 'required',
            'coverage_amount' => 'required',
            'coverage_length' => 'required|in:N/A,3 Years,5 Years,5 Years w/ 5 Year Guaranty,5 Years w/ ROP,7 Years,10 Years,15 Years,15 Years w/ ROP,20 Years,20 Years w/ 5 Year Guaranty,20 Years w/ ROP,25 Years,25 Years w/ ROP,30 Years,30 Years w/ 5 Year Guaranty,30 Years w/ ROP',
            'premium_frequency' => 'required',
            'quarterly_premium_amount' => 'required',
            'annually_premium_amount' => 'required',
            'equis_writing_number_carrier' => 'nullable',
            'carrier_writing_number' => 'nullable',
            'this_app_from_lead' => 'required',
            'source_of_lead' => 'required',
            'appointment_type' => 'required',
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
            'ef_number' => $request->ef_number,
            'upline_manager' => $request->upline_manager,
            'split_sale' => $request->split_sale,
            'split_sale_type' => $request->split_sale_type,
            'split_agent_email' => $request->split_agent_email,
            'insurance_company' => $request->insurance_company,
            'product_name' => $request->product_name,
            'application_date' => $request->application_date,
            'coverage_amount' => $request->coverage_amount,
            'coverage_length' => $request->coverage_length,
            'premium_frequency' => $request->premium_frequency,
            'quarterly_premium_amount' => $request->quarterly_premium_amount,
            'annually_premium_amount' => $request->annually_premium_amount,
            'equis_writing_number_carrier' => $request->equis_writing_number_carrier,
            'carrier_writing_number' => $request->carrier_writing_number,
            'this_app_from_lead' => $request->this_app_from_lead,
            'source_of_lead' => $request->source_of_lead,
            'appointment_type' => $request->appointment_type,
            'policy_draft_date' => $request->policy_draft_date,
            'first_name' => $request->first_name,
            'mi' => $request->mi,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'client_street_address_1' => $request->client_street_address_1,
            'client_street_address_2' => $request->client_street_address_2,
            'client_city' => $request->client_city,
            'client_state' => $request->client_state,
            'client_zipcode' => $request->client_zipcode,
            'client_phone_no' => $request->client_phone_no,
            'client_email' => $request->client_email,
        ]);
    }
}
