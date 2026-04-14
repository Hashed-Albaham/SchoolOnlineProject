<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TutorDetail;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * Display a listing of tutors
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'tutor')
            ->with(['tutorDetails', 'courses']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by Specialization
        if ($request->filled('specialization')) {
            $query->whereHas('tutorDetails', function($q) use ($request) {
                $q->where('specialization', 'like', "%{$request->specialization}%");
            });
        }

        // Filter by verification status
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereHas('tutorDetails', fn($q) => $q->where('is_verified', true));
            } elseif ($request->status === 'pending') {
                $query->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false));
            }
        }

        $tutors = $query->latest()->paginate(10)->withQueryString();

        $allCount = User::where('role', 'tutor')->count();

        $verifiedCount = User::where('role', 'tutor')
            ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', true))
            ->count();

        $pendingCount = User::where('role', 'tutor')
            ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false))
            ->count();

        return view('admin.tutors.index', compact('tutors', 'allCount', 'verifiedCount', 'pendingCount'));
    }

    /**
     * Display pending tutors for verification
     */
    public function pending()
    {
        $tutors = User::where('role', 'tutor')
            ->with(['tutorDetails', 'courses'])
            ->whereHas('tutorDetails', fn($q) => $q->where('is_verified', false))
            ->latest()
            ->paginate(10);

        return view('admin.tutors.pending', compact('tutors'));
    }

    /**
     * Verify a tutor
     */
    public function verify(User $tutor)
    {
        if ($tutor->role !== 'tutor') {
            return back()->with('error', 'المستخدم ليس معلماً');
        }

        // FIXED: Null check for tutorDetails
        if (!$tutor->tutorDetails) {
            return back()->with('error', 'لا توجد بيانات للمعلم');
        }

        $tutor->tutorDetails->update(['is_verified' => true]);

        return back()->with('success', 'تم التحقق من المعلم بنجاح');
    }

    /**
     * Reject/Unverify a tutor and reject all their courses
     */
    public function reject(User $tutor)
    {
        if ($tutor->role !== 'tutor') {
            return back()->with('error', __('site.not_a_tutor'));
        }

        if (!$tutor->tutorDetails) {
            return back()->with('error', __('site.no_tutor_data'));
        }

        $tutor->tutorDetails->update(['is_verified' => false]);

        // Reject all tutor's courses
        $tutor->courses()->update(['status' => 'rejected']);

        return back()->with('success', __('site.tutor_rejected_courses_revoked'));
    }

    /**
     * Approve all courses for a tutor
     */
    public function approveAllCourses(User $tutor)
    {
        if ($tutor->role !== 'tutor') {
            return back()->with('error', __('site.not_a_tutor'));
        }

        $tutor->courses()->update(['status' => 'approved']);

        return back()->with('success', __('site.all_courses_approved'));
    }

    /**
     * Show tutor details
     */
    public function show(User $tutor)
    {
        if ($tutor->role !== 'tutor') {
            abort(404);
        }

        $tutor->load(['tutorDetails', 'courses.contents']);

        return view('admin.tutors.show', compact('tutor'));
    }
}
