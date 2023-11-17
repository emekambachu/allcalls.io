<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Bid;
use App\Models\Call;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Client;
use App\Models\Activity;
use App\Models\CallType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CallsController extends Controller
{
    public function index(Request $request)
    {
        $calls = Call::with('user.roles','getClient','callType')->orderBy("created_at","DESC")->paginate(10);

        return Inertia::render('Admin/Calls/Index', [
            'requestData' => $request->all(),
            'calls' => $calls
        ]);
    }
}
