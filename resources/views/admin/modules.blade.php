@extends('layouts.app')

@section('title', 'Manage Modules')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Security Training Modules</h2>
            <p class="text-slate-500 text-sm">Create, publish, and manage your organization's interactive curriculum.</p>
        </div>
        <button onclick="toggleCreateDrawer(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Module
        </button>
    </div>

    <!-- Stats summary row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Modules</div>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $totalModules }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Active Modules</div>
            <div class="text-2xl font-bold text-green-600 mt-1">{{ $activeModules }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Drafts</div>
            <div class="text-2xl font-bold text-slate-500 mt-1">{{ $inactiveModules }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Enrollments</div>
            <div class="text-2xl font-bold text-blue-600 mt-1">{{ $totalEnrollments ?? 0 }}</div>
        </div>
    </div>

    <!-- Filters and Table container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Row -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search modules..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            
            <div class="flex items-center gap-3 overflow-x-auto">
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-600 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Categories</option>
                    <option value="web-security">Web Security</option>
                    <option value="social-eng">Social Engineering</option>
                    <option value="malware">Malware</option>
                </select>
                
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-600 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <!-- Modules Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Module Name & Vendor</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Lessons</th>
                        <th class="px-6 py-4">Enrollments</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($modules as $module)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ !$module->is_active ? 'bg-amber-50/5' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $module->title }}</div>
                            <div class="text-xs text-slate-450 mt-0.5">{{ $module->description ?? 'No description' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-lg border border-green-100">{{ $module->category ?? 'General' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $module->lesson_count ?? 0 }} lessons</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $module->enrollment_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($module->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Published
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-450"></span>
                                Draft
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.modules.edit', $module) }}" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-3 py-1.5 rounded-lg transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.modules.toggle-status', $module) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $module->is_active ? 'bg-orange-50 text-orange-700 hover:bg-orange-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                        {{ $module->is_active ? 'Draft' : 'Publish' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" class="inline" onsubmit="return confirm('Are you sure you want to archive this module?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-slate-50 text-slate-700 hover:bg-slate-100 font-semibold px-3 py-1.5 rounded-lg transition-colors">Archive</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-medium">No modules found</p>
                                <p class="text-sm">Create your first training module to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $modules->links() }}
    </div>

    <!-- CREATE DRAWER DIALOG (SLIDE-OVER) -->
    <div id="create-drawer-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleCreateDrawer(false)">
        <div id="create-drawer" class="absolute inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Create Training Module</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Fill in the options to build a mockup module.</p>
                </div>
                <button onclick="toggleCreateDrawer(false)" class="p-1 text-slate-400 hover:text-slate-600 transition-colors rounded-lg hover:bg-slate-200/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.modules.store') }}" class="flex-1 overflow-y-auto p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Module Name</label>
                    <input name="title" type="text" placeholder="e.g. Cross-Site Scripting (XSS) Mitigation" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Category</label>
                    <select name="category" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                        <option value="Security Awareness">Security Awareness</option>
                        <option value="Cybersecurity">Cybersecurity</option>
                        <option value="Compliance">Compliance</option>
                        <option value="IT Security">IT Security</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Difficulty</label>
                        <select name="difficulty" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Duration (Minutes)</label>
                        <input name="duration_minutes" type="number" min="15" max="300" value="45" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lesson Count</label>
                        <input name="lesson_count" type="number" min="0" value="4" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Course</label>
                        <select name="course_id" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                            <option value="">No Course</option>
                            @foreach($courses ?? [] as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="4" placeholder="Briefly describe the topics covered in this module..." required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Required Roles</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="required_roles[]" value="student" class="rounded text-blue-600">
                            <span class="text-sm">Student</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="required_roles[]" value="instructor" class="rounded text-blue-600">
                            <span class="text-sm">Instructor</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="required_roles[]" value="admin" class="rounded text-blue-600">
                            <span class="text-sm">Admin</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="module_active" class="rounded text-blue-600">
                    <label for="module_active" class="text-sm font-semibold text-slate-700">Publish immediately</label>
                </div>

                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 text-xs text-blue-800 flex gap-2">
                    <svg class="w-4 h-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Modules are added in draft status by default unless "Publish immediately" is checked.</span>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleCreateDrawer(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Save Module</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleCreateDrawer(show) {
        const backdrop = document.getElementById('create-drawer-backdrop');
        const drawer = document.getElementById('create-drawer');
        if (show) {
            backdrop.classList.remove('pointer-events-none', 'opacity-0');
            backdrop.classList.add('opacity-100');
            drawer.classList.remove('translate-x-full');
        } else {
            backdrop.classList.add('pointer-events-none', 'opacity-0');
            backdrop.classList.remove('opacity-100');
            drawer.classList.add('translate-x-full');
        }
    }
</script>
@endsection
