@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Audit Logs</h2>
            <p class="text-slate-500 text-sm">Track all system changes and user actions.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">User ID</label>
                <input type="text" name="user_id" value="{{ request('user_id') }}" placeholder="Filter by user ID" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Model</label>
                <input type="text" name="model" value="{{ request('model') }}" placeholder="Filter by model" class="w-full px-4 py-2 border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm">Filter</button>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Model</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $log->created_at ? $log->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $log->action ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $log->model ?? 'N/A' }} ({{ $log->model_id ?? 'N/A' }})</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->ip_address ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $log->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($log->status ?? 'unknown') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-medium">No audit logs found</p>
                                <p class="text-sm">Audit logs will appear here as users interact with the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($auditLogs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
