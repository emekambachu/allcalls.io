<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ActiveUserChannelController extends Controller
{
    public function join()
    {
        return Inertia::render('ActiveUserChannel/Show');
    }
}
