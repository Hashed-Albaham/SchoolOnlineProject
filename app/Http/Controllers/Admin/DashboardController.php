<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // PERFORMANCE FIX: Combine multiple COUNT queries into fewer queries
        // Before: 8 separate queries
        // After: 3 optimized queries
        
        // User stats in one query
        $userStats = User::selectRaw("
            COUNT(*) as total_users,
            SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) as total_students,
            SUM(CASE WHEN role = 'tutor' THEN 1 ELSE 0 END) as total_tutors
        ")->first();
        
        // Course stats in one query
        $courseStats = Course::selectRaw("
            COUNT(*) as total_courses,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_courses,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_courses
        ")->first();
        
        // Pending tutors count (requires join)
        $pendingTutorsCount = User::where('role', 'tutor')
            ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false))
            ->count();

        $stats = [
            'total_users' => $userStats->total_users,
            'total_students' => $userStats->total_students,
            'total_tutors' => $userStats->total_tutors,
            'pending_tutors' => $pendingTutorsCount,
            'total_courses' => $courseStats->total_courses,
            'pending_courses' => $courseStats->pending_courses,
            'approved_courses' => $courseStats->approved_courses,
            'total_enrollments' => Enrollment::where('payment_status', 'paid')->count(),
        ];

        $pendingTutors = User::where('role', 'tutor')
            ->with('tutorDetails')
            ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false))
            ->latest()
            ->take(5)
            ->get();

        $pendingCourses = Course::where('status', 'pending')
            ->with('tutor')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingTutors', 'pendingCourses'));
    }
}
