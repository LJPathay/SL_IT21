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
use App\Services\LoggingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

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
            ->with(['lessons' => function($query) {
                $query->orderBy('order')->published();
            }])
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

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(10);

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

        $securityLogs = $query->orderBy('occurred_at', 'desc')->paginate(10);

        return view('admin.security-logs', [
            'securityLogs' => $securityLogs,
        ]);
    }

    /**
     * Admin courses page with course list.
     */
    public function adminCourses(Request $request)
    {
        $courses = Course::with('instructor', 'modules')->orderBy('title')->paginate(10);
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.courses', [
            'courses' => $courses,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Show the form for creating a new course.
     */
    public function adminCoursesCreate()
    {
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.courses', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'capacity' => 'required|integer|min:1|max:1000',
        ], [
            'title.required' => 'Course title is required.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code already exists.',
            'description.required' => 'Course description is required.',
            'instructor_id.required' => 'An instructor must be assigned.',
            'level.required' => 'Course level is required.',
            'capacity.required' => 'Course capacity is required.',
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'instructor_id' => $validated['instructor_id'],
            'level' => $validated['level'],
            'capacity' => $validated['capacity'],
            'is_active' => true,
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'course_created',
            'Course',
            $course->id,
            ['title' => $course->title, 'code' => $course->code],
            $request,
            'success'
        );

        return redirect()->route('admin.courses')->with('success', 'Course created successfully!');
    }

    /**
     * Admin modules page with module list and stats.
     */
    public function adminModules(Request $request)
    {
        $query = Module::with('course');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_active', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_active', false);
            }
        }

        $modules = $query->orderBy('title')->paginate(10);
        $totalModules = Module::count();
        $activeModules = Module::where('is_active', true)->count();
        $inactiveModules = Module::where('is_active', false)->count();
        $totalEnrollments = UserEnrollment::count();
        $courses = Course::orderBy('title')->get();

        return view('admin.modules', [
            'modules' => $modules,
            'totalModules' => $totalModules,
            'activeModules' => $activeModules,
            'inactiveModules' => $inactiveModules,
            'totalEnrollments' => $totalEnrollments,
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new module.
     */
    public function adminModulesCreate()
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.modules', [
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created module in storage.
     */
    public function storeModule(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration_minutes' => 'required|integer|min:1|max:10080',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_count' => 'required|integer|min:0',
            'required_roles' => 'nullable|array',
            'required_roles.*' => 'in:student,instructor,admin',
        ], [
            'title.required' => 'Module title is required.',
            'description.required' => 'Module description is required.',
            'category.required' => 'Module category is required.',
            'difficulty.required' => 'Module difficulty is required.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.min' => 'Duration must be at least 1 minute.',
            'lesson_count.required' => 'Lesson count is required.',
        ]);

        $module = Module::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'difficulty' => $validated['difficulty'],
            'duration_minutes' => $validated['duration_minutes'],
            'course_id' => $validated['course_id'] ?? null,
            'lesson_count' => $validated['lesson_count'],
            'required_roles' => $validated['required_roles'] ?? null,
            'order' => 0,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'module_created',
            'Module',
            $module->id,
            ['title' => $module->title, 'category' => $module->category],
            $request,
            'success'
        );

        return redirect()->route('admin.modules')->with('success', 'Module created successfully!');
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

        if ($request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Store a new user (admin only).
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required|in:student,instructor,admin',
            'password' => ['required', 'string', PasswordRule::min(12)->mixedCase()->numbers()->symbols()],
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'Role is required.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
            'mfa_enabled' => false,
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
     * Admin quizzes page with quiz list and questions.
     */
    public function adminQuizzes(Request $request)
    {
        $quizzes = Quiz::with('module')->orderBy('title')->paginate(10);
        $totalQuizzes = Quiz::count();
        $totalQuestions = Quiz::withCount('questions')->get()->sum('questions_count');
        $modules = Module::orderBy('title')->get();

        return view('admin.quizzes', [
            'quizzes' => $quizzes,
            'totalQuizzes' => $totalQuizzes,
            'totalQuestions' => $totalQuestions,
            'modules' => $modules,
        ]);
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function adminQuizzesCreate()
    {
        $modules = Module::orderBy('title')->get();

        return view('admin.quizzes', [
            'modules' => $modules,
        ]);
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1|max:180',
        ], [
            'title.required' => 'Quiz title is required.',
            'module_id.required' => 'A module must be selected.',
            'passing_score.required' => 'Passing score is required.',
            'passing_score.min' => 'Passing score must be at least 0.',
            'passing_score.max' => 'Passing score cannot exceed 100.',
        ]);

        $module = Module::find($validated['module_id']);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'],
            'course_id' => $module->course_id ?? null,
            'passing_score' => $validated['passing_score'],
            'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'quiz_created',
            'Quiz',
            $quiz->id,
            ['title' => $quiz->title, 'module_id' => $quiz->module_id],
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes')->with('success', 'Quiz created successfully!');
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

        // Get module completion data
        $modules = Module::withCount('enrollments')->get();
        $moduleCompletions = $modules->map(function ($module) {
            $totalEnrolled = $module->enrollments_count;
            $completedCount = UserEnrollment::where('module_id', $module->id)
                ->where('status', 'completed')
                ->count();
            $completionPercentage = $totalEnrolled > 0 ? round(($completedCount / $totalEnrolled) * 100, 2) : 0;

            return [
                'id' => $module->id,
                'title' => $module->title,
                'category' => $module->category,
                'total_enrolled' => $totalEnrolled,
                'completed_count' => $completedCount,
                'completion_percentage' => $completionPercentage,
                'is_active' => $module->is_active,
            ];
        })->sortByDesc('total_enrolled')->values();

        return view('admin.reports', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalEnrollments' => $totalEnrollments,
            'completedEnrollments' => $completedEnrollments,
            'completionRate' => $completionRate,
            'recentAuditLogs' => $recentAuditLogs,
            'moduleCompletions' => $moduleCompletions,
        ]);
    }

    /**
     * Export admin reports as PDF.
     */
    public function exportReports(Request $request)
    {
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $totalEnrollments = UserEnrollment::count();
        $completedEnrollments = UserEnrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;

        $recentAuditLogs = AuditLog::orderBy('created_at', 'desc')->limit(10)->get();

        // Get module completion data
        $modules = Module::withCount('enrollments')->get();
        $moduleCompletions = $modules->map(function ($module) {
            $totalEnrolled = $module->enrollments_count;
            $completedCount = UserEnrollment::where('module_id', $module->id)
                ->where('status', 'completed')
                ->count();
            $completionPercentage = $totalEnrolled > 0 ? round(($completedCount / $totalEnrolled) * 100, 2) : 0;

            return [
                'title' => $module->title,
                'category' => $module->category,
                'total_enrolled' => $totalEnrolled,
                'completed_count' => $completedCount,
                'completion_percentage' => $completionPercentage,
            ];
        })->sortByDesc('total_enrolled')->values();

        $pdf = Pdf::loadView('admin.reports-pdf', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalEnrollments' => $totalEnrollments,
            'completedEnrollments' => $completedEnrollments,
            'completionRate' => $completionRate,
            'recentAuditLogs' => $recentAuditLogs,
            'moduleCompletions' => $moduleCompletions,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        return $pdf->download('security_reports_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Instructor courses page.
     */
    public function instructorCourses(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses()->with(['modules', 'enrollments'])->paginate(10);

        // Add counts to each course
        $courses->getCollection()->transform(function ($course) {
            $course->modules_count = $course->modules->count();
            $course->enrolled_count = $course->enrollments->count();
            return $course;
        });

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
        $query = UserEnrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->where('status', 'active');

        // Search filter
        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Progress filter
        if ($request->progress) {
            if ($request->progress === 'completed') {
                $query->where('progress_percentage', 100);
            } elseif ($request->progress === 'in-progress') {
                $query->where('progress_percentage', '>', 0)->where('progress_percentage', '<', 100);
            } elseif ($request->progress === 'struggling') {
                $query->where('progress_percentage', '<', 70);
            }
        }

        $students = $query->paginate(10);

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
            ->paginate(10);

        // Calculate statistics
        $totalResults = QuizResult::whereIn('quiz_id', $quizIds)->count();
        $passedResults = QuizResult::whereIn('quiz_id', $quizIds)
            ->where('score', '>=', 80)
            ->count();
        $passRate = $totalResults > 0 ? round(($passedResults / $totalResults) * 100, 1) : 0;

        // Calculate average attempts
        $studentAttempts = QuizResult::whereIn('quiz_id', $quizIds)
            ->selectRaw('user_id, COUNT(*) as attempts')
            ->groupBy('user_id')
            ->get();
        $avgAttempts = $studentAttempts->isNotEmpty() ? round($studentAttempts->avg('attempts'), 1) : 0;

        // Calculate students needing attention (failed 3+ times)
        $attentionRequired = QuizResult::whereIn('quiz_id', $quizIds)
            ->selectRaw('user_id, COUNT(*) as attempts, MAX(score) as max_score')
            ->groupBy('user_id')
            ->having('attempts', '>=', 3)
            ->having('max_score', '<', 80)
            ->count();

        return view('instructor.assessments', [
            'quizResults' => $quizResults,
            'passRate' => $passRate,
            'totalResults' => $totalResults,
            'passedResults' => $passedResults,
            'avgAttempts' => $avgAttempts,
            'attentionRequired' => $attentionRequired,
        ]);
    }

    /**
     * Export instructor assessments as CSV.
     */
    public function exportAssessments(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses();
        $moduleIds = Module::whereIn('course_id', $courses->pluck('id'))->pluck('id');
        $quizIds = Quiz::whereIn('module_id', $moduleIds)->pluck('id');
        $quizResults = QuizResult::whereIn('quiz_id', $quizIds)
            ->with(['user', 'quiz'])
            ->latest('completed_at')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="assessments_export_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($quizResults) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Student Email', 'Quiz', 'Score', 'Completed At']);

            foreach ($quizResults as $result) {
                fputcsv($file, [
                    $result->user->name ?? 'Unknown',
                    $result->user->email ?? 'N/A',
                    $result->quiz->title ?? 'Unknown',
                    $result->score ?? 0,
                    $result->completed_at ? $result->completed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export instructor students as CSV.
     */
    public function exportStudents(Request $request)
    {
        $user = Auth::user();
        $courses = $user->taughtCourses();
        $courseIds = $courses->pluck('id');
        $moduleIds = Module::whereIn('course_id', $courseIds)->pluck('id');

        $students = User::where('role', 'student')
            ->whereHas('enrollments', function ($query) use ($moduleIds) {
                $query->whereIn('module_id', $moduleIds);
            })
            ->with(['enrollments' => function ($query) use ($moduleIds) {
                $query->whereIn('module_id', $moduleIds);
            }])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Student Email', 'Enrolled Modules', 'Progress', 'Last Login']);

            foreach ($students as $student) {
                $moduleNames = $student->enrollments->pluck('module.title')->implode(', ');
                $avgProgress = $student->enrollments->avg('progress_percentage') ?? 0;

                fputcsv($file, [
                    $student->name,
                    $student->email,
                    $moduleNames,
                    round($avgProgress, 1) . '%',
                    $student->last_login_at ? $student->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Student courses page with enrolled courses.
     */
    public function studentCourses(Request $request)
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledModules()
            ->with(['course', 'lessons' => function($query) {
                $query->orderBy('order')->published();
            }])
            ->where('user_enrollments.status', 'active')
            ->paginate(10);

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
            ->paginate(10);

        $completedQuizIds = $completedQuizzes->pluck('quiz_id');
        $availableQuizzes = Quiz::whereHas('module', function ($query) use ($user) {
            $query->whereJsonContains('required_roles', $user->role)
                  ->orWhereNull('required_roles');
        })->whereNotIn('id', $completedQuizIds)
            ->get();

        // Compute average over ALL results (not just current page)
        $avgScore = round(
            $user->quizResults()->whereNotNull('completed_at')->avg('score') ?? 0,
            0
        );

        return view('student.quizzes', [
            'completedQuizzes' => $completedQuizzes,
            'availableQuizzes' => $availableQuizzes,
            'avgScore'         => $avgScore,
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
            ->paginate(10);

        return view('student.certificates', [
            'certificates' => $certificates,
        ]);
    }

    /**
     * Download certificate as PDF.
     */
    public function downloadCertificate(Certificate $certificate)
    {
        $user = Auth::user();
        if ($certificate->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $service = new \App\Services\CertificateService();
        return $service->generatePdf($certificate);
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
