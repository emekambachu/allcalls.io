<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\AgentInvite;
use App\Models\InternalAgentLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MyAgencyController extends Controller
{
    public function index()
    {
        $agentInvites = AgentInvite::with('getAgentLevel')->orderBy('created_at', 'desc')->paginate(10);
        $agentLevels = InternalAgentLevel::get();
        return Inertia::render('InternalAgent/AgentInvites/Index', compact('agentInvites', 'agentLevels'));
    }
    public function store(Request $request)
    {
        dd($request->all());
        $valdiation = Validator::make($request->all(), [
            'email' => 'required|unique:agent_invites',
            'level' => 'required',
            'upline_id' => 'required',
        ]);
        if ($valdiation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valdiation->errors(),
            ], 400);
        }

        $uuId = uniqid();
        $inviteSent = AgentInvite::create(
            [
                'token' => $uuId,
                'level_id' => $request->level,
                'email' => $request->email,
                'upline_id' => $request->upline_id,
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
        dd($id);
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
        dd($id);
        $invite = AgentInvite::findOrFail($id);
        $invite->delete();
        return response()->json([
            'success' =>  true,
            'message' => 'Invite deleted successfully.'
        ]);
        // return redirect()->back()->with(['message' => 'Invite deleted successfully.']);
    }
}
