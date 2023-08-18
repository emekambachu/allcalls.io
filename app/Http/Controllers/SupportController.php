<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
class SupportController extends Controller
{
    public function index()
    {
        if(Auth::check()){

            return Inertia::render('Support/Index');
        }else{
            // dd('asdas');
            return Inertia::render('front/Support/index',[
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        }
    }
    
}
