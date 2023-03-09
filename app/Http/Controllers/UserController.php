<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\UserInterface;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    private UserInterface $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
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

        $user = $this->userInterface->create($request->toArray());

        $token = $user->createToken('User Token', ['user'])->accessToken;

        return response(['user' => $user, 'token' => $token], ResponseAlias::HTTP_CREATED);
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

        $token = auth()->user()->createToken('User Token', ['user'])->accessToken;

        return response(['token' => $token]);

    }
}
