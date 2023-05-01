<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Transactions/Index');
    }
}
