<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TutorController as AdminTutorController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

// Tutor Controllers
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\ProfileController as TutorProfileController;
use App\Http\Controllers\Tutor\CourseController as TutorCourseController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public course browsing
Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Tutor Management
    Route::get('/tutors', [AdminTutorController::class, 'index'])->name('tutors.index');
    Route::get('/tutors/pending', [AdminTutorController::class, 'pending'])->name('tutors.pending');
    Route::get('/tutors/{tutor}', [AdminTutorController::class, 'show'])->name('tutors.show');
    Route::post('/tutors/{tutor}/verify', [AdminTutorController::class, 'verify'])->name('tutors.verify');
    Route::post('/tutors/{tutor}/reject', [AdminTutorController::class, 'reject'])->name('tutors.reject');

    // Course Management
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/pending', [AdminCourseController::class, 'pending'])->name('courses.pending');
    Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
});

/*
|--------------------------------------------------------------------------
| Tutor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [TutorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [TutorProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/cv', [TutorProfileController::class, 'downloadCv'])->name('profile.cv');

    // Courses
    Route::resource('courses', TutorCourseController::class);
    Route::post('/courses/{course}/content', [TutorCourseController::class, 'addContent'])->name('courses.content.add');
    Route::delete('/courses/{course}/content/{content}', [TutorCourseController::class, 'deleteContent'])->name('courses.content.delete');
    Route::post('/courses/{course}/content/reorder', [TutorCourseController::class, 'reorderContents'])->name('courses.content.reorder');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::get('/my-courses', [StudentCourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/courses/{course}/watch/{content?}', [StudentCourseController::class, 'watch'])->name('courses.watch');

    // Enrollments
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');
    Route::get('/enrollment/{enrollment}/payment', [EnrollmentController::class, 'showPayment'])->name('enrollment.payment');
    Route::post('/enrollment/{enrollment}/payment', [EnrollmentController::class, 'processPayment'])->name('enrollment.payment.process');
    Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my');
});

/*
|--------------------------------------------------------------------------
| Legacy Dashboard Route (redirect based on role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'tutor' => redirect()->route('tutor.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
