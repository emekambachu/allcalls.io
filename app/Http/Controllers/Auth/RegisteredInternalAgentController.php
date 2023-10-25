<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AgentInvite;
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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'legacy_key' => false,
                'progress' => 'Started contracting'
            ]);

            $agentRole = Role::whereName('internal-agent')->first();

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $agentRole->id,
            ]);

            $token = AgentInvite::where('token', '=', session()->get('agent-token'))->first();

            $token->isUsed($token->token);

            DB::commit();

            session()->remove('agent-token');

            event(new Registered($user));

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Agent added successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Invalid agent invite token.',
            ], 401);
        }
    }
}
