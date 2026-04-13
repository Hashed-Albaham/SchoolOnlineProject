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

        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $proofPath = $request->file('payment_proof')->store('payments/sessions', 'public');

        $booking->update([
            'payment_method_id' => $request->payment_method_id,
            // Lock until Admin reviews
            'locked_until' => now()->addDays(7), // hold seat until admin reviews or extend lock
        ]);

        $transaction = $financialService->recordBookingPayment($booking);
        $transaction->update(['payment_proof' => clone $proofPath ?? $proofPath]); // Actually let's just update proof

        // Refresh model to apply to transaction
        $transaction->payment_proof = $proofPath;
        $transaction->save();
        
        return redirect()->route('student.sessions.index')->with('success', __('site.payment_submitted_successfully'));
    }
}
