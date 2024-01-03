<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PromotionGuidelinesController extends Controller
{
    public function show(Request $request)
    {
        $level = $request->user()->getAgentLevel;

        dd($level->name);

        return Inertia::render('PromotionGuidelines/Show');
    }
}
