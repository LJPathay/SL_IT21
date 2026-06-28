<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Certificate;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules accessible to the user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get modules - filter by role only if user is authenticated
        $query = Module::query()->active();

        if ($user) {
            $query->byRole($user->role);
        }

        // Filter by category if provided
        if ($request->category) {
            $query->byCategory($request->category);
        }

        // Filter by difficulty if provided
        if ($request->difficulty) {
            $query->byDifficulty($request->difficulty);
        }

        // Search by title or description
        if ($request->search) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        $modules = $query->ordered()->paginate(12);

        // Get user's enrolled modules (only if authenticated)
        $enrolledModuleIds = $user ? $user->enrolledModules()->pluck('modules.id')->toArray() : [];

        // Get all categories for filter
        $categories = Module::distinct()->pluck('category')->sort();

        return view('modules.index', [
            'modules' => $modules,
            'enrolledModuleIds' => $enrolledModuleIds,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module)
    {
        $user = Auth::user();

        // Check if user has access to this module (only if authenticated)
        if ($user) {
            // Instructors can view modules they teach
            if ($user->isInstructor()) {
                $taughtCourseIds = $user->taughtCourses()->pluck('id')->toArray();
                if (!in_array($module->course_id, $taughtCourseIds)) {
                    abort(403, 'Unauthorized - You do not teach this module');
                }
            } else {
                // Students and admins check required roles
                $requiredRoles = $module->required_roles ?? [];
                if (!empty($requiredRoles) && !in_array($user->role, $requiredRoles)) {
                    abort(403, 'Unauthorized');
                }
            }
        }

        // Check if user is enrolled (only if authenticated)
        $enrollment = null;
        if ($user) {
            $enrollment = UserEnrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();
        }

        // Get related modules (filter by role only if authenticated)
        $relatedQuery = Module::active()
            ->where('id', '!=', $module->id)
            ->where('category', $module->category);

        if ($user) {
            $relatedQuery->byRole($user->role);
        }

        $relatedModules = $relatedQuery->limit(3)->get();

        // Load lessons and quizzes for the module
        $module->load(['lessons' => function($query) {
            $query->orderBy('order')->published();
        }, 'quizzes']);

        return view('modules.show', [
            'module' => $module,
            'enrollment' => $enrollment,
            'relatedModules' => $relatedModules,
        ]);
    }

    /**
     * Enroll user in a module.
     */
    public function enroll(Module $module)
    {
        $user = Auth::user();

        // Check if user has access to this module
        $requiredRoles = $module->required_roles ?? [];
        if (!empty($requiredRoles) && !in_array($user->role, $requiredRoles)) {
            return redirect()->back()->with('error', 'You do not have access to this module.');
        }

        // Check if already enrolled
        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if ($enrollment) {
            return redirect()->back()->with('info', 'You are already enrolled in this module.');
        }

        // Create enrollment
        UserEnrollment::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'course_id' => $module->course_id,
            'status' => 'active',
            'progress_percentage' => 0,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.courses')->with('success', 'Successfully enrolled in ' . $module->title);
    }

    /**
     * Unenroll user from a module.
     */
    public function unenroll(Module $module)
    {
        $user = Auth::user();

        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->firstOrFail();

        $enrollment->status = 'dropped';
        $enrollment->save();

        return redirect()->back()->with('success', 'Successfully unenrolled from ' . $module->title);
    }

    /**
     * Mark module as completed.
     */
    public function complete(Module $module)
    {
        $user = Auth::user();

        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->firstOrFail();

        $enrollment->status = 'completed';
        $enrollment->progress_percentage = 100;
        $enrollment->completed_at = now();
        $enrollment->save();

        return redirect()->back()->with('success', 'Module marked as completed!');
    }

    /**
     * Update progress for a module.
     */
    public function updateProgress(Request $request, Module $module)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->firstOrFail();

        $enrollment->progress_percentage = $validated['progress_percentage'];
        $enrollment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified quiz.
     */
    public function showQuiz(Quiz $quiz)
    {
        $user = Auth::user();

        // Enforce progression rules
        $module = $quiz->module;
        if ($module) {
            if ($module->difficulty === 'intermediate') {
                $hasCompletedBeginner = UserEnrollment::where('user_id', $user->id)
                    ->whereHas('module', function ($q) {
                        $q->where('difficulty', 'beginner');
                    })
                    ->where('status', 'completed')
                    ->exists();
                if (!$hasCompletedBeginner) {
                    return redirect()->route('student.quizzes')->with('error', 'You must complete a Beginner module first to unlock Intermediate quizzes.');
                }
            } elseif ($module->difficulty === 'advanced') {
                $hasCompletedIntermediate = UserEnrollment::where('user_id', $user->id)
                    ->whereHas('module', function ($q) {
                        $q->where('difficulty', 'intermediate');
                    })
                    ->where('status', 'completed')
                    ->exists();
                if (!$hasCompletedIntermediate) {
                    return redirect()->route('student.quizzes')->with('error', 'You must complete an Intermediate module first to unlock Advanced quizzes.');
                }
            }
        }

        // Check enrollment
        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $quiz->module_id)
            ->first();

        if (!$enrollment) {
            // Enroll user automatically or prompt
            UserEnrollment::create([
                'user_id' => $user->id,
                'module_id' => $quiz->module_id,
                'course_id' => $quiz->course_id,
                'status' => 'active',
                'progress_percentage' => 50,
                'enrolled_at' => now(),
            ]);
        }

        $quiz->load('questions');

        return view('student.take-quiz', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * Submit and auto-score the quiz.
     */
    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $questions = $quiz->questions;

        if ($questions->isEmpty()) {
            return redirect()->route('student.quizzes')->with('error', 'This quiz has no questions.');
        }

        $correctCount = 0;
        $answers = $request->input('answers', []);

        foreach ($questions as $question) {
            $userAns = $answers[$question->id] ?? null;
            if ($userAns !== null && trim($userAns) == trim($question->correct_answer)) {
                $correctCount++;
            }
        }

        $scorePercentage = round(($correctCount / $questions->count()) * 100);
        $passed = $scorePercentage >= $quiz->passing_score;

        // Save result
        QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $scorePercentage,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        if ($passed) {
            // Update enrollment status
            $enrollment = UserEnrollment::where('user_id', $user->id)
                ->where('module_id', $quiz->module_id)
                ->first();

            if ($enrollment) {
                $enrollment->status = 'completed';
                $enrollment->progress_percentage = 100;
                $enrollment->completed_at = now();
                $enrollment->save();
            }

            // Issue Certificate
            $certNumber = 'CERT-' . strtoupper(bin2hex(random_bytes(4)));
            $credentialId = 'CRED-' . strtoupper(bin2hex(random_bytes(6)));

            Certificate::firstOrCreate([
                'user_id' => $user->id,
                'module_id' => $quiz->module_id,
            ], [
                'course_id' => $quiz->course_id,
                'certificate_number' => $certNumber,
                'credential_id' => $credentialId,
                'title' => 'Certificate of Completion: ' . $quiz->title,
                'issued_at' => now(),
            ]);
        }

        return redirect()->route('student.quizzes')->with(
            $passed ? 'success' : 'error',
            "Quiz submitted! You scored {$scorePercentage}% (" . ($passed ? "Passed - Certificate Issued!" : "Failed - Try again") . ")"
        );
    }
}
