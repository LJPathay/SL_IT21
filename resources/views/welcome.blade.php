@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="pt-8 pb-8 md:pt-12 md:pb-12 lg:pt-16 lg:pb-16 overflow-hidden relative">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 -mr-40 -mt-40 w-96 h-96 rounded-full bg-blue-100 blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-40 -mb-40 w-96 h-96 rounded-full bg-indigo-100 blur-3xl opacity-50"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Text -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium mb-6 border border-blue-100">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                    Trusted Content from Top Security Experts
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-6xl font-bold tracking-tight text-slate-900 mb-6 lg:mb-8 leading-tight">
                    Build a Human Firewall with <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">SecureLearn</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-slate-600 mb-8 lg:mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Interactive, engaging, and comprehensive security awareness training designed to protect your institution from modern cyber threats like Phishing and Malware.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ url('/modules') }}" class="w-full sm:w-auto px-6 py-3.5 sm:px-8 sm:py-4 bg-blue-600 text-white rounded-xl font-medium text-base sm:text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2">
                        Browse Catalog
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#how-it-works" class="w-full sm:w-auto px-6 py-3.5 sm:px-8 sm:py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-medium text-base sm:text-lg hover:bg-slate-50 transition-all flex items-center justify-center">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Right Column: Decorative UI Element -->
            <div class="hidden lg:block relative">
                <!-- Main decorative card -->
                <div class="bg-white p-6 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 relative z-20 max-w-md ml-auto">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Security Score</div>
                                <div class="text-xs text-slate-500">Institution Average</div>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-green-500">98%</div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 w-[98%] rounded-full"></div>
                        </div>
                        <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex gap-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <div class="text-sm font-bold text-red-700">Phishing Simulation Blocked</div>
                                <div class="text-xs text-red-500 mt-1">12 users successfully reported the threat.</div>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200"></div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">John Doe</div>
                                    <div class="text-xs text-slate-500">Completed SQLi Module</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded-md">+50 pts</span>
                        </div>
                    </div>
                </div>

                <!-- Floating background elements -->
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full blur-3xl opacity-20 z-0"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full blur-2xl opacity-20 z-0"></div>
                
                <!-- Floating badge -->
                <div class="absolute -left-12 top-20 bg-white p-3 rounded-xl shadow-lg border border-slate-100 flex items-center gap-3 z-30 animate-bounce" style="animation-duration: 3s;">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="text-sm font-bold text-slate-800">ISO 27001 Ready</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-6 md:py-10 border-y border-slate-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs md:text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4 md:mb-6">Featuring Curated Content From Trusted Sources</p>
        <div class="flex flex-wrap justify-center items-center gap-6 md:gap-12 lg:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
            <!-- W3Schools Mock Logo text -->
            <div class="text-xl md:text-2xl font-black font-serif text-green-700">W3Schools</div>
            <div class="text-lg md:text-xl font-bold font-sans text-blue-800">OWASP</div>
            <div class="text-lg md:text-xl font-bold font-mono text-slate-800">NIST Guidelines</div>
            <div class="text-lg md:text-xl font-bold font-sans text-indigo-700">CompTIA</div>
        </div>
    </div>
</section>

<!-- How it Works -->
<section id="how-it-works" class="py-12 md:py-16 lg:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-14">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 md:mb-4">How SecureLearn Works</h2>
            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">A simple, effective flow to guarantee your team understands web security.</p>
        </div>
        
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 lg:gap-12 relative">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-blue-100 -z-10 transform -translate-y-1/2"></div>
            
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm relative text-center">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-600 text-white rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl font-bold mx-auto mb-4 md:mb-6 shadow-lg shadow-blue-200">1</div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2 md:mb-3">Browse & Enroll</h3>
                <p class="text-sm md:text-base text-slate-600">Explore our catalog of curated modules. Find exactly what your team needs to learn and click enroll.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm relative text-center">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-600 text-white rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl font-bold mx-auto mb-4 md:mb-6 shadow-lg shadow-blue-200">2</div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2 md:mb-3">Interactive Learning</h3>
                <p class="text-sm md:text-base text-slate-600">Take lessons pulled from trusted sources like W3Schools. Interact with real-world scenarios and code snippets.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm relative text-center sm:col-span-2 md:col-span-1">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-600 text-white rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl font-bold mx-auto mb-4 md:mb-6 shadow-lg shadow-blue-200">3</div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2 md:mb-3">Assess & Certify</h3>
                <p class="text-sm md:text-base text-slate-600">Pass the end-of-module quizzes to prove your knowledge. Earn verified certificates of completion.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Modules Preview -->
