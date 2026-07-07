<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HindustanERP - {{ $title ?? 'Management Portal' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        /* Full gold scale built around the brand color #a38c29,
                           so primary-50 ... primary-900 all resolve — nothing
                           left undefined for gradients/hover states to silently drop. */
                        primary: {
                            DEFAULT: '#a38c29',
                            50:  '#fdfbf0',
                            100: '#f9f5dc',
                            200: '#f0e6b3',
                            300: '#e3d183',
                            400: '#d0b855',
                            500: '#b8a43d',
                            600: '#a38c29',
                            700: '#8d7923',
                            800: '#6b5d1c',
                            900: '#4a4014',
                            950: '#2e2810',
                        },
                        slate: {
                            50: '#f6f5f4',
                            100: '#eceae6',
                            200: '#d7d4ce',
                            300: '#bebab0',
                            400: '#a59d92',
                            500: '#8b8377',
                            600: '#6c665d',
                            700: '#534e47',
                            800: '#3c3933',
                            850: '#292724',
                            900: '#191816',
                            950: '#0f0e0d',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'glow': '0 0 15px rgba(163, 140, 41, 0.3)',
                    }
                }
            }
        }
    </script>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/tabasco.css') }}">
</head>
<body class="h-full bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

    <!-- Sidebar Container -->
    <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-gradient-to-b from-primary-900 to-slate-950 text-slate-200 border-r border-primary-800/30 transition-transform duration-300 transform lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Brand Header -->
        
          
   <div class="flex items-center justify-center h-24 bg-black border-b border-[#a38c29]/20 px-3">

    <img
        src="{{ asset('img/logo.jpg') }}"
        alt="HindustanERP Logo"
        class="max-h-20 max-w-full object-contain"
    >

</div>

        
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1">

            <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('dashboard') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>
            
            <!-- Approvals Inbox -->
            <!-- <a href="{{ route('approvals.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('approvals.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Approvals Inbox
            </a> -->
            

            <!-- Project Master -->
            <!-- @if(auth()->user()->hasAnyPermission(['projects.manage', 'projects.view']))
            <a href="{{ route('projects.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('projects.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Project Master
            </a>
            @endif -->

          

            <!-- Sales Register Link -->
            <!-- <a href="{{ route('bookings.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('bookings.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sales Register
            </a> -->

            <!-- Project Units Link -->
            <a href="{{ route('units.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('units.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Project Units
            </a>

            <!-- EMI Collections -->
            <a href="{{ route('emi-collections.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('emi-collections.index') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                EMI Collections
            </a>

            <!-- Partner Management -->
            <a href="{{ route('partners.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('partners.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Partner Management
            </a>

                


            <!-- Reports -->
             <!-- <a href="{{ route('reports.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('reports.index') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m3.293-7.707a1 1 0 111.414 1.414L9 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 110-4 2 2 0 010 4z"/>
                </svg>
                Reports
            </a>  -->



                    <!-- User Management (Visible to authorized roles) -->
            <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('admin.users.*') ? 'active text-white' : 'text-slate-300' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                User Management
            </a>
        </nav>
        
        <!-- Workspace Footer Status -->
       
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- Top Header -->
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 shadow-sm">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-primary-600 rounded-lg">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
               
            </div>

            <!-- Profile Info & Sign Out -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <img class="h-8 w-8 rounded-full object-cover"
                         src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(auth()->user()->name) }}" alt="Avatar">
                    <div class="hidden md:block text-left">
                        <p class="text-xs font-bold text-slate-900  leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-semibold text-slate-400 new-sign uppercase tracking-wide">
                            {{ auth()->user()->roles->first()->name ?? 'User' }}
                        </p>
                    </div>
                </div>

                <span class="w-px h-6 bg-slate-200"></span>

                <!-- Sign Out -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 new-sign hover:text-primary-600 font-bold uppercase tracking-wider transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-6">
            @if (session('status'))
                <div class="mb-6 p-4 bg-primary-50 border border-primary-200 text-primary-800 text-xs font-bold rounded-xl shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>
        
        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200 bg-white py-4 text-center text-[10px] text-slate-400 uppercase tracking-widest">
            Hindustan Real Estate ERP &copy; 2026. All rights reserved.
        </footer>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>