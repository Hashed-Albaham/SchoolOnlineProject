<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CourseStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $course;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Course $course, string $status)
    {
        $this->course = $course;
        $this->status = $status;
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
        $statusText = $this->status === 'approved' ? 'الموافقة على' : 'رفض';
        $message = $this->status === 'approved'
            ? "تمت الموافقة على كورس '{$this->course->title}' وهو الآن متاح للطلاب."
            : "تم رفض نشر كورس '{$this->course->title}'. يرجى مراجعة الملاحظات.";

        return [
            'title' => "تم {$statusText} الكورس الخاص بك",
            'message' => $message,
            'link' => route('tutor.courses.edit', $this->course->id),
            'type' => $this->status === 'approved' ? 'course_approved' : 'course_rejected',
            'icon' => $this->status === 'approved' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle',
        ];
    }
}
