<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * [v8.0] Eligibility Check Controller
 * Allows visitors (and registered users) to check tutor eligibility
 * before registering as a tutor.
 */
class EligibilityController extends Controller
{
    /**
     * Show eligibility check form.
     */
    public function show()
    {
        return view('eligibility.check');
    }

    /**
     * Check eligibility against dynamic settings.
     * On success: store session data for registration auto-fill.
     * On failure: return with validation errors.
     */
    public function check(Request $request)
    {
        $request->validate([
            'gpa' => ['required', 'numeric', 'min:0', 'max:5'],
            'gpa_scale' => ['required', 'numeric', 'in:4.0,5.0'],
            'step_score' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        // Fetch requirements from cache/settings
        $minGpa = (float) Setting::get('min_gpa', 0);
        $minGpaScale = (float) Setting::get('min_gpa_scale', 4.0);
        $minStepScore = (int) Setting::get('min_step_score', 0);

        $inputGpa = (float) $request->gpa;
        $inputGpaScale = (float) $request->gpa_scale;
        $inputStepScore = (int) $request->step_score;

        // Normalize GPA to the required scale for fair comparison
        $normalizedGpa = $inputGpa;
        if ($inputGpaScale != $minGpaScale && $minGpaScale > 0) {
            $normalizedGpa = ($inputGpa / $inputGpaScale) * $minGpaScale;
        }

        $errors = [];

        if ($minGpa > 0 && $normalizedGpa < $minGpa) {
            $errors['gpa'] = __('site.elig_gpa_fail', [
                'min' => $minGpa,
                'scale' => $minGpaScale,
            ]);
        }

        if ($minStepScore > 0 && $inputStepScore < $minStepScore) {
            $errors['step_score'] = __('site.elig_step_fail', [
                'min' => $minStepScore,
            ]);
        }

        if (!empty($errors)) {
            return back()
                ->withInput()
                ->withErrors($errors)
                ->with('elig_failed', true);
        }

        // ✅ Success - Store session data
        session([
            'elig_passed' => true,
            'elig_prefill' => [
                'gpa' => $inputGpa,
                'gpa_scale' => $inputGpaScale,
                'step_score' => $inputStepScore,
            ],
            'elig_expires_at' => now()->addHour()->timestamp,
        ]);

        return redirect()->route('register')
            ->with('elig_success', true);
    }
}
