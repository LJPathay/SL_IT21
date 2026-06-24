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
            <div class="text-2xl font-bold text-slate-905 mt-1">1</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Average Score</div>
            <div class="text-2xl font-bold text-green-600 mt-1">92%</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Certificates Unlocked</div>
            <div class="text-2xl font-bold text-blue-650 mt-1">1</div>
        </div>
    </div>

    <!-- Quizzes Table list -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Quiz List</h3>
        </div>
        <div class="divide-y divide-slate-100 text-sm">
            
            <!-- SQLi Quiz (Passed) -->
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/20 transition-colors">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-slate-900 text-base">SQL Injection Prevention Quiz</h4>
                        <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded-full">Passed</span>
                    </div>
                    <p class="text-slate-500 text-xs">Requirement: 80% passing grade. Unlocked on completing SQL lessons.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right shrink-0">
                        <div class="font-black text-slate-900 text-base">92%</div>
                        <div class="text-[10px] text-slate-450 font-bold uppercase">Score</div>
                    </div>
                    <button onclick="alert('Starting retake exam...')" class="bg-white border border-slate-250 hover:bg-slate-50 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-sm transition-colors">Retake Quiz</button>
                </div>
            </div>

            <!-- Password Quiz (Unlocked) -->
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/20 transition-colors">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-slate-900 text-base">Password Security Quiz</h4>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">Ready</span>
                    </div>
                    <p class="text-slate-500 text-xs">Requirement: 80% passing grade. Unlocked on completing Password lessons.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right shrink-0 text-slate-350 italic font-semibold text-xs pr-2">Not attempted</div>
                    <button onclick="alert('Starting quiz assessment. Good luck!')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-blue-200 transition-colors">Take Quiz</button>
                </div>
            </div>

            <!-- Phishing Quiz (Locked) -->
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/20 transition-colors opacity-65 bg-slate-50/30">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-slate-900 text-base">Phishing Detection Quiz</h4>
                        <span class="px-2.5 py-0.5 bg-slate-200 text-slate-600 text-xs font-bold rounded-full">Locked</span>
                    </div>
                    <p class="text-slate-500 text-xs">Complete lessons 3, 4 and 5 of "Phishing Detection Awareness" to unlock this assessment.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-slate-400 text-xs font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Lessons Locked
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
