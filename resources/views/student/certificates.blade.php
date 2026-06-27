@extends('layouts.app')

@section('title', 'My Certificates')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header layout -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Achievements & Certificates</h2>
        <p class="text-slate-500 text-sm">Review your earned course completion certificates and security credential badges.</p>
    </div>

    <!-- Certificates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($certificates as $cert)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <div class="p-6 flex-1 space-y-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                    🏆
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-lg">{{ $cert->module->title ?? 'Certificate' }}</h4>
                    <p class="text-xs text-slate-450 mt-1">Credential ID: <span class="font-mono text-slate-700">{{ $cert->credential_id ?? 'N/A' }}</span></p>
                    <p class="text-xs text-slate-450">Issue Date: {{ $cert->issued_at ? $cert->issued_at->format('M d, Y') : 'N/A' }}</p>
                </div>
                
                <div class="pt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Verified Certificate
                    </span>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button onclick="toggleCertModal(true, {
                    recipient: '{{ addslashes($cert->user->name) }}',
                    module: '{{ addslashes($cert->module->title ?? 'Certificate') }}',
                    credential_id: '{{ $cert->credential_id ?? $cert->certificate_number }}'
                })" class="w-full text-center py-2 bg-white border border-blue-200 text-blue-700 font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-all text-xs">
                    View Certificate
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                <p class="font-medium">No certificates earned yet</p>
                <p class="text-sm">Complete course quizzes to earn certificates.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    {{ $certificates->links() }}

    <!-- CERTIFICATE PRINT MODAL DIALOG -->
    <div id="cert-modal-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleCertModal(false)">
        <div id="cert-modal" class="max-w-3xl w-full bg-white rounded-2xl shadow-2xl p-8 border border-slate-200 text-center relative scale-90 transition-transform duration-300 ease-out mx-4" onclick="event.stopPropagation()">
            
            <!-- Close Button -->
            <button onclick="toggleCertModal(false)" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-650 transition-colors rounded-lg hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Certificate Frame -->
            <div class="border-8 border-double border-blue-600 p-6 md:p-12 space-y-6 bg-slate-50/30 rounded-xl relative">
                
                <!-- Logo & Watermark -->
                <div class="flex flex-col items-center justify-center gap-1">
                    <span class="text-blue-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <span class="font-black text-xl tracking-wider text-slate-900 uppercase">SecureLearn Platform</span>
                </div>

                <div class="space-y-2">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Certificate of Completion</div>
                    <div class="text-sm font-semibold text-slate-600 italic">This is proudly presented to</div>
                    <div id="cert-recipient-name" class="text-3xl font-extrabold text-slate-900 border-b border-slate-200 pb-2 max-w-md mx-auto leading-relaxed">Lebron James Pathay</div>
                </div>

                <div class="space-y-4 max-w-lg mx-auto">
                    <p class="text-slate-600 text-sm leading-relaxed">For demonstrating outstanding knowledge and competency in database defenses by passing all criteria, lessons, and quizzes in the curriculum</p>
                    <p id="cert-module-title" class="text-lg font-black text-blue-600">SQL Injection Prevention</p>
                </div>

                <!-- Signatures and seal layout -->
                <div class="grid grid-cols-3 gap-4 pt-6 items-center">
                    
                    <!-- Left Signature -->
                    <div class="text-center space-y-1">
                        <div class="font-serif italic text-sm text-slate-800 border-b border-slate-200 pb-1 w-32 mx-auto">SecureLearn Board</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">Issuing Authority</div>
                    </div>

                    <!-- Center Seal -->
                    <div class="flex items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 text-white flex flex-col items-center justify-center font-bold shadow-md relative border-4 border-white select-none">
                             <span class="text-[8px] uppercase tracking-wider font-extrabold">Passed</span>
                            <span class="text-xs">✔</span>
                            <div class="absolute -inset-1 rounded-full border border-yellow-500/30"></div>
                        </div>
                    </div>

                    <!-- Right Signature -->
                    <div class="text-center space-y-1">
                        <div id="cert-recipient-signature" class="font-serif italic text-sm text-slate-800 border-b border-slate-200 pb-1 w-32 mx-auto">Lebron J. Pathay</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">Recipient Signature</div>
                    </div>
                </div>

                <!-- Bottom Credential info -->
                <div class="text-[10px] text-slate-400 font-bold pt-4">
                    Verify at: <span id="cert-verification-link" class="font-mono text-slate-500 underline select-all">securelearn.org/verify/SL-10384-SQL</span>
                </div>
            </div>

            <!-- Print Actions -->
            <div class="mt-6 flex items-center justify-center gap-3">
                <button onclick="window.print();" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Certificate
                </button>
                <button onclick="toggleCertModal(false)" class="bg-white border border-slate-250 hover:bg-slate-50 text-slate-700 font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">Close</button>
            </div>

        </div>
    </div>

</div>

<script>
    function toggleCertModal(show, data = null) {
        const backdrop = document.getElementById('cert-modal-backdrop');
        const modal = document.getElementById('cert-modal');
        if (show && data) {
            document.getElementById('cert-recipient-name').innerText = data.recipient;
            document.getElementById('cert-module-title').innerText = data.module;
            document.getElementById('cert-recipient-signature').innerText = data.recipient;
            document.getElementById('cert-verification-link').innerText = 'securelearn.org/verify/' + data.credential_id;

            backdrop.classList.remove('pointer-events-none', 'opacity-0');
            backdrop.classList.add('opacity-100');
            modal.classList.remove('scale-90');
            modal.classList.add('scale-100');
        } else {
            backdrop.classList.add('pointer-events-none', 'opacity-0');
            backdrop.classList.remove('opacity-100');
            modal.classList.remove('scale-100');
            modal.classList.add('scale-90');
        }
    }
</script>
@endsection
