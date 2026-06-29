@extends('layouts.public')

@section('title', 'Register')

@section('content')
<div class="flex items-center justify-center py-12 md:py-20 px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100">
        
        <div class="flex flex-col items-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-blue-600 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </a>
            <h2 class="text-2xl font-bold text-slate-900">Create an account</h2>
            <p class="text-slate-500 text-sm mt-2">Sign up to access modules and start learning</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 text-sm font-medium mb-2">Registration failed</p>
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4" id="register-form">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 @error('name') border-red-500 @enderror" placeholder="John Doe">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 @error('email') border-red-500 @enderror" placeholder="you@example.com">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" name="password" type="password" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror" placeholder="Min. 12 characters, mixed case, numbers & symbols">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Real-time password strength meter --}}
                <div id="password-strength-panel" class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-lg" style="display:none;">
                    {{-- Strength bar --}}
                    <div class="flex items-center gap-2 mb-2.5">
                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full rounded-full transition-all duration-500 ease-out" style="width: 0%;"></div>
                        </div>
                        <span id="strength-label" class="text-xs font-semibold text-slate-400 min-w-[60px] text-right">—</span>
                    </div>

                    {{-- Requirements checklist --}}
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1.5">
                        <div class="flex items-center gap-1.5" id="req-length">
                            <div class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" id="req-length-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-slate-500">12+ characters</span>
                        </div>
                        <div class="flex items-center gap-1.5" id="req-upper">
                            <div class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" id="req-upper-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-slate-500">Uppercase letter</span>
                        </div>
                        <div class="flex items-center gap-1.5" id="req-lower">
                            <div class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" id="req-lower-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-slate-500">Lowercase letter</span>
                        </div>
                        <div class="flex items-center gap-1.5" id="req-number">
                            <div class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" id="req-number-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-slate-500">Number (0-9)</span>
                        </div>
                        <div class="flex items-center gap-1.5" id="req-symbol">
                            <div class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" id="req-symbol-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-slate-500">Special symbol (!@#$…)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Re-enter password">
                <p id="password-match-msg" class="text-xs mt-1 hidden"></p>
            </div>

            <button type="submit" id="register-submit-btn" class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Sign Up
            </button>
        </form>

        {{-- Password policy notice --}}
        <div class="mt-4 p-3 bg-slate-50 border border-slate-100 rounded-lg">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <strong class="text-slate-600">Password Policy:</strong> Minimum 12 characters with at least one uppercase letter, one lowercase letter, one number, and one special symbol.
                </p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-xs text-center text-slate-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Log in</a>
            </p>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pwField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    const panel = document.getElementById('password-strength-panel');
    const bar = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    const matchMsg = document.getElementById('password-match-msg');

    const requirements = {
        length: { test: v => v.length >= 12, icon: 'req-length-icon' },
        upper:  { test: v => /[A-Z]/.test(v), icon: 'req-upper-icon' },
        lower:  { test: v => /[a-z]/.test(v), icon: 'req-lower-icon' },
        number: { test: v => /[0-9]/.test(v), icon: 'req-number-icon' },
        symbol: { test: v => /[^A-Za-z0-9]/.test(v), icon: 'req-symbol-icon' },
    };

    const strengthLevels = [
        { min: 0, label: 'Too Weak', color: '#ef4444', bg: '#fef2f2' },
        { min: 1, label: 'Weak', color: '#f97316', bg: '#fff7ed' },
        { min: 2, label: 'Fair', color: '#eab308', bg: '#fefce8' },
        { min: 3, label: 'Good', color: '#22c55e', bg: '#f0fdf4' },
        { min: 4, label: 'Strong', color: '#16a34a', bg: '#f0fdf4' },
        { min: 5, label: 'Excellent', color: '#059669', bg: '#ecfdf5' },
    ];

    pwField.addEventListener('input', function() {
        const val = this.value;

        if (val.length === 0) {
            panel.style.display = 'none';
            return;
        }
        panel.style.display = 'block';

        let met = 0;
        for (const [key, req] of Object.entries(requirements)) {
            const passed = req.test(val);
            const iconEl = document.getElementById(req.icon);
            const checkSvg = iconEl.querySelector('svg');

            if (passed) {
                met++;
                iconEl.style.backgroundColor = '#059669';
                iconEl.style.borderColor = '#059669';
                checkSvg.classList.remove('hidden');
            } else {
                iconEl.style.backgroundColor = 'transparent';
                iconEl.style.borderColor = '#cbd5e1';
                checkSvg.classList.add('hidden');
            }
        }

        // Bonus: extra length beyond 12
        let bonusScore = met;
        if (val.length >= 16) bonusScore = Math.min(bonusScore + 0.5, 5);

        const level = strengthLevels[Math.min(Math.floor(bonusScore), 5)];
        const pct = Math.min((bonusScore / 5) * 100, 100);

        bar.style.width = pct + '%';
        bar.style.backgroundColor = level.color;
        label.textContent = level.label;
        label.style.color = level.color;
    });

    // Password match indicator
    function checkMatch() {
        const pw = pwField.value;
        const confirm = confirmField.value;
        if (confirm.length === 0) {
            matchMsg.classList.add('hidden');
            return;
        }
        matchMsg.classList.remove('hidden');
        if (pw === confirm) {
            matchMsg.textContent = '✓ Passwords match';
            matchMsg.className = 'text-xs mt-1 text-emerald-600 font-medium';
        } else {
            matchMsg.textContent = '✗ Passwords do not match';
            matchMsg.className = 'text-xs mt-1 text-red-500 font-medium';
        }
    }
    confirmField.addEventListener('input', checkMatch);
    pwField.addEventListener('input', checkMatch);
});
</script>
@endpush
