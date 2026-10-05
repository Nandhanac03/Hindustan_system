@extends('layouts.erp')

@section('title', 'Contractor Ledger Statement & Directory')

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

    /* 3. Executive Corporate Letterhead Header (Exact Treasury Report Standard) */
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

    /* 6. Table Formatting (Exact Match with Picture 2) */
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

<div x-data="raBillLedger()" class="space-y-6">

    <!-- ── 1. EXECUTIVE PRINT HEADER (EXACT TREASURY REPORT STANDARD) ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Corporate Treasury & Risk Management</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">COMPANY CONTRACTOR ACCOUNT STATEMENT & CLAIMS LEDGER</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Contractor Scope:</span> <span class="font-bold text-[#a38c29]" x-text="getSelectedContractorName()"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Period Filter:</span> <span class="font-mono font-bold text-slate-800">Beginning &rarr; Today</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredLedgerEntries().length + ' Entries'"></span></div>
            </div>
        </div>
    </div>

    <!-- ── 2. EXECUTIVE FINANCIAL SUMMARY BANNER (EXACT TREASURY REPORT GOLDEN BANNER) ── -->
    <div class="reports-banner-container print-exec-banner hidden print:flex items-center justify-between gap-4 p-4 rounded-xl border border-[#e6d594] bg-[#fffdf5] mb-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#8a7522] shadow-2xs shrink-0">
                <svg class="w-5 h-5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Contractor Account Statement Ledger</h3>
                    <span class="text-[9px] font-bold text-[#8a7522] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Audit Trail</span>
                </div>
                <p class="text-[9.5px] text-slate-600 mt-1 font-medium">Consolidated real-time audit ledger of verified RA bill liabilities, contractor payment disbursements, and running balance.</p>
            </div>
        </div>

        {{-- Right Side: Clean Stat Tiles matching Treasury Report --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <div class="px-3.5 py-2 bg-blue-50 border border-blue-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-blue-700">Total Net Claims</span>
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
                <span class="block text-[8px] font-black uppercase tracking-widest text-[#8a7522]">Master Payees</span>
                <span class="text-xs font-black text-[#5c4a10] font-mono">{{ count($contractors) }} Accounts</span>
            </div>
        </div>
    </div>

    <!-- ── 3. WEB-ONLY WORKSPACE (Top Breadcrumb Bar, Alerts, Filter Toolbar, KPI Cards) ── -->
    <div class="web-only-workspace space-y-6 print:hidden">

        <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="/" class="hover:text-slate-600 transition">HOME</a>
                    <span>›</span>
                    <span>CONTRACTOR OPERATIONS</span>
                    <span>›</span>
                    <span class="text-[#a38c29] font-bold">CONTRACTOR LEDGER VIEW</span>
                </nav>
                <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Contractor Account Statement Ledger</span>
                    <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Account Statement</span>
                </h1>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- <button type="button" @click="printLedger()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer active:scale-95">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Export PDF</span>
                </button> -->

                <a href="{{ route('contractors.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                   
                    <span>+ Contractor Master</span>
                </a>
            </div>
        </div>

        <!-- ── SUCCESS & ERROR ALERTS ── -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
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

        <!-- Toolbar & Filter Header -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm transition-all">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-3.5 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
                    <!-- Searchable Contractor Select Dropdown -->
                    <div class="relative w-full" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Contractor</label>
                        
                        <button type="button" @click="open = !open" 
                                class="px-3.5 py-2.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none w-full shadow-2xs flex items-center justify-between gap-2 hover:border-[#a38c29]/60 transition">
                            <span class="truncate" x-text="getSelectedContractorName()"></span>
                            <div class="flex items-center gap-1 shrink-0">
                                <template x-if="selectedLedgerContractorId">
                                    <span @click.stop="selectedLedgerContractorId = ''; search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-200 transition" title="Clear selection">✕</span>
                                </template>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <!-- Searchable Dropdown Menu -->
                        <div x-show="open" x-transition.opacity.duration.150ms 
                             class="absolute top-full left-0 mt-1 w-full bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                             style="display: none;">
                            
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="search" placeholder="Type contractor name to filter..." 
                                       class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:bg-white transition"
                                       @keydown.escape="open = false" autofocus>
                                <template x-if="search">
                                    <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                                </template>
                            </div>

                            <div class="max-h-56 overflow-y-auto space-y-0.5 text-xs font-semibold">
                                <button type="button" @click="selectedLedgerContractorId = ''; open = false; search = '';" 
                                        class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                        :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': !selectedLedgerContractorId }">
                                    <span>All Contractors</span>
                                    <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + (contractorsList ? contractorsList.length : 0) + ')'"></span>
                                </button>
                                
                                <template x-for="cont in getFilteredContractorsList(search)" :key="cont.id">
                                    <button type="button" @click="selectedLedgerContractorId = cont.id; open = false; search = '';" 
                                            class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                            :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': selectedLedgerContractorId == cont.id }">
                                        <span class="truncate" x-text="cont.name"></span>
                                        <span class="text-[9px] text-slate-400 font-mono" x-text="cont.gstin || cont.type || ''"></span>
                                    </button>
                                </template>
                                
                                <div x-show="getFilteredContractorsList(search).length === 0" class="px-3 py-3 text-center text-slate-400 text-xs italic">
                                    No contractors found.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Search Particulars / Ref # -->
                    <div class="w-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Search Particulars / Ref #</label>
                        <div class="relative w-full">
                            <input type="text" x-model="ledgerSearchQuery" placeholder="Search bill #, voucher #, project..."
                                   class="w-full px-3.5 py-2.5 pr-7 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none shadow-2xs hover:border-[#a38c29]/60 transition">
                            <template x-if="ledgerSearchQuery">
                                <button type="button" @click="ledgerSearchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer h-[42px]">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>

        <!-- Filtered Contractor Ledger KPI Summary (Upgraded with Icons & Hover Effects) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Net Claims Accrued -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL NET CLAIMS ACCRUED</span>
                    <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Verified RA Bill Liability</div>
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5 5 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5 5 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Payable Remaining</div>
                </div>
            </div>

            <!-- Card 4: Registered Contractors -->
            <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">REGISTERED CONTRACTORS</span>
                    <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight group-hover:text-[#8a7522] transition-colors">{{ count($contractors) }} Payees</div>
                    <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Master Accounts Linked</div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── 4. CONTRACTOR RUNNING ACCOUNT LEDGER STATEMENT TABLE CARD ── -->
    <div class="ledger-table-card bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-6 print:mt-0">
        
        {{-- Table Header Box (Exact Picture 2 Corporate Style) --}}
        <div class="ledger-table-header-box px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 print:hidden">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest print:text-[8.5pt]">Contractor Inflows & Outflows Detailed Ledger</h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center gap-1.5 flex-wrap print:text-[6.2pt] print:text-slate-600">
                        <span>Showing <strong class="text-slate-800 font-bold" x-text="filteredLedgerEntries().length"></strong> transactions</span>
                        <span x-show="selectedLedgerContractorId">for <strong class="text-[#8a7522] font-bold" x-text="getSelectedContractorName()"></strong></span>
                        <span x-show="!selectedLedgerContractorId">across <strong class="text-slate-700 font-bold">all registered contractors</strong></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto print:hidden">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Filtered Count:</span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-50 border border-slate-200 text-slate-800 shadow-2xs"
                      x-text="filteredLedgerEntries().length + ' Entries'">
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="ledger-print-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] font-extrabold uppercase text-[10px] tracking-wider text-left">
                        <th class="py-3 px-4 border-r border-[#8a7522]">Date</th>
                        <th class="py-3 px-4 border-r border-[#8a7522]">Description</th>
                        <th class="py-3 px-4 text-right text-blue-100 border-r border-[#8a7522]">Debit (Claimed)</th>
                        <th class="py-3 px-4 text-right text-emerald-100 border-r border-[#8a7522]">Credit (Released)</th>
                        <th class="py-3 px-4 text-right text-rose-100 border-r border-[#8a7522]">Running Balance</th>
                        <th class="py-3 px-4 text-center border-r border-[#8a7522] col-action print:hidden">Action</th>
                    </tr>
                </thead>
                <template x-for="group in groupedLedger()" :key="group.contractor_id">
                    <tbody class="divide-y divide-slate-100 bg-white text-[11px] font-semibold text-slate-700">
                        <!-- Group Header Row -->
                        <tr class="transition-colors border-b border-slate-200 bg-slate-50/50 hover:bg-slate-100/70 cursor-pointer" @click="expandedContractor === group.contractor_id ? expandedContractor = null : expandedContractor = group.contractor_id">
                            <td class="px-4 py-3 border-r border-slate-200/40 align-middle" colspan="2">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full bg-[#a38c29] text-white flex items-center justify-center font-black text-[11px] shrink-0 shadow-sm"
                                         x-text="(group.contractor_name || 'XX').substring(0, 2).toUpperCase()"></div>
                                    <div class="flex flex-col gap-1.5 items-start">
                                        <div class="font-black text-slate-900 text-xs leading-tight" x-text="group.contractor_name"></div>
                                        <button type="button" @click.stop="expandedContractor === group.contractor_id ? expandedContractor = null : expandedContractor = group.contractor_id"
                                                class="px-2 py-0.5 bg-[#a38c29]/15 hover:bg-[#a38c29]/30 text-[#7a681d] rounded font-black text-[10px] cursor-pointer inline-flex items-center gap-1 transition shadow-2xs border border-[#a38c29]/40"
                                                title="Toggle Transactions">
                                            <span x-text="expandedContractor === group.contractor_id ? '▲ Hide Log' : '▼ ' + group.entries.length + ' Transactions'"></span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[12px] text-blue-800 font-extrabold border-r border-slate-200/40 align-middle" x-text="group.netClaimed > 0 ? '₹' + numberFormat(group.netClaimed) : '—'"></td>
                            <td class="px-4 py-3 text-right font-mono text-[12px] text-emerald-700 font-extrabold border-r border-slate-200/40 align-middle" x-text="group.paid > 0 ? '₹' + numberFormat(group.paid) : '—'"></td>
                            <td class="px-4 py-3 text-right font-mono text-[12px] font-black border-r border-slate-200/40 align-middle">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg border bg-rose-50 text-rose-700 border-rose-200">
                                    <span x-text="'₹' + numberFormat(Math.abs(group.balance))"></span>
                                    <span class="text-[8px] font-extrabold uppercase text-rose-500">CR</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center col-action print:hidden align-middle">
                            </td>
                        </tr>
                        
                        <!-- Expanded Log Rows -->
                        <template x-if="expandedContractor === group.contractor_id">
                            <tr>
                                <td colspan="6" class="p-0 border-b border-slate-200">
                                    <div class="bg-slate-50/80 p-4 sm:p-5 shadow-[inset_0_4px_6px_-4px_rgba(0,0,0,0.05)] border-l-4 border-emerald-500 rounded-r-xl">
                                        <h4 class="text-[10px] font-black text-slate-700 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Detailed Transaction Log
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
                                                        <th class="px-2 py-2.5 text-center w-[85px] whitespace-nowrap">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                                    <template x-for="(entry, index) in group.entries" :key="index">
                                                        <tr class="hover:bg-slate-50/70 transition-colors" :class="entry.type === 'CLAIM' ? '' : 'bg-emerald-50/20'">
                                                            <td class="px-4 py-3 text-[10px] font-mono whitespace-nowrap border-r border-slate-200/50 align-top">
                                                                <div class="font-bold text-slate-600" x-text="entry.date_formatted"></div>
                                                            </td>
                                                            <td class="px-4 py-3 border-r border-slate-200/50 align-top">
                                                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider" 
                                                                          :class="entry.type === 'CLAIM' ? 'bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/20' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                                                                          x-text="entry.type === 'CLAIM' ? 'Verified Claim' : 'Payment Released'"></span>
                                                                    <span class="font-mono text-[9px] font-bold text-slate-400 bg-slate-100 px-1.5 rounded border border-slate-200" x-text="entry.ref_no"></span>
                                                                </div>
                                                                <div class="text-[11px] font-bold text-slate-800 leading-snug mb-1" x-text="entry.particulars"></div>
                                                                <div class="text-[9px] text-slate-500 font-semibold" x-text="entry.project_name + (entry.unit_name ? ' — Unit: ' + entry.unit_name : '')"></div>
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
                                                                <div class="flex items-center justify-center gap-2 flex-nowrap">
                                                                    <!-- View Modal (Eye Icon) - Gold Theme -->
                                                                    <button type="button" @click="openViewModal(entry)" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Transaction Details">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                    </button>

                                                                    <!-- Print Voucher Icon - Green Theme for Disbursment -->
                                                                    <template x-if="entry.type === 'DISBURSEMENT'">
                                                                        <a :href="'{{ url('vouchers') }}/' + (entry.voucher_id ? entry.voucher_id : entry.payment_id) + '/payment-voucher-print' + (entry.voucher_id ? '' : '?type=ra_payment')" target="_blank"
                                                                           class="p-1.5 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Print Payment Voucher">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                                        </a>
                                                                    </template>
                                                                    
                                                                    <!-- Print Voucher Icon - Gold Theme for Claim -->
                                                                    <template x-if="entry.type === 'CLAIM'">
                                                                        <a :href="'{{ url('vouchers') }}/' + (entry.jv_id || entry.voucher_id || entry.ra_bill_id) + '/payment-voucher-print?type=' + ((entry.jv_id || entry.voucher_id) ? 'jv' : 'ra_bill')" target="_blank"
                                                                           class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Print Claim Voucher">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                        </a>
                                                                    </template>
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
                
                <tbody x-show="filteredLedgerEntries().length === 0">
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">
                            No ledger transactions found matching the filter criteria.
                        </td>
                    </tr>
                </tbody>

                {{-- Table Grand Totals Footer (Live Updates via Alpine) --}}
                <tfoot x-show="filteredLedgerEntries().length > 0">
                    <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-[#a38c29]">
                        <td colspan="2" class="py-3 px-4 text-right uppercase tracking-wider text-[10px] text-slate-700 font-bold border-r border-slate-200/40">
                            Grand Totals for Selected Range:
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-xs text-blue-900 whitespace-nowrap font-black border-r border-slate-200/40"
                            x-text="getLedgerTotals().netClaimed > 0 ? '₹' + numberFormat(getLedgerTotals().netClaimed) : '—'">
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-xs text-emerald-800 whitespace-nowrap font-black border-r border-slate-200/40"
                            x-text="getLedgerTotals().paid > 0 ? '₹' + numberFormat(getLedgerTotals().paid) : '—'">
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-xs text-rose-900 whitespace-nowrap font-black border-r border-slate-200/40"
                            x-text="'₹' + numberFormat(Math.abs(getLedgerTotals().balance))">
                        </td>
                        <td class="col-action print:hidden"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- VIEW MODAL -->
    <div x-cloak x-show="viewModalOpen" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 print:hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-xs"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-xs"
         x-transition:leave-end="opacity-0 backdrop-blur-none">
        
        <div class="relative w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border-0 ring-0 outline-none flex flex-col max-h-[90vh] my-auto bg-slate-50" 
             @click.away="viewModalOpen = false"
             x-show="viewModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <!-- Header -->
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-5 sm:px-6 py-3.5 sm:py-4 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-0.5">
                            Transaction Record
                        </p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight flex items-center gap-2">
                            <span x-text="selectedLedgerEntry?.ref_no || 'N/A'"></span>
                            <span x-show="selectedLedgerEntry?.type === 'CLAIM'" class="px-2 py-0.5 rounded-md text-[9px] font-black bg-[#a38c29]/20 text-[#a38c29] uppercase border border-[#a38c29]/30 tracking-wider">Claim</span>
                            <span x-show="selectedLedgerEntry?.type === 'DISBURSEMENT'" class="px-2 py-0.5 rounded-md text-[9px] font-black bg-emerald-500/20 text-emerald-400 uppercase border border-emerald-500/30 tracking-wider">Release</span>
                        </h2>
                    </div>
                    <button type="button" @click="viewModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-4 sm:p-5 flex flex-col gap-3 overflow-y-auto custom-scrollbar flex-1 bg-white">
                
                <!-- General Information Card -->
                <div class="border border-slate-200/90 rounded-xl overflow-hidden shadow-2xs">
                    <div class="bg-slate-50 px-3.5 py-2.5 border-b border-slate-200/80">
                        <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M13 12h.01M13 16h.01M17 12h.01M17 16h.01"/></svg>
                            Contract & Project Entity
                        </span>
                    </div>
                    <div class="p-3.5 bg-white grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-4">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Contractor Name</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 block" x-text="selectedLedgerEntry?.contractor_name"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Project & Unit</span>
                            <span class="text-xs font-bold text-slate-700 block">
                                <span x-text="selectedLedgerEntry?.project_name"></span>
                                <span x-show="selectedLedgerEntry?.unit_name" class="inline-block ml-1.5 px-2 py-0.5 bg-white text-slate-600 rounded text-[9px] font-black border border-slate-200" x-text="'Unit: ' + selectedLedgerEntry?.unit_name"></span>
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Transaction Date</span>
                            <span class="text-xs font-mono font-bold text-slate-800" x-text="selectedLedgerEntry?.date_formatted"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Narration / Description</span>
                            <span class="text-xs font-medium text-slate-700 leading-relaxed block" x-text="selectedLedgerEntry?.particulars"></span>
                        </div>
                    </div>
                </div>

                <!-- Financial Breakdown Card -->
                <div class="border border-[#a38c29]/30 rounded-xl overflow-hidden shadow-2xs mt-2">
                    <div class="bg-amber-50/50 px-3.5 py-2.5 flex items-center justify-between border-b border-[#a38c29]/20">
                        <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Financial Breakdown
                        </span>
                    </div>
                    <div class="p-3 bg-white grid grid-cols-1 gap-3" :class="selectedLedgerEntry?.type === 'CLAIM' ? 'sm:grid-cols-2' : ''">
                        
                        <!-- Box 1: Claim Details (Only show if CLAIM) -->
                        <template x-if="selectedLedgerEntry?.type === 'CLAIM'">
                            <div class="border border-slate-200/90 rounded-lg p-2.5 bg-slate-50/30">
                                <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Claim Details</h4>
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[10px] font-semibold text-slate-500">Gross Claim Amount:</span>
                                    <span class="text-xs font-mono font-black text-slate-800" x-text="'₹ ' + numberFormat(selectedLedgerEntry?.gross_amount || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center" x-show="selectedLedgerEntry?.correction_amount > 0">
                                    <span class="text-[10px] font-semibold text-slate-500">Deductions:</span>
                                    <span class="text-xs font-mono font-black text-rose-700" x-text="'-₹ ' + numberFormat(selectedLedgerEntry?.correction_amount || 0)"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Box 2/1: Final Action (Net Accrued or Payment Released) -->
                        <div class="border border-[#a38c29]/20 rounded-lg p-2.5 bg-[#faf8f0]">
                            <h4 class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider mb-2 border-b border-[#a38c29]/10 pb-1.5" x-text="selectedLedgerEntry?.type === 'CLAIM' ? 'Net Approved' : 'Payment Released'"></h4>
                            
                            <!-- If it's a claim -->
                            <template x-if="selectedLedgerEntry?.type === 'CLAIM'">
                                <div class="flex justify-between items-center h-[26px]">
                                    <span class="text-[10px] font-semibold text-slate-600">Net Claim Accrued:</span>
                                    <span class="text-sm font-mono font-black text-blue-800" x-text="'₹ ' + numberFormat(selectedLedgerEntry?.net_approved || 0)"></span>
                                </div>
                            </template>

                            <!-- If it's a payment -->
                            <template x-if="selectedLedgerEntry?.type === 'DISBURSEMENT'">
                                <div class="flex justify-between items-center h-[26px]">
                                    <span class="text-[10px] font-semibold text-slate-600">Amount Released:</span>
                                    <span class="text-sm font-mono font-black text-emerald-700" x-text="'₹ ' + numberFormat(selectedLedgerEntry?.paid_amount || 0)"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Running Balance (Always Show at the bottom of Financial Breakdown) -->
                    <div class="bg-slate-50 px-3.5 py-3 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest shrink-0">Resulting Balance</span>
                        <div class="flex items-center justify-end gap-2 shrink-0">
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border shrink-0 bg-white" :class="(selectedLedgerEntry?.running_balance || 0) > 0 ? 'text-rose-700 border-rose-200' : 'text-emerald-700 border-emerald-200'" x-text="(selectedLedgerEntry?.running_balance || 0) > 0 ? 'Payable (CR)' : 'Settled'"></span>
                            <div class="text-sm sm:text-base font-mono font-black whitespace-nowrap" :class="(selectedLedgerEntry?.running_balance || 0) > 0 ? 'text-rose-700' : 'text-emerald-700'" x-text="'₹ ' + numberFormat(Math.abs(selectedLedgerEntry?.running_balance || 0))"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 sm:px-8 py-4 bg-white border-t border-slate-200/80 flex items-center justify-between shrink-0">
                <template x-if="selectedLedgerEntry?.type === 'DISBURSEMENT'">
                    <a :href="'{{ url('vouchers') }}/' + (selectedLedgerEntry?.voucher_id ? selectedLedgerEntry?.voucher_id : selectedLedgerEntry?.payment_id) + '/payment-voucher-print' + (selectedLedgerEntry?.voucher_id ? '' : '?type=ra_payment')" target="_blank"
                       class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#a38c29] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Payment Voucher
                    </a>
                </template>
                <template x-if="selectedLedgerEntry?.type === 'CLAIM'">
                    <a :href="'{{ url('vouchers') }}/' + (selectedLedgerEntry?.jv_id || selectedLedgerEntry?.voucher_id || selectedLedgerEntry?.ra_bill_id) + '/payment-voucher-print?type=' + ((selectedLedgerEntry?.jv_id || selectedLedgerEntry?.voucher_id) ? 'jv' : 'ra_bill')" target="_blank"
                       class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#a38c29] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Claim Voucher
                    </a>
                </template>
                <button type="button" @click="viewModalOpen = false" class="ml-auto px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold tracking-wider uppercase rounded-xl transition shadow-lg shadow-slate-900/20 active:scale-95">
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function raBillLedger() {
    return {
        ledgerSearchQuery: '',
        selectedLedgerContractorId: '',
        allLedgerEntries: @json($allLedgerEntries ?? []),
        contractorsList: @json($contractors ?? []),
        viewModalOpen: false,
        selectedLedgerEntry: null,
        expandedContractor: null,

        openViewModal(entry) {
            this.selectedLedgerEntry = entry;
            this.viewModalOpen = true;
        },

        printLedger() {
            window.print();
        },

        resetFilters() {
            this.selectedLedgerContractorId = '';
            this.ledgerSearchQuery = '';
            this.expandedContractor = null;
        },

        getSelectedContractorName() {
            if (!this.selectedLedgerContractorId) return 'All Contractors';
            const c = this.contractorsList.find(x => x.id == this.selectedLedgerContractorId);
            return c ? c.name : 'All Contractors';
        },

        getFilteredContractorsList(search = '') {
            if (!search) return this.contractorsList;
            const q = search.toLowerCase().trim();
            return this.contractorsList.filter(c => (c.name || '').toLowerCase().includes(q));
        },

        filteredLedgerEntries() {
            let entries = this.allLedgerEntries;
            if (this.selectedLedgerContractorId) {
                entries = entries.filter(e => e.contractor_id == this.selectedLedgerContractorId);
            }
            if (this.ledgerSearchQuery) {
                const q = this.ledgerSearchQuery.toLowerCase().trim();
                entries = entries.filter(e =>
                    (e.contractor_name && e.contractor_name.toLowerCase().includes(q)) ||
                    (e.project_name && e.project_name.toLowerCase().includes(q)) ||
                    (e.unit_name && e.unit_name.toLowerCase().includes(q)) ||
                    (e.particulars && e.particulars.toLowerCase().includes(q)) ||
                    (e.ra_bill_number && e.ra_bill_number.toLowerCase().includes(q)) ||
                    (e.ref_no && e.ref_no.toLowerCase().includes(q))
                );
            }
            return entries;
        },

        groupedLedger() {
            const entries = this.filteredLedgerEntries();
            const groupsMap = {};
            
            entries.forEach(entry => {
                if (!groupsMap[entry.contractor_id]) {
                    groupsMap[entry.contractor_id] = {
                        contractor_id: entry.contractor_id,
                        contractor_name: entry.contractor_name,
                        entries: [],
                        netClaimed: 0,
                        paid: 0,
                        balance: 0
                    };
                }
                groupsMap[entry.contractor_id].entries.push(entry);
                groupsMap[entry.contractor_id].netClaimed += parseFloat(entry.net_approved) || 0;
                groupsMap[entry.contractor_id].paid += parseFloat(entry.paid_amount) || 0;
            });

            // Calculate balance per contractor and running balance inside entries
            return Object.values(groupsMap).map(group => {
                group.balance = group.netClaimed - group.paid;
                let running_balance = 0;
                group.entries = group.entries.map(e => {
                    running_balance += (parseFloat(e.net_approved) || 0) - (parseFloat(e.paid_amount) || 0);
                    return {
                        ...e,
                        running_balance: running_balance
                    };
                });
                return group;
            });
        },

        getLedgerTotals() {
            const entries = this.filteredLedgerEntries();
            const gross = entries.reduce((acc, e) => acc + (parseFloat(e.gross_amount) || 0), 0);
            const corrections = entries.reduce((acc, e) => acc + (parseFloat(e.correction_amount) || 0), 0);
            const netClaimed = entries.reduce((acc, e) => acc + (parseFloat(e.net_approved) || 0), 0);
            const paid = entries.reduce((acc, e) => acc + (parseFloat(e.paid_amount) || 0), 0);
            return {
                gross: gross,
                corrections: corrections,
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
