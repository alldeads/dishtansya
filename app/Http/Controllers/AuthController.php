<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;

use App\Models\User;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'User successfully registered!'
        ], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ( auth()->attempt($credentials) ) {
            $user = auth()->user();

            $tokenResult = $user->createToken('Login token');
            $token = $tokenResult->token;

            $token->save();

            return response()->json([
                'message' => 'Successfully logged in.',
                'access_token' => $tokenResult->accessToken,
            ], 201);
        }

        return response()->json([
            'message' => 'Invalid credentials.'
        ], 400);
    }
}
