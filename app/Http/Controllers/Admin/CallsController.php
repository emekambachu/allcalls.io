<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Call;
use App\Models\CallType;
use App\Models\Role;
use App\Models\State;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Bid;
use App\Models\Client;

class CallsController extends Controller
{
    public function index(Request $request)
    {
        $calls = Call::with('user','getClient','callType')->orderBy("created_at","DESC")->paginate(10);
        return Inertia::render('Admin/Calls/Index', [
            'requestData' => $request->all(),
            'calls' => $calls
        ]);
    }
}
