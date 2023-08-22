<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index() {
        $users = User::paginate(10);

              return Inertia::render('Admin/User/Index', [
                  'users' => $users,
              ]);
    }
}
