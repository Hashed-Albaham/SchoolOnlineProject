<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Enroll in a course (initiate).
     *
     * SECURITY FIX [C1]: Wrapped in DB::transaction() to prevent
     * inconsistent state if any step fails (e.g., enrollment created
     * but notification fails mid-way).
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
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
            if ($existingEnrollment->canAccess()) {
                return redirect()->route('student.courses.watch', $course)
                    ->with('info', __('site.already_enrolled'));
            }
            if ($existingEnrollment->payment_status === 'pending' && $course->price > 0) {
                return redirect()->route('student.enrollment.payment', $existingEnrollment);
            }
            // Already enrolled but waiting for approval
            return redirect()->route('student.courses.show', $course)
                ->with('info', __('site.enrollment_pending_approval'));
        }

        // SECURITY FIX [C1]: DB Transaction for enrollment + payment
        return DB::transaction(function () use ($user, $course) {
            // [E1] Create new enrollment with pending_approval status
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_status' => 'pending',
                'enrollment_status' => 'pending_approval',
            ]);

            // If course is free, mark as paid but still need approval
            if ($course->price <= 0) {
                $enrollment->update(['payment_status' => 'paid']);

                // Notify Tutor about new enrollment request
                if ($course->tutor) {
                    $course->tutor->notify(new \App\Notifications\NewEnrollment($enrollment));
                }

                return redirect()->route('student.courses.show', $course)
                    ->with('success', __('site.enrolled_pending_tutor_approval'));
            }

            return redirect()->route('student.enrollment.payment', $enrollment);
        });
    }

    /**
     * Show payment page (simulated).
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showPayment(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        if ($enrollment->payment_status === 'paid') {
            return redirect()->route('student.courses.watch', $enrollment->course);
        }

        $enrollment->load('course.tutor');

        return view('student.enrollment.payment', compact('enrollment'));
    }

    /**
     * Process payment (simulated).
     *
     * SECURITY FIX [C1]: Wrapped in DB::transaction() to ensure
     * atomicity of payment status update + notification.
     * SECURITY FIX [C4]: Added validation to prevent re-processing
     * already-paid enrollments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        // SECURITY FIX [C4]: Prevent re-processing of already paid enrollments
        if ($enrollment->payment_status === 'paid') {
            return redirect()->route('student.courses.watch', $enrollment->course)
                ->with('info', 'تم الدفع مسبقاً لهذا الاشتراك');
        }

        // SECURITY FIX [C4]: Validate that enrollment is in 'pending' state
        if ($enrollment->payment_status !== 'pending') {
            return back()->with('error', 'حالة الاشتراك غير صالحة للدفع');
        }

        // SECURITY FIX [C1]: DB Transaction for payment + notification
        return DB::transaction(function () use ($enrollment) {
            // Simulate payment processing
            // TODO: In production, integrate with payment gateway (Stripe/Paddle/etc.)
            $enrollment->update(['payment_status' => 'paid']);

            // [FIN] تسجيل المعاملة المالية
            app(FinancialService::class)->recordEnrollmentPayment($enrollment);

            // Notify Tutor about paid enrollment (needs approval)
            if ($enrollment->course && $enrollment->course->tutor) {
                $enrollment->course->tutor->notify(new \App\Notifications\NewEnrollment($enrollment));
            }

            // [E1] After payment, enrollment still needs tutor/admin approval
            if ($enrollment->isApproved()) {
                return redirect()->route('student.courses.watch', $enrollment->course)
                    ->with('success', __('site.payment_success_can_watch'));
            }

            return redirect()->route('student.courses.show', $enrollment->course)
                ->with('success', __('site.payment_success_pending_approval'));
        });
    }

    /**
     * My enrollments listing.
     *
     * @return \Illuminate\View\View
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
