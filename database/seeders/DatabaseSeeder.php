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
        // Create Admin
        $admin = User::create([
            'name' => 'المسؤول',
            'email' => 'admin@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin->agreed_to_terms_at = now();
        $admin->save();

        // Create Tutors
        $tutor1 = User::create([
            'name' => 'أحمد المعلم',
            'email' => 'tutor@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
        ]);
        $tutor1->agreed_to_terms_at = now();
        $tutor1->save();

        TutorDetail::create([
            'user_id' => $tutor1->id,
            'bio' => 'مطور ويب محترف بخبرة 10 سنوات في تطوير تطبيقات Laravel و PHP',
            'specialization' => 'تطوير الويب',
            'is_verified' => true,
            // [REQ] Qualification fields
            'university' => 'جامعة الملك سعود',
            'graduation_year' => 2020,
            'skills' => 'PHP, Laravel, JavaScript, Vue.js, MySQL, REST APIs, Docker, Git',
            'portfolio_url' => 'https://github.com/ahmed-dev',
            'agreed_to_terms' => true,
        ]);

        $tutor2 = User::create([
            'name' => 'سارة خبيرة البيانات',
            'email' => 'tutor2@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
        ]);
        $tutor2->agreed_to_terms_at = now();
        $tutor2->save();

        TutorDetail::create([
            'user_id' => $tutor2->id,
            'bio' => 'خبيرة في علم البيانات والذكاء الاصطناعي',
            'specialization' => 'علم البيانات',
            'is_verified' => true,
            // [REQ] Qualification fields
            'university' => 'جامعة الأميرة نورة بنت عبدالرحمن',
            'graduation_year' => 2021,
            'skills' => 'Python, TensorFlow, Pandas, NumPy, Machine Learning, Data Visualization, SQL',
            'portfolio_url' => 'https://github.com/sara-data',
            'agreed_to_terms' => true,
        ]);

        // Create unverified tutor (pending verification - [REQ] stays pending until verified)
        $tutor3 = User::create([
            'name' => 'محمد الجديد',
            'email' => 'tutor3@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
        ]);
        $tutor3->agreed_to_terms_at = now();
        $tutor3->save();

        TutorDetail::create([
            'user_id' => $tutor3->id,
            'bio' => 'معلم جديد في انتظار التحقق',
            'specialization' => 'التصميم',
            'is_verified' => false,
            // [REQ] Incomplete qualifications - pending verification
            'university' => 'جامعة الملك عبدالعزيز',
            'graduation_year' => 2024,
            'skills' => 'Figma, Adobe XD, UI/UX, HTML, CSS',
            'agreed_to_terms' => true,
        ]);

        // Create Students
        $student1 = User::create([
            'name' => 'علي الطالب',
            'email' => 'student@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
        $student1->agreed_to_terms_at = now();
        $student1->save();

        $student2 = User::create([
            'name' => 'فاطمة الطالبة',
            'email' => 'student2@proskill.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
        $student2->agreed_to_terms_at = now();
        $student2->save();

        // Create Courses
        $course1 = Course::create([
            'tutor_id' => $tutor1->id,
            'title' => 'احتراف Laravel 11 من الصفر للاحتراف',
            'description' => 'كورس شامل لتعلم Laravel من الصفر حتى بناء تطبيقات متكاملة. يشمل:
- أساسيات PHP و Laravel
- قواعد البيانات و Eloquent ORM
- نظام المصادقة والصلاحيات
- Livewire للواجهات التفاعلية
- API Development',
            'price' => 49.99,
            'status' => 'approved',
        ]);

        // Add contents to course 1
        $contents1 = [
            ['title' => 'مقدمة وتثبيت البيئة', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'بنية مشروع Laravel', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'Routing و Controllers', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'Blade Templates', 'youtube_video_id' => 'dQw4w9WgXcQ'],
            ['title' => 'قواعد البيانات و Migrations', 'youtube_video_id' => 'dQw4w9WgXcQ'],
        ];

        foreach ($contents1 as $order => $content) {
            CourseContent::create([
                'course_id' => $course1->id,
                'title' => $content['title'],
                'youtube_video_id' => $content['youtube_video_id'],
                'order' => $order + 1,
            ]);
        }

        $course2 = Course::create([
            'tutor_id' => $tutor2->id,
            'title' => 'مقدمة في علم البيانات بـ Python',
            'description' => 'تعلم أساسيات علم البيانات باستخدام Python و مكتباتها الشهيرة',
            'price' => 0,
            'status' => 'approved',
        ]);

        CourseContent::create([
            'course_id' => $course2->id,
            'title' => 'مقدمة في Python',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'order' => 1,
        ]);

        $course3 = Course::create([
            'tutor_id' => $tutor1->id,
            'title' => 'JavaScript Modern ES6+',
            'description' => 'تعلم JavaScript الحديثة مع ES6 وما بعدها',
            'price' => 29.99,
            'status' => 'pending',
        ]);

        // Create Enrollments
        Enrollment::create([
            'course_id' => $course1->id,
            'user_id' => $student1->id,
            'payment_status' => 'paid',
        ]);

        Enrollment::create([
            'course_id' => $course2->id,
            'user_id' => $student1->id,
            'payment_status' => 'paid',
        ]);

        Enrollment::create([
            'course_id' => $course1->id,
            'user_id' => $student2->id,
            'payment_status' => 'paid',
        ]);

        $this->command->info('✅ تم إنشاء البيانات التجريبية بنجاح!');
        $this->command->info('');
        $this->command->info('📧 بيانات الدخول:');
        $this->command->info('   المسؤول: admin@proskill.com / password');
        $this->command->info('   المعلم: tutor@proskill.com / password');
        $this->command->info('   الطالب: student@proskill.com / password');
    }
}
