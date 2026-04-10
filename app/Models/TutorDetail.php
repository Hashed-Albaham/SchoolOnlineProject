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
        // [v8.0] Eligibility fields
        'gpa',
        'gpa_scale',
        'step_score',
        // [v8.0] Historical fairness - requirements at time of registration
        'req_gpa_at_registration',
        'req_step_at_registration',
        'available_balance',
        'pending_balance',
        'total_earned',
        'total_withdrawn',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'agreed_to_terms' => 'boolean',
        'graduation_year' => 'integer',
        // [v8.0]
        'gpa' => 'decimal:2',
        'gpa_scale' => 'decimal:1',
        'step_score' => 'integer',
        'req_gpa_at_registration' => 'decimal:2',
        'req_step_at_registration' => 'integer',
        'available_balance'  => 'float',
        'pending_balance'    => 'float',
        'total_earned'       => 'float',
        'total_withdrawn'    => 'float',
    ];

    /**
     * Get the user that owns the tutor details
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
