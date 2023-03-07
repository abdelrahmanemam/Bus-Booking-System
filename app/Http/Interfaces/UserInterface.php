<?php

namespace App\Http\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserInterface
{
    public function create(Request $request): User;
}
