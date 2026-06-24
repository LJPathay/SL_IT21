<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules accessible to the user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get modules accessible to this user's role
        $query = Module::byRole($user->role)->active();

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

        // Get user's enrolled modules
        $enrolledModuleIds = $user->enrolledModules()->pluck('modules.id')->toArray();

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

        // Check if user has access to this module
        $requiredRoles = $module->required_roles ?? [];
        if (!empty($requiredRoles) && !in_array($user->role, $requiredRoles)) {
            abort(403, 'Unauthorized');
        }

        // Check if user is enrolled
        $enrollment = UserEnrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        // Get related modules
        $relatedModules = Module::byRole($user->role)
            ->active()
            ->where('id', '!=', $module->id)
            ->where('category', $module->category)
            ->limit(3)
            ->get();

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
            'status' => 'active',
            'progress_percentage' => 0,
            'enrolled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in ' . $module->title);
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
}
