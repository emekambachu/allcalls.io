<?php

namespace App\Http\Controllers;

use App\Events\AppCallStatusUpdated;
use Illuminate\Http\Request;

class AppEventsController extends Controller
{
    public function store(Request $request)
    {
        AppCallStatusUpdated::dispatch($request->user());

        return [
            'message' => 'Event dispatched'
        ];
    }
}
