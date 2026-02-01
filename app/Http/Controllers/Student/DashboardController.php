<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'enrolled_courses' => $user->enrollments()->where('payment_status', 'paid')->count(),
            'pending_enrollments' => $user->enrollments()->where('payment_status', 'pending')->count(),
        ];

        $enrolledCourses = $user->enrollments()
            ->with('course.tutor')
            ->where('payment_status', 'paid')
            ->latest()
            ->take(6)
            ->get()
            ->pluck('course');

        $recommendedCourses = Course::where('status', 'approved')
            ->whereNotIn('id', $user->enrollments()->pluck('course_id'))
            ->with('tutor')
            ->latest()
            ->take(4)
            ->get();

        return view('student.dashboard', compact('stats', 'enrolledCourses', 'recommendedCourses'));
    }
}
