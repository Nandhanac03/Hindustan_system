<x-erp-layout title="Contractor RA Bill & Payout Register" headerTitle="Contractor RA Bill & Payout Register">

{{-- ExcelJS CDN Library for Executive Client-side Excel Export --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 6mm 8mm 6mm 8mm;
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
    .print\:flex {
        display: flex !important;
    }
    .print\:table {
        display: table !important;
    }
    
    .max-w-\[1850px\], .max-w-\[1800px\], .max-w-3xl, .overflow-x-auto {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .print-exec-header {
        display: block !important;
        margin-bottom: 12px !important;
        padding-bottom: 8px !important;
    }

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
    }
    th, td {
        padding: 5.5px 3.5px !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
        line-height: 1.25 !important;
        text-align: left !important;
    }
    th {
        background-color: #534E47 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-size: 6.8pt !important;
        padding: 6px 3.5px !important;
    }
    td {
        border-bottom: 0.5pt solid #e2e8f0 !important;
        font-size: 7pt !important;
    }
    .whitespace-nowrap {
        white-space: normal !important;
    }
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }
}
</style>

<div class="max-w-[1850px] mx-auto space-y-5" 
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
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all mb-4 print:hidden">
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
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:p-0 print:border-none print:shadow-none print:rounded-none">
        
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
                <button type="button" @click="window.print()" class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>PRINT</span>
                </button>
            </div>
        </div>

        {{-- Table Content (All cells left-aligned & responsive without horizontal scrollbar) --}}
        <div class="overflow-x-auto custom-scrollbar print:overflow-visible">
            <table class="w-full text-xs text-left border-collapse print:min-w-full print:w-full">
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
                            <td class="px-2.5 py-3 text-left font-bold text-slate-400 font-mono text-[11px]" x-text="(currentPage - 1) * perPage + idx + 1"></td>

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
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-slate-900 text-xs" x-text="'₹ ' + formatMoney(row.gross_amount)"></td>

                            {{-- 6. Deductions / Additions --}}
                            <td class="px-2.5 py-3 text-left font-mono text-xs">
                                <span :class="row.advance_deduction > 0 ? 'text-rose-600 font-bold block' : 'text-slate-400 block'" 
                                      x-text="row.advance_deduction > 0 ? ('-₹ ' + formatMoney(row.advance_deduction)) : '₹ 0.00'"></span>
                                <template x-if="row.additional_amount > 0">
                                    <span class="text-emerald-700 font-semibold text-[10px] block mt-0.5" x-text="'+₹ ' + formatMoney(row.additional_amount)"></span>
                                </template>
                            </td>

                            {{-- 7. Net Payable --}}
                            <td class="px-2.5 py-3 text-left font-mono font-black text-blue-950 text-xs" x-text="'₹ ' + formatMoney(row.net_payable)"></td>

                            {{-- 8. Paid Amount --}}
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-emerald-700 text-xs" x-text="'₹ ' + formatMoney(row.paid_amount)"></td>

                            {{-- 9. Balance Due --}}
                            <td class="px-2.5 py-3 text-left font-mono font-bold text-xs" :class="row.balance_amount > 0 ? 'text-rose-600' : 'text-slate-400'" x-text="'₹ ' + formatMoney(row.balance_amount)"></td>

                            {{-- 10. Payout Status & Verification/Bank Info --}}
                            <td class="px-2.5 py-3 text-left">
                                <div class="text-left space-y-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9.5px] font-extrabold uppercase tracking-wide leading-tight"
                                          :class="getStatusBadgeClass(row.payout_status)"
                                          x-text="row.payout_status"></span>
                                    <span class="text-[10px] font-mono text-slate-500 block truncate max-w-[130px]" 
                                          :title="row.bank_ref_no || row.paid_date" 
                                          x-text="row.bank_ref_no || row.paid_date || '—'"></span>
                                </div>
                            </td>

                            {{-- 11. Actions --}}
                            <td class="px-2 py-3 text-left print:hidden" @click.stop>
                                <button type="button" 
                                        @click="selectBill(row)"
                                        title="View Details"
                                        class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-[#a38c29] hover:bg-amber-50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
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
                        <td colspan="4" class="py-3 px-3 text-left uppercase tracking-wider text-[11px] font-black text-slate-700">Page Total (₹)</td>
                        <td class="py-3 px-2.5 text-left text-slate-900" x-text="'₹ ' + formatMoney(pageTotals.gross)"></td>
                        <td class="py-3 px-2.5 text-left">
                            <div class="text-rose-600" x-text="'-₹ ' + formatMoney(pageTotals.deduction)"></div>
                            <template x-if="pageTotals.addition > 0">
                                <div class="text-emerald-700 text-[10px]" x-text="'+₹ ' + formatMoney(pageTotals.addition)"></div>
                            </template>
                        </td>
                        <td class="py-3 px-2.5 text-left text-blue-900" x-text="'₹ ' + formatMoney(pageTotals.net)"></td>
                        <td class="py-3 px-2.5 text-left text-emerald-700" x-text="'₹ ' + formatMoney(pageTotals.paid)"></td>
                        <td class="py-3 px-2.5 text-left text-rose-600" x-text="'₹ ' + formatMoney(pageTotals.balance)"></td>
                        <td colspan="2" class="py-3 px-2.5 text-left text-slate-400 text-[10px] font-normal">Active Register Summary</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Pagination & Entry Counter --}}
        <div class="px-5 py-3.5 bg-white border-t border-slate-200/90 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
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

    {{-- ── 5. SELECTED RA BILL BOTTOM DETAIL CARD (FROM DATABASE DATA) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden mt-6" 
         x-show="selectedRow"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">
        
        {{-- Detail Card Top Tabs & Action Button --}}
        <div class="px-5 py-3 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/70">
            
            {{-- Tabs: RA Bill Details, Deductions & Additions, Payment History, Linked Transactions --}}
            <div class="flex items-center gap-1.5 overflow-x-auto custom-scrollbar">
                <button type="button" 
                        @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'bg-white text-[#8a7522] font-black border-b-2 border-[#a38c29] shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold'"
                        class="px-4 py-2 rounded-t-xl text-xs transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>RA Bill Details</span>
                </button>
                <button type="button" 
                        @click="activeTab = 'deductions'"
                        :class="activeTab === 'deductions' ? 'bg-white text-[#8a7522] font-black border-b-2 border-[#a38c29] shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold'"
                        class="px-4 py-2 rounded-t-xl text-xs transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Deductions & Additions</span>
                </button>
                <button type="button" 
                        @click="activeTab = 'payments'"
                        :class="activeTab === 'payments' ? 'bg-white text-[#8a7522] font-black border-b-2 border-[#a38c29] shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold'"
                        class="px-4 py-2 rounded-t-xl text-xs transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Payment History</span>
                    <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.2 rounded-full font-bold" x-text="selectedRow.payments ? selectedRow.payments.length : 0"></span>
                </button>
                <button type="button" 
                        @click="activeTab = 'transactions'"
                        :class="activeTab === 'transactions' ? 'bg-white text-[#8a7522] font-black border-b-2 border-[#a38c29] shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold'"
                        class="px-4 py-2 rounded-t-xl text-xs transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Linked Transactions</span>
                </button>
            </div>

            {{-- Top Right Actions: Direct Link to Contractor Payment Release Desk --}}
            <!-- <div class="flex items-center gap-2">
                <a href="{{ route('expenses.ra-bills.payment-release') }}"
                   class="h-[36px] px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-1.5 uppercase tracking-wider cursor-pointer active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Open Payment Desk</span>
                </a>

                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open" 
                            @click.away="open = false"
                            class="w-8 h-[36px] rounded-xl border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-30 text-xs">
                        <a href="javascript:void(0)" @click="window.print(); open=false" class="block px-3 py-1.5 text-slate-700 hover:bg-slate-50">Print RA Voucher</a>
                        <a href="{{ route('expenses.ra-bills.verification') }}" class="block px-3 py-1.5 text-slate-700 hover:bg-slate-50">RA Bill Verification</a>
                        <a href="{{ route('expenses.ra-bills.ledger') }}" class="block px-3 py-1.5 text-slate-700 hover:bg-slate-50">View Contractor Ledger</a>
                    </div>
                </div>
            </div> -->
        </div>

        {{-- Tab 1 Content: RA Bill Details (4-Column Layout) --}}
        <div x-show="activeTab === 'details'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                
                {{-- Col 1: Contractor Information --}}
                <div class="space-y-2.5 border-r border-slate-100 pr-5">
                    <h4 class="text-xs font-black text-slate-900 border-b border-slate-200 pb-2 uppercase tracking-wide flex items-center gap-1.5">
                        <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                        Contractor Information
                    </h4>
                    <div class="space-y-2 pt-1">
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Contractor Name</span><span class="font-bold text-slate-900 text-right" x-text="selectedRow.contractor_name"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Company / ID</span><span class="font-mono text-slate-700 text-right font-bold" x-text="selectedRow.company_id"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Contact Person</span><span class="text-slate-800 text-right" x-text="selectedRow.contact_person"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Phone</span><span class="font-mono text-slate-800 text-right" x-text="selectedRow.contact_phone"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Email</span><span class="font-mono text-[#8a7522] font-semibold text-right truncate max-w-[140px]" x-text="selectedRow.contact_email"></span></div>
                    </div>
                </div>

                {{-- Col 2: RA Bill Information --}}
                <div class="space-y-2.5 border-r border-slate-100 pr-5">
                    <h4 class="text-xs font-black text-slate-900 border-b border-slate-200 pb-2 uppercase tracking-wide flex items-center gap-1.5">
                        <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                        RA Bill Information
                    </h4>
                    <div class="space-y-2 pt-1">
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">RA Bill No.</span><span class="font-mono font-black text-[#a38c29] text-right" x-text="selectedRow.ra_bill_no"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Submission Date</span><span class="font-mono text-slate-700 text-right" x-text="selectedRow.submission_date"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Project / Phase</span><span class="font-bold text-slate-800 text-right" x-text="selectedRow.project_phase"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Work Description</span><span class="text-slate-800 text-right truncate max-w-[150px]" :title="selectedRow.work_description" x-text="selectedRow.work_description"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Certified Value</span><span class="font-mono font-black text-slate-900 text-right" x-text="'₹ ' + formatMoney(selectedRow.gross_amount)"></span></div>
                    </div>
                </div>

                {{-- Col 3: Amount Details --}}
                <div class="space-y-2.5 border-r border-slate-100 pr-5">
                    <h4 class="text-xs font-black text-slate-900 border-b border-slate-200 pb-2 uppercase tracking-wide flex items-center gap-1.5">
                        <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                        Amount Details <span class="text-[10px] font-normal text-slate-400">(₹)</span>
                    </h4>
                    <div class="space-y-2 pt-1">
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Gross Work Done</span><span class="font-mono font-black text-slate-900 text-right" x-text="'₹ ' + formatMoney(selectedRow.gross_amount)"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Less: Deductions</span><span class="font-mono font-bold text-rose-600 text-right" x-text="'-₹ ' + formatMoney(selectedRow.advance_deduction)"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Add: Additions</span><span class="font-mono font-bold text-emerald-700 text-right" x-text="'+₹ ' + formatMoney(selectedRow.additional_amount)"></span></div>
                        <div class="flex justify-between items-center pt-1.5 border-t border-slate-200"><span class="text-blue-950 font-black">Net Approved Liability</span><span class="font-mono font-black text-blue-950 text-sm text-right" x-text="'₹ ' + formatMoney(selectedRow.net_payable)"></span></div>
                        <div class="flex justify-between items-center"><span class="text-emerald-700 font-bold">Disbursed Paid</span><span class="font-mono font-bold text-emerald-700 text-right" x-text="'₹ ' + formatMoney(selectedRow.paid_amount)"></span></div>
                        <div class="flex justify-between items-center"><span class="text-rose-600 font-bold">Remaining Balance</span><span class="font-mono font-black text-rose-600 text-right" x-text="'₹ ' + formatMoney(selectedRow.balance_amount)"></span></div>
                    </div>
                </div>

                {{-- Col 4: Payout Status --}}
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide flex items-center gap-1.5">
                            <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                            Payout Status
                        </h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide"
                              :class="getStatusBadgeClass(selectedRow.payout_status)"
                              x-text="selectedRow.payout_status"></span>
                    </div>
                    <div class="space-y-2 pt-1">
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Verified By</span><span class="text-slate-800 text-right font-medium" x-text="selectedRow.approved_by || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Verified Date</span><span class="font-mono text-slate-800 text-right" x-text="selectedRow.approved_date || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Paid Date</span><span class="font-mono text-slate-800 text-right" x-text="selectedRow.paid_date || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Bank Reference</span><span class="font-mono text-slate-800 text-right font-bold" x-text="selectedRow.bank_ref_no || '—'"></span></div>
                        <div class="pt-1">
                            <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider mb-1">Remarks:</span>
                            <p class="text-slate-700 italic text-[11px] bg-slate-50 p-2 rounded-xl border border-slate-200/80" x-text="selectedRow.remarks || 'No remarks recorded.'"></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tab 2 Content: Deductions & Additions --}}
        <div x-show="activeTab === 'deductions'" class="p-6 text-xs space-y-4">
            <h4 class="font-black text-slate-900 uppercase tracking-wide flex items-center gap-1.5">
                <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                Deductions & Additions Schedule
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <span class="font-bold text-slate-800 block text-xs">Correction & Advance Deductions</span>
                    <p class="text-slate-500 text-[11px]">Withholdings, defect corrections, or mobilization advance adjustments verified by site engineer.</p>
                    <div class="text-lg font-mono font-black text-rose-600 mt-2" x-text="'₹ ' + formatMoney(selectedRow.advance_deduction)"></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <span class="font-bold text-slate-800 block text-xs">Additional Approved Items</span>
                    <p class="text-slate-500 text-[11px]">Extra work orders, GST, or scope variations approved for this RA progress cycle.</p>
                    <div class="text-lg font-mono font-black text-emerald-700 mt-2" x-text="'₹ ' + formatMoney(selectedRow.additional_amount) + ' (' + (selectedRow.additional_pct || 0) + '%)'"></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <span class="font-bold text-slate-800 block text-xs">Net Approved Payable</span>
                    <p class="text-slate-500 text-[11px]">Formula: Gross Work Done − Deductions + Approved Additions.</p>
                    <div class="text-lg font-mono font-black text-blue-900 mt-2" x-text="'₹ ' + formatMoney(selectedRow.net_payable)"></div>
                </div>
            </div>
        </div>

        {{-- Tab 3 Content: Payment History --}}
        <div x-show="activeTab === 'payments'" class="p-6 text-xs">
            <h4 class="font-black text-slate-900 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                Disbursed Bank Payments for this RA Bill
            </h4>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3 text-left">Voucher #</th>
                            <th class="py-2.5 px-3 text-left">Payment Date</th>
                            <th class="py-2.5 px-3 text-left">Mode</th>
                            <th class="py-2.5 px-3 text-left">Bank Reference</th>
                            <th class="py-2.5 px-3 text-left">Bank Account</th>
                            <th class="py-2.5 px-3 text-left">Disbursed (₹)</th>
                            <th class="py-2.5 px-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px]">
                        <template x-for="pmt in (selectedRow.payments || [])" :key="pmt.voucher_no">
                            <tr>
                                <td class="py-2.5 px-3 text-left font-mono font-bold text-[#a38c29]" x-text="pmt.voucher_no"></td>
                                <td class="py-2.5 px-3 text-left font-mono text-slate-700" x-text="pmt.payment_date"></td>
                                <td class="py-2.5 px-3 text-left text-slate-800" x-text="pmt.payment_mode"></td>
                                <td class="py-2.5 px-3 text-left font-mono font-bold text-slate-900" x-text="pmt.bank_reference"></td>
                                <td class="py-2.5 px-3 text-left text-slate-600 text-[10.5px]" x-text="pmt.bank_account"></td>
                                <td class="py-2.5 px-3 text-left font-mono font-black text-emerald-700" x-text="'₹ ' + formatMoney(pmt.paid_amount)"></td>
                                <td class="py-2.5 px-3 text-left">
                                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-extrabold text-[10px] uppercase" x-text="pmt.status"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="!selectedRow.payments || selectedRow.payments.length === 0">
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">No disbursement completed yet for this bill in database. Awaiting payment release.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 4 Content: Linked Transactions --}}
        <div x-show="activeTab === 'transactions'" class="p-6 text-xs">
            <h4 class="font-black text-slate-900 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                Linked Project & Ledger Records
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">Site Project Allocation</span>
                        <span class="font-mono text-slate-600 text-[11px]" x-text="selectedRow.project_phase"></span>
                    </div>
                    <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 font-extrabold text-[10px] rounded-full border border-blue-200 uppercase">Allocated</span>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">Site Engineer Verification</span>
                        <span class="font-mono text-slate-600 text-[11px]" x-text="selectedRow.approved_by + ' (' + selectedRow.approved_date + ')'"></span>
                    </div>
                    <span class="px-2.5 py-0.5" 
                          :class="selectedRow.approved_date !== '—' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                          class="font-extrabold text-[10px] rounded-full border uppercase" 
                          x-text="selectedRow.approved_date !== '—' ? 'Verified' : 'Pending'"></span>
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

        // Selection & Bottom panel
        selectedRow: initialSelected || (rawRows.length > 0 ? rawRows[0] : null),
        activeTab: 'details',

        init() {
            this.applyFilters();
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
            this.selectedRow = row;
        },

        // Pagination computed helpers
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
        },

        get paginatedRows() {
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
            const list = this.paginatedRows;
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
        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading, please try again in a second.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'Tabasco Hindustan ERP';
            workbook.created = new Date();

            const sheet = workbook.addWorksheet('Contractor Payout Register', {
                views: [{ showGridLines: true }]
            });

            // 1. Company Header
            sheet.mergeCells('A1:O1');
            const titleRow = sheet.getCell('A1');
            titleRow.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.';
            titleRow.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
            titleRow.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
            titleRow.alignment = { horizontal: 'center', vertical: 'middle' };
            sheet.getRow(1).height = 28;

            sheet.mergeCells('A2:O2');
            const subTitleRow = sheet.getCell('A2');
            subTitleRow.value = 'CONTRACTOR RA BILL & PAYOUT REGISTER (₹ INR)';
            subTitleRow.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF8A7522' } };
            subTitleRow.alignment = { horizontal: 'center', vertical: 'middle' };
            sheet.getRow(2).height = 20;

            // 2. Metadata Info
            sheet.mergeCells('A3:O3');
            const metaRow = sheet.getCell('A3');
            metaRow.value = 'Generated On: ' + new Date().toLocaleString() + ' | Total Active Records: ' + this.filteredRows.length;
            metaRow.font = { name: 'Calibri', size: 9, italic: true, color: { argb: 'FF64748B' } };
            metaRow.alignment = { horizontal: 'center', vertical: 'middle' };
            sheet.getRow(3).height = 18;

            sheet.addRow([]); // Blank Row

            // 3. Table Headers
            const headers = [
                '#', 'Contractor Name', 'Company ID', 'Project / Phase', 'RA Bill No.', 
                'Submission Date', 'Gross Amount (₹)', 'Deductions (₹)', 'Additions (₹)', 
                'Net Payable (₹)', 'Paid Amount (₹)', 'Balance Due (₹)', 
                'Payout Status', 'Paid Date', 'Bank Ref / Mode'
            ];
            
            const headerRow = sheet.addRow(headers);
            headerRow.height = 26;
            headerRow.eachCell((cell) => {
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF534E47' } };
                cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: true };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    bottom: { style: 'medium', color: { argb: 'FF0F172A' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };
            });

            // 4. Data Rows (Left-aligned as per user specification)
            this.filteredRows.forEach((row, idx) => {
                const dataRow = sheet.addRow([
                    idx + 1,
                    row.contractor_name,
                    row.company_id,
                    row.project_phase,
                    row.ra_bill_no,
                    row.submission_date,
                    parseFloat(row.gross_amount) || 0,
                    parseFloat(row.advance_deduction) || 0,
                    parseFloat(row.additional_amount) || 0,
                    parseFloat(row.net_payable) || 0,
                    parseFloat(row.paid_amount) || 0,
                    parseFloat(row.balance_amount) || 0,
                    row.payout_status,
                    row.paid_date,
                    row.bank_ref_no
                ]);
                dataRow.height = 20;

                // Left align all cells
                dataRow.eachCell((cell, colNumber) => {
                    cell.alignment = { horizontal: 'left', vertical: 'middle' };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        bottom: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
                    };

                    // Format Currency columns
                    if ([7, 8, 9, 10, 11, 12].includes(colNumber)) {
                        cell.numFmt = '₹ #,##0.00';
                    }
                });
            });

            // 5. Totals Row
            const k = this.summaryKPIs;
            const totalsRow = sheet.addRow([
                '', 'TOTAL SUMMARY', '', '', '', '',
                k.gross_amount,
                k.total_deductions,
                k.total_additions,
                k.net_payable,
                k.paid_amount,
                k.balance_amount,
                '', '', ''
            ]);
            totalsRow.height = 24;
            totalsRow.eachCell((cell, colNumber) => {
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF1F5F9' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = {
                    top: { style: 'medium', color: { argb: 'FFA38C29' } },
                    bottom: { style: 'double', color: { argb: 'FFA38C29' } }
                };
                if ([7, 8, 9, 10, 11, 12].includes(colNumber)) {
                    cell.numFmt = '₹ #,##0.00';
                }
            });

            // Auto-adjust column widths
            sheet.columns.forEach((col, i) => {
                let maxLen = headers[i] ? headers[i].length : 12;
                col.width = Math.max(maxLen + 4, 13);
            });
            sheet.getColumn(2).width = 26; // Contractor Name
            sheet.getColumn(4).width = 24; // Project / Phase

            // Generate and trigger download
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.download = 'Contractor_RA_Bill_Payout_Register_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            anchor.click();
            window.URL.revokeObjectURL(url);
        }
    };
}
</script>

</x-erp-layout>
