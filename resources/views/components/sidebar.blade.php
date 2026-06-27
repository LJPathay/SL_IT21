<aside id="app-sidebar" class="w-64 bg-white text-slate-900 flex flex-col fixed inset-y-0 left-0 z-50 -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-200 ease-in-out shrink-0 border-r border-slate-200">
    <div class="h-20 flex items-center justify-between px-6 font-bold text-xl tracking-tight border-b border-slate-200 shrink-0">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 rounded-lg p-3 shadow-sm">
                <img src="{{ asset('img/logo.png') }}" alt="SecureLearn Logo" class="w-14 h-14">
            </div>
        </div>
        <!-- Close button (mobile only) -->
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-slate-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
@php
    $segment = request()->segment(1);
    $subSegment = request()->segment(2);
    $user = auth()->user();
    
    // Build navigation items based on role
    $navItems = [];
    
    if ($user->isAdmin()) {
        $navItems = [
            ['title' => 'Dashboard', 'url' => url('/admin/dashboard'), 'icon' => 'dashboard', 'active' => $segment === 'admin' && ($subSegment === 'dashboard' || empty($subSegment))],
            ['title' => 'Manage Modules', 'url' => url('/admin/modules'), 'icon' => 'modules', 'active' => $segment === 'admin' && $subSegment === 'modules'],
            ['title' => 'Manage Courses', 'url' => url('/admin/courses'), 'icon' => 'courses', 'active' => $segment === 'admin' && $subSegment === 'courses'],
            ['title' => 'Quizzes', 'url' => url('/admin/quizzes'), 'icon' => 'quizzes', 'active' => $segment === 'admin' && $subSegment === 'quizzes'],
            ['title' => 'Users', 'url' => url('/admin/users'), 'icon' => 'users', 'active' => $segment === 'admin' && $subSegment === 'users'],
            ['title' => 'Reports', 'url' => url('/admin/reports'), 'icon' => 'reports', 'active' => $segment === 'admin' && $subSegment === 'reports'],
            ['title' => 'Audit Logs', 'url' => url('/admin/audit-logs'), 'icon' => 'reports', 'active' => $segment === 'admin' && $subSegment === 'audit-logs'],
            ['title' => 'Security Logs', 'url' => url('/admin/security-logs'), 'icon' => 'reports', 'active' => $segment === 'admin' && $subSegment === 'security-logs'],
            ['title' => 'My Profile', 'url' => url('/profile'), 'icon' => 'users', 'active' => $segment === 'profile'],
        ];
    } elseif ($user->isInstructor()) {
        $navItems = [
            ['title' => 'Dashboard', 'url' => url('/instructor/dashboard'), 'icon' => 'dashboard', 'active' => $segment === 'instructor' && ($subSegment === 'dashboard' || empty($subSegment))],
            ['title' => 'My Courses', 'url' => url('/instructor/courses'), 'icon' => 'courses', 'active' => $segment === 'instructor' && $subSegment === 'courses'],
            ['title' => 'My Students', 'url' => url('/instructor/students'), 'icon' => 'students', 'active' => $segment === 'instructor' && $subSegment === 'students'],
            ['title' => 'Assessment Results', 'url' => url('/instructor/assessments'), 'icon' => 'assessments', 'active' => $segment === 'instructor' && $subSegment === 'assessments'],
            ['title' => 'My Profile', 'url' => url('/profile'), 'icon' => 'users', 'active' => $segment === 'profile'],
        ];
    } else { // Student
        $navItems = [
            ['title' => 'Dashboard', 'url' => url('/student/dashboard'), 'icon' => 'dashboard', 'active' => $segment === 'student' && ($subSegment === 'dashboard' || empty($subSegment))],
            ['title' => 'Learning Modules', 'url' => url('/modules'), 'icon' => 'modules', 'active' => $segment === 'modules'],
            ['title' => 'My Courses', 'url' => url('/student/courses'), 'icon' => 'courses', 'active' => $segment === 'student' && $subSegment === 'courses'],
            ['title' => 'Quizzes & Exams', 'url' => url('/student/quizzes'), 'icon' => 'quizzes', 'active' => $segment === 'student' && $subSegment === 'quizzes'],
            ['title' => 'My Certificates', 'url' => url('/student/certificates'), 'icon' => 'certificates', 'active' => $segment === 'student' && $subSegment === 'certificates'],
            ['title' => 'My Profile', 'url' => url('/profile'), 'icon' => 'users', 'active' => $segment === 'profile'],
        ];
    }
@endphp

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @foreach($navItems as $item)
            @php
                $activeClass = $item['active'] 
                    ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' 
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium';
            @endphp
            <a href="{{ $item['url'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ $activeClass }}">
                @if($item['icon'] === 'dashboard')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                @elseif($item['icon'] === 'modules')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                @elseif($item['icon'] === 'quizzes')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                @elseif($item['icon'] === 'users')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                @elseif($item['icon'] === 'leaderboard')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.969 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 9.42c-.783-.57-.38-1.81.588-1.81h4.906a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                @elseif($item['icon'] === 'reports')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                @elseif($item['icon'] === 'students')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                @elseif($item['icon'] === 'assessments')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                @elseif($item['icon'] === 'courses')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                @elseif($item['icon'] === 'certificates')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                @endif
                {{ $item['title'] }}
            </a>
        @endforeach
        {{ $slot }}
    </nav>

    <div class="p-4 border-t border-slate-200 shrink-0">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-semibold shrink-0 text-white">
                {{ substr($name ?? 'U', 0, 1) }}
            </div>
            <div class="min-w-0">
                <div class="text-sm font-medium truncate text-slate-900">{{ $name ?? 'User' }}</div>
                <div class="text-xs text-slate-500 truncate">{{ ucfirst($user->role ?? 'User') }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full text-center py-2 px-3 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors font-medium">
                Sign Out
            </button>
        </form>
    </div>
</aside>
