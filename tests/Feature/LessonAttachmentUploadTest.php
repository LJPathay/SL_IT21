<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\SecurityDetection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LessonAttachmentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_malware_demo_attachment_to_lesson(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $instructor = User::factory()->create([
            'role' => 'instructor',
            'email' => 'instructor@example.com',
        ]);

        $course = Course::create([
            'title' => 'Malware Demo Course',
            'description' => 'A course for malware detection demonstration',
            'code' => 'MDM101',
            'instructor_id' => $instructor->id,
            'level' => 'intermediate',
            'capacity' => 50,
            'is_active' => true,
        ]);

        $module = Module::create([
            'title' => 'Malware Detection Module',
            'description' => 'Module for testing malware file uploads',
            'category' => 'Security Awareness',
            'difficulty' => 'intermediate',
            'duration_minutes' => 30,
            'course_id' => $course->id,
            'lesson_count' => 1,
            'order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        $malwareFile = UploadedFile::fake()->create('sample_malware.exe', 10, 'application/x-msdownload');

        $response = $this->post(route('admin.lessons.store', $module), [
            'title' => 'Malware Demo Lesson',
            'content' => 'Contains a malicious attachment sample.',
            'attachment' => $malwareFile,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.modules.edit', $module));

        $this->assertDatabaseHas('lessons', [
            'module_id' => $module->id,
            'title' => 'Malware Demo Lesson',
            'attachment_name' => 'sample_malware.exe',
        ]);

        $this->assertDatabaseHas('security_detections', [
            'detection_type' => SecurityDetection::TYPE_MALWARE,
            'source_id' => 'sample_malware.exe',
        ]);
    }
}
