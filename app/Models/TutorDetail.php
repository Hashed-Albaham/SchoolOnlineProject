<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cv_path',
        'is_verified',
        'bio',
        'specialization',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the tutor details
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
