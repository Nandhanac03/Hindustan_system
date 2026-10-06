@extends('layouts.erp')

@section('title', 'Broker Commission Ledger & Directory')

@section('content')
<style>
/* CRITICAL: @page must be defined at the stylesheet root for Chromium/WebKit to honor landscape mode */
@page {
    size: landscape;
    margin: 0;
}

@media screen {
    .print-exec-header,
    .print-exec-banner {
        display: none !important;
    }
}

@media print {
    @page {
        size: landscape;
        margin: 0;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 7.5pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 6mm 8mm !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 1. Complete Web Clutter & Sidebar Removal */
    .web-only-workspace,
    .print\:hidden,
    [class*="print:hidden"],
    header, nav, aside, footer, button, select, input, 
    .custom-scrollbar::-webkit-scrollbar, [class*="nav"], [class*="sidebar"] {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        max-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        overflow: hidden !important;
    }

    /* 2. Reset ERP Layout Parent Flex Containers & Left Sidebar Padding */
    div.lg\:pl-72, [class*="lg:pl-72"], [class*="pl-72"] {
        padding-left: 0 !important;
        margin-left: 0 !important;
        width: 100% !important;
        min-height: auto !important;
        height: auto !important;
        display: block !important;
        flex: none !important;
    }
    main, .flex-1.p-6, main.flex-1 {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: auto !important;
        height: auto !important;
        display: block !important;
        flex: none !important;
    }

    /* 3. Executive Corporate Letterhead Header */
    .print-exec-header {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding-bottom: 10px !important;
        border-bottom: 2px solid #a38c29 !important;
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        background: transparent !important;
    }

    /* 4. Top Golden Summary Banner */
    .print-exec-banner {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding: 10px 16px !important;
        border: 1px solid #e6d594 !important;
        border-radius: 10px !important;
        background: #fffdf5 !important;
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        box-sizing: border-box !important;
    }

    /* 5. Table Container */
    .ledger-table-card {
        display: block !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }

    .ledger-table-header-box {
        display: block !important;
        padding: 8px 12px !important;
        margin-top: 4px !important;
        margin-bottom: 8px !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        page-break-inside: avoid !important;
        break-inside: auto !important;
        box-sizing: border-box !important;
    }

    .overflow-x-auto {
        display: block !important;
        overflow: visible !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }

    /* 6. Table Formatting */
    table.ledger-print-table {
        display: table !important;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: auto !important;
        border-collapse: collapse !important;
        font-size: 7.2pt !important;
        margin: 0 !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table.ledger-print-table thead {
        display: table-header-group !important;
    }
    table.ledger-print-table thead tr {
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }
    table.ledger-print-table thead th {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        font-size: 6.8pt !important;
        font-weight: 800 !important;
        padding: 7px 5px !important;
        border: 0.5pt solid #8a7522 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        letter-spacing: 0.25px !important;
        text-transform: uppercase !important;
        box-sizing: border-box !important;
    }
    table.ledger-print-table tbody {
        display: table-row-group !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table.ledger-print-table tbody tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table.ledger-print-table tbody td {
        padding: 5.5px 5px !important;
        font-size: 7pt !important;
        border: 0.5pt solid #e2e8f0 !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
        line-height: 1.35 !important;
    }
    table.ledger-print-table tbody tr:nth-child(even) td {
        background-color: #F6F3E9 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    table.ledger-print-table tfoot {
        display: table-footer-group !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table.ledger-print-table tfoot td {
        padding: 7px 5px !important;
        font-size: 7.2pt !important;
        background-color: #f8fafc !important;
        border-top: 1.5pt solid #a38c29 !important;
        border-bottom: 1.5pt solid #a38c29 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: 800 !important;
    }

    /* Reset font size on child elements inside table */
    table.ledger-print-table td div, 
    table.ledger-print-table td span {
        font-size: inherit;
    }
    table.ledger-print-table td .text-\[10px\], 
    table.ledger-print-table td .text-\[9px\] {
        font-size: 6pt !important;
    }
    table.ledger-print-table td .rounded-full, 
    table.ledger-print-table td .rounded {
        font-size: 5.5pt !important;
        padding: 1px 3px !important;
        line-height: 1 !important;
    }

    /* Hide Action column in print */
    .col-action, th.col-action, td.col-action {
        display: none !important;
        width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
}
</style>

<div x-data="brokerCommissionLedger()" class="space-y-6">

    <!-- ── 1. EXECUTIVE PRINT HEADER ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Corporate Brokerage & Commission Management</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">BROKER COMMISSION ACCOUNT STATEMENT & LEDGER</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Broker Scope:</span> <span class="font-bold text-[#a38c29]" x-text="getSelectedBrokerName()"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Period Filter:</span> <span class="font-mono font-bold text-slate-800">Beginning &rarr; Today</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredLedgerEntries().length + ' Entries'"></span></div>
            </div>
        </div>
    </div>

    <!-- ── 2. EXECUTIVE FINANCIAL SUMMARY BANNER ── -->
    <div class="reports-banner-container print-exec-banner hidden print:flex items-center justify-between gap-4 p-4 rounded-xl border border-[#e6d594] bg-[#fffdf5] mb-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#8a7522] shadow-2xs shrink-0">
                <svg class="w-5 h-5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Broker Commission Statement Ledger</h3>
                    <span class="text-[9px] font-bold text-[#8a7522] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Audit Trail</span>
                </div>
                <p class="text-[9.5px] text-slate-600 mt-1 font-medium">Consolidated real-time audit ledger of accrued brokerage commissions, payout disbursements, and running payable balances.</p>
            </div>
        </div>

        {{-- Right Side: Stat Tiles --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <div class="px-3.5 py-2 bg-blue-50 border border-blue-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-blue-700">Total Accrued</span>
                <span class="text-xs font-black text-blue-900 font-mono" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></span>
            </div>
            <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Disbursed</span>
                <span class="text-xs font-black text-emerald-900 font-mono" x-text="'₹' + numberFormat(getLedgerTotals().paid)"></span>
            </div>
            <div class="px-3.5 py-2 bg-rose-50 border border-rose-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-rose-700">Outstanding Balance</span>
                <span class="text-xs font-black text-rose-900 font-mono" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></span>
            </div>
            <div class="px-3.5 py-2 bg-[#a38c29]/10 border border-[#a38c29]/25 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-[#8a7522]">Master Brokers</span>
                <span class="text-xs font-black text-[#5c4a10] font-mono">{{ count($brokers) }} Accounts</span>
            </div>
        </div>
    </div>

    <!-- ── 3. WEB-ONLY WORKSPACE (Top Breadcrumb Bar, KPI Cards, Filter Toolbar) ── -->
    <div class="web-only-workspace space-y-6 print:hidden">

        <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="/" class="hover:text-slate-600 transition">HOME</a>
                    <span>›</span>
                    <span>BROKERAGE & COMMISSIONS</span>
                    <span>›</span>
                    <span class="text-[#a38c29] font-bold">BROKER COMMISSION LEDGER</span>
                </nav>
                <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Broker Statement & Commission Ledger</span>
                </h1>
            </div>

            <!-- <div class="flex items-center gap-2.5">
                <a href="{{ route('brokers.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                    <span>+ Broker Master</span>
                </a>
            </div> -->
        </div>

        <!-- ── SUCCESS & ERROR ALERTS ── -->
        @if(session('status') || session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') ?? session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
            </div>
        @endif

        <!-- ── 4 KPI SUMMARY CARDS (Placed directly below Header) ── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Net Claims Accrued -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL COMMISSION ACCRUED</span>
                    <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Verified Brokerage Liability</div>
                </div>
            </div>

            <!-- Card 2: Total Disbursements Released -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DISBURSEMENTS RELEASED</span>
                    <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().paid)"></div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Paid Outflow via Treasury</div>
                </div>
            </div>

            <!-- Card 3: Outstanding Ledger Balance -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">OUTSTANDING LEDGER BALANCE</span>
                    <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Payable Remaining</div>
                </div>
            </div>

            <!-- Card 4: Registered Brokers -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">REGISTERED BROKERS</span>
                    <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight group-hover:text-[#8a7522] transition-colors">{{ count($brokers) }} Payees</div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Master Accounts Linked</div>
                </div>
            </div>
        </div>

        <!-- ── SEARCH & FILTER PANEL (Theme Color Popovers for all dropdowns, No Blue Browser Dropdowns) ── -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 flex-1">
                    
                    {{-- 1. Searchable Broker Filter (Custom Gold Popover) --}}
                    <div class="relative" @click.outside="brokerFilterOpen = false">
                        <div @click="brokerFilterOpen = !brokerFilterOpen; if(brokerFilterOpen) { projectFilterOpen = false; statusFilterOpen = false; brokerFilterSearch = ''; $nextTick(() => $refs.brokerFilterSearchInput?.focus()); }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="brokerFilterOpen || selectedLedgerBrokerId ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="brokerFilterOpen || selectedLedgerBrokerId ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="selectedLedgerBrokerId ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSelectedBrokerName()"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="brokerFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Broker Popover Menu --}}
                        <div x-show="brokerFilterOpen" x-transition
                             class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                             style="display: none;">
                            <div class="p-2 bg-[#a38c29]/10 border-b border-[#a38c29]/20 sticky top-0 z-10">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#a38c29]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text"
                                           x-model="brokerFilterSearch"
                                           x-ref="brokerFilterSearchInput"
                                           placeholder="Search broker name..."
                                           class="w-full pl-8 pr-3 py-1.5 bg-white border border-[#a38c29]/40 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/30 rounded-lg text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none transition-all">
                                </div>
                            </div>
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="selectedLedgerBrokerId = ''; brokerFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!selectedLedgerBrokerId ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>All Brokers</span>
                                    </span>
                                    <span class="text-[9.5px] font-medium" :class="!selectedLedgerBrokerId ? 'text-white/80' : 'text-slate-400'">({{ count($brokers) }} registered)</span>
                                </div>
                                <template x-for="b in getFilteredBrokersList(brokerFilterSearch)" :key="b.id">
                                    <div @click="selectedLedgerBrokerId = String(b.id); brokerFilterOpen = false"
                                         class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                         :class="String(selectedLedgerBrokerId) === String(b.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                        <span x-text="b.name"></span>
                                        <span class="px-2 py-0.5 rounded text-[9.5px] font-mono font-black shrink-0 ml-2"
                                              :class="String(selectedLedgerBrokerId) === String(b.id) ? 'bg-[#a38c29] text-white' : 'bg-[#a38c29]/10 text-[#8a7522]'"
                                              x-text="b.default_commission_pct ? b.default_commission_pct + '%' : ''">
                                        </span>
                                    </div>
                                </template>
                                <div x-show="getFilteredBrokersList(brokerFilterSearch).length === 0" class="px-3.5 py-4 text-center text-slate-400 text-xs italic">
                                    No brokers found matching query
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Project Filter (Custom Gold Popover - First Project Selected by Default) --}}
                    <div class="relative" @click.outside="projectFilterOpen = false">
                        <div @click="projectFilterOpen = !projectFilterOpen; if(projectFilterOpen) { brokerFilterOpen = false; statusFilterOpen = false; }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="projectFilterOpen || selectedProjectId ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="projectFilterOpen || selectedProjectId ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="selectedProjectId ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSelectedProjectName()"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="projectFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Project Popover Menu --}}
                        <div x-show="projectFilterOpen" x-transition
                             class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                             style="display: none;">
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="selectedProjectId = ''; projectFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!selectedProjectId ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span>All Projects</span>
                                </div>
                                @foreach($projects as $proj)
                                    <div @click="selectedProjectId = '{{ $proj->id }}'; projectFilterOpen = false"
                                         class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                         :class="String(selectedProjectId) === '{{ $proj->id }}' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                        <span>{{ $proj->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 3. Status Filter (Custom Gold Popover - Theme Color without blue browser dropdown) --}}
                    <div class="relative" @click.outside="statusFilterOpen = false">
                        <div @click="statusFilterOpen = !statusFilterOpen; if(statusFilterOpen) { brokerFilterOpen = false; projectFilterOpen = false; }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="statusFilterOpen || selectedStatus ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="statusFilterOpen || selectedStatus ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="selectedStatus ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSelectedStatusName()"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="statusFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Status Popover Menu (Golden theme) --}}
                        <div x-show="statusFilterOpen" x-transition
                             class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                             style="display: none;">
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="selectedStatus = ''; statusFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!selectedStatus ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span>All Statuses (Pending, Partial, Fully Paid)</span>
                                </div>
                                <div @click="selectedStatus = 'pending'; statusFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="selectedStatus === 'pending' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Pending (Unpaid / Payable Share)</span>
                                </div>
                                <div @click="selectedStatus = 'partial'; statusFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="selectedStatus === 'partial' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Partially Paid</span>
                                </div>
                                <div @click="selectedStatus = 'paid'; statusFilterOpen = false"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="selectedStatus === 'paid' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Fully Paid / Disbursed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Reset Filters Button --}}
                <div class="shrink-0 flex items-center">
                    <button type="button" @click="resetFilters()"
                            class="h-[38px] px-5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer whitespace-nowrap group active:scale-95">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </button>
                </div>

            </div>
        </div>

    </div>

    <!-- ── 4. BROKER COMMISSION & DEALS LEDGER TABLE CARD ── -->
    <div class="ledger-table-card bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-6 print:mt-0">
        
        {{-- Table Header Box (Corporate Style) --}}
        <div class="ledger-table-header-box px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 print:hidden">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest print:text-[8.5pt]">Broker Commission Deals & Audit Ledger</h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center gap-1.5 flex-wrap print:text-[6.2pt] print:text-slate-600">
                        <span>Showing <strong class="text-slate-800 font-bold" x-text="filteredDeals().length"></strong> deals</span>
                        <span x-show="selectedLedgerBrokerId">for <strong class="text-[#8a7522] font-bold" x-text="getSelectedBrokerName()"></strong></span>
                        <span x-show="!selectedLedgerBrokerId">across <strong class="text-slate-700 font-bold">all registered brokers</strong></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto print:hidden">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Filtered Deals:</span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-50 border border-slate-200 text-slate-800 shadow-2xs"
                      x-text="filteredDeals().length + ' Deals'">
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="ledger-print-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] font-extrabold uppercase text-[10px] tracking-wider text-left">
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap">BOOKING & DATE</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap">PROPERTY / PROJECT</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap">BROKER / AGENT</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap">NET SALE VALUE</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap min-w-[170px]">SALE EMI PROGRESS</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] whitespace-nowrap">COMMISSION</th>
                        <th class="py-3.5 px-4 border-r border-[#8a7522] text-center whitespace-nowrap min-w-[180px]">COMMISSION STATUS</th>
                        <th class="py-3.5 px-4 text-center border-r border-[#8a7522] col-action print:hidden whitespace-nowrap">ACTION</th>
                    </tr>
                </thead>
                <template x-for="deal in filteredDeals()" :key="deal.id">
                    <tbody class="divide-y divide-slate-100 bg-white text-[11px] font-semibold text-slate-700">
                        <!-- Main Deal Row -->
                        <tr class="transition-colors border-b border-slate-200/80 hover:bg-slate-50/70 cursor-pointer"
                            @click="expandedDeal === deal.id ? expandedDeal = null : expandedDeal = deal.id">
                            
                            <!-- 1. Booking & Date -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle">
                                <div class="font-extrabold text-[#a38c29] text-xs font-mono tracking-tight" x-text="deal.sale_number"></div>
                                <div class="text-[11px] text-slate-500 font-semibold mt-0.5" x-text="deal.sale_date_formatted"></div>
                                <div class="text-[10px] text-slate-700 font-bold uppercase tracking-wider mt-0.5 truncate max-w-[170px]" x-text="deal.customer_name"></div>
                            </td>

                            <!-- 2. Property / Project -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle">
                                <div class="font-extrabold text-slate-900 text-xs leading-snug" x-text="deal.project_name"></div>
                                <div class="text-[11px] text-slate-500 font-semibold mt-0.5" x-text="deal.unit_name ? 'Unit: ' + deal.unit_name : '—'"></div>
                            </td>

                            <!-- 3. Broker / Agent -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle">
                                <div class="font-black text-slate-900 text-xs" x-text="deal.broker_name"></div>
                                <div class="text-[11px] text-slate-500 font-semibold mt-0.5" x-text="'Rate: ' + numberFormat(deal.commission_percent) + '%'"></div>
                                <button type="button" @click.stop="expandedDeal === deal.id ? expandedDeal = null : expandedDeal = deal.id"
                                        class="mt-1.5 px-2.5 py-1 bg-[#a38c29]/15 hover:bg-[#a38c29]/25 text-[#7a681d] rounded-lg text-xs font-black cursor-pointer inline-flex items-center gap-1.5 transition shadow-2xs border border-[#a38c29]/40">
                                    <span x-text="expandedDeal === deal.id ? '▲ Hide Log' : '▼ ' + (deal.entries ? deal.entries.length : 1) + ' Transactions'"></span>
                                </button>
                            </td>

                            <!-- 4. Net Sale Value -->
                            <td class="px-4 py-3.5 font-mono font-black text-slate-900 text-xs border-r border-slate-200/50 align-middle whitespace-nowrap">
                                <span x-text="'₹' + numberFormat(deal.net_sale_value)"></span>
                            </td>

                            <!-- 5. Sale EMI Progress -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle min-w-[170px]">
                                <div class="text-[11px] font-semibold text-slate-600 mb-1">
                                    <span>Pending </span><span class="text-rose-700 font-mono font-black" x-text="'₹' + numberFormat(deal.sale_remaining_balance)"></span> <span class="text-slate-400">Bal.</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#a38c29] h-1.5 rounded-full transition-all duration-500" :style="'width: ' + deal.sale_collected_pct + '%'"></div>
                                </div>
                                <div class="text-[9.5px] text-slate-500 font-semibold mt-1 text-center" x-text="deal.sale_collected_pct + '% collected'"></div>
                            </td>

                            <!-- 6. Commission -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle whitespace-nowrap">
                                <div class="font-mono font-black text-slate-900 text-sm" x-text="'₹' + numberFormat(deal.commission_amount)"></div>
                                <div class="text-[10px] text-slate-500 font-bold uppercase mt-0.5" x-text="'@ ' + numberFormat(deal.commission_percent) + '% OF SALE'"></div>
                            </td>

                            <!-- 7. Commission Status -->
                            <td class="px-4 py-3.5 border-r border-slate-200/50 align-middle min-w-[180px]">
                                <div class="flex flex-col items-center">
                                    <!-- Badge -->
                                    <template x-if="deal.status === 'paid'">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1.5">
                                            PAID OUT
                                        </span>
                                    </template>
                                    <template x-if="deal.status === 'partial'">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#fef9c3] text-[#854d0e] border border-[#fde68a] mb-1.5">
                                            PARTIALLY PAID
                                        </span>
                                    </template>
                                    <template x-if="deal.status === 'pending' || deal.status === 'payable'">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 mb-1.5">
                                            PENDING
                                        </span>
                                    </template>

                                    <!-- Paid / Bal Details -->
                                    <template x-if="deal.status === 'paid'">
                                        <div class="text-[10px] font-bold text-emerald-700 mt-0.5 flex items-center gap-1">
                                            <span>✓ Fully settled</span>
                                            <span class="font-mono" x-text="'₹' + numberFormat(deal.paid_amount)"></span>
                                        </div>
                                    </template>

                                    <template x-if="deal.status !== 'paid'">
                                        <div class="w-full">
                                            <div class="text-[10px] font-semibold text-slate-700 mb-1 flex items-center justify-between gap-1 text-center">
                                                <span>Paid: <strong class="font-mono font-black text-slate-900" x-text="'₹' + numberFormat(deal.paid_amount)"></strong></span>
                                                <span>Bal: <strong class="font-mono font-black text-rose-700" x-text="'₹' + numberFormat(deal.balance_due)"></strong></span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#a38c29] h-1.5 rounded-full transition-all duration-500" :style="'width: ' + deal.payout_pct + '%'"></div>
                                            </div>
                                            <div class="text-[9.5px] text-slate-500 font-semibold mt-1 text-center" x-text="deal.payout_pct + '% paid'"></div>
                                        </div>
                                    </template>
                                </div>
                            </td>

                            <!-- 8. Action (Only View Eye Icon) -->
                            <td class="px-3 py-3.5 text-center col-action print:hidden align-middle">
                                <div class="flex items-center justify-center">
                                    <button type="button" @click.stop="openViewModal(deal.entries && deal.entries[0] ? deal.entries[0] : deal)"
                                            class="p-2 rounded-xl bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                            title="View Deal & Commission Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Expanded Detailed Transaction Log Rows -->
                        <template x-if="expandedDeal === deal.id">
                            <tr>
                                <td colspan="8" class="p-0 border-b border-slate-200">
                                    <div class="bg-slate-50/80 p-4 sm:p-5 shadow-[inset_0_4px_6px_-4px_rgba(0,0,0,0.05)] border-l-4 border-[#a38c29] rounded-r-xl">
                                        <h4 class="text-[10px] font-black text-slate-700 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Detailed Transaction Log — <span x-text="deal.sale_number"></span> (<span x-text="deal.broker_name"></span>)
                                        </h4>
                                        <div class="overflow-hidden rounded-lg border border-[#a38c29]/30 bg-white shadow-sm">
                                            <table class="w-full text-left border-collapse">
                                                <thead class="bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white border-b border-[#8a7522] text-[9px] font-black uppercase tracking-wider">
                                                    <tr>
                                                        <th class="px-4 py-2.5 w-28 border-r border-slate-200/50">Date</th>
                                                        <th class="px-4 py-2.5 border-r border-slate-200/50">Event Details</th>
                                                        <th class="px-4 py-2.5 text-right w-28 border-r border-slate-200/50">Debit (Claim)</th>
                                                        <th class="px-4 py-2.5 text-right w-28 border-r border-slate-200/50">Credit (Paid)</th>
                                                        <th class="px-4 py-2.5 text-right w-32 border-r border-slate-200/50">Balance Due</th>
                                                        <th class="px-2 py-2.5 text-center w-[60px] whitespace-nowrap">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                                    <template x-for="(entry, index) in deal.entries" :key="index">
                                                        <tr class="hover:bg-slate-50/70 transition-colors" :class="entry.type === 'CLAIM' ? '' : 'bg-emerald-50/20'">
                                                            <td class="px-4 py-3 text-[10px] font-mono whitespace-nowrap border-r border-slate-200/50 align-top">
                                                                <div class="font-bold text-slate-600" x-text="entry.date_formatted"></div>
                                                            </td>
                                                            <td class="px-4 py-3 border-r border-slate-200/50 align-top">
                                                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider" 
                                                                          :class="entry.type === 'CLAIM' ? 'bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/20' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                                                                          x-text="entry.type === 'CLAIM' ? 'Commission Allocated' : 'Payment Released'"></span>
                                                                    <span class="font-mono text-[9px] font-bold text-slate-400 bg-slate-100 px-1.5 rounded border border-slate-200" x-text="entry.ref_no"></span>
                                                                </div>
                                                                <div class="text-[11px] font-bold text-slate-800 leading-snug mb-1" x-text="entry.particulars"></div>
                                                                <div class="text-[9px] text-slate-500 font-semibold" x-text="entry.project_name + (entry.unit_name ? ' — ' + entry.unit_name : '') + (entry.customer_name ? ' — ' + entry.customer_name : '')"></div>
                                                            </td>
                                                            <td class="px-4 py-3 text-right font-mono text-[11px] text-blue-700 font-bold border-r border-slate-200/50 align-top" x-text="entry.net_approved > 0 ? '₹' + numberFormat(entry.net_approved) : '—'"></td>
                                                            <td class="px-4 py-3 text-right font-mono text-[11px] text-emerald-700 font-bold border-r border-slate-200/50 align-top" x-text="entry.paid_amount > 0 ? '₹' + numberFormat(entry.paid_amount) : '—'"></td>
                                                            <td class="px-4 py-3 text-right font-mono text-[11px] font-black border-r border-slate-200/50 align-top" :class="entry.running_balance > 0 ? 'text-rose-700' : 'text-emerald-700'">
                                                                <div class="flex flex-col items-end">
                                                                    <span x-text="'₹' + numberFormat(Math.abs(entry.running_balance))"></span>
                                                                    <span class="text-[8px] font-bold uppercase text-slate-400 mt-0.5">CR</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-2 py-3 text-center col-action print:hidden align-middle">
                                                                <div class="flex items-center justify-center">
                                                                    <!-- View Modal (Eye Icon) ONLY -->
                                                                    <button type="button" @click="openViewModal(entry)" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Transaction Details">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </template>
                
                <tbody x-show="filteredDeals().length === 0">
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                            No brokerage deals found matching the filter criteria.
                        </td>
                    </tr>
                </tbody>

                {{-- Table Grand Totals Footer (Live Updates via Alpine) --}}
                <tfoot x-show="filteredDeals().length > 0">
                    <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-[#a38c29]">
                        <td colspan="3" class="py-3 px-4 text-right uppercase tracking-wider text-[10px] text-slate-700 font-bold border-r border-slate-200/40">
                            Grand Totals for Filtered Deals:
                        </td>
                        <td class="py-3 px-4 text-left font-mono text-xs text-slate-900 whitespace-nowrap font-black border-r border-slate-200/40"
                            x-text="'₹' + numberFormat(getTotals().saleValue)">
                        </td>
                        <td class="py-3 px-4 text-left font-mono text-xs text-rose-800 whitespace-nowrap font-bold border-r border-slate-200/40"
                            x-text="'₹' + numberFormat(getTotals().emiPending) + ' Bal.'">
                        </td>
                        <td class="py-3 px-4 text-left font-mono text-xs text-blue-900 whitespace-nowrap font-black border-r border-slate-200/40"
                            x-text="'₹' + numberFormat(getTotals().commissionAccrued)">
                        </td>
                        <td class="py-3 px-4 text-center font-mono text-xs whitespace-nowrap font-black border-r border-slate-200/40">
                            <span class="text-emerald-700" x-text="'Paid: ₹' + numberFormat(getTotals().commissionPaid)"></span>
                            <span class="text-slate-400 mx-1">|</span>
                            <span class="text-rose-700" x-text="'Bal: ₹' + numberFormat(getTotals().balanceDue)"></span>
                        </td>
                        <td class="col-action print:hidden"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- VIEW TRANSACTION MODAL (PROPORTIONAL CLEAN REDESIGN) -->
    <div x-cloak x-show="viewModalOpen" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-[100] flex items-center justify-center p-3 sm:p-4 print:hidden overflow-y-auto"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Frosted Dark Overlay Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
             style="background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;"
             @click="viewModalOpen = false"></div>
        
        <div class="relative z-10 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border-0 flex flex-col bg-slate-50 my-auto" 
             style="max-width: 896px !important; width: 100% !important;"
             @click.away="viewModalOpen = false"
             x-show="viewModalOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- 1. Deep Black / Charcoal Executive Header (No Border) -->
            <div class="px-6 py-3.5 flex items-center justify-between relative overflow-hidden shrink-0"
                 style="background-color: #12100c !important; color: #ffffff !important;">
                <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full pointer-events-none opacity-20" style="background: radial-gradient(circle, #a38c29 0%, transparent 70%);"></div>
                
                <div class="relative z-10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[#f3e5ab] border border-[#a38c29]/40 shrink-0"
                         style="background-color: rgba(163, 140, 41, 0.25) !important;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-black uppercase tracking-widest text-[#f3e5ab] px-2 py-0.5 rounded border border-[#a38c29]/40"
                                  style="background-color: rgba(163, 140, 41, 0.2) !important;">
                                BROKER TRANSACTION RECORD
                            </span>
                            <span x-show="selectedLedgerEntry?.type === 'CLAIM' || !selectedLedgerEntry?.type" class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider text-white shadow-xs" style="background-color: #a38c29 !important;">
                                COMMISSION ALLOCATED
                            </span>
                            <span x-show="selectedLedgerEntry?.type === 'DISBURSEMENT'" class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider text-white shadow-xs" style="background-color: #059669 !important;">
                                PAYMENT DISBURSED
                            </span>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-white tracking-tight mt-0.5" x-text="selectedLedgerEntry?.ref_no || selectedLedgerEntry?.sale_number || 'N/A'"></h2>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 relative z-10">
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-lg text-white/90 text-xs font-mono font-bold border border-white/10"
                         style="background-color: rgba(255, 255, 255, 0.08) !important;">
                        <svg class="w-3.5 h-3.5 text-[#f3e5ab]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span x-text="selectedLedgerEntry?.date_formatted || selectedLedgerEntry?.sale_date_formatted || 'N/A'"></span>
                    </div>
                    <button type="button" @click="viewModalOpen = false" class="w-7 h-7 rounded-full text-white/80 hover:text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer border border-white/20 hover:bg-white/20"
                            style="background-color: rgba(255, 255, 255, 0.12) !important;">
                        ✕
                    </button>
                </div>
            </div>

            <!-- 2. Streamlined Compact Body (Fits on screen without scroll) -->
            <div class="p-4 sm:p-5 space-y-3 bg-slate-50">
                
                <!-- 2A. Four Financial Metric KPI Cards (4 Columns) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    
                    <!-- KPI 1: Property Deal Value -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between text-slate-400 mb-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-500">Deal Value</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-sm sm:text-base font-black font-mono text-slate-900 block leading-tight" x-text="'₹' + numberFormat(selectedLedgerEntry?.deal_value || selectedLedgerEntry?.net_sale_value || 0)"></span>
                        <span class="text-[9px] text-slate-400 font-medium">Net Sales Value</span>
                    </div>

                    <!-- KPI 2: Commission Rate -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between text-slate-400 mb-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-500">Rate</span>
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <span class="text-sm sm:text-base font-black font-mono text-[#8a7522] block leading-tight" x-text="(selectedLedgerEntry?.commission_percent || 2) + '%'"></span>
                        <span class="text-[9px] text-slate-400 font-medium">Broker Commission</span>
                    </div>

                    <!-- KPI 3: Commission Claimed / Disbursed -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between text-slate-400 mb-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-500" x-text="selectedLedgerEntry?.type === 'DISBURSEMENT' ? 'Paid Amount' : 'Commission'"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm sm:text-base font-black font-mono block leading-tight" 
                              :class="selectedLedgerEntry?.type === 'DISBURSEMENT' ? 'text-emerald-700' : 'text-blue-900'"
                              x-text="selectedLedgerEntry?.type === 'DISBURSEMENT' ? ('₹' + numberFormat(selectedLedgerEntry?.paid_amount || 0)) : ('₹' + numberFormat(selectedLedgerEntry?.net_approved || selectedLedgerEntry?.commission_amount || 0))"></span>
                        <span class="text-[9px] text-slate-400 font-medium" x-text="selectedLedgerEntry?.type === 'DISBURSEMENT' ? 'Released' : 'Total Allocated'"></span>
                    </div>

                    <!-- KPI 4: Net Balance Due -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between text-slate-400 mb-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-500">Balance Due</span>
                            <span class="px-1.5 py-0.2 rounded text-[8px] font-black uppercase tracking-wider"
                                  :class="(selectedLedgerEntry?.running_balance || selectedLedgerEntry?.balance_due || 0) > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'"
                                  x-text="(selectedLedgerEntry?.running_balance || selectedLedgerEntry?.balance_due || 0) > 0 ? 'Payable' : 'Settled'"></span>
                        </div>
                        <span class="text-sm sm:text-base font-black font-mono block leading-tight"
                              :class="(selectedLedgerEntry?.running_balance || selectedLedgerEntry?.balance_due || 0) > 0 ? 'text-rose-700' : 'text-emerald-700'"
                              x-text="'₹' + numberFormat(Math.abs(selectedLedgerEntry?.running_balance || selectedLedgerEntry?.balance_due || 0))"></span>
                        <span class="text-[9px] text-slate-400 font-medium">Resulting Status</span>
                    </div>

                </div>

                <!-- 2B. Entity & Transaction Details (2 Columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    
                    <!-- Card 1: Broker & Property Information -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-2">
                        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100">
                            <div class="w-5 h-5 rounded-md bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-wider">Broker & Property Entity</h3>
                        </div>

                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Broker :</span>
                                <span class="font-black text-slate-900 text-right truncate max-w-[220px]" x-text="selectedLedgerEntry?.broker_name || 'N/A'"></span>
                            </div>

                            <!-- <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Project:</span>
                                <span class="font-bold text-slate-800 text-right truncate max-w-[220px]" x-text="selectedLedgerEntry?.project_name || 'All Projects'"></span>
                            </div> -->

                            <div class="flex items-center justify-between" x-show="selectedLedgerEntry?.unit_name">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Unit / Door No:</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800 font-mono font-bold text-[9.5px]" x-text="selectedLedgerEntry?.unit_name"></span>
                            </div>

                            <div class="flex items-center justify-between" x-show="selectedLedgerEntry?.customer_name">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Customer / Client:</span>
                                <span class="font-bold text-slate-800 text-right truncate max-w-[220px]" x-text="selectedLedgerEntry?.customer_name"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Transaction Audit Log & Settlement Progress -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-2">
                        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100">
                            <div class="w-5 h-5 rounded-md bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-wider">Transaction Audit & Settlement</h3>
                        </div>

                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Reference No:</span>
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/10 border border-[#a38c29]/20 text-[#8a7522] font-mono font-bold text-[9.5px]" x-text="selectedLedgerEntry?.ref_no || selectedLedgerEntry?.sale_number || 'N/A'"></span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Posting Date:</span>
                                <span class="font-mono font-bold text-slate-800 text-xs" x-text="selectedLedgerEntry?.date_formatted || selectedLedgerEntry?.sale_date_formatted || 'N/A'"></span>
                            </div>

                            <div class="flex items-center justify-between" x-show="selectedLedgerEntry?.payment_mode">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Payment Mode:</span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-[9.5px]" x-text="selectedLedgerEntry?.payment_mode"></span>
                            </div>

                            <div class="flex items-center justify-between" x-show="selectedLedgerEntry?.company_bank_account_name">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Source Bank:</span>
                                <span class="font-bold text-slate-800 truncate max-w-[210px]" x-text="selectedLedgerEntry?.company_bank_account_name"></span>
                            </div>

                            <!-- Settlement Progress mini bar when Claim -->
                            <div class="pt-0.5" x-show="selectedLedgerEntry?.type !== 'DISBURSEMENT'">
                                <div class="flex items-center justify-between text-[9px] font-bold text-slate-500 mb-1">
                                    <span>Settlement Progress:</span>
                                    <span class="font-mono font-black text-[#8a7522]" x-text="((selectedLedgerEntry?.commission_amount || selectedLedgerEntry?.net_approved || 0) > 0 ? Math.min(100, Math.round(((selectedLedgerEntry?.paid_amount || 0) / (selectedLedgerEntry?.commission_amount || selectedLedgerEntry?.net_approved || 1)) * 100)) : 0) + '% Settled'"></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200/80">
                                    <div class="bg-gradient-to-r from-[#a38c29] to-emerald-600 h-1.5 rounded-full transition-all duration-500" 
                                         :style="'width: ' + ((selectedLedgerEntry?.commission_amount || selectedLedgerEntry?.net_approved || 0) > 0 ? Math.min(100, Math.round(((selectedLedgerEntry?.paid_amount || 0) / (selectedLedgerEntry?.commission_amount || selectedLedgerEntry?.net_approved || 1)) * 100)) : 0) + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 2C. Narration & Particulars Box -->
                <div class="bg-white rounded-xl px-3.5 py-2.5 border border-slate-200/90 shadow-2xs flex items-start gap-2">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase shrink-0 mt-0.5">Particulars:</span>
                    <span class="text-xs font-semibold text-slate-700 leading-relaxed" x-text="selectedLedgerEntry?.particulars || ('Brokerage commission record for sale #' + (selectedLedgerEntry?.sale_number || ''))"></span>
                </div>

            </div>

            <!-- 3. Executive Compact Footer -->
            <div class="px-6 py-3 bg-white flex items-center justify-between shrink-0">
                <a :href="'{{ route('brokers.payable-report') }}?broker_id=' + (selectedLedgerEntry?.broker_id || '')" 
                   class="px-3.5 py-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#8a7522] text-xs font-extrabold uppercase tracking-wider transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <span>Full Payout Ledger</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <button type="button" @click="viewModalOpen = false" class="px-5 py-1.5 text-white text-xs font-black tracking-wider uppercase rounded-lg transition shadow-sm hover:shadow active:scale-95 cursor-pointer"
                        style="background-color: #18181b !important;">
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

<script>
const defaultProjectId = '{{ $projects->first()?->id ?? '' }}';
const projectsList = @json($projects ?? []);

function brokerCommissionLedger() {
    return {
        brokerFilterOpen: false,
        brokerFilterSearch: '',
        selectedLedgerBrokerId: '',
        projectFilterOpen: false,
        selectedProjectId: defaultProjectId,
        statusFilterOpen: false,
        selectedStatus: '',
        allDeals: @json($allDeals ?? []),
        allLedgerEntries: @json($allLedgerEntries ?? []),
        brokersList: @json($brokers ?? []),
        projectsList: projectsList,
        viewModalOpen: false,
        selectedLedgerEntry: null,
        expandedDeal: null,

        openViewModal(entry) {
            this.selectedLedgerEntry = entry;
            this.viewModalOpen = true;
        },

        printLedger() {
            window.print();
        },

        resetFilters() {
            this.selectedLedgerBrokerId = '';
            this.selectedProjectId = defaultProjectId;
            this.selectedStatus = '';
            this.brokerFilterSearch = '';
            this.brokerFilterOpen = false;
            this.projectFilterOpen = false;
            this.statusFilterOpen = false;
            this.expandedDeal = null;
        },

        getSelectedBrokerName() {
            if (!this.selectedLedgerBrokerId) return 'All Brokers';
            const b = this.brokersList.find(x => String(x.id) === String(this.selectedLedgerBrokerId));
            return b ? b.name : 'All Brokers';
        },

        getSelectedProjectName() {
            if (!this.selectedProjectId) return 'All Projects';
            const p = this.projectsList.find(x => String(x.id) === String(this.selectedProjectId));
            return p ? p.name : 'All Projects';
        },

        getSelectedStatusName() {
            if (!this.selectedStatus) return 'All Statuses (Pending, Partial, Fully Paid)';
            if (this.selectedStatus === 'pending') return 'Pending (Unpaid / Payable Share)';
            if (this.selectedStatus === 'partial') return 'Partially Paid';
            if (this.selectedStatus === 'paid') return 'Fully Paid / Disbursed';
            return 'All Statuses (Pending, Partial, Fully Paid)';
        },

        getFilteredBrokersList(search = '') {
            if (!search) return this.brokersList;
            const q = search.toLowerCase().trim();
            return this.brokersList.filter(b => (b.name || '').toLowerCase().includes(q));
        },

        filteredDeals() {
            let deals = this.allDeals;
            if (this.selectedLedgerBrokerId) {
                deals = deals.filter(d => String(d.broker_id) === String(this.selectedLedgerBrokerId));
            }
            if (this.selectedProjectId) {
                deals = deals.filter(d => String(d.project_id) === String(this.selectedProjectId));
            }
            if (this.selectedStatus) {
                deals = deals.filter(d => {
                    if (this.selectedStatus === 'pending') {
                        return d.status === 'pending' || d.status === 'payable' || (d.paid_amount <= 0);
                    } else if (this.selectedStatus === 'partial') {
                        return d.status === 'partial' || (d.paid_amount > 0 && d.paid_amount < d.commission_amount);
                    } else if (this.selectedStatus === 'paid') {
                        return d.status === 'paid' || (d.paid_amount >= d.commission_amount && d.commission_amount > 0);
                    }
                    return true;
                });
            }
            return deals;
        },

        filteredLedgerEntries() {
            let entries = this.allLedgerEntries;
            if (this.selectedLedgerBrokerId) {
                entries = entries.filter(e => String(e.broker_id) === String(this.selectedLedgerBrokerId));
            }
            if (this.selectedProjectId) {
                entries = entries.filter(e => String(e.project_id) === String(this.selectedProjectId));
            }
            if (this.selectedStatus) {
                entries = entries.filter(e => {
                    if (this.selectedStatus === 'pending') {
                        return e.status === 'pending' || (e.type === 'CLAIM' && (!e.status || e.status === 'pending' || e.status === 'payable'));
                    } else if (this.selectedStatus === 'partial') {
                        return e.status === 'partial';
                    } else if (this.selectedStatus === 'paid') {
                        return e.status === 'paid' || e.type === 'DISBURSEMENT';
                    }
                    return true;
                });
            }
            return entries;
        },

        getTotals() {
            const deals = this.filteredDeals();
            const saleValue = deals.reduce((acc, d) => acc + (parseFloat(d.net_sale_value) || 0), 0);
            const emiPending = deals.reduce((acc, d) => acc + (parseFloat(d.sale_remaining_balance) || 0), 0);
            const commissionAccrued = deals.reduce((acc, d) => acc + (parseFloat(d.commission_amount) || 0), 0);
            const commissionPaid = deals.reduce((acc, d) => acc + (parseFloat(d.paid_amount) || 0), 0);
            const balanceDue = deals.reduce((acc, d) => acc + (parseFloat(d.balance_due) || 0), 0);
            return {
                saleValue,
                emiPending,
                commissionAccrued,
                commissionPaid,
                balanceDue
            };
        },

        getLedgerTotals() {
            const deals = this.filteredDeals();
            const netClaimed = deals.reduce((acc, d) => acc + (parseFloat(d.commission_amount) || 0), 0);
            const paid = deals.reduce((acc, d) => acc + (parseFloat(d.paid_amount) || 0), 0);
            return {
                netClaimed: netClaimed,
                paid: paid,
                balance: netClaimed - paid
            };
        },

        numberFormat(val) {
            return (Math.abs(parseFloat(val)) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    };
}
</script>
@endsection
