<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\PaymentMethod;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Show tutor's payout requests + earning summary
     */
    public function index()
    {
        $tutor = Auth::user();

        // Calculate total earnings from paid enrollments
        $totalEarnings = Enrollment::whereHas('course', fn($q) => $q->where('tutor_id', $tutor->id))
            ->where('payment_status', 'paid')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        // Total paid out
        $totalPaidOut = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PAID)
            ->sum('amount');

        // Pending requests amount
        $pendingAmount = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PENDING)
            ->sum('amount');

        // Available balance
        $availableBalance = $totalEarnings - $totalPaidOut - $pendingAmount;

        // Payout history
        $payoutRequests = PayoutRequest::where('tutor_id', $tutor->id)
            ->with('paymentMethod')
            ->latest()
            ->get();

        // Active payment methods
        $paymentMethods = PaymentMethod::active()->get();

        return view('tutor.payouts.index', compact(
            'totalEarnings',
            'totalPaidOut',
            'pendingAmount',
            'availableBalance',
            'payoutRequests',
            'paymentMethods'
        ));
    }

    /**
     * Submit a new payout request
     */
    public function store(Request $request)
    {
        $tutor = Auth::user();

        $request->validate([
            'amount'            => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'tutor_notes'       => 'nullable|string|max:500',
        ]);

        // Calculate available balance
        $totalEarnings = Enrollment::whereHas('course', fn($q) => $q->where('tutor_id', $tutor->id))
            ->where('payment_status', 'paid')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $totalPaidOut = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PAID)
            ->sum('amount');

        $pendingAmount = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PENDING)
            ->sum('amount');

        $availableBalance = $totalEarnings - $totalPaidOut - $pendingAmount;

        if ($request->amount > $availableBalance) {
            return back()->with('error', __('site.payout_insufficient_balance'));
        }

        PayoutRequest::create([
            'tutor_id'          => $tutor->id,
            'amount'            => $request->amount,
            'payment_method_id' => $request->payment_method_id,
            'tutor_notes'       => $request->tutor_notes,
            'status'            => PayoutRequest::STATUS_PENDING,
        ]);

        return back()->with('success', __('site.payout_request_submitted'));
    }
}
