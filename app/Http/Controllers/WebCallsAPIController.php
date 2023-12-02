<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class WebCallsAPIController extends Controller
{
    public function index(Request $request)
    {
        $calls = Call::whereUserId($request->user()->id)->latest()->paginate();

        return ['calls' => $calls];
    }
}
