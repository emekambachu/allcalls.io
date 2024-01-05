<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class FEAgentStatusDocsController extends Controller
{
    public function show()
    {
        return Inertia::render('Docs/Ping/Show', [
            'domain' => 'https://feagentping.com',
            'brandName' => 'FEAgentPing',
        ]);
    }
}
