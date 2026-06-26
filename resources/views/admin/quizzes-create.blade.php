@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create New Quiz</h2>
            <p class="text-slate-500 text-sm">Add a new quiz to assess student knowledge.</p>
        </div>
        <a href="{{ route('admin.quizzes') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Quizzes
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.quizzes.store') }}" class="p-6 md:p-8 space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Quiz Title</label>
                <input type="text" name="title" id="title" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    placeholder="e.g., SQL Injection Assessment">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description (Optional)</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"
                    placeholder="Provide a brief description of the quiz..."></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Module -->
                <div>
                    <label for="module_id" class="block text-sm font-semibold text-slate-700 mb-1">Module</label>
                    <select name="module_id" id="module_id" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">Select a module</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->title }} ({{ $module->category }})</option>
                        @endforeach
                    </select>
                    @error('module_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passing Score -->
                <div>
                    <label for="passing_score" class="block text-sm font-semibold text-slate-700 mb-1">Passing Score (%)</label>
                    <input type="number" name="passing_score" id="passing_score" required min="0" max="100"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="e.g., 70">
                    @error('passing_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Limit -->
                <div class="md:col-span-2">
                    <label for="time_limit_minutes" class="block text-sm font-semibold text-slate-700 mb-1">Time Limit (minutes, Optional)</label>
                    <input type="number" name="time_limit_minutes" id="time_limit_minutes" min="1" max="180"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="e.g., 30 (leave empty for no time limit)">
                    @error('time_limit_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Make quiz active immediately</label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.quizzes') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Create Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
