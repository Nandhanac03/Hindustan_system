<x-erp-layout title="Contractor RA Bill & Payout Register" headerTitle="Contractor RA Bill & Payout Register">

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
    .print\:hidden, header, nav, aside, footer, button, select, input, .custom-scrollbar::-webkit-scrollbar, [class*="nav"], [class*="sidebar"] {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
    .print\:grid {
        display: grid !important;
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
    .max-w-\[1850px\], .max-w-\[1800px\], .max-w-3xl, .overflow-x-auto {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    /* Executive Print Header */
    .print-exec-header {
        display: block !important;
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        padding-bottom: 8px !important;
        border-bottom: 2px solid #a38c29 !important;
    }

    /* Reset Tailwind vertical space helpers in print */
    .space-y-5 > * + *,
    .space-y-6 > * + *,
    .space-y-7 > * + *,
    .space-y-8 > * + * {
        margin-top: 0 !important;
    }

    /* Row 1: "That Above Box" (Executive Top Banner) - Equal 16px spacing */
    .bg-gradient-to-r.from-\[\#fdfaf3\] {
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        padding: 10px 16px !important;
        border-radius: 10px !important;
        border: 1px solid rgba(163, 140, 41, 0.4) !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] .w-12.h-12 {
        width: 32px !important;
        height: 32px !important;
        border-radius: 8px !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] svg {
        width: 16px !important;
        height: 16px !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] h1 {
        font-size: 12pt !important;
        font-weight: 900 !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] p {
        font-size: 7.2pt !important;
        margin-top: 2px !important;
        line-height: 1.25 !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] .px-3\.5 {
        padding: 4px 10px !important;
        border-radius: 6px !important;
    }
    .bg-gradient-to-r.from-\[\#fdfaf3\] .text-xs {
        font-size: 8pt !important;
    }

    /* Row 2: "Card Row" (5 KPI Metric Cards) - Equal 16px spacing */
    #dashboard-stats {
        display: grid !important;
        grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        width: 100% !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    #dashboard-stats > div {
        padding: 10px 12px !important;
        border-radius: 8px !important;
        border-width: 1px !important;
        border-left-width: 4px !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        min-width: 0 !important;
        overflow: hidden !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #dashboard-stats > div:nth-child(1) { border-left-color: #3b82f6 !important; }
    #dashboard-stats > div:nth-child(2) { border-left-color: #a38c29 !important; }
    #dashboard-stats > div:nth-child(3) { border-left-color: #f43f5e !important; }
    #dashboard-stats > div:nth-child(4) { border-left-color: #4f46e5 !important; }
    #dashboard-stats > div:nth-child(5) { border-left-color: #10b981 !important; }
    #dashboard-stats .text-xl {
        font-size: 10.5pt !important;
        line-height: 1.2 !important;
        margin-top: 3px !important;
        margin-bottom: 3px !important;
        font-weight: 900 !important;
    }
    #dashboard-stats .text-\[10px\],
    #dashboard-stats .text-xs {
        font-size: 6.8pt !important;
        line-height: 1.2 !important;
    }
    #dashboard-stats svg {
        width: 13px !important;
        height: 13px !important;
    }
    #dashboard-stats .w-7 {
        width: 20px !important;
        height: 20px !important;
    }
    #dashboard-stats .rounded-full {
        border-radius: 50% !important;
    }

    /* Row 3: "Table" - Equal 16px spacing */
    #contractor-payout-table-card {
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        clear: both !important;
        page-break-before: auto !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
    }
    #contractor-payout-table-card [class*="max-w-"] {
        max-width: none !important;
    }
    #contractor-payout-table-card > div:first-child {
        padding: 8px 12px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    #contractor-payout-table-card > div:first-child h3 {
        font-size: 9pt !important;
    }
    #contractor-payout-table-card > div:first-child p {
        font-size: 7.2pt !important;
        margin-top: 2px !important;
    }
    #contractor-payout-table-card .w-7.h-7 {
        width: 20px !important;
        height: 20px !important;
        font-size: 7pt !important;
    }

    /* Table Formatting for Print: Same Like the Page, No Broken Columns or Line Breaks */
    table {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7.2pt !important;
    }
    thead {
        display: table-header-group !important;
    }
    tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    th, td {
        padding: 6px 4px !important;
        box-sizing: border-box !important;
        line-height: 1.25 !important;
        text-align: left !important;
        vertical-align: middle !important;
        word-break: normal !important;
        overflow-wrap: normal !important;
    }
    thead th, th, thead th.whitespace-nowrap, th.whitespace-nowrap {
        background-color: #534E47 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-size: 6.8pt !important;
        font-weight: 800 !important;
        padding: 6px 3.5px !important;
        line-height: 1.25 !important;
        letter-spacing: 0.02em !important;
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: normal !important;
        hyphens: none !important;
        vertical-align: top !important;
    }
    td {
        border-bottom: 0.5pt solid #e2e8f0 !important;
        font-size: 7.2pt !important;
    }
    td.font-mono, td .font-mono, td [class*="font-mono"] {
        white-space: nowrap !important;
        font-size: 7.2pt !important;
        font-variant-numeric: tabular-nums !important;
    }
    #contractor-payout-table-card .rounded-full {
        white-space: nowrap !important;
        padding: 2px 6px !important;
        font-size: 6.5pt !important;
        display: inline-flex !important;
    }
    tfoot tr {
        background-color: #f8fafc !important;
        border-top: 2px solid #cbd5e1 !important;
    }
    tfoot td {
        padding: 8px 4px !important;
        font-size: 7.5pt !important;
        font-weight: 800 !important;
        white-space: nowrap !important;
    }
    td.whitespace-nowrap, .font-mono.whitespace-nowrap {
        white-space: nowrap !important;
    }
    .truncate {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs, .shadow-xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }
}
</style>

