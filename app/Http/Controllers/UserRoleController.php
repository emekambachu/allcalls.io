<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserRoleController extends Controller
{
        /**
     * Display a listing of the user roles.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getUserRoles($userId)
    {
        $user = User::with('roles')->find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user->roles);
    }
}
