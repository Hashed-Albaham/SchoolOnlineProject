<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Determine whether the user can manage the quiz (update/delete/manage questions).
     */
    public function manage(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'tutor') {
            return $user->id === $quiz->course->tutor_id;
        }

        return false;
    }
}
