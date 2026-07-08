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

    <!-- Animation CSS styles -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fade-out-down {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(1rem);
            }
        }
        @keyframes skeleton-shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-out-down {
            animation: fade-out-down 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .skeleton-loader {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite linear;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-900 flex relative">

    <!-- Toast Notification Wrapper -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-55 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        @if(session('success'))
            <div class="toast-alert flex items-start gap-3 bg-white border-l-4 border-green-500 shadow-xl rounded-xl p-4 animate-fade-in-up pointer-events-auto" role="alert">
                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-900 text-sm">Success</h4>
                    <p class="text-slate-650 text-xs mt-0.5">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-alert flex items-start gap-3 bg-white border-l-4 border-red-500 shadow-xl rounded-xl p-4 animate-fade-in-up pointer-events-auto" role="alert">
                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-900 text-sm">Error</h4>
                    <p class="text-slate-650 text-xs mt-0.5">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Script to automatically dismiss notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.toast-alert');
            alerts.forEach((alert) => {
                setTimeout(() => {
                    alert.classList.add('animate-fade-out-down');
                    alert.addEventListener('animationend', () => alert.remove());
                }, 5000);
            });
        });
        
        function showToast(title, message, type = 'success') {
            const container = document.getElementById('toast-container');
            const alertElement = document.createElement('div');
            alertElement.className = `toast-alert flex items-start gap-3 bg-white border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'} shadow-xl rounded-xl p-4 animate-fade-in-up pointer-events-auto`;
            
            const iconBg = type === 'success' ? 'bg-green-100' : 'bg-red-100';
            const iconColor = type === 'success' ? 'text-green-600' : 'text-red-600';
            const svgIcon = type === 'success' 
                ? `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                : `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            
            alertElement.innerHTML = `
                <div class="w-6 h-6 rounded-full ${iconBg} flex items-center justify-center shrink-0">
                    ${svgIcon}
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-900 text-sm">${title}</h4>
                    <p class="text-slate-650 text-xs mt-0.5">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            
            container.appendChild(alertElement);
            setTimeout(() => {
                alertElement.classList.add('animate-fade-out-down');
                alertElement.addEventListener('animationend', () => alertElement.remove());
            }, 5000);
        }
    </script>

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

    <!-- Security: Disable inspection tools (deterrent only, not foolproof) -->
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.shiftKey && e.key === 'J') ||
                (e.ctrlKey && e.key === 'U') ||
                (e.ctrlKey && e.shiftKey && e.key === 'C')) {
                e.preventDefault();
            }
        });

        // Detect devtools open
        let devtools = { open: false };
        setInterval(function() {
            const threshold = 160;
            if (window.outerHeight - window.innerHeight > threshold ||
                window.outerWidth - window.innerWidth > threshold) {
                if (!devtools.open) {
                    devtools.open = true;
                    console.clear();
                    console.log('%cSecurity Notice', 'color: red; font-size: 20px; font-weight: bold;');
                    console.log('%cThis application is protected. Unauthorized access is monitored.', 'color: red; font-size: 14px;');
                }
            } else {
                devtools.open = false;
            }
        }, 500);
    </script>

</body>
</html>
