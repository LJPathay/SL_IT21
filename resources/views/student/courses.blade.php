@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top layout -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Training Courses</h2>
            <p class="text-slate-500 text-sm">Resume your active training modules or start new ones to keep your security score high.</p>
        </div>
        <a href="{{ url('/modules') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Browse Catalog
        </a>
    </div>

    <!-- Sub headings for In Progress vs Completed -->
    <div class="space-y-6">
        <h3 class="font-bold text-slate-800 text-lg">In Progress</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($enrolledCourses as $course)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">{{ $course->category ?? 'General' }}</span>
                        <span class="text-xs text-slate-500 font-medium">Enrolled</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">{{ $course->title }}</h4>
                        <p class="text-slate-500 text-sm mt-1">{{ $course->description ?? 'No description available' }}</p>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-600">
                            <span>Progress</span>
                            <span>{{ $course->pivot->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $course->pivot->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="{{ url('/modules/' . $course->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow-sm">Continue Learning</a>
                </div>
            </div>
            @empty
            <div class="col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <p class="font-medium">No courses enrolled</p>
                    <p class="text-sm">Browse the catalog to start your learning journey.</p>
                </div>
            </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
