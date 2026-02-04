<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewEnrollment extends Notification implements ShouldQueue
{
    use Queueable;

    public $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_enrollment',
            'title' => 'تسجيل جديد في الكورس',
            'message' => 'قام طالب بالتسجيل في كورس: ' . $this->enrollment->course->title,
            'link' => route('tutor.courses.show', $this->enrollment->course_id), // Or tutor dashboard
            'icon' => 'enrollment', // Helper in view to pick generic icon
            'color' => 'green',
            'user_id' => $this->enrollment->user_id,
        ];
    }
}
