<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $fields = $request->validate([
            'username' => 'required|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if(!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong username or password'
            ], 401);
        }

        $token = $user->createToken($request->username);

        return response()->json([
            'message' => 'Login success',
            'token' => $token->plainTextToken,
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'created_at' => $user->created_at
            ]
        ], 200);

    }

    public function logout(Request $request)
    {
       $request->user()->tokens()->delete();

       return response()->json([
            'message' => 'Logout success'
       ], 200);
    }
}