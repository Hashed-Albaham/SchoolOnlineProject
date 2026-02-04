<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile form
     */
    public function edit()
    {
        $user = Auth::user();
        $tutorDetail = $user->tutorDetails ?? new TutorDetail();

        return view('tutor.profile.edit', compact('user', 'tutorDetail'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'specialization' => 'nullable|string|max:255',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Create or get tutor details
        $tutorDetail = $user->tutorDetails ?? new TutorDetail(['user_id' => $user->id]);

        $tutorDetail->bio = $request->bio;
        $tutorDetail->specialization = $request->specialization;

        // Handle CV upload
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($tutorDetail->cv_path) {
                Storage::disk('public')->delete($tutorDetail->cv_path);
            }

            $path = $request->file('cv')->store('cvs', 'public');
            $tutorDetail->cv_path = $path;
        }

        $tutorDetail->save();

        if (!$tutorDetail->is_verified) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TutorVerificationRequested($user));
            }
        }

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Download CV
     */
    public function downloadCv()
    {
        $user = Auth::user();
        $tutorDetail = $user->tutorDetails;

        if (!$tutorDetail || !$tutorDetail->cv_path) {
            return back()->with('error', 'لا يوجد سيرة ذاتية');
        }

        return response()->download(Storage::disk('public')->path($tutorDetail->cv_path));
    }
}
