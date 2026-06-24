<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('SecurePassword123!'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create instructor user
        User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Instructor',
                'password' => Hash::make('SecurePassword123!'),
                'role' => 'instructor',
                'is_active' => true,
            ]
        );

        // Create student user
        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => Hash::make('SecurePassword123!'),
                'role' => 'student',
                'is_active' => true,
            ]
        );

        // Create additional instructors
        for ($i = 2; $i <= 3; $i++) {
            User::firstOrCreate(
                ['email' => "instructor$i@example.com"],
                [
                    'name' => "Instructor $i",
                    'password' => Hash::make('SecurePassword123!'),
                    'role' => 'instructor',
                    'is_active' => true,
                ]
            );
        }

        // Create additional students with realistic names
        $studentNames = [
            'John Smith' => 'john.smith@example.com',
            'Sarah Johnson' => 'sarah.johnson@example.com',
            'Michael Brown' => 'michael.brown@example.com',
            'Emily Davis' => 'emily.davis@example.com',
            'Robert Wilson' => 'robert.wilson@example.com',
            'Jessica Martinez' => 'jessica.martinez@example.com',
            'David Anderson' => 'david.anderson@example.com',
            'Lisa Taylor' => 'lisa.taylor@example.com',
        ];

        foreach ($studentNames as $name => $email) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('SecurePassword123!'),
                    'role' => 'student',
                    'is_active' => true,
                ]
            );
        }
    }
}
