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
                'mfa_enabled' => false,
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
                'mfa_enabled' => false,
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
                'mfa_enabled' => false,
            ]
        );
    }
}
