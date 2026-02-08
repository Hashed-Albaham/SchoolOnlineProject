<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show the quiz taking page.
     */
    public function show(Quiz $quiz)
    {
        // FIXED: Eager load course AND questions with options to avoid lazy loading
        $quiz->load(['course', 'questions.options']);
        
        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $quiz->course_id)
            ->where('payment_status', 'paid')
            ->exists();
        
        if (!$isEnrolled) {
            abort(403, 'يجب التسجيل في الكورس أولاً للوصول إلى الاختبار');
        }
        
        // FIXED: Check max attempts
        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();
        
        if ($quiz->max_attempts && $attemptCount >= $quiz->max_attempts) {
            return redirect()->route('student.quizzes.result', $quiz)
                ->with('error', 'لقد استنفدت جميع المحاولات المتاحة');
        }
        
        return view('student.quizzes.show', compact('quiz', 'attemptCount'));
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // FIXED: Verify enrollment before accepting submission
        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $quiz->course_id)
            ->where('payment_status', 'paid')
            ->exists();
        
        if (!$isEnrolled) {
            abort(403, 'غير مصرح');
        }

        // FIXED: Check max attempts BEFORE creating new attempt
        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();
        
        if ($quiz->max_attempts && $attemptCount >= $quiz->max_attempts) {
            return redirect()->route('student.quizzes.result', $quiz)
                ->with('error', 'لقد استنفدت جميع المحاولات المتاحة');
        }

        // Simple grading logic
        $score = 0;
        
        // PERFORMANCE FIX: Eager load questions with their correct options in ONE query
        // Before: N+1 queries (1 query per question)
        // After: 2 queries total (questions + options)
        $quiz->load(['questions.options']);
        $total = $quiz->questions->sum('points');

        foreach ($quiz->questions as $question) {
            $selectedOptionId = $request->input("q-{$question->id}");
            // PERFORMANCE FIX: Use collection filter instead of DB query
            $correctOption = $question->options->where('is_correct', true)->first();

            if ($correctOption && $correctOption->id == $selectedOptionId) {
                $score += $question->points;
            }
        }

        $passed = ($score / $total) * 100 >= $quiz->pass_percentage;

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        return redirect()->route('student.quizzes.result', $quiz)->with('status', 'تم تسليم الاختبار!');
    }

    public function result(Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->firstOrFail();
        
        // Get attempt count for retry logic
        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();
        
        $remainingAttempts = $quiz->max_attempts ? ($quiz->max_attempts - $attemptCount) : null;

        return view('student.quizzes.result', compact('quiz', 'attempt', 'attemptCount', 'remainingAttempts'));
    }
}
