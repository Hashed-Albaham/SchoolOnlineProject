<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * [A7] Admin Reports & Analytics Controller
 * Detailed reports with revenue breakdown, date filters, and statistics
 */
class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter (default: last 12 months)
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // ── Revenue Stats ──
        $totalRevenue = Enrollment::where('payment_status', 'paid')
            ->whereBetween('enrollments.created_at', [$startDate, $endDate])
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $totalEnrollments = Enrollment::whereBetween('created_at', [$startDate, $endDate])->count();
        $paidEnrollments = Enrollment::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        // ── Monthly Revenue (for chart) ──
        $monthlyRevenue = Enrollment::where('payment_status', 'paid')
            ->whereBetween('enrollments.created_at', [$startDate, $endDate])
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->select(
                DB::raw("DATE_FORMAT(enrollments.created_at, '%Y-%m') as month"),
                DB::raw('SUM(courses.price) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Monthly New Users ──
        $monthlyUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Top Courses by Revenue ──
        $topCourses = Course::with('tutor')->withCount(['enrollments' => function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->whereHas('enrollments', function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($course) {
                $course->total_revenue = $course->enrollments_count * $course->price;
                return $course;
            })
            ->sortByDesc('total_revenue')
            ->take(10);

        // ── Users Stats ──
        $usersStats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'tutors' => User::where('role', 'tutor')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'new_this_month' => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        // ── Courses Stats ──
        $coursesStats = [
            'total' => Course::count(),
            'approved' => Course::where('status', 'approved')->count(),
            'pending' => Course::where('status', 'pending')->count(),
            'rejected' => Course::where('status', 'rejected')->count(),
        ];

        // ── Category Distribution ──
        $categoryStats = Category::withCount(['courses' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->where('is_active', true)
            ->orderByDesc('courses_count')
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalEnrollments', 'paidEnrollments',
            'monthlyRevenue', 'monthlyUsers', 'topCourses',
            'usersStats', 'coursesStats', 'categoryStats',
            'startDate', 'endDate'
        ));
    }
}
