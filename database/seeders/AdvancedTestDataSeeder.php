<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\TutorDetail;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Enrollment;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\SessionSlot;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Services\FinancialService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdvancedTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // Use Arabic Faker
        $password = Hash::make('password');
        $financial = app(FinancialService::class);
        $admin = User::where('role', 'admin')->first();
        $paymentMethod = PaymentMethod::first();

        $this->command->info('Creating 10 Random Categories...');
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = Category::create([
                'name' => $faker->catchPhrase,
                'slug' => Str::slug($faker->catchPhrase . ' ' . $i),
                'description' => $faker->realText(50)
            ]);
        }

        $this->command->info('Creating 20 Random Tutors...');
        $tutors = [];
        for ($i = 0; $i < 20; $i++) {
            $tutor = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => $password,
                'role' => 'tutor',
                'agreed_to_terms_at' => now(),
            ]);

            TutorDetail::create([
                'user_id' => $tutor->id,
                'bio' => $faker->realText(100),
                'specialization' => $faker->jobTitle,
                'is_verified' => $faker->boolean(80), // 80% chance verified
                'university' => 'جامعة ' . $faker->word,
                'graduation_year' => $faker->numberBetween(2010, 2024),
                'skills' => implode(', ', $faker->words(4)),
                'agreed_to_terms' => true,
            ]);
            $tutors[] = $tutor;
        }

        $this->command->info('Creating 50 Random Students...');
        $students = [];
        for ($i = 0; $i < 50; $i++) {
            $students[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => $password,
                'role' => 'student',
                'agreed_to_terms_at' => now(),
            ]);
        }

        $this->command->info('Creating 30 Random Courses & Contents & Quizzes...');
        $courses = [];
        foreach ($tutors as $tutor) {
            // Give 80% of verified tutors 2-3 courses
            $tutorDetail = TutorDetail::where('user_id', $tutor->id)->first();
            if ($tutorDetail && $tutorDetail->is_verified) {
                for ($j = 0; $j < rand(1, 3); $j++) {
                    $coursePrice = $faker->randomElement([0, 19.99, 49.99, 99.99]);
                    $course = Course::create([
                        'tutor_id' => $tutor->id,
                        'category_id' => $faker->boolean(80) ? $faker->randomElement($categories)->id : null,
                        'title' => $faker->catchPhrase,
                        'description' => $faker->realText(200),
                        'price' => $coursePrice,
                        'status' => $faker->randomElement(['approved', 'approved', 'pending']), // 66% approved
                    ]);
                    $courses[] = $course;

                    // Course Contents
                    for ($k = 0; $k < rand(3, 8); $k++) {
                        CourseContent::create([
                            'course_id' => $course->id,
                            'title' => 'الدرس ' . ($k + 1) . ': ' . $faker->words(3, true),
                            'youtube_video_id' => 'dQw4w9WgXcQ',
                            'order' => $k + 1,
                        ]);
                    }

                    // 1 Quiz per course
                    $quiz = Quiz::create([
                        'course_id' => $course->id,
                        'title' => 'اختبار نهائي - ' . $faker->words(2, true),
                        'description' => $faker->realText(50),
                        'time_limit_minutes' => rand(15, 60),
                        'pass_percentage' => rand(50, 80),
                        'max_attempts' => rand(1, 3)
                    ]);

                    for ($q = 0; $q < rand(3, 5); $q++) {
                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $faker->realText(30) . '؟',
                            'points' => 1
                        ]);
                        Option::create(['question_id' => $question->id, 'option_text' => $faker->word, 'is_correct' => true]);
                        Option::create(['question_id' => $question->id, 'option_text' => $faker->word, 'is_correct' => false]);
                        Option::create(['question_id' => $question->id, 'option_text' => $faker->word, 'is_correct' => false]);
                    }
                }
            }
        }

        $this->command->info('Creating 100 Enrollments...');
        foreach (array_rand($students, 40) as $studentIndex) { // Pick 40 random students
            $student = $students[$studentIndex];
            $approvedCourses = array_filter($courses, fn($c) => $c->status === 'approved');
            if(empty($approvedCourses)) continue;
            
            // Pick 1-4 random courses per student
            $courseKeys = (array) array_rand($approvedCourses, rand(1, 4));
            
            foreach ($courseKeys as $cKey) {
                $course = $approvedCourses[$cKey];
                $status = $faker->randomElement(['approved', 'approved', 'pending_approval']);
                $payment = $course->price > 0 ? 'paid' : 'paid';

                $enrollment = Enrollment::firstOrCreate(
                    ['course_id' => $course->id, 'user_id' => $student->id],
                    [
                        'enrollment_status' => $status,
                        'payment_status' => $payment
                    ]
                );

                if ($enrollment->payment_status === 'paid' && $enrollment->enrollment_status === 'approved' && $course->price > 0 && $admin) {
                     if (!\App\Models\Transaction::where('enrollment_id', $enrollment->id)->exists()) {
                         $financial->recordEnrollmentPayment($enrollment);
                         $financial->confirmEnrollmentPayment($enrollment, $admin->id);
                     }
                }
            }
        }

        $this->command->info('Creating 50 Session Slots and Random Bookings...');
        // Create random slots for tutors
        $sessionSlots = [];
        foreach (array_rand($tutors, 10) as $tutorIndex) {
            $tutor = $tutors[$tutorIndex];
            $tutorDetail = TutorDetail::where('user_id', $tutor->id)->first();
            if ($tutorDetail && $tutorDetail->is_verified) {
                for ($s = 0; $s < rand(2, 5); $s++) {
                    $type = $faker->randomElement(['1-on-1', 'group']);
                    $cap = $type === '1-on-1' ? 1 : rand(5, 20);
                    $price = $faker->randomElement([0, 20, 50, 100]);
                    
                    // maybe assign to one of his courses
                    $myCourses = Course::where('tutor_id', $tutor->id)->where('status', 'approved')->get();
                    $courseId = ($myCourses->count() > 0 && $faker->boolean(50)) ? $myCourses->random()->id : null;

                    $slot = SessionSlot::create([
                        'tutor_id' => $tutor->id,
                        'course_id' => $courseId,
                        'type' => $type,
                        'price' => $price,
                        'max_participants' => $cap,
                        'start_time' => now()->addDays(rand(1, 14))->setHour(rand(8, 20))->setMinute(0),
                        'end_time' => now()->addDays(rand(1, 14))->setHour(rand(9, 21))->setMinute(0),
                        'meeting_link' => 'https://zoom.us/j/' . rand(100000000, 999999999),
                        'status' => 'scheduled'
                    ]);
                    $sessionSlots[] = $slot;
                }
            }
        }

        // Create Bookings
        foreach (array_rand($sessionSlots, min(30, count($sessionSlots))) as $slotIndex) {
            $slot = $sessionSlots[$slotIndex];
            $student = $faker->randomElement($students);
            
            if ($slot->course_id) {
                // To book it, must be enrolled and approved
                $enrollment = Enrollment::firstOrCreate(
                    ['course_id' => $slot->course_id, 'user_id' => $student->id],
                    [
                        'enrollment_status' => 'approved',
                        'payment_status' => 'paid'
                    ]
                );
            }

            $bStatus = $slot->price > 0 ? $faker->randomElement(['pending', 'confirmed']) : 'confirmed';
            
            $booking = Booking::create([
                'student_id' => $student->id,
                'session_slot_id' => $slot->id,
                'status' => $bStatus,
                'payment_method_id' => $slot->price > 0 ? $paymentMethod?->id : null,
                'locked_until' => $bStatus === 'pending' ? now()->addDays(2) : null
            ]);

            if ($bStatus === 'confirmed' && $slot->price > 0 && $admin) {
                if (!\App\Models\Transaction::where('booking_id', $booking->id)->exists()) {
                    $financial->recordBookingPayment($booking);
                    $financial->confirmBookingPayment($booking, $admin->id);
                }
            }
        }

        $this->command->info('✅ تم توليد كافة البيانات التجريبية الشاملة والمكثفة بنجاح!');
        $this->command->info('يمكنك الآن تصفح المنصة واختبار التصفح ببيانات عشوائية كثيرة جداً.');
    }
}
