<?php

namespace App\Services\User;

use App\Models\User;

class UserService
{
    public function user(): User
    {
        return new User();
    }
}
