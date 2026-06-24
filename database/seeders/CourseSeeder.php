<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = User::where('role', 'instructor')->get();

        if ($instructors->isEmpty()) {
            return;
        }

        $courses = [
            [
                'title' => 'Security Fundamentals 101',
                'description' => 'A comprehensive introduction to information security principles and best practices for all staff.',
                'code' => 'SEC101',
                'instructor_id' => $instructors->first()->id,
                'level' => 'beginner',
                'capacity' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
            ],
            [
                'title' => 'Advanced Cybersecurity for IT Teams',
                'description' => 'Deep dive into advanced security concepts, threat analysis, and defensive strategies.',
                'code' => 'SEC201',
                'instructor_id' => $instructors->first()->id,
                'level' => 'advanced',
                'capacity' => 30,
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addMonths(4),
            ],
            [
                'title' => 'Data Protection and Compliance',
                'description' => 'Learn about data protection regulations, compliance frameworks, and best practices.',
                'code' => 'DPC101',
                'instructor_id' => $instructors->last()->id ?? $instructors->first()->id,
                'level' => 'intermediate',
                'capacity' => 50,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
            ],
            [
                'title' => 'Phishing Simulation and Awareness',
                'description' => 'Interactive course on recognizing and responding to phishing attacks.',
                'code' => 'PHISH101',
                'instructor_id' => $instructors->first()->id,
                'level' => 'beginner',
                'capacity' => 200,
                'start_date' => now()->subWeeks(1),
                'end_date' => now()->addMonths(6),
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
