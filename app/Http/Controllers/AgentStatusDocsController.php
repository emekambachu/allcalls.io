<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AgentStatusDocsController extends Controller
{
    public function show()
    {
        return Inertia::render('Docs/AgentStatus/Show');
    }
}
