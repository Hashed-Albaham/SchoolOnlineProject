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
            'status' => 'required|in:confirmed,failed',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', __('site.unauthorized_access'));
        }

        if ($request->status === 'confirmed') {
            // [V4 FIX] Explicit status assignment
            $booking->status = 'confirmed';
            $booking->locked_until = null;
            $booking->save();
            $financialService->confirmBookingPayment($booking, auth()->id());
            return back()->with('success', __('site.booking_confirmed_successfully'));
        } else {
            $booking->status = 'failed';
            $booking->locked_until = null;
            $booking->save();
            $financialService->failBookingPayment($booking);
            return back()->with('success', __('site.booking_rejected'));
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
