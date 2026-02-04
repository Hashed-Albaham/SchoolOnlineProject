<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Show the certificate for a passed quiz.
     */
    public function show(QuizAttempt $attempt)
    {
        // Security check
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$attempt->passed) {
            abort(403, 'Certificate only available for passed quizzes.');
        }

        $course = $attempt->quiz->course;
        $user = Auth::user();

        return view('certificates.show', compact('attempt', 'course', 'user'));
    }
}
