<?php

namespace App\Http\Controllers;

use App\Jobs\EquisAPIJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EquisAPIController extends Controller
{
    public function show()
    {
        EquisAPIJob::dispatch();

        return 'Job dispatched!';
    }
}
