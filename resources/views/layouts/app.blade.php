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
    <x-sidebar :initial="$user_initial ?? 'U'" :name="$user_name ?? 'User'" :role="$user_role ?? 'Role'">
        @yield('sidebar')
    </x-sidebar>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden w-full">
        
        <!-- Header Component -->
        <x-header :title="$header_title ?? 'Dashboard'" />

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
