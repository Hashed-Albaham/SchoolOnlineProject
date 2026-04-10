<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\PaymentMethod;
use App\Models\Enrollment;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class PayoutController extends Controller
{
    /**
     * Show tutor's payout requests + earning summary
     */
    public function index()
    {
        $tutor = Auth::user();

        $tutorDetail = $tutor->tutorDetail;

        // Calculate total earnings from tutor details
        $totalEarnings = $tutorDetail ? $tutorDetail->total_earned : 0;
        $totalPaidOut = $tutorDetail ? $tutorDetail->total_withdrawn : 0;
        $pendingAmount = $tutorDetail ? $tutorDetail->pending_balance : 0;
        $availableBalance = $tutorDetail ? $tutorDetail->available_balance : 0;

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
    public function store(Request $request): RedirectResponse
    {
        $tutor = Auth::user();

        $request->validate([
            'amount'            => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'tutor_notes'       => 'nullable|string|max:500',
        ]);

        $financial = app(FinancialService::class);
        $check = $financial->canRequestPayout(auth()->id(), (float) $request->amount);

        if (!$check['can']) {
            $errorKey = $check['reason'] === 'below_minimum'
                ? __('site.fin_payout_below_min', ['min' => $check['min']])
                : __('site.fin_payout_insufficient', ['available' => number_format($check['available'], 2)]);
            return back()->withErrors(['amount' => $errorKey])->withInput();
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
