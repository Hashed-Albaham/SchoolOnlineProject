<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * List all payout requests with filters
     */
    public function index(Request $request)
    {
        $query = PayoutRequest::with(['tutor', 'paymentMethod', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payoutRequests = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total'    => PayoutRequest::count(),
            'pending'  => PayoutRequest::where('status', PayoutRequest::STATUS_PENDING)->count(),
            'approved' => PayoutRequest::where('status', PayoutRequest::STATUS_APPROVED)->count(),
            'paid'     => PayoutRequest::where('status', PayoutRequest::STATUS_PAID)->count(),
            'rejected' => PayoutRequest::where('status', PayoutRequest::STATUS_REJECTED)->count(),
            'total_paid_amount' => PayoutRequest::where('status', PayoutRequest::STATUS_PAID)->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payoutRequests', 'stats'));
    }

    /**
     * Approve a payout request
     */
    public function approve(PayoutRequest $payoutRequest)
    {
        if (!$payoutRequest->isPending()) {
            return back()->with('error', __('site.payout_already_processed'));
        }

        // [V5 FIX] Explicit status assignment
        $payoutRequest->status = PayoutRequest::STATUS_APPROVED;
        $payoutRequest->reviewed_at = now();
        $payoutRequest->reviewed_by = Auth::id();
        $payoutRequest->save();

        // [FIN] تسجيل معاملة السحب
        app(FinancialService::class)->recordPayoutTransaction($payoutRequest, auth()->id());

        return back()->with('success', __('site.payout_approved'));
    }

    /**
     * Reject a payout request
     */
    public function reject(Request $request, PayoutRequest $payoutRequest)
    {
        if (!$payoutRequest->isPending()) {
            return back()->with('error', __('site.payout_already_processed'));
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        // [V5 FIX] Explicit status assignment
        $payoutRequest->status = PayoutRequest::STATUS_REJECTED;
        $payoutRequest->admin_notes = $request->admin_notes;
        $payoutRequest->reviewed_at = now();
        $payoutRequest->reviewed_by = Auth::id();
        $payoutRequest->save();

        return back()->with('success', __('site.payout_rejected'));
    }

    /**
     * Mark as paid (after actual transfer)
     */
    public function markPaid(PayoutRequest $payoutRequest)
    {
        if (!$payoutRequest->isApproved()) {
            return back()->with('error', __('site.payout_must_be_approved_first'));
        }

        // [V5 FIX] Explicit status assignment
        $payoutRequest->status = PayoutRequest::STATUS_PAID;
        $payoutRequest->paid_at = now();
        $payoutRequest->save();

        // [FIN] إكمال عملية السحب وخصم الرصيد
        app(FinancialService::class)->completePayout($payoutRequest, auth()->id());

        return back()->with('success', __('site.payout_marked_paid'));
    }
}
