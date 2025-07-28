<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'full_name' => 'required|max:255',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->username);

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        //
    }

    public function logout(Request $request)
    {
        return 'logout';
    }
}
