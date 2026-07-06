<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HindustanERP') }} - Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tabasco.css') }}">
        <!-- Tailwind CDN & Lucide Icons -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                DEFAULT: '#a38c29',
                                50: '#fcfaf0',
                                100: '#f6f1d2',
                                200: '#eddfa3',
                                300: '#e1c66c',
                                400: '#d2a73c',
                                500: '#bba12f',
                                600: '#a38c29',
                                700: '#7d6b20',
                                800: '#574a16',
                                900: '#322b0c',
                                950: '#1b1706',
                            },
                        }
                    }
                }
            }
        </script>
        <script src="https://unpkg.com/lucide@latest"></script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-6px); }
            }
            .float-anim { animation: float 4s ease-in-out infinite; }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .fade-in { animation: fadeInUp 0.5s ease both; }
            .fade-in-delay { animation: fadeInUp 0.5s ease 0.15s both; }

            .credential-row {
                transition: all 0.2s ease;
                cursor: pointer;
            }
            .credential-row:hover {
                background: rgba(163, 140, 41, 0.12);
                border-color: rgba(163, 140, 41, 0.4);
            }
        </style>
    </head>
    <body class="font-sans text-slate-300 antialiased min-h-screen flex items-center justify-center p-4 bg-slate-950 relative overflow-hidden">

        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary-900/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-950/30 rounded-full blur-3xl"></div>
        </div>

        <!-- Main Content Container -->
        <div class="relative z-10 w-full max-w-5xl mx-auto">

            <!-- Brand Header -->
            <div class="flex justify-center mb-8 fade-in">

                    
                   <div class="flex items-center gap-3">

    <img
        src="{{ asset('img/logo.jpg') }}"
        alt="HindustanERP Logo"
        class="h-20 w-30 object-contain rounded-lg"
    >


</div>
             
            </div>

            <!-- Two-column Layout: Login + Credentials -->
            <div class="grid lg:grid-cols-2 gap-6 items-start fade-in-delay">

                <!-- Login / Auth Card -->
                <div class="w-full bg-slate-900/80 border border-slate-800 p-8 rounded-2xl shadow-2xl backdrop-blur-2xl">
                    {{ $slot }}
                </div>

                <!-- Demo Credentials Panel -->
                @php
                    $demoUsers = \App\Models\User::with('roles')->get();
                @endphp
                <div class="w-full bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 bg-amber-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold text-white uppercase tracking-wider">Demo Login Credentials</h2>
                            <p class="text-[10px] text-slate-500">Click any row to auto-fill the login form</p>
                        </div>
                    </div>

                    <!-- Password note -->
                    <div class="mb-4 px-3 py-2 bg-primary-950/60 border border-primary-800/50 rounded-lg flex items-center gap-2">
                        <svg class="w-3 h-3 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-[10px] text-primary-300">All accounts use password: <strong class="text-primary-200 font-bold">password</strong></span>
                    </div>

                    <!-- Users Table -->
                    <div class="space-y-2">
                        @forelse($demoUsers as $demoUser)
                        <div class="credential-row border border-slate-800 rounded-xl px-3 py-2.5 group"
                             onclick="fillCredentials('{{ $demoUser->email }}')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <!-- Avatar -->
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-[9px] font-bold text-white flex-shrink-0">
                                        {{ strtoupper(substr($demoUser->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold text-slate-200 truncate leading-tight">{{ $demoUser->name }}</p>
                                        <p class="text-[10px] text-slate-500 truncate leading-tight">{{ $demoUser->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                    @if($demoUser->roles->isNotEmpty())
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full
                                            @if($demoUser->roles->first()->name === 'Owner') bg-primary-900/60 text-primary-300
                                            @elseif($demoUser->roles->first()->name === 'Accountant') bg-emerald-900/60 text-emerald-300
                                            @elseif($demoUser->roles->first()->name === 'Sales') bg-blue-900/60 text-blue-300
                                            @else bg-amber-900/60 text-amber-300 @endif
                                            uppercase tracking-wider">
                                            {{ $demoUser->roles->first()->name }}
                                        </span>
                                    @endif
                                    <svg class="w-3 h-3 text-slate-600 group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- System Badge -->
                            @if($demoUser->system)
                            <div class="mt-1.5 pl-9">
                                <span class="text-[9px] text-slate-600 font-medium">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                    {{ $demoUser->system->name }} · {{ $demoUser->system->currency_code }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @empty
                        <p class="text-xs text-slate-500 text-center py-4">No users found. Run <code class="text-primary-400">php artisan db:seed</code></p>
                        @endforelse
                    </div>

                    <!-- Footer Note -->
                    <p class="mt-4 text-[9px] text-slate-600 text-center uppercase tracking-widest">
                        Hindustan Real Estate ERP &copy; {{ date('Y') }}
                    </p>
                </div>

            </div>
        </div>

        <script>
            // Initialize icons
            lucide.createIcons();

            // Auto-fill login form when clicking a credential row
            function fillCredentials(email) {
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                if (emailInput) {
                    emailInput.value = email;
                    emailInput.dispatchEvent(new Event('input'));
                }
                if (passwordInput) {
                    passwordInput.value = 'password';
                    passwordInput.dispatchEvent(new Event('input'));
                }
                // Briefly flash the inputs
                [emailInput, passwordInput].forEach(el => {
                    if (!el) return;
                    el.style.borderColor = '#a38c29';
                    el.style.boxShadow = '0 0 0 3px rgba(163,140,41,0.2)';
                    setTimeout(() => {
                        el.style.borderColor = '';
                        el.style.boxShadow = '';
                    }, 800);
                });
            }
        </script>
    </body>
</html>
