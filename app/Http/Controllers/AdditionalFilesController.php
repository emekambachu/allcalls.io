<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AdditionalFilesController extends Controller
{
    public function index()
    {
        return Inertia::render('AdditionalFiles/Index');
    }

    public function store(Request $request)
    {
        dd($request->file);
    }
}
