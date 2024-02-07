<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\EquisAPIJob;
use App\Models\AgentInvite;
use App\Models\CallType;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredInternalAgentController extends Controller
{
    public function create(): Response
    {
        $callTypes = CallType::all();
        $states = State::all();
        $tokenData = '';
        if (session('agent-token')) {
            $tokenData = AgentInvite::where('token', session('agent-token'))->first();
        }

        return Inertia::render('InternalAgent/Register', compact('callTypes', 'states', 'tokenData'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'phone' => ['required', 'string', 'min:10', 'max:10', 'unique:' . User::class, 'regex:/^[0-9]*$/'],
            'phone_code' => ['required', 'regex:/^\+(?:[0-9]){1,4}$/'],
            'phone_country' => ['required'],
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
                'phone_country' => $request->phone_country,
                'phone_code' => $request->phone_code,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'legacy_key' => false,
                'upline_id' => $request->upline_id,
                'level_id' => $request->level_id,
                'invited_by' => $request->invited_by,
            ]);

            $user->generateUnsubscribeToken();

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


//            dispatch(new EquisAPIJob($user));

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

    public function getEfNo($id = 1423)
    {
        // First, retrieve the Bearer token
        $clientId = env('EQUIS_CLIENT_ID'); // Your client ID here
        $clientSecret = env('EQUIS_CLIENT_SECRET'); // Your client secret here

        // First, retrieve the Bearer token
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://equisfinancialb2c.onmicrosoft.com/equis-partner-api-uat/.default'
        ]);

        if (!$tokenResponse->successful()) {
            dd('token issue');
            return;
        }
        $accessToken = $tokenResponse->json()['access_token'];

        $user = User::findOrFail($id);

        $requestData = [
            "address" => $user->internalAgentContract->address ?? null,
            "birthDate" => isset($user->internalAgentContract->dob) ? Carbon::parse($user->internalAgentContract->dob)->format('Y-m-d') : '-',
            "city" => $user->internalAgentContract->city ?? null,
            "currentlyLicensed" => false,
            "email" => $user->internalAgentContract->email ?? null,
            "firstName" => $user->internalAgentContract->first_name ?? null,
            "languageId" => "en",
            "lastName" => $user->internalAgentContract->last_name ?? null,
            "npn" => $user->internalAgentContract->resident_insu_license_no ?? null,
            "partnerUniqueId" => "AC" . $user->id,
            "role" => "Agent",
            "state" => isset($user->internalAgentContract->state) ? $user->internalAgentContract->state : null,
            "uplineAgentEFNumber" => isset($user->upline_id) ? $user->upline_id : "",
            "zipCode" => $user->internalAgentContract->zip ?? null,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post('https://equisapipartner-uat.azurewebsites.net/Agent', $requestData);
        dd("User ID $id",'Agent registered for EF number',$response->body());
        $partnerUniqueId = "AC" . $id;

        $url = "https://equisapipartner-uat.azurewebsites.net/Agent/{$partnerUniqueId}/UserName";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->get($url);

        dd($response->json());

        if ($response->successful()) {
            $efNumber = $response->json()['userName'];
            dd($efNumber);

            $user->ef_number = $efNumber;
            $user->save();

            if (isset($this->inviteeId)) {
                User::whereId($this->inviteeId)->update([
                    'upline_id' => $efNumber
                ]);
            }

            Log::debug('EF Number saved for user', ['Inviter' => $user->id, 'invitee' => $this->inviteeId, 'Inviter EF Number' => $efNumber]);
        } else {
            // Handle the error scenario
            Log::debug('Failed to save EF Number for user', ['Inviter' => $user->id, 'invitee' => $this->inviteeId, 'response' => $response->body()]);
        }
    }
}
