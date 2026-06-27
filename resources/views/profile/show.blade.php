@extends('layouts.app')

@section('title', 'My Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header layout -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Account & Profile Settings</h2>
        <p class="text-slate-500 text-sm">Customize your personal profile details, security settings, and Multi-Factor Authentication.</p>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-150 rounded-xl text-sm font-semibold text-green-800 flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 space-y-1">
            <p class="font-bold">Please correct the following errors:</p>
            @foreach ($errors->all() as $error)
                <p class="text-xs">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left Column: Navigation/Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center space-y-4">
                <div class="w-20 h-20 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-2xl mx-auto select-none">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-400 font-medium capitalize">{{ $user->role }}</p>
                </div>
                <div class="border-t border-slate-100 pt-3 text-xs text-slate-450">
                    Account Status: <span class="text-green-600 font-bold">Active</span>
                </div>
            </div>
            
            <!-- Security Status overview -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3">
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Security Profile</h4>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">MFA Status</span>
                    <span class="font-bold {{ $user->mfa_enabled ? 'text-green-600' : 'text-slate-500' }}">{{ $user->mfa_enabled ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Role level</span>
                    <span class="font-bold text-slate-700 capitalize">{{ $user->role }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Forms -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Profile form -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 text-sm">Personal Information</h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-1">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm font-medium">
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase mb-1 select-none">Email Address (Read-only)</span>
                            <input type="text" value="{{ $user->email }}" disabled class="w-full px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-450 text-sm font-medium cursor-not-allowed select-none">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <h4 class="font-bold text-slate-900 text-sm mb-3">Change Password</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-bold text-slate-500 uppercase mb-1">New Password</label>
                                <input id="password" name="password" type="password" placeholder="Minimum 8 characters" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase mb-1">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter password" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-100">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Two-Factor settings card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Two-Factor Authentication (MFA)</h3>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->mfa_enabled ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $user->mfa_enabled ? 'bg-green-500' : 'bg-slate-450' }}"></span>
                        {{ $user->mfa_enabled ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-slate-500 text-sm leading-relaxed">Multi-Factor Authentication adds an additional layer of security to your account. When enabled, logging in will require a one-time passcode sent directly to your registered email.</p>
                    
                    <form action="{{ route('security.mfa.toggle') }}" method="POST" class="flex justify-end pt-2">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 font-bold rounded-xl text-sm transition-colors {{ $user->mfa_enabled ? 'bg-slate-700 hover:bg-slate-800 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-100' }}">
                            {{ $user->mfa_enabled ? 'Deactivate MFA' : 'Activate MFA' }}
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
