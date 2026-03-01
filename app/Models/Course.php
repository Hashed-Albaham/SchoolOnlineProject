<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'title',
        'description',
        'price',
        'thumbnail',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the tutor who created this course
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the contents/lessons of this course
     */
    public function contents(): HasMany
    {
        return $this->hasMany(CourseContent::class)->orderBy('order');
    }

    /**
     * Get the enrollments for this course
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Check if course is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if course is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get students count enrolled in this course
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->enrollments()->where('payment_status', 'paid')->count();
    }

    /**
     * Get reviews for this course
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get quizzes for this course
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get certificates for this course
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(CourseCertificate::class);
    }

    /**
     * Get average rating for this course
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}

