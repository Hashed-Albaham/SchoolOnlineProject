<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payoutRequests = $query->latest()->paginate(20)->withQueryString();
        $tutors = User::where('role', 'tutor')->get();

        // Stats
        $stats = [
            'total'    => PayoutRequest::count(),
            'pending'  => PayoutRequest::where('status', PayoutRequest::STATUS_PENDING)->count(),
            'approved' => PayoutRequest::where('status', PayoutRequest::STATUS_APPROVED)->count(),
            'paid'     => PayoutRequest::where('status', PayoutRequest::STATUS_PAID)->count(),
            'rejected' => PayoutRequest::where('status', PayoutRequest::STATUS_REJECTED)->count(),
            'total_paid_amount' => PayoutRequest::where('status', PayoutRequest::STATUS_PAID)->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payoutRequests', 'stats', 'tutors'));
    }

    /**
     * Handle bulk actions for payout requests
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,mark_paid',
            'payout_ids' => 'required|array|min:1',
            'payout_ids.*' => 'exists:payout_requests,id',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $action = $request->action;
        $payoutIds = $request->payout_ids;
        $adminNotes = $request->admin_notes;

        $payouts = PayoutRequest::whereIn('id', $payoutIds)->get();

        $successCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($payouts as $payoutRequest) {
                if ($action === 'approve') {
                    if ($payoutRequest->isPending()) {
                        $payoutRequest->status = PayoutRequest::STATUS_APPROVED;
                        $payoutRequest->reviewed_at = now();
                        $payoutRequest->reviewed_by = Auth::id();
                        $payoutRequest->save();

                        app(FinancialService::class)->recordPayoutTransaction($payoutRequest, auth()->id());
                        $successCount++;
                    } else {
                        $skippedCount++;
                    }
                } elseif ($action === 'reject') {
                    if ($payoutRequest->isPending()) {
                        $payoutRequest->status = PayoutRequest::STATUS_REJECTED;
                        $payoutRequest->admin_notes = $adminNotes;
                        $payoutRequest->reviewed_at = now();
                        $payoutRequest->reviewed_by = Auth::id();
                        $payoutRequest->save();
                        $successCount++;
                    } else {
                        $skippedCount++;
                    }
                } elseif ($action === 'mark_paid') {
                    if ($payoutRequest->isApproved()) {
                        $payoutRequest->status = PayoutRequest::STATUS_PAID;
                        $payoutRequest->paid_at = now();
                        $payoutRequest->save();

                        app(FinancialService::class)->completePayout($payoutRequest, auth()->id());
                        $successCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            }
            DB::commit();

            $message = __('site.bulk_action_success', ['success' => $successCount, 'skipped' => $skippedCount]);
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing bulk action: ' . $e->getMessage());
        }
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
