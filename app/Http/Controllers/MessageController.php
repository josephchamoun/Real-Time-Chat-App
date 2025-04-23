<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $user = auth()->user() ?? User::find(1); // Fallback for development
        
        // Verify user belongs to this conversation
        $isParticipant = DB::table('user_conversation')
            ->where('user_id', $user->id)
            ->where('conversation_id', $request->conversation_id)
            ->exists();
            
        if (!$isParticipant) {
            return response()->json([
                'message' => 'You are not authorized to post in this conversation'
            ], 403);
        }

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
    $user = auth()->user();
    
    // For debugging
    \Log::info('User trying to access conversation', [
        'user_id' => $user->id,
        'conversation_id' => $conversation_id
    ]);
    
    // Check if this user is part of the conversation using pivot table
    $participantCheck = DB::table('user_conversation')
        ->where('user_id', $user->id)
        ->where('conversation_id', $conversation_id)
        ->first();
    
    // For debugging
    \Log::info('Participant check result', [
        'found' => !is_null($participantCheck),
        'record' => $participantCheck
    ]);
    
    if (!$participantCheck) {
        // For debug - show all participants in this conversation
        $allParticipants = DB::table('user_conversation')
            ->where('conversation_id', $conversation_id)
            ->get();
            
        \Log::info('All participants in conversation', [
            'conversation_id' => $conversation_id,
            'participants' => $allParticipants
        ]);
        
        return response()->json([
            'message' => 'You are not authorized to view this conversation',
            'debug_info' => [
                'user_id' => $user->id,
                'conversation_id' => $conversation_id
            ]
        ], 403);
    }
    
    // Get all messages for this conversation
    $messages = Message::where('conversation_id', $conversation_id)
                     ->with('user:id,name')
                     ->orderBy('created_at')
                     ->get();
    
    return response()->json([
        'messages' => $messages,
    ], 200);
}
}