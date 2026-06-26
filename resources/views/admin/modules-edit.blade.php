@extends('layouts.app')

@section('title', 'Edit Module')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Module</h2>
            <p class="text-slate-500 text-sm">Update module information for {{ $module->title }}.</p>
        </div>
        <a href="{{ route('admin.modules') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Modules
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Module Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $module->title) }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none">{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-700 mb-1">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $module->category) }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-sm font-semibold text-slate-700 mb-1">Difficulty</label>
                    <select name="difficulty" id="difficulty" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">Select difficulty</option>
                        <option value="beginner" {{ old('difficulty', $module->difficulty) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('difficulty', $module->difficulty) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('difficulty', $module->difficulty) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('difficulty')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-slate-700 mb-1">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $module->duration_minutes) }}" required min="1" max="10080"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lesson Count -->
                <div>
                    <label for="lesson_count" class="block text-sm font-semibold text-slate-700 mb-1">Number of Lessons</label>
                    <input type="number" name="lesson_count" id="lesson_count" value="{{ old('lesson_count', $module->lesson_count) }}" required min="0"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('lesson_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course -->
                <div>
                    <label for="course_id" class="block text-sm font-semibold text-slate-700 mb-1">Course (Optional)</label>
                    <select name="course_id" id="course_id"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">No course (standalone module)</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $module->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $module->order) }}" min="0"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Required Roles -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Required Roles (Optional)</label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="required_roles[]" value="student"
                            {{ in_array('student', old('required_roles', $module->required_roles ?? [])) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-slate-700">Student</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="required_roles[]" value="instructor"
                            {{ in_array('instructor', old('required_roles', $module->required_roles ?? [])) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-slate-700">Instructor</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="required_roles[]" value="admin"
                            {{ in_array('admin', old('required_roles', $module->required_roles ?? [])) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-slate-700">Admin</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-slate-500">Leave empty to make module accessible to all users</p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $module->is_active ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Module is active</label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.modules') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Update Module
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
