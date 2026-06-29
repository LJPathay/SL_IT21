<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserEnrollment;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Show the form for creating a new lesson.
     */
    public function create(Module $module)
    {
        return view('admin.lessons-create', [
            'module' => $module,
        ]);
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:1|max:300',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|mimes:pdf,ppt,pptx,doc,docx|max:10240',
        ], [
            'title.required' => 'Lesson title is required.',
            'content.required' => 'Lesson content is required.',
            'video_url.url' => 'Video URL must be a valid URL.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
            'attachment.mimes' => 'The attachment must be a file of type: pdf, ppt, pptx, doc, docx.',
            'attachment.max' => 'The attachment may not be greater than 10MB.',
        ]);

        $maxOrder = Lesson::where('module_id', $module->id)->max('order') ?? 0;
        $order = $validated['order'] ?? $maxOrder + 1;

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/lessons'), $imageName);
            $imageUrl = 'uploads/lessons/' . $imageName;
        }

        $attachmentUrl = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('uploads/lessons/attachments'), $attachmentName);
            $attachmentUrl = 'uploads/lessons/attachments/' . $attachmentName;
            $attachmentName = $attachment->getClientOriginalName();
        }

        $lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? '',
            'video_url' => $validated['video_url'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order' => $order,
            'is_published' => $request->has('is_published'),
            'image_url' => $imageUrl,
            'attachment_url' => $attachmentUrl,
            'attachment_name' => $attachmentName,
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'lesson_created',
            'Lesson',
            $lesson->id,
            ['title' => $lesson->title, 'module_id' => $module->id],
            $request,
            'success'
        );

        return redirect()->route('admin.modules.edit', $module)->with('success', 'Lesson created successfully!');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Module $module, Lesson $lesson)
    {
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        return view('admin.lessons-edit', [
            'module' => $module,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(Request $request, Module $module, Lesson $lesson)
    {
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:1|max:300',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|mimes:pdf,ppt,pptx,doc,docx|max:10240',
        ], [
            'title.required' => 'Lesson title is required.',
            'content.required' => 'Lesson content is required.',
            'video_url.url' => 'Video URL must be a valid URL.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
            'attachment.mimes' => 'The attachment must be a file of type: pdf, ppt, pptx, doc, docx.',
            'attachment.max' => 'The attachment may not be greater than 10MB.',
        ]);

        $imageUrl = $lesson->image_url;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($lesson->image_url && file_exists(public_path($lesson->image_url))) {
                unlink(public_path($lesson->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/lessons'), $imageName);
            $imageUrl = 'uploads/lessons/' . $imageName;
        }

        $attachmentUrl = $lesson->attachment_url;
        $attachmentName = $lesson->attachment_name;
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($lesson->attachment_url && file_exists(public_path($lesson->attachment_url))) {
                unlink(public_path($lesson->attachment_url));
            }

            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('uploads/lessons/attachments'), $attachmentName);
            $attachmentUrl = 'uploads/lessons/attachments/' . $attachmentName;
            $attachmentName = $attachment->getClientOriginalName();
        }

        $lesson->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? '',
            'video_url' => $validated['video_url'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order' => $validated['order'] ?? $lesson->order,
            'is_published' => $request->has('is_published'),
            'image_url' => $imageUrl,
            'attachment_url' => $attachmentUrl,
            'attachment_name' => $attachmentName,
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'lesson_updated',
            'Lesson',
            $lesson->id,
            ['title' => $lesson->title, 'module_id' => $module->id],
            $request,
            'success'
        );

        return redirect()->route('admin.modules.edit', $module)->with('success', 'Lesson updated successfully!');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Module $module, Lesson $lesson)
    {
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        $lesson->delete();

        LoggingService::logAudit(
            Auth::user(),
            'lesson_deleted',
            'Lesson',
            $lesson->id,
            ['title' => $lesson->title, 'module_id' => $module->id],
            request(),
            'success'
        );

        return redirect()->route('admin.modules.edit', $module)->with('success', 'Lesson deleted successfully!');
    }

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

            // completed_lesson_ids is cast as array in UserEnrollment model
            $completedLessons = $enrollment ? ($enrollment->completed_lesson_ids ?? []) : [];
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

        // Get current completed lessons (model casts this as array automatically)
        $completedLessons = $enrollment->completed_lesson_ids ?? [];

        // Add lesson to completed if not already there
        if (!in_array($lesson->id, $completedLessons)) {
            $completedLessons[] = $lesson->id;
            $enrollment->completed_lesson_ids = $completedLessons;
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
