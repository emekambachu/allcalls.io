<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\User;

class CustomerController extends Controller
{
    function show($id) {
        $transactions = Transaction::where('user_id', $id)->with('card', 'user')
        ->paginate(10);
        $user = User::where('id', $id)->first();
        return Inertia::render('Admin/CustomerDetail', [
            'role' => 'admin',
            'transactions' => $transactions,
            'user' => $user,
        ]);
    }
} 
