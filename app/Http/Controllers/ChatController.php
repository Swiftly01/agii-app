<?php

// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
   
    // Main chat page
   
    
    
    // Add these methods to your ChatController

// AJAX: Send message
public function sendMessageAjax(Request $request, $conversationId)
{
    $request->validate([
        'message' => 'required|string|max:1000'
    ]);
    
    $conversation = Conversation::findOrFail($conversationId);
    
    if (!$conversation->participants->contains(Auth::id())) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $message = Message::create([
        'conversation_id' => $conversationId,
        'user_id' => Auth::id(),
        'body' => $request->message
    ]);
    
    // Load user relationship for response
    $message->load('user');
    
    return response()->json([
        'success' => true,
        'message' => $message
    ]);
}

// AJAX: Get new messages
public function getNewMessages($conversationId)
{
    $conversation = Conversation::findOrFail($conversationId);
    
    if (!$conversation->participants->contains(Auth::id())) {
        abort(403);
    }
    
    $lastMessageId = request('last_message_id', 0);
    
    $messages = $conversation->messages()
        ->with('user')
        ->where('id', '>', $lastMessageId)
        ->where('user_id', '!=', Auth::id()) // Don't return user's own messages
        ->get();
    
    return response()->json($messages);
}

// Update index method to include unread counts
public function index()
{
    $conversations = Auth::user()->conversations()
        ->with(['participants', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->get()
        ->map(function($conversation) {
            // Add unread count
            $conversation->unread_count = $conversation->messages()
                ->where('user_id', '!=', Auth::id())
                ->where('created_at', '>', $conversation->participants
                    ->where('id', Auth::id())
                    ->whereIn('user_type', ['marketer', 'admin'])
                    ->first()
                    ->pivot->last_read_at ?? now()->subYear())
                ->count();
            
            return $conversation;
        });
    
    // $users = User::where('id', '!=', Auth::id())->get();
    
    $users = User::where('id', '!=', Auth::id())
    ->whereIn('user_type', ['marketer', 'admin'])
    ->get();
    
    return view('chat.index', compact('conversations', 'users'));
}


//  public function index()
//     {
//         $conversations = Auth::user()->conversations()
//             ->with(['participants', 'messages' => function($query) {
//                 $query->latest()->limit(1);
//             }])
//             ->get();
//         $users = User::where('id', '!=', Auth::id())
             
//              ->get();

        
//         return view('chat.index', compact('conversations', 'users'));
//     }
    


    // Start or get private conversation
    public function startConversation($userId)
    {
        $otherUser = User::findOrFail($userId);
        
        // Check if conversation already exists
        $conversation = Auth::user()->conversations()
            ->whereHas('participants', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('type', 'private')
            ->first();
        
        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->participants()->attach([Auth::id(), $userId]);
        }
        
        return redirect()->route('chat.conversation', $conversation->id);
    }
    
    // View conversation
    public function conversation($id)
    {
        $conversation = Conversation::with(['participants', 'messages.user'])
            ->findOrFail($id);
        
        // Check if user is participant
        if (!$conversation->participants->contains(Auth::id())) {
            abort(403);
        }
        
        return view('chat.conversation', compact('conversation'));
    }
    
    // Send message
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user is participant
        if (!$conversation->participants->contains(Auth::id())) {
            abort(403);
        }
        
        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id' => Auth::id(),
            'body' => $request->message
        ]);
        
        return redirect()->back()->with('success', 'Message sent!');
    }
    
    // Delete message
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        
        if ($message->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own messages');
        }
        
        $message->delete();
        
        return redirect()->back()->with('success', 'Message deleted!');
    }
    
    // Create group
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id'
        ]);
        
        $conversation = Conversation::create([
            'name' => $request->name,
            'type' => 'group'
        ]);
        
        // Add members including current user
        $members = array_merge([Auth::id()], $request->members);
        $conversation->participants()->attach($members);
        
        return redirect()->route('chat.conversation', $conversation->id)
            ->with('success', 'Group created!');
    }
    
    // Get new messages (for AJAX updates)
    // public function getNewMessages($conversationId)
    // {
    //     $conversation = Conversation::findOrFail($conversationId);
        
    //     if (!$conversation->participants->contains(Auth::id())) {
    //         abort(403);
    //     }
        
    //     $lastMessageId = request('last_message_id', 0);
        
    //     $messages = $conversation->messages()
    //         ->with('user')
    //         ->where('id', '>', $lastMessageId)
    //         ->get();
        
    //     return response()->json($messages);
    // }
}