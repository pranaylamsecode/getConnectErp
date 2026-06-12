<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GetConnect ERP - Lead Generation & CRM Platform">
    <title>{{ $title ?? 'Dashboard' }} | GetConnect ERP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (for rapid development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81', 950: '#1e1b4b',
                        },
                        surface: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                            700: '#1e293b', 800: '#0f172a', 900: '#020617',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 3px; }

        /* Glass effect */
        .glass { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(99, 102, 241, 0.1); }

        /* Gradient text */
        .gradient-text { background: linear-gradient(135deg, #818cf8, #6366f1, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Sidebar link active */
        .nav-link { transition: all 0.2s ease; }
        .nav-link:hover, .nav-link.active { background: rgba(99, 102, 241, 0.15); color: #a5b4fc; }

        /* Pulse animation for hot leads */
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Card hover */
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2); }

        /* Fade in animation */
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Table hover */
        .table-row { transition: background 0.2s ease; }
        .table-row:hover { background: rgba(99, 102, 241, 0.05); }

        /* Score badge */
        .score-hot { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .score-warm { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .score-cool { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .score-cold { background: linear-gradient(135deg, #6b7280, #4b5563); }
    </style>
</head>
<body class="bg-surface-900 text-gray-200 min-h-screen" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300"
               :class="sidebarOpen ? 'w-64' : 'w-20'"
               style="background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);">

            <!-- Logo -->
            <div class="flex items-center h-16 px-4 border-b border-brand-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center font-bold text-white text-lg shadow-lg shadow-brand-500/30">
                        G
                    </div>
                    <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                        <h1 class="text-lg font-bold gradient-text">GetConnect</h1>
                        <p class="text-[10px] text-gray-500 -mt-0.5">ERP Sales Platform</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>

                <a href="{{ route('leads.index') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('leads.*') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="sidebarOpen" x-transition>Leads</span>
                </a>

                <a href="{{ route('campaigns.index') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('campaigns.*') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span x-show="sidebarOpen" x-transition>Campaigns</span>
                </a>

                <a href="{{ route('templates.index') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('templates.*') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    <span x-show="sidebarOpen" x-transition>Templates</span>
                </a>

                <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">AI Tools</p>
                </div>

                <a href="{{ route('ai.assistant') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('ai.*') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span x-show="sidebarOpen" x-transition>AI Assistant</span>
                </a>

                @if(auth()->user()?->canManageTeam())
                <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Admin</p>
                </div>

                <a href="{{ route('teams.index') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('teams.*') ? 'active' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" x-transition>Team</span>
                </a>
                @endif
            </nav>

            <!-- Toggle Button -->
            <div class="p-3 border-t border-brand-900/50">
                <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center py-2 rounded-lg text-gray-500 hover:text-gray-300 hover:bg-surface-800 transition">
                    <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            <!-- Top Bar -->
            <header class="sticky top-0 z-40 glass h-16 flex items-center justify-between px-6">
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $title ?? 'Dashboard' }}</h2>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search leads, campaigns..."
                               class="w-64 bg-surface-800/50 border border-gray-700/50 rounded-lg pl-10 pr-4 py-2 text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-surface-800 transition">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white">
                                    {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-200">{{ auth()->user()?->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()?->role ?? 'user' }}</p>
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 glass rounded-xl shadow-2xl py-2">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-surface-700 transition">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-surface-700 transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('message'))
            <div class="mx-6 mt-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                ✓ {{ session('message') }}
            </div>
            @endif

            <!-- Page Content -->
            <div class="p-6 fade-in">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
