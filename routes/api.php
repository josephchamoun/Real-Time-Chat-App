<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConversationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/users', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/send-message', [MessageController::class, 'sendMessage']);
Route::get('/messages/{conversation_id}', [MessageController::class, 'getMessages']);
Route::middleware('auth:sanctum')->post('/conversations', [ConversationController::class, 'getOrCreate']);
Route::get('/users/{id}', [UserController::class, 'show']);



