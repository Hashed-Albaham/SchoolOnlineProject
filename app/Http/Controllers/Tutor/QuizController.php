<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes for a specific course.
     */
    public function index(Course $course)
    {
        $this->authorize('update', $course);
        // FIXED: Use withCount to avoid lazy loading violation
        $quizzes = $course->quizzes()->withCount('questions')->latest()->get();
        return view('tutor.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('tutor.quizzes.create', compact('course'));
    }

    /**
     * Store a newly created quiz in database.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'pass_percentage' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        $quiz = $course->quizzes()->create([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit_minutes' => $request->time_limit_minutes,
            'pass_percentage' => $request->pass_percentage,
            'max_attempts' => $request->max_attempts,
        ]);

        return redirect()->route('tutor.courses.quizzes.builder', [$course, $quiz])
            ->with('success', 'تم إنشاء الاختبار بنجاح. ابدأ بإضافة الأسئلة.');
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        return view('tutor.quizzes.edit', compact('course', 'quiz'));
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'pass_percentage' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        $quiz->update($request->only([
            'title',
            'description',
            'time_limit_minutes',
            'pass_percentage',
            'max_attempts'
        ]));

        return back()->with('success', 'تم تحديث إعدادات الاختبار بنجاح.');
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        $quiz->delete();
        return back()->with('success', 'تم حذف الاختبار بنجاح.');
    }

    /**
     * Show the Quiz Builder (Page to add questions/options).
     */
    public function builder(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        $quiz->load(['questions.options']);
        return view('tutor.quizzes.builder', compact('course', 'quiz'));
    }

    /**
     * Store a new question via Builder.
     */
    public function storeQuestion(Request $request, Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);

        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0', // Index of correct option
        ]);

        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        return back()->with('success', 'تم إضافة السؤال بنجاح.');
    }

    /**
     * Delete a question.
     */
    public function destroyQuestion(Course $course, Quiz $quiz, Question $question)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        if ($question->quiz_id !== $quiz->id)
            abort(403);

        $question->delete();
        return back()->with('success', 'تم حذف السؤال.');
    }

    /**
     * Show quiz results/attempts for tutor.
     */
    public function results(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        
        // Get all attempts with user info
        $attempts = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
            ->with('user')
            ->latest()
            ->get();
        
        // Calculate stats
        $stats = [
            'total_attempts' => $attempts->count(),
            'passed' => $attempts->where('passed', true)->count(),
            'failed' => $attempts->where('passed', false)->count(),
            'average_score' => $attempts->avg('score') ?? 0,
        ];
        
        return view('tutor.quizzes.results', compact('course', 'quiz', 'attempts', 'stats'));
    }

    /**
     * Show a student's attempt details with answers.
     */
    public function showAttempt(Course $course, Quiz $quiz, \App\Models\QuizAttempt $attempt)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        
        // Load quiz with questions and options
        $quiz->load(['questions.options']);
        
        return view('tutor.quizzes.attempt', compact('course', 'quiz', 'attempt'));
    }

    /**
     * Delete all attempts for a quiz.
     */
    public function clearAttempts(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        
        \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->delete();
        
        return back()->with('success', 'تم حذف جميع نتائج الاختبار بنجاح.');
    }

    /**
     * Delete a single attempt.
     */
    public function deleteAttempt(Course $course, Quiz $quiz, \App\Models\QuizAttempt $attempt)
    {
        $this->authorize('update', $course);
        $this->authorize('manage', $quiz);
        
        if ($attempt->quiz_id !== $quiz->id) {
            abort(403);
        }
        
        $attempt->delete();
        
        return back()->with('success', 'تم حذف محاولة الطالب بنجاح.');
    }
}
