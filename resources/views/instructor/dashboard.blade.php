@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Assigned Students</div>
            <div class="text-3xl font-bold text-slate-900">{{ $uniqueStudents ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-500">Across {{ $courses->count() ?? 0 }} courses</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Avg. Class Score</div>
            <div class="text-3xl font-bold text-slate-900">{{ $avgQuizScore ?? 0 }}%</div>
            <div class="mt-2 text-xs text-slate-500">Across all quizzes</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Needs Attention</div>
            <div class="text-3xl font-bold text-red-600">{{ $needsAttention->count() ?? 0 }}</div>
            <div class="mt-2 text-xs text-slate-500">Students below 50% progress</div>
        </div>
    </div>

    <!-- Student Progress Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Average Completion Rate</h3>
                <span class="text-2xl font-bold text-green-600">{{ $avgCompletion ?? 0 }}%</span>
            </div>
            <div class="p-6">
                <div class="w-full bg-slate-100 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full transition-all" style="width: {{ $avgCompletion ?? 0 }}%"></div>
                </div>
                <div class="mt-4 text-sm text-slate-600">
                    <span class="font-medium">Overall progress:</span> {{ $enrolledStudents->count() ?? 0 }} total enrollments
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Your Courses</h3>
            @if($courses && $courses->count() > 0)
                <div class="space-y-3">
                    @foreach($courses->take(3) as $course)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <div class="font-medium text-slate-900">{{ $course->title }}</div>
                            <div class="text-xs text-slate-500">{{ $course->code }}</div>
                        </div>
                        <div class="text-sm text-slate-600">{{ $course->enrollments->count() }} students</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-slate-500 text-sm">No courses assigned yet</div>
            @endif
        </div>
    </div>

    <!-- Student Tracking Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">Student Progress Tracking</h3>
            @if($needsAttention && $needsAttention->count() > 0)
            <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">{{ $needsAttention->count() }} need attention</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-sm text-slate-500">
                        <th class="px-6 py-4 font-medium">Student Name</th>
                        <th class="px-6 py-4 font-medium">Course</th>
                        <th class="px-6 py-4 font-medium">Progress</th>
                        <th class="px-6 py-4 font-medium">Last Activity</th>
                        <th class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($recentActivity && $recentActivity->count() > 0)
                        @foreach($recentActivity as $enrollment)
                        @php $student = $enrollment->user; $course = $enrollment->course; @endphp
                        <tr class="hover:bg-slate-50 {{ $enrollment->progress_percentage < 50 ? 'bg-red-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $student->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">{{ $student->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $course->title ?? 'Unknown Course' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                        <div class="bg-{{ $enrollment->progress_percentage >= 70 ? 'green' : ($enrollment->progress_percentage >= 50 ? 'blue' : 'red') }}-500 h-2 rounded-full" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-600">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $enrollment->updated_at?->diffForHumans() ?? 'Recently' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">No student activity yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
