@extends('layouts.app')

@section('title', 'Instructor Dashboard')
@section('header_title', 'Instructor Dashboard')

@section('user_initial', 'I')
@section('user_name', 'Instructor User')
@section('user_role', 'Instructor')

@section('sidebar')
    <a href="{{ url('/instructor/dashboard') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-600 text-white rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Overview
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        My Students
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        Assessment Results
    </a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Assigned Students</div>
            <div class="text-3xl font-bold text-slate-900">142</div>
            <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                <span>12 new this week</span>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Avg. Class Score</div>
            <div class="text-3xl font-bold text-slate-900">82%</div>
            <div class="mt-2 text-sm text-slate-500 flex items-center gap-1">
                <span>Across all quizzes</span>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <div class="text-sm font-medium text-slate-500 mb-1">Needs Attention</div>
            <div class="text-3xl font-bold text-red-600">8</div>
            <div class="mt-2 text-sm text-slate-500 flex items-center gap-1">
                <span>Students failing assessments</span>
            </div>
        </div>
    </div>

    <!-- Student Tracking Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">Student Progress Tracking</h3>
            <button class="text-sm bg-white border border-slate-300 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                Export CSV
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-sm text-slate-500">
                        <th class="px-6 py-4 font-medium">Student Name</th>
                        <th class="px-6 py-4 font-medium">Current Module</th>
                        <th class="px-6 py-4 font-medium">Progress</th>
                        <th class="px-6 py-4 font-medium">Avg Score</th>
                        <th class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">Lebron James Pathay</div>
                            <div class="text-xs text-slate-500">lebron@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">Phishing Detection Awareness</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 40%"></div>
                                </div>
                                <span class="text-xs text-slate-600">40%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-green-600">92%</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">Jane Smith</div>
                            <div class="text-xs text-slate-500">jane@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">Password Security</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-xs text-slate-600">100%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-green-600">88%</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 bg-red-50/30">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">Michael Johnson</div>
                            <div class="text-xs text-slate-500">michael@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">Malware Threat Recognition</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-slate-600">15%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-red-600">54%</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Message Student</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
