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
            <!-- Phishing -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-lg border border-red-100">Social Engineering</span>
                        <span class="text-xs text-slate-500 font-medium">2 of 5 lessons completed</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">Phishing Detection Awareness</h4>
                        <p class="text-slate-500 text-sm mt-1">Identify deceptive URLs, malicious attachments, and social hacks.</p>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-600">
                            <span>Progress</span>
                            <span>40%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="{{ url('/learn/2') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow-sm">Resume Course</a>
                </div>
            </div>

            <!-- Password -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">Access Management</span>
                        <span class="text-xs text-slate-500 font-medium">0 of 4 lessons completed</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">Password Security Assessment</h4>
                        <p class="text-slate-500 text-sm mt-1">Learn to generate complex, robust passwords and understand 2FA configurations.</p>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-600">
                            <span>Progress</span>
                            <span>0%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="{{ url('/learn/3') }}" class="px-4 py-2 border border-slate-250 hover:bg-slate-50 text-slate-700 rounded-lg font-bold text-sm">Start Course</a>
                </div>
            </div>
        </div>

        <h3 class="font-bold text-slate-800 text-lg pt-4">Completed Courses</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- SQLi -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col opacity-90">
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-lg border border-green-100">Web Security</span>
                        <span class="text-xs text-green-700 font-bold bg-green-50 px-2 py-0.5 rounded border border-green-100 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Finished
                        </span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">SQL Injection Prevention</h4>
                        <p class="text-slate-500 text-sm mt-1">Defend applications against injection queries using prepared statement formats.</p>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-slate-650">
                            <span>Progress</span>
                            <span>100%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <a href="{{ url('/student/certificates') }}" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                        View Certificate
                    </a>
                    <a href="{{ url('/learn/1') }}" class="px-4 py-2 border border-slate-250 hover:bg-slate-50 text-slate-700 rounded-lg font-bold text-sm">Review Module</a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
