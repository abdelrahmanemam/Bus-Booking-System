<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{

    public function create($request): User
    {
        return User::create($request);
    }
}
