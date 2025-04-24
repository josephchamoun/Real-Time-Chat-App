<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function getOrCreate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Get authenticated user (or default to user 1 during development)
        $authUser = auth()->user();
        $otherUserId = $request->user_id;
        
        try {
            // Check if these two users already share a direct conversation
            $sharedConversationId = DB::table('user_conversation')
                ->whereIn('user_id', [$authUser->id, $otherUserId])
                ->groupBy('conversation_id')
                ->havingRaw('COUNT(DISTINCT user_id) = 2')
                ->having(DB::raw('COUNT(*)'), '=', 2)
                ->pluck('conversation_id')
                ->first();
            
            if ($sharedConversationId) {
                // Conversation exists, get it
                $conversation = Conversation::find($sharedConversationId);
            } else {
                // No conversation exists, create one
                DB::beginTransaction();
                
                $conversation = new Conversation();
                $conversation->name = null; // null name indicates a direct chat
                $conversation->save();
                
                // Add both users to the pivot table
                DB::table('user_conversation')->insert([
                    ['user_id' => $authUser->id, 'conversation_id' => $conversation->id],
                    ['user_id' => $otherUserId, 'conversation_id' => $conversation->id]
                ]);
                
                DB::commit();
            }
            
            return response()->json([
                'conversation' => $conversation,
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