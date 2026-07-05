<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\UserEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_leaderboard_displays_ranked_users_from_database(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Alice Student',
            'email' => 'alice@example.com',
        ]);

        $topStudent = User::factory()->create([
            'role' => 'student',
            'name' => 'Bob Student',
            'email' => 'bob@example.com',
        ]);

        $module = Module::create([
            'title' => 'Password Security',
            'category' => 'Security Awareness',
            'difficulty' => 'beginner',
            'is_active' => true,
        ]);

        UserEnrollment::create([
            'user_id' => $topStudent->id,
            'module_id' => $module->id,
            'status' => 'completed',
            'progress_percentage' => 100,
            'enrolled_at' => now(),
            'completed_at' => now(),
        ]);

        $quiz = Quiz::create([
            'module_id' => $module->id,
            'passing_score' => 80,
            'is_active' => true,
            'title' => 'Password Basics Quiz',
            'description' => 'Password basics quiz',
        ]);

        QuizResult::create([
            'user_id' => $topStudent->id,
            'quiz_id' => $quiz->id,
            'score' => 95,
            'score_percentage' => 95,
            'correct_answers' => 19,
            'questions_answered' => 20,
            'status' => 'passed',
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->actingAs($student);

        $response = $this->get('/student/leaderboard');

        $response->assertOk();
        $response->assertSee('Leaderboard');
        $response->assertSee($topStudent->name);
    }

    public function test_learn_route_redirects_to_first_published_lesson_for_a_module(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student2@example.com',
        ]);

        $module = Module::create([
            'title' => 'Phishing Awareness',
            'category' => 'Security Awareness',
            'is_active' => true,
        ]);

        $lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => 'Intro to Phishing',
            'content' => 'Phishing basics.',
            'is_published' => true,
            'order' => 1,
        ]);

        $this->actingAs($student);

        $response = $this->get('/learn/' . $module->id);

        $response->assertRedirect(route('lessons.show', [$module, $lesson]));
    }
}
