<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Module;
use App\Models\QuizResult;
use App\Models\SecurityLog;
use App\Models\User;
use App\Models\UserEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        } elseif ($user->isInstructor()) {
            return $this->instructorDashboard($request);
        } else {
            return $this->studentDashboard($request);
        }
    }

    /**
     * Admin dashboard with security and audit logs.
     */
    private function adminDashboard(Request $request)
    {
        $user = Auth::user();

        // Get recent audit logs
        $recentAuditLogs = AuditLog::orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get recent security events
        $recentSecurityLogs = SecurityLog::orderBy('occurred_at', 'desc')
            ->limit(20)
            ->get();

        // Get critical security events
        $criticalEvents = SecurityLog::where('severity', 'critical')
            ->orderBy('occurred_at', 'desc')
            ->limit(10)
            ->get();

        // Get user statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $admins = User::where('role', 'admin')->count();
        $instructors = User::where('role', 'instructor')->count();
        $students = User::where('role', 'student')->count();

        // Get login statistics
        $todayLogins = SecurityLog::where('event_type', 'successful_login')
            ->where('occurred_at', '>=', now()->startOfDay())
            ->count();

        // Get modules and courses statistics
        $totalModules = Module::where('is_active', true)->count();
        $totalCourses = Course::where('is_active', true)->count();
        $totalEnrollments = UserEnrollment::count();

        return view('admin.dashboard', [
            'recentAuditLogs' => $recentAuditLogs,
            'recentSecurityLogs' => $recentSecurityLogs,
            'criticalEvents' => $criticalEvents,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'admins' => $admins,
            'instructors' => $instructors,
            'students' => $students,
            'todayLogins' => $todayLogins,
            'totalModules' => $totalModules,
            'totalCourses' => $totalCourses,
            'totalEnrollments' => $totalEnrollments,
        ]);
    }

    /**
     * Instructor dashboard with taught courses and student progress.
     */
    private function instructorDashboard(Request $request)
    {
        $user = Auth::user();

        // Get courses taught by this instructor
        $courses = $user->taughtCourses()->with('enrollments')->get();

        // Get modules they can teach
        $modules = Module::byRole('instructor')->active()->get();

        // Get enrollments in their courses
        $enrolledStudents = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->get();

        // Get average completion across courses
        $avgCompletion = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->avg('progress_percentage') ?? 0;

        // Get instructor's recent activity
        $recentActivity = AuditLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('instructor.dashboard', [
            'courses' => $courses,
            'modules' => $modules,
            'enrolledStudents' => $enrolledStudents,
            'avgCompletion' => round($avgCompletion, 2),
            'recentActivity' => $recentActivity,
        ]);
    }

    /**
     * Student dashboard with enrolled courses, modules, and progress.
     */
    private function studentDashboard(Request $request)
    {
        $user = Auth::user();

        // Get enrolled courses
        $enrolledCourses = $user->enrolledCourses()
            ->with('instructor')
            ->where('user_enrollments.status', 'active')
            ->get();

        // Get accessible modules based on role
        $accessibleModules = Module::byRole($user->role)->active()->get();

        // Get user's enrolled modules
        $enrolledModules = $user->enrolledModules()
            ->where('user_enrollments.status', 'active')
            ->get();

        // Get completed modules
        $completedModules = $user->enrolledModules()
            ->where('user_enrollments.status', 'completed')
            ->get();

        // Get recent quiz results
        $recentQuizzes = $user->quizResults()
            ->with('quiz')
            ->latest('completed_at')
            ->limit(5)
            ->get();

        // Get earned certificates
        $certificates = $user->certificates()
            ->where('expires_at', '>', now())
            ->orWhereNull('expires_at')
            ->get();

        // Calculate overall progress
        $overallProgress = $user->enrollments()
            ->where('status', 'active')
            ->avg('progress_percentage') ?? 0;

        return view('student.dashboard', [
            'user' => $user,
            'enrolledCourses' => $enrolledCourses,
            'accessibleModules' => $accessibleModules,
            'enrolledModules' => $enrolledModules,
            'completedModules' => $completedModules,
            'recentQuizzes' => $recentQuizzes,
            'certificates' => $certificates,
            'overallProgress' => round($overallProgress, 2),
        ]);
    }

    /**
     * View audit logs (admin only).
     */
    public function auditLogs(Request $request)
    {
        $this->authorize('viewAny', AuditLog::class);

        $query = AuditLog::query();

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by model
        if ($request->model) {
            $query->where('model', $request->model);
        }

        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.audit-logs', [
            'auditLogs' => $auditLogs,
        ]);
    }

    /**
     * View security logs (admin only).
     */
    public function securityLogs(Request $request)
    {
        $this->authorize('viewAny', SecurityLog::class);

        $query = SecurityLog::query();

        // Filter by event type
        if ($request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by severity
        if ($request->severity) {
            $query->where('severity', $request->severity);
        }

        // Filter by IP address
        if ($request->ip_address) {
            $query->where('ip_address', $request->ip_address);
        }

        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('occurred_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        // Get only critical events if filtered
        if ($request->critical_only) {
            $query->where('severity', 'critical');
        }

        $securityLogs = $query->orderBy('occurred_at', 'desc')->paginate(50);

        return view('admin.security-logs', [
            'securityLogs' => $securityLogs,
        ]);
    }
}
