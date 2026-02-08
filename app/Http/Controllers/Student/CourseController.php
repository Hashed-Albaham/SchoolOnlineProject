<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Browse all approved courses
     */
    public function index(Request $request)
    {
        $query = Course::where('status', 'approved')
            ->with('tutor')
            ->withCount([
                'enrollments' => function ($query) {
                    $query->where('payment_status', 'paid');
                }
            ])
            ->withAvg('reviews', 'rating');

        // Search
        if ($request->has('search')) {
            // FIXED: Sanitize wildcards to prevent SQL Wildcard DoS
            $search = addcslashes($request->search, '%_\\');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('enrollments_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);

        return view('student.courses.index', compact('courses'));
    }

    /**
     * My Enrolled Courses
     */
    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()
            ->with([
                'course' => function ($query) {
                    $query->with('tutor')->withCount('contents');
                }
            ])
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(12);

        return view('student.courses.my-courses', compact('enrollments'));
    }

    /**
     * Watch Course Content
     */
    public function watch(Course $course, $content = null)
    {
        // 1. Check Enrollment
        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'يجب الاشتراك في الكورس أولاً');
        }

        // 2. Load Contents
        $course->load(['contents' => fn($q) => $q->orderBy('order')]);

        // 3. Determine Current Content
        if ($content) {
            $currentContent = $course->contents->where('id', $content)->first();
        } else {
            $currentContent = $course->contents->first();
        }

        // 4. Progress Stats
        $totalContents = $course->contents->count();
        $completedContents = Auth::user()->getCompletedContentsCount($course->id);
        $progressPercent = $totalContents > 0 ? round(($completedContents / $totalContents) * 100) : 0;

        $canRequestCertificate = $progressPercent == 100;

        $certificateRequest = \App\Models\CourseCertificate::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->latest()
            ->first();

        // 5. Current Content Status
        $isCurrentCompleted = false;
        if ($currentContent) {
            $isCurrentCompleted = Auth::user()->hasCompletedContent($currentContent->id);
        }

        return view('student.courses.watch', compact(
            'course',
            'currentContent',
            'totalContents',
            'completedContents',
            'progressPercent',
            'canRequestCertificate',
            'certificateRequest',
            'isCurrentCompleted'
        ));
    }

    /**
     * Mark content as complete
     */
    public function markComplete(Request $request, Course $course, $contentId)
    {
        $user = Auth::user();

        // FIXED: Validate content belongs to course (Prevents IDOR)
        $content = $course->contents()->where('id', $contentId)->first();
        
        if (!$content) {
            abort(404, 'المحتوى غير موجود في هذا الكورس');
        }

        // FIXED: Validate user is enrolled and paid
        $isEnrolled = $user->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'يجب التسجيل والدفع في الكورس أولاً');
        }

        // Use updateOrCreate to handle existing records properly
        \App\Models\ContentProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_content_id' => $content->id, // FIXED: Use validated content id
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'تم إكمال الدرس بنجاح!');
    }
    public function show(Course $course)
    {
        $course->load([
            'tutor.tutorDetails',
            'contents' => fn($q) => $q->orderBy('order'),
            'quizzes'
        ])->loadCount([
                    'enrollments' => fn($q) => $q->where('payment_status', 'paid')
                ]);

        $isEnrolled = Auth::check() && Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->exists();

        return view('student.courses.show', compact('course', 'isEnrolled'));
    }

    /**
     * Request course certificate
     */
    public function requestCertificate(Request $request, Course $course)
    {
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'يجب التسجيل في الكورس أولاً');
        }

        // Check if all contents completed
        $totalContents = $course->contents()->count();
        $completedContents = Auth::user()->getCompletedContentsCount($course->id);

        if ($completedContents < $totalContents) {
            return back()->with('error', 'يجب إكمال جميع الدروس أولاً');
        }

        // Check if already requested
        $existing = \App\Models\CourseCertificate::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'لقد طلبت الشهادة مسبقاً');
        }

        $certificate = \App\Models\CourseCertificate::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'status' => 'pending',
        ]);

        // Send Notification to Tutor
        if ($course->tutor) {
            $course->tutor->notify(new \App\Notifications\CertificateRequested($certificate));
        }

        return back()->with('success', 'تم إرسال طلب الشهادة بنجاح! سيقوم المعلم بمراجعته.');
    }
}