<section class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 md:mb-10 gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-2 md:mb-4">Popular Modules</h2>
                <p class="text-base sm:text-lg text-slate-600">Start learning with our most enrolled courses.</p>
            </div>
            <a href="{{ url('/modules') }}" class="hidden sm:flex items-center gap-2 text-blue-600 font-medium hover:text-blue-700 whitespace-nowrap">
                View All Modules
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- Course Card 1 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow group flex flex-col">
                <div class="h-40 md:h-48 bg-slate-200 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white font-serif text-2xl md:text-3xl font-black opacity-30">W3Schools</div>
                </div>
                <div class="p-5 md:p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">Web Security</span>
                        <span class="text-xs md:text-sm font-medium text-slate-500">2 Hours</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2">SQL Injection Prevention</h3>
                    <p class="text-slate-600 text-xs md:text-sm mb-5 md:mb-6 flex-1">Learn how attackers use SQL Injection to destroy your database and how to protect against it using parameterized queries.</p>
                    <a href="{{ url('/modules/1') }}" class="w-full py-2 md:py-2.5 border border-slate-300 text-center text-slate-700 text-sm md:text-base font-medium rounded-xl hover:bg-white hover:border-blue-600 hover:text-blue-600 transition-colors">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Course Card 2 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow group flex flex-col">
                <div class="h-40 md:h-48 bg-slate-200 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-600"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white text-2xl md:text-3xl font-bold opacity-30">Phishing</div>
                </div>
                <div class="p-5 md:p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">Critical</span>
                        <span class="text-xs md:text-sm font-medium text-slate-500">1 Hour</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2">Phishing Detection Awareness</h3>
                    <p class="text-slate-600 text-xs md:text-sm mb-5 md:mb-6 flex-1">Identify characteristics of phishing emails, fraudulent websites, and deceptive URLs before it's too late.</p>
                    <a href="{{ url('/modules/2') }}" class="w-full py-2 md:py-2.5 border border-slate-300 text-center text-slate-700 text-sm md:text-base font-medium rounded-xl hover:bg-white hover:border-blue-600 hover:text-blue-600 transition-colors">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Course Card 3 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow group flex flex-col sm:col-span-2 lg:col-span-1">
                <div class="h-40 md:h-48 bg-slate-200 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-purple-600"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white text-2xl md:text-3xl font-bold opacity-30">Auth</div>
                </div>
                <div class="p-5 md:p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg">Intermediate</span>
                        <span class="text-xs md:text-sm font-medium text-slate-500">3 Hours</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-2">Password Security Assessment</h3>
                    <p class="text-slate-600 text-xs md:text-sm mb-5 md:mb-6 flex-1">Detect weak password practices and educate users on strong password creation and multi-factor authentication.</p>
                    <a href="{{ url('/modules/3') }}" class="w-full py-2 md:py-2.5 border border-slate-300 text-center text-slate-700 text-sm md:text-base font-medium rounded-xl hover:bg-white hover:border-blue-600 hover:text-blue-600 transition-colors">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-6 sm:hidden text-center border-t border-slate-100 pt-6">
            <a href="{{ url('/modules') }}" class="inline-flex items-center gap-2 text-blue-600 font-medium w-full justify-center py-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                View All Modules
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</section>
@endsection
