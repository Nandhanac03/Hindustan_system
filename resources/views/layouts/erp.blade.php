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
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('dashboard') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        Dashboard
    </a>

    <!-- Projects (moved out of Master Setup) -->
    @if(auth()->user()->hasAnyPermission(['projects.manage', 'projects.view']))
    <a href="{{ route('projects.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('projects.*') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/>
        </svg>
        Projects
    </a>
    @endif

    <!-- Units -->
    <a href="{{ route('units.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('units.*') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Units
    </a>

   {{-- ═══ SALES SECTION ═══ --}}
<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sales</p>

<div x-data="{ openSales: {{ Request::routeIs('sales.*') || request('tab') === 'returns' || request('tab') === 'cancellations' ? 'true' : 'false' }} }" class="space-y-1">
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
        <a href="{{ route('sales.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('sales.index') && !request()->has('tab') ? 'bg-[#a38c29]/20 text-white font-bold border-l-2 border-[#a38c29] pl-2.5' : 'text-slate-300 hover:text-primary-300 hover:bg-slate-800/20' }}">
            Sales Register
        </a>
         <a href="{{ route('sales.index') }}?tab=sale-return" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'sale-return' ? 'bg-[#a38c29]/20 text-white font-bold border-l-2 border-[#a38c29] pl-2.5' : 'text-slate-300 hover:text-primary-300 hover:bg-slate-800/20' }}">
           Cancel Booking
        </a>
        <a href="{{ route('sales.index') }}?tab=exchange" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'exchange' ? 'bg-[#a38c29]/20 text-white font-bold border-l-2 border-[#a38c29] pl-2.5' : 'text-slate-300 hover:text-primary-300 hover:bg-slate-800/20' }}">
            Unit Exchange
        </a>
        <!-- <a href="{{ route('sales.index') }}?tab=returns" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'returns' ? 'bg-[#a38c29]/20 text-white font-bold border-l-2 border-[#a38c29] pl-2.5' : 'text-slate-300 hover:text-primary-300 hover:bg-slate-800/20' }}">
            Sales Return
        </a> -->
       
        <!-- <a href="{{ route('sales.index') }}?tab=cancellations" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'cancellations' ? 'bg-[#a38c29]/20 text-white font-bold border-l-2 border-[#a38c29] pl-2.5' : 'text-slate-300 hover:text-primary-300 hover:bg-slate-800/20' }}">
            Cancel Booking
        </a> -->
    </div>
</div>

    <!-- EMI & Payments -->
<div x-data="{ openEMI: {{ Request::routeIs('emi-collections.*') ? 'true' : 'false' }} }" class="space-y-1">
    <div class="w-full flex items-center justify-between rounded-lg hover:bg-slate-800/30 transition-all {{ Request::routeIs('emi-collections.*') ? 'bg-slate-800/20' : '' }}">
        <a href="{{ route('emi-collections.index') }}" class="flex-1 flex items-center gap-3 px-3 py-2.5 text-xs font-semibold hover:text-primary-300 transition-colors {{ Request::routeIs('emi-collections.index') ? 'active text-white' : 'text-slate-300' }}">
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
        @php $firstSaleId = \App\Models\Sale::first()?->id ?? 7; @endphp
        <a href="{{ route('emi-collections.ledger', $firstSaleId) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            My EMI Ledger
        </a>
        <a href="{{ route('emi-collections.schedules') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.schedules') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Payment Schedule
        </a>
        <a href="{{ route('emi-collections.receipts') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.receipts') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Collect Payment
        </a>
        <a href="{{ route('emi-collections.outstanding') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.outstanding') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            Dues & Outstanding
        </a>
    </div>
</div>

    <!-- Customers -->
    <a href="{{ route('customers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('customers.*') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Customers
    </a>

    {{-- ═══ FINANCE SECTION ═══ --}}
    <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Finance</p>

    <!-- Collections (using cash-book route as closest match) -->
    <a href="{{ route('emi-collections.cash-book') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('emi-collections.cash-book') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
        </svg>
        Collections
    </a>

    <!-- Bank & Loans (Bank from Master Setup + Loans, combined) -->
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
        </div>
    </div>

    <!-- Vouchers & Core Accounting Engine Dropdown -->
    <div x-data="{ openVoucher: {{ Request::routeIs('vouchers.*') ? 'true' : 'false' }} }" class="space-y-1">
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
            <a href="{{ route('vouchers.receipt.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.receipt.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Receipt Vouchers
            </a>
            <a href="{{ route('vouchers.payment.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.payment.create') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Payment Vouchers
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
          
        </div>
    </div>

   

    {{-- ═══ PARTNERS SECTION ═══ --}}
    <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Partners</p>

    <a href="{{ route('partners.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('partners.*') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Partners
    </a>

    <a href="{{ route('brokers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('brokers.*') ? 'active text-white' : 'text-slate-300' }}">
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Brokerage Management
    </a>

    {{-- ═══ REPORTS SECTION ═══ --}}
    <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports</p>

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
                    'dashboard'  => 'Dashboard & MIS',
                    'availability' => 'Availability Report',
                    'sales' => 'Sales Report',
                    'sales_return' => 'Sales Return Report',
                    'exchange_report' => 'Exchange Report',
                    'emi_collections' => 'EMI & Collections',
                    'customer_ledger' => 'Customer Ledger/Stmt',
                    'cash_book' => 'Consolidated Cash Book',
                    'bank_reports' => 'Bank Reports',
                    'partner_statements' => 'Partner Statements',
                    'loan_schedules' => 'Bank Loan EMI',
                    'profit_loss' => 'Profit & Loss',
                ];
                $currentReport = request('report', 'dashboard');
            @endphp
            @foreach($reportLinks as $key => $label)
                <a href="{{ route('reports.index', ['report' => $key]) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ $currentReport === $key ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ═══ SETTINGS SECTION ═══ --}}
    <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Settings</p>

    <!-- Configurations (Master Setup renamed) -->
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
            <a href="{{ route('gst.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('gst.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                GST
            </a>
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