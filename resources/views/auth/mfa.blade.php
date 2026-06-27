@extends('layouts.public')

@section('title', 'Multi-Factor Authentication')

@section('content')
<div class="flex items-center justify-center py-12 md:py-20 px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100">
        
        <div class="flex flex-col items-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-500/10 text-blue-600 mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Two-Step Verification</h2>
            <p class="text-slate-500 text-sm mt-2 text-center">Enter the 6-digit code from your authenticator app to verify your identity.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 text-sm font-medium mb-1">Verification failed</p>
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.mfa.post') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Verification Code</label>
                <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required autofocus maxlength="6" class="w-full px-4 py-2.5 text-center text-xl font-bold tracking-widest bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 @error('code') border-red-500 @enderror" placeholder="000000">
                @error('code')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="remember_browser" name="remember_browser" type="checkbox" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                <label for="remember_browser" class="ml-2 block text-xs text-slate-700">
                    Remember this browser for 30 days
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Verify and Log in
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-xs text-center text-slate-500">
                Having trouble? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Try signing in again</a>
            </p>
        </div>

    </div>

</div>
@endsection
