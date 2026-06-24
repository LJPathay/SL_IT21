<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users first
        $this->call(UserSeeder::class);

        // Then seed modules, courses, and enrollments
        $this->call(ModuleSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(EnrollmentSeeder::class);
    }
}
