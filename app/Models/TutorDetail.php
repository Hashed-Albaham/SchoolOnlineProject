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
        // [REQ] Qualification fields
        'university',
        'graduation_year',
        'degree_certificate_path',
        'skills',
        'portfolio_url',
        'agreed_to_terms',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'agreed_to_terms' => 'boolean',
        'graduation_year' => 'integer',
    ];

    /**
     * Get the user that owns the tutor details
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
