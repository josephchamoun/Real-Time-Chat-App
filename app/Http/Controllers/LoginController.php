<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    function login(Request $request)
    {
        //api login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }
    }
}
