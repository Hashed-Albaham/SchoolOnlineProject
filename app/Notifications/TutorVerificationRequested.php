<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TutorVerificationRequested extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tutor;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $tutor)
    {
        $this->tutor = $tutor;
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
            'title' => 'طلب توثيق جديد من معلم',
            'message' => "طلب المعلم {$this->tutor->name} مراجعة ملفه الشخصي للتوثيق.",
            'link' => route('admin.tutors.show', $this->tutor->id),
            'type' => 'tutor_verification_request',
            'icon' => 'heroicon-o-shield-check',
        ];
    }
}
