<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request)
    {
        $query = Course::with('instructor');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->level) {
            $query->where('level', $request->level);
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.courses-create', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'capacity' => 'required|integer|min:1|max:1000',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Course title is required.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code already exists.',
            'description.required' => 'Course description is required.',
            'instructor_id.required' => 'An instructor must be assigned.',
            'level.required' => 'Course level is required.',
            'capacity.required' => 'Course capacity is required.',
            'capacity.min' => 'Capacity must be at least 1.',
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'instructor_id' => $validated['instructor_id'],
            'level' => $validated['level'],
            'capacity' => $validated['capacity'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'course_created',
            'Course',
            $course->id,
            $validated,
            $request,
            'success'
        );

        return redirect()->route('admin.courses')->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load('instructor', 'modules', 'enrollments.user');

        return view('admin.courses-show', [
            'course' => $course,
        ]);
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.courses-edit', [
            'course' => $course,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'capacity' => 'required|integer|min:1|max:1000',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Course title is required.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code already exists.',
            'description.required' => 'Course description is required.',
            'instructor_id.required' => 'An instructor must be assigned.',
            'level.required' => 'Course level is required.',
            'capacity.required' => 'Course capacity is required.',
            'capacity.min' => 'Capacity must be at least 1.',
        ]);

        $changes = array_diff_assoc($validated, $course->only(['title', 'code', 'description', 'instructor_id', 'level', 'capacity', 'start_date', 'end_date']));
        $changes['is_active'] = $request->has('is_active') !== $course->is_active;

        $course->update([
            'title' => $validated['title'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'instructor_id' => $validated['instructor_id'],
            'level' => $validated['level'],
            'capacity' => $validated['capacity'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'course_updated',
            'Course',
            $course->id,
            $changes,
            $request,
            'success'
        );

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Request $request, Course $course)
    {
        $course->delete();

        LoggingService::logAudit(
            Auth::user(),
            'course_deleted',
            'Course',
            $course->id,
            ['title' => $course->title, 'code' => $course->code],
            $request,
            'success'
        );

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    }

    /**
     * Bulk delete courses.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $courses = Course::whereIn('id', $validated['course_ids'])->get();
        $count = $courses->count();

        foreach ($courses as $course) {
            LoggingService::logAudit(
                Auth::user(),
                'course_deleted',
                'Course',
                $course->id,
                ['title' => $course->title, 'code' => $course->code],
                $request,
                'success'
            );
        }

        Course::whereIn('id', $validated['course_ids'])->delete();

        return redirect()->route('admin.courses')->with('success', "{$count} course(s) deleted successfully!");
    }

    /**
     * Toggle course active status.
     */
    public function toggleStatus(Request $request, Course $course)
    {
        $course->update(['is_active' => !$course->is_active]);

        LoggingService::logAudit(
            Auth::user(),
            'course_status_toggled',
            'Course',
            $course->id,
            ['is_active' => $course->is_active],
            $request,
            'success'
        );

        return redirect()->route('admin.courses')->with('success', 'Course status updated successfully!');
    }
}
