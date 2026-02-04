<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\TutorDetail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total Users: " . User::count() . "\n";
echo "Total Students: " . User::where('role', 'student')->count() . "\n";
echo "Total Tutors: " . User::where('role', 'tutor')->count() . "\n";
echo "Total Courses: " . Course::count() . "\n";
echo "Pending Courses: " . Course::where('status', 'pending')->count() . "\n";
echo "Total Enrollments (all): " . Enrollment::count() . "\n";
echo "Total Enrollments (paid): " . Enrollment::where('payment_status', 'paid')->count() . "\n";

echo "Tutor Details count: " . TutorDetail::count() . "\n";
echo "Unverified Tutors (via relation): " . User::where('role', 'tutor')->whereHas('tutorDetails', function ($q) {
    $q->where('is_verified', false); })->count() . "\n";
