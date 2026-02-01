<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tutorDetail = $user->tutorDetails;

        $stats = [
            'total_courses' => $user->courses()->count(),
            'approved_courses' => $user->courses()->where('status', 'approved')->count(),
            'pending_courses' => $user->courses()->where('status', 'pending')->count(),
            'total_students' => Enrollment::whereIn('course_id', $user->courses()->pluck('id'))
                ->where('payment_status', 'paid')
                ->distinct('user_id')
                ->count('user_id'),
            'is_verified' => $tutorDetail?->is_verified ?? false,
        ];

        $recentCourses = $user->courses()
            ->with('enrollments')
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::whereIn('course_id', $user->courses()->pluck('id'))
            ->with(['student', 'course'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        return view('tutor.dashboard', compact('stats', 'recentCourses', 'recentEnrollments', 'tutorDetail'));
    }
}
