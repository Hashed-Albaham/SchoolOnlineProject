<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Enroll in a course (initiate)
     */
    public function enroll(Course $course)
    {
        if ($course->status !== 'approved') {
            return back()->with('error', 'هذا الكورس غير متاح');
        }

        $user = Auth::user();

        // Check if already enrolled
        $existingEnrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === 'paid') {
                return redirect()->route('student.courses.watch', $course)
                    ->with('info', 'أنت مسجل بالفعل في هذا الكورس');
            }
            // If pending, redirect to payment
            return redirect()->route('student.enrollment.payment', $existingEnrollment);
        }

        // Create new enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'pending',
        ]);

        // If course is free, mark as paid immediately
        if ($course->price <= 0) {
            $enrollment->update(['payment_status' => 'paid']);

            // Send Notification to Tutor
            // FIXED: $course->tutor is already a User model, not TutorDetails
            if ($course->tutor) {
                $course->tutor->notify(new \App\Notifications\NewEnrollment($enrollment));
            }

            return redirect()->route('student.courses.watch', $course)
                ->with('success', 'تم التسجيل بنجاح في الكورس المجاني');
        }

        return redirect()->route('student.enrollment.payment', $enrollment);
    }

    /**
     * Show payment page (simulated)
     */
    public function showPayment(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($enrollment->payment_status === 'paid') {
            return redirect()->route('student.courses.watch', $enrollment->course);
        }

        $enrollment->load('course.tutor');

        return view('student.enrollment.payment', compact('enrollment'));
    }

    /**
     * Process payment (simulated)
     */
    public function processPayment(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate payment processing
        // In real application, integrate with payment gateway here

        $enrollment->update(['payment_status' => 'paid']);

        // Send Notification to Tutor
        // FIXED: $course->tutor is already a User model, not TutorDetails
        if ($enrollment->course && $enrollment->course->tutor) {
            $enrollment->course->tutor->notify(new \App\Notifications\NewEnrollment($enrollment));
        }

        return redirect()->route('student.courses.watch', $enrollment->course)
            ->with('success', 'تم الدفع بنجاح! يمكنك الآن مشاهدة الكورس');
    }

    /**
     * My enrollments
     */
    public function myEnrollments()
    {
        $enrollments = Auth::user()->enrollments()
            ->with('course.tutor')
            ->latest()
            ->paginate(10);

        return view('student.enrollment.my-enrollments', compact('enrollments'));
    }
}
