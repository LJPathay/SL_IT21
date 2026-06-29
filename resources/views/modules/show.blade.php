@extends('layouts.public')

@section('title', 'Module Details')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500 font-medium">
        <a href="{{ url('/modules') }}" class="hover:text-blue-600 transition-colors">Modules</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900">{{ $module->title }}</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="h-48 md:h-64 bg-slate-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-green-500/20 mix-blend-multiply"></div>
            <!-- W3Schools Mock Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
                <span class="text-9xl font-black font-serif text-white">W3S</span>
            </div>
            
            <div class="absolute bottom-0 left-0 p-8 w-full bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2.5 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg">{{ $module->category ?? 'General' }}</span>
                    <span class="px-2.5 py-1 bg-white/20 text-white backdrop-blur-md text-xs font-medium rounded-lg">{{ $module->difficulty ?? 'All Levels' }}</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $module->title }}</h2>
                <p class="text-slate-300 max-w-2xl text-sm md:text-base">{{ $module->description ?? 'Learn essential security concepts and best practices.' }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8 p-8">
            
            <!-- Left Content: Syllabus -->
            <div class="md:col-span-2 space-y-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">What you'll learn</h3>
                    <p class="text-slate-600 text-sm">{{ $module->description ?? 'This module covers essential security concepts and best practices.' }}</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Course Syllabus</h3>
                    <div class="border border-slate-200 rounded-xl divide-y divide-slate-100 bg-white">
                        @if($module->lessons && $module->lessons->count() > 0)
                            @foreach($module->lessons->sortBy('order') as $index => $lesson)
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">{{ $index + 1 }}</div>
                                    <span class="font-medium text-slate-800">{{ $lesson->title }}</span>
                                </div>
                                <span class="text-sm text-slate-500">{{ $lesson->duration_minutes ?? '15' }} min</span>
                            </div>
                            @endforeach
                        @else
                            <div class="p-4 text-center text-slate-500 text-sm">No lessons available yet</div>
                        @endif
                        @if($module->quizzes && $module->quizzes->count() > 0)
                            <div class="p-4 flex items-center justify-between bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <span class="font-medium text-slate-800">End of Module Assessment</span>
                                </div>
                                <span class="text-sm text-slate-500">{{ $module->quizzes->count() }} Question(s)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Content: Enrollment Card -->
            <div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 sticky top-24">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-black text-slate-900 mb-1">Free</div>
                        <p class="text-sm text-slate-500 font-medium">Included in your organization plan</p>
                    </div>

                    <button onclick="confirmEnrollment({{ $module->id }})" class="w-full py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200 mb-6">
                        Enroll Now
                    </button>
                    
                    <ul class="space-y-4 text-sm font-medium text-slate-600">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            Approx. 2 Hours
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            Certificate of Completion
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            </div>
                            Interactive Code Examples
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function confirmEnrollment(moduleId) {
    if (confirm('Are you sure you want to enroll in this module?')) {
        const form = document.getElementById('enroll-form-' + moduleId);
        if (form) form.submit();
    }
}
</script>
@auth
<form id="enroll-form-{{ $module->id }}" method="POST" action="{{ route('modules.enroll', $module) }}" class="hidden">
    @csrf
</form>
@endauth
@endsection
