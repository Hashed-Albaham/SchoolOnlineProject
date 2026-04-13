<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        // FIXED: Removed 'role' to prevent Mass Assignment privilege escalation
        // Use $user->role = 'admin' explicitly when needed
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
            'agreed_to_terms_at' => 'datetime', // [REQ]
            'is_super_admin' => 'boolean', // [v8.0] NOT in $fillable - set explicitly only
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
     * [v8.0] Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_super_admin === true;
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

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'tutor_id');
    }

    /**
     * Get session slots created by this tutor
     */
    public function sessionSlots(): HasMany
    {
        return $this->hasMany(SessionSlot::class, 'tutor_id');
    }

    /**
     * Get bookings made by this student
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
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
     * Get completed contents for this user
     */
    public function completedContents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CourseContent::class, 'content_progress')
            ->withPivot('completed', 'completed_at')
            ->wherePivot('completed', true)
            ->withTimestamps();
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

    /**
     * Get transactions as a student
     */
    public function studentTransactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'student_id');
    }

    /**
     * Get transactions as a tutor
     */
    public function tutorTransactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'tutor_id');
    }
}
