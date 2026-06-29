@extends('layouts.app')

@section('title', 'Manage Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Training Courses</h2>
            <p class="text-slate-500 text-sm">Manage course offerings, instructors, and enrollments.</p>
        </div>
        <button onclick="toggleCourseModal(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Course
        </button>
    </div>

    <!-- Stats summary row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Courses</div>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $courses->count() ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Active Courses</div>
            <div class="text-2xl font-bold text-green-600 mt-1">{{ $courses->where('is_active', true)->count() ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Modules</div>
            <div class="text-2xl font-bold text-blue-600 mt-1">{{ $courses->sum(function($course) { return $course->modules->count(); }) ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Enrollments</div>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ $courses->sum(function($course) { return $course->enrolled_count ?? 0; }) ?? 0 }}</div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Course Name</th>
                        <th class="px-6 py-4">Instructor</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4">Modules</th>
                        <th class="px-6 py-4">Enrolled</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $course->title }}</div>
                            <div class="text-xs text-slate-450 mt-0.5">{{ $course->code ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $course->instructor->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200">{{ ucfirst($course->level ?? 'beginner') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $course->modules->count() ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $course->enrolled_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($course->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-450"></span>
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-3 py-1.5 rounded-lg transition-colors">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <p class="font-medium">No courses found</p>
                                <p class="text-sm">Create your first course to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $courses->links() }}
    </div>

    <!-- CREATE COURSE MODAL DIALOG -->
    <div id="course-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleCourseModal(false)">
        <div id="course-modal" class="max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-lg">Create New Course</h3>
                <button onclick="toggleCourseModal(false)" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.courses.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Course Title</label>
                    <input name="title" type="text" placeholder="e.g. Web Security Fundamentals" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Course Code</label>
                    <input name="code" type="text" placeholder="e.g. WS-101" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Briefly describe the course..." required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Level</label>
                        <select name="level" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Capacity</label>
                        <input name="capacity" type="number" min="1" max="1000" value="50" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Instructor</label>
                    <select name="instructor_id" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white" required>
                        @foreach($instructors ?? [] as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="toggleCourseModal(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Create Course</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleCourseModal(show) {
        const backdrop = document.getElementById('course-modal-backdrop');
        const modal = document.getElementById('course-modal');
        if (show) {
            backdrop.classList.remove('pointer-events-none', 'opacity-0');
            backdrop.classList.add('opacity-100');
            modal.classList.remove('scale-90');
            modal.classList.add('scale-100');
        } else {
            backdrop.classList.add('pointer-events-none', 'opacity-0');
            backdrop.classList.remove('opacity-100');
            modal.classList.remove('scale-100');
            modal.classList.add('scale-90');
        }
    }

    // Intercept form submission to show visual loading state
    document.querySelector('#course-modal form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating...
        `;

        const tbody = document.querySelector('tbody');
        if (tbody) {
            const emptyRow = tbody.querySelector('tr td[colspan]');
            if (emptyRow) {
                emptyRow.parentElement.remove();
            }

            const skeletonRow = document.createElement('tr');
            skeletonRow.className = 'hover:bg-slate-50/50 transition-colors opacity-70';
            skeletonRow.innerHTML = `
                <td class="px-6 py-4">
                    <div class="h-4 w-32 skeleton-loader rounded-md mb-1.5"></div>
                    <div class="h-3 w-16 skeleton-loader rounded-sm"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-4 w-24 skeleton-loader rounded-md"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-5 w-16 skeleton-loader rounded-md"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-4 w-8 skeleton-loader rounded-md"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-4 w-8 skeleton-loader rounded-md"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-5 w-16 skeleton-loader rounded-full"></div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-block h-7 w-12 skeleton-loader rounded-lg"></div>
                </td>
            `;
            tbody.insertBefore(skeletonRow, tbody.firstChild);
        }

        setTimeout(() => toggleCourseModal(false), 200);
    });
</script>
@endsection
