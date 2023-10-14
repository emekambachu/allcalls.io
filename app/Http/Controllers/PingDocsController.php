<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PingDocsController extends Controller
{
    public function show()
    {
        return Inertia::render('Docs/Ping/Show');
    }
}
