<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewMessageNotification;

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
    
        // Create the message
        $message = $user->messages()->create([
            'message' => $request->message,
            'conversation_id' => $request->conversation_id,
        ]);
    
        // Send to Node.js server (Socket.IO)
        //
        //
        //
        //
        //
        Http::post('https://d9bc-94-72-152-229.ngrok-free.app/broadcast', [
            'message' => $message->message,
            'conversation_id' => $message->conversation_id,
            'user_id' => $user->id,
            'created_at' => $message->created_at->toISOString(),
        ]);
    
        // 🔔 Send FCM Notification to the other participant
        $otherUser = DB::table('user_conversation')
            ->where('conversation_id', $request->conversation_id)
            ->where('user_id', '!=', $user->id)
            ->first();
    
        if ($otherUser) {
            $recipient = User::find($otherUser->user_id);
            if ($recipient && $recipient->fcm_token) {
                $recipient->notify(new NewMessageNotification(
                    "New message from {$user->name}",
                    $request->message
                ));
            }
        }
    
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