<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'reference_number', 'type', 'status',
        'enrollment_id', 'payout_request_id', 'booking_id',
        'student_id', 'tutor_id', 'payment_method_id', 'processed_by',
        'gross_amount', 'platform_fee_rate', 'platform_fee_amount', 'tutor_amount',
        'payment_proof', 'notes', 'processed_at',
    ];

    protected $casts = [
        'gross_amount'        => 'float',
        'platform_fee_rate'   => 'float',
        'platform_fee_amount' => 'float',
        'tutor_amount'        => 'float',
        'processed_at'        => 'datetime',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function enrollment()    { return $this->belongsTo(Enrollment::class); }
    public function payoutRequest() { return $this->belongsTo(PayoutRequest::class); }
    public function booking()       { return $this->belongsTo(Booking::class); }
    public function student()       { return $this->belongsTo(User::class, 'student_id'); }
    public function tutor()         { return $this->belongsTo(User::class, 'tutor_id'); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class); }
    public function processor()     { return $this->belongsTo(User::class, 'processed_by'); }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeCompleted($q)   { return $q->where('status', 'completed'); }
    public function scopeEnrollments($q) { return $q->where('type', 'enrollment'); }
    public function scopePayouts($q)     { return $q->where('type', 'payout'); }
    public function scopeRefunds($q)     { return $q->where('type', 'refund'); }

    // ─── Static Helper ───────────────────────────────────────────
    public static function generateReference(): string
    {
        do {
            $ref = 'TXN-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (static::where('reference_number', $ref)->exists());

        return $ref;
    }
}