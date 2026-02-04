<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsDropdown extends Component
{
    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()->take(10)->get();
    }

    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications->count();
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notificationsMarkedAsRead');
    }

    public function render()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->take(10)->get();
        $notificationsUnreadCount = $user->unreadNotifications->count();

        // Get Unread Messages Count (distinct senders)
        $unreadMessagesCount = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Total Badge Count
        $totalUnread = $notificationsUnreadCount + $unreadMessagesCount;

        return view('livewire.notifications-dropdown', [
            'notifications' => $notifications,
            'unreadCount' => $totalUnread,
            'unreadMessagesCount' => $unreadMessagesCount,
        ]);
    }
}
