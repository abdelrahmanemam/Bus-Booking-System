<?php

namespace App\Http\Interfaces;

use App\Models\Admin;
use Illuminate\Http\Request;

interface AdminInterface
{
    public function create(Request $request): Admin;
}
