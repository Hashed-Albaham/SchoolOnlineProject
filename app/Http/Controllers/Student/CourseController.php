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
            ->with('tutor');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
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
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);

        return view('student.courses.index', compact('courses'));
    }

    /**
     * Show course details
     */
    public function show(Course $course)
    {
        if ($course->status !== 'approved') {
            abort(404);
        }

        $course->load(['tutor.tutorDetails', 'contents']);

        $isEnrolled = false;
        $enrollment = null;

        if (Auth::check()) {
            $enrollment = Auth::user()->enrollments()
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = $enrollment && $enrollment->payment_status === 'paid';
        }

        return view('student.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    /**
     * My enrolled courses
     */
    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()
            ->with('course.tutor')
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(12);

        return view('student.courses.my-courses', compact('enrollments'));
    }

    /**
     * Watch course content (protected)
     */
    public function watch(Course $course, $contentId = null)
    {
        // Check if enrolled and paid
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->first();

        if (!$enrollment) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'يجب التسجيل في الكورس أولاً');
        }

        $course->load('contents');

        // Get current content
        if ($contentId) {
            $currentContent = $course->contents->find($contentId);
        } else {
            $currentContent = $course->contents->first();
        }

        if (!$currentContent) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'لا يوجد محتوى في هذا الكورس');
        }

        // Calculate progress
        $totalContents = $course->contents->count();
        $completedContents = Auth::user()->getCompletedContentsCount($course->id);
        $progressPercent = $totalContents > 0 ? round(($completedContents / $totalContents) * 100) : 0;
        $isCurrentCompleted = Auth::user()->hasCompletedContent($currentContent->id);

        // Check if can request certificate
        $canRequestCertificate = $completedContents >= $totalContents && $totalContents > 0;
        $certificateRequest = \App\Models\CourseCertificate::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        return view('student.courses.watch', compact(
            'course',
            'currentContent',
            'totalContents',
            'completedContents',
            'progressPercent',
            'isCurrentCompleted',
            'canRequestCertificate',
            'certificateRequest'
        ));
    }

    /**
     * Mark content as completed
     */
    public function markComplete(Request $request, Course $course, $contentId)
    {
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'يجب التسجيل في الكورس أولاً');
        }

        $content = $course->contents()->find($contentId);
        if (!$content) {
            return back()->with('error', 'محتوى غير موجود');
        }

        \App\Models\ContentProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'course_content_id' => $contentId,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'تم إكمال الدرس بنجاح!');
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
        $totalContents = $course->contents->count();
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
        if ($course->tutor && $course->tutor->user) {
            $course->tutor->user->notify(new \App\Notifications\CertificateRequested($certificate));
        }

        return back()->with('success', 'تم إرسال طلب الشهادة بنجاح! سيقوم المعلم بمراجعته.');
    }
}
