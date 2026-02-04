<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $receiverId;
    public $courseId;
    public $newMessage = '';
    public $messages = [];

    protected $rules = [
        'newMessage' => 'required|string|max:1000',
    ];

    public function mount($receiverId = null, $courseId = null)
    {
        $this->receiverId = $receiverId;
        $this->courseId = $courseId;
        $this->loadMessages();
    }

    /**
     * Load messages between current user and receiver
     */
    public function loadMessages()
    {
        if (!$this->receiverId) {
            return;
        }

        $userId = Auth::id();

        $this->messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $this->receiverId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $this->receiverId)
                ->where('receiver_id', $userId);
        })
            ->when($this->courseId, fn($q) => $q->where('course_id', $this->courseId))
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get()
            ->toArray();

        // Mark unread messages as read
        Message::where('sender_id', $this->receiverId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Send a new message
     */
    public function sendMessage()
    {
        $this->validate();

        if (!$this->receiverId) {
            return;
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->receiverId,
            'course_id' => $this->courseId,
            'content' => $this->newMessage,
        ]);

        // Send Notification
        $receiver = User::find($this->receiverId);
        if ($receiver) {
            $receiver->notify(new \App\Notifications\MessageSent($message));
        }

        // Optimistically append message to local state
        // Ensure is_read is set so the view doesn't crash
        $message->is_read = false;
        $this->messages[] = $message->load('sender')->toArray();

        $this->dispatch('messageSent');

        $this->newMessage = '';

        // Remove loadMessages to avoid re-fetching immediately
        // $this->loadMessages();
    }

    /**
     * Get receiver user
     */
    public function getReceiverProperty()
    {
        return $this->receiverId ? User::find($this->receiverId) : null;
    }

    /**
     * Polling method - called every 3 seconds
     */
    public function poll()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat-box', [
            'receiver' => $this->receiver,
        ]);
    }
}
