<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'conversation_id' => 'required|exists:conversations,id',
    ]);

    $user = auth()->user();

    // Create the message in the database
    $message = $user->messages()->create([
        'message' => $request->message,
        'conversation_id' => $request->conversation_id,
    ]);

    // Send the message to Node.js server
    Http::post('http://localhost:3000/broadcast', [
        'message' => $message->message,
        'conversation_id' => $message->conversation_id,
        'user_id' => $user->id,
        'created_at' => $message->created_at->toISOString(),
    ]);

    return response()->json([
        'message' => 'Message sent successfully',
        'data' => $message,
    ], 201);
}
    
    
    
public function getMessages($conversation_id)
{
    // Get the authenticated user
    $user = auth()->user() ?? User::find(1); // Fallback for development
    
    // Find the conversation
    $conversation = Conversation::findOrFail($conversation_id);
    
    // Check if this user is part of the conversation
    $isParticipant = Message::where('conversation_id', $conversation_id)
                          ->where('user_id', $user->id)
                          ->exists();
    
    // Alternative check: fetch all unique users in this conversation
    $participantIds = Message::where('conversation_id', $conversation_id)
                           ->distinct('user_id')
                           ->pluck('user_id')
                           ->toArray();
    
    // If the user hasn't sent any messages yet but is trying to access a valid conversation
    // We'll allow it for new conversations
    if (!$isParticipant && !in_array($user->id, $participantIds) && count($participantIds) >= 2) {
        return response()->json([
            'message' => 'You are not authorized to view this conversation'
        ], 403);
    }
    
    // Get all messages for this conversation
    $messages = Message::where('conversation_id', $conversation_id)
                     ->with('user:id,name') // Include only necessary user info
                     ->orderBy('created_at')
                     ->get();
    
    return response()->json([
        'messages' => $messages,
    ], 200);
}
    
}