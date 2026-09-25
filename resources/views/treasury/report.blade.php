<x-erp-layout title="Treasury Cash Flow Report" headerTitle="Bank & Treasury Management">

{{-- ExcelJS Corporate Export Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
/* CRITICAL: @page must be defined at the stylesheet root for Chromium/WebKit to honor landscape mode */
@page {
    size: A4 landscape;
    margin: 5mm 6mm 5mm 6mm;
}

@media screen {
    .print-exec-header,
    .reports-banner-container.print-exec-banner {
        display: none !important;
    }
}

@media print {
    @page {
        size: A4 landscape;
        margin: 6mm 8mm 6mm 8mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 7.5pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 1. Complete Web Clutter & Sidebar Removal */
    .web-only-workspace,
    .print\:hidden,
    [class*="print:hidden"],
    .treasury-pagination-bar,
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
    .max-w-\[1800px\], .max-w-3xl {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* 3. Executive Corporate Letterhead Header (Exact Exchange Report Standard) */
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

    /* 4. Top Banner Print Styling (Exact Exchange Report Golden Banner) */
    .reports-banner-container.print-exec-banner {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding: 12px 18px !important;
        border: 1px solid #e6d594 !important;
        border-radius: 10px !important;
        background: #fffdf5 !important;
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        box-sizing: border-box !important;
    }

    /* 5. Table Container: Completely Transparent Block Flow */
    .treasury-table-card {
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

    .treasury-table-header-box {
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

    /* 6. Table Formatting: Clean Modern Corporate Styling from Picture 3 */
    table#treasuryReportTable {
        display: table !important;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7.2pt !important;
        margin: 0 !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table#treasuryReportTable colgroup {
        display: table-column-group !important;
    }
    table#treasuryReportTable thead {
        display: table-header-group !important;
    }
    table#treasuryReportTable thead tr {
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }
    table#treasuryReportTable thead th {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        font-size: 6.8pt !important;
        font-weight: 800 !important;
        padding: 7.5px 5px !important;
        border: none !important;
        border-right: 0.5pt solid rgba(255,255,255,0.35) !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        letter-spacing: 0.25px !important;
        text-transform: uppercase !important;
        box-sizing: border-box !important;
    }
    table#treasuryReportTable tbody {
        display: table-row-group !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table#treasuryReportTable tbody tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table#treasuryReportTable tbody td {
        padding: 6.5px 5px !important;
        font-size: 7pt !important;
        border: none !important;
        border-bottom: 0.5pt solid #e2e8f0 !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
        line-height: 1.35 !important;
    }
    table#treasuryReportTable tbody tr:nth-child(even) td {
        background-color: #fbfaf6 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    table#treasuryReportTable tfoot {
        display: table-footer-group !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table#treasuryReportTable tfoot td {
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
    table#treasuryReportTable td div, 
    table#treasuryReportTable td span {
        font-size: inherit;
    }
    table#treasuryReportTable td .text-\[10px\], 
    table#treasuryReportTable td .text-\[9px\] {
        font-size: 6pt !important;
    }
    table#treasuryReportTable td .rounded-full, 
    table#treasuryReportTable td .rounded {
        font-size: 5.5pt !important;
        padding: 1.5px 4px !important;
        line-height: 1.1 !important;
    }

    /* Allow standard text cells to wrap nicely */
    .whitespace-nowrap:not(.amount-cell):not(th) {
        white-space: normal !important;
    }
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }

    /* Strict formatting for monetary cells */
    .amount-cell, table#treasuryReportTable td.amount-cell {
        white-space: nowrap !important;
        font-size: 7.2pt !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        text-align: right !important;
        letter-spacing: -0.2px !important;
        padding-right: 6px !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto" 
     x-data="treasuryReportApp()" 
     x-init="$watch('filters', () => { currentPage = 1; }, { deep: true })">

    <!-- ── 1. EXECUTIVE PRINT HEADER (EXACT EXCHANGE REPORT STANDARD) ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Corporate Treasury & Risk Management</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">COMPANY BANK ACCOUNTS CASH FLOW & TREASURY REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Account Scope:</span> <span class="font-bold text-[#a38c29]" x-text="filters.bank_account_id === 'all' ? 'All Company Accounts' : activeBankName"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Period Filter:</span> <span class="font-mono font-bold text-slate-800" x-text="(filters.date_from || 'Beginning') + ' → ' + (filters.date_to || 'Today')"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredTransactions.length + ' Entries'"></span></div>
            </div>
        </div>
    </div>

    <!-- ── 2. EXECUTIVE FINANCIAL SUMMARY BANNER (EXACT EXCHANGE REPORT GOLDEN BANNER) ── -->
    <div class="reports-banner-container print-exec-banner hidden print:flex items-center justify-between gap-4 p-4 rounded-xl border border-[#e6d594] bg-[#fffdf5] mb-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#8a7522] shadow-2xs shrink-0">
                <svg class="w-5 h-5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Treasury Cash Flow Report</h3>
                    <span class="text-[9px] font-bold text-[#8a7522] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Audit Trail</span>
                </div>
                <p class="text-[9.5px] text-slate-600 mt-1 font-medium">Consolidated real-time audit ledger of all cash inflows, contractor disbursements, site expenses & contra withdrawals.</p>
            </div>
        </div>

        {{-- Right Side: Clean Stat Tiles matching Exchange Report --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Cash Inflow</span>
                <span class="text-xs font-black text-emerald-900 font-mono" x-text="'+₹' + formatMoney(totalInflow)"></span>
            </div>
            <div class="px-3.5 py-2 bg-rose-50 border border-rose-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-rose-700">Total Cash Outflow</span>
                <span class="text-xs font-black text-rose-900 font-mono" x-text="'-₹' + formatMoney(totalOutflow)"></span>
            </div>
            <div class="px-3.5 py-2 bg-[#a38c29]/10 border border-[#a38c29]/25 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-[#8a7522]">Net Cash Delta</span>
                <span class="text-xs font-black font-mono"
                      :class="netCashFlow >= 0 ? 'text-[#5c4a10]' : 'text-rose-700'"
                      x-text="(netCashFlow >= 0 ? '+' : '-') + '₹' + formatMoney(Math.abs(netCashFlow))"></span>
            </div>
            <div class="px-3.5 py-2 bg-slate-100 border border-slate-200 rounded-xl text-left">
                <span class="block text-[8px] font-black uppercase tracking-widest text-slate-600">Company Bank Balance</span>
                <span class="text-xs font-black text-slate-800 font-mono" x-text="'₹' + formatMoney(activeBankBalance)"></span>
            </div>
        </div>
    </div>

    <!-- ── 3. WEB-ONLY WORKSPACE (Top Banner, KPI Cards, Category Breakdown, Filter Toolbar) ── -->
    <div class="web-only-workspace space-y-6">
        {{-- ── 1. Top Executive Page Header Card (Web View Only) ── --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 relative overflow-hidden print-header-card print:hidden">
        {{-- Subtle Luxury Gold Ambient Corner Glow --}}
        <div class="absolute -right-20 -top-20 w-72 h-72 bg-gradient-to-br from-[#a38c29]/12 via-[#a38c29]/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            
            {{-- Left: Breadcrumb, Emblem, Title & Subtitle --}}
            <div class="flex items-start sm:items-center gap-4">
                {{-- Bank & Treasury Architectural Emblem --}}
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29]/20 via-[#a38c29]/10 to-[#8a7522]/5 text-[#8a7522] flex items-center justify-center shrink-0 border border-[#a38c29]/30 shadow-xs">
                    <svg class="w-6 h-6 text-[#a38c29]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M12 3l9 4.5H3L12 3z"/>
                    </svg>
                </div>

                <div>
                    {{-- Clean Professional Breadcrumb --}}
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 mb-1.5 flex-wrap">
                        <a href="{{ route('dashboard') }}" class="hover:text-slate-700 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            <span>Finance & Analytics</span>
                        </a>
                        <span class="text-slate-300">/</span>
                        <a href="{{ route('treasury.dashboard') }}" class="hover:text-slate-700 transition">Bank & Treasury Management</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-[#a38c29] font-bold">Treasury Report</span>
                    </div>

                    {{-- Title + Audit Badge --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Company Bank Accounts Cash Flow Report</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/90 shadow-2xs">Audit Trail</span>
                    </div>
                </div>
            </div>

            {{-- Right: Metric Snapshot + Action Suite (Print, Excel) --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                
                {{-- Executive Total Bank Balance Tile --}}
                <div class="bg-gradient-to-br from-[#fcfbf8] via-white to-[#F6F3E9]/80 border border-[#a38c29]/30 rounded-2xl p-3 px-4 shadow-2xs flex items-center gap-3.5 hover:border-[#a38c29]/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#a38c29]/20 to-[#8a7522]/10 text-[#8a7522] border border-[#a38c29]/30 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-5 h-5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Bank Balance</span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30"
                                  x-text="filters.bank_account_id === 'all' ? (bankAccounts.length + ' Active') : '1 Selected'">
                            </span>
                        </div>
                        <div class="text-base font-black text-[#73611c] font-mono tracking-tight whitespace-nowrap mt-0.5"
                             x-text="'₹' + formatMoney(activeBankBalance)">
                        </div>
                    </div>
                </div>

                {{-- Action Suite Buttons (Exact Exchange Report Style: Emerald Green & Rose Red) --}}
                <div class="flex items-center gap-2.5 flex-wrap print:hidden">
                    {{-- Export Excel Button (Exact Exchange Report Emerald-600 Style) --}}
                    <button type="button" @click="exportToExcel()" :disabled="isExportingExcel"
                            class="h-[42px] px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98] disabled:opacity-75 disabled:cursor-not-allowed"
                            title="Export Corporate Excel Workbook (.xlsx)">
                        <template x-if="!isExportingExcel">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>EXPORT EXCEL</span>
                            </span>
                        </template>
                        <template x-if="isExportingExcel">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>GENERATING...</span>
                            </span>
                        </template>
                    </button>

                    {{-- Export PDF / Print Button (Exact Exchange Report Rose-600 Style) --}}
                    <button type="button" @click="exportPDF()" 
                            class="h-[42px] px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]"
                            title="Export Statement as PDF">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>EXPORT PDF</span>
                    </button>
                </div>
            </div>

        </div>
        </div>

        {{-- ── 2. 4 KPI Summary Cards (Live Updates via Alpine - Web View Only) ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 print:hidden">
            
            {{-- Card 1: Total Cash Inflow --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full print-kpi-card">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Cash Inflow</span>
                    </div>
                    <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm shrink-0"
                          x-text="inflowCount + ' Credits'">
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300 whitespace-nowrap"
                          x-text="'+₹' + formatMoney(totalInflow)">
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Customer collections & bank credits</p>
                </div>
            </div>

            {{-- Card 2: Total Cash Outflow --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full print-kpi-card">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Cash Outflow</span>
                    </div>
                    <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm shrink-0"
                          x-text="outflowCount + ' Debits'">
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300 whitespace-nowrap"
                          x-text="'-₹' + formatMoney(totalOutflow)">
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Contractor, site expense & contra payouts</p>
                </div>
            </div>

            {{-- Card 3: Net Cash Flow --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full print-kpi-card">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Net Cash Flow</span>
                    </div>
                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md border uppercase tracking-wider shadow-sm shrink-0"
                          :class="netCashFlow >= 0 ? 'text-[#8a7522] bg-[#a38c29]/10 border-[#a38c29]/30' : 'text-rose-700 bg-rose-50 border-rose-200'"
                          x-text="netCashFlow >= 0 ? 'Cash Surplus' : 'Cash Deficit'">
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black font-mono tracking-tight block transition-colors duration-300 whitespace-nowrap"
                          :class="netCashFlow >= 0 ? 'text-slate-900 group-hover:text-[#a38c29]' : 'text-rose-600 group-hover:text-rose-700'"
                          x-text="(netCashFlow >= 0 ? '+' : '') + '₹' + formatMoney(netCashFlow)">
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Net inflow delta for selected period</p>
                </div>
            </div>

            {{-- Card 4: Closing Bank Balance --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-indigo-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full print-kpi-card">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/60 transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Company Bank Balance</span>
                    </div>
                    <span class="text-[9px] text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 uppercase tracking-wider shadow-sm shrink-0"
                          x-text="filters.bank_account_id !== 'all' ? '1 Account' : bankAccounts.length + ' Accounts'">
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-indigo-700 font-mono tracking-tight block group-hover:text-indigo-800 transition-colors duration-300 whitespace-nowrap"
                          x-text="'₹' + formatMoney(activeBankBalance)">
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate"
                       x-text="filters.bank_account_id !== 'all' ? (activeBankName + ' Account') : ('Combined across all ' + bankAccounts.length + ' company accounts')">
                    </p>
                </div>
            </div>

        </div>

        {{-- ── 3. Category Breakdown Section (Live Updates via Alpine) ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-start print:hidden" x-show="inflowCategories.length > 0 || outflowCategories.length > 0">
            {{-- Inflow Breakdown --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2.5">
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Inflow Sources Breakdown</h3>
                        <span class="text-xs font-mono font-black text-emerald-700" x-text="'₹' + formatMoney(totalInflow)"></span>
                    </div>
                    <div class="space-y-2">
                        <template x-for="[catName, catAmt] in inflowCategories" :key="catName">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#F6F3E9] border border-slate-200/70 transition">
                                <span class="text-xs font-bold text-slate-700" x-text="catName"></span>
                                <span class="font-black text-[#8a7522] font-mono text-xs" x-text="'₹' + formatMoney(catAmt)"></span>
                            </div>
                        </template>
                        <template x-if="inflowCategories.length === 0">
                            <div class="p-4 text-center text-xs text-slate-400 italic">No inflows recorded in selected criteria.</div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Outflow Breakdown --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2.5">
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Outflow Uses Breakdown</h3>
                        <span class="text-xs font-mono font-black text-rose-700" x-text="'₹' + formatMoney(totalOutflow)"></span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="[catName, catAmt] in outflowCategories" :key="catName">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#F6F3E9] border border-slate-200/70 transition">
                                <span class="text-xs font-bold text-slate-700 truncate pr-2" :title="catName" x-text="catName"></span>
                                <span class="font-black text-rose-700 font-mono text-xs whitespace-nowrap" x-text="'₹' + formatMoney(catAmt)"></span>
                            </div>
                        </template>
                        <template x-if="outflowCategories.length === 0">
                            <div class="p-4 text-center text-xs text-slate-400 italic col-span-2">No outflows recorded in selected criteria.</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 4. 1-Row Compact Filter Toolbar Directly Above Table (No Above Labels) ── --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80 print:hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 items-center">
                
                {{-- 1. Company Bank Account Selector --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <select x-model="filters.bank_account_id"
                            class="w-full h-11 pl-10 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all appearance-none shadow-2xs"
                            title="Company Bank Account">
                        <option value="all">All Bank Accounts</option>
                        <template x-for="ba in bankAccounts" :key="ba.id">
                            <option :value="String(ba.id)" x-text="ba.bank_name + (ba.account_number ? ' (•••• ' + ba.account_number.slice(-4) + ')' : '') + ' - ₹' + formatMoney(ba.current_balance)"></option>
                        </template>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 2. Flow Direction --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    </div>
                    <select x-model="filters.flow_type"
                            class="w-full h-11 pl-10 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all appearance-none shadow-2xs"
                            title="Flow Direction">
                        <option value="all">All Cash Flows</option>
                        <option value="inflow">Inflows Only (Credits)</option>
                        <option value="outflow">Outflows Only (Debits)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 3. From Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" x-model="filters.date_from" placeholder="From Date" title="From Date"
                           class="w-full h-11 pl-10 pr-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- 4. To Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" x-model="filters.date_to" placeholder="To Date" title="To Date"
                           class="w-full h-11 pl-10 pr-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- 5. Search Keywords (Instant debounced search) --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model.debounce.250ms="filters.search" placeholder="Search keywords..." title="Search Keywords"
                           class="w-full h-11 pl-10 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    <template x-if="filters.search">
                        <button type="button" @click="filters.search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            ✕
                        </button>
                    </template>
                </div>

                {{-- 6. Reset Filters Button (In 1 Single Row, Signature Gold Gradient) --}}
                <div>
                    <button type="button" @click="resetFilters()" 
                            class="w-full h-11 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] px-4 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Filters</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ── 4. Comprehensive Transactions Ledger Table Card ── --}}
    <div class="treasury-table-card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6 print:mt-0">
        <style>
            #treasuryReportTable thead th {
                border-color: #8a7522 !important;
                background-color: #a38c29 !important;
                color: #ffffff !important;
            }
            #treasuryReportTbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #treasuryReportTbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>

        {{-- Table Header Bar (Integrated with Dynamic Showing Info) --}}
        <div class="treasury-table-header-box px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 print:hidden">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest print:text-[8.5pt]">Cash Inflows & Outflows Detailed Ledger</h2>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5 flex items-center gap-1.5 flex-wrap print:text-[6.2pt] print:text-slate-600">
                        <span>Showing <strong class="text-slate-800 font-bold" x-text="filteredTransactions.length"></strong> transactions</span>
                        <template x-if="filters.bank_account_id !== 'all'">
                            <span>for <strong class="text-[#8a7522] font-bold" x-text="activeBankName"></strong></span>
                        </template>
                        <template x-if="filters.bank_account_id === 'all'">
                            <span>across <strong class="text-slate-700 font-bold">all company accounts</strong></span>
                        </template>
                        <template x-if="filters.date_from || filters.date_to">
                            <span>
                                
                                <span>(from <span class="font-semibold text-slate-700" x-text="filters.date_from || 'Beginning'"></span> to <span class="font-semibold text-slate-700" x-text="filters.date_to || 'Today'"></span>)</span>
                            </span>
                        </template>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto print:hidden">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Filtered Count:</span>
                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-50 border border-slate-200 text-slate-800 shadow-2xs"
                      x-text="filteredTransactions.length + ' Entries'">
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs" id="treasuryReportTable">
                <colgroup>
                    <col style="width: 4%;">
                    <col style="width: 13%;">
                    <col style="width: 15%;">
                    <col style="width: 34%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 12%;">
                </colgroup>
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] font-extrabold uppercase text-[10px] tracking-wider text-center">
                        <th class="py-3 px-3 border border-[#8a7522] w-12 text-center">#</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] whitespace-nowrap text-left">Date & Voucher</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] whitespace-nowrap text-left">Bank Account</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] text-left">Transaction Particulars</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] text-right whitespace-nowrap">Inflow (₹)</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] text-right whitespace-nowrap">Outflow (₹)</th>
                        <th class="py-3 px-3.5 border border-[#8a7522] text-right whitespace-nowrap">Running Bal (₹)</th>
                    </tr>
                </thead>
                <tbody id="treasuryReportTbody" class="divide-y divide-slate-100 text-slate-700 font-medium">
                    <template x-for="(t, idx) in paginatedTransactions" :key="t.id">
                        <tr class="transition-colors border-b border-slate-200/60">
                            {{-- Index --}}
                            <td class="py-3 px-3 text-center text-slate-400 font-mono text-[11px] border-r border-slate-200/40"
                                x-text="((currentPage - 1) * perPage) + idx + 1">
                            </td>

                            {{-- Date & Voucher --}}
                            <td class="py-3 px-3.5 whitespace-nowrap border-r border-slate-200/40">
                                <div class="font-bold text-slate-800 text-xs" x-text="t.date_formatted"></div>
                                <div class="font-mono text-[10px] text-slate-400 font-semibold" x-text="t.voucher_no"></div>
                                <template x-if="t.reference_no && t.reference_no !== '—' && t.reference_no !== t.voucher_no">
                                    <div class="text-[9px] text-slate-400 font-mono" x-text="'Ref: ' + t.reference_no"></div>
                                </template>
                            </td>

                            {{-- Bank Account & Mode --}}
                            <td class="py-3 px-3.5 whitespace-nowrap border-r border-slate-200/40">
                                <div class="font-bold text-slate-800 text-xs" x-text="t.bank_name"></div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-1.5 font-medium mt-0.5">
                                    <span class="font-mono font-semibold text-slate-500" x-show="t.account_number" x-text="t.account_number ? ('A/C ' + t.account_number.slice(-4)) : ''"></span>
                                    <span x-show="t.account_number" class="text-slate-300">/</span>
                                    <span x-text="t.payment_mode"></span>
                                </div>
                            </td>

                            {{-- Particulars, Category, Direction & Narration --}}
                            <td class="py-3 px-3.5 border-r border-slate-200/40">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-900 text-xs" x-text="t.counterparty"></span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80" x-text="t.category"></span>
                                    <template x-if="t.flow_type === 'inflow'">
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[8.5px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">INFLOW</span>
                                    </template>
                                    <template x-if="t.flow_type !== 'inflow'">
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[8.5px] font-black uppercase bg-rose-50 text-rose-700 border border-rose-200">OUTFLOW</span>
                                    </template>
                                </div>
                                <template x-if="t.narration && t.narration !== t.category && t.narration !== t.counterparty">
                                    <div class="text-[10.5px] text-slate-400 truncate max-w-md mt-0.5" :title="t.narration" x-text="t.narration"></div>
                                </template>
                            </td>

                            {{-- Inflow Amount --}}
                            <td class="py-3 px-3.5 text-right whitespace-nowrap font-mono font-bold text-xs border-r border-slate-200/40 amount-cell"
                                :class="t.inflow_amount > 0 ? 'text-emerald-700' : 'text-slate-300'"
                                x-text="t.inflow_amount > 0 ? ('+₹' + formatMoney(t.inflow_amount)) : '—'">
                            </td>

                            {{-- Outflow Amount --}}
                            <td class="py-3 px-3.5 text-right whitespace-nowrap font-mono font-bold text-xs border-r border-slate-200/40 amount-cell"
                                :class="t.outflow_amount > 0 ? 'text-rose-700' : 'text-slate-300'"
                                x-text="t.outflow_amount > 0 ? ('-₹' + formatMoney(t.outflow_amount)) : '—'">
                            </td>

                            {{-- Running Balance --}}
                            <td class="py-3 px-3.5 text-right whitespace-nowrap font-mono font-extrabold text-xs amount-cell"
                                :class="t.running_balance >= 0 ? 'text-slate-900' : 'text-rose-700'"
                                x-text="t.running_balance < 0 ? ('-₹' + formatMoney(Math.abs(t.running_balance))) : ('₹' + formatMoney(t.running_balance))">
                            </td>
                        </tr>
                    </template>

                    <template x-if="filteredTransactions.length === 0">
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700">No bank transactions match your criteria</h3>
                                <p class="text-xs text-slate-400 mt-1">Try selecting a different bank account, clearing date filters, or adjusting search keywords.</p>
                                <button type="button" @click="resetFilters()" class="inline-block mt-3 px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                                    Clear All Filters
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>

                {{-- Table Grand Totals Footer (Live Updates via Alpine) --}}
                <tfoot x-show="filteredTransactions.length > 0">
                    <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-[#a38c29]">
                        <td colspan="4" class="py-3.5 px-4 text-right uppercase tracking-wider text-[11px] text-slate-700 font-bold border-r border-slate-200/40">Grand Totals for Selected Range:</td>
                        <td class="py-3.5 px-3.5 text-right font-mono text-xs text-emerald-800 whitespace-nowrap font-black border-r border-slate-200/40 amount-cell"
                            x-text="'+₹' + formatMoney(totalInflow)">
                        </td>
                        <td class="py-3.5 px-3.5 text-right font-mono text-xs text-rose-800 whitespace-nowrap font-black border-r border-slate-200/40 amount-cell"
                            x-text="'-₹' + formatMoney(totalOutflow)">
                        </td>
                        <td class="py-3.5 px-3.5 text-right font-mono text-xs text-slate-900 whitespace-nowrap font-black amount-cell"
                            x-text="'Net: ' + (netCashFlow >= 0 ? '+' : '') + '₹' + formatMoney(netCashFlow)">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ── Table Pagination (New Booking Section Style) ── --}}
        <div class="treasury-pagination-bar px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl" x-show="filteredTransactions.length > 0">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900" x-text="(currentPage - 1) * perPage + 1"></span> TO 
                <span class="text-slate-900" x-text="Math.min(currentPage * perPage, filteredTransactions.length)"></span> OF 
                <span class="text-slate-900" x-text="filteredTransactions.length"></span> TRANSACTIONS
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="if(currentPage > 1) currentPage--" 
                        :disabled="currentPage <= 1"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                    PREV
                </button>
                
                {{-- Page Numbers --}}
                <template x-for="p in getPageNumbers()" :key="p">
                    <span class="inline-flex items-center gap-1">
                        <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                        <button type="button" x-show="p !== '...'"
                                @click="currentPage = p"
                                x-text="p"
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs cursor-pointer"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white border border-[#8a7522]' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                    </span>
                </template>
                
                <button type="button" @click="if(currentPage < getTotalPages()) currentPage++" 
                        :disabled="currentPage >= getTotalPages()"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                    NEXT
                </button>
            </div>
        </div>
    </div>

</div>

{{-- Treasury Report Alpine.js Reactive App --}}
<script>
function treasuryReportApp() {
    return {
        allTransactions: @js($allTransactions),
        bankAccounts: @js($bankAccounts),
        filters: {
            bank_account_id: @js((string)$selectedBankId),
            flow_type: @js($flowType),
            date_from: @js($dateFrom ?? ''),
            date_to: @js($dateTo ?? ''),
            search: @js($search ?? '')
        },
        currentPage: 1,
        perPage: 10,
        isExportingExcel: false,

        init() {
            window.addEventListener('beforeprint', () => {
                this._prevPerPage = this.perPage;
                this._prevPage = this.currentPage;
                this.perPage = 999999;
                this.currentPage = 1;
            });
            window.addEventListener('afterprint', () => {
                if (this._prevPerPage) {
                    this.perPage = this._prevPerPage;
                    this.currentPage = this._prevPage;
                }
            });
        },

        exportPDF() {
            const prevPerPage = this.perPage;
            const prevPage = this.currentPage;
            this.perPage = 999999;
            this.currentPage = 1;

            this.$nextTick(() => {
                setTimeout(() => {
                    const restore = () => {
                        this.perPage = prevPerPage;
                        this.currentPage = prevPage;
                        window.removeEventListener('afterprint', restore);
                    };
                    window.addEventListener('afterprint', restore, { once: true });
                    window.print();
                    setTimeout(restore, 2000);
                }, 100);
            });
        },

        resetFilters() {
            this.filters.bank_account_id = 'all';
            this.filters.flow_type = 'all';
            this.filters.date_from = '';
            this.filters.date_to = '';
            this.filters.search = '';
            this.currentPage = 1;
        },

        get filteredTransactions() {
            const bId = this.filters.bank_account_id;
            const flow = this.filters.flow_type;
            const from = this.filters.date_from;
            const to = this.filters.date_to;
            const q = (this.filters.search || '').trim().toLowerCase();

            return this.allTransactions.filter(t => {
                if (bId && bId !== 'all' && String(t.bank_account_id) !== String(bId)) {
                    return false;
                }
                if (flow && flow !== 'all' && t.flow_type !== flow) {
                    return false;
                }
                if (from && t.date < from) {
                    return false;
                }
                if (to && t.date > to) {
                    return false;
                }
                if (q) {
                    const haystack = (
                        (t.voucher_no || '') + ' ' + 
                        (t.counterparty || '') + ' ' + 
                        (t.narration || '') + ' ' + 
                        (t.reference_no || '') + ' ' + 
                        (t.category || '') + ' ' + 
                        (t.bank_name || '') + ' ' +
                        (t.payment_mode || '')
                    ).toLowerCase();
                    if (!haystack.includes(q)) {
                        return false;
                    }
                }
                return true;
            });
        },

        get totalInflow() {
            return this.filteredTransactions
                .filter(t => t.flow_type === 'inflow')
                .reduce((sum, t) => sum + (parseFloat(t.inflow_amount) || 0), 0);
        },

        get totalOutflow() {
            return this.filteredTransactions
                .filter(t => t.flow_type === 'outflow')
                .reduce((sum, t) => sum + (parseFloat(t.outflow_amount) || 0), 0);
        },

        get netCashFlow() {
            return this.totalInflow - this.totalOutflow;
        },

        get inflowCount() {
            return this.filteredTransactions.filter(t => t.flow_type === 'inflow').length;
        },

        get outflowCount() {
            return this.filteredTransactions.filter(t => t.flow_type === 'outflow').length;
        },

        get activeBankBalance() {
            if (this.filters.bank_account_id && this.filters.bank_account_id !== 'all') {
                const b = this.bankAccounts.find(acc => String(acc.id) === String(this.filters.bank_account_id));
                return b ? (parseFloat(b.current_balance) || 0) : 0;
            }
            return this.bankAccounts.reduce((sum, b) => sum + (parseFloat(b.current_balance) || 0), 0);
        },

        get activeBankName() {
            if (this.filters.bank_account_id && this.filters.bank_account_id !== 'all') {
                const b = this.bankAccounts.find(acc => String(acc.id) === String(this.filters.bank_account_id));
                return b ? b.bank_name : 'Selected Bank';
            }
            return 'all company accounts';
        },

        get inflowCategories() {
            const map = {};
            this.filteredTransactions.forEach(t => {
                if (t.flow_type === 'inflow') {
                    map[t.category] = (map[t.category] || 0) + (parseFloat(t.inflow_amount) || 0);
                }
            });
            return Object.entries(map).sort((a, b) => b[1] - a[1]);
        },

        get outflowCategories() {
            const map = {};
            this.filteredTransactions.forEach(t => {
                if (t.flow_type === 'outflow') {
                    map[t.category] = (map[t.category] || 0) + (parseFloat(t.outflow_amount) || 0);
                }
            });
            return Object.entries(map).sort((a, b) => b[1] - a[1]);
        },

        getTotalPages() {
            return Math.ceil(this.filteredTransactions.length / this.perPage) || 1;
        },

        getPageNumbers() {
            const totalPages = this.getTotalPages();
            const current = this.currentPage;
            const pages = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                } else if (current >= totalPages - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = totalPages - 4; i <= totalPages; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                }
            }
            return pages;
        },

        get paginatedTransactions() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredTransactions.slice(start, start + this.perPage);
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) {
                this.currentPage = p;
            }
        },

        formatMoney(num) {
            return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num || 0);
        },

        exportTableToCSV(filename) {
            let csv = "Index,Date,Bank Account,Account No,Type,Category,Voucher No,Counterparty,Mode,Reference No,Narration,Inflow Amount,Outflow Amount,Running Balance\n";
            this.filteredTransactions.forEach((t, idx) => {
                const row = [
                    idx + 1,
                    `"${(t.date_formatted || '').replace(/"/g, '""')}"`,
                    `"${(t.bank_name || '').replace(/"/g, '""')}"`,
                    `"${(t.account_number || '').replace(/"/g, '""')}"`,
                    `"${(t.flow_type || '').toUpperCase()}"`,
                    `"${(t.category || '').replace(/"/g, '""')}"`,
                    `"${(t.voucher_no || '').replace(/"/g, '""')}"`,
                    `"${(t.counterparty || '').replace(/"/g, '""')}"`,
                    `"${(t.payment_mode || '').replace(/"/g, '""')}"`,
                    `"${(t.reference_no || '').replace(/"/g, '""')}"`,
                    `"${(t.narration || '').replace(/"/g, '""')}"`,
                    t.inflow_amount || 0,
                    t.outflow_amount || 0,
                    t.running_balance || 0
                ];
                csv += row.join(",") + "\n";
            });
            
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            this.isExportingExcel = true;
            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Hindustan ERP';
                workbook.lastModifiedBy = 'Hindustan ERP';
                workbook.created = new Date();
                workbook.modified = new Date();

                const worksheet = workbook.addWorksheet('Cash Flow Statement', {
                    views: [{ showGridLines: true }]
                });

                // Column definitions
                worksheet.columns = [
                    { key: 'index', width: 8 },
                    { key: 'date', width: 14 },
                    { key: 'voucher', width: 22 },
                    { key: 'bank', width: 28 },
                    { key: 'account_no', width: 18 },
                    { key: 'counterparty', width: 34 },
                    { key: 'category', width: 24 },
                    { key: 'flow_type', width: 14 },
                    { key: 'mode_ref', width: 22 },
                    { key: 'narration', width: 38 },
                    { key: 'inflow', width: 18 },
                    { key: 'outflow', width: 18 },
                    { key: 'running_bal', width: 18 },
                ];

                const totalCols = 13;

                // Title Banner Row 1
                const row1 = worksheet.getRow(1);
                row1.height = 32;
                worksheet.mergeCells('A1:M1');
                const titleCell = worksheet.getCell('A1');
                titleCell.value = 'HINDUSTAN REAL ESTATE ERP : TREASURY CASH FLOW REPORT';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row1.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                }

                // Subtitle Row 2
                const row2 = worksheet.getRow(2);
                row2.height = 24;
                worksheet.mergeCells('A2:M2');
                const subCell = worksheet.getCell('A2');
                const bankLabel = this.filters.bank_account_id === 'all' ? 'All Company Accounts' : this.activeBankName;
                const rangeLabel = (this.filters.date_from || this.filters.date_to) ? ` (${this.filters.date_from || 'Beginning'} to ${this.filters.date_to || 'Today'})` : ' (All Dates)';
                subCell.value = `Bank Account: ${bankLabel} | Period: ${rangeLabel} | Generated: ${new Date().toLocaleDateString('en-GB')}`;
                subCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                subCell.alignment = { horizontal: 'center', vertical: 'middle' };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row2.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF8A7522' } };
                }

                // Spacer Row 3
                worksheet.getRow(3).height = 10;

                // Executive KPI Summary Tile in Excel (Row 4)
                worksheet.mergeCells('A4:C4');
                worksheet.getCell('A4').value = `Total Inflow: +₹${this.formatMoney(this.totalInflow)}`;
                worksheet.getCell('A4').font = { bold: true, color: { argb: 'FF065F46' } };

                worksheet.mergeCells('D4:F4');
                worksheet.getCell('D4').value = `Total Outflow: -₹${this.formatMoney(this.totalOutflow)}`;
                worksheet.getCell('D4').font = { bold: true, color: { argb: 'FF9F1239' } };

                worksheet.mergeCells('G4:I4');
                worksheet.getCell('G4').value = `Net Cash Flow: ${this.netCashFlow >= 0 ? '+' : ''}₹${this.formatMoney(this.netCashFlow)}`;
                worksheet.getCell('G4').font = { bold: true, color: { argb: 'FF1E293B' } };

                worksheet.mergeCells('J4:M4');
                worksheet.getCell('J4').value = `Bank Balance: ₹${this.formatMoney(this.activeBankBalance)}`;
                worksheet.getCell('J4').font = { bold: true, color: { argb: 'FF4338CA' } };

                worksheet.getRow(4).height = 22;
                worksheet.getRow(4).alignment = { vertical: 'middle' };

                // Spacer Row 5
                worksheet.getRow(5).height = 10;

                // Table Header Row 6
                const headerRow = worksheet.getRow(6);
                headerRow.height = 26;
                const headers = ['#', 'Date', 'Voucher No', 'Bank Account', 'Account No', 'Counterparty / Payee', 'Category', 'Direction', 'Payment Mode', 'Narration', 'Inflow (₹)', 'Outflow (₹)', 'Running Bal (₹)'];
                headers.forEach((h, i) => {
                    const cell = headerRow.getCell(i + 1);
                    cell.value = h;
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                    cell.alignment = { horizontal: (i >= 10 ? 'right' : (i === 0 ? 'center' : 'left')), vertical: 'middle' };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF8A7522' } },
                        bottom: { style: 'medium', color: { argb: 'FF8A7522' } },
                        left: { style: 'thin', color: { argb: 'FF8A7522' } },
                        right: { style: 'thin', color: { argb: 'FF8A7522' } }
                    };
                });

                // Populate Rows
                let currentRowIndex = 7;
                this.filteredTransactions.forEach((t, idx) => {
                    const row = worksheet.getRow(currentRowIndex);
                    row.height = 20;

                    row.getCell(1).value = idx + 1;
                    row.getCell(2).value = t.date_formatted;
                    row.getCell(3).value = t.voucher_no;
                    row.getCell(4).value = t.bank_name;
                    row.getCell(5).value = t.account_number ? `•••• ${t.account_number.slice(-4)}` : '—';
                    row.getCell(6).value = t.counterparty;
                    row.getCell(7).value = t.category;
                    row.getCell(8).value = (t.flow_type || '').toUpperCase();
                    row.getCell(9).value = `${t.payment_mode || ''} ${t.reference_no && t.reference_no !== '—' ? '(' + t.reference_no + ')' : ''}`.trim();
                    row.getCell(10).value = t.narration || '';
                    row.getCell(11).value = t.inflow_amount || 0;
                    row.getCell(12).value = t.outflow_amount || 0;
                    row.getCell(13).value = t.running_balance || 0;

                    row.getCell(1).alignment = { horizontal: 'center', vertical: 'middle' };
                    for (let c = 2; c <= 10; c++) {
                        row.getCell(c).alignment = { horizontal: 'left', vertical: 'middle' };
                    }
                    for (let c = 11; c <= 13; c++) {
                        row.getCell(c).numFmt = '#,##0.00';
                        row.getCell(c).alignment = { horizontal: 'right', vertical: 'middle' };
                    }

                    row.getCell(11).font = { color: { argb: t.inflow_amount > 0 ? 'FF047857' : 'FF94A3B8' } };
                    row.getCell(12).font = { color: { argb: t.outflow_amount > 0 ? 'FFE11D48' : 'FF94A3B8' } };
                    row.getCell(13).font = { bold: true };

                    const isEven = (idx % 2 === 1);
                    for (let c = 1; c <= totalCols; c++) {
                        const cell = row.getCell(c);
                        if (isEven) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF6F3E9' } };
                        }
                        cell.border = {
                            top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                            bottom: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                            left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                            right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
                        };
                    }

                    currentRowIndex++;
                });

                // Grand Totals Row
                const totalsRow = worksheet.getRow(currentRowIndex);
                totalsRow.height = 24;
                worksheet.mergeCells(`A${currentRowIndex}:J${currentRowIndex}`);
                const totLabel = worksheet.getCell(`A${currentRowIndex}`);
                totLabel.value = 'GRAND TOTALS FOR SELECTED CRITERIA:';
                totLabel.font = { bold: true, color: { argb: 'FF1E293B' } };
                totLabel.alignment = { horizontal: 'right', vertical: 'middle' };

                totalsRow.getCell(11).value = this.totalInflow;
                totalsRow.getCell(11).numFmt = '#,##0.00';
                totalsRow.getCell(11).font = { bold: true, color: { argb: 'FF065F46' } };
                totalsRow.getCell(11).alignment = { horizontal: 'right', vertical: 'middle' };

                totalsRow.getCell(12).value = this.totalOutflow;
                totalsRow.getCell(12).numFmt = '#,##0.00';
                totalsRow.getCell(12).font = { bold: true, color: { argb: 'FF9F1239' } };
                totalsRow.getCell(12).alignment = { horizontal: 'right', vertical: 'middle' };

                totalsRow.getCell(13).value = this.netCashFlow;
                totalsRow.getCell(13).numFmt = '#,##0.00';
                totalsRow.getCell(13).font = { bold: true, color: { argb: 'FF1E293B' } };
                totalsRow.getCell(13).alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 1; c <= totalCols; c++) {
                    const cell = totalsRow.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF1F5F9' } };
                    cell.border = {
                        top: { style: 'medium', color: { argb: 'FFA38C29' } },
                        bottom: { style: 'medium', color: { argb: 'FFA38C29' } },
                        left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
                    };
                }

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                const fileDate = new Date().toISOString().split('T')[0];
                link.setAttribute('download', `Treasury_Cash_Flow_Report_${fileDate}.xlsx`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } catch (err) {
                console.error('Excel Export Error:', err);
                alert('An error occurred during Excel export: ' + err.message);
            } finally {
                this.isExportingExcel = false;
            }
        }
    };
}
</script>

</x-erp-layout>
