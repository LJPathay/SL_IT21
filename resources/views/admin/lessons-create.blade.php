@extends('layouts.app')

@section('title', 'Create Lesson')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create New Lesson</h2>
            <p class="text-slate-500 text-sm">Add a lesson to module: {{ $module->title }}</p>
        </div>
        <a href="{{ route('admin.modules.edit', $module) }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Module
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.lessons.store', $module) }}" class="p-6 md:p-8 space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Lesson Title</label>
                <input type="text" name="title" id="title" placeholder="e.g. Introduction to SQL Injection" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-1">Lesson Content (Optional if attachment provided)</label>
                <textarea name="content" id="content" rows="8" placeholder="Enter the lesson content here..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                <p class="mt-1 text-xs text-slate-500">Leave empty if using an attachment file instead</p>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-slate-700 mb-1">Lesson Image (Optional)</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <p class="mt-1 text-xs text-slate-500">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachment Upload -->
            <div>
                <label for="attachment" class="block text-sm font-semibold text-slate-700 mb-1">Lesson Attachment (Optional)</label>
                <input type="file" name="attachment" id="attachment" accept=".pdf,.ppt,.pptx,.doc,.docx"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <p class="mt-1 text-xs text-slate-500">Accepted formats: PDF, PPT, PPTX, DOC, DOCX. Max size: 10MB</p>
                @error('attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Video URL -->
                <div>
                    <label for="video_url" class="block text-sm font-semibold text-slate-700 mb-1">Video URL (Optional)</label>
                    <input type="url" name="video_url" id="video_url" placeholder="https://example.com/video"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-slate-700 mb-1">Duration (minutes, Optional)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" min="1" max="300" placeholder="e.g. 30"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order" min="0" placeholder="Auto-assigned if empty"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published Status -->
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                        class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    <label for="is_published" class="text-sm font-medium text-slate-700">Publish immediately</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.modules.edit', $module) }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Create Lesson
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
