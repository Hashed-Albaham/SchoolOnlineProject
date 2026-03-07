<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can view the enrollment.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'tutor') {
            // Tutor can view enrollments for their own courses
            return $user->id === $enrollment->course->tutor_id;
        }

        if ($user->role === 'student') {
            // Student can only view their own enrollments
            return $user->id === $enrollment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the enrollment (e.g. status).
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'tutor') {
            return $user->id === $enrollment->course->tutor_id;
        }

        return false;
    }
}
