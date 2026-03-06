<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PayoutRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * [T1] Tutor Reports & Analytics Controller
 * Shows tutor-specific statistics for their own courses only
 */
class ReportController extends Controller
{
    public function index()
    {
        $tutor = Auth::user();
        $courseIds = $tutor->courses()->pluck('id');

        // ── Course Stats ──
        $courseStats = $tutor->courses()->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        ")->first();

        // ── Student Stats ──
        $totalStudents = $courseIds->isNotEmpty()
            ? Enrollment::whereIn('course_id', $courseIds)
                ->where('payment_status', 'paid')
                ->distinct('user_id')
                ->count('user_id')
            : 0;

        // ── Revenue Stats ──
        $totalEarnings = $courseIds->isNotEmpty()
            ? Enrollment::whereIn('enrollments.course_id', $courseIds)
                ->where('payment_status', 'paid')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->sum('courses.price')
            : 0;

        $totalPaidOut = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PAID)
            ->sum('amount');

        $pendingPayout = PayoutRequest::where('tutor_id', $tutor->id)
            ->where('status', PayoutRequest::STATUS_PENDING)
            ->sum('amount');

        $availableBalance = $totalEarnings - $totalPaidOut - $pendingPayout;

        // ── Average Rating ──
        $avgRating = $courseIds->isNotEmpty()
            ? Review::whereIn('course_id', $courseIds)->avg('rating')
            : 0;
        $totalReviews = $courseIds->isNotEmpty()
            ? Review::whereIn('course_id', $courseIds)->count()
            : 0;

        // ── Monthly Enrollments Chart (last 6 months) ──
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $monthlyEnrollments = $courseIds->isNotEmpty()
            ? Enrollment::whereIn('enrollments.course_id', $courseIds)
                ->where('payment_status', 'paid')
                ->where('enrollments.created_at', '>=', $sixMonthsAgo)
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->select(
                    DB::raw("DATE_FORMAT(enrollments.created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(courses.price) as revenue')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
            : collect();

        // ── Top 5 Courses by Students ──
        $topCourses = $courseIds->isNotEmpty()
            ? Course::whereIn('id', $courseIds)
                ->withCount(['enrollments' => function ($q) {
                    $q->where('payment_status', 'paid');
                }])
                ->withAvg('reviews', 'rating')
                ->orderByDesc('enrollments_count')
                ->take(5)
                ->get()
                ->map(function ($course) {
                    $course->total_revenue = $course->enrollments_count * $course->price;
                    return $course;
                })
            : collect();

        return view('tutor.reports.index', compact(
            'courseStats', 'totalStudents',
            'totalEarnings', 'totalPaidOut', 'pendingPayout', 'availableBalance',
            'avgRating', 'totalReviews',
            'monthlyEnrollments', 'topCourses'
        ));
    }
}
