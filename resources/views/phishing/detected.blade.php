@extends('layouts.app')

@section('title', 'Phishing Attempt Detected!')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 py-6">

    <!-- Warning / Impact Header -->
    <div class="bg-red-50 border border-red-200 rounded-2xl p-8 text-center space-y-4 shadow-sm">
        <div class="w-16 h-16 rounded-full bg-red-100 text-red-650 flex items-center justify-center font-bold text-2xl mx-auto border border-red-200 animate-pulse">
            ⚠️
        </div>
        <div class="space-y-1">
            <h2 class="text-2xl font-black text-red-900 tracking-tight">You Clicked a Simulated Phishing Link!</h2>
            <p class="text-red-750 text-sm max-w-lg mx-auto">Don't worry, this was a training exercise launched by the administration to help improve security awareness.</p>
        </div>
    </div>

    <!-- Educational breakdown -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8 space-y-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Campaign Details</h3>
            <p class="text-xs text-slate-500">Here is the context of the phishing simulation you received.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-y border-slate-100 py-4">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">Simulation Name</span>
                <span class="text-sm font-semibold text-slate-800">{{ $campaign->name }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase">Template Type</span>
                <span class="text-sm font-mono text-slate-700 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ str_replace('_', ' ', $campaign->template_type) }}</span>
            </div>
        </div>

        <!-- Lessons & Tips -->
        <div class="space-y-4">
            <h4 class="font-bold text-slate-900 text-base">Key Indicators You Missed:</h4>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">1</span>
                    <div>
                        <strong class="text-sm text-slate-800">Check the Sender Domain</strong>
                        <p class="text-xs text-slate-500 leading-relaxed">Always inspect the sender's address. Scammers use lookalike domains (e.g. <code>micros0ft-support.com</code> using a zero or <code>netflix-accounts-portal.net</code> instead of official sites).</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">2</span>
                    <div>
                        <strong class="text-sm text-slate-800">Beware of Artificial Urgency</strong>
                        <p class="text-xs text-slate-500 leading-relaxed">Phishing emails often pressure you to act quickly (e.g., "Account suspended in 2 hours") to create panic so you bypass verification checks.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">3</span>
                    <div>
                        <strong class="text-sm text-slate-800">Inspect Link Targets</strong>
                        <p class="text-xs text-slate-500 leading-relaxed">Hover over buttons or links to check the destination URL before clicking. If it points to an unknown external portal, report it immediately.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="pt-4 flex justify-end gap-3">
            <a href="/student/dashboard" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-100">
                Go to Dashboard
            </a>
            <a href="/student/inbox" class="px-5 py-2.5 bg-white border border-slate-250 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-sm transition-colors">
                Back to Inbox
            </a>
        </div>

    </div>

</div>
@endsection
