<?php

namespace App\Notifications;

use App\Models\CourseCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateIssued extends Notification implements ShouldQueue
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
            'type' => 'certificate_issued',
            'title' => 'مبروك! تم إصدار شهادتك',
            'message' => 'تم إصدار شهادة الكورس: ' . $this->certificate->course->title,
            'link' => route('student.certificates'),
            'icon' => 'certificate', // standardized icon name
            'color' => 'gold', // standardized color
        ];
    }
}
