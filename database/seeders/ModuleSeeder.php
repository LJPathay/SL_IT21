<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // Detection Techniques - Available to all roles
            [
                'title' => 'Phishing Detection Awareness',
                'description' => 'Identify characteristics of phishing emails and fraudulent websites.',
                'category' => 'Detection Techniques',
                'difficulty' => 'beginner',
                'duration_minutes' => 45,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 1,
            ],
            [
                'title' => 'Social Engineering Detection',
                'description' => 'Recognize manipulation tactics used by attackers to obtain sensitive information.',
                'category' => 'Detection Techniques',
                'difficulty' => 'intermediate',
                'duration_minutes' => 60,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 2,
            ],
            [
                'title' => 'Password Security Assessment',
                'description' => 'Detect weak password practices and educate users on strong password creation.',
                'category' => 'Detection Techniques',
                'difficulty' => 'beginner',
                'duration_minutes' => 30,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 3,
            ],
            [
                'title' => 'Malware Threat Recognition',
                'description' => 'Identify signs of malware, ransomware, and suspicious software behavior.',
                'category' => 'Detection Techniques',
                'difficulty' => 'intermediate',
                'duration_minutes' => 90,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 4,
            ],
            [
                'title' => 'Unsafe Online Activity Detection',
                'description' => 'Recognize risky online behaviors and potential cybersecurity threats.',
                'category' => 'Detection Techniques',
                'difficulty' => 'beginner',
                'duration_minutes' => 45,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 5,
            ],

            // Security Awareness - Available to all roles
            [
                'title' => 'Phishing Email Recognition',
                'description' => 'Learn to identify phishing emails and suspicious links. Understand common tactics used by attackers.',
                'category' => 'Security Awareness',
                'difficulty' => 'beginner',
                'duration_minutes' => 45,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 6,
            ],
            [
                'title' => 'Password Security Best Practices',
                'description' => 'Master the art of creating strong passwords and managing them securely.',
                'category' => 'Security Awareness',
                'difficulty' => 'beginner',
                'duration_minutes' => 30,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 7,
            ],
            [
                'title' => 'Social Engineering Tactics',
                'description' => 'Understand how attackers manipulate humans and how to protect yourself.',
                'category' => 'Security Awareness',
                'difficulty' => 'intermediate',
                'duration_minutes' => 60,
                'required_roles' => ['student', 'instructor', 'admin'],
                'order' => 8,
            ],

            // Cybersecurity - Advanced content
            [
                'title' => 'Malware Analysis Fundamentals',
                'description' => 'Introduction to malware types, behavior, and analysis techniques.',
                'category' => 'Cybersecurity',
                'difficulty' => 'intermediate',
                'duration_minutes' => 90,
                'required_roles' => ['instructor', 'admin'],
                'order' => 4,
            ],
            [
                'title' => 'Network Security Essentials',
                'description' => 'Learn about firewalls, intrusion detection, and network hardening.',
                'category' => 'Cybersecurity',
                'difficulty' => 'intermediate',
                'duration_minutes' => 120,
                'required_roles' => ['instructor', 'admin'],
                'order' => 5,
            ],
            [
                'title' => 'Penetration Testing Basics',
                'description' => 'Introduction to ethical hacking and penetration testing methodologies.',
                'category' => 'Cybersecurity',
                'difficulty' => 'advanced',
                'duration_minutes' => 150,
                'required_roles' => ['admin'],
                'order' => 6,
            ],

            // Compliance & Policy - For staff
            [
                'title' => 'GDPR Compliance Overview',
                'description' => 'Understand GDPR requirements and data protection regulations.',
                'category' => 'Compliance',
                'difficulty' => 'intermediate',
                'duration_minutes' => 75,
                'required_roles' => ['instructor', 'admin'],
                'order' => 7,
            ],
            [
                'title' => 'Data Classification and Handling',
                'description' => 'Learn how to properly classify, handle, and protect sensitive data.',
                'category' => 'Compliance',
                'difficulty' => 'beginner',
                'duration_minutes' => 45,
                'required_roles' => ['instructor', 'admin'],
                'order' => 8,
            ],

            // IT Security - Admin only
            [
                'title' => 'System Hardening Guide',
                'description' => 'Advanced techniques for hardening operating systems and servers.',
                'category' => 'IT Security',
                'difficulty' => 'advanced',
                'duration_minutes' => 180,
                'required_roles' => ['admin'],
                'order' => 9,
            ],
            [
                'title' => 'Incident Response Planning',
                'description' => 'Develop and execute effective incident response procedures.',
                'category' => 'IT Security',
                'difficulty' => 'advanced',
                'duration_minutes' => 120,
                'required_roles' => ['admin'],
                'order' => 10,
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
