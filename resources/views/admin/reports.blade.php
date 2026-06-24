@extends('layouts.app')

@section('title', 'Platform Reports')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header layout -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Security Reports & Metrics</h2>
            <p class="text-slate-500 text-sm">Analyze overall organization progress, quiz completion graphs, and audit logs.</p>
        </div>
        <button onclick="alert('Exporting PDF...')" class="inline-flex items-center gap-2 bg-white border border-slate-350 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export PDF
        </button>
    </div>

    <!-- Reports Grid: Metrics and Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Completion Rate Stats & visual line chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-base">Weekly Completion Rate</h3>
                <span class="text-xs text-green-600 font-bold bg-green-50 px-2.5 py-1 rounded-lg border border-green-150">+12% vs last week</span>
            </div>

            <!-- SVG Mock Chart -->
            <div class="relative h-64 bg-slate-50 rounded-xl border border-slate-100 flex items-end p-4 overflow-hidden">
                <!-- Grid Lines -->
                <div class="absolute inset-0 flex flex-col justify-between p-4 pointer-events-none opacity-20">
                    <div class="border-b border-slate-500 w-full h-0"></div>
                    <div class="border-b border-slate-500 w-full h-0"></div>
                    <div class="border-b border-slate-500 w-full h-0"></div>
                    <div class="border-b border-slate-500 w-full h-0"></div>
                </div>
                
                <!-- Mock SVG Line -->
                <svg class="absolute inset-0 w-full h-full p-6" viewBox="0 0 100 50" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2563eb" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#2563eb" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <!-- Area under curve -->
                    <path d="M0 50 C 15 45, 30 20, 50 15 S 85 10, 100 5 L 100 50 L 0 50 Z" fill="url(#chartGradient)"></path>
                    <!-- Line -->
                    <path d="M0 50 C 15 45, 30 20, 50 15 S 85 10, 100 5" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"></path>
                </svg>

                <!-- Labels -->
                <div class="w-full flex justify-between text-[10px] text-slate-400 font-bold z-10">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Page Views</div>
                    <div class="text-lg font-bold text-slate-900 mt-0.5">8,450</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Total Certs</div>
                    <div class="text-lg font-bold text-slate-900 mt-0.5">142</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Active Users</div>
                    <div class="text-lg font-bold text-slate-900 mt-0.5">1,124</div>
                </div>
            </div>
        </div>

        <!-- Right: Course completion percentages -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="font-bold text-slate-800 text-base mb-6">Completion by Module</h3>
            
            <div class="space-y-5 flex-1">
                <!-- Module 1 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-700">Phishing Detection</span>
                        <span class="text-slate-905">91% Completed</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: 91%"></div>
                    </div>
                </div>

                <!-- Module 2 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-700">SQL Injection Prevention</span>
                        <span class="text-slate-905">64% Completed</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full" style="width: 64%"></div>
                    </div>
                </div>

                <!-- Module 3 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-700">Password Security</span>
                        <span class="text-slate-905">42% Completed</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: 42%"></div>
                    </div>
                </div>

                <!-- Module 4 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-700">Malware Threat Recognition</span>
                        <span class="text-slate-905">0% (Draft Stage)</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-slate-300 h-full rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mt-6">
                <div class="text-xs font-semibold text-slate-700">Highest Engagement:</div>
                <div class="text-sm font-black text-blue-600 mt-0.5">Phishing Detection (814 enrolled)</div>
            </div>
        </div>
        
    </div>

    <!-- Audit Logs Section -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Security Audit Logs</h3>
        </div>
        <div class="divide-y divide-slate-100 text-sm">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-slate-700">Administrator <span class="font-semibold">Admin User</span> added question to SQLi Quiz.</span>
                </div>
                <span class="text-xs text-slate-450">June 24, 2026 14:15</span>
            </div>
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span class="text-slate-700">User role upgraded for <span class="font-semibold">Michael Johnson</span>.</span>
                </div>
                <span class="text-xs text-slate-455">June 24, 2026 11:32</span>
            </div>
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span class="text-slate-700">Suspended account <span class="font-semibold">Michael Johnson</span> due to test misconduct.</span>
                </div>
                <span class="text-xs text-slate-455">June 23, 2026 16:50</span>
            </div>
        </div>
    </div>

</div>
@endsection
