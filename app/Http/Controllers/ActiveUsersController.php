<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ActiveUsersController extends Controller
{
    public function index()
    {
        return Inertia::render('ActiveUsers/Index');
    }
}
