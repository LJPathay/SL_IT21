@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="flex flex-col h-full">
    <!-- Top Navbar -->
    <header class="h-14 bg-slate-900 text-white flex items-center justify-between px-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="font-semibold text-sm border-l border-slate-700 pl-4">{{ $module->title }}: {{ $lesson->title }}</div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-xs text-slate-400">Progress: {{ $currentLessonIndex }}/{{ $lessons->count() }}</div>
            <div class="w-24 bg-slate-700 rounded-full h-1.5">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Course Sidebar -->
        <aside class="w-64 bg-slate-50 border-r border-slate-200 flex flex-col hidden md:flex shrink-0">
            <div class="p-4 font-semibold text-slate-800 border-b border-slate-200">
                Course Contents
            </div>
            <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                @foreach($lessons as $index => $l)
                    <a href="{{ route('lessons.show', [$module, $l]) }}"
                        class="block px-3 py-2 rounded-lg text-sm {{ $l->id === $lesson->id ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }} {{ in_array($l->id, $completedLessons) ? 'flex items-center justify-between' : '' }}">
                        <span>{{ $index + 1 }}. {{ $l->title }}</span>
                        @if(in_array($l->id, $completedLessons))
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Learning Content -->
        <main class="flex-1 overflow-y-auto bg-white p-6 md:p-12 lg:px-24">
            <div class="max-w-3xl mx-auto">
                <div class="mb-2 text-sm font-semibold text-green-600 uppercase tracking-wider">Lesson {{ $currentLessonIndex }}</div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">{{ $lesson->title }}</h1>

                @if($lesson->video_url)
                <div class="mb-8 aspect-video bg-slate-900 rounded-xl overflow-hidden">
                    <iframe src="{{ $lesson->video_url }}" class="w-full h-full" frameborder="0" allowaccelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                @endif

                <div class="prose prose-slate max-w-none">
                    {!! $lesson->content !!}
                </div>

                <!-- Mark Complete Button -->
                <div class="mt-8 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <button onclick="toggleLessonComplete({{ $lesson->id }}, {{ $isCompleted ? 'false' : 'true' }})"
                        class="w-full py-3 rounded-xl font-medium transition-colors {{ $isCompleted ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-green-600 text-white hover:bg-green-700' }}">
                        @if($isCompleted)
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Lesson Completed
                            </span>
                        @else
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Mark as Complete
                            </span>
                        @endif
                    </button>
                </div>

                <!-- Footer Navigation -->
                <div class="mt-8 pt-8 border-t border-slate-200 flex items-center justify-between">
                    @if($previousLesson)
                        <a href="{{ route('lessons.show', [$module, $previousLesson]) }}" class="px-5 py-2.5 text-slate-700 font-medium hover:bg-slate-100 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Previous Lesson
                        </a>
                    @else
                        <button class="px-5 py-2.5 text-slate-400 font-medium rounded-lg cursor-not-allowed" disabled>
                            Previous Lesson
                        </button>
                    @endif

                    @if($nextLesson)
                        <a href="{{ route('lessons.show', [$module, $nextLesson]) }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                            Next Lesson: {{ $nextLesson->title }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('student.quizzes') }}" class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm flex items-center gap-2">
                            Take Quiz
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function toggleLessonComplete(lessonId, markComplete) {
    const url = markComplete
        ? '{{ route('lessons.complete', [$module, $lesson]) }}'
        : '{{ route('lessons.incomplete', [$module, $lesson]) }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
