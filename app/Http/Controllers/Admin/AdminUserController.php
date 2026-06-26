<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users-create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,instructor,admin',
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'mfa_enabled' => 'boolean',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required' => 'Role is required.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? null,
            'is_active' => $request->has('is_active'),
            'mfa_enabled' => $request->has('mfa_enabled'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'user_created',
            'User',
            $user->id,
            ['name' => $user->name, 'email' => $user->email, 'role' => $user->role],
            $request,
            'success'
        );

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('enrollments.course', 'enrollments.module', 'quizResults.quiz', 'certificates.module', 'auditLogs', 'securityLogs');

        return view('admin.users-show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users-edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,instructor,admin',
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'mfa_enabled' => 'boolean',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required' => 'Role is required.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => trim($validated['email']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? null,
            'is_active' => $request->has('is_active'),
            'mfa_enabled' => $request->has('mfa_enabled'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $changes = array_diff_assoc($validated, $user->only(['name', 'email', 'role', 'department', 'is_active', 'mfa_enabled']));
        if (!empty($validated['password'])) {
            $changes['password'] = 'updated';
        }

        $user->update($updateData);

        LoggingService::logAudit(
            Auth::user(),
            'user_updated',
            'User',
            $user->id,
            $changes,
            $request,
            'success'
        );

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deleting the currently authenticated user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        LoggingService::logAudit(
            Auth::user(),
            'user_deleted',
            'User',
            $user->id,
            ['name' => $user->name, 'email' => $user->email],
            $request,
            'success'
        );

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Prevent deleting the currently authenticated user
        if (in_array(Auth::id(), $validated['user_ids'])) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $users = User::whereIn('id', $validated['user_ids'])->get();
        $count = $users->count();

        foreach ($users as $user) {
            LoggingService::logAudit(
                Auth::user(),
                'user_deleted',
                'User',
                $user->id,
                ['name' => $user->name, 'email' => $user->email],
                $request,
                'success'
            );
        }

        User::whereIn('id', $validated['user_ids'])->delete();

        return redirect()->route('admin.users')->with('success', "{$count} user(s) deleted successfully!");
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Prevent deactivating the currently authenticated user
        if ($user->id === Auth::id) {
            return redirect()->route('admin.users')->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        LoggingService::logAudit(
            Auth::user(),
            'user_status_toggled',
            'User',
            $user->id,
            ['is_active' => $user->is_active],
            $request,
            'success'
        );

        return redirect()->route('admin.users')->with('success', 'User status updated successfully!');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'user_password_reset',
            'User',
            $user->id,
            ['email' => $user->email],
            $request,
            'success'
        );

        return redirect()->route('admin.users')->with('success', 'Password reset successfully!');
    }
}
