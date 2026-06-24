@extends('layouts.app')

@section('title', 'My Students')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header info -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Student Roster</h2>
            <p class="text-slate-500 text-sm">Monitor enrolled students, track lesson completion rates, and send direct reminders.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-3 py-2 rounded-xl">Cohorts assigned: 2</span>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl">Total Students: 142</span>
        </div>
    </div>

    <!-- Roster table with filters -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Row -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search student name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-250 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            
            <div class="flex items-center gap-3">
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-655 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Progress Levels</option>
                    <option value="completed">Completed (100%)</option>
                    <option value="in-progress">In Progress</option>
                    <option value="struggling">Struggling (<70% Score)</option>
                </select>
                <button onclick="alert('Exporting student data...')" class="bg-white border border-slate-250 hover:bg-slate-50 text-slate-655 text-sm px-3.5 py-2.5 rounded-xl font-semibold flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Student Details</th>
                        <th class="px-6 py-4">Enrolled Course</th>
                        <th class="px-6 py-4">Course Progress</th>
                        <th class="px-6 py-4">Avg Quiz Mark</th>
                        <th class="px-6 py-4">Last Logged In</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    <!-- Student 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Lebron James Pathay</div>
                            <div class="text-xs text-slate-500 mt-0.5">lebron@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-medium">Phishing Detection Awareness</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-slate-100 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 40%"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600">40%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">92%</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">2 minutes ago</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="toggleMessageModal('lebron@example.com')" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold px-3 py-1.5 rounded-lg transition-colors">Message</button>
                                <button onclick="alert('Nudge notification sent to student!')" class="text-xs border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold px-3 py-1.5 rounded-lg transition-colors">Nudge</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Student 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Jane Smith</div>
                            <div class="text-xs text-slate-500 mt-0.5">jane@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-medium">Password Security Assessment</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-slate-100 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600">100%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">88%</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">1 hour ago</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="toggleMessageModal('jane@example.com')" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold px-3 py-1.5 rounded-lg transition-colors">Message</button>
                                <button class="text-xs text-slate-350 cursor-not-allowed border border-slate-100 px-3 py-1.5 rounded-lg font-semibold" disabled>Completed</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Student 3 -->
                    <tr class="hover:bg-slate-50/50 transition-colors bg-red-50/10">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Michael Johnson</div>
                            <div class="text-xs text-slate-500 mt-0.5">michael@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-medium">SQL Injection Prevention</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-slate-100 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 15%"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600">15%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-red-600">54%</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">3 days ago</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="toggleMessageModal('michael@example.com')" class="text-xs bg-blue-550 text-blue-700 bg-blue-50 hover:bg-blue-100 font-bold px-3 py-1.5 rounded-lg transition-colors">Message</button>
                                <button onclick="alert('Warning notification sent to struggling student.')" class="text-xs bg-red-50 text-red-755 hover:bg-red-100 border border-red-200 font-bold px-3 py-1.5 rounded-lg transition-colors">Alert</button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- DIRECT MESSAGE MODAL -->
    <div id="message-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleMessageModal(null)">
        <div id="message-modal" class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-lg">Send Student Message</h3>
                <button onclick="toggleMessageModal(null)" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form class="p-6 space-y-4" onsubmit="event.preventDefault(); alert('Message successfully sent!'); toggleMessageModal(null);">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">To Student</label>
                    <input id="recipient_email" type="text" readonly class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Subject</label>
                    <input type="text" value="Course Progress Update" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Message Content</label>
                    <textarea rows="4" required placeholder="Write your message here..." class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm"></textarea>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="toggleMessageModal(null)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Send Message</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleMessageModal(email) {
        const backdrop = document.getElementById('message-modal-backdrop');
        const modal = document.getElementById('message-modal');
        const input = document.getElementById('recipient_email');
        if (email) {
            input.value = email;
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
