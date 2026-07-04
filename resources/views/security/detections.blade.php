@extends('layouts.app')

@section('title', 'Security Detections')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Security Detections</h1>
            <p class="text-slate-500 text-sm mt-0.5">
                @if(isset($detections) && $detections->total() > 0)
                    {{ number_format($detections->total()) }} record{{ $detections->total() !== 1 ? 's' : '' }} found
                @else
                    View and manage all security threat detections
                @endif
            </p>
        </div>
        <a href="{{ route('security.dashboard') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-900 font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </div>

    {{-- ── Filter bar ── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-3">
        <form method="GET" action="{{ route('security.detections') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">All Types</option>
                    <option value="phishing"          {{ ($filters['type'] ?? '') === 'phishing'          ? 'selected' : '' }}>Phishing</option>
                    <option value="social_engineering" {{ ($filters['type'] ?? '') === 'social_engineering'? 'selected' : '' }}>Social Engineering</option>
                    <option value="password"           {{ ($filters['type'] ?? '') === 'password'          ? 'selected' : '' }}>Password</option>
                    <option value="malware"            {{ ($filters['type'] ?? '') === 'malware'           ? 'selected' : '' }}>Malware</option>
                    <option value="online_activity"    {{ ($filters['type'] ?? '') === 'online_activity'   ? 'selected' : '' }}>Online Activity</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Severity</label>
                <select name="severity" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">All Severities</option>
                    <option value="critical" {{ ($filters['severity'] ?? '') === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high"     {{ ($filters['severity'] ?? '') === 'high'     ? 'selected' : '' }}>High</option>
                    <option value="medium"   {{ ($filters['severity'] ?? '') === 'medium'   ? 'selected' : '' }}>Medium</option>
                    <option value="low"      {{ ($filters['severity'] ?? '') === 'low'      ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">All</option>
                    <option value="unresolved" {{ ($filters['status'] ?? '') === 'unresolved' ? 'selected' : '' }}>Unresolved Only</option>
                </select>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">Apply</button>
                <a href="{{ route('security.detections') }}" class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold rounded-lg transition-colors">Clear</a>
            </div>
        </form>
    </div>

    {{-- ── Detections Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-24">Severity</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-32">Type</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Title / Details</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-20">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-28">Date</th>
                        <th class="px-4 py-3 text-right text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($detections as $detection)
                    @php
                    $sev = $detection->severity;
                    $sevClass = match($sev) {
                        'critical' => 'bg-purple-100 text-purple-700',
                        'high'     => 'bg-orange-100 text-orange-700',
                        'medium'   => 'bg-yellow-100 text-yellow-700',
                        default    => 'bg-green-100 text-green-700',
                    };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $sevClass }} w-16">{{ $sev }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium text-slate-600">{{ ucwords(str_replace('_', ' ', $detection->detection_type)) }}</span>
                        </td>
                        <td class="px-4 py-3 max-w-sm">
                            <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $detection->title }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 truncate">{{ Str::limit($detection->details, 80) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($detection->is_resolved)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">Fixed</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600">Open</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $detection->created_at->format('M d, Y') }}<br><span class="text-[10px]">{{ $detection->created_at->format('H:i') }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('security.detections.show', $detection) }}" class="px-2.5 py-1 text-[11px] font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors">View</a>
                                @if(!$detection->is_resolved)
                                <a href="{{ route('security.detections.show', $detection) }}#resolve" class="px-2.5 py-1 text-[11px] font-semibold bg-green-50 text-green-700 hover:bg-green-100 rounded-lg transition-colors">Resolve</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <p class="font-semibold text-slate-500 text-sm">No detections found</p>
                            <p class="text-slate-400 text-xs mt-1">Try clearing your filters or use the test panel on the dashboard</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($detections->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-500">Showing <strong>{{ $detections->firstItem() }}</strong>–<strong>{{ $detections->lastItem() }}</strong> of <strong>{{ number_format($detections->total()) }}</strong></p>
            {{ $detections->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
