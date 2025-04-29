<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function saveToken(Request $request)
{
    $request->user()->update([
        'fcm_token' => $request->token,
    ]);

    return response()->json(['message' => 'Token saved']);
}
}
