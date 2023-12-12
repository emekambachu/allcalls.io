<?php

namespace App\Http\Controllers\InternalAgent;

use App\Events\InviteAgent;
use App\Http\Controllers\Controller;
use App\Models\AgentInvite;
use App\Models\InternalAgentLevel;
use App\Models\sendEmailToPeople;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;
class MyAgencyController extends Controller
{
    public function index()
    {
       
        $agentInvites = AgentInvite::where('invited_by', auth()->user()->id)
            ->with('getAgentLevel')
            ->with('getAgentLevel')->orderBy('created_at', 'desc')
            ->paginate(5);

        // $agentLevels = InternalAgentLevel::get();
        $agentLevels = [];
        if(auth()->user()->getAgentLevel) {
            $agentLevels = InternalAgentLevel::where('order', '<=', auth()->user()->getAgentLevel->order)
            ->where(function ($query) {
                $stringToCheck = auth()->user()->getAgentLevel->name;
                if (Str::contains($stringToCheck, 'Internal')) {
                    $query->where('name', 'like', "%Internal%");
                } elseif (Str::contains($stringToCheck, 'AC'))  {
                    $query->where('name', 'like', "%AC%");
                }
            })
            ->get();
        }

        $inviteAgents = getInviteeIds(auth()->user());
        $agents = User::whereIn('id', $inviteAgents)
        ->where('id', '!=', auth()->user()->id)
        ->withCount('invitees')
        ->with(['getAgentLevel', 'states', 'latestActivity', 'callTypes'])
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        return Inertia::render('InternalAgent/Invites/Index', compact('agentInvites', 'agentLevels', 'agents'));
    }
    function GetAgentInvites() {
        $agentInvites = AgentInvite::where('invited_by', auth()->user()->id)
        ->with('getAgentLevel')
        ->with('getAgentLevel')->orderBy('created_at', 'desc')
        ->paginate(5);
        return response()->json([
            'success' => true,
            'agentInvites' => $agentInvites,
        ], 200);

    }
    public function store(Request $request)
    {
        $valdiation = Validator::make($request->all(), [
            'email' => 'required|unique:agent_invites',
            'level' => 'required',
        ]);
        if ($valdiation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valdiation->errors(),
            ], 400);
        }

        if ($request->level > auth()->user()->level_id) {
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

        $uuId = uniqid();
        $inviteSent = AgentInvite::create(
            [
                'token' => $uuId,
                'level_id' => $request->level,
                'email' => $request->email,
                'upline_id' => auth()->user()->upline_id,
                'invited_by' => auth()->user()->id,
                'url' => url('/internal-agent/register?agentToken=' . $uuId)
            ]
        );
        event(new InviteAgent($inviteSent->email, $inviteSent->url));
        $agentInvites = AgentInvite::where('invited_by', auth()->user()->id)
        ->with('getAgentLevel')
        ->with('getAgentLevel')->orderBy('created_at', 'desc')
        ->paginate(5);
        return response()->json([
            'success' =>  true,
            'agentInvites' =>  $agentInvites,
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
        $agentInvites = AgentInvite::where('invited_by', auth()->user()->id)
        ->with('getAgentLevel')
        ->with('getAgentLevel')->orderBy('created_at', 'desc')
        ->paginate(10);
        return response()->json([
            'success' =>  true,
            'agentInvites' =>  $agentInvites,
            'message' => 'Invite deleted successfully.'
        ]);
    }

    public function myAgent() {
        $inviteAgents = getInviteeIds(auth()->user());
        $agents = User::whereIn('id', $inviteAgents)
        ->where('id', '!=', auth()->user()->id)
        ->withCount('invitees')
        ->with(['getAgentLevel', 'states', 'latestActivity', 'callTypes'])
        ->orderBy('created_at', 'desc')
        ->paginate(5);
        return response()->json([
            'success' => true,
            'agents' => $agents,
        ], 200);
        // return Inertia::render('InternalAgent/MyAgents/Index', compact('agents'));
    }
    public function getAgentTree($id)
    {
        //retrieve the entire hierarchy
        $agentHierarchy = User::with('allInvitees')->find($id);
        
        return response()->json([
            'success' => true,
            'agentHierarchy' => $agentHierarchy,
        ], 200);
    }
}
