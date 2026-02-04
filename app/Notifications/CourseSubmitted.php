<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CourseSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $course;

    /**
     * Create a new notification instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
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
            'title' => 'تم تقديم كورس جديد للمراجعة',
            'message' => "قام المعلم {$this->course->tutor->name} بتقديم كورس '{$this->course->title}' للمراجعة.",
            'link' => route('admin.courses.show', $this->course->id),
            'type' => 'course_submission',
            'icon' => 'heroicon-o-book-open',
        ];
    }
}
