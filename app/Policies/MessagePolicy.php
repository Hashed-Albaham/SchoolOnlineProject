<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine whether the user can view the message.
     */
    public function view(User $user, Message $message): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $message->sender_id || $user->id === $message->receiver_id;
    }

    /**
     * Determine whether the user can delete the message.
     */
    public function delete(User $user, Message $message): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $message->sender_id || $user->id === $message->receiver_id;
    }
}
