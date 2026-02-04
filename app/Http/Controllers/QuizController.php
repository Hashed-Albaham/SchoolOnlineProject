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
        // Ensure user is enrolled in the course... (logic skipped for brevity)
        return view('student.quizzes.show', compact('quiz'));
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // Simple grading logic
        $score = 0;
        $total = $quiz->questions->sum('points');

        foreach ($quiz->questions as $question) {
            $selectedOptionId = $request->input("q-{$question->id}");
            $correctOption = $question->options()->where('is_correct', true)->first();

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

        return redirect()->route('quizzes.result', $quiz)->with('status', 'تم تسليم الاختبار!');
    }

    public function result(Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->firstOrFail();

        return view('student.quizzes.result', compact('quiz', 'attempt'));
    }
}
