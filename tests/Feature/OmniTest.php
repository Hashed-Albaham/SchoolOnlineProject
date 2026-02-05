<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OmniTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            \Mcamara\LaravelLocalization\Middleware\LocaleViewPath::class,
        ]);
    }

    /**
     * Test that the application homepage is accessible.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the courses index page is accessible.
     */
    public function test_the_courses_page_is_accessible(): void
    {
        $response = $this->get('/courses');

        $response->assertStatus(200);
    }

    /**
     * Test that the admin dashboard is accessible for an admin user.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that the tutor dashboard is accessible for a tutor user.
     */
    public function test_tutor_can_access_dashboard(): void
    {
        $tutor = User::factory()->create([
            'role' => 'tutor',
        ]);

        $response = $this->actingAs($tutor)->get('/tutor/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that the student dashboard is accessible for a student user.
     */
    public function test_student_can_access_dashboard(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->actingAs($student)->get('/student/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test database seeding logic via a quick check of roles.
     */
    public function test_default_roles_exist_in_database_after_seed(): void
    {
        // Run the seeder
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@proskill.com',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'tutor@proskill.com',
            'role' => 'tutor',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'student@proskill.com',
            'role' => 'student',
        ]);
    }
}
