<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\AgentInvite;

class AgentInvitesController extends Controller
{
    public function index()
    {
        $agentInvites = AgentInvite::latest()->get();
        $baseUrl = url('/');

        return Inertia::render('Admin/AgentInvites/Index', compact('agentInvites', 'baseUrl'));
    }

    public function store()
    {
        $agentInvite = AgentInvite::create([ 'token' => uniqid() ]);
    
        $link = url('/internal-agent/register?token=' . $agentInvite->token);
    
        return redirect('/admin/agent-invites')->with([
            'agentInvitationLink' => $link
        ]);
    }
}
