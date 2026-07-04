@extends('layouts.app')

@section('title', 'Detection Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Detection Details</h2>
            <p class="text-slate-500 text-sm">View detailed information about this security detection</p>
        </div>
        <a href="{{ route('security.detections') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Detections
        </a>
    </div>

    <!-- Detection Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium 
                            {{ $detection->severity === 'critical' ? 'bg-purple-100 text-purple-800' : 
                               ($detection->severity === 'high' ? 'bg-orange-100 text-orange-800' : 
                               ($detection->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                            {{ ucfirst($detection->severity) }} Severity
                        </span>
                        @if($detection->is_resolved)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Resolved
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Unresolved
                        </span>
                        @endif
                    </div>
                    <span class="text-sm text-slate-500">{{ $detection->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $detection->title }}</h3>
                <p class="text-slate-600 mb-4">{{ $detection->description }}</p>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm font-medium text-slate-700 mb-1">Detection Details:</p>
                    <p class="text-sm text-slate-600">{{ $detection->details }}</p>
                </div>
            </div>

            <!-- Source Information -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Source Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-1">Source Type</p>
                        <p class="text-sm text-slate-600">{{ ucfirst($detection->source ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-1">Source ID</p>
                        <p class="text-sm text-slate-600">{{ $detection->source_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-1">Detection Type</p>
                        <p class="text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $detection->detection_type)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-1">Created At</p>
                        <p class="text-sm text-slate-600">{{ $detection->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Mitigation Steps -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Recommended Mitigation Steps</h4>
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-sm text-blue-900 whitespace-pre-line">{{ $detection->mitigation_steps ?? 'No specific mitigation steps provided.' }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Resolution Status -->
            @if($detection->is_resolved)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Resolution Status</h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm text-slate-700">Resolved by: {{ $detection->resolver->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm text-slate-700">Resolved at: {{ $detection->resolved_at->format('M d, Y H:i:s') }}</span>
                    </div>
                    @if($detection->mitigation_steps)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-700 mb-2">Resolution Notes:</p>
                        <p class="text-sm text-slate-600">{{ $detection->mitigation_steps }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6" id="resolve">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Resolve Detection</h4>
                <form method="POST" action="{{ route('security.detections.resolve', $detection) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Resolution Notes</label>
                        <textarea name="resolution_notes" rows="4" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none" placeholder="Describe how this detection was resolved..."></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">Mark as Resolved</button>
                </form>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('security.detections') }}" class="block w-full px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors text-center">
                        View All Detections
                    </a>
                    <a href="{{ route('security.dashboard') }}" class="block w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-center">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
