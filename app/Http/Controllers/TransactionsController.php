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
        $transactions = Transaction::where('user_id',auth()->user()->id)->latest()->paginate(100);
        $isInternalAgent = false;
        if(auth()->user()->roles->contains('name', 'internal-agent')) {
            $isInternalAgent = true;
        }
        // dd($transactions);
        return Inertia::render('Transactions/Index', [
            'transactions'=>$transactions,
            'isInternalAgent'=>$isInternalAgent
            ]);
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
