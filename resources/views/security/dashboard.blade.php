@extends('layouts.app')

@section('title', 'Security Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </span>
                Security Dashboard
            </h1>
            <p class="text-slate-500 text-sm mt-0.5">Real-time threat detection and monitoring system</p>
        </div>
        <a href="{{ route('security.detections') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition-colors">
            All Detections
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
        $statCards = [
            ['label' => 'Total Detections', 'value' => $stats['total_detections'],       'color' => 'blue',   'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['label' => 'Unresolved',       'value' => $stats['unresolved_detections'],  'color' => 'red',    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ['label' => 'Critical',         'value' => $stats['critical_count'],         'color' => 'purple', 'icon' => 'M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01'],
            ['label' => 'High Severity',    'value' => $stats['high_count'],             'color' => 'orange', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ];
        $colorMap = [
            'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-100 text-blue-600',   'val' => 'text-blue-700'],
            'red'    => ['bg' => 'bg-red-50',    'icon' => 'bg-red-100 text-red-600',     'val' => 'text-red-700'],
            'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-100 text-purple-600','val' => 'text-purple-700'],
            'orange' => ['bg' => 'bg-orange-50', 'icon' => 'bg-orange-100 text-orange-600','val' => 'text-orange-700'],
        ];
        @endphp
        @foreach($statCards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl {{ $c['icon'] }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold {{ $c['val'] }} leading-tight">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Detection Type mini-bar ── --}}
    <div class="grid grid-cols-5 gap-2">
        @php
        $types = [
            ['label' => 'Phishing',      'val' => $stats['phishing_count'],          'dot' => 'bg-red-400'],
            ['label' => 'Social Eng.',   'val' => $stats['social_engineering_count'], 'dot' => 'bg-orange-400'],
            ['label' => 'Password',      'val' => $stats['password_count'],           'dot' => 'bg-yellow-400'],
            ['label' => 'Malware',       'val' => $stats['malware_count'],            'dot' => 'bg-purple-400'],
            ['label' => 'Online',        'val' => $stats['online_activity_count'],    'dot' => 'bg-blue-400'],
        ];
        @endphp
        @foreach($types as $t)
        <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ $t['dot'] }} shrink-0"></span>
            <div class="min-w-0">
                <p class="text-[10px] font-medium text-slate-500 truncate">{{ $t['label'] }}</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $t['val'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Recent Detections ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-900 text-sm">Recent Security Detections</h3>
            <a href="{{ route('security.detections') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">View all →</a>
        </div>

        @forelse($recentDetections as $detection)
        @php
        $sev = $detection->severity;
        $sevClasses = [
            'critical' => 'bg-purple-100 text-purple-700',
            'high'     => 'bg-orange-100 text-orange-700',
            'medium'   => 'bg-yellow-100 text-yellow-700',
            'low'      => 'bg-green-100 text-green-700',
        ][$sev] ?? 'bg-slate-100 text-slate-600';
        $typeLabel = ucwords(str_replace('_', ' ', $detection->detection_type));
        @endphp
        <div class="flex items-center gap-3 px-5 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50/60 transition-colors group">
            {{-- Severity pill --}}
            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $sevClasses }} w-16 justify-center">{{ $sev }}</span>

            {{-- Type badge --}}
            <span class="shrink-0 text-[10px] font-semibold text-slate-400 uppercase tracking-wide w-24 truncate">{{ $typeLabel }}</span>

            {{-- Title + details truncated --}}
            <div class="flex-1 min-w-0">
                <span class="text-sm font-semibold text-slate-800">{{ $detection->title }}</span>
                <span class="text-xs text-slate-400 ml-2 hidden sm:inline truncate">— {{ Str::limit($detection->details, 60) }}</span>
            </div>

            {{-- Status --}}
            @if(!$detection->is_resolved)
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase">Open</span>
            @else
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase">Fixed</span>
            @endif

            {{-- Time --}}
            <span class="shrink-0 text-[11px] text-slate-400 hidden md:block">{{ $detection->created_at->format('M d H:i') }}</span>

            {{-- Action --}}
            <a href="{{ route('security.detections.show', $detection) }}" class="shrink-0 text-[11px] font-semibold text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity">View →</a>
        </div>
        @empty
        <div class="py-12 text-center">
            <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p class="font-semibold text-slate-600 text-sm">No detections yet</p>
            <p class="text-slate-400 text-xs mt-1">Use the test panel below to simulate threats</p>
        </div>
        @endforelse
    </div>

    {{-- ── Test Forms (collapsible panel) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <button onclick="document.getElementById('testPanel').classList.toggle('hidden')"
            class="w-full px-5 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors group">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-semibold text-slate-900 text-sm">Detection Test Panel</span>
                <span class="text-xs text-slate-400 font-normal">— Simulate threats for demonstration</span>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div id="testPanel" class="hidden border-t border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-0 divide-y md:divide-y-0 md:divide-x divide-slate-100">

                {{-- Phishing --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-md bg-red-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-slate-800">Phishing</h4>
                    </div>
                    <form method="POST" action="{{ route('security.test.phishing') }}" class="space-y-2.5">
                        @csrf
                        <input type="email" name="sender_email" required placeholder="Sender email" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none">
                        <input type="text" name="subject" required placeholder="Subject line" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none">
                        <textarea name="email_content" rows="2" required placeholder="Email content…" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none resize-none"></textarea>
                        <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">Run Test</button>
                    </form>
                </div>

                {{-- Password --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-md bg-yellow-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-slate-800">Password</h4>
                    </div>
                    <form method="POST" action="{{ route('security.test.password') }}" class="space-y-2.5">
                        @csrf
                        <input type="text" name="password" required placeholder="Password to analyse" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
                        <p class="text-[11px] text-slate-400">Try: <code class="bg-slate-100 px-1 rounded">password123</code> or <code class="bg-slate-100 px-1 rounded">admin</code></p>
                        <button type="submit" class="w-full py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-lg transition-colors">Run Test</button>
                    </form>
                </div>

                {{-- Social Engineering --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-md bg-orange-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-slate-800">Social Eng.</h4>
                    </div>
                    <form method="POST" action="{{ route('security.test.social-engineering') }}" class="space-y-2.5">
                        @csrf
                        <textarea name="message" rows="2" required placeholder="Suspicious message…" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none resize-none"></textarea>
                        <input type="text" name="context" required placeholder="Context (e.g. HR email)" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none">
                        <button type="submit" class="w-full py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-colors">Run Test</button>
                    </form>
                </div>

                {{-- Malware --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-md bg-purple-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-slate-800">Malware</h4>
                    </div>
                    <form method="POST" action="{{ route('security.test.malware') }}" class="space-y-2.5" enctype="multipart/form-data">
                        @csrf
                        <label class="flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50/30 transition-colors">
                            <svg class="w-4 h-4 text-slate-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-[11px] text-slate-400">Click to upload file</span>
                            <input type="file" name="file" required class="hidden">
                        </label>
                        <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition-colors">Run Test</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
