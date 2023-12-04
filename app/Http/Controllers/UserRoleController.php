<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserRoleController extends Controller
{
    /**
     * Display a listing of the roles of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserRoles()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        return response()->json($user->roles);
    }
}
