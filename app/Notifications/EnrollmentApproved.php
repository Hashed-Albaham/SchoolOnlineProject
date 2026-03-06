<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * [E1] Notification sent to student when enrollment is approved
 */
class EnrollmentApproved extends Notification
{
    use Queueable;

    public function __construct(public Enrollment $enrollment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'enrollment_approved',
            'message' => 'تمت الموافقة على طلب انضمامك للكورس: ' . ($this->enrollment->course?->title ?? ''),
            'course_id' => $this->enrollment->course_id,
            'enrollment_id' => $this->enrollment->id,
        ];
    }
}
