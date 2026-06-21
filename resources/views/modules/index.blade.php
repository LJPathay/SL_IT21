@extends('layouts.public')

@section('title', 'Module Catalog')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-8">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 mb-2">Available Modules</h2>
            <p class="text-lg text-slate-500">Explore and enroll in security training modules.</p>
        </div>
        <div class="relative max-w-sm w-full">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search modules..." class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm">
        </div>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 overflow-x-auto pb-4">
        <button class="px-5 py-2 bg-blue-100 text-blue-700 font-medium rounded-full text-sm whitespace-nowrap shadow-sm">All</button>
        <button class="px-5 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium rounded-full text-sm whitespace-nowrap transition-colors">Web Security</button>
        <button class="px-5 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium rounded-full text-sm whitespace-nowrap transition-colors">Social Engineering</button>
        <button class="px-5 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium rounded-full text-sm whitespace-nowrap transition-colors">Malware</button>
    </div>

    <!-- Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Module Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <div class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg uppercase tracking-wider">Web Security</span>
                    <span class="text-sm font-semibold text-slate-500">2 Hours</span>
                </div>
                <h4 class="text-2xl font-bold text-slate-900 mb-3">SQL Injection Prevention</h4>
                <p class="text-slate-600 text-base mb-6 leading-relaxed">Powered by W3Schools content. Learn how attackers use SQL Injection and how to protect against it.</p>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 group-hover:bg-blue-50 transition-colors">
                <a href="{{ url('/modules/1') }}" class="w-full block text-center py-3 bg-white border border-blue-200 text-blue-700 font-bold rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">View Details</a>
            </div>
        </div>

        <!-- Module Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <div class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg uppercase tracking-wider">Critical</span>
                    <span class="text-sm font-semibold text-slate-500">1.5 Hours</span>
                </div>
                <h4 class="text-2xl font-bold text-slate-900 mb-3">Phishing Detection</h4>
                <p class="text-slate-600 text-base mb-6 leading-relaxed">Identify characteristics of phishing emails, fraudulent websites, and deceptive URLs.</p>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 group-hover:bg-blue-50 transition-colors">
                <a href="{{ url('/modules/2') }}" class="w-full block text-center py-3 bg-white border border-blue-200 text-blue-700 font-bold rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">View Details</a>
            </div>
        </div>

        <!-- Module Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <div class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-lg uppercase tracking-wider">Intermediate</span>
                    <span class="text-sm font-semibold text-slate-500">3 Hours</span>
                </div>
                <h4 class="text-2xl font-bold text-slate-900 mb-3">Password Security Assessment</h4>
                <p class="text-slate-600 text-base mb-6 leading-relaxed">Detect weak password practices and educate users on strong password creation.</p>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 group-hover:bg-blue-50 transition-colors">
                <a href="{{ url('/modules/3') }}" class="w-full block text-center py-3 bg-white border border-blue-200 text-blue-700 font-bold rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">View Details</a>
            </div>
        </div>

    </div>

</div>
@endsection
