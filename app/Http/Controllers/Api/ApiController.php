<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\UserEnrollment;
use App\Models\QuizResult;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Get authenticated user profile.
     */
    public function profile()
    {
        $user = Auth::user()->load(['enrollments.module', 'certificates', 'quizResults']);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Get all courses.
     */
    public function courses(Request $request)
    {
        $query = Course::with('instructor')->active();

        if ($request->level) {
            $query->byLevel($request->level);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    /**
     * Get a specific course.
     */
    public function course(Course $course)
    {
        $course->load(['instructor', 'modules', 'quizzes']);

        return response()->json([
            'success' => true,
            'data' => $course,
        ]);
    }

    /**
     * Get all modules.
     */
    public function modules(Request $request)
    {
        $query = Module::with('course')->active();

        if ($request->category) {
            $query->byCategory($request->category);
        }

        if ($request->difficulty) {
            $query->byDifficulty($request->difficulty);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $modules = $query->ordered()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $modules,
        ]);
    }

    /**
     * Get a specific module.
     */
    public function module(Module $module)
    {
        $module->load(['course', 'lessons', 'quizzes']);

        return response()->json([
            'success' => true,
            'data' => $module,
        ]);
    }

    /**
     * Get user's enrollments.
     */
    public function enrollments()
    {
        $user = Auth::user();
        $enrollments = UserEnrollment::where('user_id', $user->id)
            ->with(['module', 'course'])
            ->orderBy('enrolled_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $enrollments,
        ]);
    }

    /**
     * Enroll in a module.
     */
    public function enroll(Request $request, Module $module)
    {
        $user = Auth::user();

        // Check if already enrolled
        $existing = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already enrolled in this module.',
            ], 400);
        }

        // Check role restrictions
        if (!empty($module->required_roles) && !in_array($user->role, $module->required_roles)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to enroll in this module.',
            ], 403);
        }

        $enrollment = UserEnrollment::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'course_id' => $module->course_id,
            'status' => 'active',
            'progress_percentage' => 0,
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully enrolled in module.',
            'data' => $enrollment,
        ]);
    }

    /**
     * Get user's quiz results.
     */
    public function quizResults()
    {
        $user = Auth::user();
        $results = QuizResult::where('user_id', $user->id)
            ->with('quiz', 'quiz.module')
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Get user's certificates.
     */
    public function certificates()
    {
        $user = Auth::user();
        $certificates = Certificate::where('user_id', $user->id)
            ->with('module')
            ->orderBy('issued_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $certificates,
        ]);
    }

    /**
     * Update enrollment progress.
     */
    public function updateProgress(Request $request, UserEnrollment $enrollment)
    {
        $user = Auth::user();

        if ($enrollment->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $enrollment->update([
            'progress_percentage' => $validated['progress_percentage'],
        ]);

        if ($validated['progress_percentage'] === 100) {
            $enrollment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully.',
            'data' => $enrollment,
        ]);
    }

    /**
     * Get dashboard statistics for the authenticated user.
     */
    public function dashboardStats()
    {
        $user = Auth::user();

        $stats = [
            'enrolled_modules' => UserEnrollment::where('user_id', $user->id)->count(),
            'completed_modules' => UserEnrollment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'in_progress_modules' => UserEnrollment::where('user_id', $user->id)->where('status', 'active')->count(),
            'certificates_earned' => Certificate::where('user_id', $user->id)->count(),
            'quiz_attempts' => QuizResult::where('user_id', $user->id)->count(),
            'avg_quiz_score' => QuizResult::where('user_id', $user->id)->avg('score'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
