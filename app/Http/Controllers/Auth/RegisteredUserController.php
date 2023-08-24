<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
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
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Call;

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
    public function store(Request $request)
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

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect(route('verification.notice'));
    }

    public function steps(Request $request)
    {
        if (DB::table('users_call_type_state')->where('user_id', auth()->user()->id)->count()) {
            return redirect()->route('dashboard');
        }

        $sevenDaysAgo = Carbon::now()->subDays(7);

        $spendData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('sum(amount_spent) as sum'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        $callTypes = CallType::get();
        $callData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('count(*) as count'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalCalls = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->count();


        $totalAmountSpent = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->sum('amount_spent');

        $averageCallDuration = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->average('call_duration_in_seconds');


        $callTypes = CallType::all();
        $states = State::all();

        return Inertia::render('Auth/RegistrationSteps', [
            'callTypes' => $callTypes,
            'states' => $states,
            'spendData' => $spendData,
            'callData' => $callData,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ]);
    }

    public function storeSteps(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consent' => 'required',
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
            'bids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        if (!$request->consent) {
            return response()->json([
                'success' => false,
                'consent' => 'You must accept the terms and conditions.'
            ], 400);
        }
        if (sizeof($request->bids) !== CallType::count()) {
            return abort(400);
        }

        $user = auth()->user();
        foreach ($request->bids as $bid) {
            Bid::updateOrCreate([
                'call_type_id' => $bid['call_type_id'],
                'amount' => $bid['amount'],
                'user_id' => $user->id,
            ]);
        }
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
        ], 200);
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
