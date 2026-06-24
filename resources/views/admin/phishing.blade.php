@extends('layouts.app')

@section('title', 'Phishing Simulator')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header layout -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Phishing Simulations</h2>
            <p class="text-slate-500 text-sm">Create and monitor mock phishing tests to train employees to detect deceptive emails.</p>
        </div>
        <button onclick="toggleCampaignDrawer(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Launch Campaign
        </button>
    </div>

    <!-- Stats row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-450 text-xs font-bold uppercase tracking-wider">Active Campaigns</div>
            <div class="text-2xl font-bold text-slate-900 mt-1">1 Active</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-455 text-xs font-bold uppercase tracking-wider">Total Mock Emails Sent</div>
            <div class="text-2xl font-bold text-blue-600 mt-1">1,482</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-455 text-xs font-bold uppercase tracking-wider">Avg Click Rate (Bait Taken)</div>
            <div class="text-2xl font-bold text-red-500 mt-1">4.2%</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-455 text-xs font-bold uppercase tracking-wider">Avg Reported Rate</div>
            <div class="text-2xl font-bold text-green-600 mt-1">82.1%</div>
        </div>
    </div>

    <!-- Active campaigns table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="font-bold text-slate-800 text-base">Campaign Log</h3>
            <div class="flex items-center gap-3">
                <select class="bg-white border border-slate-250 rounded-xl px-3 py-2 text-sm text-slate-655 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Campaigns</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-150 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Campaign Name & Template</th>
                        <th class="px-6 py-4">Audience Size</th>
                        <th class="px-6 py-4 text-center">Bait Clicked</th>
                        <th class="px-6 py-4 text-center">Reported Safe</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    
                    <!-- Campaign 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Urgent System Password Expiry</div>
                            <div class="text-xs text-slate-450 mt-0.5">Template: Microsoft Login Spoof</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">1,248 users</td>
                        <td class="px-6 py-4 text-center text-red-600">
                            <div>3.1%</div>
                            <div class="text-[10px] text-slate-400 font-bold">(39 users)</div>
                        </td>
                        <td class="px-6 py-4 text-center text-green-600">
                            <div>85.4%</div>
                            <div class="text-[10px] text-slate-400 font-bold">(1,065 users)</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="alert('Viewing campaign audit logs.')" class="text-xs text-blue-600 hover:text-blue-800 font-bold">Details</button>
                        </td>
                    </tr>

                    <!-- Campaign 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Unclaimed Package Notification</div>
                            <div class="text-xs text-slate-450 mt-0.5">Template: DHL Shipping Notification</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">234 users</td>
                        <td class="px-6 py-4 text-center text-red-600">
                            <div>9.8%</div>
                            <div class="text-[10px] text-slate-400 font-bold">(23 users)</div>
                        </td>
                        <td class="px-6 py-4 text-center text-green-600">
                            <div>68.8%</div>
                            <div class="text-[10px] text-slate-400 font-bold">(161 users)</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-450"></span>
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold">Details</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- CAMPAIGN DRAWER DIALOG -->
    <div id="campaign-drawer-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleCampaignDrawer(false)">
        <div id="campaign-drawer" class="absolute inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Launch Phishing Simulation</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Deploy a mock test template to your student pool.</p>
                </div>
                <button onclick="toggleCampaignDrawer(false)" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form class="flex-1 overflow-y-auto p-6 space-y-5" onsubmit="event.preventDefault(); alert('Campaign deployed successfully!'); toggleCampaignDrawer(false);">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Campaign Title</label>
                    <input type="text" placeholder="e.g. End of Month Payroll Audit" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Phish Bait Template</label>
                    <select class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                        <option value="mso">Microsoft Urgent Login Security Alert</option>
                        <option value="ship">DHL Package Delivery Unclaimed</option>
                        <option value="net">Netflix Account Membership Renewal Renewal</option>
                        <option value="pay">Direct Deposit Details Update Request</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Target Audience Cohort</label>
                    <select class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                        <option value="all">All Enrolled Students (1,248 users)</option>
                        <option value="cs">Computer Science Department (312 users)</option>
                        <option value="staff">Administrative Staff (86 users)</option>
                    </select>
                </div>

                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-xs text-amber-800 flex gap-2">
                    <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span><strong>Caution:</strong> Simulated phishing emails look exactly like actual threats. Instructors will be notified to monitor student reports.</span>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleCampaignDrawer(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Deploy Simulation</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleCampaignDrawer(show) {
        const backdrop = document.getElementById('campaign-drawer-backdrop');
        const drawer = document.getElementById('campaign-drawer');
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
