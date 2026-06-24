@extends('layouts.app')

@section('title', 'Manage Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Training Courses</h2>
            <p class="text-slate-500 text-sm">Manage course offerings, instructors, and enrollments.</p>
        </div>
        <button onclick="alert('Create course modal would open here')" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Course
        </button>
    </div>

    <!-- Stats summary row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Courses</div>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $courses->count() ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Active Courses</div>
            <div class="text-2xl font-bold text-green-600 mt-1">{{ $courses->where('is_active', true)->count() ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Modules</div>
            <div class="text-2xl font-bold text-blue-600 mt-1">{{ $courses->sum(function($course) { return $course->modules->count(); }) ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Enrollments</div>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ $courses->sum(function($course) { return $course->enrolled_count ?? 0; }) ?? 0 }}</div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Course Name</th>
                        <th class="px-6 py-4">Instructor</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4">Modules</th>
                        <th class="px-6 py-4">Enrolled</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $course->title }}</div>
                            <div class="text-xs text-slate-450 mt-0.5">{{ $course->code ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $course->instructor->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200">{{ ucfirst($course->level ?? 'beginner') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $course->modules->count() ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $course->enrolled_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($course->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-450"></span>
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="Edit course">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <p class="font-medium">No courses found</p>
                                <p class="text-sm">Create your first course to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
