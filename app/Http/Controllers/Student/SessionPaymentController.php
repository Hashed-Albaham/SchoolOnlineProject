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
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('student.sessions.index')->with('error', __('site.booking_expired'));
        }

        // In simulation, we don't require external proof or real payment method.
        // We will just process the payment assuming it's a simulated card charge.

        // Assign a default payment method if available (just for referencing in DB) or leave null.
        $defaultMethod = PaymentMethod::where('is_active', true)->first();

        $booking->update([
            'payment_method_id' => $defaultMethod ? $defaultMethod->id : null,
            // Lock until Admin reviews, or depending on business logic, wait for session finish
            'locked_until' => now()->addDays(7), 
            'status' => 'confirmed' // Since it's an online simulated payment, assume instant confirmation
        ]);

        $transaction = $financialService->recordBookingPayment($booking);

        return redirect()->route('student.sessions.index')->with('success', __('site.payment_submitted_successfully') ?? 'تم دفع رسوم الجلسة بنجاح.');
    }
}
