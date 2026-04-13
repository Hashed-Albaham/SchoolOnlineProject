<?php

namespace App\Services;

use App\Models\{Enrollment, PayoutRequest, Transaction, Setting, TutorDetail, Booking};
use Illuminate\Support\Facades\DB;

class FinancialService
{
    /**
     * الحصول على نسبة عمولة المنصة من الإعدادات
     */
    public function getCommissionRate(): float
    {
        return (float) Setting::get('platform_commission_rate', 20);
    }

    /**
     * حساب توزيع المبلغ
     * @return array ['gross', 'platform_fee', 'tutor_amount', 'rate']
     */
    public function calculateSplit(float $grossAmount): array
    {
        $rate = $this->getCommissionRate();
        $platformFee = round($grossAmount * ($rate / 100), 2);
        return [
            'gross'          => $grossAmount,
            'rate'           => $rate,
            'platform_fee'   => $platformFee,
            'tutor_amount'   => round($grossAmount - $platformFee, 2),
        ];
    }

    /**
     * [1] تسجيل دفعة اشتراك جديدة (حالة pending — في انتظار موافقة الأدمن)
     * يُستدعى من: Student\EnrollmentController::processPayment()
     */
    public function recordEnrollmentPayment(Enrollment $enrollment): Transaction
    {
        return DB::transaction(function () use ($enrollment) {
            $course = $enrollment->course()->first();
            $split  = $this->calculateSplit((float) $course->price);

            $transaction = Transaction::create([
                'reference_number'  => Transaction::generateReference(),
                'type'              => 'enrollment',
                'status'            => 'pending',
                'enrollment_id'     => $enrollment->id,
                'student_id'        => $enrollment->user_id, // Note: The field is user_id in Enrollment, not student_id
                'tutor_id'          => $course->tutor_id,    // Note: The field is tutor_id in Course, not user_id
                'payment_method_id' => $enrollment->payment_method_id ?? null,
                'gross_amount'      => $split['gross'],
                'platform_fee_rate' => $split['rate'],
                'platform_fee_amount' => $split['platform_fee'],
                'tutor_amount'      => $split['tutor_amount'],
                'payment_proof'     => $enrollment->payment_proof ?? null,
            ]);

            // أضف للرصيد المعلق للمعلم
            TutorDetail::where('user_id', $course->tutor_id)
                ->increment('pending_balance', $split['tutor_amount']);

            return $transaction;
        });
    }

    /**
     * [2] تأكيد الدفع (Admin يوافق على الاشتراك)
     * يُستدعى من: Admin\EnrollmentController::updateStatus() عند تغيير الحالة إلى 'completed'
     */
    public function confirmEnrollmentPayment(Enrollment $enrollment, int $adminId): void
    {
        DB::transaction(function () use ($enrollment, $adminId) {
            $transaction = Transaction::where('enrollment_id', $enrollment->id)
                ->where('type', 'enrollment')
                ->latest()
                ->first();

            if (!$transaction) return;

            $transaction->update([
                'status'       => 'completed',
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);

            // انقل من pending إلى available
            $tutorDetail = TutorDetail::where('user_id', $transaction->tutor_id)->first();
            if ($tutorDetail) {
                $tutorDetail->decrement('pending_balance', $transaction->tutor_amount);
                $tutorDetail->increment('available_balance', $transaction->tutor_amount);
                $tutorDetail->increment('total_earned', $transaction->tutor_amount);
            }
        });
    }

    /**
     * [3] رفض/إلغاء دفعة اشتراك (Admin يرفض أو حالة failed)
     * يُستدعى من: Admin\EnrollmentController::updateStatus() عند تغيير الحالة إلى 'failed'
     */
    public function failEnrollmentPayment(Enrollment $enrollment): void
    {
        DB::transaction(function () use ($enrollment) {
            $transaction = Transaction::where('enrollment_id', $enrollment->id)
                ->where('type', 'enrollment')
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$transaction) return;

            $transaction->update(['status' => 'failed']);

            // أرجع الرصيد المعلق
            TutorDetail::where('user_id', $transaction->tutor_id)
                ->decrement('pending_balance', $transaction->tutor_amount);
        });
    }

