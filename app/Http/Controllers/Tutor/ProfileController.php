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
            // [REQ] Qualification fields
            'university' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'degree_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'skills' => 'nullable|string|max:1000',
            'portfolio_url' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();

        // Create or get tutor details
        $tutorDetail = $user->tutorDetails ?? new TutorDetail(['user_id' => $user->id]);

        $tutorDetail->bio = $request->bio;
        $tutorDetail->specialization = $request->specialization;

        // [REQ] Qualification fields
        $tutorDetail->university = $request->university;
        $tutorDetail->graduation_year = $request->graduation_year;
        $tutorDetail->skills = $request->skills;
        $tutorDetail->portfolio_url = $request->portfolio_url;

        // Handle CV upload
        if ($request->hasFile('cv')) {
            if ($tutorDetail->cv_path) {
                Storage::disk('public')->delete($tutorDetail->cv_path);
            }
            $path = $request->file('cv')->store('cvs', 'public');
            $tutorDetail->cv_path = $path;
        }

        // [REQ] Handle Degree Certificate upload
        if ($request->hasFile('degree_certificate')) {
            if ($tutorDetail->degree_certificate_path) {
                Storage::disk('public')->delete($tutorDetail->degree_certificate_path);
            }
            $path = $request->file('degree_certificate')->store('certificates', 'public');
            $tutorDetail->degree_certificate_path = $path;
        }

        $tutorDetail->save();

        if (!$tutorDetail->is_verified) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                /** @var \App\Models\User $admin */
                $admin->notify(new \App\Notifications\TutorVerificationRequested($user));
            }
        }

        return back()->with('success', __('site.profile_updated_success'));
    }

    /**
     * Download CV
     */
    public function downloadCv()
    {
        $user = Auth::user();
        $tutorDetail = $user->tutorDetails;

        if (!$tutorDetail || !$tutorDetail->cv_path) {
            return back()->with('error', __('site.no_cv_available'));
        }

        return response()->download(Storage::disk('public')->path($tutorDetail->cv_path));
    }
}
