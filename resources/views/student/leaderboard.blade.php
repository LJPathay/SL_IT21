@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header layout -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Leaderboard & Rankings</h2>
            <p class="text-slate-500 text-sm">Review top performers in security awareness and help your department rise in rankings.</p>
        </div>
        <button onclick="alert('Shared to department channels!')" class="inline-flex items-center gap-2 bg-white border border-slate-350 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all shrink-0">
            Share Standings
        </button>
    </div>

    <!-- Podium Section (Top 3) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-4">
        
        <!-- Second Place (Silver) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center text-center shadow-sm order-2 md:order-1 h-72 justify-center relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-slate-400"></div>
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl border-2 border-slate-400 shadow-sm font-bold relative">
                JD
                <span class="absolute -top-2 -right-2 bg-slate-400 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center border-2 border-white shadow font-sans">2</span>
            </div>
            <h4 class="font-extrabold text-slate-800 text-base mt-4">Jane Doe</h4>
            <p class="text-xs text-slate-450 mt-1">CS Department</p>
            <div class="text-blue-600 font-black text-lg mt-3">940 pts</div>
        </div>

        <!-- First Place (Gold) -->
        <div class="bg-white rounded-2xl border border-blue-200 p-6 flex flex-col items-center text-center shadow-md order-1 md:order-2 h-80 justify-center relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-yellow-500"></div>
            <div class="w-20 h-20 rounded-full bg-yellow-50 flex items-center justify-center text-3xl border-2 border-yellow-400 shadow-sm font-bold relative">
                AS
                <span class="absolute -top-2 -right-2 bg-yellow-500 text-white rounded-full w-7 h-7 text-xs flex items-center justify-center border-2 border-white shadow font-sans font-bold">1</span>
            </div>
            <h4 class="font-black text-slate-900 text-lg mt-4">Alice Smith</h4>
            <p class="text-xs text-slate-450 mt-1">HR Department</p>
            <div class="text-blue-600 font-black text-xl mt-3">985 pts</div>
            <span class="mt-2 text-[10px] uppercase font-black tracking-widest text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded border border-yellow-100">Crown Champion</span>
        </div>

        <!-- Third Place (Bronze) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center text-center shadow-sm order-3 h-64 justify-center relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-600"></div>
            <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center text-xl border-2 border-amber-600 shadow-sm font-bold relative">
                BW
                <span class="absolute -top-2 -right-2 bg-amber-600 text-white rounded-full w-5 h-5 text-[10px] flex items-center justify-center border-2 border-white shadow font-sans">3</span>
            </div>
            <h4 class="font-extrabold text-slate-800 text-base mt-4">Bob Wilson</h4>
            <p class="text-xs text-slate-450 mt-1">Finance Team</p>
            <div class="text-blue-600 font-black text-lg mt-3">915 pts</div>
        </div>

    </div>

    <!-- Leaderboard Split Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Rankings directory table list -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-base">Top User Rankings</h3>
            </div>
            <div class="divide-y divide-slate-100">
                <!-- Rank 4 -->
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-6 text-slate-400 font-bold text-center">4</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold">CP</div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">Charlie Peterson</div>
                            <div class="text-[10px] text-slate-450">Marketing</div>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-blue-600">890 pts</span>
                </div>

                <!-- Rank 5 -->
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-6 text-slate-400 font-bold text-center">5</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold">DM</div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">David Miller</div>
                            <div class="text-[10px] text-slate-450">IT Security</div>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-blue-600">875 pts</span>
                </div>

                <!-- Spacer indicator -->
                <div class="p-2 bg-slate-50/50 text-slate-400 text-center text-xs font-bold tracking-widest select-none border-y border-slate-100">
                    •••
                </div>

                <!-- Student's own Rank (#14) -->
                <div class="p-4 flex items-center justify-between bg-blue-50/40 border-l-4 border-blue-500 hover:bg-blue-50/60 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-6 text-blue-600 font-black text-center">14</span>
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">LP</div>
                        <div>
                            <div class="text-sm font-extrabold text-blue-900">Lebron James Pathay (You)</div>
                            <div class="text-[10px] text-blue-700/80">CS Department</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-blue-700">850 pts</span>
                        <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100 flex items-center gap-0.5">
                            ▲ 2
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Department Competition Rankings -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="font-bold text-slate-800 text-base mb-6">Department Challenge</h3>
            <div class="space-y-4 flex-1">
                <!-- Dept 1 -->
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-5 text-slate-400 font-bold">1</span>
                        <span class="text-slate-800 font-semibold">Human Resources</span>
                    </div>
                    <span class="font-bold text-blue-600">Avg 865 pts</span>
                </div>
                <!-- Dept 2 -->
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-5 text-slate-400 font-bold">2</span>
                        <span class="text-slate-800 font-semibold">IT Security Team</span>
                    </div>
                    <span class="font-bold text-blue-600">Avg 842 pts</span>
                </div>
                <!-- Dept 3 (Student's dept) -->
                <div class="flex justify-between items-center text-sm p-2 bg-blue-50/30 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-5 text-blue-600 font-black">3</span>
                        <span class="text-blue-900 font-bold">Computer Science</span>
                    </div>
                    <span class="font-bold text-blue-700">Avg 814 pts</span>
                </div>
                <!-- Dept 4 -->
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-5 text-slate-400 font-bold">4</span>
                        <span class="text-slate-800 font-semibold">Finance & Admin</span>
                    </div>
                    <span class="font-bold text-blue-600">Avg 780 pts</span>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mt-6 text-xs text-slate-550 leading-relaxed">
                Take the **Phishing Simulator** in your inbox or finish course modules to score points and help your department rise!
            </div>
        </div>

    </div>

</div>
@endsection
