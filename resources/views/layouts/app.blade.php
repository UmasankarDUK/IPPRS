<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IPPRS') }} — Integrated Public Health Response System</title>
    <meta name="description" content="Government of Kerala — Integrated Public Health Preparedness & Response System">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        :root {
            --kerala-green:   #006B4F;
            --kerala-green-l: #00875F;
            --kerala-teal:    #0891B2;
            --kerala-gold:    #D97706;
            --kerala-bg:      #F0F7F4;
            --sidebar-w:      240px;
        }

        body { background: var(--kerala-bg); }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            transition: width 0.3s cubic-bezier(.4,0,.2,1);
            background: #fff;
            border-right: 1px solid #E2EDE9;
            box-shadow: 4px 0 20px rgba(0,107,79,0.06);
        }
        #sidebar.collapsed { width: 64px; }

        /* Nav links */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            transition: all 0.15s ease; cursor: pointer;
            text-decoration: none; position: relative;
        }
        .nav-link:hover { background: #F0FAF6; }
        .nav-link.active {
            background: linear-gradient(135deg, #E8F5F0 0%, #D1EDE4 100%);
            color: var(--kerala-green);
        }
        .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 8px; bottom: 8px;
            width: 3px; border-radius: 0 3px 3px 0;
            background: var(--kerala-green);
        }
        .nav-link .icon { flex-shrink: 0; width: 18px; height: 18px; }
        .nav-link.active .icon { color: var(--kerala-green); }
        .nav-link:not(.active) .icon { color: #6B7280; }
        .nav-link .label { font-size: 12.5px; font-weight: 600; white-space: nowrap; overflow: hidden; }
        .nav-link.active .label { color: var(--kerala-green); }
        .nav-link:not(.active) .label { color: #374151; }

        /* Section labels */
        .nav-section { font-size: 9px; font-weight: 800; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.12em; padding: 0 12px; margin: 14px 0 6px; font-family: 'JetBrains Mono', monospace; }

        /* Top bar */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #E2EDE9;
            box-shadow: 0 1px 8px rgba(0,107,79,0.05);
        }

        /* Kerala pattern stripe */
        .kerala-stripe {
            background: linear-gradient(90deg,
                #006B4F 0%, #00875F 25%,
                #0891B2 50%, #D97706 75%, #006B4F 100%);
            height: 3px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #F0F7F4; }
        ::-webkit-scrollbar-thumb { background: #C7DDD5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #96BFB1; }

        /* Pulse */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.3); }
        }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Page fade in */
        main { animation: fadeIn 0.18s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(3px); } to { opacity: 1; transform: translateY(0); } }

        /* Badge */
        .live-badge {
            background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
            border: 1px solid #FCD34D;
            color: #92400E;
        }

        /* Avatar gradient */
        .avatar-grad { background: linear-gradient(135deg, var(--kerala-green), var(--kerala-teal)); }

        /* Active indicator dot */
        .active-dot { background: #10B981; }

        /* Hover card subtle */
        .topbar-btn {
            padding: 7px; border-radius: 8px; color: #6B7280;
            transition: all 0.15s; border: 1px solid transparent;
        }
        .topbar-btn:hover { background: #F0FAF6; color: var(--kerala-green); border-color: #D1EDE4; }

        /* User dropdown */
        .user-dropdown {
            background: #fff;
            border: 1px solid #E2EDE9;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,107,79,0.12), 0 2px 8px rgba(0,0,0,0.06);
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 16px; font-size: 12px; font-weight: 600;
            color: #374151; transition: background 0.12s; text-decoration: none;
        }
        .dropdown-item:hover { background: #F0FAF6; color: var(--kerala-green); }
        .dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }

        /* Brand header */
        .brand-name { font-size: 13px; font-weight: 800; color: var(--kerala-green); letter-spacing: -0.3px; }
        .brand-sub { font-size: 8.5px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.1em; font-family: 'JetBrains Mono', monospace; }

        /* Collapse button */
        .collapse-btn {
            width: 100%; display: flex; align-items: center; justify-content: center;
            padding: 8px; border-radius: 8px; color: #9CA3AF;
            transition: all 0.15s; background: transparent; border: none; cursor: pointer;
        }
        .collapse-btn:hover { background: #F0FAF6; color: var(--kerala-green); }
    </style>
</head>
<body x-data="{ sidebarOpen: true, mobileOpen: false, userOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        <aside id="sidebar" :class="sidebarOpen ? '' : 'collapsed'"
               class="hidden lg:flex flex-col flex-shrink-0 z-50 overflow-hidden">

            {{-- Kerala stripe at top --}}
            <div class="kerala-stripe flex-shrink-0"></div>

            {{-- Brand --}}
            <div class="flex items-center h-[62px] px-4 border-b border-[#E2EDE9] flex-shrink-0 gap-3">
                {{-- Kerala emblem placeholder --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #E8F5F0, #D1EDE4); border: 1.5px solid #A7D4C3;">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: var(--kerala-green);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div x-show="sidebarOpen"
                     x-transition:enter="transition-opacity duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="overflow-hidden">
                    <p class="brand-name">IPPRS</p>
                    <p class="brand-sub">Kerala · Health</p>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

                <div x-show="sidebarOpen" class="nav-section">Main</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="label">Surge Simulator</span>
                </a>

                <a href="{{ route('plans.index') }}"
                   class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="label">Digital Archive</span>
                </a>

                <a href="{{ route('search.index') }}"
                   class="nav-link {{ request()->routeIs('search.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="label">Preparedness Search</span>
                </a>

                <div x-show="sidebarOpen" class="nav-section" style="margin-top:18px;">Outbreak Response</div>
                <div x-show="!sidebarOpen" class="my-2 mx-2 h-px bg-gray-100"></div>

                <a href="{{ route('ebola.index') }}"
                   class="nav-link {{ request()->routeIs('ebola.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="label">Ebola Surveillance</span>
                    @if(request()->routeIs('ebola.*'))
                        <span x-show="sidebarOpen" class="ml-auto w-2 h-2 rounded-full bg-red-500 pulse-dot flex-shrink-0"></span>
                    @endif
                </a>

            </nav>

            {{-- Footer --}}
            <div class="border-t border-[#E2EDE9] p-3 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="collapse-btn" title="Toggle Sidebar">
                    <svg x-show="sidebarOpen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7"/>
                    </svg>
                    <svg x-show="!sidebarOpen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </aside>

        {{-- ===== MOBILE OVERLAY ===== --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm lg:hidden"
             @click="mobileOpen = false"></div>

        <aside x-show="mobileOpen"
               x-transition:enter="transition-transform duration-250"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-[#E2EDE9] flex flex-col lg:hidden shadow-xl">
            <div class="kerala-stripe"></div>
            <div class="flex items-center justify-between h-[62px] px-4 border-b border-[#E2EDE9]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5F0,#D1EDE4);border:1.5px solid #A7D4C3;">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--kerala-green);" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div><p class="brand-name">IPPRS</p><p class="brand-sub">Kerala · Health</p></div>
                </div>
                <button @click="mobileOpen = false" class="topbar-btn">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
                <div class="nav-section">Main</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="label">Surge Simulator</span>
                </a>
                <a href="{{ route('plans.index') }}" class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="label">Digital Archive</span>
                </a>
                <a href="{{ route('search.index') }}" class="nav-link {{ request()->routeIs('search.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="label">Preparedness Search</span>
                </a>
                <div class="nav-section" style="margin-top:18px;">Outbreak Response</div>
                <a href="{{ route('ebola.index') }}" class="nav-link {{ request()->routeIs('ebola.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="label">Ebola Surveillance</span>
                </a>
            </nav>
        </aside>

        {{-- ===== MAIN AREA ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- Top Bar --}}
            <header id="topbar" class="h-[63px] flex-shrink-0 flex items-center justify-between px-5 sm:px-6 z-30">
                <div class="flex items-center gap-4">
                    {{-- Mobile hamburger --}}
                    <button @click="mobileOpen = true" class="topbar-btn lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Live badge --}}
                    <div class="hidden sm:flex items-center gap-1.5 live-badge rounded-full px-3 py-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 pulse-dot"></span>
                        <span class="text-[10px] font-black uppercase tracking-wider font-mono">Monitoring Active</span>
                    </div>

                    {{-- Breadcrumb --}}
                    <div class="hidden md:flex items-center gap-1.5 text-xs">
                        <span class="font-semibold text-gray-400">Government of Kerala</span>
                        <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        <span class="font-bold" style="color: var(--kerala-green);">Integrated Public Health Response System</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Notification --}}
                    <button class="topbar-btn" title="Notifications">
                        <svg class="w-4.5 h-4.5" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- Divider --}}
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    {{-- User menu --}}
                    <div class="relative" @click.away="userOpen = false">
                        <button @click="userOpen = !userOpen"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-xl border border-gray-200 hover:border-[#A7D4C3] hover:bg-[#F0FAF6] transition-all duration-150">
                            <div class="avatar-grad w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-black text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold text-gray-700 hidden sm:block max-w-28 truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="userOpen"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="user-dropdown absolute right-0 mt-2 w-56 z-50">

                            <div class="px-4 py-3" style="border-bottom:1px solid #E2EDE9;">
                                <div class="flex items-center gap-2.5">
                                    <div class="avatar-grad w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black text-white flex-shrink-0">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full active-dot"></span>
                                    <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider font-mono">Active Session</span>
                                </div>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile Settings
                                </a>
                            </div>

                            <div style="border-top:1px solid #E2EDE9;" class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger w-full text-left" style="color:#DC2626;">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto" style="background: var(--kerala-bg);">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>
