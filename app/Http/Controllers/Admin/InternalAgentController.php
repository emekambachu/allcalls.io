<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Call;
use App\Models\CallType;
use App\Models\Card;
use App\Models\Client;
use App\Models\InternalAgentContractSigned;
use App\Models\Role;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\CallTypeIdEixst;
use Barryvdh\DomPDF\Facade\Pdf;
use Clegginabox\PDFMerger\PDFMerger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Client\Response;
use Inertia\Inertia;
use App\Models\InternalAgentLevel as AgentLevel;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Crypt;

class InternalAgentController extends Controller
{
    public function index(Request $request)
    {
        $matchedCardsFirstSix = [];
        $matchedCardsLastFour = [];
        if (isset($request->first_six_card_no) && $request->first_six_card_no != '' || isset($request->last_four_card_no) && $request->last_four_card_no != '') {
            $cards = Card::get();
            foreach ($cards as $card) {
                if (isset($request->first_six_card_no) && $request->first_six_card_no != '') {
                    $decryptedNumber = Crypt::decryptString($card->number);
                    $decryptedFirstSixDigits = substr($decryptedNumber, 0, 6);
                    if ($decryptedFirstSixDigits == $request->first_six_card_no) {
                        $matchedCardsFirstSix[] = $card->id;
                    }
                }
                if (isset($request->last_four_card_no) && $request->last_four_card_no != '') {
                    $decryptedNumber = Crypt::decryptString($card->number);
                    $decryptedLastFourDigit = substr($decryptedNumber, -4);
                    if ($decryptedLastFourDigit == $request->last_four_card_no) {
                        $matchedCardsLastFour[] = $card->id;
                    }
                }
            }
        }
        $agent = Role::whereName('internal-agent')->first();
        $levels = AgentLevel::orderBy('created_at', 'desc')->get();
        $agents = User::whereHas('roles', function ($query) use ($agent) {
            $query->where('role_id', $agent->id);
        })->withCount('invitees')
            ->where(function ($query) use ($request) {
                if (isset($request->name) && $request->name != '') {
                    $query->where('first_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->email) && $request->email != '') {
                    $query->where('email', 'LIKE', '%' . $request->email . '%');
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->phone) && $request->phone != '') {
                    $query->where('phone', 'LIKE', '%' . $request->phone . '%');
                }
            })
            ->where(function ($query) use ($request , $matchedCardsFirstSix ) {
                if (isset($request->first_six_card_no) && $request->first_six_card_no != '') {
                    $query->whereHas('cards', function ($query) use ($request, $matchedCardsFirstSix ) {
                        $query->whereIn('id', $matchedCardsFirstSix);
                    });
                }
            })

            ->where(function ($query) use ($request, $matchedCardsLastFour) {
                if (isset($request->last_four_card_no) && $request->last_four_card_no != '') {
                    $query->whereHas('cards', function ($query) use ($request, $matchedCardsLastFour) {
                        $query->whereIn('id', $matchedCardsLastFour);
                    });
                }
            })
            ->with(['internalAgentContract.additionalInfo','internalAgentContract.addresses','internalAgentContract.driverLicense',
            'internalAgentContract.amlCourse','internalAgentContract.bankingInfo','internalAgentContract.errorAndEmission',
            'internalAgentContract.legalQuestion','internalAgentContract.residentLicense','internalAgentContract.getQuestionSign',
            'internalAgentContract.getContractSign','states','callTypes','getAgentLevel','invitedBy',
            ])
            // // ->with('internalAgentContract.addresses')
            // // ->with('internalAgentContract.driverLicense')
            // // ->with('internalAgentContract.amlCourse')
            // ->with('internalAgentContract.bankingInfo')
            // ->with('internalAgentContract.')
            // ->with('internalAgentContract.')
            // ->with('internalAgentContract.')
            // ->with('internalAgentContract.')
            // ->with('internalAgentContract.')
            // ->with('states')
            // ->with('callTypes')
            // ->with('getAgentLevel')
            // ->with('invitedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        // dd($agents);

        $callTypes = CallType::get();
        $states = State::get();
        return Inertia::render('Admin/Agent/Index', [
            'requestData' => $request->all(),
            'agents' => $agents,
            'levels' => $levels,
            'callTypes' => $callTypes,
            'states' => $states,
            'statuses' => PROGRESS_STATUSES
        ]);
    }

