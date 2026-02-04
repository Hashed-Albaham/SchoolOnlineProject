<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'type',
        'youtube_video_id',
        'file_path',
        'link_url',
        'quiz_id',
        'text_content',
        'order',
        'description',
    ];

    /**
     * Content type constants
     */
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'file';
    const TYPE_IMAGE = 'image';
    const TYPE_TEXT = 'text';
    const TYPE_QUIZ = 'quiz';
    const TYPE_LINK = 'link';

    /**
     * Get the course this content belongs to
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the quiz associated with this content
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Extract YouTube video ID from URL
     */
    public static function extractYoutubeId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        // If it's already just an ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Get the embed URL for the video
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->type === self::TYPE_VIDEO && $this->youtube_video_id) {
            return "https://www.youtube.com/embed/{$this->youtube_video_id}";
        }
        return null;
    }

    /**
     * Check if content is video type
     */
    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }

    /**
     * Check if content is file type
     */
    public function isFile(): bool
    {
        return $this->type === self::TYPE_FILE;
    }

    /**
     * Check if content is image type
     */
    public function isImage(): bool
    {
        return $this->type === self::TYPE_IMAGE;
    }

    /**
     * Check if content is text type
     */
    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    /**
     * Check if content is quiz type
     */
    public function isQuiz(): bool
    {
        return $this->type === self::TYPE_QUIZ;
    }

    /**
     * Check if content is link type
     */
    public function isLink(): bool
    {
        return $this->type === self::TYPE_LINK;
    }

    /**
     * Get type label in Arabic
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_VIDEO => 'فيديو',
            self::TYPE_FILE => 'ملف',
            self::TYPE_IMAGE => 'صورة',
            self::TYPE_TEXT => 'نص/ملاحظات',
            self::TYPE_QUIZ => 'اختبار',
            self::TYPE_LINK => 'رابط خارجي',
            default => 'غير معروف',
        };
    }
}
