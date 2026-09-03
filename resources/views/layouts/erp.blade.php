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
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/tabasco.css') }}">
</head>
<body class="h-full bg-slate-50 text-slate-900" 
      x-data="{ 
          sidebarOpen: false, 
          openSettingsModal: false,
          openHeaderSettings: false,
          fontSize: localStorage.getItem('erp-font-size') || '100%'
      }" 
      x-init="
          $watch('fontSize', val => { document.documentElement.style.fontSize = val; localStorage.setItem('erp-font-size', val); }); 
          document.documentElement.style.fontSize = fontSize;
      ">

    <!-- Mobile Sidebar Backdrop (Removed dark transparent overlay) -->

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
        <nav id="sidebar-nav" class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        <!-- 📊 Executive Dashboard -->
        <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('dashboard') ? 'active text-white' : 'text-white/90' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Dashboard
        </a>

        <!-- 🏗️ Projects & Configuration -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Projects & Configuration</p>
        
        <div x-data="{ openProjects: {{ Request::routeIs('projects.*') || Request::routeIs('units.*') || Request::routeIs('partners.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openProjects = !openProjects" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('projects.*') || Request::routeIs('units.*') || Request::routeIs('partners.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/>
                    </svg>
                    <span>Projects & Configuration</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openProjects ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openProjects" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                @if(auth()->user()->hasAnyPermission(['projects.manage', 'projects.view']))
                <a href="{{ route('projects.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('projects.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Projects
                </a>
                @endif
                <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Tower / Block Master
                </a> -->
                <a href="{{ route('units.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('units.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Units (Unit Master)
                </a>
                <a href="{{ route('partners.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('partners.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Partner Management
                </a>
                <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Project Settings & Defaults
                </a> -->
            </div>
        </div>

        <!-- 🏠 Sales & Property Management -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Sales & Booking</p>
        
        <div x-data="{ openSalesProperty: {{ Request::routeIs('sales.*') && request('tab') !== 'sale-return' && request('tab') !== 'exchange' || (Request::routeIs('reports.availability')) || Request::routeIs('cancellation-additional-work.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openSalesProperty = !openSalesProperty" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('sales.*') && request('tab') !== 'sale-return' && request('tab') !== 'exchange' || (Request::routeIs('reports.availability')) || Request::routeIs('cancellation-additional-work.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Sales & Booking</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openSalesProperty ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openSalesProperty" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                
                <a href="{{ route('sales.index', ['action' => 'add']) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('sales.index') && request('action') === 'add' ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    New Booking
                </a>

                <a href="{{ route('sales.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('sales.index') && !request('action') && !request('tab') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Sales Register
                </a>
                <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Agreement Register
                </a> -->
                <a href="{{ route('rate-revision.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('rate-revision.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Unit Rate History
                </a>
                <a href="{{ route('unit-matrix.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('unit-matrix.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Availability Grid
                </a>
            </div>
        </div>

        <!-- 🔄 Property Transfer & Exchange -->
        <div x-data="{ openTransfer: {{ (Request::routeIs('sales.index') && (request('tab') === 'sale-return' || request('tab') === 'exchange')) || Request::routeIs('reports.sales_return') || Request::routeIs('reports.exchange_report') || Request::routeIs('cancellation-additional-work.index') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openTransfer = !openTransfer" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ (Request::routeIs('sales.index') && (request('tab') === 'sale-return' || request('tab') === 'exchange')) || Request::routeIs('reports.sales_return') || Request::routeIs('reports.exchange_report') || Request::routeIs('cancellation-additional-work.index') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Property Transfer & Exchange</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openTransfer ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openTransfer" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('sales.index', ['tab'=>'sale-return']) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'sale-return' ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Sale Return / Cancellation
                </a>
                <a href="{{ route('sales.index', ['tab'=>'exchange']) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ request('tab') === 'exchange' ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Unit Exchange
                </a>
                <a href="{{ route('reports.sales_return') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.sales_return') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Sales Cancel Report
                </a>
                <a href="{{ route('reports.exchange_report') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.exchange_report') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Exchange Report
                </a>
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Customer Resale & Ownership Transfer
                </a>
                <a href="{{ route('cancellation-additional-work.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('cancellation-additional-work.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Cancellation Charges & Additional Work
                </a>
            </div>
        </div>

        <!-- 💳 Customer Management & Collections -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Customer Management & Collections</p>
        
        <div x-data="{ openCustomerColls: {{ Request::routeIs('customers.*') || Request::routeIs('reports.customer_ledger') || Request::routeIs('receipt-management.*') || Request::routeIs('receipts.allocated-to-others') || Request::routeIs('emi-collections.*') || Request::routeIs('reports.emi_collections') || Request::routeIs('cheque-realization.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openCustomerColls = !openCustomerColls" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('customers.*') || Request::routeIs('reports.customer_ledger') || Request::routeIs('receipt-management.*') || Request::routeIs('receipts.allocated-to-others') || Request::routeIs('emi-collections.*') || Request::routeIs('reports.emi_collections') || Request::routeIs('cheque-realization.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Customers & Collections</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openCustomerColls ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openCustomerColls" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('customers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('customers.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Customer Directory
                </a>
                <a href="{{ route('reports.customer_ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.customer_ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Customer Ledger & Statement
                </a>
                <a href="{{ route('emi-collections.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Payment Milestone Schedules
                </a>
                  <!-- <a href="{{ route('receipt-management.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('receipt-management.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Receipt Management
                </a> -->
                <a href="{{ route('cheque-receipt-entry.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('cheque-receipt-entry.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Payment Receipt Entry
                </a>
                <a href="{{ route('cheque-realization.queue') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('cheque-realization.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Cheque Realization Console
                </a>

                <!-- <a href="{{ route('receipts.allocated-to-others') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('receipts.allocated-to-others') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Allocate Payments
                </a>
                <a href="{{ route('emi-collections.cash-book') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('emi-collections.cash-book') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Collections (Cash Book)
                </a> -->
                <a href="{{ route('reports.collection_forecast') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.collection_forecast') || Request::routeIs('emi-collections.outstanding') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Collection Forecast & Overdue Reports
                </a>
            </div>
        </div>

        <!-- 🛠️ Contractor Operations (RA Bills) -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Contractor Operations</p>

        <div x-data="{ openContractors: {{ Request::routeIs('suppliers.*') || Request::routeIs('expenses.ra-bills.*') || Request::routeIs('reports.supplier_contractor') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openContractors = !openContractors" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('suppliers.*') || Request::routeIs('expenses.ra-bills.*') || Request::routeIs('reports.supplier_contractor') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Contractor Operations</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openContractors ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openContractors" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('contractors.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('contractors.*') || Request::routeIs('suppliers.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Contractor
                </a>

                <a href="{{ route('expenses.ra-bills.verification') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.ra-bills.verification') || Request::routeIs('expenses.ra-bills.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    RA Bill Verification
                </a>

                <a href="{{ route('expenses.ra-bills.payment-release') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.ra-bills.payment-release') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Contractor Payment Release
                </a>

                <a href="{{ route('expenses.ra-bills.ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('expenses.ra-bills.ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Contractor Ledger View
                </a>
            </div>
        </div>

        <!-- 🤝 Agents & Brokerage -->
        <div x-data="{ openBrokers: {{ Request::routeIs('brokers.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openBrokers = !openBrokers" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('brokers.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Brokerage</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openBrokers ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openBrokers" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('brokers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('brokers.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Broker Master Directory
                </a>
                <a href="{{ route('brokers.commission-ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('brokers.commission-ledger') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                   Broker Commission Ledger
                </a>
                <a href="{{ route('brokers.payable-report') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('brokers.payable-report') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Broker Payout Release
                </a>
            </div>
        </div>

        <!-- 💵 Petty Cash & Site Expense -->
        <div x-data="{ openPettyCash: {{ Request::routeIs('reports.petty_cash') || Request::routeIs('petty-cash.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openPettyCash = !openPettyCash" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.petty_cash') || Request::routeIs('petty-cash.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Petty Cash & Site Expense</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openPettyCash ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openPettyCash" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('petty-cash.balance-register') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('petty-cash.balance-register') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Petty Cash Balance Register
                </a>
                <a href="{{ route('petty-cash.contra-withdrawal') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('petty-cash.contra-withdrawal') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Bank Cash Withdrawal (Contra)
                </a>
                <a href="{{ route('petty-cash.daily-site-expenses') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('petty-cash.daily-site-expenses') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Daily Site Expenses
                </a>
                <a href="{{ route('reports.petty_cash.reports') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.petty_cash.reports') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Petty Cash Book (report)
                </a>
                <!-- <a href="{{ route('reports.petty_cash') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.petty_cash') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Petty Cash Book (report)
                </a> -->
            </div>
        </div>

        <!-- 📂 Document Management (DMS) -->
        <div x-data="{ openDMS: {{ Request::routeIs('dms.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openDMS = !openDMS" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('dms.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Document Management</span>
                </div>
                <svg :class="openDMS ? 'rotate-90' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openDMS" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('dms.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('dms.index') && !request('upload') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Document Repository
                </a>
                <a href="{{ route('dms.index', ['upload' => 1]) }}" 
                   @if(Request::routeIs('dms.index'))
                       @click.prevent="window.dispatchEvent(new CustomEvent('open-dms-upload'))"
                   @endif
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('dms.index') && request('upload') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Upload Document
                </a>
                <a href="{{ route('dms.categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('dms.categories.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Document Categories
                </a>
                <a href="{{ route('dms.document-types.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('dms.document-types.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Document Types
                </a>
            </div>
        </div>

        <!-- 📑 Approvals Center -->
        <!-- <a href="{{ route('reports.approvals') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 mt-2 text-xs font-semibold rounded-lg hover:text-primary-300 transition-colors {{ Request::routeIs('reports.approvals') ? 'active text-white' : 'text-white/90' }}">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Workflow Approvals
        </a> -->

        <!-- 💰 Project Profitability & Costing -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Finance & Analytics</p>
        
        <div x-data="{ openProfitability: {{ Request::routeIs('reports.partner_statements') || Request::routeIs('reports.project_costing_summary') || Request::routeIs('reports.revenue_cost_breakdown') || Request::routeIs('reports.project_margin_analysis') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openProfitability = !openProfitability" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.partner_statements') || Request::routeIs('reports.project_costing_summary') || Request::routeIs('reports.revenue_cost_breakdown') || Request::routeIs('reports.project_margin_analysis') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <span>Project Profitability & Costing</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openProfitability ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openProfitability" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('reports.partner_statements') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.partner_statements') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Partner Statements
                </a>
                <!-- <a href="{{ route('reports.project_costing_summary') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.project_costing_summary') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Project Costing Summary
                </a>
                <a href="{{ route('reports.revenue_cost_breakdown') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.revenue_cost_breakdown') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Revenue vs. Cost Breakdown
                </a> -->
                <a href="{{ route('reports.project_margin_analysis') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.project_margin_analysis') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Project Margin Analysis
                </a>
            </div>
        </div>

        <!-- 🏦 Bank & Treasury Management -->
        <div x-data="{ openTreasury: {{ Request::routeIs('reports.cash_book') || Request::routeIs('reports.bank_reports') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openTreasury = !openTreasury" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.cash_book') || Request::routeIs('reports.bank_reports') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18v-7m4 7v-7m4 7v-7m4 7v-7M4 10l8-6 8 6"/>
                    </svg>
                    <span>Bank & Treasury Management</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openTreasury ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openTreasury" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('reports.cash_book') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.cash_book') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Cash Book
                </a>
                <a href="{{ route('reports.bank_reports') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.bank_reports') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Bank Reports
                </a>
                <a href="{{ route('vouchers.contra.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.contra.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Internal Contra Transfers
                </a>
                <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Bank Reconciliation (BRS)
                </a> -->
            </div>
        </div>

        <!-- 📈 Accounting & Financial Reports (Restricted) -->
        <div x-data="{ openAccounting: {{ Request::routeIs('reports.trial_balance') || Request::routeIs('reports.profit_loss') || Request::routeIs('reports.balance_sheet') || Request::routeIs('reports.gst_report') || Request::routeIs('reports.customer_ledger') || Request::routeIs('vouchers.ledger.index') || Request::routeIs('reports.audit_trail') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openAccounting = !openAccounting" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.trial_balance') || Request::routeIs('reports.profit_loss') || Request::routeIs('reports.balance_sheet') || Request::routeIs('reports.gst_report') || Request::routeIs('reports.customer_ledger') || Request::routeIs('vouchers.ledger.index') || Request::routeIs('reports.audit_trail') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    <span>Accounting & Financial Reports</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openAccounting ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openAccounting" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                 <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.trial_balance') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Trial Balance
                </a>
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.profit_loss') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Profit & Loss
                </a>
                <a href="{{ route('reports.balance_sheet') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.balance_sheet') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Balance Sheet Summary
                </a>
                <a href="{{ route('reports.gst_report') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.gst_report') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    GST & Tax Report
                </a>
                <a href="{{ route('reports.customer_ledger') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.customer_ledger') || Request::routeIs('vouchers.ledger.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Ledger & Accounts
                </a>
                <!-- <a href="{{ route('reports.trial_balance') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.trial_balance') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Trial Balance
                </a>
                <a href="{{ route('reports.profit_loss') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.profit_loss') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Profit & Loss
                </a>
                <a href="{{ route('reports.balance_sheet') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.balance_sheet') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Balance Sheet Summary
                </a>
                <a href="{{ route('reports.gst_report') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.gst_report') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    GST & Tax Report
                </a>
                <a href="{{ route('vouchers.ledger.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('vouchers.ledger.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Ledger & Accounts
                </a> -->
                <!-- <a href="{{ route('reports.audit_trail') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.audit_trail') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Audit Trail Log
                </a> -->
            </div>
        </div>

        <!-- 💸 Loans & Debt Servicing -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Loans & Debt Servicing</p>
        
        <div x-data="{ openLoansDebt: {{ Request::routeIs('loans.index') || Request::routeIs('loans.schedule') || Request::routeIs('loans.reports') || Request::routeIs('loan-disbursals.*') || Request::routeIs('bank.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openLoansDebt = !openLoansDebt" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('loans.index') || Request::routeIs('loans.schedule') || Request::routeIs('loans.reports') || Request::routeIs('loan-disbursals.*') || Request::routeIs('bank.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Loans & Debt Servicing</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openLoansDebt ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openLoansDebt" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('bank.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('bank.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Bank Loan Master
                </a>
                <a href="{{ route('loan-disbursals.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('loan-disbursals.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Loan Disbursal Entry
                </a>
                <a href="{{ route('loans.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('loans.index') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    EMI & Interest Payment Release
                </a>
                <a href="{{ route('loans.reports') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('loans.reports') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Loan Outstanding Summary
                </a>
            </div>
        </div>

        <!-- 📊 Reports & Analytics -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Reports & Analytics</p>
        
        <div x-data="{ openReports: {{ Request::routeIs('reports.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="openReports = !openReports" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('reports.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                    <span>Reports & Analytics</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openReports ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openReports" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <!-- @php
                    $reportLinks = [
                        'dashboard'           => 'Executive Dashboard',
                        'availability'        => 'Availability Report',
                        'sales'               => 'Sales Report',
                        'emi_collections'     => 'EMI & Collection Reports',
                        'gst_report'          => 'GST & Tax Report',
                        'customer_ledger'     => 'Customer Ledger / Account Stmt',
                        'cash_book'           => 'Cash Book',
                        'bank_reports'        => 'Bank Reports',
                        'partner_statements'  => 'Partner Statements',
                        'supplier_contractor' => 'Supplier & Contractor Stmt',
                        'sales_return'        => 'Sales Cancel Report',
                        'exchange_report'     => 'Exchange Report',
                        'petty_cash'          => 'Petty Cash Book',
                        'loan_schedules'      => 'Bank Loan EMI Schedules',
                        'trial_balance'       => 'Trial Balance',
                        'profit_loss'         => 'Profit & Loss',
                        'balance_sheet'       => 'Balance Sheet Summary',
                        'audit_trail'         => 'Audit Trail Log',
                        'approvals'           => 'Workflow Approvals',
                    ];
                @endphp -->
               @php
    $reportLinks = [
        'dashboard'           => 'Executive Dashboard',
        'sales'               => 'Sales Report',
        'emi_collections'     => 'EMI & Collection Reports',
        'supplier_contractor' => 'Contractor Statement',
    ];
@endphp
                @foreach($reportLinks as $key => $label)
                    <a href="{{ route('reports.' . $key) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.' . $key) ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('reports.loan_schedules') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.loan_schedules') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Bank loan EMI schedules
                </a>
                <a href="{{ route('reports.availability') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('reports.availability') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Availability Report
                </a>
            </div>
        </div>

        <!-- ⚙️ System Settings & Administration -->
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-white/60 uppercase tracking-widest">Administration</p>
        
        <!-- Master Configuration -->
        <div x-data="{ openMaster: {{ Request::routeIs('engineers.*') || Request::routeIs('chart-of-accounts.*') || Request::routeIs('voucher-types.*') || Request::routeIs('bank.*') || Request::routeIs('payment-modes.*') || Request::routeIs('cheque-statuses.*') || Request::routeIs('floors.*') || Request::routeIs('unit-types.*') || Request::routeIs('employees.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openMaster = !openMaster" class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('engineers.*') || Request::routeIs('chart-of-accounts.*') || Request::routeIs('voucher-types.*') || Request::routeIs('bank.*') || Request::routeIs('payment-modes.*') || Request::routeIs('cheque-statuses.*') || Request::routeIs('floors.*') || Request::routeIs('unit-types.*') || Request::routeIs('employees.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <span>Master</span>
                </div>
                <svg class="w-3.5 h-3.5 transition-transform duration-250" :class="openMaster ? 'transform rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="openMaster" x-transition.opacity class="pl-8 space-y-1" style="display: none;">
                <a href="{{ route('chart-of-accounts.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('chart-of-accounts.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Chart of Accounts
                </a>
                <a href="{{ route('voucher-types.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('voucher-types.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Voucher Types
                </a>
                <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 text-white/80 hover:bg-slate-800 hover:text-white">
                    Opening Balance Migration Tool
                </a> -->
                <a href="{{ route('employees.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('employees.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Employee Master
                </a>
                <a href="{{ route('engineers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('engineers.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Engineer
                </a>
                <a href="{{ route('company-bank-accounts.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('company-bank-accounts.*') ? 'bg-[#a38c29] text-white shadow-md font-bold active' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Company Bank Accounts
                </a>
                <a href="{{ route('cheque-statuses.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('cheque-statuses.*') ? 'bg-[#a38c29] text-white shadow-md font-bold active' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Cheque Status
                </a>
                
                <a href="{{ route('floors.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('floors.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Floor
                </a>
                <a href="{{ route('unit-types.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-semibold transition-all duration-200 {{ Request::routeIs('unit-types.*') ? 'bg-[#a38c29] text-white shadow-md font-bold' : 'text-white/80 hover:bg-slate-800 hover:text-white' }}">
                    Unit Type
                </a>
            </div>
        </div>

        <div class="pt-1 mt-2 space-y-1">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg hover:text-primary-300 hover:bg-slate-800/30 transition-all {{ Request::routeIs('admin.users.*') ? 'text-white bg-slate-800/20' : 'text-white/90' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users & Roles</span>
            </a>
        </div>

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
                <!-- Header Settings Dropdown Menu -->
                <div class="relative" x-data="{ openHeaderSettings: false }" @click.outside="openHeaderSettings = false">
                    <button @click="openHeaderSettings = !openHeaderSettings" 
                            type="button" 
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold transition-all border border-slate-200 shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                        <span>Settings</span>
                        <svg class="w-3 h-3 text-slate-400 transition-transform" :class="openHeaderSettings ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Settings Dropdown Popover -->
                    <div x-show="openHeaderSettings" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-72 rounded-2xl bg-white p-4 shadow-xl border border-slate-200 z-50 space-y-4"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="border-b border-slate-100 pb-2">
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span> System Settings
                            </h4>
                            <p class="text-[10px] text-slate-500 font-medium">Display Preferences & Appearance</p>
                        </div>

                        <!-- 1. Font Size Adjustment Option -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                                <span>Font Size</span>
                                <span class="text-[9px] font-mono text-slate-400" x-text="fontSize"></span>
                            </label>
                            <div class="grid grid-cols-3 gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200">
                                <button type="button" @click="fontSize = '100%'" 
                                        :class="fontSize === '100%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-500 hover:text-slate-800'"
                                        class="py-1.5 text-center text-xs font-bold rounded-lg transition-all" title="Normal 100%">
                                    A <span class="block text-[9px] font-normal opacity-75">Normal</span>
                                </button>
                                <button type="button" @click="fontSize = '112.5%'" 
                                        :class="fontSize === '112.5%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-500 hover:text-slate-800'"
                                        class="py-1.5 text-center text-xs font-bold rounded-lg transition-all" title="Medium 112.5%">
                                    A+ <span class="block text-[9px] font-normal opacity-75">Medium</span>
                                </button>
                                <button type="button" @click="fontSize = '125%'" 
                                        :class="fontSize === '125%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-500 hover:text-slate-800'"
                                        class="py-1.5 text-center text-xs font-bold rounded-lg transition-all" title="Large 125%">
                                    A++ <span class="block text-[9px] font-normal opacity-75">Large</span>
                                </button>
                            </div>
                        </div>

                   

                        <!-- Quick Administration Links -->
                        <div class="border-t border-slate-100 pt-2 space-y-1">
                            <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest px-1">Quick Configs</span>
                            <a href="{{ route('floors.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50 rounded-lg font-semibold transition">
                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"/></svg>
                                Master Configuration
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-50 rounded-lg font-semibold transition">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Users & Roles Settings
                            </a>
                        </div>
                    </div>
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
            @if (session('status') || session('success'))
            @php
                $statusText = session('status') ?? session('success');
                $modalTitle = 'Success!';
                $modalBadge = 'Action Completed';
                $modalSubtitle = 'Operation completed successfully.';

                $lowerText = strtolower($statusText);

                if (str_contains($lowerText, 'project')) {
                    if (str_contains($lowerText, 'create') || str_contains($lowerText, 'add')) {
                        $modalTitle = 'Project Added!';
                        $modalBadge = 'New Project Created';
                        $modalSubtitle = 'The new project has been added successfully.';
                    } elseif (str_contains($lowerText, 'delete')) {
                        $modalTitle = 'Project Deleted!';
                        $modalBadge = 'Project Removed';
                        $modalSubtitle = 'The project has been deleted successfully.';
                    } elseif (str_contains($lowerText, 'update') || str_contains($lowerText, 'edit')) {
                        $modalTitle = 'Project Updated!';
                        $modalBadge = 'Project Modified';
                        $modalSubtitle = 'Project specifications updated successfully.';
                    }
                } elseif (str_contains($lowerText, 'broker')) {
                    if (str_contains($lowerText, 'create') || str_contains($lowerText, 'register') || str_contains($lowerText, 'add') || str_contains($lowerText, 'save')) {
                        $modalTitle = 'Broker Registered!';
                        $modalBadge = 'Broker Profile Created';
                        $modalSubtitle = 'Broker profile registered successfully.';
                    } elseif (str_contains($lowerText, 'delete')) {
                        $modalTitle = 'Broker Deleted!';
                        $modalBadge = 'Broker Profile Removed';
                        $modalSubtitle = 'Broker profile deleted successfully.';
                    } elseif (str_contains($lowerText, 'update') || str_contains($lowerText, 'edit')) {
                        $modalTitle = 'Broker Updated!';
                        $modalBadge = 'Broker Profile Modified';
                        $modalSubtitle = 'Broker profile updated successfully.';
                    }
                } elseif (str_contains($lowerText, 'bank')) {
                    if (str_contains($lowerText, 'create') || str_contains($lowerText, 'add')) {
                        $modalTitle = 'Bank Account Added!';
                        $modalBadge = 'Bank Configured';
                        $modalSubtitle = 'Corporate bank account added successfully.';
                    } elseif (str_contains($lowerText, 'delete')) {
                        $modalTitle = 'Bank Account Deleted!';
                        $modalBadge = 'Bank Account Removed';
                        $modalSubtitle = 'Corporate bank account deleted successfully.';
                    } elseif (str_contains($lowerText, 'update') || str_contains($lowerText, 'edit')) {
                        $modalTitle = 'Bank Account Updated!';
                        $modalBadge = 'Bank Account Modified';
                        $modalSubtitle = 'Corporate bank account updated successfully.';
                    }
                } elseif (str_contains($lowerText, 'payment mode')) {
                    if (str_contains($lowerText, 'create') || str_contains($lowerText, 'add')) {
                        $modalTitle = 'Payment Mode Created!';
                        $modalBadge = 'Payment Mode Added';
                        $modalSubtitle = 'Payment mode registered successfully.';
                    } elseif (str_contains($lowerText, 'delete')) {
                        $modalTitle = 'Payment Mode Deleted!';
                        $modalBadge = 'Payment Mode Removed';
                        $modalSubtitle = 'Payment mode deleted successfully.';
                    } elseif (str_contains($lowerText, 'update') || str_contains($lowerText, 'edit') || str_contains($lowerText, 'status')) {
                        $modalTitle = 'Payment Mode Updated!';
                        $modalBadge = 'Payment Mode Modified';
                        $modalSubtitle = 'Payment mode updated successfully.';
                    }
                }
            @endphp
            {{-- ═══════ PROFESSIONAL SUCCESS MODAL ═══════ --}}
            <div id="statusSuccessModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 pointer-events-none" style="background: transparent;">
                <div id="statusModalCard" class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden pointer-events-auto"
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
                                <p class="text-xs font-extrabold text-slate-900 mt-0.5">{{ $statusText }}</p>
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

            {!! $slot ?? '' !!}
            @yield('content')
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

        window.updateAmountInWordsForInput = function(el) {
            if (!el || el.tagName !== 'INPUT') return;
            const type = (el.type || '').toLowerCase();
            if (type !== 'number' && type !== 'text') return;

            let xModel = (el.getAttribute('x-model') || el.getAttribute('x-model.number') || '').toLowerCase();
            const name = (el.getAttribute('name') || '').toLowerCase();
            const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();

            // Exclude sqft and agreed total
            if (xModel.includes('sqft') || name.includes('sqft') || placeholder.includes('sqft') || xModel.includes('sale_amount')) {
                return;
            }

            const isAmount = xModel.includes('amount') || name.includes('amount') || placeholder.includes('amount') || name === 'debit' || name === 'credit' || name.includes('debit') || name.includes('credit');
            if (!isAmount) return;

            // Target the field container directly below the input box (climb past relative/horizontal flex containers)
            let targetParent = el.parentNode;
            while (targetParent && (
                targetParent.classList.contains('relative') || 
                targetParent.classList.contains('flex-1') || 
                (targetParent.classList.contains('flex') && !targetParent.classList.contains('flex-col')) || 
                targetParent.classList.contains('h-9') || 
                targetParent.classList.contains('h-10') || 
                targetParent.classList.contains('h-11') || 
                targetParent.classList.contains('shadow-sm')
            )) {
                if (!targetParent.parentElement || targetParent.tagName === 'TD' || targetParent.tagName === 'BODY') break;
                targetParent = targetParent.parentElement;
            }

            let wordsLabel = targetParent ? targetParent.querySelector('.amount-in-words-label') : null;
            const words = window.convertNumberToWords(el.value);

            if (!wordsLabel && targetParent && words) {
                wordsLabel = document.createElement('div');
                wordsLabel.className = 'amount-in-words-label text-[10px] text-amber-800 font-extrabold capitalize mt-1.5 px-2.5 py-1 rounded-lg bg-amber-50/90 border border-amber-200/80 tracking-wide transition-all leading-snug break-words block w-full shadow-xs';
                targetParent.appendChild(wordsLabel);
            }

            if (wordsLabel) {
                if (words) {
                    wordsLabel.textContent = words;
                    wordsLabel.style.display = 'block';
                } else {
                    wordsLabel.style.display = 'none';
                }
            }
        };

        // Update all amount inputs with their text representation
        window.updateAllAmountInWords = function() {
            document.querySelectorAll('input[type="number"], input[type="text"]').forEach(el => {
                window.updateAmountInWordsForInput(el);
            });
        };

        // Event delegation for text updates and decimal limits
        document.addEventListener('input', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT') {
                let xModel = (el.getAttribute('x-model') || el.getAttribute('x-model.number') || '').toLowerCase();
                const name = (el.getAttribute('name') || '').toLowerCase();
                const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();
                const id = (el.getAttribute('id') || '').toLowerCase();
                
                // 1. Handle GST & Percentage inputs limit
                if (!xModel.includes('sqft') && !name.includes('sqft') && !placeholder.includes('sqft') && !xModel.includes('amount') && !name.includes('amount') && !placeholder.includes('amount') && !name.includes('gstin') && !xModel.includes('gstin') && !placeholder.includes('gstin') && !xModel.includes('reason') && !name.includes('reason') && !name.includes('state') && !name.includes('emirate') && !placeholder.includes('state') && !placeholder.includes('emirate')) {
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
                        
                        // Update in-words label with full-width grid alignment
                        window.updateAmountInWordsForInput(el);
                    }
                }
            }
        });

        // Strictly block non-numeric characters (letters, e, E, +, -, symbols) on amount fields during keydown
        document.addEventListener('keydown', function(e) {
            const el = e.target;
            if (el && el.tagName === 'INPUT') {
                const xModel = (el.getAttribute('x-model') || el.getAttribute('x-model.number') || '').toLowerCase();
                const name = (el.getAttribute('name') || '').toLowerCase();
                const placeholder = (el.getAttribute('placeholder') || '').toLowerCase();
                const type = (el.type || '').toLowerCase();

                if ((type === 'number' || type === 'text') && !xModel.includes('sqft') && !name.includes('sqft') && !placeholder.includes('sqft')) {
                    if (xModel.includes('amount') || name.includes('amount') || placeholder.includes('amount') || name === 'debit' || name === 'credit' || name.includes('debit') || name.includes('credit')) {
                        // Allow navigation and edit control keys (Backspace, Tab, Enter, Escape, Delete, Arrows, Home, End, Ctrl/Cmd shortcuts)
                        if (e.ctrlKey || e.metaKey || ['Backspace', 'Tab', 'Enter', 'Escape', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(e.key)) {
                            return;
                        }
                        // Allow numbers 0-9
                        if (/^[0-9]$/.test(e.key)) {
                            return;
                        }
                        // Allow a single decimal point if not already present
                        if (e.key === '.' && !el.value.includes('.')) {
                            return;
                        }
                        // Prevent typing any letters, symbols, e, E, +, -
                        e.preventDefault();
                    }
                }
            }
        }, true);

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
            
            // Exclude gstin fields, reason fields, and state/emirate to allow alphanumeric input
            if (name.includes('gstin') || xModel.includes('gstin') || placeholder.includes('gstin') || id.includes('gstin') || name.includes('reason') || xModel.includes('reason') || placeholder.includes('reason') || name.includes('state') || placeholder.includes('state') || name.includes('emirate') || placeholder.includes('emirate')) {
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
                'bold', 'italic', '|',
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
    <!-- System Settings Modal -->
    <div x-show="openSettingsModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;">
        <div @click.outside="openSettingsModal = false" 
             class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden space-y-6 p-6">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-[#a38c29]/15 text-[#a38c29] rounded-2xl border border-[#a38c29]/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider">System Settings</h3>
                        <p class="text-xs text-slate-500 font-medium">Font size, theme & display preferences</p>
                    </div>
                </div>
                <button @click="openSettingsModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Font Size Options -->
            <div class="space-y-2">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider flex items-center justify-between">
                    <span>Font Size Customization</span>
                    <span class="text-xs font-mono text-[#a38c29]" x-text="fontSize"></span>
                </label>
                <div class="grid grid-cols-3 gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                    <button type="button" @click="fontSize = '100%'" 
                            :class="fontSize === '100%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2.5 text-center text-xs font-bold rounded-xl transition-all" title="Normal 100%">
                        A <span class="block text-[10px] font-normal opacity-75">Normal</span>
                    </button>
                    <button type="button" @click="fontSize = '112.5%'" 
                            :class="fontSize === '112.5%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2.5 text-center text-xs font-bold rounded-xl transition-all" title="Medium 112.5%">
                        A+ <span class="block text-[10px] font-normal opacity-75">Medium</span>
                    </button>
                    <button type="button" @click="fontSize = '125%'" 
                            :class="fontSize === '125%' ? 'bg-white text-slate-900 font-black shadow-xs border border-slate-200' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2.5 text-center text-xs font-bold rounded-xl transition-all" title="Large 125%">
                        A++ <span class="block text-[10px] font-normal opacity-75">Large</span>
                    </button>
                </div>
            </div>

           

            <div class="flex justify-end border-t border-slate-100 pt-4">
                <button type="button" @click="openSettingsModal = false" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl transition">
                    Done
                </button>
            </div>
    <!-- Persistent Sidebar Scroll & Active Menu Alignment Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarNav = document.getElementById('sidebar-nav') || document.querySelector('aside nav');
            if (!sidebarNav) return;

            // Track scroll position in sessionStorage
            sidebarNav.addEventListener('scroll', function() {
                sessionStorage.setItem('erp_sidebar_scroll', sidebarNav.scrollTop);
            });

            sidebarNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    sessionStorage.setItem('erp_sidebar_scroll', sidebarNav.scrollTop);
                });
            });

            function alignActiveSidebarItem() {
                const activeItem = sidebarNav.querySelector('.active') || 
                                   sidebarNav.querySelector('[class*="bg-[#a38c29]"]');
                
                if (activeItem) {
                    // Ensure active item's parent container is visible if hidden
                    const parentGroup = activeItem.closest('[x-data]');
                    if (parentGroup && parentGroup.querySelector('[x-show]')) {
                        const hiddenChild = parentGroup.querySelector('[x-show]');
                        if (hiddenChild) hiddenChild.style.display = 'block';
                    }

                    // Pin active item in center of sidebar
                    activeItem.scrollIntoView({ block: 'center', behavior: 'instant' });
                } else {
                    const savedScroll = sessionStorage.getItem('erp_sidebar_scroll');
                    if (savedScroll !== null) {
                        sidebarNav.scrollTop = parseInt(savedScroll, 10);
                    }
                }
            }

            alignActiveSidebarItem();
            setTimeout(alignActiveSidebarItem, 100);
            setTimeout(alignActiveSidebarItem, 300);
        });
    </script>
</body>
</html>
