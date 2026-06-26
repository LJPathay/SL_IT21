<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that modules can be created.
     */
    public function test_module_can_be_created(): void
    {
        $course = Course::factory()->create();

        $module = Module::create([
            'title' => 'Test Module',
            'description' => 'A test module description',
            'category' => 'security',
            'difficulty' => 'beginner',
            'duration_minutes' => 60,
            'course_id' => $course->id,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('modules', [
            'title' => 'Test Module',
            'category' => 'security',
        ]);
    }

    /**
     * Test that modules have a relationship with course.
     */
    public function test_module_belongs_to_course(): void
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create(['course_id' => $course->id]);

        $this->assertEquals($course->id, $module->course->id);
    }

    /**
     * Test that active scope works correctly.
     */
    public function test_active_scope_filters_active_modules(): void
    {
        Module::factory()->create(['is_active' => true]);
        Module::factory()->create(['is_active' => false]);

        $activeModules = Module::active()->get();

        $this->assertCount(1, $activeModules);
    }

    /**
     * Test that category scope works correctly.
     */
    public function test_category_scope_filters_by_category(): void
    {
        Module::factory()->create(['category' => 'security']);
        Module::factory()->create(['category' => 'networking']);

        $securityModules = Module::byCategory('security')->get();

        $this->assertCount(1, $securityModules);
    }

    /**
     * Test that modules can be soft deleted.
     */
    public function test_module_can_be_soft_deleted(): void
    {
        $module = Module::factory()->create();

        $module->delete();

        $this->assertSoftDeleted('modules', ['id' => $module->id]);
    }
}
