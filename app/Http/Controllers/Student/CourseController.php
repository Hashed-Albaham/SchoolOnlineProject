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

        return view('student.courses.watch', compact('course', 'currentContent'));
    }
}
