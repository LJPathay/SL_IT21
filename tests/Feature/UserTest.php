<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can be created.
     */
    public function test_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'student',
        ]);
    }

    /**
     * Test that user role methods work correctly.
     */
    public function test_user_role_methods(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isStudent());

        $this->assertTrue($instructor->isInstructor());
        $this->assertFalse($instructor->isAdmin());

        $this->assertTrue($student->isStudent());
        $this->assertFalse($student->isAdmin());
    }

    /**
     * Test that hasRole method works correctly.
     */
    public function test_has_role_method(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test that hasAnyRole method works correctly.
     */
    public function test_has_any_role_method(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->assertTrue($user->hasAnyRole(['student', 'instructor']));
        $this->assertFalse($user->hasAnyRole(['admin', 'instructor']));
    }

    /**
     * Test that isActive method works correctly.
     */
    public function test_is_active_method(): void
    {
        $activeUser = User::factory()->create(['is_active' => true]);
        $inactiveUser = User::factory()->create(['is_active' => false]);

        $this->assertTrue($activeUser->isActive());
        $this->assertFalse($inactiveUser->isActive());
    }

    /**
     * Test that users can be soft deleted.
     */
    public function test_user_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
