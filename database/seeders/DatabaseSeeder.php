<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Enrollment;
use App\Models\TutorDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin (Super Admin) — use firstOrCreate to avoid duplicate entry on re-seed
        $admin = User::firstOrCreate(
            ['email' => 'admin@proskill.com'],
            [
                'name' => 'المسؤول',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->agreed_to_terms_at = now();
        $admin->is_super_admin = true; // [v8.0] Genesis
        $admin->save();

        // Create Tutors — use firstOrCreate for re-seed safety
        $tutor1 = User::firstOrCreate(
            ['email' => 'tutor@proskill.com'],
            [
                'name' => 'أحمد المعلم',
                'password' => Hash::make('password'),
                'role' => 'tutor',
            ]
        );
        if (!$tutor1->agreed_to_terms_at) {
            $tutor1->agreed_to_terms_at = now();
            $tutor1->save();
        }

        TutorDetail::firstOrCreate(
            ['user_id' => $tutor1->id],
            [
                'bio' => 'مطور ويب محترف بخبرة 10 سنوات في تطوير تطبيقات Laravel و PHP',
                'specialization' => 'تطوير الويب',
                'is_verified' => true,
                'university' => 'جامعة الملك سعود',
                'graduation_year' => 2020,
                'skills' => 'PHP, Laravel, JavaScript, Vue.js, MySQL, REST APIs, Docker, Git',
                'portfolio_url' => 'https://github.com/ahmed-dev',
                'agreed_to_terms' => true,
                'gpa' => 3.85,
                'gpa_scale' => 4.0,
                'step_score' => 78,
            ]
        );

        $tutor2 = User::firstOrCreate(
            ['email' => 'tutor2@proskill.com'],
            [
                'name' => 'سارة خبيرة البيانات',
                'password' => Hash::make('password'),
                'role' => 'tutor',
            ]
        );
        if (!$tutor2->agreed_to_terms_at) {
            $tutor2->agreed_to_terms_at = now();
            $tutor2->save();
        }

        TutorDetail::firstOrCreate(
            ['user_id' => $tutor2->id],
            [
                'bio' => 'خبيرة في علم البيانات والذكاء الاصطناعي',
                'specialization' => 'علم البيانات',
                'is_verified' => true,
                'university' => 'جامعة الأميرة نورة بنت عبدالرحمن',
                'graduation_year' => 2021,
                'skills' => 'Python, TensorFlow, Pandas, NumPy, Machine Learning, Data Visualization, SQL',
                'portfolio_url' => 'https://github.com/sara-data',
                'agreed_to_terms' => true,
                'gpa' => 4.50,
                'gpa_scale' => 5.0,
                'step_score' => 82,
            ]
        );

        // Create unverified tutor (pending verification)
        $tutor3 = User::firstOrCreate(
            ['email' => 'tutor3@proskill.com'],
            [
                'name' => 'محمد الجديد',
                'password' => Hash::make('password'),
                'role' => 'tutor',
            ]
        );
        if (!$tutor3->agreed_to_terms_at) {
            $tutor3->agreed_to_terms_at = now();
            $tutor3->save();
        }

        TutorDetail::firstOrCreate(
            ['user_id' => $tutor3->id],
            [
                'bio' => 'معلم جديد في انتظار التحقق',
                'specialization' => 'التصميم',
                'is_verified' => false,
                'university' => 'جامعة الملك عبدالعزيز',
                'graduation_year' => 2024,
                'skills' => 'Figma, Adobe XD, UI/UX, HTML, CSS',
                'agreed_to_terms' => true,
            ]
        );

        // Create Students
        $student1 = User::firstOrCreate(
            ['email' => 'student@proskill.com'],
            [
                'name' => 'علي الطالب',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
        if (!$student1->agreed_to_terms_at) {
            $student1->agreed_to_terms_at = now();
            $student1->save();
        }

        $student2 = User::firstOrCreate(
            ['email' => 'student2@proskill.com'],
            [
                'name' => 'فاطمة الطالبة',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
        if (!$student2->agreed_to_terms_at) {
            $student2->agreed_to_terms_at = now();
            $student2->save();
        }

        // Create Courses
        $course1 = Course::firstOrCreate(
            ['title' => 'احتراف Laravel 11 من الصفر للاحتراف'],
            [
                'tutor_id' => $tutor1->id,
                'description' => 'كورس شامل لتعلم Laravel من الصفر حتى بناء تطبيقات متكاملة. يشمل:
- أساسيات PHP و Laravel
- قواعد البيانات و Eloquent ORM
- نظام المصادقة والصلاحيات
- Livewire للواجهات التفاعلية
- API Development',
                'price' => 49.99,
                'status' => 'approved',
            ]
        );

        // Add contents to course 1
        $contents1 = [
            ['title' => 'مقدمة وتثبيت البيئة', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'بنية مشروع Laravel', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'Routing و Controllers', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'Blade Templates', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'قواعد البيانات و Migrations', 'youtube_video_id' => 'dQw4w9WgXcQ'],
        ];

        foreach ($contents1 as $order => $content) {
            CourseContent::firstOrCreate(
                ['course_id' => $course1->id, 'title' => $content['title']],
                [
                    'youtube_video_id' => $content['youtube_video_id'],
                    'order' => $order + 1,
                ]
            );
        }

        $course2 = Course::firstOrCreate(
            ['title' => 'مقدمة في علم البيانات بـ Python'],
            [
                'tutor_id' => $tutor2->id,
                'description' => 'تعلم أساسيات علم البيانات باستخدام Python و مكتباتها الشهيرة',
                'price' => 0,
                'status' => 'approved',
            ]
        );

        CourseContent::firstOrCreate(
            ['course_id' => $course2->id, 'title' => 'مقدمة في Python'],
            [
                'youtube_video_id' => 'dQw4w9WgXcQ',
                'order' => 1,
            ]
        );

        $course3 = Course::firstOrCreate(
            ['title' => 'JavaScript Modern ES6+'],
            [
                'tutor_id' => $tutor1->id,
                'description' => 'تعلم JavaScript الحديثة مع ES6 وما بعدها',
                'price' => 29.99,
                'status' => 'pending',
            ]
        );

        // Create Enrollments and Financial Transactions
        $financial = app(\App\Services\FinancialService::class);
        $processEnrollment = function ($course, $student) use ($admin, $financial) {
            $enrollment = Enrollment::firstOrCreate(
                ['course_id' => $course->id, 'user_id' => $student->id],
                ['enrollment_status' => 'approved']
            );
            
            if ($enrollment->payment_status !== 'paid') {
                $enrollment->payment_status = 'paid';
                $enrollment->save();

                $exists = \App\Models\Transaction::where('enrollment_id', $enrollment->id)
                    ->where('type', 'enrollment')
                    ->exists();

                if (!$exists) {
                    $financial->recordEnrollmentPayment($enrollment);
                    $financial->confirmEnrollmentPayment($enrollment, $admin->id);
                }
            }
        };

        // Create Quizzes
        $quiz1 = \App\Models\Quiz::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'اختبار منتصف الفصل'],
            [
                'description' => 'اختبار شامل لأساسيات لارافل',
                'time_limit_minutes' => 30,
                'pass_percentage' => 70,
                'max_attempts' => 3
            ]
        );

        $question1 = \App\Models\Question::firstOrCreate(
            ['quiz_id' => $quiz1->id, 'question_text' => 'ما هو إطار عمل لارافل (Laravel)؟'],
            ['points' => 1]
        );
        \App\Models\Option::firstOrCreate(['question_id' => $question1->id, 'option_text' => 'إطار عمل PHP'], ['is_correct' => true]);
        \App\Models\Option::firstOrCreate(['question_id' => $question1->id, 'option_text' => 'مكتبة جافاسكربت'], ['is_correct' => false]);
        \App\Models\Option::firstOrCreate(['question_id' => $question1->id, 'option_text' => 'قاعدة بيانات'], ['is_correct' => false]);

        $quiz_course2 = \App\Models\Quiz::firstOrCreate(
            ['course_id' => $course2->id, 'title' => 'اختبار بايثون الأساسي'],
            [
                'description' => 'اختبار أساسيات بايثون وعلم البيانات',
                'time_limit_minutes' => 20,
                'pass_percentage' => 60,
                'max_attempts' => 2
            ]
        );

        // Create Payment Method
        $paymentMethod = \App\Models\PaymentMethod::firstOrCreate(
            ['name' => 'Bank Transfer'],
            ['name_ar' => 'تحويل بنكي', 'is_active' => true, 'type' => 'bank_transfer', 'account_number' => 'SA123456789', 'account_name' => 'ProSkill Co', 'instructions_ar' => 'يرجى التحويل لحساب المؤسسة أدناه']
        );

        // Process Enrollments
        $processEnrollment = function ($course, $student, $status = 'approved', $payment = 'paid') use ($admin, $financial) {
            $enrollment = Enrollment::firstOrCreate(
                ['course_id' => $course->id, 'user_id' => $student->id],
                ['enrollment_status' => $status, 'payment_status' => $payment]
            );
            
            if ($enrollment->payment_status === 'paid' && $enrollment->enrollment_status === 'approved') {
                $exists = \App\Models\Transaction::where('enrollment_id', $enrollment->id)
                    ->where('type', 'enrollment')
                    ->exists();

                if (!$exists) {
                    $financial->recordEnrollmentPayment($enrollment);
                    $financial->confirmEnrollmentPayment($enrollment, $admin->id);
                }
            }
        };

        // Student 1 Enrollments
        $processEnrollment($course1, $student1, 'approved', 'paid');
        $processEnrollment($course2, $student1, 'approved', 'paid'); // Free course
        $processEnrollment($course3, $student1, 'pending_approval', 'pending'); 

        // Student 2 Enrollments
        $processEnrollment($course1, $student2, 'approved', 'paid');
        $processEnrollment($course3, $student2, 'rejected', 'failed');

        // Create Payout Request for Tutor 1
        $tutor1Detail = TutorDetail::where('user_id', $tutor1->id)->first();
        if ($tutor1Detail && $tutor1Detail->available_balance > 10) {
            $payoutAmount = min(50, $tutor1Detail->available_balance);
            
            $payout = \App\Models\PayoutRequest::firstOrCreate(
                ['tutor_id' => $tutor1->id, 'status' => 'pending'],
                [
                    'amount' => $payoutAmount,
                    'payment_method_id' => $paymentMethod->id,
                    'tutor_notes' => 'يرجى تحويل الأرباح إلى حسابي البنكي المسجل',
                ]
            );
            
            if ($payout->wasRecentlyCreated) {
                // Deduct from available balance to simulate requested amount logic accurately
                $tutor1Detail->available_balance -= $payoutAmount;
                $tutor1Detail->save();
                
                \App\Models\Transaction::create([
                    'reference_number' => \App\Models\Transaction::generateReference(),
                    'tutor_id' => $tutor1->id,
                    'payout_request_id' => $payout->id,
                    'type' => 'payout',
                    'gross_amount' => $payoutAmount,
                    'platform_fee_amount' => 0,
                    'tutor_amount' => $payoutAmount,
                    'status' => 'pending',
                    'payment_method_id' => $paymentMethod->id,
                ]);
            }
        }

        $this->command->info('✅ تم إنشاء البيانات التجريبية بنجاح!');
        $this->command->info('');
        $this->command->info('📧 بيانات الدخول:');
        $this->command->info('   المسؤول: admin@proskill.com / password (سوبر أدمن)');
        $this->command->info('   المعلم: tutor@proskill.com / password');
        $this->command->info('   الطالب: student@proskill.com / password');

        // [v8.0] Seed default settings
        $this->call(SettingsSeeder::class);
    }
}
