<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $phishingModule = Module::where('title', 'Phishing Email Recognition')->first();
        if ($phishingModule) {
            $quiz = Quiz::create([
                'title' => 'Phishing Email Recognition Assessment',
                'description' => 'Test your ability to identify suspicious phishing emails, links, and domains.',
                'course_id' => $phishingModule->course_id,
                'module_id' => $phishingModule->id,
                'total_questions' => 2,
                'passing_score' => 80,
                'is_active' => true,
            ]);

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'Which of the following sender addresses is most likely to be a phishing attempt?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A' => 'support@microsoft.com',
                    'B' => 'billing@netflix-accounts-portal.net',
                    'C' => 'updates@github.com',
                    'D' => 'security@google.com',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Phishing campaigns often use lookalike domains (e.g. netflix-accounts-portal.net) rather than official company domains.',
                'order' => 1,
            ]);

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'What is the primary indicator of a phishing email requesting immediate action?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A' => 'Artificial urgency (e.g., "Your account will be suspended in 24 hours")',
                    'B' => 'Professional greetings',
                    'C' => 'Standard product updates',
                    'D' => 'Links to HTTPS websites',
                ],
                'correct_answer' => 'A',
                'explanation' => 'Scammers use artificial urgency to induce panic and force users to act quickly without verifying the legitimacy of the request.',
                'order' => 2,
            ]);
        }

        $passwordModule = Module::where('title', 'Password Security Best Practices')->first();
        if ($passwordModule) {
            $quiz = Quiz::create([
                'title' => 'Password Security Quiz',
                'description' => 'Verify your understanding of secure credential creation and storage.',
                'course_id' => $passwordModule->course_id,
                'module_id' => $passwordModule->id,
                'total_questions' => 2,
                'passing_score' => 80,
                'is_active' => true,
            ]);

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'Which of the following forms the strongest password?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A' => 'P@ssword123!',
                    'B' => 'admin_secure_admin',
                    'C' => 'correct_horse_battery_staple_green_field',
                    'D' => 'ShortPassword!',
                ],
                'correct_answer' => 'C',
                'explanation' => 'Longer passphrases (multiple unrelated random words) have much higher entropy and are far harder to brute-force than short passwords, even those with symbols.',
                'order' => 1,
            ]);

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'Should you reuse the same secure password across multiple platforms?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A' => 'Yes, if it is highly complex',
                    'B' => 'No, because a breach on one service compromises all your other accounts',
                    'C' => 'Yes, if you change it every month',
                    'D' => 'Only for social media accounts',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Credential stuffing attacks target users who reuse credentials on multiple sites. Each site must have a unique password.',
                'order' => 2,
            ]);
        }
    }
}
