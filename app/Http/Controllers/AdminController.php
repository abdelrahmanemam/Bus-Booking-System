<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\AdminInterface;
use App\Http\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminController extends Controller
{
    private AdminRepository $adminInterface;

    public function __construct(AdminInterface $adminInterface)
    {
        $this->adminInterface = $adminInterface;
    }

    public function register(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), ResponseAlias::HTTP_FORBIDDEN);
        }

        $request['password'] = bcrypt($request->password);

        $admin = $this->adminInterface->create($request->toArray());

        $token = $admin->createToken('Admin Token')->accessToken;

        return response(['admin' => $admin, 'token' => $token], ResponseAlias::HTTP_CREATED);
    }

    public function login(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!auth()->attempt($request->toArray())) {
            return response(['error_message' => 'Incorrect Details.
            Please try again'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = auth()->admin()->createToken('Admin Token')->accessToken;

        return response(['token' => $token]);

    }
}
