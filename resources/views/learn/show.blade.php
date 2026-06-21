<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecureLearn - SQL Injection Prevention</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <!-- Monospace Font for code -->
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased text-slate-900 flex flex-col">

    <!-- Top Navbar -->
    <header class="h-14 bg-slate-900 text-white flex items-center justify-between px-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ url('/student/dashboard') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="font-semibold text-sm border-l border-slate-700 pl-4">Web Security: SQL Injection Prevention</div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-xs text-slate-400">Progress: 1/4</div>
            <div class="w-24 bg-slate-700 rounded-full h-1.5">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: 25%"></div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Course Sidebar -->
        <aside class="w-64 bg-slate-50 border-r border-slate-200 flex flex-col hidden md:flex shrink-0">
            <div class="p-4 font-semibold text-slate-800 border-b border-slate-200">
                Course Contents
            </div>
            <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                <a href="#" class="block px-3 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg text-sm">
                    1. Introduction to SQLi
                </a>
                <a href="#" class="block px-3 py-2 text-slate-600 hover:bg-slate-100 font-medium rounded-lg text-sm">
                    2. The "1=1" Attack Vector
                </a>
                <a href="#" class="block px-3 py-2 text-slate-600 hover:bg-slate-100 font-medium rounded-lg text-sm">
                    3. Defending with Prepared Statements
                </a>
                <a href="#" class="block px-3 py-2 text-slate-600 hover:bg-slate-100 font-medium rounded-lg text-sm flex items-center justify-between mt-4 border border-slate-200">
                    <span>End of Module Assessment</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </a>
            </nav>
        </aside>

        <!-- Main Learning Content -->
        <main class="flex-1 overflow-y-auto bg-white p-6 md:p-12 lg:px-24">
            
            <div class="max-w-3xl mx-auto">
                <div class="mb-2 text-sm font-semibold text-green-600 uppercase tracking-wider">Lesson 1</div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Introduction to SQL Injection</h1>

                <div class="prose prose-slate max-w-none">
                    <p class="text-lg text-slate-700 mb-6">
                        SQL injection (SQLi) is one of the most common and dangerous web hacking techniques. It occurs when a malicious user can insert or "inject" a SQL query via the input data from the client to the application.
                    </p>

                    <h2 class="text-2xl font-bold text-slate-900 mt-10 mb-4">What is SQL Injection?</h2>
                    <p class="text-slate-700 mb-4">
                        A successful SQL injection exploit can read sensitive data from the database, modify database data (Insert/Update/Delete), execute administration operations on the database, and sometimes issue commands to the operating system.
                    </p>

                    <div class="bg-[#f1f1f1] border-l-4 border-[#04AA6D] p-6 rounded-r-lg my-8 relative">
                        <div class="absolute top-0 right-0 bg-[#04AA6D] text-white text-xs font-bold px-2 py-1 rounded-bl-lg">W3Schools Example</div>
                        <h3 class="text-lg font-bold mb-2">Example: A Vulnerable Query</h3>
                        <p class="mb-4 text-sm text-slate-600">Consider the following code snippet that takes a user ID from an input field:</p>
                        
                        <div class="bg-white border border-slate-300 rounded p-4 overflow-x-auto font-[fira-code] text-sm shadow-sm">
                            <span class="text-blue-600">txtUserId</span> = getRequestString(<span class="text-green-600">"UserId"</span>);<br>
                            <span class="text-blue-600">txtSQL</span> = <span class="text-green-600">"SELECT * FROM Users WHERE UserId = "</span> + txtUserId;
                        </div>
                    </div>

                    <p class="text-slate-700 mb-4">
                        If there is nothing to prevent a user from entering "wrong" input, the user can enter some "smart" input like this:
                    </p>

                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto font-[fira-code] text-sm shadow-sm text-slate-300 mb-8">
                        User Input: <span class="text-red-400">105 OR 1=1</span>
                    </div>

                    <p class="text-slate-700 mb-4">
                        Then, the SQL statement will look like this:
                    </p>

                    <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto font-[fira-code] text-sm shadow-sm text-slate-300 mb-8">
                        <span class="text-purple-400">SELECT</span> * <span class="text-purple-400">FROM</span> Users <span class="text-purple-400">WHERE</span> UserId = <span class="text-green-400">105</span> <span class="text-red-400">OR 1=1</span>;
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mt-10 mb-4">Why is this dangerous?</h2>
                    <p class="text-slate-700 mb-4">
                        Since <code class="bg-slate-100 text-red-600 px-1 py-0.5 rounded text-sm">1=1</code> is always true, the query will return <strong>ALL</strong> rows from the "Users" table, potentially bypassing authentication and exposing all user data.
                    </p>

                </div>

                <!-- Footer Navigation -->
                <div class="mt-16 pt-8 border-t border-slate-200 flex items-center justify-between">
                    <button class="px-5 py-2.5 text-slate-500 font-medium hover:bg-slate-100 rounded-lg transition-colors cursor-not-allowed opacity-50">
                        Previous Lesson
                    </button>
                    <a href="#" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        Next Lesson: The "1=1" Attack Vector
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