    public function getAgentTree($id)
    {
        //retrieve the entire hierarchy
        $user = User::findOrFail($id);

        if ($user && $user->roles->contains('name', 'admin')) {
            $agent = Role::whereName('internal-agent')->first();
            $agentHierarchy = User::whereHas('roles', function ($query) use ($agent) {
                $query->where('role_id', $agent->id);
            })->whereNull('invited_by')->with('allInvitees')->get();

            $returnArr = [
                "first_name" => 'AllCalls',
                "last_name" => '.io',
                "is_admin" => true,
                "all_invitees" => $agentHierarchy
            ];

            return response()->json([
                'success' => true,
                'agentHierarchy' => $returnArr,
            ], 200);
        }

        $agentHierarchy = User::with('allInvitees')->find($id);

        return response()->json([
            'success' => true,
            'agentHierarchy' => $agentHierarchy,
        ], 200);
    }

    public function show($id)
    {
        $user = User::with('states')->findOrFail($id);
        $user->setRelation('states', $user->states->unique('id')->values());
        $callsCount = Call::whereUserId($id)->count();
        $transactionsCount = Transaction::whereUserId($id)->count();
        $activitiesCount = Activity::whereUserId($id)->count();
        $ClientCount = Client::where('user_id', $id)->count();
        return Inertia::render('Admin/Agent/Show', [
            'user' => $user,
            'additionalFiles' => $user->additionalFiles,
            'callsCount' => $callsCount,
            'transactionsCount' => $transactionsCount,
            'activitiesCount' => $activitiesCount,
            'ClientCount' => $ClientCount,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            // 'phone' => ['required', 'string', 'max:255', 'unique:' . User::class, 'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/'],
            'phone' => ['required', 'string', 'min:10', 'max:10',  'unique:' . User::class, 'regex:/^[0-9]*$/'],
            'phone_code' => ['required', 'regex:/^\+(?:[0-9]){1,4}$/'],
            'phone_country' => ['required'],
            'level' => 'required|max:255',
            'upline_id' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
            'typesWithStates' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $nestedArray) {
                        if (!empty($nestedArray)) {
                            return; // Validation passes if a non-empty nested array is found
                        }
                    }
                    $fail('At least one States you are licensed in is required.');
                },
                new CallTypeIdEixst('call_types', 'id')
            ],
            'typesWithStates.*' => ['nullable', 'exists:states,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_country' => $request->phone_country,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'level_id' => $request->level,
            'upline_id' => $request->upline_id,
            'password' => Hash::make($request->password),
            'balance' => isset($request->balance) ? $request->balance : 0,
            'invited_by' => auth()->user()->id,

        ]);

        $user->markEmailAsVerified();

        $agentRole = Role::whereName('internal-agent')->first();
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $agentRole->id,
        ]);

        // Initialize an empty array to hold the data
        $data = [];

        // Get the current timestamp
        $now = now()->toDateTimeString();

        // Build the data array
        foreach ($request->typesWithStates as $typeId => $stateIds) {
            if (count($stateIds)) {
                foreach ($stateIds as $stateId) {
                    $data[] = [
                        'user_id' => $user->id,  // assuming you have the $user available here
                        'call_type_id' => $typeId,
                        'state_id' => $stateId,
                        'created_at' => $now,  // if you are using timestamps in your pivot table
                        'updated_at' => $now,  // if you are using timestamps in your pivot table
                    ];
                }
            }
        }
        if (count($data)) {
            DB::transaction(function () use ($data) {
                // Insert new entries
                DB::table('users_call_type_state')->insert($data);
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Agent added successfully',
        ], 200);
    }

    public function getCall($id)
    {
        $calls = Call::whereUserId($id)->with('user', 'getClient',  'callType')->latest()->paginate(10);
        $states = State::all();
        return response()->json([
            'calls' => $calls,
            'states' => $states
        ]);
    }

    public function getTransaction($id)
    {
        $transactions = Transaction::latest()->whereUserId($id)->with('card')->paginate(10);
        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function getActivity($id)
    {
        $activities = UserActivity::whereUserId($id)->orderBy('created_at', 'desc')->with('user')->paginate(100);
        return response()->json([
            'activities' => $activities
        ]);
    }

    public function getAgentClients($id)
    {
        $Clients = Client::where('user_id', $id)->paginate(10);
        return response()->json([
            'clients' => $Clients
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        if ($user->phone !== $request->phone) {
            $phoneValidator = Validator::make($request->all(), [
                'phone_code' => ['required', 'regex:/^\+(?:[0-9]){1,4}$/'],
                'phone_country' => ['required'],
                'phone' => ['required', 'string', 'min:10', 'max:10',  Rule::unique('users', 'phone')->ignore($id), 'regex:/^[0-9]*$/'],
            ]);
            if ($phoneValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $phoneValidator->errors(),
                ], 400);
            }

            $user->update([
                'phone_country' => $request->phone_country,
                'phone_code' => $request->phone_code,
                'phone' => $request->phone,
            ]);
        }

        try {

            if ($user->balance != $request->balance) {
                Transaction::create([
                    'amount' => $request->balance - $user->balance,
                    'sign' => 1,
                    'bonus' => 0,
                    'user_id' => $id,
                    'comment' => $request->comment
                ]);
            }
            //Call Types And State
            $callTypesArr = [];
            if (count($request->selected_states)) {
                foreach ($request->selected_states as $states) {
                    if (isset($states['typeId']) && count($states['selectedStateIds'])) {
                        foreach ($states['selectedStateIds'] as $selectedState) {
                            $data = [
                                'user_id' => $user->id,
                                'call_type_id' => $states['typeId'],
                                'state_id' => $selectedState,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                            array_push($callTypesArr, $data);
                        }
                    }
                }
            }
            DB::table('users_call_type_state')->where('user_id', $user->id)->delete();
            if (count($callTypesArr)) {
                DB::table('users_call_type_state')->insert($callTypesArr);
            }
            //Call Types And State
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'balance' => isset($request->balance) ? $request->balance : 0,
                'level_id' => $request->level,
                'upline_id' => $request->upline_id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Agent updated successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
    public function UpdateTrainingStatus(Request $request){
       try {
        $user = User::where('id', $request->agent_id)->first();
        if($user){
            $user->agent_access_status = $request->agent_access_status;
            $user->update();
            return response()->json([
                'message' => 'Status updated successfully.',
                'user' => $user
            ],200);
        }
       } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'errors' => $th->getMessage(),
        ], 400);
       }
        
        // dd($request->all());
    }

    public function downloadAgentContractPdf($id)
    {
        set_time_limit(0);
        try {
            $user = User::findOrFail($id);
            $contractPDF = InternalAgentContractSigned::where('reg_info_id', $user->internalAgentContract->id)->first();
            return response()->download($contractPDF->sign_url);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }

    public function getQuestionPdf($id, $userId, $serialNo)
    {
        set_time_limit(0);
        $returnArr['contractData'] = User::where('id', $userId)
            ->with(['internalAgentContract.legalQuestion' => function ($query) use ($id) {
                $query->where('id', $id);
            }])
            ->first();
        $pdf = PDF::loadView('pdf.internal-agent-contract.legal-question-by-detail', $returnArr);

        return $pdf->download($serialNo . '-explaination.pdf');
    }

    public function internalAgentApproved($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_locked = false;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Internal Agent Approved Successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }

    public function internalAgentProgress(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->progress = $request->progress;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Internal Agent Progress Status Updated Successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }

    public function signatureAuthorizationPdf($id)
    {
        //        set_time_limit(0);
        //
        //        $returnArr['contractData'] = User::where('id', $id)
        //            ->with('internalAgentContract.getContractSign')
        //            ->first();
        //
        //        $pdf = PDF::loadView('pdf.internal-agent-contract.signature-authorization', $returnArr);
        //
        //        return $pdf->download('signature-authorization.pdf');
    }
}
