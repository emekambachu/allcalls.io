<?php

namespace App\Http\Controllers;

use App\Models\EmailBlacklist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEmailBlacklistController extends Controller
{
    public function index()
    {
        $blacklist = EmailBlacklist::paginate(100);

        return Inertia::render('Admin/EmailBlacklist/Index', [
            'blacklist' => $blacklist
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_blacklists'
        ]);

        EmailBlacklist::create([
            'email' => $request->email
        ]);

        return redirect()->back()->with('message', 'Email has been added to the blacklist.');
    }

    public function destroy(EmailBlacklist $emailBlacklist)
    {
        $emailBlacklist->delete();

        return redirect()->back()->with('message', 'Email has been removed from the blacklist.');
    }
}
