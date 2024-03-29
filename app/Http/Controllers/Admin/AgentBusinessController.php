<?php

namespace App\Http\Controllers\Admin;

use App\Events\AppSubmittedEvent;
use App\Events\PolicySubmitedEvent;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InternalAgentMyBusiness;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AgentBusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalAgentMyBusiness::query();

        if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
            $startDate = Carbon::parse($request->from)->startOfDay();
            $endDate = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalAppsSubmitted = $query->count();

        $averageAPV = $query->average('premium_volumn');

        $totalAPV = $query->sum('premium_volumn');

        $businesses = $query->with(['client', 'client.call', 'getCall'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        $states = State::get();

        $agentRole = Role::whereName('internal-agent')->first();

        $agents = User::whereHas('roles', function ($query) use ($agentRole) {
            $query->where('role_id', $agentRole->id);
        })->get();

        $clients = Client::where('unlocked', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('InternalAgent/MyBusiness/Index', [
            'businesses' => $businesses,
            'states' => $states,
            'clients' => $clients,
            'requestData' =>  $request->all(),
            'agents' => $agents,
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
        $internalAgentBusiness =   InternalAgentMyBusiness::create([
            'agent_id' => $request->agent_id,
            'client_id' => $request->client_id ? $request->client_id :  null,
            'agent_full_name' => $request->agent_full_name,
            'agent_email' => $request->agent_email,
            'status' => $request->status,
            'insurance_company' => $request->insurance_company,
            'product_name' => $request->product_name,
            'application_date' => $request->application_date,
            'coverage_amount' => $request->coverage_amount,
            'coverage_length' => $request->coverage_length,
            'premium_frequency' => $request->premium_frequency,
            'premium_amount' => $request->premium_amount,
            'premium_volumn' => $request->premium_volumn,
            'carrier_writing_number' => $request->carrier_writing_number,
            'this_app_from_lead' => $request->this_app_from_lead,
            'source_of_lead' => $request->this_app_from_lead == 'NO' ? null : $request->source_of_lead,
            'policy_draft_date' => $request->policy_draft_date,
            'label' => $request->label ?? null,
            'first_name' => $request->first_name,
            'mi' => $request->mi,
            'last_name' => $request->last_name,
            'beneficiary_name' => $request->beneficiary_name,
            'beneficiary_relationship' => $request->beneficiary_relationship,
            'notes' => $request->notes,
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
        event(new AppSubmittedEvent($internalAgentBusiness));

        if ($internalAgentBusiness->client_id != '' && Str::startsWith($internalAgentBusiness->client->status, 'Sale')) {
            event(new PolicySubmitedEvent($internalAgentBusiness));
        }

        return response()->json([
            'success' => true,
            'message' => 'Business Added Successfully!',
        ], 200);
    }

    public function update(Request $request)
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

        $InternalAgentMyBusiness = InternalAgentMyBusiness::find($request->business_id);

        if ($InternalAgentMyBusiness) {
            $InternalAgentMyBusiness->update([
                'agent_id' => $request->agent_id,
                'agent_full_name' => $request->agent_full_name,
                'agent_email' => $request->agent_email,
                'status' => $request->status,
                'insurance_company' => $request->insurance_company,
                'product_name' => $request->product_name,
                'application_date' => $request->application_date,
                'coverage_amount' => $request->coverage_amount,
                'coverage_length' => $request->coverage_length,
                'premium_frequency' => $request->premium_frequency,
                'premium_amount' => $request->premium_amount,
                'premium_volumn' => $request->premium_volumn,
                'carrier_writing_number' => $request->carrier_writing_number,
                'this_app_from_lead' => $request->this_app_from_lead,
                'source_of_lead' => $request->this_app_from_lead === 'NO' ? null : $request->source_of_lead,
                'policy_draft_date' => $request->policy_draft_date,
                'client_id' => $request->client_id,
                'first_name' => $request->first_name,
                'mi' => $request->mi,
                'last_name' => $request->last_name,
                'beneficiary_name' => $request->beneficiary_name,
                'beneficiary_relationship' => $request->beneficiary_relationship,
                'notes' => $request->notes,
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
            event(new AppSubmittedEvent($InternalAgentMyBusiness));
            return response()->json([
                'success' => true,
                'message' => 'Business updated Successfully!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Business Not found!',
            ], 401);
        }
    }

    public function getAgentByName(Request $request)
    {
        $agentRole = Role::whereName('internal-agent')->first();

        $agents = User::whereHas('roles', function ($query) use ($agentRole) {
            $query->where('role_id', $agentRole->id);
        })
            ->where(function ($query) use ($request) {
                if (isset($request->agent_name) && $request->agent_name != '') {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->agent_name . '%']);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'agents' => $agents
        ]);
    }
    public function fetchClient($id)
    {
        try {
            $clients = Client::where('user_id', $id)->where('unlocked', true)->get();
            return response()->json([
                'clients' => $clients,
                'status' => 200
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'status' => 404
            ],404);
        }
    }
}
