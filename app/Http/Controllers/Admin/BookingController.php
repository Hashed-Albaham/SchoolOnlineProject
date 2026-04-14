<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['student', 'sessionSlot.tutor', 'sessionSlot.course', 'paymentMethod', 'transaction'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['student', 'sessionSlot.tutor', 'sessionSlot.course', 'paymentMethod', 'transaction']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking, FinancialService $financialService)
    {
        $request->validate([
            'status' => 'required|in:confirmed,rejected_by_tutor,failed',
        ]);

        if (!in_array($booking->status, ['pending', 'pending_tutor_approval'])) {
            return back()->with('error', __('site.unauthorized_access'));
        }

        if ($request->status === 'confirmed') {
            $booking->status = 'confirmed';
            $booking->locked_until = null;
            $booking->save();
            // We DO NOT call confirmBookingPayment here anymore. Money isn't moved until explicit approval.
            return back()->with('success', __('site.booking_confirmed_successfully') ?? 'تم الموافقة على الحجز بنجاح.');
        } else {
            $booking->status = $request->status; // failed or rejected_by_tutor
            $booking->locked_until = null;
            $booking->save();
            
            // Refund or fail payment
            if ($booking->payment_method_id != null && $booking->transaction_id) {
                $financialService->failBookingPayment($booking);
            }
            return back()->with('success', __('site.booking_rejected') ?? 'تم رفض الحجز وإلغاؤه.');
        }
    }

    public function approvePayment(Request $request, Booking $booking, FinancialService $financialService)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'يجب أن يكون الحجز مؤكداً من المعلم أولاً قبل اعتماد الدفعة.');
        }

        try {
            $financialService->confirmBookingPayment($booking, auth()->id());
            return back()->with('success', 'تم تأكيد الدفعة ونقل الرصيد إلى المعلم بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function refund(Request $request, Booking $booking, FinancialService $financialService)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', __('site.only_confirmed_can_be_refunded'));
        }

        try {
            $financialService->processBookingRefund($booking, auth()->id(), $request->notes ?? '');
            
            // Also change booking status
            $booking->status = 'refunded';
            $booking->save();

            return back()->with('success', __('site.refund_successful'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
