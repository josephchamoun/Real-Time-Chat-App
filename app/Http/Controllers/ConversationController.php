<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function getOrCreate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Get authenticated user (or default to user 1 during development)
        $authUser = auth()->user() ?? User::find(1);
        $otherUserId = $request->user_id;
        
        try {
            // Find conversations where both users have sent messages
            $conversationIds = Message::where('user_id', $authUser->id)
                ->pluck('conversation_id')
                ->unique();
                
            $sharedConversations = Message::whereIn('conversation_id', $conversationIds)
                ->where('user_id', $otherUserId)
                ->pluck('conversation_id')
                ->unique();
            
            // Check if we have a direct conversation (only 2 participants)
            $directConversation = null;
            
            foreach ($sharedConversations as $convId) {
                $uniqueParticipants = Message::where('conversation_id', $convId)
                    ->distinct('user_id')
                    ->pluck('user_id');
                
                if ($uniqueParticipants->count() === 2) {
                    $directConversation = Conversation::find($convId);
                    break;
                }
            }
            
            // If no direct conversation exists, create one
            if (!$directConversation) {
                DB::beginTransaction();
                
                $directConversation = new Conversation();
                $directConversation->name = null; // null name indicates a direct chat
                $directConversation->save();
                
                // We don't need to create a welcome message, but we could:
                // $message = new Message([
                //     'user_id' => $authUser->id,
                //     'conversation_id' => $directConversation->id,
                //     'message' => 'Started a conversation'
                // ]);
                // $message->save();
                
                DB::commit();
            }
            
            return response()->json([
                'conversation' => $directConversation,
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Conversation creation error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Failed to create conversation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
