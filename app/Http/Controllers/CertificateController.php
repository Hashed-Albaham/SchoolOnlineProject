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
     * Show "My Certificates" for student
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
     * Verify certificate publicly
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
     */
    public function show(Request $request, $id)
    {
        // Try finding CourseCertificate first
        $certificate = CourseCertificate::find($id);

        if ($certificate) {
            // Check access: Owner or Admin or Tutor of course or Public if Approved
            if (Auth::id() !== $certificate->user_id && !auth()->user()?->isAdmin() && !auth()->user()?->isTutor()) {
                abort(403);
            }
            return view('certificate.show', compact('certificate'));
        }

        // Fallback to old QuizAttempt logic (if needed, or can be removed if deprecated)
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
