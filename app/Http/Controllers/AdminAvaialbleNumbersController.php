<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\AvailableNumber;

class AdminAvaialbleNumbersController extends Controller
{
    public function index()
    {
        $availableNumbers = AvailableNumber::all();
        
        return Inertia::render('Admin/AvailableNumbers/Index', compact('availableNumbers'));
    }
}
