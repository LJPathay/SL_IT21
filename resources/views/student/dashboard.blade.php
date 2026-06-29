@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-1">Enrolled Courses</div>
            <div class="text-3xl font-bold text-slate-900">{{ $enrolledCourses->count() ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-500">Active learning</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-1">Modules Completed</div>
            <div class="text-3xl font-bold text-slate-900">{{ $completedModules ?? 0 }}</div>
            <div class="mt-2 text-xs text-green-600 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                On track
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-1">Quiz Average</div>
            <div class="text-3xl font-bold text-slate-900">{{ $quizAverage ?? 0 }}%</div>
            <div class="mt-2 text-xs text-slate-500">Overall performance</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-1">Certificates Earned</div>
            <div class="text-3xl font-bold text-slate-900">{{ $certificates->count() ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-500">Achievements unlocked</div>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl md:rounded-2xl shadow-md p-5 md:p-8 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name ?? 'Student' }}!</h2>
            <p class="text-blue-100 max-w-xl text-sm md:text-base">Continue your security training journey. You have {{ $inProgressModules->count() ?? 0 }} modules in progress.</p>
        </div>
        <div class="hidden sm:block shrink-0">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-blue-400 flex items-center justify-center relative">
                <span class="text-xl md:text-2xl font-bold">{{ $overallProgress ?? 0 }}%</span>
                <svg class="absolute inset-0 w-full h-full -rotate-90 text-white" viewBox="0 0 36 36">
                    <path class="text-blue-500/30 stroke-current" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="stroke-current" stroke-dasharray="{{ $overallProgress ?? 0 }}, 100" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
            </div>
        </div>
    </div>

    <!-- In Progress Modules -->
    <div>
        <h3 class="text-lg font-semibold text-slate-800 mb-4">In Progress Modules</h3>
        @if($inProgressModules && $inProgressModules->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                @foreach($inProgressModules as $module)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-{{ $module->difficulty === 'advanced' ? 'red' : ($module->difficulty === 'intermediate' ? 'yellow' : 'green') }}-100 text-{{ $module->difficulty === 'advanced' ? 'red' : ($module->difficulty === 'intermediate' ? 'yellow' : 'green') }}-700 text-xs font-semibold rounded-lg">{{ ucfirst($module->difficulty) }}</span>
                            <span class="text-sm font-medium text-slate-500">{{ $module->pivot->progress_percentage ?? 0 }}% complete</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">{{ $module->title }}</h4>
                        <p class="text-slate-600 text-sm mb-6">{{ Str::limit($module->description, 100) }}</p>

                        <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $module->pivot->progress_percentage ?? 0 }}%"></div>
                        </div>

                        <div class="mt-6">
                            @if($module->lessons && $module->lessons->count() > 0)
                                <a href="{{ route('lessons.show', [$module, $module->lessons->first()]) }}" class="block w-full py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium rounded-lg transition-colors text-center">
                                    Continue Learning
                                </a>
                            @else
                                <a href="{{ route('modules.show', $module) }}" class="block w-full py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium rounded-lg transition-colors text-center">
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                <div class="text-slate-500 mb-4">No modules in progress</div>
                <a href="{{ route('modules.index') }}" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Browse Modules
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Activity & Security Score -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- Recent Quiz Results -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Recent Quiz Results</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @if($recentQuizResults && $recentQuizResults->count() > 0)
                    @foreach($recentQuizResults->take(3) as $result)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-slate-900">{{ $result->quiz->title ?? 'Quiz' }}</div>
                            <div class="text-xs text-slate-500">{{ $result->completed_at?->diffForHumans() ?? 'Recently' }}</div>
                        </div>
                        <div class="text-sm font-semibold {{ ($result->score_percentage ?? $result->score) >= 70 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $result->score_percentage ?? $result->score ?? 0 }}%
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="p-4 text-center text-slate-500 text-sm">No quiz results yet</div>
                @endif
            </div>
            @if($recentQuizResults && $recentQuizResults->count() > 0)
            <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 text-center">
                <a href="{{ route('student.quizzes') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all results</a>
            </div>
            @endif
        </div>

        <!-- Security Score -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Your Security Awareness Score</h3>
            <p class="text-slate-500 text-sm mb-4">Based on your recent quiz performances and module completions</p>
            <div class="flex items-center justify-between">
                <div class="text-4xl font-bold text-green-500">{{ $securityScore ?? 0 }}</div>
                <div class="text-sm font-medium text-green-600 bg-green-50 px-3 py-2 rounded-md flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Top {{ $percentile ?? 50 }}%
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <div class="text-sm text-slate-600">
                    <span class="font-medium">Next milestone:</span> {{ $nextMilestone ?? 'Complete 5 modules' }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
