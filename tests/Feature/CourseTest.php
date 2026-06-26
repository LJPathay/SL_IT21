<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that courses can be created.
     */
    public function test_course_can_be_created(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'A test course description',
            'code' => 'TEST101',
            'instructor_id' => $instructor->id,
            'level' => 'beginner',
            'capacity' => 50,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'code' => 'TEST101',
        ]);
    }

    /**
     * Test that courses have a relationship with instructor.
     */
    public function test_course_belongs_to_instructor(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertEquals($instructor->id, $course->instructor->id);
    }

    /**
     * Test that active scope works correctly.
     */
    public function test_active_scope_filters_active_courses(): void
    {
        Course::factory()->create(['is_active' => true]);
        Course::factory()->create(['is_active' => false]);

        $activeCourses = Course::active()->get();

        $this->assertCount(1, $activeCourses);
    }

    /**
     * Test that courses can be updated.
     */
    public function test_course_can_be_updated(): void
    {
        $course = Course::factory()->create();

        $course->update(['title' => 'Updated Title']);

        $this->assertEquals('Updated Title', $course->fresh()->title);
    }

    /**
     * Test that courses can be soft deleted.
     */
    public function test_course_can_be_soft_deleted(): void
    {
        $course = Course::factory()->create();

        $course->delete();

        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }
}
