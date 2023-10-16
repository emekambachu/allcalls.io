<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AgentStatusPriceDocsController extends Controller
{
    public function show()
    {
        return Inertia::render('Docs/AgentStatusPrice/Show');
    }
}
