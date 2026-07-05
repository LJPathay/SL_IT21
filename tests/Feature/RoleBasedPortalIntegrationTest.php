<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\UserEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedPortalIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_modules_page_renders_live_module_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $course = Course::create([
            'title' => 'Secure Coding Basics',
            'code' => 'SEC101',
            'description' => 'Intro course',
            'instructor_id' => User::factory()->create(['role' => 'instructor'])->id,
            'level' => 'beginner',
            'capacity' => 50,
            'is_active' => true,
        ]);

        Module::create([
            'title' => 'Input Validation',
            'description' => 'Learn to validate input safely.',
            'category' => 'Web Security',
            'difficulty' => 'beginner',
            'duration_minutes' => 45,
            'course_id' => $course->id,
            'lesson_count' => 4,
            'order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/modules');

        $response->assertOk();
        $response->assertSee('Input Validation');
        $response->assertSee('Web Security');
    }

    public function test_instructor_students_page_renders_enrolled_students_from_database(): void
    {
        $instructor = User::factory()->create([
            'role' => 'instructor',
            'email' => 'instructor@example.com',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student@example.com',
        ]);

        $course = Course::create([
            'title' => 'Phishing Defense',
            'code' => 'PHI201',
            'description' => 'Phishing awareness course',
            'instructor_id' => $instructor->id,
            'level' => 'intermediate',
            'capacity' => 100,
            'is_active' => true,
        ]);

        UserEnrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'module_id' => null,
            'status' => 'active',
            'progress_percentage' => 35,
            'enrolled_at' => now(),
        ]);

        $this->actingAs($instructor);

        $response = $this->get('/instructor/students');

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertSee('Phishing Defense');
    }
}
