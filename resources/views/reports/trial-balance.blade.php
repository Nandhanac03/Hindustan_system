<x-erp-layout title="Trial Balance Workspace" headerTitle="Accounting & Financial Reports">

{{-- ExcelJS CDN Library for Executive Client-side Excel Export --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 8mm 10mm 8mm 10mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 8pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print\:hidden, header, nav, aside, footer, button, select, input, form, .custom-scrollbar::-webkit-scrollbar, [class*="nav"], [class*="sidebar"] {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
    .print\:flex {
        display: flex !important;
    }
    .print\:table {
        display: table !important;
    }
    .print\:table-column-group {
        display: table-column-group !important;
    }
    .print\:table-cell {
        display: table-cell !important;
    }
    
    /* Container Reset for Print */
    .w-full, [class*="max-w-"], .overflow-x-auto {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    /* Reset Tailwind vertical space helpers in print */
    .space-y-4 > * + *,
    .space-y-5 > * + *,
    .space-y-6 > * + *,
    .space-y-7 > * + *,
    .space-y-8 > * + * {
        margin-top: 0 !important;
    }

    /* Executive Print Header (Only visible in print/PDF) */
    .print-exec-header {
        display: block !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding-bottom: 6px !important;
        border-bottom: 2px solid #a38c29 !important;
    }

    /* Audit Equality Banner in Print */
    #audit-balance-banner {
        display: flex !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        border-width: 1.5px !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #audit-balance-banner .w-11.h-11 {
        width: 24px !important;
        height: 24px !important;
        border-radius: 6px !important;
        font-size: 9pt !important;
    }
    #audit-balance-banner h3 {
        font-size: 8pt !important;
    }
    #audit-balance-banner p {
        font-size: 6.8pt !important;
    }
    #audit-balance-banner .text-right {
        padding: 3px 8px !important;
    }

    /* Trial Balance Table Card in Print */
    #trial-balance-table-card {
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        clear: both !important;
        page-break-before: auto !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
    }
    #trial-balance-table-card > div:first-child {
        padding: 6px 10px !important;
    }

    /* Table Formatting for Print: Landscape, Proportional Columns, Exact Same Colors as Page */
    table {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7.5pt !important;
    }
    thead {
        display: table-header-group !important;
    }
    tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    th, td {
        padding: 4px 5px !important;
        box-sizing: border-box !important;
        line-height: 1.25 !important;
        text-align: left !important;
        vertical-align: middle !important;
    }
    thead th, th {
        background-color: #17365D !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-size: 7pt !important;
        font-weight: 800 !important;
        padding: 5px 4px !important;
        line-height: 1.25 !important;
        letter-spacing: 0.02em !important;
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: normal !important;
        hyphens: none !important;
        vertical-align: top !important;
        text-align: center !important;
    }

    /* ── Group Rows: Subtle Soft Amber Tint Same Like in Page (bg-amber-50/60) ── */
    tr.group-row {
        background-color: #fffdf5 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: 800 !important;
        border-top: 1pt solid #fde68a !important;
        border-bottom: 1pt solid #fde68a !important;
    }
    tr.group-row td {
        background-color: #fffdf5 !important;
        font-weight: 800 !important;
        font-size: 7.5pt !important;
        color: #0f172a !important;
        padding: 4.5px 5px !important;
        text-align: left !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    tr.group-row td span[class*="bg-"] {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        padding: 1.5px 6px !important;
        border-radius: 4px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* ── Sub-Account Rows: Clean Pure White Same Like in Page (bg-white) ── */
    tr.account-row {
        background-color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    tr.account-row td {
        background-color: #ffffff !important;
        color: #1e293b !important;
        font-weight: 500 !important;
        padding: 3.5px 5px !important;
        border-bottom: 0.5pt solid #f1f5f9 !important;
        text-align: left !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    tr.account-row td.font-mono, 
    tr.account-row td .font-mono, 
    tr.account-row td [class*="font-mono"] {
        white-space: nowrap !important;
        font-size: 7.5pt !important;
        font-variant-numeric: tabular-nums !important;
        text-align: left !important;
    }

    /* Preserve Colored Numbers on Sub-Accounts in Print */
    tr.account-row td[class*="text-indigo"], tr.account-row td .text-indigo-700 { color: #4338ca !important; }
    tr.account-row td[class*="text-amber"], tr.account-row td .text-amber-700 { color: #b45309 !important; }
    tr.account-row td[class*="text-blue"], tr.account-row td .text-blue-700 { color: #1d4ed8 !important; }
    tr.account-row td[class*="text-rose"], tr.account-row td .text-rose-700 { color: #be123c !important; }

    /* Footer Row */
    tfoot tr {
        background-color: #ffffff !important;
        border-top: 2pt solid #a38c29 !important;
        border-bottom: 2pt solid #a38c29 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    tfoot td {
        background-color: #ffffff !important;
        padding: 5px 5px !important;
        font-size: 7.8pt !important;
        font-weight: 900 !important;
        white-space: nowrap !important;
        text-align: left !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs, .shadow-xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }
}
</style>

<div class="w-full space-y-6" x-data="trialBalanceApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="print-exec-header hidden print:block mb-4 border-b-2 border-[#a38c29] pb-3">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Accounting & Audited Financial Reports</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">CONSOLIDATED GENERAL LEDGER TRIAL BALANCE REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Financial Period:</span> <span class="font-mono font-bold text-[#a38c29]">{{ $financialYearLabel }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Date Range:</span> <span class="font-mono font-bold text-slate-800">{{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Project:</span> <span class="font-mono font-bold text-slate-800" x-text="selectedProjectName">{{ (string)$selectedProjectId === 'all' ? 'All Projects' : ($allProjects->firstWhere('id', $selectedProjectId)?->name ?? 'All Projects') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Report View:</span> <span class="font-mono font-bold text-slate-800" x-text="viewMode === 'detailed' ? 'Detailed View' : 'Summary View'">{{ ucfirst($viewMode) }}</span></div>
            </div>
        </div>
    </div>

    <!-- ── 1. HEADER & BREADCRUMBS ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4 print:hidden">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span class="text-slate-500 uppercase">ACCOUNTING & FINANCE</span>
                <span>›</span>
                <span class="text-[#a38c29] font-black uppercase tracking-wider">TRIAL BALANCE</span>
            </nav>
            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <div class="p-2 bg-amber-50 rounded-xl text-[#a38c29] border border-amber-200/80 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <span>Trial Balance</span>
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                View consolidated trial balance for the selected period and project.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-center">
            <span class="px-3.5 py-2 rounded-xl text-xs font-black bg-amber-50 text-[#8a7522] border border-amber-200/90 shadow-2xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#a38c29] animate-pulse"></span>
                <span>Audited Ledger Verification</span>
            </span>

            <!-- <button type="button" @click="exportToExcel()" class="h-[38px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>EXPORT EXCEL</span>
            </button> -->

            <!-- <button type="button" onclick="window.print()" class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>PRINT</span>
            </button> -->
        </div>
    </div>

    <!-- ── 2. ULTRA-CLEAN MODERN SEARCH & FILTER PANEL (MATCHING COMMON ERP THEME) ── -->
    <form id="trialBalanceForm" action="{{ route('reports.trial_balance') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all print:hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 flex-1">
                
                {{-- 1. Search Account Code / Name (Instant in-memory filtering) --}}
                <div class="relative group col-span-1 sm:col-span-2 lg:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Code, Account..." x-model="searchQuery" autocomplete="off"
                           class="w-full erp-search-input pl-10 pr-9">
                    <div x-show="searchQuery" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                        <button type="button" @click="searchQuery = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 2. Project Filter (Custom Gold Popover with Search, Default First) --}}
                <div class="relative w-full" @click.outside="projectDropdownOpen = false">
                    <input type="hidden" name="project_id" :value="selectedProjectId">
                    <button type="button"
                            @click="projectDropdownOpen = !projectDropdownOpen; if(projectDropdownOpen) { $nextTick(() => $refs.projSearchInput?.focus()); }"
                            class="erp-dropdown-trigger"
                            :class="projectDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="selectedProjectId && selectedProjectId !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="selectedProjectName">All Projects</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="selectedProjectId && selectedProjectId !== 'all'">
                                <span @click.stop="selectProject('all', 'All Projects')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="projectDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="projectDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover min-w-[240px]" style="display: none;">
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="projectSearch" x-ref="projSearchInput" placeholder="Search project..."
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="projectDropdownOpen = false">
                                <template x-if="projectSearch">
                                    <button type="button" @click="projectSearch = ''; $refs.projSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-56">
                            <div @click="selectProject('all', 'All Projects')" 
                                 x-show="!projectSearch || 'All Projects'.toLowerCase().includes(projectSearch.toLowerCase())"
                                 class="erp-dropdown-option" :class="selectedProjectId === 'all' ? 'selected-all' : ''">
                                <span>All Projects</span>
                            </div>
                            @foreach($allProjects as $proj)
                                <div @click="selectProject('{{ $proj->id }}', '{{ addslashes($proj->name) }}')"
                                     x-show="!projectSearch || '{{ strtolower(addslashes($proj->name)) }}'.includes(projectSearch.toLowerCase())"
                                     class="erp-dropdown-option" :class="String(selectedProjectId) === '{{ (string)$proj->id }}' ? 'selected' : ''">
                                    <span class="truncate">{{ $proj->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 3. Period Type Dropdown --}}
                <div class="relative w-full" @click.outside="periodDropdownOpen = false">
                    <input type="hidden" name="period_type" :value="periodType">
                    <button type="button" @click="periodDropdownOpen = !periodDropdownOpen" class="erp-dropdown-trigger" :class="periodDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="periodTypeLabel">Financial Year</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="periodDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="periodDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectPeriodType('fy', 'Financial Year')" class="erp-dropdown-option" :class="periodType === 'fy' ? 'selected-all' : ''">
                                <span>Financial Year</span>
                            </div>
                            <div @click="selectPeriodType('quarter', 'Quarter')" class="erp-dropdown-option" :class="periodType === 'quarter' ? 'selected' : ''">
                                <span>Quarter</span>
                            </div>
                            <div @click="selectPeriodType('month', 'Month')" class="erp-dropdown-option" :class="periodType === 'month' ? 'selected' : ''">
                                <span>Month</span>
                            </div>
                            <div @click="selectPeriodType('custom', 'Custom Date Range')" class="erp-dropdown-option" :class="periodType === 'custom' ? 'selected' : ''">
                                <span>Custom Date Range</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. From Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="from_date" id="fromDateInput" x-model="fromDate" @change="submitPeriodDates()"
                           title="From Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 5. To Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="to_date" id="toDateInput" x-model="toDate" @change="submitPeriodDates()"
                           title="To Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 6. Report Level / View Mode (Instant In-Memory Toggle) --}}
                <div class="relative w-full" @click.outside="viewModeDropdownOpen = false">
                    <input type="hidden" name="view_mode" :value="viewMode">
                    <button type="button" @click="viewModeDropdownOpen = !viewModeDropdownOpen" class="erp-dropdown-trigger" :class="viewModeDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="viewMode === 'detailed' ? 'Detailed View' : 'Summary View'">Detailed View</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="viewModeDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="viewModeDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectViewMode('detailed')" class="erp-dropdown-option" :class="viewMode === 'detailed' ? 'selected-all' : ''">
                                <span>Detailed View</span>
                            </div>
                            <div @click="selectViewMode('summary')" class="erp-dropdown-option" :class="viewMode === 'summary' ? 'selected' : ''">
                                <span>Summary View</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- 7. Hide Zero Balance Checkbox (Instant In-Memory Toggle) --}}
                <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-[#a38c29] h-[38px] transition cursor-pointer select-none">
                    <input type="checkbox" x-model="hideZero" class="w-4 h-4 rounded text-[#a38c29] border-slate-300 focus:ring-[#a38c29] cursor-pointer">
                    <span class="text-xs font-bold text-slate-700 whitespace-nowrap">Hide Zero</span>
                </label>

                {{-- 8. Signature Gold RESET FILTERS Button --}}
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8C7A2E] hover:bg-[#786826] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>
    </form>

    <!-- ── 3. AUDIT EQUALITY & BALANCE BANNER ── -->
    @if($isBalanced)
    <div id="audit-balance-banner" class="rounded-2xl bg-emerald-50/90 border-2 border-emerald-500/80 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20 text-xl font-bold">
                ✓
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-emerald-950 uppercase tracking-wide flex items-center gap-2">
                    <span>TRIAL BALANCE EQUAL & BALANCED</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-200/80 text-emerald-900">Audited</span>
                </h3>
                <p class="text-xs text-emerald-800 font-medium mt-0.5">
                    Total debits and credits are equal (<strong class="font-mono font-bold">₹ {{ number_format($grandTotalDebit, 2) }}</strong>) for the selected period.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-emerald-200 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Financial Period</span>
            <span class="text-xs font-mono font-black text-slate-800">{{ $financialYearLabel }}</span>
        </div>
    </div>
    @else
    <div id="audit-balance-banner" class="rounded-2xl bg-rose-50 border-2 border-rose-500 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20 text-xl font-bold">
                !
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-rose-950 uppercase tracking-wide">
                    TRIAL BALANCE VARIANCE DETECTED
                </h3>
                <p class="text-xs text-rose-800 font-medium mt-0.5">
                    Total debits (₹ {{ number_format($grandTotalDebit, 2) }}) and credits (₹ {{ number_format($grandTotalCredit, 2) }}) differ by ₹ {{ number_format($diff, 2) }}.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-rose-200 shadow-2xs">
            <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider block">Variance Amount</span>
            <span class="text-sm font-mono font-black text-rose-700">₹ {{ number_format($diff, 2) }}</span>
        </div>
    </div>
    @endif

    <!-- ── 4. TRIAL BALANCE DATA GRID (BRAND GOLD HEADER THEME) ── -->
    <div id="trial-balance-table-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        {{-- Table Card Header with Title, Verification & Export/Print Buttons (Matching Customer Collection Dues) --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white print:px-2 print:py-2">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Trial Balance Register
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">
                    <span>Audited General Ledger Verification</span>
                    <span class="text-slate-300 mx-1">•</span>
                    <span class="font-mono font-bold text-slate-700">{{ $financialYearLabel }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-2.5 print:hidden">
                <button type="button" @click="exportToExcel()" class="h-[38px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" onclick="window.print()" class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="trialBalanceTable" class="w-full text-xs text-left border-collapse">
                <colgroup class="print:table-column-group">
                    <col style="width: 12%;">
                    <col style="width: 36%;">
                    <col style="width: 13%;">
                    <col style="width: 13%;">
                    <col style="width: 13%;">
                    <col style="width: 13%;">
                </colgroup>
                <thead>
                    <tr class="erp-table-header bg-[#17365D] text-white border-b-2 border-slate-700 text-[10.5px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 w-36 text-center text-white font-extrabold tracking-wider">ACCOUNT CODE</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold tracking-wider">ACCOUNT NAME / GROUP</th>
                        <th class="px-5 py-3.5 text-center w-44 text-white font-extrabold tracking-wider">OPENING BALANCE (₹)</th>
                        <th class="px-5 py-3.5 text-center w-44 text-white font-extrabold tracking-wider">PERIOD DEBIT (₹)</th>
                        <th class="px-5 py-3.5 text-center w-44 text-white font-extrabold tracking-wider">PERIOD CREDIT (₹)</th>
                        <th class="px-5 py-3.5 text-center w-48 text-white font-extrabold tracking-wider">CLOSING BALANCE (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    
                    @foreach($groupsData as $grpKey => $grp)
                        @php
                            $hasAccounts = !empty($grp['accounts']);
                        @endphp

                        <!-- Group Header Row (Rich Gold/Amber Themed) -->
                        <tr class="group-row bg-amber-50/60 hover:bg-amber-100/60 transition-colors font-bold border-t-2 border-b border-amber-200/70 cursor-pointer select-none"
                            x-show="isGroupVisible('{{ $grp['code'] }}')"
                            @click="toggleGroup('{{ $grp['code'] }}')">
                            
                            <td class="px-5 py-3 text-left font-mono font-black text-slate-900 text-xs">
                                <div class="flex items-center gap-2">
                                    <template x-if="viewMode === 'detailed' && {{ $hasAccounts ? 'true' : 'false' }}">
                                        <div class="w-5 h-5 rounded-md bg-white border border-amber-300 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs print:hidden">
                                            <svg class="w-3 h-3 transition-transform duration-200"
                                                 :class="isGroupCollapsed('{{ $grp['code'] }}') ? '-rotate-90' : 'rotate-0'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </template>
                                    <span class="px-2.5 py-0.5 bg-[#a38c29] text-white rounded-md text-[11px] font-mono font-black shadow-2xs">
                                        {{ $grp['code'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-3 text-left font-black text-slate-900 uppercase tracking-wide text-xs">
                                <div class="flex items-center gap-2">
                                    <span>{{ $grp['name'] }}</span>
                                    @if($hasAccounts)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-white border border-amber-200 text-[#8a7522] font-extrabold lowercase print:hidden">
                                            {{ count($grp['accounts']) }} accounts
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3 text-left font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['opening_balance'], 2) }} <span class="font-extrabold {{ ($grp['opening_side'] ?? 'Dr') === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">{{ $grp['opening_side'] ?? 'Dr' }}</span>
                            </td>

                            <td class="px-5 py-3 text-left font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_debit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-left font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_credit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-left font-mono font-black text-xs {{ $grp['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                ₹{{ number_format($grp['closing_balance'], 2) }} <span class="font-extrabold">{{ $grp['closing_side'] }}</span>
                            </td>
                        </tr>

                        <!-- Sub-Accounts (Detailed View) -->
                        @if($hasAccounts)
                            @foreach($grp['accounts'] as $acc)
                            <tr x-show="viewMode === 'detailed' && !isGroupCollapsed('{{ $grp['code'] }}') && isAccountVisible({{ $acc['is_zero'] ? 'true' : 'false' }}, '{{ $acc['code'] }}', '{{ addslashes($acc['name']) }}')"
                                class="account-row hover:bg-amber-50/30 transition-colors bg-white">
                                
                                <td class="px-5 py-2.5 text-left font-mono text-slate-500 pl-12 text-[11px] font-bold">
                                    {{ $acc['code'] }}
                                </td>

                                <td class="px-5 py-2.5 text-left">
                                    <span class="font-semibold text-slate-800">{{ $acc['name'] }}</span>
                                </td>

                                <td class="px-5 py-2.5 text-left font-mono font-bold text-slate-700 text-xs">
                                    {{ number_format(abs($acc['opening_balance']), 2) }}@if($acc['opening_balance'] != 0) <span class="font-extrabold {{ $acc['opening_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">{{ $acc['opening_side'] }}</span>@endif
                                </td>

                                <td class="px-5 py-2.5 text-left font-mono font-bold text-indigo-700 text-xs">
                                    {{ $acc['period_debit'] > 0 ? '₹'.number_format($acc['period_debit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-left font-mono font-bold text-amber-700 text-xs">
                                    {{ $acc['period_credit'] > 0 ? '₹'.number_format($acc['period_credit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-left font-mono font-black text-xs {{ $acc['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                    @if($acc['closing_balance'] > 0)
                                        ₹{{ number_format($acc['closing_balance'], 2) }} <span class="font-extrabold">{{ $acc['closing_side'] }}</span>
                                    @else
                                        0.00
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @endif

                    @endforeach

                </tbody>
                <tfoot>
                    <tr class="bg-white text-slate-900 font-black text-xs uppercase border-t-2 border-b-2 border-[#a38c29] shadow-2xs">
                        <td class="px-5 py-4 text-left font-black tracking-wider text-slate-900 text-xs" colspan="2">
                            TOTAL
                        </td>
                        <td class="px-5 py-4 text-left font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalOpening, 2) }} <span class="font-extrabold">{{ $grandTotalOpeningSide ?? '' }}</span>
                        </td>
                        <td class="px-5 py-4 text-left font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalDebit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-left font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalCredit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-left font-mono font-black text-slate-400 text-xs">
                            —
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- ── SCRIPTS FOR PERIOD PRESETS, ALPINE APP & EXCEL EXPORT ── -->
<script>
function trialBalanceApp() {
    return {
        collapsedGroups: {},
        searchQuery: '',
        viewMode: '{{ $viewMode }}',
        hideZero: {{ $hideZero ? 'true' : 'false' }},
        selectedProjectId: '{{ (string)$selectedProjectId }}',
        selectedProjectName: '{{ (string)$selectedProjectId === 'all' ? 'All Projects' : addslashes($allProjects->firstWhere('id', $selectedProjectId)?->name ?? 'Select Project') }}',
        projectDropdownOpen: false,
        projectSearch: '',
        periodType: '{{ $periodType }}',
        periodTypeLabel: '{{ $periodType === 'fy' ? 'Financial Year' : ($periodType === 'quarter' ? 'Quarter' : ($periodType === 'month' ? 'Month' : 'Custom Date Range')) }}',
        periodDropdownOpen: false,
        viewModeDropdownOpen: false,
        fromDate: '{{ $fromDate }}',
        toDate: '{{ $toDate }}',
        groups: @json(array_values($groupsData)),
        grandTotalOpening: {{ (float)$grandTotalOpening }},
        grandTotalOpeningSide: '{{ $grandTotalOpeningSide ?? '' }}',
        grandTotalDebit: {{ (float)$grandTotalDebit }},
        grandTotalCredit: {{ (float)$grandTotalCredit }},
        isBalanced: {{ $isBalanced ? 'true' : 'false' }},
        varianceDiff: {{ (float)$diff }},
        financialYearLabel: '{{ addslashes($financialYearLabel) }}',

        toggleGroup(code) {
            this.collapsedGroups[code] = !this.collapsedGroups[code];
        },
        isGroupCollapsed(code) {
            return !!this.collapsedGroups[code];
        },
        selectProject(id, name) {
            this.selectedProjectId = id;
            this.selectedProjectName = name;
            this.projectDropdownOpen = false;
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        selectPeriodType(type, label) {
            this.periodType = type;
            this.periodTypeLabel = label;
            this.periodDropdownOpen = false;
            const today = new Date();
            if (type === 'fy') {
                const fyStart = today.getMonth() >= 3 ? today.getFullYear() : today.getFullYear() - 1;
                this.fromDate = fyStart + '-04-01';
                this.toDate = (fyStart + 1) + '-03-31';
            } else if (type === 'quarter') {
                const curMonth = today.getMonth();
                const qStartMonth = Math.floor(curMonth / 3) * 3;
                const qStart = new Date(today.getFullYear(), qStartMonth, 1);
                const qEnd = new Date(today.getFullYear(), qStartMonth + 3, 0);
                this.fromDate = this.formatDateYmd(qStart);
                this.toDate = this.formatDateYmd(qEnd);
            } else if (type === 'month') {
                const mStart = new Date(today.getFullYear(), today.getMonth(), 1);
                const mEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                this.fromDate = this.formatDateYmd(mStart);
                this.toDate = this.formatDateYmd(mEnd);
            }
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        submitPeriodDates() {
            this.periodType = 'custom';
            this.periodTypeLabel = 'Custom Date Range';
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        formatDateYmd(d) {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        selectViewMode(mode) {
            this.viewMode = mode;
            this.viewModeDropdownOpen = false;
        },
        isAccountVisible(isZero, code, name) {
            if (this.hideZero && isZero) return false;
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase().trim();
                const matchCode = String(code).toLowerCase().includes(q);
                const matchName = String(name).toLowerCase().includes(q);
                if (!matchCode && !matchName) return false;
            }
            return true;
        },
        isGroupVisible(grpCode) {
            const grp = this.groups.find(g => String(g.code) === String(grpCode));
            if (!grp) return true;
            if (!this.searchQuery && !this.hideZero) return true;
            const matchingAccounts = (grp.accounts || []).filter(a => this.isAccountVisible(a.is_zero, a.code, a.name));
            if (matchingAccounts.length > 0) return true;
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase().trim();
                if (String(grp.code).toLowerCase().includes(q) || String(grp.name).toLowerCase().includes(q)) {
                    return true;
                }
            }
            return !this.hideZero && !this.searchQuery;
        },
        resetFilters() {
            this.searchQuery = '';
            this.viewMode = 'detailed';
            this.hideZero = false;
            this.projectDropdownOpen = false;
            this.periodDropdownOpen = false;
            this.viewModeDropdownOpen = false;
            this.projectSearch = '';
        },

        // ── EXECUTIVE CLIENT-SIDE EXCEL EXPORT (EXCELJS) ──
        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading. Please try again in a moment.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'TABASCO Hindustan Infra Developers';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Trial Balance', {
                    views: [{ showGridLines: true }]
                });

                const slateBorder = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };

                const dataBorder = {
                    top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };

                const groupBorder = {
                    top: { style: 'medium', color: { argb: 'FFB45309' } },
                    bottom: { style: 'medium', color: { argb: 'FFB45309' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };

                sheet.columns = [
                    { key: 'code', width: 20 },
                    { key: 'title', width: 44 },
                    { key: 'opening', width: 24 },
                    { key: 'debit', width: 24 },
                    { key: 'credit', width: 24 },
                    { key: 'closing', width: 26 }
                ];

                // ── Row 1: Top Empty Spacer ──
                sheet.mergeCells('A1:F1');
                const spacer1 = sheet.getCell('A1');
                spacer1.value = '';
                spacer1.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(1).height = 18;

                // ── Row 2: Main Title Banner (#2C3E50 Dark Slate Navy) ──
                sheet.mergeCells('A2:F2');
                const titleCell = sheet.getCell('A2');
                titleCell.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - TRIAL BALANCE REPORT';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 30;
                sheet.getRow(2).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 3: Subtitle Banner (#007398 Ocean Teal Blue) ──
                sheet.mergeCells('A3:F3');
                const subTitleCell = sheet.getCell('A3');
                const periodText = this.financialYearLabel + ' (' + this.fromDate + ' to ' + this.toDate + ')';
                subTitleCell.value = 'Consolidated General Ledger Trial Balance | Period: ' + periodText + ' | Project: ' + this.selectedProjectName;
                subTitleCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                subTitleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                subTitleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 24;
                sheet.getRow(3).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 4: Section Banner (#006039 Deep Forest Green) ──
                sheet.mergeCells('A4:F4');
                const secCell = sheet.getCell('A4');
                secCell.value = 'AUDITED TRIAL BALANCE STATEMENT (' + (this.viewMode === 'detailed' ? 'DETAILED VIEW' : 'SUMMARY VIEW') + ')';
                secCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                secCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                secCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(4).height = 24;
                sheet.getRow(4).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 5: Middle Empty Spacer ──
                sheet.mergeCells('A5:F5');
                const spacer2 = sheet.getCell('A5');
                spacer2.value = '';
                spacer2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(5).height = 14;

                // ── Row 6: Table Headers (#34495E Navy Slate Blue) ──
                const headers = [
                    'ACCOUNT CODE',
                    'ACCOUNT NAME / GROUP',
                    'OPENING BALANCE (₹)',
                    'PERIOD DEBIT (₹)',
                    'PERIOD CREDIT (₹)',
                    'CLOSING BALANCE (₹)'
                ];

                const hRow = sheet.getRow(6);
                hRow.values = headers;
                hRow.height = 30;
                hRow.eachCell({ includeEmpty: true }, (c, col) => {
                    c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    c.alignment = { vertical: 'middle', horizontal: 'center' };
                    c.border = slateBorder;
                });

                // ── Rows 7+: Data Rows ──
                let rIdx = 7;
                let sumDebit = 0;
                let sumCredit = 0;

                this.groups.forEach((grp) => {
                    if (!this.isGroupVisible(grp.code)) return;

                    // Group Header Row
                    const gRow = sheet.getRow(rIdx);
                    const grpOpening = Number(grp.opening_balance || 0);
                    const grpDebit = Number(grp.period_debit || 0);
                    const grpCredit = Number(grp.period_credit || 0);
                    const grpClosing = Number(grp.closing_balance || 0);
                    const grpOpeningSide = grp.opening_side || 'Dr';
                    const grpClosingSide = grp.closing_side || 'Dr';

                    gRow.values = [
                        String(grp.code || ''),
                        String(grp.name || '').toUpperCase(),
                        `${grpOpening.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${grpOpeningSide}`,
                        grpDebit,
                        grpCredit,
                        `${grpClosing.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${grpClosingSide}`
                    ];

                    gRow.height = 26;
                    gRow.eachCell({ includeEmpty: true }, (c, col) => {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFEF3C7' } }; // Soft Amber Group Highlight
                        c.border = groupBorder;
                        c.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FF1E293B' } };
                        c.alignment = { vertical: 'middle', horizontal: 'left' };

                        if (col === 4 || col === 5) {
                            c.numFmt = '#,##0.00';
                        }
                    });

                    sumDebit += grpDebit;
                    sumCredit += grpCredit;
                    rIdx++;

                    // Sub-Accounts (if Detailed View and Group has accounts)
                    if (this.viewMode === 'detailed' && Array.isArray(grp.accounts)) {
                        let accIdx = 0;
                        grp.accounts.forEach((acc) => {
                            if (!this.isAccountVisible(acc.is_zero, acc.code, acc.name)) return;

                            const aRow = sheet.getRow(rIdx);
                            const accOpening = Number(acc.opening_balance || 0);
                            const accDebit = Number(acc.period_debit || 0);
                            const accCredit = Number(acc.period_credit || 0);
                            const accClosing = Number(acc.closing_balance || 0);
                            const accOpeningSide = acc.opening_side || 'Dr';
                            const accClosingSide = acc.closing_side || 'Dr';

                            aRow.values = [
                                '   ' + String(acc.code || ''),
                                '      ' + String(acc.name || ''),
                                accOpening !== 0 ? `${Math.abs(accOpening).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${accOpeningSide}` : '0.00',
                                accDebit,
                                accCredit,
                                accClosing > 0 ? `${accClosing.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${accClosingSide}` : '0.00'
                            ];

                            aRow.height = 22;
                            // Alternating zebra background: #F0F8FF (Ice Blue) / #FFFFFF (White)
                            const rowBg = (accIdx % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';

                            aRow.eachCell({ includeEmpty: true }, (c, col) => {
                                c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                                c.border = dataBorder;
                                c.alignment = { vertical: 'middle', horizontal: 'left' };

                                if (col === 1) {
                                    c.font = { name: 'Calibri', size: 9.5, color: { argb: 'FF64748B' } };
                                } else if (col === 2) {
                                    c.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF334155' } };
                                } else if (col === 4) {
                                    c.font = { name: 'Calibri', size: 9.5, bold: accDebit > 0, color: { argb: accDebit > 0 ? 'FF4338CA' : 'FF64748B' } };
                                    c.numFmt = '#,##0.00';
                                } else if (col === 5) {
                                    c.font = { name: 'Calibri', size: 9.5, bold: accCredit > 0, color: { argb: accCredit > 0 ? 'FFB45309' : 'FF64748B' } };
                                    c.numFmt = '#,##0.00';
                                } else if (col === 6) {
                                    c.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: accClosingSide === 'Dr' ? 'FF1D4ED8' : 'FFE11D48' } };
                                } else {
                                    c.font = { name: 'Calibri', size: 9.5, color: { argb: 'FF0F172A' } };
                                }
                            });

                            accIdx++;
                            rIdx++;
                        });
                    }
                });

                // ── Footer Row: Grand Total Summary (#2C3E50 Dark Slate Navy, 36pt Height, Bold White 11pt Text) ──
                const totRow = sheet.getRow(rIdx);
                totRow.height = 36;

                sheet.getCell(`A${rIdx}`).value = 'TOTAL AUDITED TRIAL BALANCE';
                sheet.getCell(`B${rIdx}`).value = '';
                sheet.getCell(`C${rIdx}`).value = `${Number(this.grandTotalOpening || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${this.grandTotalOpeningSide || ''}`;
                sheet.getCell(`D${rIdx}`).value = Number(this.grandTotalDebit || sumDebit || 0);
                sheet.getCell(`E${rIdx}`).value = Number(this.grandTotalCredit || sumCredit || 0);
                sheet.getCell(`F${rIdx}`).value = this.isBalanced ? 'AUDITED & BALANCED' : `VARIANCE: ₹ ${Number(this.varianceDiff || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                sheet.mergeCells(`A${rIdx}:B${rIdx}`);

                for (let c = 1; c <= 6; c++) {
                    const cell = sheet.getRow(rIdx).getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: false };
                    cell.border = slateBorder;
                    if (c === 4 || c === 5) {
                        cell.numFmt = '#,##0.00';
                    }
                }

                // Generate and trigger download
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Trial_Balance_${new Date().toISOString().slice(0, 10)}.xlsx`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Excel Export Error:', err);
                alert('An error occurred while generating Excel. Please check console for details.');
            }
        }
    };
}

// Global alias for compatibility
window.exportTrialBalanceToExcel = function() {
    const el = document.querySelector('[x-data]');
    if (el && el._x_dataStack && el._x_dataStack[0] && typeof el._x_dataStack[0].exportToExcel === 'function') {
        el._x_dataStack[0].exportToExcel();
    }
};
</script>

</x-erp-layout>

