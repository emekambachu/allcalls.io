<?php

namespace App\Http\Controllers;

use App\Events\InviteAgent;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\AgentInvite;
use App\Models\InternalAgentLevel;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\InternalAgentLevel as AgentLevel;
use App\Models\User;
use Illuminate\Validation\Rule;

class AgentInvitesController extends Controller
{
    public function index(Request $request)
    {
        $agentInvites = AgentInvite::with('getAgentLevel')->orderBy('created_at', 'desc')->paginate(10);
        $agentLevels = InternalAgentLevel::get();
        $agent = Role::whereName('internal-agent')->first();
        $agents = User::whereHas('roles', function ($query) use ($agent) {
            $query->where('role_id', $agent->id);
        })
            ->whereNotNull('upline_id')
            ->with('getAgentLevel')
            ->orderBy('created_at', 'desc')
            ->get();

        $additionalAgent = User::where('email', 'vince@pinnaclesyn.com')->with('getAgentLevel')->first();

        if ($additionalAgent) {
            $agents->push($additionalAgent);
        }

        return Inertia::render('Admin/AgentInvites/Index', compact('agentInvites', 'agentLevels', 'agents'));
    }

    public function store(Request $request)
    {
        $valdiation = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('agent_invites', 'email'),
                Rule::unique('users', 'email'),
            ],
            'level' => 'required',
            'upline_id' => 'required',
        ]);
        if ($valdiation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valdiation->errors(),
            ], 400);
        }
        if ($request->invited_by) {
            $user = User::where('id', $request->invited_by)->first();
            if ($request->level > $user->level_id) {
                $levelGreater = [
                    "level" => [
                        0 => "Please invite agents who are at the same level or lower."
                    ]
                ];
                return response()->json([
                    'success' =>  false,
                    'errors' => $levelGreater,
                ], 400);
            }
        }
        $uuId = uniqid();
        $inviteSent = AgentInvite::create(
            [
                'token' => $uuId,
                'level_id' => $request->level,
                'email' => $request->email,
                'upline_id' => $request->upline_id,
                'invited_by' => $request->invited_by ?? auth()->user()->id,
                'url' => url('/internal-agent/register?agentToken=' . $uuId)
            ]
        );
        event(new InviteAgent($inviteSent->email, $inviteSent->url));
        return response()->json([
            'success' =>  true,
            'message' => 'Agent invited successfully.',
        ]);
    }

    public function reInvite($id)
    {
        $agentreInvite = AgentInvite::findOrFail($id);
        $uuId = uniqid();
        $agentreInvite->token = $uuId;
        $agentreInvite->url = url('/internal-agent/register?agentToken=' . $uuId);
        $agentreInvite->created_at = now();
        $agentreInvite->updated_at = now();
        $agentreInvite->save();

        event(new InviteAgent($agentreInvite->email, $agentreInvite->url));

        return response()->json([
            'success' =>  true,
            'message' => 'Agent re-invited successfully.',
        ]);
    }

    public function destroy($id)
    {
        $invite = AgentInvite::findOrFail($id);
        $invite->delete();
        return response()->json([
            'success' =>  true,
            'message' => 'Invite deleted successfully.'
        ]);
        // return redirect()->back()->with(['message' => 'Invite deleted successfully.']);
    }
}
