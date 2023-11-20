<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InternalAgentExportController extends Controller
{
    public function export(User $user)
    {
        Log::debug('Exporting agent', ['user' => $user->id, 'firstName' => $user->first_name]);

        return redirect()->back();
    }
}
