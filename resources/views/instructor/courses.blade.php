@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header info -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Courses</h2>
            <p class="text-slate-500 text-sm">Manage your assigned courses and track student progress.</p>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <div class="p-6 flex-1 space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">{{ ucfirst($course->level ?? 'beginner') }}</span>
                    @if($course->is_active)
                    <span class="text-xs text-green-700 font-bold bg-green-50 px-2 py-0.5 rounded border border-green-100 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Active
                    </span>
                    @else
                    <span class="text-xs text-slate-500 font-bold bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                        Inactive
                    </span>
                    @endif
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-lg">{{ $course->title }}</h4>
                    <p class="text-slate-500 text-sm mt-1">{{ $course->description ?? 'No description' }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-600">
                        <span>Modules</span>
                        <span>{{ $course->modules_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-slate-600">
                        <span>Enrolled Students</span>
                        <span>{{ $course->enrolled_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                <a href="{{ route('modules.index') }}" class="px-4 py-2 border border-slate-250 hover:bg-slate-50 text-slate-700 rounded-lg font-bold text-sm">View Modules</a>
                <a href="{{ route('instructor.students') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow-sm">View Students</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <p class="font-medium">No courses assigned</p>
                <p class="text-sm">Contact an administrator to get course assignments.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    {{ $courses->links() }}

</div>
@endsection
