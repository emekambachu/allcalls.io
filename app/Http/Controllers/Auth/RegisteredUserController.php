<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Bid;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use Inertia\Response;
use App\Models\CallType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $callTypes = CallType::all();
        $states = State::all();

        return Inertia::render('Auth/Register', compact('callTypes', 'states'));
    }

    public function validateStepOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'phone' => ['required', 'string', 'max:255', 'unique:'.User::class, 'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        return response()->json([
            'success' => true,
        ], 201);

    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'phone' => ['required', 'string', 'max:255', 'unique:'.User::class, 'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'consent' => 'required',

            'typesWithStates' => 'required|array',
            'typesWithStates.*' => ['nullable', 'exists:call_types,id'],
            'typesWithStates.*.*' => ['nullable', 'exists:states,id'],

            'bids' => 'required|array',
        ]);

        if (sizeof($request->bids) !== CallType::count()) {
            return abort(400);
        }
        



        if (! $request->consent) {
            return redirect()->back()->withErrors(['consent' => 'Consent is required.']);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);


        // NOTE: ADD SOME VALIDATION FOR BIDS HERE
        foreach($request->bids as $bid) {
            Bid::updateOrCreate([
                'call_type_id' => $bid['call_type_id'],
                'amount' => $bid['amount'],
                'user_id' => $user->id,
            ]);
        }

        $typesWithStates = $request->typesWithStates;

        // Initialize an empty array to hold the data
        $data = [];
        
        // Get the current timestamp
        $now = now()->toDateTimeString();
        
        // Build the data array
        foreach($typesWithStates as $typeId => $stateIds) {
            foreach($stateIds as $stateId) {
                $data[] = [
                    'user_id' => $user->id,  // assuming you have the $user available here
                    'call_type_id' => $typeId,
                    'state_id' => $stateId,
                    'created_at' => $now,  // if you are using timestamps in your pivot table
                    'updated_at' => $now,  // if you are using timestamps in your pivot table
                ];
            }
        }
        
        DB::transaction(function () use ($user, $data) {
            // Remove existing entries
            DB::table('users_call_type_state')->where('user_id', $user->id)->delete();
        
            // Insert new entries
            DB::table('users_call_type_state')->insert($data);
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function saveCallTypeStates(array $data, User $user): void
    {
        // begin a database transaction
        DB::beginTransaction();
    
        try {
            // iterate over the data array
            foreach ($data as $callTypeId => $stateIds) {
                // find the CallType
                $callType = CallType::findOrFail($callTypeId);
    
                // iterate over the stateIds for this callType
                foreach ($stateIds as $stateId) {
                    // find the State
                    $state = State::findOrFail($stateId);
    
                    // create a new record in the pivot table
                    $user->callTypes()->attach($callType, ['state_id' => $state->id]);
                }
            }
    
            // if we made it this far without an exception, commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // if there was any exception, rollback the transaction
            DB::rollBack();
            throw $e;
        }
    }
}
