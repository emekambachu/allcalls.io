<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Call;
use App\Models\CallType;
use App\Models\Role;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Bid;



class CustomerController extends Controller
{
    public function index()
    {
        $admin = Role::whereName('admin')->first();
        $users = User::whereDoesntHave('roles', function ($query) use ($admin) {
            $query->where('role_id', $admin->id);
        })->paginate(10);

        return Inertia::render('Admin/User/Index', [
            'users' => $users,
        ]);
    }

    public function show($id, $type = null)
    {
        $user = User::findOrFail($id);
        $callsCount = Call::whereUserId($id)->count();
        $transactionsCount = Transaction::whereUserId($id)->count();
        $activitiesCount = Activity::whereUserId($id)->count();

        return Inertia::render('Admin/User/Show', [
            'user' => $user,
            'callsCount' => $callsCount,
            'transactionsCount' => $transactionsCount,
            'activitiesCount' => $activitiesCount,
        ]);
    }

    public function InternelAgents()  {
        $admin = Role::whereName('admin')->first();
        $users = User::whereDoesntHave('roles', function ($query) use ($admin) {
            $query->where('role_id', $admin->id);
        })->paginate(10);
        $callTypes = CallType::all();
        $states = State::all();
        return Inertia::render('Admin/User/InternelAgents', [
            'users' => $users,
            'callTypes' => $callTypes,
            'states' => $states,
        ]);
    }
    public function internelAgentStore(Request $request){
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
    public function getUserCall($id)
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

        $user = User::whereId($id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'balance' => $request->balance,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
        ], 200);
    }
}
