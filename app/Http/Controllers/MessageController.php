<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Get unread message counts per sender for the current user
     */
    private function getUnreadCounts($userId)
    {
        return Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');
    }

    /**
     * Get last message for each contact
     */
    private function getLastMessages($userId, $contactIds)
    {
        $lastMessages = [];
        foreach ($contactIds as $contactId) {
            $lastMessage = Message::where(function ($q) use ($userId, $contactId) {
                $q->where('sender_id', $userId)->where('receiver_id', $contactId);
            })->orWhere(function ($q) use ($userId, $contactId) {
                $q->where('sender_id', $contactId)->where('receiver_id', $userId);
            })->latest()->first();

            if ($lastMessage) {
                $lastMessages[$contactId] = $lastMessage;
            }
        }
        return $lastMessages;
    }

    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $userId = Auth::id();

        $searchQuery = request()->query('search');
        $isSearch = false;

        if ($searchQuery) {
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->limit(50)
                ->get();
            $isSearch = true;
        } else {
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');

            $contactIDs = $sentIDs->merge($receivedIDs)->unique();
            $contacts = User::whereIn('id', $contactIDs)->get();
        }

        $unreadCounts = $this->getUnreadCounts($userId);
        $lastMessages = $this->getLastMessages($userId, $contacts->pluck('id'));

        // Sort contacts: those with unread messages first, then by last message time
        $contacts = $contacts->sortByDesc(function ($contact) use ($unreadCounts, $lastMessages) {
            $hasUnread = $unreadCounts->get($contact->id, 0) > 0 ? 1 : 0;
            $lastTime = isset($lastMessages[$contact->id]) ? $lastMessages[$contact->id]->created_at->timestamp : 0;
            return $hasUnread * 10000000000 + $lastTime;
        })->values();

        return view('messages.index', compact('contacts', 'searchQuery', 'isSearch', 'unreadCounts', 'lastMessages'));
    }

    /**
     * Show chat with a specific user.
     */
    public function show(User $user)
    {
        $userId = Auth::id();

        // Mark messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $searchQuery = request()->query('search');
        $isSearch = false;

        if ($searchQuery) {
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->limit(50)
                ->get();
            $isSearch = true;
        } else {
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');
            $contactIDs = $sentIDs->merge($receivedIDs)->unique();
            $contacts = User::whereIn('id', $contactIDs)->get();

            if (!$contacts->contains('id', $user->id)) {
                $contacts->prepend($user);
            }
        }

        $unreadCounts = $this->getUnreadCounts($userId);
        $lastMessages = $this->getLastMessages($userId, $contacts->pluck('id'));

        $contacts = $contacts->sortByDesc(function ($contact) use ($unreadCounts, $lastMessages) {
            $hasUnread = $unreadCounts->get($contact->id, 0) > 0 ? 1 : 0;
            $lastTime = isset($lastMessages[$contact->id]) ? $lastMessages[$contact->id]->created_at->timestamp : 0;
            return $hasUnread * 10000000000 + $lastTime;
        })->values();

        return view('messages.index', compact('contacts', 'user', 'searchQuery', 'isSearch', 'unreadCounts', 'lastMessages'));
    }
}

