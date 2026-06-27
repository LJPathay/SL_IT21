<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
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

        // Get all active modules (instructors should have access to all)
        $modules = Module::active()->get();

        // Get enrollments in their courses
        $enrolledStudents = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->get();

        // Get unique students count
        $uniqueStudents = $enrolledStudents->pluck('user_id')->unique()->count();

        // Get average completion across courses
        $avgCompletion = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->avg('progress_percentage') ?? 0;

        // Get students who need attention (failing or low progress)
        $needsAttention = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->where('progress_percentage', '<', 50)
            ->with('user')
            ->get();

        // Get average quiz score for students in instructor's courses
        $avgQuizScore = QuizResult::whereIn('quiz_id', function($query) use ($courses) {
            $query->select('id')->from('quizzes')->whereIn('module_id', function($q) use ($courses) {
                $q->select('id')->from('modules')->whereIn('course_id', $courses->pluck('id'));
            });
        })->avg('score_percentage') ?? 0;

        // Get recent student activity
        $recentActivity = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('instructor.dashboard', [
            'courses' => $courses,
            'modules' => $modules,
            'enrolledStudents' => $enrolledStudents,
            'uniqueStudents' => $uniqueStudents,
            'avgCompletion' => round($avgCompletion, 1),
            'needsAttention' => $needsAttention,
            'avgQuizScore' => round($avgQuizScore, 1),
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

        // Get user's enrolled modules (in progress)
        $inProgressModules = $user->enrolledModules()
            ->where('user_enrollments.status', 'active')
            ->get();

        // Get completed modules
        $completedModules = $user->enrolledModules()
            ->where('user_enrollments.status', 'completed')
            ->count();

        // Get recent quiz results
        $recentQuizResults = $user->quizResults()
            ->with('quiz')
            ->latest('completed_at')
            ->limit(5)
            ->get();

        // Calculate quiz average
        $quizAverage = $user->quizResults()->avg('score_percentage') ?? 0;

        // Get earned certificates
        $certificates = $user->certificates()
            ->where('expires_at', '>', now())
            ->orWhereNull('expires_at')
            ->get();

        // Calculate overall progress
        $overallProgress = $user->enrollments()
            ->where('status', 'active')
            ->avg('progress_percentage') ?? 0;

        // Calculate security score (based on quiz performance and module completion)
        $securityScore = min(1000, round(($quizAverage * 5) + ($completedModules * 50)));

        // Calculate percentile (simplified)
        $percentile = min(99, round($securityScore / 10));

        // Determine next milestone
        $nextMilestone = match(true) {
            $completedModules < 1 => 'Complete your first module',
            $completedModules < 5 => 'Complete 5 modules',
            $completedModules < 10 => 'Complete 10 modules',
            $completedModules < 20 => 'Complete 20 modules',
            default => 'Become a security expert'
        };

        return view('student.dashboard', [
            'user' => $user,
            'enrolledCourses' => $enrolledCourses,
            'accessibleModules' => $accessibleModules,
            'inProgressModules' => $inProgressModules,
            'completedModules' => $completedModules,
            'recentQuizResults' => $recentQuizResults,
            'quizAverage' => round($quizAverage, 1),
            'certificates' => $certificates,
            'overallProgress' => round($overallProgress, 2),
            'securityScore' => $securityScore,
            'percentile' => $percentile,
            'nextMilestone' => $nextMilestone,
        ]);
    }

    /**
     * View audit logs (admin only).
     */
    public function auditLogs(Request $request)
    {
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

    /**
     * Admin courses page with course list.
     */
    public function adminCourses(Request $request)
    {
        $courses = Course::with('instructor', 'modules')->orderBy('title')->get();

        return view('admin.courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Admin modules page with module list and stats.
     */
    public function adminModules(Request $request)
    {
        $modules = Module::with('course')->orderBy('title')->get();
        $totalModules = $modules->count();
        $activeModules = $modules->where('is_active', true)->count();
        $inactiveModules = $modules->where('is_active', false)->count();
        $totalEnrollments = $modules->sum('enrollment_count');

        return view('admin.modules', [
            'modules' => $modules,
            'totalModules' => $totalModules,
            'activeModules' => $activeModules,
            'inactiveModules' => $inactiveModules,
            'totalEnrollments' => $totalEnrollments,
        ]);
    }

    /**
     * Admin users page with user list and filters.
     */
    public function adminUsers(Request $request)
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

        $users = $query->orderBy('name')->paginate(20);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Admin quizzes page with quiz list and questions.
     */
    public function adminQuizzes(Request $request)
    {
        $quizzes = Quiz::with('module')->orderBy('title')->get();
        $totalQuizzes = $quizzes->count();
        $totalQuestions = $quizzes->sum(function ($quiz) {
            return $quiz->questions ? $quiz->questions->count() : 0;
        });

        return view('admin.quizzes', [
            'quizzes' => $quizzes,
            'totalQuizzes' => $totalQuizzes,
            'totalQuestions' => $totalQuestions,
        ]);
    }

    /**
     * Admin reports page with analytics data.
     */
    public function adminReports(Request $request)
    {
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $totalEnrollments = UserEnrollment::count();
        $completedEnrollments = UserEnrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;

        $recentAuditLogs = AuditLog::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.reports', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalEnrollments' => $totalEnrollments,
            'completedEnrollments' => $completedEnrollments,
            'completionRate' => $completionRate,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }

    /**
     * Instructor courses page.
     */
    public function instructorCourses(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses()->with('modules')->get();

        return view('instructor.courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Instructor students page with enrolled students.
     */
    public function instructorStudents(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses();
        $students = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->where('status', 'active')
            ->get();

        return view('instructor.students', [
            'students' => $students,
        ]);
    }

    /**
     * Instructor assessments page with quiz results.
     */
    public function instructorAssessments(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses();
        $moduleIds = Module::whereIn('course_id', $courses->pluck('id'))->pluck('id');
        $quizIds = Quiz::whereIn('module_id', $moduleIds)->pluck('id');
        $quizResults = QuizResult::whereIn('quiz_id', $quizIds)
            ->with(['user', 'quiz'])
            ->latest('completed_at')
            ->get();

        return view('instructor.assessments', [
            'quizResults' => $quizResults,
        ]);
    }

    /**
     * Student courses page with enrolled courses.
     */
    public function studentCourses(Request $request)
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()
            ->with('instructor')
            ->where('user_enrollments.status', 'active')
            ->get();

        return view('student.courses', [
            'enrolledCourses' => $enrolledCourses,
        ]);
    }

    /**
     * Student quizzes page with available and completed quizzes.
     */
    public function studentQuizzes(Request $request)
    {
        $user = Auth::user();
        $completedQuizzes = $user->quizResults()
            ->with('quiz')
            ->latest('completed_at')
            ->get();

        $availableQuizzes = Quiz::whereHas('module', function ($query) use ($user) {
            $query->whereJsonContains('required_roles', $user->role)
                  ->orWhereNull('required_roles');
        })->whereNotIn('id', $completedQuizzes->pluck('quiz_id'))
            ->get();

        return view('student.quizzes', [
            'completedQuizzes' => $completedQuizzes,
            'availableQuizzes' => $availableQuizzes,
        ]);
    }



    /**
     * Student certificates page with earned certificates.
     */
    public function studentCertificates(Request $request)
    {
        $user = Auth::user();
        $certificates = $user->certificates()
            ->with('module')
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                      ->orWhereNull('expires_at');
            })
            ->get();

        return view('student.certificates', [
            'certificates' => $certificates,
        ]);
    }

    public function studentInbox(Request $request)
    {
        $user = Auth::user();
        $simulations = \App\Models\PhishingResult::where('user_id', $user->id)
            ->with('campaign')
            ->whereHas('campaign', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        return view('student.inbox', [
            'simulations' => $simulations,
        ]);
    }
}