<div class="max-w-[1850px] mx-auto space-y-7" 
     x-data="contractorPayoutApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="print-exec-header hidden print:block mb-4 border-b-2 border-[#a38c29] pb-3">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Contractor Management & Accounts Audit</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">CONTRACTOR RA BILL & PAYOUT REGISTER</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total RA Bills:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredRows.length + ' Bills'">{{ count($rows) }} Bills</span></div>
            </div>
        </div>
    </div>

    {{-- ── 1. EXECUTIVE TOP HEADER BANNER (GOLDEN & WHITE COMBINATION LIKE CUSTOMER COLLECTION DUES) ── --}}
    <div class="bg-gradient-to-r from-[#fdfaf3] via-[#f8f3e6] to-white rounded-2xl border border-[#a38c29]/30 p-5 sm:p-6 shadow-sm relative overflow-hidden text-slate-900">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-[#a38c29]/12 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-full bg-gradient-to-l from-[#a38c29]/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Left: Icon + Title + Breadcrumbs --}}
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center shadow-md shadow-[#a38c29]/20 shrink-0 border border-amber-300/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-black tracking-tight uppercase text-slate-900">Contractor RA Bill & Payout Register</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">Contractor Desk</span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium mt-1">Real-time audit of contractor running account (RA) progress bills, deductions, net verified liabilities, and bank disbursements.</p>
                </div>
            </div>

            {{-- Quick Metadata & Live Counts --}}
            <div class="flex items-center gap-3 text-right">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">As Of Date</span>
                    <span class="text-xs font-mono font-black text-slate-900">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Active Records</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="filteredRows.length + ' RA Bills (' + allContractorsList.length + ' Contractors)'">{{ count($rows) }} Bills</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 5 FINANCIAL KPI METRIC CARDS (REAL DATABASE DATA IN ₹) ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        
        {{-- Card 1: TOTAL RA BILLS (Blue) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL RA BILLS</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors truncate"
                     x-text="summaryKPIs.total_bills">{{ $kpis['total_bills'] }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5">Active Claims in System</div>
            </div>
        </div>

        {{-- Card 2: GROSS RA AMOUNT (Golden #a38c29) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">GROSS RA AMOUNT</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-[#8a7522] tracking-tight group-hover:text-[#736118] transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.gross_amount)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.gross_amount)">₹ {{ number_format($kpis['gross_amount'], 2) }}</div>
                <div class="text-[10px] text-[#8a7522] font-bold mt-1.5 pt-1.5">Total Certified Work Value</div>
            </div>
        </div>

        {{-- Card 3: TOTAL DEDUCTIONS (Rose) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DEDUCTIONS</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_deductions)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_deductions)">₹ {{ number_format($kpis['total_deductions'], 2) }}</div>
                <div class="text-[10px] text-rose-600 font-bold mt-1.5 pt-1.5">Withholdings & Corrections</div>
            </div>
        </div>

        {{-- Card 4: NET PAYABLE AMOUNT (Indigo / Royal Blue) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-indigo-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">NET PAYABLE AMOUNT</span>
                <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 transition-all duration-300 group-hover:bg-indigo-600 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-indigo-950 tracking-tight group-hover:text-indigo-900 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.net_payable)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.net_payable)">₹ {{ number_format($kpis['net_payable'], 2) }}</div>
                <div class="text-[10px] text-indigo-600 font-bold mt-1.5 pt-1.5">Net Authorized Liability</div>
            </div>
        </div>

        {{-- Card 5: TOTAL DISBURSED / PAID AMOUNT (Emerald) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DISBURSED (PAID)</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.paid_amount)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.paid_amount)">₹ {{ number_format($kpis['paid_amount'], 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5"
                     x-text="summaryKPIs.paid_pct + '% Disbursed of Net Claims'">{{ $kpis['paid_pct'] }}% Disbursed</div>
            </div>
        </div>

    </div>

    <!-- ── 3. ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (NO CURRENCY PILL) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all print:hidden">
        <div class="flex items-center gap-2.5 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Search Box (.erp-search-input) --}}
            <div class="relative min-w-[200px] flex-[1.5]" style="flex: 1.5 1 0%;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.200ms="currentPage = 1; applyFilters()"
                       placeholder="Search contractor, RA number, project..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="searchQuery">
                    <button type="button" @click="searchQuery = ''; currentPage = 1; applyFilters()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer">✕</button>
                </template>
            </div>

            {{-- 2. Contractor Filter (Searchable Dropdown Popover) --}}
            <div class="relative min-w-[160px] flex-1" style="flex: 1 1 0%;"
                 x-data="{
                     open: false,
                     search: '',
                     get filteredList() {
                         const q = (this.search || '').toLowerCase().trim();
                         if (!q) return allContractorsList;
                         return allContractorsList.filter(c => 
                             (c.name && c.name.toLowerCase().includes(q)) || 
                             (c.id && c.id.toLowerCase().includes(q))
                         );
                     },
                     select(name) {
                         filterContractor = name;
                         currentPage = 1;
                         applyFilters();
                         this.open = false;
                         this.search = '';
                     },
                     clear() {
                         filterContractor = 'All Contractors';
                         currentPage = 1;
                         applyFilters();
                         this.open = false;
                         this.search = '';
                     }
                 }"
                 :class="open ? 'z-30' : ''"
                 @click.outside="open = false">

                <button type="button"
                        @click="open = !open; if (open) { $nextTick(() => $refs.contractorSearchInput?.focus()); }"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="truncate text-xs font-bold"
                              :class="filterContractor !== 'All Contractors' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="filterContractor">All Contractors</span>
                    </div>
                    <div class="flex items-center gap-1 shrink-0 ml-1">
                        <template x-if="filterContractor !== 'All Contractors'">
                            <span @click.stop="clear()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </template>
                        <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </button>

                <div x-show="open" x-cloak
                     class="erp-dropdown-popover min-w-[240px] w-max"
                     style="display: none;">
                    
                    {{-- Search Input inside Popover --}}
                    <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text"
                                   x-model="search"
                                   x-ref="contractorSearchInput"
                                   placeholder="Search contractor..."
                                   @keydown.escape="open = false"
                                   class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                            <template x-if="search">
                                <button type="button" @click="search = ''; $refs.contractorSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                            </template>
                        </div>
                    </div>

                    {{-- All Contractors Option --}}
                    <button type="button" @click="clear()"
                            class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer"
                            :class="filterContractor === 'All Contractors' ? 'bg-[#a38c29]/10 text-[#8a7522] font-black' : ''">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>— All Contractors —</span>
                    </button>

                    {{-- Options List with Avatar Pills --}}
                    <div class="overflow-y-auto flex-1 p-1 space-y-0.5 max-h-52">
                        <template x-for="c in filteredList" :key="c.id || c.name">
                            <button type="button"
                                    @click="select(c.name)"
                                    :class="filterContractor === c.name ? 'bg-[#a38c29]/15 text-[#8a7522] font-black' : 'hover:bg-slate-50 text-slate-700'"
                                    class="w-full px-2.5 py-1.5 text-left text-xs rounded-xl transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div :class="filterContractor === c.name ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                         class="w-5 h-5 rounded-full font-bold text-[9px] flex items-center justify-center shrink-0 transition-colors"
                                         x-text="(c.name || '?').charAt(0).toUpperCase()">
                                    </div>
                                    <span class="truncate text-xs" :class="filterContractor === c.name ? 'text-[#8a7522] font-bold' : 'text-slate-800'" x-text="c.name"></span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono shrink-0" x-text="c.id"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 3. Project Filter Dropdown --}}
            <div class="relative min-w-[140px] flex-[0.95]" style="flex: 0.95 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="truncate" :class="filterProject !== 'All Projects' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'" x-text="filterProject">All Projects</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[170px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filterProject = 'All Projects'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterProject === 'All Projects' ? 'selected-all' : ''">
                            <span>All Projects</span>
                        </div>
                        @foreach($allProjects as $proj)
                            <div @click="filterProject = '{{ $proj }}'; currentPage = 1; applyFilters(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filterProject === '{{ $proj }}' ? 'selected' : ''">
                                <span>{{ $proj }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. Payout Status Filter Dropdown --}}
            <div class="relative min-w-[130px] flex-[0.9]" style="flex: 0.9 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate" x-text="filterStatus === 'All' ? 'All Statuses' : filterStatus">All Statuses</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[170px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filterStatus = 'All'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterStatus === 'All' ? 'selected-all' : ''">
                            <span>All Statuses</span>
                        </div>
                        <div @click="filterStatus = 'Paid'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterStatus === 'Paid' ? 'selected' : ''">
                            <span>Paid</span>
                        </div>
                        <div @click="filterStatus = 'Partially Paid'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterStatus === 'Partially Paid' ? 'selected' : ''">
                            <span>Partially Paid</span>
                        </div>
                        <div @click="filterStatus = 'Approved'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterStatus === 'Approved' ? 'selected' : ''">
                            <span>Approved</span>
                        </div>
                        <div @click="filterStatus = 'Pending Approval'; currentPage = 1; applyFilters(); open = false"
                             class="erp-dropdown-option"
                             :class="filterStatus === 'Pending Approval' ? 'selected' : ''">
                            <span>Pending Approval</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Reset Filters Button (.theme-btn) --}}
            <button type="button" 
                    @click="resetFilters()"
                    class="theme-btn h-[38px] px-4 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap"
                    title="Reset All Filters">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>

        </div>
    </div>

    {{-- ── 4. PROFESSIONAL DATA TABLE CARD (100% IN ₹ - ALL CELLS CENTER-ALIGNED) ── --}}
    <div id="contractor-payout-table-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:p-0 print:border-none print:shadow-none print:rounded-none">
        
        {{-- Table Card Header with Title, Counts & Export Buttons --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white print:px-2 print:py-2">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Contractor RA Bill & Payout Register
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3" x-text="filteredRows.length + ' / ' + allRows.length + ' Active Contractor Records'"></p>
            </div>
            
            <div class="flex items-center gap-2.5 print:hidden">
                <button type="button" @click="exportToExcel()" class="h-[38px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" @click="printReport()" class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
            </div>
        </div>

        {{-- Table Content (All cells left-aligned & responsive without horizontal scrollbar) --}}
        <div class="overflow-x-auto custom-scrollbar print:overflow-visible">
            <table class="w-full text-xs text-left border-collapse table-fixed print:min-w-full print:w-full">
                <colgroup class="print:hidden">
                    <col style="width: 2.5%;">    {{-- # --}}
                    <col style="width: 14%;">     {{-- Contractor Details --}}
                    <col style="width: 12.5%;">   {{-- Project / Phase --}}
                    <col style="width: 8.5%;">    {{-- RA Bill & Date --}}
                    <col style="width: 11%;">     {{-- Gross Amount --}}
                    <col style="width: 11%;">     {{-- Deductions / Add. --}}
                    <col style="width: 11%;">     {{-- Net Payable --}}
                    <col style="width: 9.5%;">    {{-- Paid Amount --}}
                    <col style="width: 9%;">      {{-- Balance Due --}}
                    <col style="width: 8.5%;">    {{-- Payout Status --}}
                    <col style="width: 2.5%;">    {{-- Actions --}}
                </colgroup>
                <colgroup class="hidden print:table-column-group">
                    <col style="width: 2.5%;">    {{-- # --}}
                    <col style="width: 14%;">     {{-- Contractor Details --}}
                    <col style="width: 12.5%;">   {{-- Project / Phase --}}
                    <col style="width: 8.5%;">    {{-- RA Bill & Date --}}
                    <col style="width: 11.5%;">   {{-- Gross Amount --}}
                    <col style="width: 11.5%;">   {{-- Deductions / Add. --}}
                    <col style="width: 11.5%;">   {{-- Net Payable --}}
                    <col style="width: 9.5%;">    {{-- Paid Amount --}}
                    <col style="width: 9.5%;">    {{-- Balance Due --}}
                    <col style="width: 9%;">      {{-- Payout Status --}}
                </colgroup>
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr class="erp-table-header border-b border-[#443f38] text-left">
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px] w-9">#</th>
                        <th class="px-3 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Contractor Details</th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Project / Phase</th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">RA Bill & Date</th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Gross Amount <span class="text-[9px] font-normal text-amber-200">(₹)</span></th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Deductions / Add. <span class="text-[9px] font-normal text-amber-200">(₹)</span></th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Net Payable <span class="text-[9px] font-normal text-amber-200">(₹)</span></th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Paid Amount <span class="text-[9px] font-normal text-amber-200">(₹)</span></th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Balance Due <span class="text-[9px] font-normal text-amber-200">(₹)</span></th>
                        <th class="px-2.5 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px]">Payout Status</th>
                        <th class="px-2 py-3.5 text-left whitespace-nowrap text-white font-extrabold text-[10px] w-10 print:hidden">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 border-b border-slate-100 font-medium text-slate-700">
                    <template x-for="(row, idx) in paginatedRows" :key="row.id">
                        <tr class="hover:bg-amber-50/30 transition-colors cursor-pointer group"
                            :class="selectedRow && selectedRow.id === row.id ? 'bg-[#a38c29]/10 border-l-4 border-l-[#a38c29]' : ''"
                            @click="selectBill(row)">
                            
                            {{-- 1. Index # --}}
                            <td class="px-2.5 py-3 text-left font-bold text-slate-400 font-mono text-[11px]" x-text="isPrinting ? (idx + 1) : ((currentPage - 1) * perPage + idx + 1)"></td>

                            {{-- 2. Contractor Details with Avatar & ID --}}
                            <td class="px-3 py-3 text-left">
                                <div class="flex items-center justify-start gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center font-bold text-[9px] shrink-0 uppercase shadow-xs"
                                         x-text="row.contractor_name ? row.contractor_name.substring(0,2).toUpperCase() : 'CO'"></div>
                                    <div class="min-w-0 text-left">
                                        <span class="font-bold text-slate-900 group-hover:text-[#a38c29] transition-colors text-xs block truncate leading-tight" x-text="row.contractor_name"></span>
                                        <span class="font-mono text-slate-500 text-[10px] block leading-tight mt-0.5" x-text="row.company_id || 'ID: —'"></span>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Project / Phase --}}
                            <td class="px-2.5 py-3 text-left">
                                <div class="text-left">
                                    <span class="text-slate-800 font-semibold text-xs block leading-tight truncate max-w-[150px]" :title="row.project_phase" x-text="row.project_phase"></span>
                                    <span class="text-slate-400 font-mono text-[10px] block leading-tight mt-0.5" x-text="row.unit_no ? ('Unit: ' + row.unit_no) : 'Site Contract'"></span>
                                </div>
                            </td>

                            {{-- 4. RA Bill No. & Submission Date --}}
                            <td class="px-2.5 py-3 text-left">
                                <div class="text-left font-mono">
                                    <span class="font-bold text-[#a38c29] group-hover:underline text-xs block leading-tight" x-text="row.ra_bill_no"></span>
                                    <span class="text-slate-500 text-[10px] block leading-tight mt-0.5" x-text="row.submission_date"></span>
                                </div>
                            </td>

                            {{-- 5. Gross Amount --}}
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-slate-900 text-xs whitespace-nowrap" x-text="'₹ ' + formatMoney(row.gross_amount)"></td>

                            {{-- 6. Deductions / Additions --}}
                            <td class="px-2.5 py-3 text-left font-mono text-xs whitespace-nowrap">
                                <span :class="row.advance_deduction > 0 ? 'text-rose-600 font-bold block whitespace-nowrap' : 'text-slate-400 block whitespace-nowrap'" 
                                      x-text="row.advance_deduction > 0 ? ('-₹ ' + formatMoney(row.advance_deduction)) : '₹ 0.00'"></span>
                                <template x-if="row.additional_amount > 0">
                                    <span class="text-emerald-700 font-semibold text-[10px] block mt-0.5 whitespace-nowrap" x-text="'+₹ ' + formatMoney(row.additional_amount)"></span>
                                </template>
                            </td>

                            {{-- 7. Net Payable --}}
                            <td class="px-2.5 py-3 text-left font-mono font-black text-blue-950 text-xs whitespace-nowrap" x-text="'₹ ' + formatMoney(row.net_payable)"></td>

                            {{-- 8. Paid Amount --}}
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-emerald-700 text-xs whitespace-nowrap" x-text="'₹ ' + formatMoney(row.paid_amount)"></td>

                            {{-- 9. Balance Due --}}
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-xs whitespace-nowrap" :class="row.balance_amount > 0 ? 'text-rose-600' : 'text-slate-400'" x-text="'₹ ' + formatMoney(row.balance_amount)"></td>

                            {{-- 10. Payout Status & Verification/Bank Info --}}
                            <td class="px-2.5 py-3 text-left">
                                <div class="text-left space-y-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9.5px] font-extrabold uppercase tracking-wide leading-tight whitespace-nowrap"
                                          :class="getStatusBadgeClass(row.payout_status)"
                                          x-text="row.payout_status"></span>
                                    <span class="text-[10px] font-mono text-slate-500 block truncate max-w-[130px] whitespace-nowrap" 
                                          :title="row.bank_ref_no || row.paid_date" 
                                          x-text="row.bank_ref_no || row.paid_date || '—'"></span>
                                </div>
                            </td>

                            {{-- 11. Actions --}}
                            <td class="px-2 py-3 text-left print:hidden" @click.stop>
                                <button type="button" 
                                        @click="openViewModal(row)"
                                        title="View RA Bill Details"
                                        class="w-7 h-7 rounded-lg border border-[#a38c29]/30 bg-[#a38c29]/10 text-[#8a7522] hover:bg-[#a38c29] hover:text-white hover:border-[#a38c29] transition-all flex items-center justify-center cursor-pointer shadow-2xs group/btn">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="filteredRows.length === 0">
                        <td colspan="11" class="py-12 text-center text-slate-400 font-medium">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>No contractor RA bills match your search criteria.</span>
                        </td>
                    </tr>
                </tbody>

                {{-- Table Foot: Left-aligned totals matching column positions --}}
                <tfoot class="bg-slate-50 border-t-2 border-slate-300 font-mono font-black text-slate-900 text-xs">
                    <tr>
                        <td colspan="4" class="py-3 px-3 text-left uppercase tracking-wider text-[11px] font-black text-slate-700 whitespace-nowrap" x-text="isPrinting ? 'Total Report (₹)' : 'Page Total (₹)'">Page Total (₹)</td>
                        <td class="py-3 px-2.5 text-left text-slate-900 whitespace-nowrap" x-text="'₹ ' + formatMoney(pageTotals.gross)"></td>
                        <td class="py-3 px-2.5 text-left whitespace-nowrap">
                            <div class="text-rose-600 whitespace-nowrap" x-text="'-₹ ' + formatMoney(pageTotals.deduction)"></div>
                            <template x-if="pageTotals.addition > 0">
                                <div class="text-emerald-700 text-[10px] whitespace-nowrap" x-text="'+₹ ' + formatMoney(pageTotals.addition)"></div>
                            </template>
                        </td>
                        <td class="py-3 px-2.5 text-left text-blue-900 whitespace-nowrap" x-text="'₹ ' + formatMoney(pageTotals.net)"></td>
                        <td class="py-3 px-2.5 text-left text-emerald-700 whitespace-nowrap" x-text="'₹ ' + formatMoney(pageTotals.paid)"></td>
                        <td class="py-3 px-2.5 text-left text-rose-600 whitespace-nowrap" x-text="'₹ ' + formatMoney(pageTotals.balance)"></td>
                        <td colspan="2" class="py-3 px-2.5 text-left text-slate-400 text-[10px] font-normal print:hidden">Active Register Summary</td>
                        <td class="py-3 px-2.5 text-left text-slate-400 text-[10px] font-normal whitespace-nowrap hidden print:table-cell">Summary</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Pagination & Entry Counter --}}
        <div class="px-5 py-3.5 bg-white border-t border-slate-200/90 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600 print:hidden">
            <div>
                Showing <span class="font-bold text-slate-900" x-text="paginationStart"></span> to <span class="font-bold text-slate-900" x-text="paginationEnd"></span> of <span class="font-bold text-slate-900" x-text="filteredRows.length"></span> entries
            </div>

            <div class="flex items-center gap-1.5 select-none">
                <button type="button" 
                        @click="prevPage()" 
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-slate-100 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold transition-all">
                    Previous
                </button>

                <template x-for="p in totalPages" :key="p">
                    <button type="button" 
                            @click="currentPage = p"
                            :class="currentPage === p ? 'bg-[#a38c29] text-white font-black shadow-xs' : 'hover:bg-slate-100 text-slate-700 font-semibold'"
                            class="w-8 h-8 rounded-xl text-xs transition-all cursor-pointer flex items-center justify-center"
                            x-text="p"></button>
                </template>

                <button type="button" 
                        @click="nextPage()" 
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-slate-100 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold transition-all">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- ── 5. VIEW MODAL POPUP (EXECUTIVE ERP MODAL MATCHING CUSTOMER COLLECTION DUES & EXCHANGE) ── --}}
    <div class="fixed inset-0 z-[120] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 print:hidden" 
         x-show="viewModalOpen" 
         x-cloak
         @keydown.escape.window="closeViewModal()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop click listener --}}
        <div class="fixed inset-0" @click="closeViewModal()"></div>

        {{-- Modal Dialog Card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden my-auto transform flex flex-col max-h-[92vh] z-10"
             @click.stop
             x-show="viewModalOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95 translate-y-3"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-3">

            {{-- Modal Header Banner (Styled like Customer Collection Dues & Exchange Details Modal) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 shrink-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">
                                Contractor Payout
                            </span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-widest whitespace-nowrap border"
                                  :class="getStatusBadgeClass(selectedRow?.payout_status)"
                                  x-text="selectedRow?.payout_status || 'Draft'">
                            </span>
                            <!-- <template x-if="selectedRow?.ra_bill_no">
                                <span class="px-2 py-0.5 rounded bg-white/10 text-slate-300 font-mono text-[9px] font-bold uppercase tracking-widest whitespace-nowrap"
                                      x-text="'RA Bill #' + selectedRow.ra_bill_no">
                                </span>
                            </template> -->
                        </div>
                        <!-- <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wider uppercase" 
                                x-text="selectedRow?.contractor_name || 'CONTRACTOR PAYOUT DETAILS'"></h2>
                            <span class="text-xs font-semibold text-amber-200/80" 
                                  x-show="selectedRow?.project_phase" 
                                  x-text="'• ' + selectedRow.project_phase"></span>
                        </div> -->
                    </div>

                    {{-- Close Button (Matching Screenshot) --}}
                    <button type="button" 
                            @click="closeViewModal()" 
                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 cursor-pointer" 
                            title="Close">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Modal Segmented Navigation Tabs --}}
            <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-200/80 shrink-0 space-y-2.5">
                {{-- Full-width Segmented Tabs Row (One Line, No Horizontal Scrollbar) --}}
                <div class="bg-white p-1 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center w-full">
                    {{-- Tab 1: RA Bill Details --}}
                    <button type="button" @click="activeTab = 'details'" 
                            class="flex-1 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap min-w-0"
                            :class="activeTab === 'details' 
                                ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                        <div class="w-4 h-4 rounded-md flex items-center justify-center transition-colors shrink-0"
                             :class="activeTab === 'details' ? 'bg-white/20 text-white' : 'text-slate-500'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="truncate">RA Bill Details</span>
                    </button>

                    {{-- Tab 2: Deductions & Additions --}}
                    <button type="button" @click="activeTab = 'deductions'" 
                            class="flex-1 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap min-w-0"
                            :class="activeTab === 'deductions' 
                                ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                        <div class="w-4 h-4 rounded-md flex items-center justify-center transition-colors shrink-0"
                             :class="activeTab === 'deductions' ? 'bg-white/20 text-white' : 'text-slate-500'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="truncate">Deductions & Additions</span>
                    </button>

                    {{-- Tab 3: Payment History --}}
                    <button type="button" @click="activeTab = 'payments'" 
                            class="flex-1 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap min-w-0"
                            :class="activeTab === 'payments' 
                                ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                        <div class="w-4 h-4 rounded-md flex items-center justify-center transition-colors shrink-0"
                             :class="activeTab === 'payments' ? 'bg-white/20 text-white' : 'text-slate-500'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="truncate">Payment History</span>
                        <span class="px-1.5 py-0.2 rounded-md text-[10px] font-black shrink-0"
                              :class="activeTab === 'payments' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500'"
                              x-text="selectedRow?.payments ? selectedRow.payments.length : 0"></span>
                    </button>

                    {{-- Tab 4: Linked Transactions --}}
                    <button type="button" @click="activeTab = 'transactions'" 
                            class="flex-1 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap min-w-0"
                            :class="activeTab === 'transactions' 
                                ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                        <div class="w-4 h-4 rounded-md flex items-center justify-center transition-colors shrink-0"
                             :class="activeTab === 'transactions' ? 'bg-white/20 text-white' : 'text-slate-500'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <span class="truncate">Linked Transactions</span>
                    </button>
                </div>

                {{-- Balance Pending Status (Lies below tabs) --}}
                <div class="flex items-center justify-end pt-0.5 px-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white text-slate-700 text-xs font-bold border border-slate-200/90 shadow-2xs">
                        <span class="w-2 h-2 rounded-full" :class="(selectedRow?.balance_amount || 0) <= 0 ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                        <span x-text="(selectedRow?.balance_amount || 0) <= 0 ? 'Fully Cleared' : 'Balance Pending'"></span>
                        <span class="font-mono font-black" :class="(selectedRow?.balance_amount || 0) <= 0 ? 'text-emerald-700' : 'text-amber-800'" x-text="'(₹ ' + formatMoney(selectedRow?.balance_amount) + ')'"></span>
                    </span>
                </div>
            </div>

            {{-- Modal Body Content (Scrollable) --}}
            <div class="p-5 sm:p-6 bg-slate-50/70 overflow-y-auto flex-1 custom-scrollbar space-y-5">
                
                {{-- ── TAB 1: RA BILL DETAILS ── --}}
                <div x-show="activeTab === 'details'" class="space-y-5">
                    
                    {{-- Top 4 Financial Metric Cards (Executive Header Style) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                        {{-- Card 1: Certified Gross Work Done --}}
                        <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 border-l-[5px] border-l-blue-600 p-4 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Gross Work Done</span>
                                </div>
                                <span class="text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">Certified</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-xl font-black text-slate-900 font-mono tracking-tight block" x-text="'₹ ' + formatMoney(selectedRow?.gross_amount)"></span>
                                <p class="text-[9px] text-slate-400 mt-1 font-medium">Total Measured Work</p>
                            </div>
                        </div>

                        {{-- Card 2: Net Approved Liability --}}
                        <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 border-l-[5px] border-l-indigo-600 p-4 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Approved Liability</span>
                                </div>
                                <span class="text-[9px] text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200 uppercase tracking-wider">Net Payable</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-xl font-black text-indigo-950 font-mono tracking-tight block" x-text="'₹ ' + formatMoney(selectedRow?.net_payable)"></span>
                                <p class="text-[9px] text-slate-400 mt-1 font-medium">After Deductions & Additions</p>
                            </div>
                        </div>

                        {{-- Card 3: Disbursed Paid Amount --}}
                        <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 border-l-[5px] border-l-emerald-500 p-4 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Disbursed Paid</span>
                                </div>
                                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 uppercase tracking-wider" 
                                      x-text="(selectedRow?.net_payable > 0 ? (selectedRow.paid_amount / selectedRow.net_payable * 100).toFixed(1) : 0) + '% Settled'"></span>
                            </div>
                            <div class="mt-1">
                                <span class="text-xl font-black text-emerald-600 font-mono tracking-tight block" x-text="'₹ ' + formatMoney(selectedRow?.paid_amount)"></span>
                                <p class="text-[9px] text-slate-400 mt-1 font-medium">Cleared Bank Disbursals</p>
                            </div>
                        </div>

                        {{-- Card 4: Remaining Balance Due --}}
                        <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 border-l-[5px] border-l-rose-500 p-4 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Remaining Balance</span>
                                </div>
                                <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-200 uppercase tracking-wider"
                                      x-text="(selectedRow?.net_payable > 0 ? (selectedRow.balance_amount / selectedRow.net_payable * 100).toFixed(1) : 0) + '% Pending'"></span>
                            </div>
                            <div class="mt-1">
                                <span class="text-xl font-black text-rose-600 font-mono tracking-tight block" x-text="'₹ ' + formatMoney(selectedRow?.balance_amount)"></span>
                                <p class="text-[9px] text-slate-400 mt-1 font-medium" x-text="(selectedRow?.balance_amount || 0) <= 0 ? 'Fully Disbursed' : 'Awaiting Release'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- 4 Detailed Specifications Cards (Arranged as 2x2 Balanced Grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 text-xs">
                        
                        {{-- Box 1: Contractor Information --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Contractor Profile</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Vendor Identity & Contact</p>
                                    </div>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Contractor Name</span>
                                        <span class="font-black text-slate-900 text-right text-xs truncate" x-text="selectedRow?.contractor_name"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Company / Tax ID</span>
                                        <span class="font-mono text-slate-800 font-bold text-right text-xs bg-slate-100 px-2 py-0.5 rounded border border-slate-200" x-text="selectedRow?.company_id || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Contact Person</span>
                                        <span class="font-semibold text-slate-800 text-right text-xs" x-text="selectedRow?.contact_person || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Phone Number</span>
                                        <span class="font-mono text-slate-800 font-bold text-right text-xs" x-text="selectedRow?.contact_phone || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 pt-0.5">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Email Address</span>
                                        <span class="font-mono text-[#8a7522] font-semibold text-right text-xs truncate max-w-[280px]" :title="selectedRow?.contact_email" x-text="selectedRow?.contact_email || '—'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Box 2: RA Bill Scope & Project Details --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">RA Bill Information</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Invoice & Project Scope</p>
                                    </div>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">RA Bill No.</span>
                                        <span class="font-mono font-black text-[#8a7522] bg-[#a38c29]/10 px-2.5 py-0.5 rounded-full border border-[#a38c29]/25 text-right text-xs" x-text="selectedRow?.ra_bill_no"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Submission Date</span>
                                        <span class="font-mono text-slate-800 font-bold text-right text-xs" x-text="selectedRow?.submission_date || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Project / Phase</span>
                                        <span class="font-bold text-slate-900 text-right text-xs truncate max-w-[280px]" :title="selectedRow?.project_phase" x-text="selectedRow?.project_phase"></span>
                                    </div>
                                    <div class="flex items-baseline justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Work Description</span>
                                        <span class="text-slate-700 text-right font-medium text-xs leading-snug break-words max-w-[280px]" x-text="selectedRow?.work_description || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 pt-0.5">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Certified Value</span>
                                        <span class="font-mono font-black text-slate-900 text-sm text-right" x-text="'₹ ' + formatMoney(selectedRow?.gross_amount)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Box 3: Financial Valuation & Liability --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Financial Audit</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Valuation & Liability</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">₹ INR</span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-36 text-slate-500 font-semibold text-xs shrink-0 text-left">Gross Work Done</span>
                                        <span class="font-mono font-bold text-slate-900 text-right text-xs" x-text="'₹ ' + formatMoney(selectedRow?.gross_amount)"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-36 text-rose-600 font-semibold text-xs shrink-0 text-left">Less: Deductions</span>
                                        <span class="font-mono font-bold text-rose-600 text-right text-xs" x-text="'-₹ ' + formatMoney(selectedRow?.advance_deduction)"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-36 text-emerald-700 font-semibold text-xs shrink-0 text-left">Add: Additions</span>
                                        <span class="font-mono font-bold text-emerald-700 text-right text-xs" x-text="'+₹ ' + formatMoney(selectedRow?.additional_amount)"></span>
                                    </div>
                                    <div class="bg-indigo-50/70 p-2.5 rounded-xl border border-indigo-100 flex items-center justify-between gap-3">
                                        <span class="text-[10px] font-black text-indigo-900 uppercase tracking-wider text-left">Net Approved Liability</span>
                                        <span class="font-mono font-black text-indigo-950 text-sm text-right" x-text="'₹ ' + formatMoney(selectedRow?.net_payable)"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 pt-0.5">
                                        <span class="w-36 text-slate-500 font-semibold text-xs shrink-0 text-left">Disbursed / Paid</span>
                                        <span class="font-mono font-bold text-emerald-700 text-right text-xs" x-text="'₹ ' + formatMoney(selectedRow?.paid_amount)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Box 4: Payout Verification & Banking Audit --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Payout Audit</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Verification & Audit Status</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide border shadow-2xs"
                                          :class="getStatusBadgeClass(selectedRow?.payout_status)"
                                          x-text="selectedRow?.payout_status"></span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Verified By</span>
                                        <span class="font-semibold text-slate-800 text-right text-xs" x-text="selectedRow?.approved_by || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Verified Date</span>
                                        <span class="font-mono text-slate-800 text-right text-xs font-medium" x-text="selectedRow?.approved_date || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Paid Date</span>
                                        <span class="font-mono text-slate-800 text-right text-xs font-medium" x-text="selectedRow?.paid_date || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Bank Ref</span>
                                        <span class="font-mono text-slate-900 font-bold text-right text-xs truncate max-w-[200px]" x-text="selectedRow?.bank_ref_no || '—'"></span>
                                    </div>
                                    <div class="flex items-baseline justify-between gap-3 pt-0.5">
                                        <span class="w-28 text-slate-500 font-semibold text-xs shrink-0 text-left">Remarks</span>
                                        <span class="text-slate-700 italic text-[11px] text-right truncate max-w-[280px]" x-text="selectedRow?.remarks || 'No remarks recorded.'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── TAB 2: DEDUCTIONS & ADDITIONS ── --}}
                <div x-show="activeTab === 'deductions'" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 text-xs">
                        {{-- Box 1: Advance & Deductions --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Advance & Deductions</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Withholdings & Defect Adjustments</p>
                                    </div>
                                </div>
                                <p class="text-slate-500 text-[11px] pt-3 leading-relaxed">Mobilization advance amortizations, defect corrections, or statutory retentions verified by site engineer.</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-baseline justify-between">
                                <span class="text-slate-500 font-medium text-[11px]">Total Deducted:</span>
                                <span class="text-lg font-mono font-black text-rose-600" x-text="'-₹ ' + formatMoney(selectedRow?.advance_deduction)"></span>
                            </div>
                        </div>

                        {{-- Box 2: Approved Scope Additions --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Approved Scope Additions</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Extra Variations & GST Schedule</p>
                                    </div>
                                </div>
                                <p class="text-slate-500 text-[11px] pt-3 leading-relaxed">Site extra work orders, variations, approved scope adjustments, and additions for this RA progress cycle.</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-baseline justify-between">
                                <span class="text-slate-500 font-medium text-[11px]">Total Additions:</span>
                                <span class="text-lg font-mono font-black text-emerald-700" x-text="'+₹ ' + formatMoney(selectedRow?.additional_amount) + ' (' + (selectedRow?.additional_pct || 0) + '%)'"></span>
                            </div>
                        </div>

                        {{-- Box 3: Net Approved Liability --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Net Approved Liability</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Final Certified Payable Value</p>
                                    </div>
                                </div>
                                <p class="text-slate-500 text-[11px] pt-3 leading-relaxed">Formula: Gross Certified Work Done − Total Deductions + Approved Scope Additions.</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-baseline justify-between">
                                <span class="text-slate-500 font-medium text-[11px]">Net Liability:</span>
                                <span class="text-lg font-mono font-black text-indigo-950" x-text="'₹ ' + formatMoney(selectedRow?.net_payable)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── TAB 3: PAYMENT HISTORY ── --}}
                <div x-show="activeTab === 'payments'" class="space-y-5">
                    {{-- Payment Summary Boxes --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 text-xs">
                        {{-- Box 1: Disbursed Paid --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Disbursed Paid</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Cleared to Contractor</p>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Paid to Date</div>
                                    <div class="font-mono font-black text-emerald-700 text-xl" x-text="'₹ ' + formatMoney(selectedRow?.paid_amount)"></div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Vouchers:</span>
                                <span class="font-bold text-slate-800" x-text="(selectedRow?.payments ? selectedRow.payments.length : 0) + ' Payment(s)'"></span>
                            </div>
                        </div>

                        {{-- Box 2: Outstanding Due --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Balance Outstanding</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Pending Release Clearance</p>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Remaining Due</div>
                                    <div class="font-mono font-black text-rose-600 text-xl" x-text="'₹ ' + formatMoney(selectedRow?.balance_amount)"></div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Clearance:</span>
                                <span class="font-extrabold uppercase text-[10.5px]" :class="(selectedRow?.balance_amount || 0) <= 0 ? 'text-emerald-700' : 'text-amber-700'" x-text="(selectedRow?.balance_amount || 0) <= 0 ? 'Fully Cleared' : 'Pending Payment'"></span>
                            </div>
                        </div>

                        {{-- Box 3: Net Certified Liability --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Approved Liability</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Net Bill Valuation</p>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Approved Payable</div>
                                    <div class="font-mono font-black text-indigo-950 text-xl" x-text="'₹ ' + formatMoney(selectedRow?.net_payable)"></div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">RA Bill No:</span>
                                <span class="font-mono font-bold text-[#8a7522]" x-text="selectedRow?.ra_bill_no"></span>
                            </div>
                        </div>

                        {{-- Box 4: Settlement Audit --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/70 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Settlement Audit</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">Banking Verification</p>
                                    </div>
                                </div>
                                <div class="space-y-1.5 pt-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-500 font-medium">Bank Ref:</span>
                                        <span class="font-mono font-bold text-slate-900 truncate" x-text="selectedRow?.bank_ref_no || (selectedRow?.payments && selectedRow?.payments[0] ? selectedRow.payments[0].bank_reference : '—')"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-500 font-medium">Paid Date:</span>
                                        <span class="font-mono text-slate-800" x-text="selectedRow?.paid_date || (selectedRow?.payments && selectedRow?.payments[0] ? selectedRow.payments[0].payment_date : '—')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Status:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border"
                                      :class="getStatusBadgeClass(selectedRow?.payout_status)"
                                      x-text="selectedRow?.payout_status"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Disbursed Payments Table --}}
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3 pb-3 mb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/70 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Disbursed Payment Transactions</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">Bank Vouchers & Settlement Receipts</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200"
                                  x-text="(selectedRow?.payments ? selectedRow.payments.length : 0) + ' Record(s)'"></span>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-100">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50/80 text-[10.5px] font-black uppercase text-slate-600 border-b border-slate-100">
                                    <tr>
                                        <th class="py-2.5 px-3.5">Voucher #</th>
                                        <th class="py-2.5 px-3.5">Payment Date</th>
                                        <th class="py-2.5 px-3.5">Mode</th>
                                        <th class="py-2.5 px-3.5">Bank Reference</th>
                                        <th class="py-2.5 px-3.5">Bank Account</th>
                                        <th class="py-2.5 px-3.5 text-right">Disbursed (₹)</th>
                                        <th class="py-2.5 px-3.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <template x-for="pmt in (selectedRow?.payments || [])" :key="pmt.voucher_no">
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="py-3 px-3.5 font-mono font-bold text-[#8a7522]" x-text="pmt.voucher_no"></td>
                                            <td class="py-3 px-3.5 font-mono text-slate-700" x-text="pmt.payment_date"></td>
                                            <td class="py-3 px-3.5 text-slate-800 font-medium">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                    <span x-text="pmt.payment_mode"></span>
                                                </span>
                                            </td>
                                            <td class="py-3 px-3.5 font-mono font-bold text-slate-900" x-text="pmt.bank_reference"></td>
                                            <td class="py-3 px-3.5 text-slate-600 font-mono text-[11px]" x-text="pmt.bank_account"></td>
                                            <td class="py-3 px-3.5 font-mono font-black text-emerald-700 text-sm text-right" x-text="'₹ ' + formatMoney(pmt.paid_amount)"></td>
                                            <td class="py-3 px-3.5 text-center">
                                                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full font-extrabold text-[10px] uppercase tracking-wide inline-flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span x-text="pmt.status"></span>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="!selectedRow?.payments || selectedRow.payments.length === 0">
                                        <td colspan="7" class="py-10 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <span class="font-bold text-slate-700 text-xs">No Disbursed Payments Recorded</span>
                                                <span class="text-[11px] text-slate-400 mt-0.5">This RA Bill is currently pending approval or payment release.</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── TAB 4: LINKED TRANSACTIONS ── --}}
                <div x-show="activeTab === 'transactions'" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 text-xs">
                        {{-- Box 1: Site Project Allocation --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Project Allocation</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Site Location & Scope</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-extrabold text-[10px] rounded-full border border-blue-200 uppercase">Allocated</span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="border-b border-slate-100/70 pb-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Project / Phase</span>
                                        <div class="font-bold text-slate-800 break-words leading-snug" x-text="selectedRow?.project_phase"></div>
                                    </div>
                                    <div class="flex items-baseline justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Contractor</span>
                                        <span class="font-bold text-slate-900 text-right break-words min-w-0" x-text="selectedRow?.contractor_name"></span>
                                    </div>
                                    <div class="border-b border-slate-100/70 pb-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Scope Description</span>
                                        <div class="text-slate-700 break-words leading-snug font-medium" x-text="selectedRow?.work_description"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Vendor ID:</span>
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[11px]" x-text="selectedRow?.company_id"></span>
                            </div>
                        </div>

                        {{-- Box 2: Site Engineer Technical Inspection --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Engineer Site Audit</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Quality & Measurement</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border"
                                          :class="selectedRow?.approved_date && selectedRow?.approved_date !== '—' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                                          x-text="selectedRow?.approved_date && selectedRow?.approved_date !== '—' ? 'Verified' : 'Pending'"></span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Inspected By</span>
                                        <span class="font-bold text-slate-800 text-right" x-text="selectedRow?.approved_by || 'Site Engineer'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Audit Date</span>
                                        <span class="font-mono text-slate-800 text-right font-medium" x-text="selectedRow?.approved_date || 'Awaiting Inspection'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Measurement Log</span>
                                        <span class="text-slate-700 font-semibold text-right" x-text="selectedRow?.approved_date && selectedRow?.approved_date !== '—' ? 'Logged on Site' : 'Pending Site Visit'"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">QC Status:</span>
                                <span class="font-bold text-emerald-700">Compliance Passed</span>
                            </div>
                        </div>

                        {{-- Box 3: General Ledger Linkage --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">General Ledger Link</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Accounting Sub-Ledger</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 font-extrabold text-[10px] rounded-full border border-indigo-200 uppercase">GL Linked</span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">RA Voucher #</span>
                                        <span class="font-mono font-black text-[#8a7522]" x-text="selectedRow?.ra_bill_no"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Account Head</span>
                                        <span class="font-medium text-slate-800 text-right">Contractor Payables</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Liability Value</span>
                                        <span class="font-mono font-bold text-indigo-950 text-right" x-text="'₹ ' + formatMoney(selectedRow?.net_payable)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Financial Year:</span>
                                <span class="font-semibold text-slate-700">Active FY 2025-26</span>
                            </div>
                        </div>

                        {{-- Box 4: Banking & Disbursement Clearance --}}
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/70 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Banking Clearance</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">Payment Desk Tracking</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border"
                                          :class="getStatusBadgeClass(selectedRow?.payout_status)"
                                          x-text="selectedRow?.payout_status"></span>
                                </div>
                                <div class="space-y-2.5 pt-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Bank Ref</span>
                                        <span class="font-mono font-bold text-slate-800 text-right truncate" x-text="selectedRow?.bank_ref_no || '—'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Settlement Date</span>
                                        <span class="font-mono text-slate-800 text-right" x-text="selectedRow?.paid_date || 'Pending'"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2">
                                        <span class="text-slate-500 font-medium shrink-0">Disbursed</span>
                                        <span class="font-mono font-bold text-emerald-700 text-right" x-text="'₹ ' + formatMoney(selectedRow?.paid_amount)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Remaining:</span>
                                <span class="font-mono font-bold text-rose-600" x-text="'₹ ' + formatMoney(selectedRow?.balance_amount)"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-3.5 bg-white border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                    <!-- <span>RA Bill #<strong class="font-mono text-slate-900" x-text="selectedRow?.ra_bill_no || '—'"></strong></span>
                    <span>•</span> -->
                    <span>Submission Date: <strong class="font-mono text-slate-900" x-text="selectedRow?.submission_date || '—'"></strong></span>
                    <!-- <template x-if="selectedRow?.bank_ref_no">
                        <span>• Ref: <strong class="font-mono text-slate-700" x-text="selectedRow.bank_ref_no"></strong></span>
                    </template> -->
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="closeViewModal()" 
                            class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Alpine.js Application Component Script --}}
<script>
function contractorPayoutApp() {
    const rawRows = @json($allRows ?? $rows);
    const initialSelected = @json($selectedBill);
    const allContractors = @json($allContractors);
    const allProjects = @json($allProjects);
    const defaultProject = '{{ $selectedProject }}' || (allProjects.length > 0 ? allProjects[0] : 'All Projects');

    return {
        // Data arrays
        allRows: rawRows,
        filteredRows: rawRows,
        allContractorsList: allContractors,
        allProjectsList: allProjects,

        // Filtering state
        searchQuery: '',
        filterStatus: '{{ $selectedStatus ?? "All" }}',
        filterProject: defaultProject,
        filterContractor: '{{ $selectedContractor ?? "All Contractors" }}',

        // Pagination
        currentPage: 1,
        perPage: 10,
        isPrinting: false,

        // View Modal State
        viewModalOpen: false,
        selectedRow: null,
        activeTab: 'details',

        openViewModal(row) {
            this.selectedRow = row;
            this.activeTab = 'details';
            this.viewModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeViewModal() {
            this.viewModalOpen = false;
            document.body.style.overflow = '';
        },

        init() {
            this.applyFilters();
            window.addEventListener('beforeprint', () => {
                this.isPrinting = true;
            });
            window.addEventListener('afterprint', () => {
                this.isPrinting = false;
            });
        },

        printReport() {
            this.isPrinting = true;
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    this.isPrinting = false;
                }, 500);
            }, 150);
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterStatus = 'All';
            this.filterProject = this.allProjectsList.length > 0 ? this.allProjectsList[0] : 'All Projects';
            this.filterContractor = 'All Contractors';
            this.currentPage = 1;
            this.applyFilters();
        },

        applyFilters() {
            const query = (this.searchQuery || '').toLowerCase().trim();
            const status = this.filterStatus;
            const project = this.filterProject;
            const contractor = this.filterContractor;

            this.filteredRows = this.allRows.filter(row => {
                // Search query match
                if (query) {
                    const matchName = (row.contractor_name || '').toLowerCase().includes(query);
                    const matchRa = (row.ra_bill_no || '').toLowerCase().includes(query);
                    const matchProj = (row.project_phase || '').toLowerCase().includes(query);
                    const matchId = (row.company_id || '').toLowerCase().includes(query);
                    if (!matchName && !matchRa && !matchProj && !matchId) {
                        return false;
                    }
                }

                // Status match
                if (status && status !== 'All') {
                    if ((row.payout_status || '').toLowerCase() !== status.toLowerCase()) {
                        return false;
                    }
                }

                // Project match
                if (project && project !== 'All Projects' && project !== 'All') {
                    if (!(row.project_phase || '').toLowerCase().includes(project.toLowerCase())) {
                        return false;
                    }
                }

                // Contractor match
                if (contractor && contractor !== 'All Contractors' && contractor !== 'All') {
                    if (!(row.contractor_name || '').toLowerCase().includes(contractor.toLowerCase())) {
                        return false;
                    }
                }

                return true;
            });

            // Update selected row if current one got filtered out
            if (this.selectedRow && !this.filteredRows.find(r => r.id === this.selectedRow.id)) {
                this.selectedRow = this.filteredRows.length > 0 ? this.filteredRows[0] : null;
            }
        },

        selectBill(row) {
            this.openViewModal(row);
        },

        // Pagination computed helpers
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
        },

        get paginatedRows() {
            if (this.isPrinting) {
                return this.filteredRows;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredRows.slice(start, start + this.perPage);
        },

        get paginationStart() {
            if (this.filteredRows.length === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get paginationEnd() {
            return Math.min(this.currentPage * this.perPage, this.filteredRows.length);
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        // Dynamic KPI metrics summary for filtered subset
        get summaryKPIs() {
            const list = this.filteredRows;
            const totalBills = list.length;
            const gross = list.reduce((acc, r) => acc + (parseFloat(r.gross_amount) || 0), 0);
            const deduc = list.reduce((acc, r) => acc + (parseFloat(r.advance_deduction) || 0), 0);
            const add = list.reduce((acc, r) => acc + (parseFloat(r.additional_amount) || 0), 0);
            const net = list.reduce((acc, r) => acc + (parseFloat(r.net_payable) || 0), 0);
            const paid = list.reduce((acc, r) => acc + (parseFloat(r.paid_amount) || 0), 0);
            const bal = list.reduce((acc, r) => acc + (parseFloat(r.balance_amount) || 0), 0);
            const paidPct = net > 0 ? (paid / net * 100).toFixed(1) : 0;

            return {
                total_bills: totalBills,
                gross_amount: gross,
                total_deductions: deduc,
                total_additions: add,
                net_payable: net,
                paid_amount: paid,
                balance_amount: bal,
                paid_pct: paidPct
            };
        },

        // Page totals for tfoot
        get pageTotals() {
            const list = this.isPrinting ? this.filteredRows : this.paginatedRows;
            return {
                gross: list.reduce((acc, r) => acc + (parseFloat(r.gross_amount) || 0), 0),
                deduction: list.reduce((acc, r) => acc + (parseFloat(r.advance_deduction) || 0), 0),
                addition: list.reduce((acc, r) => acc + (parseFloat(r.additional_amount) || 0), 0),
                net: list.reduce((acc, r) => acc + (parseFloat(r.net_payable) || 0), 0),
                paid: list.reduce((acc, r) => acc + (parseFloat(r.paid_amount) || 0), 0),
                balance: list.reduce((acc, r) => acc + (parseFloat(r.balance_amount) || 0), 0)
            };
        },

        // Format number with Indian Rupee formatting (2 decimals)
        formatMoney(num) {
            if (num === null || num === undefined || isNaN(num)) return '0.00';
            return parseFloat(num).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        // Format compact number
        formatCompact(num) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            return parseFloat(num).toLocaleString('en-IN', {
                maximumFractionDigits: 0
            });
        },

        // Badge styling matching ERP design system
        getStatusBadgeClass(status) {
            switch ((status || '').toLowerCase()) {
                case 'paid':
                    return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                case 'partially paid':
                    return 'bg-blue-50 text-blue-700 border border-blue-200';
                case 'approved':
                case 'verified':
                    return 'bg-indigo-50 text-indigo-700 border border-indigo-200';
                case 'pending approval':
                case 'submitted':
                    return 'bg-amber-50 text-amber-800 border border-amber-300';
                default:
                    return 'bg-slate-100 text-slate-700 border border-slate-200';
            }
        },

        // Executive Excel export with ExcelJS in Indian Rupee
        // Executive Excel export with ExcelJS matching Customer Collection Dues styling
        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading. Please try again.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'TABASCO Human Capital';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Contractor RA Bill & Payout', {
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

                sheet.columns = [
                    { key: 'idx', width: 8 },
                    { key: 'contractor', width: 28 },
                    { key: 'company_id', width: 18 },
                    { key: 'project', width: 34 },
                    { key: 'ra_bill', width: 16 },
                    { key: 'date', width: 18 },
                    { key: 'gross', width: 22 },
                    { key: 'deductions', width: 20 },
                    { key: 'additions', width: 18 },
                    { key: 'net', width: 22 },
                    { key: 'paid', width: 22 },
                    { key: 'balance', width: 22 },
                    { key: 'status', width: 20 },
                    { key: 'paid_date', width: 16 },
                    { key: 'bank_ref', width: 24 }
                ];

                // ── Row 1: Top Empty Spacer ──
                sheet.mergeCells('A1:O1');
                const spacer1 = sheet.getCell('A1');
                spacer1.value = '';
                spacer1.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(1).height = 18;

                // ── Row 2: Main Title Banner (#2C3E50 Dark Slate Navy) ──
                sheet.mergeCells('A2:O2');
                const titleCell = sheet.getCell('A2');
                titleCell.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - CONTRACTOR RA BILL & PAYOUT REPORT';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 30;
                sheet.getRow(2).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 3: Subtitle Banner (#007398 Ocean Teal Blue) ──
                sheet.mergeCells('A3:O3');
                const subTitleCell = sheet.getCell('A3');
                subTitleCell.value = 'Contractor Running Account (RA) Progress Bills, Deductions & Disbursement Audit';
                subTitleCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                subTitleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                subTitleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 24;
                sheet.getRow(3).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 4: Section Banner (#006039 Deep Forest Green) ──
                sheet.mergeCells('A4:O4');
                const secCell = sheet.getCell('A4');
                secCell.value = 'TRANSACTION DETAILS';
                secCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                secCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                secCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(4).height = 24;
                sheet.getRow(4).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 5: Middle Empty Spacer ──
                sheet.mergeCells('A5:O5');
                const spacer2 = sheet.getCell('A5');
                spacer2.value = '';
                spacer2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(5).height = 14;

                // ── Row 6: Table Headers (Unified in #34495E Navy Slate Blue) ──
                const headers = [
                    '#',
                    'Contractor Name',
                    'Company ID',
                    'Project / Phase',
                    'RA Bill No.',
                    'Submission Date',
                    'Gross Amount (₹)',
                    'Deductions (₹)',
                    'Additions (₹)',
                    'Net Payable (₹)',
                    'Paid Amount (₹)',
                    'Balance Due (₹)',
                    'Payout Status',
                    'Paid Date',
                    'Bank Ref / Mode'
                ];

                const hRow = sheet.getRow(6);
                hRow.values = headers;
                hRow.height = 30;
                hRow.eachCell({ includeEmpty: true }, (c) => {
                    c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    c.alignment = { vertical: 'middle', horizontal: 'left' };
                    c.border = slateBorder;
                });

                // ── Data Rows (Ice Blue Alternating Background like Customer Collection Dues) ──
                let rIdx = 7;
                this.filteredRows.forEach((r, idx) => {
                    const row = sheet.getRow(rIdx);
                    row.values = [
                        idx + 1,
                        r.contractor_name || '—',
                        r.company_id || '—',
                        r.project_phase || '—',
                        r.ra_bill_no || '—',
                        r.submission_date || '—',
                        Number(r.gross_amount || 0),
                        Number(r.advance_deduction || 0),
                        Number(r.additional_amount || 0),
                        Number(r.net_payable || 0),
                        Number(r.paid_amount || 0),
                        Number(r.balance_amount || 0),
                        (r.payout_status || 'PENDING APPROVAL').toUpperCase(),
                        r.paid_date || '—',
                        r.bank_ref_no || '—'
                    ];

                    row.height = 24;
                    // Alternating background: Row 1 is #F0F8FF (Ice Blue), Row 2 is #FFFFFF (White)
                    const rowBg = (idx % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';

                    row.eachCell({ includeEmpty: true }, (c, col) => {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        c.border = dataBorder;
                        c.alignment = { vertical: 'middle', horizontal: 'left' };

                        if (col === 2) {
                            // Contractor Name bold black
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        } else if (col === 8 && Number(r.advance_deduction || 0) > 0) {
                            // Deductions: Bold Rose / Crimson
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFE11D48' } };
                        } else if (col === 9 && Number(r.additional_amount || 0) > 0) {
                            // Additions: Bold Emerald Green
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF047857' } };
                        } else if (col === 10) {
                            // Net Payable: Bold Navy Slate
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1E293B' } };
                        } else if (col === 11) {
                            // Paid Amount: Bold Forest Green
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF008000' } };
                        } else if (col === 12) {
                            // Balance Due: Bold Rose if balance due
                            const bal = Number(r.balance_amount || 0);
                            c.font = { name: 'Calibri', size: 10, bold: bal > 0, color: { argb: bal > 0 ? 'FFE11D48' : 'FF64748B' } };
                        } else if (col === 13) {
                            // Payout Status: Bold Royal Blue
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1D4ED8' } };
                        } else {
                            c.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                        }

                        if (col >= 7 && col <= 12) {
                            c.numFmt = '#,##0.00';
                        }
                    });

                    rIdx++;
                });

                // ── Footer Row: Grand Total Summary (#2C3E50 Dark Slate Navy, 36pt Height, Bold White 12pt Text, Centered in Blank Space) ──
                const totRow = sheet.getRow(rIdx);
                totRow.height = 36;
                const k = this.summaryKPIs;

                // Explicitly set values on cells
                sheet.getCell(`A${rIdx}`).value = 'TOTAL CONTRACTOR RA BILL & PAYOUT SUMMARY';
                sheet.getCell(`B${rIdx}`).value = '';
                sheet.getCell(`C${rIdx}`).value = '';
                sheet.getCell(`D${rIdx}`).value = '';
                sheet.getCell(`E${rIdx}`).value = '';
                sheet.getCell(`F${rIdx}`).value = '';
                sheet.getCell(`G${rIdx}`).value = Number(k.gross_amount || 0);
                sheet.getCell(`H${rIdx}`).value = Number(k.total_deductions || 0);
                sheet.getCell(`I${rIdx}`).value = Number(k.total_additions || 0);
                sheet.getCell(`J${rIdx}`).value = Number(k.net_payable || 0);
                sheet.getCell(`K${rIdx}`).value = Number(k.paid_amount || 0);
                sheet.getCell(`L${rIdx}`).value = Number(k.balance_amount || 0);
                sheet.getCell(`M${rIdx}`).value = '';
                sheet.getCell(`N${rIdx}`).value = '';
                sheet.getCell(`O${rIdx}`).value = '';

                // Perform merge AFTER values are set so master-slave relationship is preserved
                sheet.mergeCells(`A${rIdx}:F${rIdx}`);

                // Style all cells from Col 1 to 15
                for (let c = 1; c <= 15; c++) {
                    const cell = sheet.getRow(rIdx).getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: false };
                    cell.border = slateBorder;
                    if (c >= 7 && c <= 12) {
                        cell.numFmt = '#,##0.00';
                    }
                }

                // Generate and trigger download
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Contractor_RA_Bill_Payout_Report_${new Date().toISOString().slice(0, 10)}.xlsx`;
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
</script>

</x-erp-layout>
