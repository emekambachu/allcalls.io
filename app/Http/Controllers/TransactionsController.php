<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()->transactions;

        return Inertia::render('Transactions/Index', compact('transactions'));
    }

    public function destroy(Transaction $transaction)
    {
        if (Auth::user()->id !== $transaction->user_id) {
            return;
        }

        $transaction->delete();

        return redirect()->back()->with([
            'message' => 'Transaction deleted.',
        ]);
    }
}
