<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_tutors' => User::where('role', 'tutor')->count(),
            'pending_tutors' => User::where('role', 'tutor')
                ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false))
                ->count(),
            'total_courses' => Course::count(),
            'pending_courses' => Course::where('status', 'pending')->count(),
            'approved_courses' => Course::where('status', 'approved')->count(),
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
