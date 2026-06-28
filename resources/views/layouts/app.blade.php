@php
    $segment = request()->segment(1);
    $subSegment = request()->segment(2);

    // Use authenticated user's role instead of URL segment for accuracy
    if (auth()->check()) {
        $authUser = auth()->user();
        if ($authUser->isAdmin()) {
            $user_role = 'Administrator';
            $user_name = $authUser->name ?? 'Admin User';
            $user_initial = strtoupper(substr($user_name, 0, 1));
        } elseif ($authUser->isInstructor()) {
            $user_role = 'Instructor';
            $user_name = $authUser->name ?? 'Instructor User';
            $user_initial = strtoupper(substr($user_name, 0, 1));
        } else {
            $user_role = 'Student';
            $user_name = $authUser->name ?? 'Student User';
            $user_initial = strtoupper(substr($user_name, 0, 1));
        }
    } else {
        // Fallback for non-authenticated users
        if ($segment === 'admin') {
            $user_role = 'Administrator';
            $user_name = 'Admin User';
            $user_initial = 'A';
        } elseif ($segment === 'instructor') {
            $user_role = 'Instructor';
            $user_name = 'Instructor User';
            $user_initial = 'I';
        } else {
            $user_role = 'Student';
            $user_name = 'Student User';
            $user_initial = 'S';
        }
    }

    // Automatically set header title based on current segment/action if not overridden
    if ($segment === 'admin') {
        $header_title = match($subSegment) {
            'dashboard' => 'Administrator Dashboard',
            'modules' => 'Manage Modules',
            'quizzes' => 'Quizzes & Assessments',
            'users' => 'User Management',
            'phishing' => 'Phishing Simulation Campaigns',
            'reports' => 'System Reports',
            default => 'Admin Control Panel'
        };
    } elseif ($segment === 'instructor') {
        $header_title = match($subSegment) {
            'dashboard' => 'Instructor Dashboard',
            'students' => 'Student Progress Tracking',
            'assessments' => 'Assessment Performance',
            default => 'Instructor Portal'
        };
    } elseif ($segment === 'modules') {
        $header_title = match($subSegment) {
            default => 'Learning Modules'
        };
    } else { // student
        $header_title = match($subSegment) {
            'dashboard' => 'Student Dashboard',
            'courses' => 'My Enrolled Courses',
            'inbox' => 'Phishing Inbox Simulator',
            'leaderboard' => 'Leaderboard & Rankings',
            'quizzes' => 'Quizzes & Exams',
            'certificates' => 'My Achievements',
            default => 'Student Dashboard'
        };
    }

    $initial = trim(View::yieldContent('user_initial')) ?: $user_initial;
    $name = trim(View::yieldContent('user_name')) ?: $user_name;
    $role = trim(View::yieldContent('user_role')) ?: $user_role;
    $title = trim(View::yieldContent('header_title')) ?: $header_title;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecureLearn - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased text-slate-900 flex">

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar Component -->
    <x-sidebar :initial="$initial" :name="$name" :role="$role">
        @yield('sidebar')
    </x-sidebar>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden w-full">
        
        <!-- Header Component -->
        <x-header :title="$title" />

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-auto bg-slate-50 p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

</body>
</html>
