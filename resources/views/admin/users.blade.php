@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top header layout -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">User Administration</h2>
            <p class="text-slate-500 text-sm">Assign user roles, invite new students and instructors, and audit logs.</p>
        </div>
        <button onclick="toggleInviteModal(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Invite User
        </button>
    </div>

    <!-- Filter and table section -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Search and filters -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search user by name or email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>

            <div class="flex items-center gap-3">
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-655 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Roles</option>
                    <option value="admin">Administrators</option>
                    <option value="instructor">Instructors</option>
                    <option value="student">Students</option>
                </select>
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-655 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Suspended</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Full Name</th>
                        <th class="px-6 py-4">Email Address</th>
                        <th class="px-6 py-4">Current Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Registered Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    <!-- User 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 font-bold text-sm flex items-center justify-center">LP</div>
                                <div class="font-bold text-slate-900">Lebron James Pathay</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">lebron@example.com</td>
                        <td class="px-6 py-4">
                            <select onchange="alert('Role updated to ' + this.value)" class="text-xs bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1 rounded-md outline-none font-semibold focus:ring-1 focus:ring-blue-500">
                                <option value="Student" selected>Student</option>
                                <option value="Instructor">Instructor</option>
                                <option value="Administrator">Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">June 20, 2026</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="alert('Audit logs for Lebron James Pathay will be loaded.')" class="text-xs text-blue-600 hover:text-blue-800 font-bold">Audit Log</button>
                        </td>
                    </tr>

                    <!-- User 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-600 font-bold text-sm flex items-center justify-center">IU</div>
                                <div class="font-bold text-slate-900">Instructor User</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">instructor@example.com</td>
                        <td class="px-6 py-4">
                            <select onchange="alert('Role updated to ' + this.value)" class="text-xs bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1 rounded-md outline-none font-semibold focus:ring-1 focus:ring-blue-500">
                                <option value="Student">Student</option>
                                <option value="Instructor" selected>Instructor</option>
                                <option value="Administrator">Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">June 18, 2026</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold">Audit Log</button>
                        </td>
                    </tr>

                    <!-- User 3 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-red-100 text-red-600 font-bold text-sm flex items-center justify-center">AU</div>
                                <div class="font-bold text-slate-900">Admin User</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">admin@example.com</td>
                        <td class="px-6 py-4">
                            <select onchange="alert('Role updated to ' + this.value)" class="text-xs bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1 rounded-md outline-none font-semibold focus:ring-1 focus:ring-blue-500">
                                <option value="Student">Student</option>
                                <option value="Instructor">Instructor</option>
                                <option value="Administrator" selected>Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">June 15, 2026</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold">Audit Log</button>
                        </td>
                    </tr>

                    <!-- User 4 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold text-sm flex items-center justify-center">JS</div>
                                <div class="font-bold text-slate-900">Jane Smith</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">jane@example.com</td>
                        <td class="px-6 py-4">
                            <select class="text-xs bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1 rounded-md outline-none font-semibold">
                                <option value="Student" selected>Student</option>
                                <option value="Instructor">Instructor</option>
                                <option value="Administrator">Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">June 21, 2026</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold">Audit Log</button>
                        </td>
                    </tr>

                    <!-- User 5 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold text-sm flex items-center justify-center">MJ</div>
                                <div class="font-bold text-slate-900">Michael Johnson</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">michael@example.com</td>
                        <td class="px-6 py-4">
                            <select class="text-xs bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1 rounded-md outline-none font-semibold">
                                <option value="Student" selected>Student</option>
                                <option value="Instructor">Instructor</option>
                                <option value="Administrator">Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                Suspended
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">June 22, 2026</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold">Audit Log</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- INVITE USER MODAL DIALOG -->
    <div id="invite-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleInviteModal(false)">
        <div id="invite-modal" class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-lg">Invite New User</h3>
                <button onclick="toggleInviteModal(false)" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form class="p-6 space-y-4" onsubmit="event.preventDefault(); alert('Invitation email sent to ' + document.getElementById('invite_email').value); toggleInviteModal(false);">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <input id="invite_email" type="email" placeholder="e.g. employee@company.com" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Initial Designation Role</label>
                    <select class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                        <option value="student">Student / Employee</option>
                        <option value="instructor">Instructor / Security Lead</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    Once you send this invite, the user will receive a verification link with details to register and access their customized learning dashboards.
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="toggleInviteModal(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Send Invite</button>
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
