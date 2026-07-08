@extends('layouts.public')

@section('title', 'Log in')

@section('content')
<div class="flex items-center justify-center py-12 md:py-20 px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100">
        
        <div class="flex flex-col items-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-blue-600 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </a>
            <h2 class="text-2xl font-bold text-slate-900">Sign in to your account</h2>
            <p class="text-slate-500 text-sm mt-2">Enter your credentials to access the platform</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div id="login-error-box" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                @php
                    $isLockout = false;
                    foreach ($errors->all() as $error) {
                        if (str_contains($error, 'Too many unsuccessful')) {
                            $isLockout = true;
                        }
                    }
                @endphp

                @if ($isLockout)
                    {{-- Lockout state: show a locked-out banner without revealing duration --}}
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span class="text-red-800 text-sm font-semibold">Account Temporarily Locked</span>
                    </div>
                    <p class="text-red-700 text-xs leading-relaxed">
                        We've detected multiple unsuccessful login attempts. For your security, this account has been temporarily locked. Please try again later or reset your password.
                    </p>
                @else
                    {{-- Generic credential error --}}
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5" id="login-form">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 @error('email') border-red-500 @enderror" placeholder="you@example.com">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>
                <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror">
            </div>

            {{-- Custom Math CAPTCHA --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Security Check</label>
                <div class="flex items-center gap-3">
                    <div id="captcha-question" class="px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-medium min-w-[120px] text-center">
                        Loading...
                    </div>
                    <input type="hidden" id="captcha-id" name="captcha_id">
                    <input type="number" id="captcha-answer" name="captcha_answer" required class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400" placeholder="Your answer">
                    <button type="button" id="refresh-captcha" class="p-2.5 text-slate-500 hover:text-slate-700 transition-colors" title="Refresh CAPTCHA">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
                @error('captcha_answer')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-slate-600">Remember me</label>
            </div>

            <button type="submit" id="login-submit-btn" class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Sign in
            </button>
        </form>

        {{-- Security notice --}}
        <div class="mt-4 p-3 bg-slate-50 border border-slate-100 rounded-lg">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <strong class="text-slate-600">Security Notice:</strong> Multiple unsuccessful login attempts will result in a temporary account lockout to protect your account.
                </p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-xs text-center text-slate-500">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">Sign up now</a>
            </p>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Load CAPTCHA on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCaptcha();
        
        // Refresh CAPTCHA button
        document.getElementById('refresh-captcha').addEventListener('click', loadCaptcha);
    });

    function loadCaptcha() {
        fetch('{{ route('captcha.generate') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('captcha-question').textContent = data.question;
                document.getElementById('captcha-id').value = data.captcha_id;
                document.getElementById('captcha-answer').value = '';
            })
            .catch(error => {
                console.error('Error loading CAPTCHA:', error);
                document.getElementById('captcha-question').textContent = 'Error loading';
            });
    }
</script>
@endpush
