@extends('layouts.app')

@section('title', 'Learning Modules')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Learning Modules</h1>
            <p class="text-slate-600 mt-1">Explore and enroll in available security training modules</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-slate-200 p-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" name="search" placeholder="Search modules..." value="{{ request('search') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <!-- Category Filter -->
            <select name="category" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <!-- Difficulty Filter -->
            <select name="difficulty" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">All Levels</option>
                <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
            </select>

            <!-- Submit -->
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Filter
            </button>
            <a href="{{ route('modules.index') }}" class="px-6 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium">
                Reset
            </a>
        </form>
    </div>

    <!-- Modules Grid -->
    @if($modules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modules as $module)
                <div class="bg-white rounded-lg border border-slate-200 hover:border-blue-300 hover:shadow-lg transition-all overflow-hidden">
                    <!-- Module Header -->
                    <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-slate-50">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-slate-900 line-clamp-2">{{ $module->title }}</h3>
                            @if(in_array($module->id, $enrolledModuleIds))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Enrolled
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 line-clamp-2">{{ $module->description }}</p>
                    </div>

                    <!-- Module Details -->
                    <div class="p-6 space-y-4">
                        <!-- Meta Info -->
                        <div class="grid grid-cols-3 gap-3 text-xs">
                            <div class="bg-slate-50 p-2 rounded">
                                <div class="text-slate-600 font-medium">Duration</div>
                                <div class="text-slate-900 font-semibold">{{ $module->duration_minutes }}m</div>
                            </div>
                            <div class="bg-slate-50 p-2 rounded">
                                <div class="text-slate-600 font-medium">Level</div>
                                <div class="text-slate-900 font-semibold capitalize">{{ $module->difficulty }}</div>
                            </div>
                            <div class="bg-slate-50 p-2 rounded">
                                <div class="text-slate-600 font-medium">Category</div>
                                <div class="text-slate-900 font-semibold">{{ substr($module->category, 0, 3) }}</div>
                            </div>
                        </div>

                        <!-- Category Badge -->
                        <div class="flex gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $module->category }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('modules.show', $module) }}" class="flex-1 text-center py-2 px-3 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors font-medium text-sm">
                                View Details
                            </a>
                            @if(!in_array($module->id, $enrolledModuleIds))
                                <form action="{{ route('modules.enroll', $module) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 px-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                        Enroll
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $modules->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg border border-slate-200">
            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <h3 class="text-lg font-semibold text-slate-900 mb-1">No modules found</h3>
            <p class="text-slate-600">Try adjusting your filters to find modules</p>
        </div>
    @endif
</div>
@endsection
