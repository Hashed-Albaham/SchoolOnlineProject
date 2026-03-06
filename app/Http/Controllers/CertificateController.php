<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCertificate;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Show "My Certificates" for student.
     *
     * @return \Illuminate\View\View
     */
    public function myCertificates()
    {
        $certificates = Auth::user()->courseCertificates()
            ->with(['course.tutor'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(9);

        return view('student.certificates.index', compact('certificates'));
    }

    /**
     * Verify certificate publicly by code.
     *
     * @param  string  $code
     * @return \Illuminate\View\View
     */
    public function verify($code)
    {
        $certificate = CourseCertificate::where('certificate_code', $code)
            ->where('status', 'approved')
            ->with(['user', 'course.tutor'])
            ->firstOrFail();

        return view('certificate.verify', compact('certificate'));
    }

    /**
     * Show the certificate for a passed quiz or course.
     *
     * SECURITY FIX [C3]: Fixed IDOR vulnerability - previously ANY tutor could
     * view ANY certificate. Now only the tutor who owns the course can view it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        // Try finding CourseCertificate first
        $certificate = CourseCertificate::find($id);

        if ($certificate) {
            $certificate->load('course');

            // SECURITY FIX [C3]: Strict access control
            // Allowed: Certificate owner, Admin, or the Tutor who owns the course
            $user = auth()->user();
            $isOwner = Auth::id() === $certificate->user_id;
            $isAdmin = $user?->isAdmin();
            $isCoursetutor = $user?->isTutor() && $certificate->course && $certificate->course->tutor_id === $user->id;

            if (!$isOwner && !$isAdmin && !$isCoursetutor) {
                abort(403, 'غير مصرح لك بمشاهدة هذه الشهادة');
            }

            return view('certificate.show', compact('certificate'));
        }

        // Fallback to old QuizAttempt logic
        $attempt = QuizAttempt::findOrFail($id);

        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$attempt->passed) {
            abort(403, 'Certificate only available for passed quizzes.');
        }

        $course = $attempt->quiz->course;
        $user = Auth::user();

        return view('certificates.quiz-show', compact('attempt', 'course', 'user'));
    }
}
