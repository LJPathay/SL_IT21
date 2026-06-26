<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index(Request $request)
    {
        $query = Module::with('course');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.modules', [
            'modules' => $modules,
        ]);
    }

    /**
     * Show the form for creating a new module.
     */
    public function create()
    {
        $courses = Course::where('is_active', true)->get();

        return view('admin.modules-create', [
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request)
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
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
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
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'module_created',
            'Module',
            $module->id,
            $validated,
            $request,
            'success'
        );

        return redirect()->route('admin.modules')->with('success', 'Module created successfully!');
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module)
    {
        $module->load('course', 'quizzes', 'enrollments.user');

        return view('admin.modules-show', [
            'module' => $module,
        ]);
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module)
    {
        $courses = Course::where('is_active', true)->get();

        return view('admin.modules-edit', [
            'module' => $module,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Module $module)
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
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Module title is required.',
            'description.required' => 'Module description is required.',
            'category.required' => 'Module category is required.',
            'difficulty.required' => 'Module difficulty is required.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.min' => 'Duration must be at least 1 minute.',
            'lesson_count.required' => 'Lesson count is required.',
        ]);

        $changes = array_diff_assoc($validated, $module->only(['title', 'description', 'category', 'difficulty', 'duration_minutes', 'course_id', 'lesson_count', 'required_roles', 'order']));
        $changes['is_active'] = $request->has('is_active') !== $module->is_active;

        $module->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'difficulty' => $validated['difficulty'],
            'duration_minutes' => $validated['duration_minutes'],
            'course_id' => $validated['course_id'] ?? null,
            'lesson_count' => $validated['lesson_count'],
            'required_roles' => $validated['required_roles'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'module_updated',
            'Module',
            $module->id,
            $changes,
            $request,
            'success'
        );

        return redirect()->route('admin.modules')->with('success', 'Module updated successfully!');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Request $request, Module $module)
    {
        $module->delete();

        LoggingService::logAudit(
            Auth::user(),
            'module_deleted',
            'Module',
            $module->id,
            ['title' => $module->title],
            $request,
            'success'
        );

        return redirect()->route('admin.modules')->with('success', 'Module deleted successfully!');
    }

    /**
     * Bulk delete modules.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:modules,id',
        ]);

        $modules = Module::whereIn('id', $validated['module_ids'])->get();
        $count = $modules->count();

        foreach ($modules as $module) {
            LoggingService::logAudit(
                Auth::user(),
                'module_deleted',
                'Module',
                $module->id,
                ['title' => $module->title],
                $request,
                'success'
            );
        }

        Module::whereIn('id', $validated['module_ids'])->delete();

        return redirect()->route('admin.modules')->with('success', "{$count} module(s) deleted successfully!");
    }

    /**
     * Toggle module active status.
     */
    public function toggleStatus(Request $request, Module $module)
    {
        $module->update(['is_active' => !$module->is_active]);

        LoggingService::logAudit(
            Auth::user(),
            'module_status_toggled',
            'Module',
            $module->id,
            ['is_active' => $module->is_active],
            $request,
            'success'
        );

        return redirect()->route('admin.modules')->with('success', 'Module status updated successfully!');
    }
}
