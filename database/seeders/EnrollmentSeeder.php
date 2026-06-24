<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\UserEnrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::all();
        $modules = Module::all();

        if ($students->isEmpty() || $courses->isEmpty() || $modules->isEmpty()) {
            return;
        }

        // Enroll students in courses
        foreach ($students as $student) {
            // Enroll in 1-2 random courses
            $randomCourses = $courses->random(rand(1, min(2, $courses->count())));
            
            foreach ($randomCourses as $course) {
                UserEnrollment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'module_id' => null,
                    ],
                    [
                        'status' => 'active',
                        'progress_percentage' => rand(0, 100),
                        'enrolled_at' => now(),
                    ]
                );
            }
        }

        // Enroll students in modules
        foreach ($students as $student) {
            // Enroll in modules accessible to their role
            $accessibleModules = $modules->filter(function ($module) use ($student) {
                $requiredRoles = $module->required_roles;
                if (empty($requiredRoles)) {
                    return true;
                }
                return in_array($student->role, $requiredRoles);
            });

            // Enroll in 2-4 random modules
            $randomModules = $accessibleModules->random(
                min(rand(2, 4), $accessibleModules->count())
            );

            foreach ($randomModules as $module) {
                UserEnrollment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'module_id' => $module->id,
                        'course_id' => null,
                    ],
                    [
                        'status' => 'active',
                        'progress_percentage' => rand(0, 100),
                        'enrolled_at' => now(),
                    ]
                );
            }
        }

        // Enroll instructors in relevant modules
        $instructors = User::where('role', 'instructor')->get();
        foreach ($instructors as $instructor) {
            $accessibleModules = $modules->filter(function ($module) {
                $requiredRoles = $module->required_roles;
                if (empty($requiredRoles)) {
                    return true;
                }
                return in_array('instructor', $requiredRoles);
            });

            foreach ($accessibleModules->random(min(3, $accessibleModules->count())) as $module) {
                UserEnrollment::firstOrCreate(
                    [
                        'user_id' => $instructor->id,
                        'module_id' => $module->id,
                        'course_id' => null,
                    ],
                    [
                        'status' => 'completed',
                        'progress_percentage' => 100,
                        'enrolled_at' => now()->subMonths(2),
                        'completed_at' => now()->subMonths(1),
                    ]
                );
            }
        }
    }
}
