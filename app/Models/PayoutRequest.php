<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // [BUG-11 FIX]

class PayoutRequest extends Model
{
    use HasFactory, SoftDeletes; // [BUG-11 FIX] حفظ السجل المالي عند الحذف

    protected $fillable = [
        'tutor_id',
        'amount',
        'payment_method_id',
        // [V5 FIX] 'status' removed — use explicit assignment
        'tutor_notes',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
        'paid_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'reviewed_at' => 'datetime',
        'paid_at'     => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAID     = 'paid';

    /**
     * Get tutor who made this request
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the payment method selected
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the admin who reviewed
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Scope: pending only
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Get the financial transactions associated with this payout request
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'payout_request_id');
    }
}
