@extends('layouts.app')

@section('title', 'Set Up Two-Factor Authentication')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Profile
        </a>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Set Up Two-Factor Authentication</h2>
        <p class="text-slate-500 text-sm mt-1">Scan the QR code below with your authenticator app (Google Authenticator, Authy, Microsoft Authenticator, etc.)</p>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 space-y-1">
            <p class="font-bold">Verification failed:</p>
            @foreach ($errors->all() as $error)
                <p class="text-xs">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 text-sm">Step 1: Scan QR Code</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8 items-center">
                <!-- QR Code -->
                <div class="shrink-0 p-4 bg-white border-2 border-slate-200 rounded-2xl shadow-inner">
                    <img src="{{ $qrCodeImageUrl }}" alt="MFA QR Code" class="w-48 h-48">
                </div>

                <div class="flex-1 space-y-4">
                    <div>
                        <p class="text-slate-600 text-sm leading-relaxed">Open your authenticator app and scan the QR code on the left. If you can't scan, manually enter the key below:</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Manual Entry Key</label>
                        <code class="text-base font-mono font-bold text-slate-900 tracking-widest select-all break-all">{{ $secret }}</code>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Important:</strong> Save this key in a safe place. You will need it to recover your account if you lose access to your authenticator app.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 text-sm">Step 2: Verify Code</h3>
        </div>
        <div class="p-6">
            <p class="text-slate-600 text-sm mb-4">Enter the 6-digit code currently shown in your authenticator app to verify the setup.</p>
            <form action="{{ route('profile.mfa.confirm') }}" method="POST" class="space-y-4">
                @csrf
                <div class="max-w-xs">
                    <label for="code" class="block text-xs font-bold text-slate-500 uppercase mb-1">Verification Code</label>
                    <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required autofocus maxlength="6"
                        class="w-full px-4 py-3 text-center text-2xl font-bold tracking-[0.3em] bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 @error('code') border-red-500 @enderror"
                        placeholder="000000">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-100">
                        Verify & Activate MFA
                    </button>
                    <a href="{{ route('profile.show') }}" class="px-6 py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
