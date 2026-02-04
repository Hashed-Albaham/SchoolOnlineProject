<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display all courses
     */
    public function index()
    {
        $courses = Course::with('tutor')
            ->latest()
            ->paginate(10);

        $allCount = Course::count();
        $pendingCount = Course::where('status', 'pending')->count();
        $approvedCount = Course::where('status', 'approved')->count();
        $rejectedCount = Course::where('status', 'rejected')->count();

        return view('admin.courses.index', compact('courses', 'allCount', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Display pending courses
     */
    public function pending()
    {
        $courses = Course::where('status', 'pending')
            ->with('tutor')
            ->latest()
            ->paginate(10);

        return view('admin.courses.pending', compact('courses'));
    }

    /**
     * Approve a course
     */
    public function approve(Course $course)
    {
        $course->update(['status' => 'approved']);

        if ($course->tutor) {
            $course->tutor->notify(new \App\Notifications\CourseStatusUpdated($course, 'approved'));
        }

        return back()->with('success', 'تم الموافقة على الكورس بنجاح');
    }

    /**
     * Reject a course
     */
    public function reject(Course $course)
    {
        $course->update(['status' => 'rejected']);

        if ($course->tutor) {
            $course->tutor->notify(new \App\Notifications\CourseStatusUpdated($course, 'rejected'));
        }

        return back()->with('success', 'تم رفض الكورس');
    }

    /**
     * Show course details
     */
    public function show(Course $course)
    {
        $course->load(['tutor', 'contents', 'enrollments.student']);

        return view('admin.courses.show', compact('course'));
    }
}
