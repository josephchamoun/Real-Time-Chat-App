<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //list of users
    function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
            'message' => 'Users retrieved successfully'
        ], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }
    
}

