<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AdminInterface;
use App\Models\Admin;

class AdminRepository implements AdminInterface
{
    public function create($request): Admin
    {
        return Admin::create($request);
    }
}
