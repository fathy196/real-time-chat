<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->get();
        return view('users', compact('users'));
    }
    public function chat($receiverID)
    {
        $receiver = User::findOrFail($receiverID);
        $messages = Message::where(function ($query) use ($receiverID) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $receiverID);
        })->orWhere(function ($query) use ($receiverID) {
            $query->where('sender_id', $receiverID)
                ->where('receiver_id', auth()->id());
        })->get();
        return view('chat', compact('receiver', 'messages'));
    }
    public function sendMessage(Request $request, $receiverID)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverID,
            'message' => $request->input('message'),
        ]);

        // Broadcast the message to other users 
        broadcast(new MessageSent($message))->toOthers();

        // return redirect()->route('chat', ['receiverID' => $receiverID]);
        return response()->json(['status' => 'Message sent successfully']);
    }
    public function typing(Request $request)
    {
        broadcast(new UserTyping(Auth::id()))->toOthers();
        return response()->json(['status' => 'Typing...']);
    }
    public function setOnline()
    {
        Cache::put('user-is-online-' . Auth::id(), true, now()->addMinutes(5));
        return response()->json(['status' => 'Online']);
    }

    public function setOffline()
    {
        Cache::forget('user-is-online-' . Auth::id());
        return response()->json(['status' => 'Offline']);
    }
}
