<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\FinancialService;

class SessionPaymentController extends Controller
{
    public function show(Booking $booking)
    {
        // Must be pending and own booking
        if ($booking->student_id !== auth()->id() || $booking->status !== 'pending') {
            return redirect()->route('student.sessions.index')->with('error', __('site.unauthorized_access'));
        }

        // Check lock expiration
        if ($booking->locked_until && $booking->locked_until < now()) {
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('student.sessions.index')->with('error', __('site.booking_expired'));
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('student.sessions.payment', compact('booking', 'paymentMethods'));
    }

    public function process(Request $request, Booking $booking, FinancialService $financialService)
    {
        if ($booking->student_id !== auth()->id() || $booking->status !== 'pending') {
            abort(403);
        }

        if ($booking->locked_until && $booking->locked_until < now()) {
            $booking->status = 'cancelled';
            $booking->save();
            return redirect()->route('student.sessions.index')->with('error', __('site.booking_expired'));
        }

        // [V1+V2 FIX] Wrap entire flow in DB::transaction
        return \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $financialService) {
            $defaultMethod = PaymentMethod::where('is_active', true)->first();

            // [V4 FIX] Explicit assignment instead of mass assignment for status
            // [V5 FIX] Status becomes pending_tutor_approval instead of confirmed
            $booking->payment_method_id = $defaultMethod?->id;
            $booking->locked_until = null;
            $booking->status = 'pending_tutor_approval';
            $booking->save();

            // [BUG-03 FIX] تسجيل المعاملة واسترداد الـ ID لربطه بالحجز
            $transaction = $financialService->recordBookingPayment($booking);

            // [BUG-03 FIX] ربط Booking بالمعاملة المالية (سلامة الربط)
            $booking->transaction_id = $transaction->id;
            $booking->save();

            // [V5 FIX] We DO NOT call confirmBookingPayment here, admin/tutor will handle it explicitly.

            return redirect()->route('student.sessions.index')
                ->with('success', __('site.payment_submitted_awaiting_tutor') ?? 'تم تقديم دفع رسوم الجلسة بنجاح وبانتظار موافقة المعلم.');
        });
    }
}
