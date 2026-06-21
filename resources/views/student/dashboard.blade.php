@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header_title', 'My Learning Dashboard')

@section('user_initial', 'S')
@section('user_name', 'Student User')
@section('user_role', 'Student')

@section('sidebar')
    <a href="{{ url('/student/dashboard') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-600 text-white rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Dashboard
    </a>
    <a href="{{ url('/modules') }}" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        My Courses
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        Quizzes & Exams
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
        My Certificates
    </a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl md:rounded-2xl shadow-md p-5 md:p-8 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold mb-2">Welcome back, Student!</h2>
            <p class="text-blue-100 max-w-xl text-sm md:text-base">You've completed 40% of your current module. Keep up the good work and stay secure!</p>
        </div>
        <div class="hidden sm:block shrink-0">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-blue-400 flex items-center justify-center relative">
                <span class="text-xl md:text-2xl font-bold">40%</span>
                <svg class="absolute inset-0 w-full h-full -rotate-90 text-white" viewBox="0 0 36 36">
                    <path class="text-blue-500/30 stroke-current" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="stroke-current" stroke-dasharray="40, 100" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Modules -->
    <h3 class="text-lg font-semibold text-slate-800 mt-8 mb-4">In Progress Modules</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
        
        <!-- Module Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">Critical</span>
                    <span class="text-sm font-medium text-slate-500">2 of 5 lessons</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Phishing Detection Awareness</h4>
                <p class="text-slate-600 text-sm mb-6">Learn to identify characteristics of phishing emails and fraudulent websites.</p>
                
                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 40%"></div>
                </div>
                
                <div class="mt-6">
                    <button class="w-full py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium rounded-lg transition-colors">
                        Continue Learning
                    </button>
                </div>
            </div>
        </div>

        <!-- Module Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">Beginner</span>
                    <span class="text-sm font-medium text-slate-500">0 of 4 lessons</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Password Security Assessment</h4>
                <p class="text-slate-600 text-sm mb-6">Detect weak password practices and educate yourself on strong password creation.</p>
                
                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                </div>
                
                <div class="mt-6">
                    <button class="w-full py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium rounded-lg transition-colors">
                        Start Module
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Security Score -->
    <div class="mt-8 bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-800 mb-1">Your Security Awareness Score</h3>
            <p class="text-slate-500 text-sm">Based on your recent quiz performances</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-3xl font-bold text-green-500">850</div>
            <div class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded-md flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Top 15%
            </div>
        </div>
    </div>

</div>
@endsection
