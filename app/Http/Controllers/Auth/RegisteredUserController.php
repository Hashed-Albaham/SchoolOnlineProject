<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * [v8.0] Added eligibility enforcement for tutor registration.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,tutor'], // Only student/tutor allowed
            'agreed_to_terms' => ['required', 'accepted'], // [REQ] Must agree to terms
        ]);

        // [v8.0] SECURITY: Eligibility enforcement for tutor registration
        if ($request->role === 'tutor') {
            $eligPassed = session('elig_passed');
            $eligExpires = session('elig_expires_at');

            // Reject if no eligibility session or expired
            if (!$eligPassed || !$eligExpires || now()->timestamp > $eligExpires) {
                // Clean up any stale session data
                session()->forget(['elig_passed', 'elig_prefill', 'elig_expires_at']);

                return redirect()->route('eligibility.show')
                    ->with('error', __('site.elig_required_for_tutor'));
            }
        }

        // Use DB::transaction for data integrity
        $user = DB::transaction(function () use ($request) {
            // Create user without role first (Mass Assignment protection)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Set role explicitly after creation (safe because it's validated above)
            $user->role = $request->role;
            $user->agreed_to_terms_at = now(); // [REQ] Record terms agreement
            $user->save();

            if ($request->role === 'tutor') {
                $eligPrefill = session('elig_prefill', []);

                $user->tutorDetails()->create([
                    'is_verified' => false,
                    'agreed_to_terms' => true, // [REQ] Tutor agreed to terms
                    // [v8.0] Store eligibility data from session
                    'gpa' => $eligPrefill['gpa'] ?? null,
                    'gpa_scale' => $eligPrefill['gpa_scale'] ?? null,
                    'step_score' => $eligPrefill['step_score'] ?? null,
                    // [v8.0] Historical fairness: store current requirements
                    'req_gpa_at_registration' => Setting::get('min_gpa'),
                    'req_step_at_registration' => Setting::get('min_step_score'),
                ]);
            }

            return $user;
        });

        // [v8.0] IMMEDIATE FLUSH: Prevent session reuse for another account
        session()->forget(['elig_passed', 'elig_prefill', 'elig_expires_at']);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
