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
    {{-- CKEditor 5 — Rich Text Editor for description/narration fields --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
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
<body class="h-full bg-slate-50 text-slate-900" 
      x-data="{ sidebarOpen: false, fontSize: localStorage.getItem('erp-font-size') || '100%' }" 
      x-init="$watch('fontSize', val => { document.documentElement.style.fontSize = val; localStorage.setItem('erp-font-size', val); }); document.documentElement.style.fontSize = fontSize;">

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
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('dashboard') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Dashboard
        </a>

        {{-- ═══ PROJECTS & PROPERTIES ═══ --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Projects & Properties</p>

        @if(auth()->user()->hasAnyPermission(['projects.manage', 'projects.view']))
        <a href="{{ route('projects.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('projects.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/>
            </svg>
            Projects
        </a>
        @endif

        <a href="{{ route('units.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('units.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Units
        </a>

        {{-- ═══ SALES & CUSTOMERS ═══ --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sales & Customers</p>

       <div x-data="{ openSales: {{ Request::routeIs('sales.*') || request('tab') === 'sale-return' || request('tab') === 'exchange' ? 'true' : 'false' }} }" class="space-y-1">
    <div class="w-full flex items-center justify-between rounded-lg hover:bg-slate-800/30 transition-all {{ Request::routeIs('sales.*') ? 'bg-slate-800/20' : '' }}">
        <a href="{{ route('sales.index') }}" class="flex-1 flex items-center gap-3 px-3 py-2.5 text-xs font-semibold hover:text-primary-300 transition-colors {{ Request::routeIs('sales.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Sales
        </a>
        <button @click.prevent="openSales = !openSales" class="p-2.5 text-slate-400 hover:text-primary-300 transition-colors focus:outline-none">
            <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openSales ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
    <div x-show="openSales" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
        <a href="{{ route('sales.index') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('sales.index') && !request('tab') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Sales Register
        </a>
        <a href="{{ route('sales.index') }}?tab=sale-return"
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'sale-return' ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Cancellation / Return
        </a>
        <a href="{{ route('sales.index') }}?tab=exchange"
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'exchange' ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Unit Exchange
        </a>
    </div>
</div>

        <div x-data="{ openEMI: {{ Request::routeIs('emi-collections.*') ? 'true' : 'false' }} }" class="space-y-1">
            <div class="w-full flex items-center justify-between rounded-lg hover:bg-slate-800/30 transition-all {{ Request::routeIs('emi-collections.*') ? 'bg-slate-800/20' : '' }}">
                <a href="{{ route('emi-collections.index') }}" class="flex-1 flex items-center gap-3 px-3 py-2.5 text-xs font-semibold hover:text-primary-300 transition-colors {{ Request::routeIs('emi-collections.*') ? 'active text-white' : 'text-slate-300' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    EMI & Payments
                </a>
                <button @click.prevent="openEMI = !openEMI" class="p-2.5 text-slate-400 hover:text-primary-300 transition-colors focus:outline-none">
                    <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openEMI ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            <div x-show="openEMI" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('emi-collections.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Customer EMI
                </a>
                <a href="{{ route('emi-collections.receipts') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.receipts') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Collect Payment
                </a>
                <a href="{{ route('emi-collections.outstanding') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.outstanding') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Dues & Outstanding
                </a>
            </div>
        </div>

        <a href="{{ route('customers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('customers.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Customers
        </a>

        <a href="{{ route('brokers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('brokers.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Brokerage Management
        </a>

 



        {{-- ═══ FINANCE & ACCOUNTING ═══ --}}
        <!-- <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Finance & Accounting</p>


<a href="{{ route('vouchers.receipt.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.receipt.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Receipt Vouchers
                </a>
 -->

<!-- Finance & Accounting -->
{{-- Finance & Accounting --}}
<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Finance & Accounting</p>

<a href="{{ route('vouchers.receipt.create') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('vouchers.receipt.*') ? 'active text-white' : 'text-slate-300' }}">
    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14h6M9 10h6M7 3h10a2 2 0 012 2v14l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
    </svg>
    Receipt Allocation Management
</a>

<a href="{{ route('vouchers.ledger.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('vouchers.ledger.*') ? 'active text-white' : 'text-slate-300' }}">
    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a1 1 0 01-1.447.894L16 18.618l-2.553 1.276a1 1 0 01-.894 0L10 18.618l-2.553 1.276A1 1 0 016 19V5z"/>
    </svg>
    Ledger Directory
</a>

        <!-- Vouchers -->
        <!-- <div x-data="{ openVoucher: {{ Request::routeIs('vouchers.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openVoucher = !openVoucher" class="w-full flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('vouchers.*') ? 'text-white' : 'text-slate-300' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Vouchers 
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openVoucher ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openVoucher" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('vouchers.payment.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.payment.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Payment Vouchers
                </a>
                <a href="{{ route('vouchers.receipt.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.receipt.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Receipt Vouchers
                </a>
                <a href="{{ route('vouchers.contra.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.contra.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Contra Vouchers
                </a>
                <a href="{{ route('vouchers.journal.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.journal.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Journal Vouchers
                </a>
                <a href="{{ route('vouchers.sales-purchase.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.sales-purchase.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Sales & Purchases
                </a>
                <a href="{{ route('vouchers.ledger.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.ledger.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Ledger Directory
                </a>
                <a href="{{ route('vouchers.cash-book') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.cash-book') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Cash Book
                </a>
                <a href="{{ route('vouchers.bank-book') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.bank-book') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Bank Book
                </a>
                <a href="{{ route('vouchers.entity-ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.entity-ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Entity Sub-Ledger
                </a>
            </div>
        </div> -->

        <!-- Bank & Loans -->
        <div x-data="{ openBankLoans: {{ Request::routeIs('bank.*') || Request::routeIs('loans.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openBankLoans = !openBankLoans" class="w-full flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('bank.*') || Request::routeIs('loans.*') ? 'text-white' : 'text-slate-300' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18v-7m4 7v-7m4 7v-7m4 7v-7M4 10l8-6 8 6"/>
                    </svg>
                    Bank & Loans
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openBankLoans ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openBankLoans" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('bank.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('bank.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Bank Accounts
                </a>
                <a href="{{ route('loans.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('loans.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Loan Repayment
                </a>
                <!-- <a href="{{ route('vouchers.ledger.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.ledger.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Chart of Accounts
                </a> -->
            </div>
        </div>

        <!-- Collections -->
        <a href="{{ route('emi-collections.cash-book') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('emi-collections.cash-book') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
            </svg>
            Collections
        </a>

        {{-- ═══ EXPENSES & VENDORS ═══ --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Expenses & Vendors</p>

        <div x-data="{ openExpenses: {{ Request::is('expenses*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openExpenses = !openExpenses" class="w-full flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::is('expenses*') ? 'text-white' : 'text-slate-300' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Site Expenses
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openExpenses ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openExpenses" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('expenses.bills.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.bills.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Add New Bill
                </a>
                <a href="{{ route('expenses.ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Expense Ledger
                </a>
                <a href="{{ route('expenses.bills.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.bills.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Bills List
                </a>
            </div>
        </div>

        <a href="{{ route('suppliers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('suppliers.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Suppliers
        </a>

        <a href="{{ route('partners.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('partners.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Partners
        </a>

        <!-- <a href="{{ route('employees.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('employees.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Employees Master
        </a> -->

        {{-- ═══ REPORTS & ANALYTICS ═══ --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports & Analytics</p>

        <div x-data="{ openReports: {{ Request::routeIs('reports.*') ? 'true' : 'false' }} }" class="space-y-1">
            <div class="w-full flex items-center justify-between rounded-lg hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.*') ? 'bg-slate-800/20' : '' }}">
                <a href="{{ route('reports.index', ['report' => 'dashboard']) }}" class="flex-1 flex items-center gap-3 px-3 py-2.5 text-xs font-semibold hover:text-primary-300 transition-colors {{ Request::routeIs('reports.*') ? 'text-white' : 'text-slate-300' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                    Reports & Statements
                </a>
                <button @click.prevent="openReports = !openReports" class="p-2.5 text-slate-400 hover:text-primary-300 transition-colors focus:outline-none">
                    <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openReports ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            <div x-show="openReports" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                @php
                    $reportLinks = [
                        'availability'        => 'Availability Report',
                        'sales'               => 'Sales Report',
                        'emi_collections'     => 'EMI & Collection Reports',
                        'customer_ledger'     => 'Customer Ledger / Account Stmt',
                        'cash_book'           => 'Cash Book',
                        'bank_reports'        => 'Bank Reports',
                        'partner_statements'  => 'Supplier, Contractor & Partner Stmt',
                        'sales_return'        => 'Sales Return Report',
                        'exchange_report'     => 'Exchange Report',
                        'petty_cash'          => 'Petty Cash Book',
                        'loan_schedules'      => 'Bank Loan EMI Schedules',
                        'trial_balance'       => 'Trial Balance',
                        'profit_loss'         => 'Profit & Loss',
                        'balance_sheet'       => 'Balance Sheet Summary',
                    ];
                    $currentReport = request('report', 'availability');
                @endphp
                @foreach($reportLinks as $key => $label)
                    <a href="{{ route('reports.index', ['report' => $key]) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ $currentReport === $key ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ═══ ADMINISTRATION & SETTINGS ═══ --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Administration</p>

        <!-- Configurations -->
        <div x-data="{ openMaster: {{ Request::routeIs('floors.*') || Request::routeIs('unit-types.*') || Request::routeIs('gst.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openMaster = !openMaster" class="w-full flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all text-slate-300">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    Master
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openMaster ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openMaster" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('floors.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('floors.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Floor
                </a>
                <a href="{{ route('unit-types.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('unit-types.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Unit Type
                </a>
                <!-- <a href="{{ route('gst.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('gst.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    GST
                </a> -->
            </div>
        </div>

        <!-- Users & Roles -->
        <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('admin.users.*') ? 'active text-white' : 'text-slate-300' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Users & Roles
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
                <!-- Interactive Font Size Switcher -->
                <div class="flex items-center gap-1.5 bg-slate-100/90 rounded-full px-2.5 py-1 border border-slate-200 shadow-sm mr-2 shrink-0">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 mr-0.5"></i>
                    <button type="button" @click="fontSize = '100%'" 
                            :class="fontSize === '100%' ? 'bg-white text-primary font-black shadow-sm scale-105' : 'text-slate-500 hover:text-slate-800'"
                            class="text-[9px] font-bold px-2 py-0.5 rounded-full transition-all duration-150" title="Normal text size">A</button>
                    <button type="button" @click="fontSize = '112.5%'" 
                            :class="fontSize === '112.5%' ? 'bg-white text-primary font-black shadow-sm scale-105' : 'text-slate-500 hover:text-slate-800'"
                            class="text-[9px] font-bold px-2 py-0.5 rounded-full transition-all duration-150" title="Medium text size">A+</button>
                    <button type="button" @click="fontSize = '125%'" 
                            :class="fontSize === '125%' ? 'bg-white text-primary font-black shadow-sm scale-105' : 'text-slate-500 hover:text-slate-800'"
                            class="text-[9px] font-bold px-2 py-0.5 rounded-full transition-all duration-150" title="Large text size">A++</button>
                </div>

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
            @php
                $statusText = session('status');
                $modalTitle = 'Success!';
                $modalBadge = 'Action Completed';
                $modalSubtitle = 'Operation completed successfully.';

                if (str_contains(strtolower($statusText), 'project')) {
                    if (str_contains(strtolower($statusText), 'create') || str_contains(strtolower($statusText), 'add')) {
                        $modalTitle = 'Project Added!';
                        $modalBadge = 'New Project Created';
                        $modalSubtitle = 'The new project has been added successfully.';
                    } elseif (str_contains(strtolower($statusText), 'delete')) {
                        $modalTitle = 'Project Deleted!';
                        $modalBadge = 'Project Removed';
                        $modalSubtitle = 'The project has been deleted successfully.';
                    } elseif (str_contains(strtolower($statusText), 'update') || str_contains(strtolower($statusText), 'edit')) {
                        $modalTitle = 'Project Updated!';
                        $modalBadge = 'Project Modified';
                        $modalSubtitle = 'Project specifications updated successfully.';
                    }
                }
            @endphp
            {{-- ═══════ PROFESSIONAL SUCCESS MODAL ═══════ --}}
            <div id="statusSuccessModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" style="background: rgba(15,23,42,0.55); backdrop-filter: blur(4px);">
                <div id="statusModalCard" class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden"
                    style="animation: successModalIn 0.35s cubic-bezier(0.34,1.56,0.64,1) both;">
                    {{-- Gold shimmer top stripe --}}
                    <div class="h-1 w-full bg-gradient-to-r from-[#a38c29] via-[#d9bf3b] to-[#a38c29]"></div>

                    {{-- Dark header --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                {{-- Animated Checkmark Icon --}}
                                <div class="w-12 h-12 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/40 flex items-center justify-center shadow-lg shadow-[#a38c29]/20 ring-1 ring-[#d9bf3b]/20 shrink-0"
                                    style="animation: iconPop 0.5s cubic-bezier(0.34,1.56,0.64,1) 0.2s both;">
                                    <svg class="w-6 h-6 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"
                                            style="stroke-dasharray:30; stroke-dashoffset:30; animation: drawCheck 0.5s ease-out 0.4s forwards;"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        {{ $modalBadge }}
                                    </span>
                                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">{{ $modalTitle }}</h2>
                                    <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $modalSubtitle }}</p>
                                </div>
                            </div>
                            {{-- Close Button --}}
                            <button onclick="closeStatusModal()" title="Close"
                                class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#a38c29]/30 text-white hover:text-[#d9bf3b] flex items-center justify-center transition-all focus:outline-none shrink-0 border border-white/10 hover:border-[#a38c29]/40 text-sm font-bold">
                                ✕
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-5 bg-gradient-to-b from-slate-50/80 to-white space-y-3">
                        {{-- Message Card --}}
                        <div class="flex items-center gap-3 bg-white border border-[#a38c29]/25 rounded-xl px-4 py-3.5 shadow-sm ring-1 ring-[#a38c29]/10">
                            <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold text-[#a38c29]/70 uppercase tracking-widest">System Message</p>
                                <p class="text-xs font-extrabold text-slate-900 mt-0.5">{{ session('status') }}</p>
                            </div>
                        </div>

                        {{-- Auto-close progress bar --}}
                        <div class="flex items-center gap-2.5">
                            <div class="flex-1 h-1 bg-slate-100 rounded-full overflow-hidden">
                                <div id="statusProgressBar" class="h-full bg-gradient-to-r from-[#a38c29] to-[#d9bf3b] rounded-full"
                                    style="width: 100%; animation: drainBar 4s linear 0.5s forwards;"></div>
                            </div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider whitespace-nowrap">Auto-close</span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-end">
                        <button onclick="closeStatusModal()"
                            class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#7a6920] text-white text-xs font-bold shadow-md shadow-[#a38c29]/25 uppercase tracking-wider transition-all ring-1 ring-[#a38c29]/30 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Got it
                        </button>
                    </div>
                </div>
            </div>

            <style>
                @keyframes successModalIn {
                    from { opacity: 0; transform: scale(0.85) translateY(20px); }
                    to   { opacity: 1; transform: scale(1) translateY(0); }
                }
                @keyframes iconPop {
                    from { opacity: 0; transform: scale(0.4); }
                    to   { opacity: 1; transform: scale(1); }
                }
                @keyframes drawCheck {
                    to { stroke-dashoffset: 0; }
                }
                @keyframes drainBar {
                    from { width: 100%; }
                    to   { width: 0%; }
                }
            </style>
            <script>
                function closeStatusModal() {
                    var modal = document.getElementById('statusSuccessModal');
                    if (modal) {
                        modal.style.transition = 'opacity 0.25s ease';
                        modal.style.opacity = '0';
                        setTimeout(function() { modal.remove(); }, 260);
                    }
                }
                // Auto close after 4.5s
                setTimeout(closeStatusModal, 4500);
            </script>
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

        // Limit percentage/GST inputs to 2 digits before decimal and 2 digits after decimal
        window.limitPercentageInput = function(input) {
            let value = input.value;
            // Allow only numbers and a single decimal point
            value = value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            if (parts.length > 2) {
                parts.splice(2); // keep only first two parts
            }
            if (parts[0].length > 2) {
                parts[0] = parts[0].substring(0, 2);
            }
            if (parts[1] !== undefined && parts[1].length > 2) {
                parts[1] = parts[1].substring(0, 2);
            }
            const newValue = parts.join('.');
            if (input.value !== newValue) {
                input.value = newValue;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        // Indian Numbering System words converter
        window.convertNumberToWords = function(num) {
            num = parseFloat(num);
            if (isNaN(num) || num <= 0) return '';
            
            let str = num.toFixed(2);
            let parts = str.split('.');
            let integerPart = parseInt(parts[0]);
            let decimalPart = parseInt(parts[1]);
            
            let words = "";
            
            function convertInteger(n) {
                if (n === 0) return "";
                
                const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", 
                               "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
                const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
                
                if (n < 20) return units[n];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 !== 0 ? " " + units[n % 10] : "");
                if (n < 1000) return units[Math.floor(n / 100)] + " Hundred" + (n % 100 !== 0 ? " and " + convertInteger(n % 100) : "");
                if (n < 100000) return convertInteger(Math.floor(n / 1000)) + " Thousand" + (n % 1000 !== 0 ? " " + convertInteger(n % 1000) : "");
                if (n < 10000000) return convertInteger(Math.floor(n / 100000)) + " Lakh" + (n % 100000 !== 0 ? " " + convertInteger(n % 100000) : "");
                return convertInteger(Math.floor(n / 10000000)) + " Crore" + (n % 10000000 !== 0 ? " " + convertInteger(n % 10000000) : "");
            }
            
            let rupeeWords = convertInteger(integerPart);
            if (rupeeWords) {
                words += rupeeWords + " Rupees";
            }
            
            if (decimalPart > 0) {
                let paiseWords = convertInteger(decimalPart);
                if (rupeeWords) {
                    words += " and " + paiseWords + " Paise";
                } else {
                    words += paiseWords + " Paise";
                }
            }
            
            return words ? words + " Only" : "";
        };

        // Sanitize general amount inputs (strip non-numeric except one decimal point and 2 decimal places)
        window.sanitizeAmountInput = function(input) {
            let value = input.value;
            // Allow only numbers and a single decimal point
            value = value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            if (parts.length > 2) {
                parts.splice(2); // keep only first two parts
            }
            if (parts[1] !== undefined && parts[1].length > 2) {
                parts[1] = parts[1].substring(0, 2);
            }
            const newValue = parts.join('.');
            if (input.value !== newValue) {
                input.value = newValue;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        // Update all amount inputs with their text representation
        window.updateAllAmountInWords = function() {
            document.querySelectorAll('input[type="number"], input[type="text"]').forEach(el => {
                const xModel = (el.getAttribute('x-model') || '').toLowerCase();
                const name = (el.getAttribute('name') || '').toLowerCase();
                const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();
                
                // Exclude rates per sqft and agreed sale amounts
                if (xModel.includes('sqft') || name.includes('sqft') || placeholder.includes('sqft') || xModel.includes('sale_amount')) {
                    return;
                }
                
                if (xModel.includes('amount') || name.includes('amount') || placeholder.includes('amount') || name === 'debit' || name === 'credit' || name.includes('debit') || name.includes('credit')) {
                    let wordsLabel = el.nextElementSibling;
                    if (!wordsLabel || !wordsLabel.classList.contains('amount-in-words-label')) {
                        wordsLabel = document.createElement('div');
                        wordsLabel.className = 'amount-in-words-label text-[10px] text-amber-700 font-extrabold uppercase mt-1 tracking-wide transition-all';
                        el.parentNode.insertBefore(wordsLabel, el.nextSibling);
                    }
                    const words = window.convertNumberToWords(el.value);
                    wordsLabel.textContent = words ? 'In Words: ' + words : '';
                }
            });
        };

        // Event delegation for text updates and decimal limits
        document.addEventListener('input', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT') {
                const xModel = (el.getAttribute('x-model') || '').toLowerCase();
                const name = (el.getAttribute('name') || '').toLowerCase();
                const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();
                const id = (el.getAttribute('id') || '').toLowerCase();
                
                // 1. Handle GST & Percentage inputs limit
                if (!xModel.includes('sqft') && !name.includes('sqft') && !placeholder.includes('sqft') && !xModel.includes('amount') && !name.includes('amount') && !placeholder.includes('amount')) {
                    if (
                        xModel.includes('gst') ||
                        xModel.includes('percentage') ||
                        xModel.includes('rate') ||
                        xModel.includes('value') ||
                        name.includes('gst') ||
                        name.includes('rate') ||
                        name.includes('percentage') ||
                        placeholder.includes('gst') ||
                        placeholder.includes('percentage') ||
                        placeholder.includes('%') ||
                        placeholder.includes('e.g. 7.50') ||
                        placeholder.includes('e.g. 18') ||
                        id.includes('gst') ||
                        id.includes('percentage') ||
                        id.includes('rate')
                    ) {
                        window.limitPercentageInput(el);
                    }
                }
                
                // 2. Handle Amount sanitization & In Words update
                if ((el.type === 'number' || el.type === 'text') && !xModel.includes('sqft') && !name.includes('sqft') && !placeholder.includes('sqft')) {
                    if (xModel.includes('amount') || name.includes('amount') || placeholder.includes('amount') || name === 'debit' || name === 'credit' || name.includes('debit') || name.includes('credit')) {
                        // Strip invalid characters from the amount input
                        window.sanitizeAmountInput(el);
                        
                        // Render in-words only if it is not the Agreed Sale Amount field
                        if (!xModel.includes('sale_amount')) {
                            let wordsLabel = el.nextElementSibling;
                            if (!wordsLabel || !wordsLabel.classList.contains('amount-in-words-label')) {
                                wordsLabel = document.createElement('div');
                                wordsLabel.className = 'amount-in-words-label text-[10px] text-amber-700 font-extrabold uppercase mt-1 tracking-wide transition-all';
                                el.parentNode.insertBefore(wordsLabel, el.nextSibling);
                            }
                            const words = window.convertNumberToWords(el.value);
                            wordsLabel.textContent = words ? 'In Words: ' + words : '';
                        }
                    }
                }
            }
        });

        // Initialize on load and on DOM updates (e.g. when opening modals)
        document.addEventListener('DOMContentLoaded', window.updateAllAmountInWords);
        window.addEventListener('load', window.updateAllAmountInWords);
        document.addEventListener('click', () => setTimeout(window.updateAllAmountInWords, 100));

        // Auto-clear 0 default values on focus, and restore 0 on empty blur
        window.isAutoClearTargetField = function(el) {
            const xModel = (el.getAttribute('x-model') || '').toLowerCase();
            const name = (el.getAttribute('name') || '').toLowerCase();
            const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();
            const id = (el.getAttribute('id') || '').toLowerCase();
            
            // Exclude sqft rates
            if (xModel.includes('sqft') || name.includes('sqft') || placeholder.includes('sqft')) {
                return false;
            }
            
            // Matches amount fields
            if (xModel.includes('amount') || name.includes('amount') || placeholder.includes('amount') || name === 'debit' || name === 'credit' || name.includes('debit') || name.includes('credit')) {
                return true;
            }
            
            // Matches percentage/rate/gst fields
            if (
                xModel.includes('gst') ||
                xModel.includes('percentage') ||
                xModel.includes('rate') ||
                xModel.includes('value') ||
                name.includes('gst') ||
                name.includes('rate') ||
                name.includes('percentage') ||
                placeholder.includes('gst') ||
                placeholder.includes('percentage') ||
                placeholder.includes('%') ||
                placeholder.includes('e.g. 7.50') ||
                placeholder.includes('e.g. 18') ||
                id.includes('gst') ||
                id.includes('percentage') ||
                id.includes('rate')
            ) {
                return true;
            }
            
            return false;
        };

        document.addEventListener('focusin', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT' && (el.type === 'number' || el.type === 'text')) {
                if (window.isAutoClearTargetField(el)) {
                    const val = el.value.trim();
                    if (val === '0' || val === '0.00' || parseFloat(val) === 0) {
                        el.value = '';
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            }
        });

        document.addEventListener('focusout', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT' && (el.type === 'number' || el.type === 'text')) {
                if (window.isAutoClearTargetField(el)) {
                    const val = el.value.trim();
                    if (val === '') {
                        el.value = '0';
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            }
        });

        // Block typing of invalid non-numeric/non-dot characters before rendering
        document.addEventListener('keypress', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT') {
                if (window.isAutoClearTargetField(el)) {
                    const charCode = (e.which) ? e.which : e.keyCode;
                    const keyChar = String.fromCharCode(charCode);
                    
                    // Allow standard navigation/control keystrokes (like backspace, tab, enter)
                    if (e.ctrlKey || e.metaKey || charCode < 32) {
                        return;
                    }
                    
                    // Allow only digits (0-9) and decimal point (.)
                    if (/[0-9.]/.test(keyChar)) {
                        // Allow decimal point only if there is not one already
                        if (keyChar === '.' && el.value.indexOf('.') !== -1) {
                            e.preventDefault();
                        }
                        return;
                    }
                    
                    // Prevent any other characters (letters, mathematical operators like +, -, e)
                    e.preventDefault();
                }
            }
        });
    </script>

    {{-- ═══ GLOBAL CKEDITOR INITIALIZER ═══ --}}
    {{-- Any <textarea class="ck-editor-field" id="unique_id"> will be upgraded to a rich editor --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ckToolbar = [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'link', '|',
                'undo', 'redo'
            ];

            document.querySelectorAll('textarea.ck-editor-field').forEach(textarea => {
                ClassicEditor
                    .create(textarea, {
                        toolbar: ckToolbar,
                        placeholder: textarea.getAttribute('placeholder') || 'Enter details here...',
                    })
                    .then(editor => {
                        // Sync editor data back to textarea on every keystroke
                        editor.model.document.on('change:data', () => {
                            textarea.value = editor.getData();
                        });

                        // Also sync before the parent form is submitted
                        const form = textarea.closest('form');
                        if (form) {
                            form.addEventListener('submit', () => {
                                textarea.value = editor.getData();
                            }, { once: false });
                        }

                        // Store reference globally keyed by textarea id
                        if (textarea.id) {
                            window['ckEditor_' + textarea.id] = editor;
                        }
                    })
                    .catch(err => console.error('CKEditor init error on #' + textarea.id + ':', err));
            });
        });
    </script>
</body>
</html>