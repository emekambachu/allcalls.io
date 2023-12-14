<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Bid;
use App\Models\Call;
use App\Models\Card;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Client;
use App\Models\Activity;
use App\Models\CallType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\NotificationGroup;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\NotificationGroupMember;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $excludeRoles = Role::whereIn('name', ['admin', 'internal-agent', 'user'])->pluck('id');
        $roles = Role::get();
        $users = User::select('users.*', 'role_user.role_id')->leftjoin('role_user', 'role_user.user_id', 'users.id')
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
            ->where(function ($query) use ($request) {
                if (isset($request->first_six_card_no) && $request->first_six_card_no != '') {
                    $query->whereHas('cards', function ($query) use ($request) {
                        $query->whereRaw('SUBSTRING(number, 1, 6) = ?', [$request->first_six_card_no]);
                    });
                }
            })

            ->where(function ($query) use ($request) {
                if (isset($request->last_four_card_no) && $request->last_four_card_no != '') {
                    $query->whereHas('cards', function ($query) use ($request) {
                        $query->whereRaw('SUBSTRING(number, -4) = ?', [$request->last_four_card_no]);
                    });
                }
            })
            ->with('states')
            ->with('roles')
            ->with('callTypes')
            ->orderBy("users.created_at","DESC")
            ->paginate(100);

        $callTypes = CallType::get();
        $states = State::get();
        return Inertia::render('Admin/User/Index', [
            'requestData' => $request->all(),
            'users' => $users,
            'callTypes' => $callTypes,
            'states' => $states,
            'roles' => $roles,
        ]);
    }

    public function show($id, $type = null)
    {
        $user = User::findOrFail($id);
        $callsCount = Call::whereUserId($id)->count();
        $transactionsCount = Transaction::whereUserId($id)->count();
        $activitiesCount = Activity::whereUserId($id)->count();
        $ClientCount = Client::where('user_id', $id)->count();



        // Get user's call types with states
        $userCallTypesWithStates = $user->callTypes()->with('states')->get();

        // For some reason it's returning the correct call types
        // (only the one's associated with the authenticated user)
        // but wrong states event the ones that do not belong to the current user.
        // So, I'm just filtering it down to currently authenticated user, might be a cleaner
        // way to go about it. Might update later. :)

        $userCallTypesWithStates = $userCallTypesWithStates->map(function ($type) use ($user) {
            $type->user_states = $type->states->filter(function ($state) use ($user) {
                return (int) $state->pivot->user_id === (int) $user->id;
            })->toArray();

            return $type;
        });

        $callTypes = CallType::all();
        $states = State::all();

        $callTypes = $callTypes->map(function ($callType) use ($userCallTypesWithStates, $states) {
            // Find the corresponding call type in $userCallTypesWithStates, if it exists
            $userCallType = $userCallTypesWithStates->firstWhere('id', $callType->id);

            // If a corresponding call type was found in $userCallTypesWithStates, set the call type's selected property
            $callType->selected = $userCallType !== null;

            $callType->statesWithSelection = $states->map(function ($state) use ($userCallType) {
                if ($userCallType !== null && in_array($state->id, array_column($userCallType->user_states, 'id'))) {
                    $state['selected'] = true;
                } else {
                    $state['selected'] = false;
                }

                return $state;
            })->toArray();

            return $callType;

        });
        // dd($callTypes,$userCallTypesWithStates);

        return Inertia::render('Admin/User/Show', [
            'user' => $user,
            'callsCount' => $callsCount,
            'transactionsCount' => $transactionsCount,
            'activitiesCount' => $activitiesCount,
            'ClientCount' => $ClientCount,
            'callTypes'=>$callTypes
        ]);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        
        Card::where('user_id', $user->id)->delete();
        UserCallTypeState::where('user_id', $user->id)->delete();
        Transaction::where('user_id', $user->id)->delete();
        DB::table('role_user')->where('user_id', $user->id)->delete();
        NotificationGroupMember::whereUserId($user->id)->delete();
        $user->delete();

        return response()->json([
            'success' =>  true,
            'message' => 'User deleted successfully.'
        ]);
    }

    public function getUserCall($id)
    {
        $calls = Call::whereUserId($id)->with('user','getClient', 'callType')->orderBy('created_at', 'desc')->paginate(100);
        $states = State::all();
        return response()->json([
            'calls' => $calls,
            'states' => $states,
        ]);
    }

    public function getCustomerClients($id){
        $Clients = Client::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(100);
        return response()->json([
            'clients' => $Clients
        ]);
    }

    public function getTransaction($id)
    {
        $transactions = Transaction::whereUserId($id)->with('card')->orderBy('created_at', 'desc')->paginate(100);
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
    public function deleteActivity($id)
    {
         UserActivity::whereUserId($id)->delete();
        $activities = UserActivity::whereUserId($id)->orderBy('created_at', 'desc')->with('user')->paginate(100);
        return response()->json([
            'activities' => $activities
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
        if($user->phone !== $request->phone){
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
                    'amount' => $request->balance - $user->balance>0 ?$request->balance - $user->balance:-1*($request->balance - $user->balance),
                    'sign' => $request->balance - $user->balance>0?1:0,
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
            ]);
            // check roles user exix
            // $rolesUser = DB::table('role_user')->where('user_id', $user->id)->exists();
            if (isset($request->roles) && !empty($request->roles)) {
                $rolesUser = DB::table('role_user')->where('user_id', $user->id)->exists();
                if ($rolesUser) {
                    DB::table('role_user')->where('user_id', $id)->update(['role_id' => $request->roles]);
                }else{
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role_id' => $request->roles,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
            ], 200);
        } catch (Exception $e) {
            dd($e);
            return response()->json(['error' => $e], 500);
        }
    }

    public function banUser($id){
        $user=User::find($id);
        if(!$user->banned){
            $user->update(['banned'=>!$user->banned]);

            return response()->json([
                'user'=>$user,
                'success' => true,
                'message' => 'Customer banned successfully.',
            ], 200);
        }else{
            $user->update(['banned'=>!$user->banned]);
            return response()->json([
                'user'=>$user,
                'success' => true,
                'message' => 'Customer un banned successfully.',
             ], 200);

        }
    }
}
