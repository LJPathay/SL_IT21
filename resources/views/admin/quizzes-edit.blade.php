@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Quiz</h2>
            <p class="text-slate-500 text-sm">Update quiz information for {{ $quiz->title }}.</p>
        </div>
        <a href="{{ route('admin.quizzes') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Quizzes
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Quiz Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description (Optional)</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none">{{ old('description', $quiz->description) }}</textarea>
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
                            <option value="{{ $module->id }}" {{ old('module_id', $quiz->module_id) == $module->id ? 'selected' : '' }}>
                                {{ $module->title }} ({{ $module->category }})
                            </option>
                        @endforeach
                    </select>
                    @error('module_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passing Score -->
                <div>
                    <label for="passing_score" class="block text-sm font-semibold text-slate-700 mb-1">Passing Score (%)</label>
                    <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" required min="0" max="100"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('passing_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Limit -->
                <div class="md:col-span-2">
                    <label for="time_limit_minutes" class="block text-sm font-semibold text-slate-700 mb-1">Time Limit (minutes, Optional)</label>
                    <input type="number" name="time_limit_minutes" id="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1" max="180"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="e.g., 30 (leave empty for no time limit)">
                    @error('time_limit_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $quiz->is_active ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Quiz is active</label>
            </div>

            <!-- Assessment Standards -->
            <div class="border-t border-slate-200 pt-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Assessment Standards</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Question Distribution -->
                    <div>
                        <label for="question_distribution" class="block text-sm font-semibold text-slate-700 mb-1">Question Distribution</label>
                        <select name="question_distribution" id="question_distribution"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                            <option value="sequential" {{ old('question_distribution', $quiz->question_distribution ?? 'sequential') == 'sequential' ? 'selected' : '' }}>Sequential</option>
                            <option value="randomized" {{ old('question_distribution', $quiz->question_distribution ?? 'sequential') == 'randomized' ? 'selected' : '' }}>Randomized</option>
                        </select>
                    </div>

                    <!-- Attempts Allowed -->
                    <div>
                        <label for="attempts_allowed" class="block text-sm font-semibold text-slate-700 mb-1">Attempts Allowed</label>
                        <input type="number" name="attempts_allowed" id="attempts_allowed" value="{{ old('attempts_allowed', $quiz->attempts_allowed ?? 0) }}" min="0"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="0 for unlimited">
                    </div>

                    <!-- Show Correct Answers -->
                    <div>
                        <label for="show_correct_answers" class="block text-sm font-semibold text-slate-700 mb-1">Show Correct Answers</label>
                        <select name="show_correct_answers" id="show_correct_answers"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                            <option value="never" {{ old('show_correct_answers', $quiz->show_correct_answers ?? 'after_submission') == 'never' ? 'selected' : '' }}>Never</option>
                            <option value="after_submission" {{ old('show_correct_answers', $quiz->show_correct_answers ?? 'after_submission') == 'after_submission' ? 'selected' : '' }}>After Submission</option>
                            <option value="immediately" {{ old('show_correct_answers', $quiz->show_correct_answers ?? 'after_submission') == 'immediately' ? 'selected' : '' }}>Immediately</option>
                        </select>
                    </div>

                    <!-- Shuffle Options -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="shuffle_options" id="shuffle_options" value="1" {{ $quiz->shuffle_options ?? false ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <label for="shuffle_options" class="text-sm font-medium text-slate-700">Shuffle Answer Options</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.quizzes') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Update Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
