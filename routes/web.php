<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EligibilityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| SECURITY FIX [C2]: Rate Limiting for Sensitive Routes
|--------------------------------------------------------------------------
*/
RateLimiter::for('enroll', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('payment', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('messaging', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
});

// [v8.0] Rate limiter for settings updates
RateLimiter::for('settings', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

// [v8.0] Rate limiter for eligibility checks
RateLimiter::for('eligibility', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Localized Routes (with /ar and /en prefix)
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

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

    // [PP1] Public Pages
    Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');
    Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');

    // [v8.0] Eligibility Check (accessible to everyone)
    Route::get('/eligibility-check', [EligibilityController::class, 'show'])->name('eligibility.show');
    Route::middleware('throttle:eligibility')->group(function () {
        Route::post('/eligibility-check', [EligibilityController::class, 'check'])->name('eligibility.check');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        // Messaging Routes (SECURITY FIX [C2]: Rate Limited)
        Route::middleware('throttle:messaging')->group(function () {
            Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
            Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
        });

        // Certificate Routes
        Route::get('/certificate/verify/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');
        Route::get('/certificate/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificate.show');
        Route::get('/my-certificates', [App\Http\Controllers\CertificateController::class, 'myCertificates'])
            ->middleware(['auth'])
            ->name('student.certificates');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/notifications/mark-as-read', [ProfileController::class, 'markAsRead'])->name('notifications.markAsRead');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // [A3] User Management + [v8.0] Create user
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Tutor Management
        Route::get('/tutors', [AdminTutorController::class, 'index'])->name('tutors.index');
        Route::get('/tutors/pending', [AdminTutorController::class, 'pending'])->name('tutors.pending');
        Route::get('/tutors/{tutor}', [AdminTutorController::class, 'show'])->name('tutors.show');
        Route::post('/tutors/{tutor}/verify', [AdminTutorController::class, 'verify'])->name('tutors.verify');
        Route::post('/tutors/{tutor}/reject', [AdminTutorController::class, 'reject'])->name('tutors.reject');
        Route::post('/tutors/{tutor}/approve-all-courses', [AdminTutorController::class, 'approveAllCourses'])->name('tutors.approveAllCourses');

        // Course Management [A4] with edit/delete
        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/pending', [AdminCourseController::class, 'pending'])->name('courses.pending');
        Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
        Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
        Route::post('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
        Route::post('/courses/{course}/unapprove', [AdminCourseController::class, 'unapprove'])->name('courses.unapprove');
        // [A9] Review Management
        Route::delete('/courses/{course}/reviews/{review}', [AdminCourseController::class, 'deleteReview'])->name('courses.reviews.destroy');
        // [A10] Admin Content/Lesson Management
        Route::delete('/courses/{course}/content/{content}', [AdminCourseController::class, 'deleteContent'])->name('courses.content.destroy');

        // [A6] Enrollment/Subscription Management
        Route::get('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::patch('/enrollments/{enrollment}/status', [\App\Http\Controllers\Admin\EnrollmentController::class, 'updateStatus'])->name('enrollments.updateStatus');

        // [A5] Category Management
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // [A7] Reports & Analytics
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        // [PAY1] Payment Methods Management
        Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class)->names([
            'index'   => 'payment_methods.index',
            'create'  => 'payment_methods.create',
            'store'   => 'payment_methods.store',
            'edit'    => 'payment_methods.edit',
            'update'  => 'payment_methods.update',
            'destroy' => 'payment_methods.destroy',
        ]);
        Route::post('/payment-methods/{paymentMethod}/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggle'])->name('payment_methods.toggle');

        // [PAY2] Admin Payout Management
        Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{payoutRequest}/approve', [\App\Http\Controllers\Admin\PayoutController::class, 'approve'])->name('payouts.approve');
        Route::post('/payouts/{payoutRequest}/reject', [\App\Http\Controllers\Admin\PayoutController::class, 'reject'])->name('payouts.reject');
        Route::post('/payouts/{payoutRequest}/mark-paid', [\App\Http\Controllers\Admin\PayoutController::class, 'markPaid'])->name('payouts.markPaid');

        // [A8] Admin Chat Oversight
        Route::get('/chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/{user1}/{user2}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('chat.show');
        Route::delete('/chat/messages/{message}', [\App\Http\Controllers\Admin\ChatController::class, 'destroyMessage'])->name('chat.destroyMessage');

        // [v8.0] Super Admin Settings (protected by super_admin middleware + throttle)
        Route::middleware(['super_admin', 'throttle:settings'])->group(function () {
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Tutor Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:tutor,admin'])->prefix('tutor')->name('tutor.')->group(function () {
        Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [TutorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [TutorProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/cv', [TutorProfileController::class, 'downloadCv'])->name('profile.cv');

        // Courses
        Route::resource('courses', TutorCourseController::class);
        Route::post('/courses/{course}/content', [TutorCourseController::class, 'addContent'])->name('courses.content.add');
        Route::get('/courses/{course}/content/{content}/edit', [TutorCourseController::class, 'editContent'])->name('courses.content.edit');
        Route::put('/courses/{course}/content/{content}', [TutorCourseController::class, 'updateContent'])->name('courses.content.update');
        Route::delete('/courses/{course}/content/{content}', [TutorCourseController::class, 'deleteContent'])->name('courses.content.delete');
        Route::post('/courses/{course}/content/reorder', [TutorCourseController::class, 'reorderContents'])->name('courses.content.reorder');

        // Quiz Management
        Route::resource('courses.quizzes', \App\Http\Controllers\Tutor\QuizController::class);
        Route::get('/courses/{course}/quizzes/{quiz}/builder', [\App\Http\Controllers\Tutor\QuizController::class, 'builder'])->name('courses.quizzes.builder');
        Route::get('/courses/{course}/quizzes/{quiz}/results', [\App\Http\Controllers\Tutor\QuizController::class, 'results'])->name('courses.quizzes.results');
        Route::get('/courses/{course}/quizzes/{quiz}/attempts/{attempt}', [\App\Http\Controllers\Tutor\QuizController::class, 'showAttempt'])->name('courses.quizzes.attempts.show');
        Route::delete('/courses/{course}/quizzes/{quiz}/attempts', [\App\Http\Controllers\Tutor\QuizController::class, 'clearAttempts'])->name('courses.quizzes.attempts.clear');
        Route::delete('/courses/{course}/quizzes/{quiz}/attempts/{attempt}', [\App\Http\Controllers\Tutor\QuizController::class, 'deleteAttempt'])->name('courses.quizzes.attempts.delete');
        Route::post('/courses/{course}/quizzes/{quiz}/questions', [\App\Http\Controllers\Tutor\QuizController::class, 'storeQuestion'])->name('courses.quizzes.questions.store');
        Route::delete('/courses/{course}/quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\Tutor\QuizController::class, 'destroyQuestion'])->name('courses.quizzes.questions.destroy');

        // Certificate Management
        Route::get('/certificates', [TutorCourseController::class, 'certificatesIndex'])->name('certificates.index');
        Route::post('/certificates/{certificate}/issue', [TutorCourseController::class, 'issueCertificate'])->name('certificates.issue');
        Route::post('/certificates/{certificate}/reject', [TutorCourseController::class, 'rejectCertificate'])->name('certificates.reject');
        Route::post('/certificates/{certificate}/revoke', [TutorCourseController::class, 'revokeCertificate'])->name('certificates.revoke');

        // [E1] Enrollment Approval Management
        Route::get('/enrollments', [TutorCourseController::class, 'enrollmentsIndex'])->name('enrollments.index');
        Route::post('/enrollments/{enrollment}/approve', [TutorCourseController::class, 'approveEnrollment'])->name('enrollments.approve');
        Route::post('/enrollments/{enrollment}/reject', [TutorCourseController::class, 'rejectEnrollment'])->name('enrollments.reject');

        // [T1] Tutor Reports & Analytics
        Route::get('/reports', [\App\Http\Controllers\Tutor\ReportController::class, 'index'])->name('reports.index');

        // [PAY2] Tutor Payout Requests
        Route::get('/payouts', [\App\Http\Controllers\Tutor\PayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts', [\App\Http\Controllers\Tutor\PayoutController::class, 'store'])->name('payouts.store');
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

        // Enrollments (SECURITY FIX [C2]: Rate Limited)
        Route::middleware('throttle:enroll')->group(function () {
            Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');
        });
        Route::get('/enrollment/{enrollment}/payment', [EnrollmentController::class, 'showPayment'])->name('enrollment.payment');
        Route::middleware('throttle:payment')->group(function () {
            Route::post('/enrollment/{enrollment}/payment', [EnrollmentController::class, 'processPayment'])->name('enrollment.payment.process');
        });
        Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my');

        // Quizzes
        Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}', [QuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/result', [QuizController::class, 'result'])->name('quizzes.result');

        // Certificates
        Route::get('/certificates/{attempt}', [CertificateController::class, 'show'])->name('certificates.show');

        // Progress & Certificates
        Route::post('/courses/{course}/content/{content}/complete', [StudentCourseController::class, 'markComplete'])->name('courses.content.complete');
        Route::post('/courses/{course}/request-certificate', [StudentCourseController::class, 'requestCertificate'])->name('courses.certificate.request');

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

}); // End of localization group

