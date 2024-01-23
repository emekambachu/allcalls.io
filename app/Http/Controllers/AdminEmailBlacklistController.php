<?php

namespace App\Http\Controllers;

use App\Models\EmailBlacklist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEmailBlacklistController extends Controller
{
    public function index()
    {
        $emails = EmailBlacklist::all();
        return Inertia::render('Admin/EmailBlacklist/Index');
    }
}
