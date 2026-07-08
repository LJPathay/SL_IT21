<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecureLearn - @yield('title', 'Security Awareness Training')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-800 selection:bg-blue-500 selection:text-white flex flex-col min-h-screen">

    <!-- Public Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('img/logo_nasad.png') }}" alt="SecureLearn Logo" class="h-28 w-auto">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/#how-it-works') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">How it Works</a>
                    <a href="{{ url('/modules') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Modules</a>
                    <a href="{{ url('/login') }}" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">Log in</a>
                    <a href="{{ url('/login') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm shadow-blue-200">Dashboard</a>
                </div>

                <!-- Mobile Nav Toggle -->
                <div class="flex md:hidden items-center gap-3">
                    <a href="{{ url('/login') }}" class="text-slate-600 hover:text-slate-900 font-medium text-sm transition-colors">Log in</a>
                    <button id="mobile-nav-btn" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-slate-600 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ url('/#how-it-works') }}" class="block text-slate-600 hover:text-blue-600 font-medium transition-colors py-2">How it Works</a>
                <a href="{{ url('/modules') }}" class="block text-slate-600 hover:text-blue-600 font-medium transition-colors py-2">Modules</a>
                <a href="{{ url('/login') }}" class="block w-full text-center bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors">Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 md:py-12 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
            <div class="flex items-center gap-2 mb-4 md:mb-6 text-white">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span class="font-bold text-lg md:text-xl tracking-tight">SecureLearn</span>
            </div>
            <p class="text-sm">&copy; {{ date('Y') }} SecureLearn Platform. Information Assurance and Security 2 Project.</p>
            <p class="mt-2 text-xs md:text-sm">Developed by Lebron James Pathay</p>
        </div>
    </footer>

    @stack('scripts')

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
