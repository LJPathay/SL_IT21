@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-slate-500 mb-1">Total Users</div>
                <div class="text-2xl font-bold text-slate-900">{{ $totalUsers ?? 0 }}</div>
                <div class="text-xs text-slate-500">{{ $activeUsers ?? 0 }} active</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-slate-500 mb-1">Active Modules</div>
                <div class="text-2xl font-bold text-slate-900">{{ $totalModules ?? 0 }}</div>
                <div class="text-xs text-slate-500">{{ $totalCourses ?? 0 }} courses</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-slate-500 mb-1">Total Enrollments</div>
                <div class="text-2xl font-bold text-slate-900">{{ $totalEnrollments ?? 0 }}</div>
                <div class="text-xs text-green-600 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Active learning
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-slate-500 mb-1">Today's Logins</div>
                <div class="text-2xl font-bold text-slate-900">{{ $todayLogins ?? 0 }}</div>
                <div class="text-xs text-slate-500">Unique users</div>
            </div>
        </div>
    </div>

    <!-- User Distribution -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-2">Administrators</div>
            <div class="text-3xl font-bold text-slate-900">{{ $admins ?? 0 }}</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalUsers > 0 ? round(($admins / $totalUsers) * 100) : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-2">Instructors</div>
            <div class="text-3xl font-bold text-slate-900">{{ $instructors ?? 0 }}</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalUsers > 0 ? round(($instructors / $totalUsers) * 100) : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-sm font-medium text-slate-500 mb-2">Students</div>
            <div class="text-3xl font-bold text-slate-900">{{ $students ?? 0 }}</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalUsers > 0 ? round(($students / $totalUsers) * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Recent Audit Activity</h3>
                <a href="{{ route('admin.audit-logs') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @if($recentAuditLogs && $recentAuditLogs->count() > 0)
                    @foreach($recentAuditLogs->take(5) as $log)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $log->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400">{{ $log->created_at?->diffForHumans() ?? 'Recently' }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="p-4 text-center text-slate-500 text-sm">No recent audit activity</div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.modules.create') }}" class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create New Module
                </a>
                <button onclick="toggleInviteModal(true)" class="w-full text-left px-4 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Add New User
                </button>
                <a href="{{ route('admin.phishing') }}" class="w-full text-left px-4 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Phishing Campaigns
                </a>
                <a href="{{ route('admin.reports') }}" class="w-full text-left px-4 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generate Report
                </a>
            </div>
        </div>
    </div>

    <!-- Security Alerts -->
    @if($criticalEvents && $criticalEvents->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-red-800">Critical Security Events</h3>
                <p class="text-sm text-red-600">{{ $criticalEvents->count() }} critical events require attention</p>
            </div>
        </div>
        <div class="divide-y divide-red-100">
            @foreach($criticalEvents->take(3) as $event)
            <div class="py-3 flex items-center justify-between">
                <div class="text-sm text-red-800">{{ $event->description ?? 'Security event detected' }}</div>
                <div class="text-xs text-red-600">{{ $event->occurred_at?->diffForHumans() ?? 'Recently' }}</div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('admin.security-logs') }}" class="mt-4 inline-block text-sm text-red-700 hover:text-red-800 font-medium">View all security logs</a>
    </div>
    @endif

    <!-- INVITE USER MODAL DIALOG -->
    <div id="invite-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleInviteModal(false)">
        <div id="invite-modal" class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-lg">Invite New User</h3>
                <button onclick="toggleInviteModal(false)" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                    <input name="name" type="text" placeholder="e.g. John Doe" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <input name="email" type="email" placeholder="e.g. employee@company.com" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input name="password" type="password" placeholder="Minimum 8 characters" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                    <select name="role" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                        <option value="student">Student / Employee</option>
                        <option value="instructor">Instructor / Security Lead</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="toggleInviteModal(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Create User</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleInviteModal(show) {
        const backdrop = document.getElementById('invite-modal-backdrop');
        const modal = document.getElementById('invite-modal');
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
</script>
@endsection
