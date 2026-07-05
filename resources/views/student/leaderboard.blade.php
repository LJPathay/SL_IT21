@extends('layouts.app')

@section('title', 'Leaderboard & Rankings')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Leaderboard</h2>
            <p class="text-slate-500 text-sm">See how your security training progress compares with the wider learner community.</p>
        </div>
        <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
            Your rank: {{ $currentRank ?? 'N/A' }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-sm font-medium text-slate-500">Top Score</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">{{ $rankedUsers->first()?->score ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-sm font-medium text-slate-500">Department Averages</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">{{ $departmentAverages->count() }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-sm font-medium text-slate-500">Your Score</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">{{ $currentUser->score ?? 0 }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Rank</th>
                        <th class="px-6 py-4">Learner</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Score</th>
                        <th class="px-6 py-4">Completed Modules</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rankedUsers as $entry)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $entry->id === $currentUser->id ? 'bg-blue-50/70' : '' }}">
                            <td class="px-6 py-4 font-semibold text-slate-700">#{{ $entry->rank }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $entry->name }}</div>
                                <div class="text-xs text-slate-500">{{ $entry->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry->department }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $entry->score }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry->completedModules }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">No leaderboard data is available yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
