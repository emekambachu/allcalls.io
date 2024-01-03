<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PromotionGuidelinesController extends Controller
{
    public function show()
    {
        return Inertia::render('PromotionGuidelines/Show');
    }
}
