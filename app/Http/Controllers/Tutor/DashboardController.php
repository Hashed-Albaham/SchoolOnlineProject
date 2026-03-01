<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCertificate;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tutorDetail = $user->tutorDetails;

        // Course stats
        $courseStats = $user->courses()->selectRaw("
            COUNT(*) as total_courses,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_courses,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_courses
        ")->first();

        $courseIds = $user->courses()->pluck('id');
        $totalStudents = $courseIds->isNotEmpty() 
            ? Enrollment::whereIn('course_id', $courseIds)
                ->where('payment_status', 'paid')
                ->distinct('user_id')
                ->count('user_id')
            : 0;

        // Certificate stats
        $pendingCertificates = $courseIds->isNotEmpty()
            ? CourseCertificate::whereIn('course_id', $courseIds)
                ->where('status', 'pending')
                ->count()
            : 0;

        $issuedCertificates = $courseIds->isNotEmpty()
            ? CourseCertificate::whereIn('course_id', $courseIds)
                ->where('status', 'approved')
                ->count()
            : 0;

        $stats = [
            'total_courses' => $courseStats->total_courses ?? 0,
            'approved_courses' => $courseStats->approved_courses ?? 0,
            'pending_courses' => $courseStats->pending_courses ?? 0,
            'total_students' => $totalStudents,
            'is_verified' => $tutorDetail?->is_verified ?? false,
            'pending_certificates' => $pendingCertificates,
            'issued_certificates' => $issuedCertificates,
        ];

        $recentCourses = $user->courses()
            ->with('enrollments')
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = $courseIds->isNotEmpty()
            ? Enrollment::whereIn('course_id', $courseIds)
                ->with(['user', 'course'])
                ->where('payment_status', 'paid')
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('tutor.dashboard', compact('stats', 'recentCourses', 'recentEnrollments', 'tutorDetail'));
    }
}

