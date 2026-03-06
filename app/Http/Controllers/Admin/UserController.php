<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * [A3] Admin User Management Controller
 * 
 * Allows admin to view, edit, and delete all users (students, tutors, admins).
 * Created as part of the LARA-PROSKILL-ARCHITECT-v4.0 audit.
 */
class UserController extends Controller
{
    /**
     * Display a listing of all users with role filtering.
     */
    public function index(Request $request)
    {
        $roleFilter = $request->get('role');
        $search = $request->get('search');

        $query = User::query()
            ->when($roleFilter, fn($q) => $q->where('role', $roleFilter))
            ->when($search, function ($q) use ($search) {
                $safe = str_replace(['%', '_'], ['\%', '\_'], $search);
                $q->where(function ($q2) use ($safe) {
                    $q2->where('name', 'like', "%{$safe}%")
                       ->orWhere('email', 'like', "%{$safe}%");
                });
            })
            ->withCount(['enrollments', 'courses'])
            ->latest();

        // Counts per role (single optimized query)
        $roleCounts = User::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
            SUM(CASE WHEN role = 'tutor' THEN 1 ELSE 0 END) as tutors,
            SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) as students
        ")->first();

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'roleCounts', 'roleFilter', 'search'));
    }

    /**
     * Display user details.
     */
    public function show(User $user)
    {
        $user->load(['enrollments.course', 'courses', 'tutorDetails', 'courseCertificates']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user information.
     * Admin can change name, email, and role. Password only if provided.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:admin,tutor,student'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Handle role change with side effects
        $oldRole = $user->role;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // If changed to tutor, ensure TutorDetail exists
        if ($request->role === 'tutor' && $oldRole !== 'tutor') {
            $user->tutorDetails()->firstOrCreate([], ['is_verified' => false]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('site.user_updated_success'));
    }

    /**
     * Delete a user.
     * Admin cannot delete themselves.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', __('site.cannot_delete_self'));
        }

        // Prevent deleting other admins (safety measure)
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', __('site.cannot_delete_last_admin'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('site.user_deleted_success'));
    }
}
