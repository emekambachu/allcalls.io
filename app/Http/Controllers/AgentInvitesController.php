<?php

namespace App\Http\Controllers;

use App\Events\InviteAgent;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\AgentInvite;
use Illuminate\Http\Request;

class AgentInvitesController extends Controller
{
    public function index()
    {
        $agentInvites = AgentInvite::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/AgentInvites/Index', compact('agentInvites'));
    }

    public function store(Request $request)
    {
        $valdiation = Validator::make($request->all(), [
            'email' => 'required|unique:agent_invites',
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
                'email' => $request->email,
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
        $agentreInvite->used = false;
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
