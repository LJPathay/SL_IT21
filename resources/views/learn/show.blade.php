@extends('layouts.app')

@section('title', $module->title ?? 'Module Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ $module->title }}</h1>
            <p class="text-slate-500 mt-2">{{ $module->description ?? 'No description available for this module.' }}</p>
        </div>
        <div class="space-y-2 text-right">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-slate-100 text-slate-700 text-sm">
                <strong>Category:</strong> {{ $module->category ?? 'General' }}
            </span>
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-slate-100 text-slate-700 text-sm">
                <strong>Difficulty:</strong> {{ ucfirst($module->difficulty ?? 'beginner') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Progress</div>
            <div class="text-4xl font-bold text-slate-900">{{ $progressPercentage ?? 0 }}%</div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progressPercentage ?? 0 }}%"></div>
            </div>
            <div class="text-sm text-slate-500">{{ $lessons->count() }} lesson{{ $lessons->count() === 1 ? '' : 's' }} available</div>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Module Overview</h2>
            <div class="space-y-3 text-sm text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-700">Course:</span>
                    <span>{{ $module->course->title ?? 'Standalone module' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-700">Access:</span>
                    <span>{{ empty($module->required_roles) ? 'All roles' : implode(', ', $module->required_roles) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-700">Status:</span>
                    <span>{{ $module->is_active ? 'Published' : 'Draft' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900">Lesson Content</h2>
        </div>

        <div class="p-6">
            <div class="text-slate-500 text-sm mb-6">
                This module currently has no published lesson content. Create lessons from the admin module page to enable learner access.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 p-6 bg-slate-50">
                    <h3 class="font-semibold text-slate-900 mb-3">Next Step</h3>
                    <p class="text-slate-600 text-sm">Add one or more lessons to this module to start training users on the required security topic.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-6 bg-slate-50">
                    <h3 class="font-semibold text-slate-900 mb-3">Need help?</h3>
                    <p class="text-slate-600 text-sm">Use the admin module editor to populate lesson materials and attach reference files or malware training exercises.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('modules.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors text-sm font-semibold">
            Back to Catalog
        </a>
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('admin.modules.edit', $module) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-colors text-sm font-semibold">
            Edit Module
        </a>
        @endif
    </div>
</div>
@endsection
