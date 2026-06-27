@extends('layouts.app')

@section('title', 'Assessment Results')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header metrics -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Assessment Gradebook</h2>
            <p class="text-slate-500 text-sm">Review quiz grade results, passing statistics, and identify topic weaknesses.</p>
        </div>
        <button onclick="alert('Exporting grade sheets...')" class="bg-white border border-slate-350 hover:bg-slate-50 text-slate-700 text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors shrink-0 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Grades
        </button>
    </div>

    <!-- Overview stats grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Pass fail rates -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Overall Pass Rate</h4>
            <div class="flex items-center gap-4">
                <div class="text-4xl font-black text-green-600">89.4%</div>
                <div class="text-xs font-semibold text-slate-450 leading-relaxed">127 out of 142 students have met the passing threshold.</div>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-green-500 h-full rounded-full" style="width: 89.4%"></div>
            </div>
        </div>

        <!-- Average attempts count -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Avg Attempts to Pass</h4>
            <div class="flex items-center gap-4">
                <div class="text-4xl font-black text-blue-600">1.6</div>
                <div class="text-xs font-semibold text-slate-450 leading-relaxed">Students are mastering the concepts within two attempts.</div>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <!-- Needs review warning flag -->
        <div class="bg-red-50/20 p-5 rounded-2xl border border-red-100 shadow-sm space-y-4">
            <h4 class="font-bold text-red-800 text-sm uppercase tracking-wider">Attention Required</h4>
            <div class="flex items-center gap-4">
                <div class="text-4xl font-black text-red-600">8</div>
                <div class="text-xs font-semibold text-red-700 leading-relaxed">Students failed the quiz on their third attempt.</div>
            </div>
            <div class="w-full bg-red-100 h-2 rounded-full overflow-hidden">
                <div class="bg-red-500 h-full rounded-full" style="width: 30%"></div>
            </div>
        </div>

    </div>

    <!-- Detailed Assessment results table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Quiz Results Breakdown</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">SQL Injection Prevention</th>
                        <th class="px-6 py-4">Phishing Detection</th>
                        <th class="px-6 py-4">Password Security</th>
                        <th class="px-6 py-4 text-right">Performance Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($quizResults as $result)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $result->score < 70 ? 'bg-red-50/10' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $result->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-450 mt-0.5">{{ $result->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 {{ $result->score >= 80 ? 'text-green-600' : ($result->score >= 70 ? 'text-blue-600' : 'text-red-600') }}">
                            {{ $result->score ?? 0 }}% <span class="text-[10px] text-slate-400 font-semibold">(1 attempt)</span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-medium">—</td>
                        <td class="px-6 py-4 text-slate-400 font-medium">—</td>
                        <td class="px-6 py-4 text-right">
                            @if($result->score >= 80)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Excellent</span>
                            @elseif($result->score >= 70)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Good</span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Needs Attention</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-medium">No quiz results found</p>
                                <p class="text-sm">Quiz results will appear here as students complete assessments.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $quizResults->links() }}
    </div>

    <!-- Curriculum Weaknesses analysis -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-bold text-slate-800 text-base">Curriculum Insights</h3>
        <p class="text-sm text-slate-500">The platform aggregates quiz question failures to identify modules where students struggle with concepts. We recommend discussing these during class hours.</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div class="p-4 rounded-xl border border-red-100 bg-red-50/30 flex gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">!</div>
                <div>
                    <div class="text-sm font-bold text-slate-900">Vulnerable Parameterized Code</div>
                    <div class="text-xs text-slate-550 mt-1">42% of students failed this question on their first try in the SQL Injection Quiz.</div>
                </div>
            </div>
            
            <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/30 flex gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">!</div>
                <div>
                    <div class="text-sm font-bold text-slate-900">Subdomain Spoofing Patterns</div>
                    <div class="text-xs text-slate-550 mt-1">29% of students failed this URL recognition query in the Phishing Awareness Quiz.</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
