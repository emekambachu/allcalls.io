<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AvailableNumber;
use App\Models\Call;
use App\Models\CallType;
use App\Models\Client;
use App\Models\Role;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\CallTypeIdEixst;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AvailableNumberController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->phone)) {
            $availableNumber = AvailableNumber::where('phone', 'LIKE', '%' . $request->phone . '%')->with('callType')->with('user')->paginate(10);
        } else {
            $availableNumber = AvailableNumber::with('callType')->with('user')->paginate(10);
        }


        $user = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'user');
            }
        )->get();

        // dd($user);
        $callTypes = CallType::get();
        $states = State::get();
        return Inertia::render('Admin/AvailableNumber/Index', [
            'requestData' => $request->all(),
            'availableNumber' => $availableNumber,
            'callTypes' => $callTypes,
            'states' => $states,
            'user' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $callsCount = Call::whereUserId($id)->count();
        $transactionsCount = Transaction::whereUserId($id)->count();
        $activitiesCount = Activity::whereUserId($id)->count();
        $ClientCount = Client::where('user_id', $id)->count();
        return Inertia::render('Admin/Agent/Show', [
            'user' => $user,
            'callsCount' => $callsCount,
            'transactionsCount' => $transactionsCount,
            'activitiesCount' => $activitiesCount,
            'ClientCount' => $ClientCount,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:255', 'unique:' . AvailableNumber::class, 'regex:/^\+?1?[-.\s]?(\([2-9]\d{2}\)|[2-9]\d{2})[-.\s]?\d{3}[-.\s]?\d{4}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        AvailableNumber::create([
            'phone' => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agent added successfully',
        ], 200);
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

        try {
            $user = User::find($id);
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
                'phone' => $request->phone,
                'balance' => isset($request->balance) ? $request->balance : 0,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Agent updated successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
