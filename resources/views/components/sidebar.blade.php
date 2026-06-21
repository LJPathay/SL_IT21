<aside id="app-sidebar" class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-200 ease-in-out shrink-0">
    <div class="h-16 flex items-center justify-between px-6 font-bold text-xl tracking-tight border-b border-slate-800 shrink-0">
        <div class="flex items-center gap-2">
            <span class="text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
            SecureLearn
        </div>
        <!-- Close button (mobile only) -->
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        {{ $slot }}
    </nav>

    <div class="p-4 border-t border-slate-800 shrink-0">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-sm font-semibold shrink-0">
                {{ $initial ?? 'U' }}
            </div>
            <div class="min-w-0">
                <div class="text-sm font-medium truncate">{{ $name ?? 'User' }}</div>
                <div class="text-xs text-slate-400 truncate">{{ $role ?? 'Role' }}</div>
            </div>
        </div>
        <a href="{{ url('/') }}" class="block w-full text-center py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
            Sign Out
        </a>
    </div>
</aside>
