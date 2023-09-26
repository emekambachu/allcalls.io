<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Call;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\State;
use App\Models\CallType;
use App\Models\Role;
use App\Models\User;
use App\Rules\CallTypeIdEixst;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegisteredInternalAgentController extends Controller
{
    public function create(): Response
    {
        $callTypes = CallType::all();
        $states = State::all();
        return Inertia::render('InternalAgent/Register', compact('callTypes', 'states'));
    }
    public function store(Request $request)
    {
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
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

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

        event(new Registered($user));

        Auth::login($user);
        return response()->json([
            'success' => true,
            'message' => 'Agent added successfully',
        ], 200);
    }
}
