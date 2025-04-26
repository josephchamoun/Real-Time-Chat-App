<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Attempt to authenticate the user
        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->plainTextToken;
    
            // Return a successful response with user details and token
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 200);
        }
    
        // Return an error response for invalid credentials
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }
}
