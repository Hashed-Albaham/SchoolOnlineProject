<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\SessionSlot;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Services\FinancialService;

class SessionBookingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@proskill.com')->first();
        $tutor = User::where('email', 'tutor@proskill.com')->first();
        $student1 = User::where('email', 'student@proskill.com')->first();
        $student2 = User::where('email', 'student2@proskill.com')->first();
        $course = Course::where('tutor_id', $tutor->id)->first();
        $paymentMethod = PaymentMethod::where('type', 'bank_transfer')->first();

        if (!$tutor || !$student1 || !$student2) {
            return; // Essential users not found
        }

        // 1. Create a 1-on-1 Free Session
        $slot1 = SessionSlot::firstOrCreate(
            ['tutor_id' => $tutor->id, 'type' => '1-on-1', 'price' => 0],
            [
                'max_participants' => 1,
                'start_time' => now()->addDays(2)->setHour(10)->setMinute(0),
                'end_time' => now()->addDays(2)->setHour(11)->setMinute(0),
                'status' => 'scheduled',
                'meeting_link' => 'https://zoom.us/j/1234567890'
            ]
        );

        // Student 1 books slot 1
        Booking::firstOrCreate(
            ['student_id' => $student1->id, 'session_slot_id' => $slot1->id],
            [
                'status' => 'confirmed' // Free is confirmed instantly
            ]
        );

        // 2. Create a Group Paid Session linked to Course
        $slot2 = SessionSlot::firstOrCreate(
            ['tutor_id' => $tutor->id, 'type' => 'group', 'course_id' => $course?->id],
            [
                'price' => 15.00,
                'max_participants' => 10,
                'start_time' => now()->addDays(3)->setHour(15)->setMinute(0),
                'end_time' => now()->addDays(3)->setHour(17)->setMinute(0),
                'status' => 'scheduled',
                'meeting_link' => 'https://zoom.us/j/0987654321'
            ]
        );

        // Student 1 books and pays
        $booking2 = Booking::firstOrCreate(
            ['student_id' => $student1->id, 'session_slot_id' => $slot2->id],
            [
                'payment_method_id' => $paymentMethod?->id,
                'status' => 'pending',
                'locked_until' => now()->addDays(7)
            ]
        );

        // Process student 1 payment
        $financial = app(FinancialService::class);
        $exists2 = \App\Models\Transaction::where('booking_id', $booking2->id)->exists();
        if (!$exists2 && $booking2->status === 'pending') {
            $financial->recordBookingPayment($booking2);
            $booking2->update(['status' => 'confirmed', 'locked_until' => null]);
            $financial->confirmBookingPayment($booking2, $admin->id);
        }

        // Student 2 books but stays pending
        $booking3 = Booking::firstOrCreate(
            ['student_id' => $student2->id, 'session_slot_id' => $slot2->id],
            [
                'payment_method_id' => $paymentMethod?->id,
                'status' => 'pending',
                'locked_until' => now()->addDays(2)
            ]
        );

        $exists3 = \App\Models\Transaction::where('booking_id', $booking3->id)->exists();
        if (!$exists3) {
            $transaction = $financial->recordBookingPayment($booking3);
            $transaction->update(['payment_proof' => 'payments/mock_proof.jpg']);
        }
        
        $this->command->info('✅ تم توليد وتخصيص جلسات وحجوزات المعلمين والطلاب بنجاح.');
    }
}
