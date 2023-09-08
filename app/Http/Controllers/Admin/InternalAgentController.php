<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Call;
use App\Models\CallType;
use App\Models\Role;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class InternalAgentController extends Controller
{
    public function index(Request $request)
    {
        $agent = Role::whereName('internal-agent')->first();

        $agents = User::whereHas('roles', function ($query) use ($agent) {
            $query->where('role_id', $agent->id);
        })
        ->where(function($query) use ($request) {
            if(isset($request->name) && $request->name != '') {
                $query->where('first_name', 'LIKE', '%' . $request->name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            }
        })
        ->where(function($query) use ($request) {
            if(isset($request->email) && $request->email != '') {
                $query->where('email', 'LIKE', '%' . $request->email . '%');
            }
        })
        ->where(function($query) use ($request) {
            if(isset($request->phone) && $request->phone != '') {
                $query->where('phone', 'LIKE', '%' . $request->phone . '%');
            }
        })
        ->where(function($query) use ($request) {
            if(isset($request->card_no) && $request->card_no != '') {
                $query->whereHas('cards', function($query) use ($request){
                    $query->where('number', 'LIKE', '%' . $request->card_no . '%');
                });
            }
        })
        ->with('states')
        ->with('callTypes')
        ->paginate(10);

        $callTypes = CallType::get();
        $states = State::get();
        return Inertia::render('Admin/Agent/Index', [
            'requestData'=>$request->all(),
            'agents' => $agents,
            'callTypes' => $callTypes,
            'states' => $states,
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $callsCount = Call::whereUserId($id)->count();
        $transactionsCount = Transaction::whereUserId($id)->count();
        $activitiesCount = Activity::whereUserId($id)->count();

        return Inertia::render('Admin/Agent/Show', [
            'user' => $user,
            'callsCount' => $callsCount,
            'transactionsCount' => $transactionsCount,
            'activitiesCount' => $activitiesCount,
        ]);
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'phone' => ['required', 'string', 'max:255', 'unique:' . User::class, 'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/'],
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
            ],
            'typesWithStates.*' => ['nullable', 'exists:call_types,id'],
            'typesWithStates.*.*' => ['nullable', 'exists:states,id'],
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
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'balance' => isset($request->balance)?$request->balance:0,
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
        $calls = Call::whereUserId($id)->with('user', 'callType')->paginate(10);
        return response()->json([
            'calls' => $calls
        ]);
    }

    public function getTransaction($id)
    {
        $transactions = Transaction::whereUserId($id)->with('card')->paginate(10);
        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function getActivity($id)
    {
        $activities = Activity::whereUserId($id)->paginate(10);
        return response()->json([
            'activities' => $activities
        ]);
    }

    public function update(Request $request, $id)
    {
        // echo $request->all();
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
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'phone')->ignore($id),
                'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        try{
            $user= User::find($id);
            if($user->balance!=$request->balance){
                Transaction::create([
                    'amount'=>$request->balance-$user->balance,
                    'sign'=> 1,
                    'bonus'=>0,
                    'user_id'=>$id,
                    'comment'=>$request->comment
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
            'phone' => $request->phone,
            'balance' => isset($request->balance)?$request->balance:0,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Agent updated successfully.',
        ], 200);
    }catch(Exception $e){
        return response()->json(['error'=>$e], 500);
    }

    }
}
