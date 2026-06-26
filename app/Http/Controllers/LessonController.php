<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Module $module, Lesson $lesson)
    {
        $user = Auth::user();

        // Check if user has access to this module
        if ($user) {
            $requiredRoles = $module->required_roles ?? [];
            if (!empty($requiredRoles) && !in_array($user->role, $requiredRoles)) {
                abort(403, 'Unauthorized');
            }
        }

        // Check if lesson belongs to the module
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        // Get all lessons for this module for navigation
        $lessons = $module->lessons()->ordered()->published()->get();
        $currentLessonIndex = $lessons->search(fn($l) => $l->id === $lesson->id);
        $previousLesson = $currentLessonIndex > 0 ? $lessons[$currentLessonIndex - 1] : null;
        $nextLesson = $currentLessonIndex < $lessons->count() - 1 ? $lessons[$currentLessonIndex + 1] : null;

        // Check enrollment and progress
        $enrollment = null;
        $completedLessons = [];
        if ($user) {
            $enrollment = UserEnrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();

            // Get completed lessons from enrollment (stored in JSON or separate table)
            // For now, we'll use a simple approach
            $completedLessons = $enrollment ? json_decode($enrollment->completed_lesson_ids ?? '[]', true) : [];
        }

        $isCompleted = in_array($lesson->id, $completedLessons);

        // Calculate progress
        $totalLessons = $lessons->count();
        $completedCount = count($completedLessons);
        $progressPercentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        return view('lessons.show', [
            'module' => $module,
            'lesson' => $lesson,
            'lessons' => $lessons,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'enrollment' => $enrollment,
            'isCompleted' => $isCompleted,
            'completedLessons' => $completedLessons,
            'progressPercentage' => $progressPercentage,
            'currentLessonIndex' => $currentLessonIndex + 1,
        ]);
    }

    /**
     * Mark lesson as completed.
     */
    public function markComplete(Request $request, Module $module, Lesson $lesson)
    {
        $user = Auth::user();

        // Check if lesson belongs to the module
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        // Get or create enrollment
        $enrollment = UserEnrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'active',
                'progress_percentage' => 0,
                'enrolled_at' => now(),
            ]
        );

        // Get current completed lessons
        $completedLessons = json_decode($enrollment->completed_lesson_ids ?? '[]', true);

        // Add lesson to completed if not already there
        if (!in_array($lesson->id, $completedLessons)) {
            $completedLessons[] = $lesson->id;
            $enrollment->completed_lesson_ids = json_encode($completedLessons);
        }

        // Calculate new progress
        $totalLessons = $module->lessons()->published()->count();
        $completedCount = count($completedLessons);
        $progressPercentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        $enrollment->progress_percentage = $progressPercentage;

        // Check if all lessons are completed
        if ($progressPercentage === 100) {
            $enrollment->status = 'completed';
            $enrollment->completed_at = now();
        }

        $enrollment->save();

        return response()->json([
            'success' => true,
            'progress' => $progressPercentage,
            'isCompleted' => true,
        ]);
    }

    /**
     * Mark lesson as incomplete.
     */
    public function markIncomplete(Request $request, Module $module, Lesson $lesson)
    {
        $user = Auth::user();

        // Check if lesson belongs to the module
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['success' => false, 'message' => 'Enrollment not found'], 404);
        }

        // Remove lesson from completed
        $completedLessons = json_decode($enrollment->completed_lesson_ids ?? '[]', true);
        $completedLessons = array_filter($completedLessons, fn($id) => $id !== $lesson->id);
        $completedLessons = array_values($completedLessons);

        $enrollment->completed_lesson_ids = json_encode($completedLessons);

        // Recalculate progress
        $totalLessons = $module->lessons()->published()->count();
        $completedCount = count($completedLessons);
        $progressPercentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        $enrollment->progress_percentage = $progressPercentage;

        // If not 100%, reset status to active
        if ($progressPercentage < 100) {
            $enrollment->status = 'active';
            $enrollment->completed_at = null;
        }

        $enrollment->save();

        return response()->json([
            'success' => true,
            'progress' => $progressPercentage,
            'isCompleted' => false,
        ]);
    }
}
