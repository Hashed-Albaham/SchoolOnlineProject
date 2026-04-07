<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TutorDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * [A3] Admin User Management Controller
 * [v8.0] Updated with Hierarchical Authorization:
 * - Only Super Admins can create/edit/delete admin accounts
 * - Self-deletion is prohibited
 * - Regular admins can only manage students and tutors
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
                $safe = str_replace(['%', '_'], ['\\%', '\\_'], $search);
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
     * [v8.0] Show form for creating a new user.
     * Only Super Admins can see the 'admin' role option.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * [v8.0] Store a newly created user.
     * Non-Super Admins cannot create admin accounts.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,tutor,student'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // [v8.0] Hierarchical protection: only super admin can create admin
        if ($request->role === 'admin' && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', __('site.only_super_admin_can_create_admin'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Set role explicitly (protected field)
        $user->role = $request->role;
        $user->save();

        // If tutor, create TutorDetail
        if ($request->role === 'tutor') {
            $user->tutorDetails()->create(['is_verified' => false]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('site.user_created_success'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        // [v8.0] Non-super admins cannot edit other admins
        if ($user->role === 'admin' && $user->id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', __('site.only_super_admin_can_edit_admin'));
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user information.
     * [v8.0] Hierarchical protection for admin accounts.
     */
    public function update(Request $request, User $user)
    {
        // [v8.0] Non-super admins cannot edit other admins
        if ($user->role === 'admin' && $user->id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', __('site.only_super_admin_can_edit_admin'));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:admin,tutor,student'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // [v8.0] Cannot change role to admin unless super admin
        if ($request->role === 'admin' && $user->role !== 'admin' && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', __('site.only_super_admin_can_create_admin'));
        }

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
     * [v8.0] Hierarchical protection: only super admin can delete admins.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', __('site.cannot_delete_self'));
        }

        // [v8.0] Prevent deleting admin unless super admin
        if ($user->role === 'admin' && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', __('site.only_super_admin_can_delete_admin'));
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', __('site.cannot_delete_last_admin'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('site.user_deleted_success'));
    }
}
