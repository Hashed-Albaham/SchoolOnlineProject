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
    public function index()
    {
        $tutors = User::where('role', 'tutor')
            ->with(['tutorDetails', 'courses'])
            ->latest()
            ->paginate(10);

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
     * Reject/Unverify a tutor
     */
    public function reject(User $tutor)
    {
        if ($tutor->role !== 'tutor') {
            return back()->with('error', 'المستخدم ليس معلماً');
        }

        // FIXED: Null check for tutorDetails
        if (!$tutor->tutorDetails) {
            return back()->with('error', 'لا توجد بيانات للمعلم');
        }

        $tutor->tutorDetails->update(['is_verified' => false]);

        return back()->with('success', 'تم رفض المعلم');
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
