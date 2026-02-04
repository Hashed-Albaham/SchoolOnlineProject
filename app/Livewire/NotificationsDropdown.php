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
        return view('livewire.notifications-dropdown', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