    /**
     * [4] استرداد مبلغ (Refund)
     * يُستدعى من: Admin\EnrollmentController::refund()
     */
    public function processRefund(Enrollment $enrollment, int $adminId, string $notes = ''): Transaction
    {
        return DB::transaction(function () use ($enrollment, $adminId, $notes) {
            // اجلب المعاملة الأصلية المكتملة
            $originalTx = Transaction::where('enrollment_id', $enrollment->id)
                ->where('type', 'enrollment')
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$originalTx) {
                throw new \Exception('No completed transaction found for this enrollment');
            }

            // أنشئ معاملة استرداد
            $refundTx = Transaction::create([
                'reference_number'    => Transaction::generateReference(),
                'type'                => 'refund',
                'status'              => 'completed',
                'enrollment_id'       => $enrollment->id,
                'student_id'          => $enrollment->user_id,
                'tutor_id'            => $originalTx->tutor_id,
                'payment_method_id'   => $originalTx->payment_method_id,
                'processed_by'        => $adminId,
                'gross_amount'        => $originalTx->gross_amount,
                'platform_fee_rate'   => $originalTx->platform_fee_rate,
                'platform_fee_amount' => $originalTx->platform_fee_amount,
                'tutor_amount'        => $originalTx->tutor_amount,
                'notes'               => $notes,
                'processed_at'        => now(),
            ]);

            // علّم المعاملة الأصلية بأنها مُستردة
            $originalTx->update(['status' => 'refunded']);

            // اخصم من رصيد المعلم (available فقط إذا كانت مكتملة)
            $tutorDetail = TutorDetail::where('user_id', $originalTx->tutor_id)->first();
            if ($tutorDetail && $tutorDetail->available_balance >= $originalTx->tutor_amount) {
                $tutorDetail->decrement('available_balance', $originalTx->tutor_amount);
                $tutorDetail->decrement('total_earned', $originalTx->tutor_amount);
            }

            return $refundTx;
        });
    }

    /**
     * [5] تسجيل معاملة سحب أرباح
     * يُستدعى من: Admin\PayoutController::approve()
     */
    public function recordPayoutTransaction(PayoutRequest $payoutRequest, int $adminId): Transaction
    {
        return DB::transaction(function () use ($payoutRequest, $adminId) {
            $transaction = Transaction::create([
                'reference_number'   => Transaction::generateReference(),
                'type'               => 'payout',
                'status'             => 'pending',
                'payout_request_id'  => $payoutRequest->id,
                'tutor_id'           => $payoutRequest->tutor_id,
                'payment_method_id'  => $payoutRequest->payment_method_id ?? null,
                'gross_amount'       => $payoutRequest->amount,
                'platform_fee_rate'  => 0,
                'platform_fee_amount'=> 0,
                'tutor_amount'       => $payoutRequest->amount,
                'processed_by'       => $adminId,
                'notes'              => $payoutRequest->tutor_notes ?? null, // Changed notes to tutor_notes
            ]);

            return $transaction;
        });
    }

    /**
     * [6] تأكيد صرف السحب (Admin يضغط "تم الدفع")
     * يُستدعى من: Admin\PayoutController::markPaid()
     */
    public function completePayout(PayoutRequest $payoutRequest, int $adminId): void
    {
        DB::transaction(function () use ($payoutRequest, $adminId) {
            // حدّث المعاملة
            Transaction::where('payout_request_id', $payoutRequest->id)
                ->where('type', 'payout')
                ->update([
                    'status'       => 'completed',
                    'processed_by' => $adminId,
                    'processed_at' => now(),
                ]);

            // اخصم من رصيد المعلم
            $tutorDetail = TutorDetail::where('user_id', $payoutRequest->tutor_id)->first();
            if ($tutorDetail) {
                $tutorDetail->decrement('available_balance', (float) $payoutRequest->amount);
                $tutorDetail->increment('total_withdrawn', (float) $payoutRequest->amount);
            }
        });
    }

    /**
     * [7] التحقق من أن للمعلم رصيد كافٍ للسحب
     */
    public function canRequestPayout(int $tutorUserId, float $amount): array
    {
        $minAmount   = (float) Setting::get('min_payout_amount', 50);
        $tutorDetail = TutorDetail::where('user_id', $tutorUserId)->first();
        $available   = $tutorDetail ? $tutorDetail->available_balance : 0;

        // [FIX] Subtract currently pending and approved (but not yet paid/deducted) payouts
        $lockedFunds = PayoutRequest::where('tutor_id', $tutorUserId)
            ->whereIn('status', [PayoutRequest::STATUS_PENDING, PayoutRequest::STATUS_APPROVED])
            ->sum('amount');

        $trulyAvailable = $available - $lockedFunds;

        if ($amount < $minAmount) {
            return ['can' => false, 'reason' => 'below_minimum', 'min' => $minAmount];
        }
        if ($amount > $trulyAvailable) {
            return ['can' => false, 'reason' => 'insufficient_balance', 'available' => $trulyAvailable];
        }
        return ['can' => true];
        // ─────────────────────────────────────────────────────────────────
    // عمليات الحجوزات (BOOKINGS)
    // ─────────────────────────────────────────────────────────────────

    public function recordBookingPayment(Booking $booking): Transaction
    {
        return DB::transaction(function () use ($booking) {
            $slot = $booking->sessionSlot()->first();
            $split  = $this->calculateSplit((float) $slot->price);

            $transaction = Transaction::create([
                'reference_number'  => Transaction::generateReference(),
                'type'              => 'enrollment', // We keep type as 'enrollment' or we can add 'booking' type? Let's use 'enrollment' logic or 'booking'. Actually we can treat it as a booking type if added, but let's use type 'booking' (Make sure to add it). Wait, type enum might restrict it. Let's assume 'enrollment' for now or we just use 'booking'. Wait, if we use 'booking', transaction enum must allow 'booking'. Does it? No, Transaction migration didn't have enum restriction for type, let's look at Transaction Model: 'enrollment', 'payout', 'refund'. The DB schema for transactions column 'type' is enum('enrollment','payout','refund'). So we need an enum change. Actually let's use 'booking' and I'll create a migration for the enum. Actually, we can reuse 'enrollment' type and rely on booking_id. Let's use 'enrollment'.
                'status'            => 'pending',
                'booking_id'        => $booking->id,
                'student_id'        => $booking->student_id,
                'tutor_id'          => $slot->tutor_id,
                'payment_method_id' => $booking->payment_method_id ?? null,
                'gross_amount'      => $split['gross'],
                'platform_fee_rate' => $split['rate'],
                'platform_fee_amount' => $split['platform_fee'],
                'tutor_amount'      => $split['tutor_amount'],
            ]);

            // Add to pending balance
            TutorDetail::where('user_id', $slot->tutor_id)
                ->increment('pending_balance', $split['tutor_amount']);

            return $transaction;
        });
    }

    public function confirmBookingPayment(Booking $booking, int $adminId): void
    {
        DB::transaction(function () use ($booking, $adminId) {
            $transaction = Transaction::where('booking_id', $booking->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$transaction) return;

            $transaction->update([
                'status' => 'completed',
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);

            // Move from pending to available
            $tutorDetail = TutorDetail::where('user_id', $transaction->tutor_id)->first();
            if ($tutorDetail) {
                $tutorDetail->decrement('pending_balance', (float) $transaction->tutor_amount);
                $tutorDetail->increment('available_balance', (float) $transaction->tutor_amount);
                $tutorDetail->increment('total_earned', (float) $transaction->tutor_amount);
            }
        });
    }

    public function failBookingPayment(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $transaction = Transaction::where('booking_id', $booking->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$transaction) return;

            $transaction->update(['status' => 'failed']);

            // Revert pending balance
            TutorDetail::where('user_id', $transaction->tutor_id)
                ->decrement('pending_balance', (float) $transaction->tutor_amount);
        });
    }

    public function processBookingRefund(Booking $booking, int $adminId, string $notes = ''): Transaction
    {
        return DB::transaction(function () use ($booking, $adminId, $notes) {
            $originalTx = Transaction::where('booking_id', $booking->id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$originalTx) {
                throw new \Exception('No completed transaction found for this booking');
            }

            $refundTx = Transaction::create([
                'reference_number'    => Transaction::generateReference(),
                'type'                => 'refund',
                'status'              => 'completed',
                'booking_id'          => $booking->id,
                'student_id'          => $booking->student_id,
                'tutor_id'            => $originalTx->tutor_id,
                'payment_method_id'   => $originalTx->payment_method_id,
                'processed_by'        => $adminId,
                'gross_amount'        => $originalTx->gross_amount,
                'platform_fee_rate'   => $originalTx->platform_fee_rate,
                'platform_fee_amount' => $originalTx->platform_fee_amount,
                'tutor_amount'        => $originalTx->tutor_amount,
                'notes'               => $notes,
                'processed_at'        => now(),
            ]);

            $originalTx->update(['status' => 'refunded']);

            $tutorDetail = TutorDetail::where('user_id', $originalTx->tutor_id)->first();
            if ($tutorDetail && $tutorDetail->available_balance >= $originalTx->tutor_amount) {
                $tutorDetail->decrement('available_balance', (float) $originalTx->tutor_amount);
                $tutorDetail->decrement('total_earned', (float) $originalTx->tutor_amount);
            }

            return $refundTx;
        });
    }
}