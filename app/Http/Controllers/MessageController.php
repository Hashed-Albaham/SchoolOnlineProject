<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $userId = Auth::id();

        $searchQuery = request()->query('search');
        $isSearch = false;

        if ($searchQuery) {
            // FIXED: Sanitize search query to prevent SQL wildcard injection
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->limit(50) // FIXED: Add limit to prevent DoS
                ->get();
            $isSearch = true;
        } else {
            // Get list of users the current user has exchanged messages with
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');

            $contactIDs = $sentIDs->merge($receivedIDs)->unique();
            $contacts = User::whereIn('id', $contactIDs)->get();
        }

        return view('messages.index', compact('contacts', 'searchQuery', 'isSearch'));
    }

    /**
     * Show chat with a specific user.
     */
    public function show(User $user)
    {
        $userId = Auth::id();

        $searchQuery = request()->query('search');
        $isSearch = false; // Usually false when viewing a specific chat, but could be adapted

        if ($searchQuery) {
            // FIXED: Sanitize search query to prevent SQL wildcard injection
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->limit(50) // FIXED: Add limit to prevent DoS
                ->get();
            $isSearch = true;
        } else {
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');
            $contactIDs = $sentIDs->merge($receivedIDs)->unique();
            $contacts = User::whereIn('id', $contactIDs)->get();

            // Ensure the currently viewed user is in the contacts list
            if (!$contacts->contains('id', $user->id)) {
                $contacts->prepend($user);
            }
        }

        return view('messages.index', compact('contacts', 'user', 'searchQuery', 'isSearch'));
    }
}
