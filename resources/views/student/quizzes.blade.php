@extends('layouts.app')

@section('title', 'Quizzes & Exams')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Assessments & Quizzes</h2>
        <p class="text-slate-500 text-sm">Pass end-of-module quizzes with at least 80% to earn certificates.</p>
    </div>

    <!-- Stats row -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Completed Quizzes</div>
            <div class="text-2xl font-bold text-slate-905 mt-1">{{ $completedQuizzes->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Average Score</div>
            <div class="text-2xl font-bold text-green-600 mt-1">{{ $completedQuizzes->count() > 0 ? round($completedQuizzes->avg('score'), 0) : 0 }}%</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Available Quizzes</div>
            <div class="text-2xl font-bold text-blue-650 mt-1">{{ $availableQuizzes->count() }}</div>
        </div>
    </div>

    <!-- Quizzes Table list -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Quiz List</h3>
        </div>
        <div class="divide-y divide-slate-100 text-sm">
            @forelse($completedQuizzes as $result)
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/20 transition-colors">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-slate-900 text-base">{{ $result->quiz->title ?? 'Quiz' }}</h4>
                        <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded-full">Passed</span>
                    </div>
                    <p class="text-slate-500 text-xs">Requirement: 80% passing grade.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right shrink-0">
                        <div class="font-black text-slate-900 text-base">{{ $result->score ?? 0 }}%</div>
                        <div class="text-[10px] text-slate-450 font-bold uppercase">Score</div>
                    </div>
                    <a href="{{ route('student.quizzes.show', $result->quiz->id) }}" class="inline-block bg-white border border-slate-250 hover:bg-slate-50 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-sm transition-colors">Retake Quiz</a>
                </div>
            </div>
            @empty
            @endforelse
 
            @forelse($availableQuizzes as $quiz)
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/20 transition-colors">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-slate-900 text-base">{{ $quiz->title }}</h4>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">Ready</span>
                    </div>
                    <p class="text-slate-500 text-xs">Requirement: 80% passing grade.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right shrink-0 text-slate-350 italic font-semibold text-xs pr-2">Not attempted</div>
                    <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-blue-200 transition-colors">Take Quiz</a>
                </div>
            </div>
            @empty
            @endforelse

            @if($completedQuizzes->count() === 0 && $availableQuizzes->count() === 0)
            <div class="p-12 text-center text-slate-500">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="font-medium">No quizzes available</p>
                    <p class="text-sm">Complete course modules to unlock quizzes.</p>
                </div>
            </div>
            @endif
        </div>
        
        {{ $completedQuizzes->links() }}
    </div>

</div>
@endsection
