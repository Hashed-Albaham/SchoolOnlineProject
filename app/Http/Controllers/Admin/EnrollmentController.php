<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

/**
 * [A6] Admin Enrollment Management Controller
 * 
 * Allows admin to view and manage all enrollments/subscriptions.
 * Created as part of the LARA-PROSKILL-ARCHITECT-v4.0 audit.
 */
class EnrollmentController extends Controller
{
    /**
     * Display all enrollments with filtering.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $enrollmentStatus = $request->get('enrollment_status');
        $search = $request->get('search');

        $query = Enrollment::with(['user', 'course.tutor'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($enrollmentStatus, fn($q) => $q->where('enrollment_status', $enrollmentStatus))
            ->when($search, function ($q) use ($search) {
                $safe = str_replace(['%', '_'], ['\%', '\_'], $search);
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$safe}%"))
                  ->orWhereHas('course', fn($q2) => $q2->where('title', 'like', "%{$safe}%"));
            })
            ->latest();

        // Counts
        $counts = Enrollment::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN enrollment_status = 'approved' THEN 1 ELSE 0 END) as approved_enrollments,
            SUM(CASE WHEN enrollment_status = 'pending_approval' THEN 1 ELSE 0 END) as pending_enrollments
        ")->first();

        $enrollments = $query->paginate(15)->withQueryString();

        // Revenue calculation
        $totalRevenue = Enrollment::where('payment_status', 'paid')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        return view('admin.enrollments.index', compact('enrollments', 'counts', 'status', 'enrollmentStatus', 'search', 'totalRevenue'));
    }

    /**
     * Update enrollment statuses manually.
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $request->validate([
            'payment_status' => ['required', 'string', 'in:paid,pending'],
            'enrollment_status' => ['required', 'string', 'in:pending_approval,approved,rejected'],
        ]);

        $enrollment->payment_status = $request->payment_status;
        $enrollment->enrollment_status = $request->enrollment_status;
        $enrollment->save();

        return back()->with('success', __('site.enrollment_status_updated'));
    }
}
