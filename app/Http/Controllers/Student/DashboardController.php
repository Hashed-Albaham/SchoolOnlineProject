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
        $userId = $user->id;

        // PERFORMANCE FIX: Combine enrollment counts into one query
        // Before: 3 separate COUNT queries
        // After: 1 query with conditional counts
        $enrollmentStats = Enrollment::where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as enrolled_courses,
                SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending_enrollments
            ")
            ->first();

        $stats = [
            'enrolled_courses' => $enrollmentStats->enrolled_courses ?? 0,
            'pending_enrollments' => $enrollmentStats->pending_enrollments ?? 0,
            'earned_certificates' => $user->courseCertificates()->where('status', 'approved')->count(),
        ];

        $enrolledCourses = $user->enrollments()
            ->with(['course.tutor'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(6)
            ->get();

        // PERFORMANCE FIX: Cache enrolled course IDs to avoid subquery
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        $recommendedCourses = Course::where('status', 'approved')
            ->whereNotIn('id', $enrolledCourseIds)
            ->with('tutor')
            ->latest()
            ->take(4)
            ->get();

        return view('student.dashboard', compact('stats', 'enrolledCourses', 'recommendedCourses'));
    }
}
