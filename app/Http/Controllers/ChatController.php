<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
       $users = User::where('id', '!=', auth()->id())
            ->get();
        return view('users',compact('users'));
    }
    public function chat($receiverID)
    {
        // Logic to display the chat with the specified user
    }
    public function sendMessage(Request $request, $receiverID)
    {
        // Logic to send a message to the specified user
    }
    public function typing(Request $request)
    {
        // Logic to handle typing indication
    }
    public function setOnline(Request $request)
    {
        // Logic to set the user as online
    }
    public function setOffline(Request $request)
    {
        // Logic to set the user as offline
    }
}
