@extends('layouts.app')

@section('title', 'Security Logs')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Security Logs</h2>
            <p class="text-slate-500 text-sm">Monitor security events and potential threats.</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.security-logs') }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Event Type</label>
                <input type="text" name="event_type" value="{{ request('event_type') }}" placeholder="Filter by event type" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Severity</label>
                <select name="severity" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All</option>
                    <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="Filter by IP" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm">Filter</button>
                <a href="{{ route('admin.security-logs') }}" class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm text-center">Clear</a>
            </div>
        </div>
    </form>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Event Type</th>
                        <th class="px-6 py-4">Severity</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Endpoint</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($securityLogs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $log->severity === 'critical' ? 'bg-red-50/20' : '' }}">
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $log->occurred_at ? $log->occurred_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $log->user->name ?? 'Anonymous' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $log->event_type ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                {{ $log->severity === 'critical' ? 'bg-red-100 text-red-800' : 
                                   ($log->severity === 'high' ? 'bg-orange-100 text-orange-800' : 
                                   ($log->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-slate-100 text-slate-800')) }}">
                                {{ ucfirst($log->severity ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->ip_address ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $log->endpoint ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <p class="font-medium">No security logs found</p>
                                <p class="text-sm">Security events will appear here as they occur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($securityLogs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $securityLogs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
