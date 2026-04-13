<?php

namespace App\Services;

use App\Models\SessionSlot;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    /**
     * الحجز المؤقت وتأمين المقعد
     */
    public function lockSeat(SessionSlot $slot, User $student): Booking
    {
        return DB::transaction(function () use ($slot, $student) {
            // Pessimistic Lock on the session slot
            $lockedSlot = SessionSlot::where('id', $slot->id)->lockForUpdate()->first();

            if (!$lockedSlot) {
                throw new Exception(__('site.slot_not_found'));
            }

            if ($lockedSlot->status !== 'scheduled') {
                throw new Exception(__('site.slot_not_available'));
            }

            // Check if student is already booked
            $existingBooking = Booking::where('student_id', $student->id)
                ->where('session_slot_id', $lockedSlot->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingBooking) {
                throw new Exception(__('site.already_booked'));
            }

            // Check specific course enrollment
            if ($lockedSlot->course_id) {
                $isEnrolled = $student->enrollments()
                    ->where('course_id', $lockedSlot->course_id)
                    ->where('enrollment_status', 'approved')
                    ->exists();

                if (!$isEnrolled) {
                    throw new Exception(__('site.must_be_enrolled_in_course'));
                }
            }

            // Calculate current occupied seats
            // Consider confirmed bookings and pending bookings inside locked_until
            $occupiedSeats = Booking::where('session_slot_id', $lockedSlot->id)
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'pending')
                             ->where('locked_until', '>=', now());
                      });
                })->count();

            if ($occupiedSeats >= $lockedSlot->max_participants) {
                throw new Exception(__('site.session_full'));
            }

            // Create pending booking
            $booking = Booking::create([
                'student_id'      => $student->id,
                'session_slot_id' => $lockedSlot->id,
                'status'          => ($lockedSlot->price > 0) ? 'pending' : 'confirmed',
                'locked_until'    => ($lockedSlot->price > 0) ? now()->addMinutes(15) : null,
            ]);

            return $booking;
        });
    }
}
