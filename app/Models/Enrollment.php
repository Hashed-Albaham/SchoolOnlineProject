<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * [FIX-06] 'payment_status' removed from $fillable — set explicitly to prevent Mass Assignment.
     * [FIX-03] Added payment_method_id and payment_proof for financial tracking.
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_status',      // [E1] pending_approval, approved, rejected
        'payment_method_id',      // [FIX-03] FK to payment_methods
        'payment_proof',          // [FIX-03] uploaded receipt/proof path
    ];

    /**
     * Get the student who enrolled
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user - get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course enrolled in
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if enrollment is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * [E1] Check if enrollment is approved by tutor/admin
     */
    public function isApproved(): bool
    {
        return $this->enrollment_status === 'approved';
    }

    /**
     * [E1] Check if student can fully access the course
     * Requires both: payment completed AND enrollment approved
     */
    public function canAccess(): bool
    {
        return $this->isPaid() && $this->isApproved();
    }

    /**
     * [E1] Check if enrollment is pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->enrollment_status === 'pending_approval';
    }

    /**
     * Simulate payment (for demo purposes)
     */
    public function simulatePayment(): bool
    {
        $this->payment_status = 'paid';
        return $this->save();
    }

    /**
     * Get the financial transactions associated with this enrollment
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
}
