<?php

namespace App\Notifications;

use App\Models\CourseCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CertificateRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public $certificate;

    /**
     * Create a new notification instance.
     */
    public function __construct(CourseCertificate $certificate)
    {
        $this->certificate = $certificate;
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
            'type' => 'certificate_request',
            'title' => 'طلب شهادة جديد',
            'message' => 'قام طالب بطلب شهادة للكورس: ' . $this->certificate->course->title,
            'link' => route('tutor.courses.show', $this->certificate->course_id), // Placeholder, ideally specific request management page
            'icon' => 'certificate',
            'color' => 'gold',
            'user_id' => $this->certificate->user_id,
        ];
    }
}
