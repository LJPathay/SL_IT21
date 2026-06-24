@extends('layouts.public')

@section('title', 'Page Not Found')

@section('content')
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-amber-600 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Page Not Found</h2>
        <p class="text-slate-600 mb-8">The page you are looking for could not be found.</p>
        
        <div class="space-x-4">
            <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Go Home
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-slate-200 text-slate-900 rounded-lg hover:bg-slate-300 transition-colors">
                Go to Dashboard
            </a>
            @endauth
        </div>
    </div>
</div>
@endsection
