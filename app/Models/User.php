<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is tutor
     */
    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get tutor details for this user
     */
    public function tutorDetails(): HasOne
    {
        return $this->hasOne(TutorDetail::class);
    }

    /**
     * Get courses created by this tutor
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'tutor_id');
    }

    /**
     * Get enrollments for this student
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get content progress for this user
     */
    public function contentProgress(): HasMany
    {
        return $this->hasMany(ContentProgress::class);
    }

    /**
     * Get course certificates for this user
     */
    public function courseCertificates(): HasMany
    {
        return $this->hasMany(CourseCertificate::class);
    }

    /**
     * Check if user completed a content
     */
    public function hasCompletedContent($contentId): bool
    {
        return $this->contentProgress()
            ->where('course_content_id', $contentId)
            ->where('completed', true)
            ->exists();
    }

    /**
     * Get completed contents count for a course
     */
    public function getCompletedContentsCount($courseId): int
    {
        return $this->contentProgress()
            ->whereHas('courseContent', fn($q) => $q->where('course_id', $courseId))
            ->where('completed', true)
            ->count();
    }
}
