<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'IPPRS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900">
        
        <!-- Header / Navigation -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/gok_logo.png') }}" alt="Government of Kerala Logo" class="h-10 w-auto object-contain">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">IPPRS</h1>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Kerala Health</p>
                    </div>
                </div>
                
                <nav class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-kerala-primary transition px-3 py-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-kerala-700 hover:text-kerala-900 transition px-3 py-2">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-kerala-primary hover:bg-kerala-700 px-5 py-2.5 rounded-md transition shadow-sm">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main>
            <div class="relative bg-kerala-primary overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-kerala-900 to-kerala-primary opacity-90"></div>
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 right-0 w-[40rem] h-[40rem] bg-white opacity-5 rounded-full blur-3xl translate-x-1/3 -translate-y-1/2"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 flex flex-col md:flex-row items-center">
                    <div class="md:w-3/5 text-left z-10">
                        <span class="inline-block py-1 px-3 rounded-full bg-kerala-800 text-kerala-100 text-xs font-bold tracking-widest uppercase mb-6 border border-kerala-600 shadow-sm">Official Portal</span>
                        <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight tracking-tight">
                            Integrated Pandemic <span class="text-kerala-200">Preparedness & Response</span> System
                        </h2>
                        <p class="text-xl text-kerala-50 mb-10 max-w-2xl leading-relaxed opacity-90">
                            A unified platform by the Department of Health, Kerala, designed to monitor, manage, and mitigate public health crises efficiently and effectively.
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ route('login') }}" class="bg-white text-kerala-primary hover:bg-gray-50 px-8 py-4 rounded-md font-bold transition shadow-lg flex items-center gap-2">
                                Access Dashboard
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <a href="#features" class="bg-kerala-800 bg-opacity-50 text-white border border-kerala-600 hover:bg-opacity-70 px-8 py-4 rounded-md font-semibold transition backdrop-blur-sm">
                                Learn More
                            </a>
                        </div>
                    </div>
                    <div class="md:w-2/5 mt-16 md:mt-0 z-10 hidden md:block">
                        <!-- Dashboard UI Mockup or Abstract Graphic -->
                        <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 p-4 rounded-xl shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                            <div class="flex gap-2 mb-4">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="h-8 bg-white bg-opacity-20 rounded w-1/2"></div>
                                <div class="h-32 bg-white bg-opacity-20 rounded w-full"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-24 bg-white bg-opacity-20 rounded"></div>
                                    <div class="h-24 bg-white bg-opacity-20 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Key Capabilities</h2>
                        <p class="text-lg text-gray-600">Our comprehensive system provides end-to-end solutions for pandemic management and healthcare resource allocation.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <!-- Feature 1 -->
                        <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300 group">
                            <div class="w-14 h-14 bg-kerala-100 text-kerala-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-kerala-primary group-hover:text-white transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Tracking</h3>
                            <p class="text-gray-600 leading-relaxed">Monitor outbreak metrics, hospital admissions, and resource availability with live-updating dashboards.</p>
                        </div>
                        
                        <!-- Feature 2 -->
                        <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300 group">
                            <div class="w-14 h-14 bg-kerala-100 text-kerala-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-kerala-primary group-hover:text-white transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Rapid Response</h3>
                            <p class="text-gray-600 leading-relaxed">Coordinate emergency medical teams and dispatch vital supplies instantly to high-priority zones.</p>
                        </div>
                        
                        <!-- Feature 3 -->
                        <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300 group">
                            <div class="w-14 h-14 bg-kerala-100 text-kerala-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-kerala-primary group-hover:text-white transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Resource Management</h3>
                            <p class="text-gray-600 leading-relaxed">Ensure optimal distribution of medical equipment, vaccines, and personnel across all healthcare facilities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                        <div class="bg-white p-1 rounded-full">
                            <img src="{{ asset('images/gok_logo.png') }}" alt="Government of Kerala Logo" class="h-6 w-6 object-contain">
                        </div>
                        <span class="text-xl font-bold text-white tracking-wider">IPPRS</span>
                    </div>
                    <p class="text-sm">Department of Health & Family Welfare<br>Government of Kerala</p>
                </div>
                <div class="text-sm">
                    <p>&copy; {{ date('Y') }} IPPRS. All rights reserved.</p>
                    <div class="mt-4 flex justify-center md:justify-end gap-4">
                        <a href="#" class="hover:text-white transition">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition">Terms of Service</a>
                        <a href="#" class="hover:text-white transition">Contact Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
