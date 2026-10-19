<x-erp-layout title="Customer Collection & Dues Report" headerTitle="Customer Collection & Dues Report">

{{-- ExcelJS CDN Library --}}
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
        margin-bottom: 12px !important;
        padding-bottom: 8px !important;
    }

    /* Table Formatting for Print */
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
    }
    th {
        background-color: #a38c29 !important;
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
    
    /* Allow cell text to break and wrap nicely */
    .whitespace-nowrap {
        white-space: normal !important;
    }
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs, .shadow-xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }
}
</style>

<div class="max-w-[1850px] mx-auto space-y-6" 
     x-data="customerCollectionDuesApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Customer Contract & Collection Audit</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">CUSTOMER COLLECTION & DUES REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800">{{ $asOfDate ? \Carbon\Carbon::parse($asOfDate)->format('d M Y') : 'Current Live Date' }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredRows.length + ' Records'">{{ count($rows) }} Records</span></div>
            </div>
        </div>
    </div>

    {{-- ── 1. EXECUTIVE TOP HEADER BANNER (GOLDEN & WHITE COMBINATION) ── --}}
    <div class="bg-gradient-to-r from-[#fdfaf3] via-[#f8f3e6] to-white rounded-2xl border border-[#a38c29]/30 p-5 sm:p-6 shadow-sm relative overflow-hidden text-slate-900">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-[#a38c29]/12 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-full bg-gradient-to-l from-[#a38c29]/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center shadow-md shadow-[#a38c29]/20 shrink-0 border border-amber-300/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-black tracking-tight uppercase text-slate-900">Customer Collection & Dues Report</h1>
                        <!-- <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">Customer Portal</span> -->
                    </div>
                    <p class="text-xs text-slate-600 font-medium mt-1">Audit of customer contract values, bank-cleared collections, pending cheques, overdue ageing, and milestone recovery.</p>
                </div>
            </div>

            {{-- Quick Metadata & As Of Date Info --}}
            <div class="flex items-center gap-3 text-right">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">As Of Date</span>
                    <span class="text-xs font-mono font-black text-slate-900">{{ $asOfDate->format('d M Y') }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Active Records</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="summaryKPIs.total_customers + ' Customers (' + filteredRows.length + ' Bookings)'"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 5 FINANCIAL KPI METRIC CARDS (EXACT AMOUNTS) ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        {{-- Card 1: Total Agreement Value --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL AGREEMENT VALUE</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_agreement_value)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_agreement_value)"></div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5">Total Contract Value</div>
            </div>
        </div>

        {{-- Card 2: Total Collected (Realized) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DISBURSED (PAID)</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_collected)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_collected)"></div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5" x-text="summaryKPIs.total_collected_pct + '% Cleared Collections'"></div>
            </div>
        </div>

        {{-- Card 3: Cheques Pending Realization --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-amber-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">PENDING REALIZATION</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-amber-900 tracking-tight group-hover:text-amber-800 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_pending_realization)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_pending_realization)"></div>
                <div class="text-[10px] text-amber-600 font-bold mt-1.5 pt-1.5">Uncleared / Bank In-Transit</div>
            </div>
        </div>

        {{-- Card 4: Outstanding Dues --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">OUTSTANDING DUES</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_outstanding)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_outstanding)"></div>
                <div class="text-[10px] text-rose-600 font-bold mt-1.5 pt-1.5" x-text="summaryKPIs.total_outstanding_pct + '% Outstanding Balance Remaining'"></div>
            </div>
        </div>

        {{-- Card 5: Overdue Amount (Royal Purple / Violet) --}}
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-purple-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL OVERDUE</span>
                <div class="w-7 h-7 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 transition-all duration-300 group-hover:bg-purple-600 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-purple-900 tracking-tight group-hover:text-purple-800 transition-colors truncate"
                     :title="'₹ ' + formatMoney(summaryKPIs.total_overdue)"
                     x-text="'₹ ' + formatMoney(summaryKPIs.total_overdue)"></div>
                <div class="text-[10px] text-purple-600 font-bold mt-1.5 pt-1.5" x-text="summaryKPIs.total_overdue_pct + '% of Dues Delinquent'"></div>
            </div>
        </div>
    </div>

    {{-- ── 3. VISUAL ANALYTICAL CHARTS (EXECUTIVE DASHBOARD) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Chart 1: Financial Health & Recovery Ratio --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Financial Recovery Mix</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Realized collections vs Pending cheques vs Dues</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <div id="financialRatioChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

        {{-- Chart 2: Overdue Aging Distribution --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Overdue Risk Ageing</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Delinquency distribution by aging buckets</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            </div>
            <div id="agingBucketChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

        {{-- Chart 3: Payment Instrument Share --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Payment Instrument Mix</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Receipt allocations across bank modes & cash</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <div id="paymentModeChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (DIRECTLY ABOVE TABLE) (Web Only) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all mb-4 print:hidden">
        <div class="flex items-center gap-2 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Search Customer / Unit --}}
            <div class="relative min-w-[170px] flex-[1.3]" style="flex: 1.3 1 0%;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       x-model="filters.search"
                       @input.debounce.250ms="currentPage = 1; updateCharts()"
                       placeholder="Search customer / unit..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="filters.search">
                    <button type="button" @click="filters.search = ''; currentPage = 1; updateCharts()" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
                </template>
            </div>

            {{-- 2. Customer Filter (Search & Select Dropdown) --}}
            <div class="relative min-w-[140px] flex-1" style="flex: 1 1 0%;"
                 x-data="{
                         open: false,
                         search: '',
                         get selectedCustomer() {
                             return allCustomersList.find(c => String(c.id) === String(filters.customer_id));
                         },
                         get filteredCustomers() {
                             const q = (this.search || '').toLowerCase().trim();
                             if (!q) return allCustomersList;
                             return allCustomersList.filter(c => 
                                 (c.name && c.name.toLowerCase().includes(q)) || 
                                 (c.phone && c.phone.includes(q))
                             );
                         },
                         select(id) {
                             filters.customer_id = id;
                             currentPage = 1;
                             updateCharts();
                             this.open = false;
                             this.search = '';
                         },
                         clear() {
                             filters.customer_id = 'all';
                             currentPage = 1;
                             updateCharts();
                             this.open = false;
                             this.search = '';
                         }
                     }"
                     :class="open ? 'z-30' : ''"
                     @click.outside="open = false">

                    <button type="button"
                            @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                            class="erp-dropdown-trigger px-2.5"
                            :class="open ? 'active' : ''">
                        
                        <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                            <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate text-xs font-bold"
                                  :class="selectedCustomer ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="selectedCustomer ? selectedCustomer.name : 'All Customers'">All Customers</span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <template x-if="selectedCustomer">
                                <span @click.stop="clear()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover min-w-[240px] w-max"
                         style="display: none;">
                        
                        {{-- Search Input inside Popover --}}
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text"
                                       x-model="search"
                                       x-ref="customerSearchInput"
                                       placeholder="Type to search customer..."
                                       @keydown.escape="open = false"
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                <template x-if="search">
                                    <button type="button" @click="search = ''; $refs.customerSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>

                        {{-- All Customers Option --}}
                        <button type="button" @click="clear()"
                                class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer"
                                :class="filters.customer_id === 'all' ? 'bg-[#a38c29]/10 text-[#8a7522] font-black' : ''">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>— All Customers —</span>
                        </button>

                        {{-- Options List --}}
                        <div class="overflow-y-auto flex-1 p-1 space-y-0.5 max-h-52">
                            <template x-for="c in filteredCustomers" :key="c.id">
                                <button type="button"
                                        @click="select(c.id)"
                                        :class="filters.customer_id == c.id ? 'bg-[#a38c29]/15 text-[#8a7522] font-black' : 'hover:bg-slate-50 text-slate-700'"
                                        class="w-full px-2.5 py-1.5 text-left text-xs rounded-xl transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div :class="filters.customer_id == c.id ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                             class="w-5 h-5 rounded-full font-bold text-[9px] flex items-center justify-center shrink-0 transition-colors"
                                             x-text="(c.name || '?').charAt(0).toUpperCase()">
                                        </div>
                                        <span class="truncate text-xs" :class="filters.customer_id == c.id ? 'text-[#8a7522] font-bold' : 'text-slate-800'" x-text="c.name"></span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono shrink-0" x-show="c.phone" x-text="c.phone"></span>
                                </button>
                            </template>
                            <div x-show="filteredCustomers.length === 0" class="py-4 text-center text-slate-400 text-xs">
                                No customers found
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 3. Unit Type Filter --}}
            <div class="relative min-w-[130px] flex-[0.95]" style="flex: 0.95 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="truncate" x-text="filters.unit_type === 'all' ? 'All Unit Types' : filters.unit_type">All Unit Types</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[170px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.unit_type = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.unit_type === 'all' ? 'selected-all' : ''">
                            <span>All Unit Types</span>
                        </div>
                        @foreach($unitTypes as $ut)
                            <div @click="filters.unit_type = '{{ $ut->name }}'; currentPage = 1; updateCharts(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.unit_type === '{{ $ut->name }}' ? 'selected' : ''">
                                <span>{{ $ut->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. Ageing Bucket Filter --}}
            <div class="relative min-w-[160px] flex-[1.2]" style="flex: 1.2 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate" x-text="getAgeingBucketLabel()">All Ageing Buckets</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[180px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.aging_bucket = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === 'all' ? 'selected-all' : ''">
                            <span>All Ageing Buckets</span>
                        </div>
                        <div @click="filters.aging_bucket = 'current'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === 'current' ? 'selected' : ''">
                            <span>Current / Not Due</span>
                        </div>
                        <div @click="filters.aging_bucket = '0-30'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '0-30' ? 'selected' : ''">
                            <span>0 - 30 Days</span>
                        </div>
                        <div @click="filters.aging_bucket = '31-60'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '31-60' ? 'selected' : ''">
                            <span>31 - 60 Days</span>
                        </div>
                        <div @click="filters.aging_bucket = '61-90'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '61-90' ? 'selected' : ''">
                            <span>61 - 90 Days</span>
                        </div>
                        <div @click="filters.aging_bucket = '90+'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '90+' ? 'selected' : ''">
                            <span>&gt; 90 Days</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Realization Status Filter --}}
            <div class="relative min-w-[140px] flex-1" style="flex: 1 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate" x-text="getRealizationStatusLabel()">All Realization</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[195px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.realization_status = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.realization_status === 'all' ? 'selected-all' : ''">
                            <span>All Realization Records</span>
                        </div>
                        <div @click="filters.realization_status = 'realized_only'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.realization_status === 'realized_only' ? 'selected' : ''">
                            <span>Fully Realized Only</span>
                        </div>
                        <div @click="filters.realization_status = 'has_pending'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.realization_status === 'has_pending' ? 'selected' : ''">
                            <span>Has Pending Cheques</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Financial Year Filter --}}
            <div class="relative min-w-[155px] flex-[1.15]" style="flex: 1.15 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate" x-text="filters.financial_year === 'all' ? 'All Financial Years' : filters.financial_year">All Financial Years</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[190px] w-max right-0">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.financial_year = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.financial_year === 'all' ? 'selected-all' : ''">
                            <span>All Financial Years</span>
                        </div>
                        @if(isset($allFinancialYears))
                            @foreach($allFinancialYears as $fy)
                                <div @click="filters.financial_year = '{{ $fy }}'; currentPage = 1; updateCharts(); open = false"
                                     class="erp-dropdown-option"
                                     :class="filters.financial_year === '{{ $fy }}' ? 'selected' : ''">
                                    <span>{{ $fy }}</span>
                                </div>
                            @endforeach
                        @else
                            <div @click="filters.financial_year = 'FY 2026-27'; currentPage = 1; updateCharts(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.financial_year === 'FY 2026-27' ? 'selected' : ''">
                                <span>FY 2026-27</span>
                            </div>
                            <div @click="filters.financial_year = 'FY 2025-26'; currentPage = 1; updateCharts(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.financial_year === 'FY 2025-26' ? 'selected' : ''">
                                <span>FY 2025-26</span>
                            </div>
                            <div @click="filters.financial_year = 'FY 2024-25'; currentPage = 1; updateCharts(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.financial_year === 'FY 2024-25' ? 'selected' : ''">
                                <span>FY 2024-25</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons: Overdue Only Toggle & Reset Filters --}}
            <div class="flex items-center gap-2 shrink-0">
                
                {{-- Overdue Only Toggle Button --}}
                <button type="button" 
                        @click="filters.overdue_only = !filters.overdue_only; currentPage = 1; updateCharts()"
                        :class="filters.overdue_only ? 'bg-rose-600 text-white border-rose-700 shadow-sm ring-2 ring-rose-200' : 'bg-[#f8fafc] text-slate-700 border-slate-300 hover:bg-slate-100'"
                        class="h-[38px] px-3.5 rounded-xl border text-xs font-extrabold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs shrink-0 active:scale-95"
                        title="Filter Overdue Dues Only">
                    <span class="w-2 h-2 rounded-full shrink-0" :class="filters.overdue_only ? 'bg-white animate-pulse' : 'bg-rose-500'"></span>
                    <span>Overdue</span>
                </button>

                {{-- Reset Filters Button --}}
                <button type="button" @click="resetFilters()"
                        class="theme-btn h-[38px] px-3.5 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap"
                        title="Reset All Filters">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>

            </div>

        </div>
    </div>

    {{-- ── 5. PROFESSIONAL DATA TABLE CARD (Matched to collection-forecast.blade.php) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:p-0 print:border-none print:shadow-none print:rounded-none">
        {{-- Table Card Header --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white print:px-2 print:py-2">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Customer Dues & Collection Register
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3" x-text="filteredRows.length + ' / ' + rows.length + ' Active Records'"></p>
            </div>
            <div class="flex items-center gap-2.5 print:hidden">
                <button type="button" @click="exportToExcel()" class="h-[38px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" @click="printReport()" class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>PRINT</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar print:overflow-visible">
            <table class="w-full text-xs text-left border-collapse min-w-[1300px] table-fixed print:min-w-full print:w-full">
                <colgroup class="print:hidden">
                    <col style="width: 50px;">
                    <col style="width: 220px;">
                    <col style="width: 185px;">
                    <col style="width: 150px;">
                    <col style="width: 170px;">
                    <col style="width: 150px;">
                    <col style="width: 130px;">
                    <col style="width: 155px;">
                    <col style="width: 90px;">
                </colgroup>
                <colgroup class="hidden print:table-column-group">
                    <col style="width: 4%;">   {{-- # --}}
                    <col style="width: 16%;">  {{-- Customer Details --}}
                    <col style="width: 16%;">  {{-- Property Details --}}
                    <col style="width: 13%;">  {{-- Agreement Value --}}
                    <col style="width: 14%;">  {{-- Collected Amount --}}
                    <col style="width: 13%;">  {{-- Outstanding Dues --}}
                    <col style="width: 11%;">  {{-- Overdue --}}
                    <col style="width: 13%;">  {{-- Aging Risk --}}
                </colgroup>
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr class="erp-table-header border-b border-[#443f38] text-center">
                        <th class="px-3 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">#</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Customer Details</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Property Details</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Agreement Value (₹)</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Collected Amount (₹)</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Outstanding Dues (₹)</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Overdue (₹)</th>
                        <th class="px-3 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px]">Aging Risk</th>
                        <th class="px-3 py-3.5 text-center whitespace-nowrap text-white font-extrabold text-[10px] print:hidden">Actions</th>
                    </tr>
                </thead>
                <template x-for="(row, idx) in paginatedRows" :key="row.sale_id">
                    <tbody class="divide-y divide-slate-100 border-b border-slate-100 font-medium text-slate-700">
                        {{-- Master Table Row --}}
                        <tr class="hover:bg-amber-50/30 transition-colors cursor-pointer group"
                            @click="toggleRow(row.sale_id)"
                            :class="expandedRows.includes(row.sale_id) ? 'bg-[#a38c29]/10' : ''">
                            
                            {{-- 1. Index # --}}
                            <td class="px-3 py-3.5 text-center font-bold text-slate-400 font-mono" x-text="(currentPage - 1) * perPage + idx + 1"></td>

                            {{-- 2. Customer Details --}}
                            <td class="px-4 py-3.5 text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center font-bold text-[10px] shrink-0 uppercase shadow-xs"
                                         x-text="row.customer_name ? row.customer_name.substring(0,2).toUpperCase() : 'CU'"></div>
                                    <div class="min-w-0">
                                        <span class="font-extrabold text-slate-900 block group-hover:text-[#a38c29] transition-colors leading-tight" x-text="row.customer_name"></span>
                                        <div class="flex items-center gap-1.5 text-[10.5px] font-mono text-slate-500 font-semibold mt-0.5">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span x-text="row.customer_phone || 'No phone'"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Property Details --}}
                            <td class="px-4 py-3.5 text-left">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-black text-slate-900 font-mono uppercase text-xs" x-text="row.unit_display"></span>
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-bold text-[9px] border border-slate-200" x-text="row.unit_type"></span>
                                    <template x-if="row.is_exchanged">
                                        <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-black text-[8.5px] uppercase tracking-wider border border-amber-200 shadow-2xs" title="Unit was exchanged with revised rate">Exchanged</span>
                                    </template>
                                </div>
                                <div class="flex items-center justify-between gap-1.5 mt-0.5">
                                    <span class="text-[10px] text-slate-500 font-medium block truncate max-w-[115px]" x-text="row.project_name"></span>
                                    <template x-if="row.rate_per_sqft > 0">
                                        <span class="text-[9.5px] font-mono text-slate-600 font-extrabold whitespace-nowrap" x-text="'₹' + Number(row.rate_per_sqft).toLocaleString('en-IN') + '/sq.ft'"></span>
                                    </template>
                                </div>
                            </td>

                            {{-- 4. Agreement Value --}}
                            <td class="px-4 py-3.5 text-right font-mono">
                                <span class="font-mono font-black text-slate-900 block" x-text="'₹ ' + formatMoney(row.agreement_value)"></span>
                                <span class="text-[9.5px] text-slate-400 font-mono block mt-0.5" x-text="row.agreement_date"></span>
                            </td>

                            {{-- 5. Collected Amount (Realized + Pending Realization on 2nd Line) --}}
                            <td class="px-4 py-3.5 text-right font-mono">
                                {{-- Line 1: Realized Amount --}}
                                <div class="font-extrabold text-emerald-700 leading-tight">
                                    <span x-text="'₹ ' + formatMoney(row.collected_amount)"></span>
                                    <span class="text-[9px] font-bold text-emerald-600 font-sans ml-1" x-text="'(' + row.collected_pct + '%)'"></span>
                                </div>

                                {{-- Line 2: Pending Realization Badge (Separated on second line) --}}
                                <template x-if="row.pending_realization > 0">
                                    <div class="mt-1.5 flex justify-end">
                                        <a :href="'{{ route('cheque-realization.queue') }}?customer_id=' + row.customer_id" 
                                           @click.stop
                                           class="flex flex-col items-end px-2 py-1 rounded-lg bg-gradient-to-r from-amber-50 to-amber-100/90 hover:from-amber-100 hover:to-amber-200 text-amber-950 border border-amber-300 hover:border-amber-400 transition-all group shadow-2xs cursor-pointer"
                                           title="Click to view & process in Cheque Realization Queue">
                                            {{-- Line 1 of badge: Amount with hourglass --}}
                                            <div class="inline-flex items-center gap-1 font-bold text-amber-950 text-[10.5px] leading-tight">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                                                <span class="text-[10px]">⏳</span>
                                                <span x-text="'₹ ' + formatMoney(row.pending_realization)"></span>
                                            </div>
                                            {{-- Line 2 of badge: Pending Realization text --}}
                                            <div class="inline-flex items-center gap-1 text-[8.5px] font-extrabold uppercase tracking-wider text-amber-800 group-hover:text-amber-950 mt-0.5 leading-tight font-sans">
                                                <span>Pending Realization</span>
                                                <svg class="w-2.5 h-2.5 text-amber-700 transition-transform group-hover:translate-x-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </div>
                                        </a>
                                    </div>
                                </template>
                            </td>

                            {{-- 6. Outstanding Dues (Not deducting unrealized amount) --}}
                            <td class="px-4 py-3.5 text-right font-mono">
                                <span class="font-black block text-xs" 
                                      :class="row.outstanding_amount > 0 ? 'text-rose-600' : 'text-slate-400'"
                                      x-text="'₹ ' + formatMoney(row.outstanding_amount)"></span>
                                <span class="text-[9px] font-bold text-slate-400 font-sans block mt-0.5" 
                                      x-text="row.outstanding_pct + '% Dues'"></span>
                            </td>

                            {{-- 7. Overdue Amount --}}
                            <td class="px-4 py-3.5 text-right font-mono">
                                <span class="font-black block" 
                                      :class="row.overdue_amount > 0 ? 'text-rose-700' : 'text-slate-400'"
                                      x-text="row.overdue_amount > 0 ? '₹ ' + formatMoney(row.overdue_amount) : '₹ 0.00'"></span>
                            </td>

                            {{-- 8. Aging Risk & Bucket --}}
                            <td class="px-2 py-3.5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-2xs whitespace-nowrap"
                                          :class="getBucketBadgeClass(row.aging_bucket)"
                                          x-text="row.aging_bucket_label"></span>
                                    <template x-if="row.days_overdue > 0">
                                        <span class="text-[9px] font-bold text-rose-600 font-mono whitespace-nowrap" x-text="row.days_overdue + 'd Overdue'"></span>
                                    </template>
                                    <template x-if="row.days_overdue <= 0">
                                        <span class="text-[9px] font-bold text-emerald-600 font-sans whitespace-nowrap">On Track</span>
                                    </template>
                                </div>
                            </td>

                            {{-- 9. Actions --}}
                            <td class="px-3 py-3.5 text-center w-20 print:hidden" @click.stop>
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Toggle Accordion Button --}}
                                    <button type="button" 
                                            @click="toggleRow(row.sale_id)"
                                            class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-[#a38c29] hover:text-white hover:border-[#a38c29] transition-all flex items-center justify-center cursor-pointer shadow-2xs"
                                            :title="expandedRows.includes(row.sale_id) ? 'Collapse Breakdown' : 'Expand Milestone & Receipt Breakdown'">
                                        <svg class="w-3.5 h-3.5 transform transition-transform" 
                                             :class="expandedRows.includes(row.sale_id) ? 'rotate-180' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>

                                    {{-- View Ledger Statement Button --}}
                                    <a :href="'{{ route('reports.customer_ledger') }}?customer_id=' + row.customer_id" 
                                       class="w-7 h-7 rounded-lg border border-[#a38c29]/30 bg-white text-[#a38c29] hover:bg-[#a38c29] hover:text-white hover:border-[#a38c29] transition-all flex items-center justify-center cursor-pointer shadow-2xs"
                                       title="Open in Customer Ledger Console">
                                        <svg class="w-3.5 h-3.5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- ── EXPANDABLE DETAIL ACCORDION ── --}}
                        <tr x-show="expandedRows.includes(row.sale_id)" 
                            style="display: none;"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-white border-b border-slate-200 print:hidden">
                            <td colspan="9" class="p-4 sm:p-5">
                                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-5">
                                        {{-- 4 Snapshot Cards (Premium White Cards) --}}
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3.5">
                                            
                                            {{-- 1. Customer Information --}}
                                            <div class="p-4 rounded-xl bg-white border border-slate-200/90 border-l-[5px] border-l-blue-600 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between">
                                                <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-slate-100">
                                                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Customer Details</span>
                                                    <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    </div>
                                                </div>
                                                <div class="divide-y divide-slate-100/90 text-xs">
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Name:</span>
                                                        <strong class="text-slate-900 truncate text-right font-bold" :title="row.customer_name" x-text="row.customer_name"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Mobile:</span>
                                                        <strong class="text-slate-800 font-mono font-bold text-right" x-text="row.customer_phone || '—'"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Email:</span>
                                                        <span class="text-slate-700 truncate max-w-[140px] text-right font-medium" :title="row.customer_email" x-text="row.customer_email || '—'"></span>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Address:</span>
                                                        <span class="text-slate-700 truncate max-w-[140px] text-right font-medium" :title="row.customer_address" x-text="row.customer_address || '—'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- 2. Property Information --}}
                                            <div class="p-4 rounded-xl bg-white border border-slate-200/90 border-l-[5px] border-l-indigo-600 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between">
                                                <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-slate-100">
                                                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Property Details</span>
                                                    <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    </div>
                                                </div>
                                                <div class="divide-y divide-slate-100/90 text-xs">
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Unit:</span>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 font-mono font-black text-slate-900 text-[11px] border border-slate-200/80" x-text="row.unit_display"></span>
                                                            <template x-if="row.is_exchanged">
                                                                <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 text-[9px] font-black uppercase tracking-wider border border-amber-300">Exchanged</span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Unit Rate:</span>
                                                        <span class="text-slate-900 font-mono font-black text-right" x-text="row.rate_per_sqft > 0 ? ('₹ ' + formatMoney(row.rate_per_sqft) + ' / sq.ft') : '—'"></span>
                                                    </div>
                                                    <template x-if="row.area_sqft > 0">
                                                        <div class="py-1.5 flex items-center justify-between gap-2">
                                                            <span class="text-slate-400 font-semibold text-[11px] shrink-0">Super Area:</span>
                                                            <span class="text-slate-800 font-mono font-bold text-right" x-text="row.area_sqft + ' sq.ft'"></span>
                                                        </div>
                                                    </template>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Project:</span>
                                                        <strong class="text-slate-800 truncate max-w-[140px] text-right font-bold" :title="row.project_name" x-text="row.project_name || '—'"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Floor:</span>
                                                        <span class="text-slate-700 font-medium text-right" x-text="row.floor_display || '—'"></span>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Agreement Date:</span>
                                                        <span class="text-slate-800 font-mono font-bold text-right" x-text="row.agreement_date || '—'"></span>
                                                    </div>
                                                </div>
                                                <template x-if="row.is_exchanged && row.exchange_notes">
                                                    <div class="mt-2 text-[10px] text-amber-800 bg-amber-50 p-2 rounded-lg border border-amber-200">
                                                        <span class="font-extrabold uppercase tracking-wider text-[9px] block">Exchange Note:</span>
                                                        <span class="font-medium" x-text="row.exchange_notes"></span>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- 3. Financial Recovery Summary --}}
                                            <div class="p-4 rounded-xl bg-white border border-slate-200/90 border-l-[5px] border-l-emerald-600 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between">
                                                <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-slate-100">
                                                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Financial Audit</span>
                                                    <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    </div>
                                                </div>
                                                <div class="divide-y divide-slate-100/90 text-xs">
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Contract Value:</span>
                                                        <strong class="text-slate-900 font-mono font-black text-right" x-text="'₹ ' + formatMoney(row.agreement_value)"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-slate-400 font-semibold text-[11px] shrink-0">Realized Paid:</span>
                                                        <strong class="text-emerald-700 font-mono font-black text-right" x-text="'₹ ' + formatMoney(row.collected_amount)"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-amber-700 font-bold text-[11px] shrink-0">Pending Cheques:</span>
                                                        <strong class="font-mono font-black text-right" :class="row.pending_realization > 0 ? 'text-amber-800' : 'text-slate-400'" x-text="'₹ ' + formatMoney(row.pending_realization)"></strong>
                                                    </div>
                                                    <div class="py-1.5 flex items-center justify-between gap-2">
                                                        <span class="text-rose-600 font-bold text-[11px] shrink-0">Outstanding Dues:</span>
                                                        <strong class="font-mono font-black text-right" :class="row.outstanding_amount > 0 ? 'text-rose-700' : 'text-emerald-700'" x-text="'₹ ' + formatMoney(row.outstanding_amount)"></strong>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- 4. Payment Mode Breakdown --}}
                                            <div class="p-4 rounded-xl bg-white border border-slate-200/90 border-l-[5px] border-l-amber-500 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between">
                                                <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-slate-100">
                                                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Instrument Share</span>
                                                    <div class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                                    </div>
                                                </div>
                                                <div class="text-xs space-y-2 pt-0.5">
                                                    <template x-for="(amt, mode) in row.payment_modes" :key="mode">
                                                        <div class="space-y-1">
                                                            <div class="flex items-center justify-between text-[11px]">
                                                                <span class="text-slate-700 font-bold" x-text="mode"></span>
                                                                <span class="font-mono font-black text-slate-900" x-text="'₹ ' + formatMoney(amt)"></span>
                                                            </div>
                                                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                                                <div class="bg-gradient-to-r from-[#a38c29] to-[#8a7522] h-full rounded-full transition-all duration-300" 
                                                                     :style="'width: ' + (row.agreement_value > 0 ? (amt / row.agreement_value * 100) : 0) + '%'"></div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="Object.keys(row.payment_modes).length === 0">
                                                        <p class="text-[11px] text-slate-400 italic py-3 text-center">No receipts recorded yet.</p>
                                                    </template>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Sub-tabs: Milestone Schedule vs Payment History --}}
                                        <div x-data="{ subTab: 'milestones' }" class="pt-2">
                                            <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                                                <div class="flex items-center gap-2">
                                                    <button type="button" 
                                                            @click="subTab = 'milestones'"
                                                            :class="subTab === 'milestones' ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition cursor-pointer">
                                                        Milestone Schedule (<span x-text="row.milestones.length"></span>)
                                                    </button>
                                                    <button type="button" 
                                                            @click="subTab = 'payments'"
                                                            :class="subTab === 'payments' ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition cursor-pointer">
                                                        Payment Receipts (<span x-text="row.receipts.length"></span>)
                                                    </button>
                                                </div>

                                                <a :href="'{{ route('reports.customer_ledger') }}?customer_id=' + row.customer_id" 
                                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#a38c29] hover:text-[#8a7522] hover:underline">
                                                    <span>View In Customer Ledger</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            </div>

                                            {{-- SubTab 1: Milestone Schedule --}}
                                            <div x-show="subTab === 'milestones'" class="pt-3 overflow-x-auto">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-slate-100 text-[9.5px] font-black text-slate-600 uppercase tracking-wider border-b text-center">
                                                            <th class="py-2.5 px-3 w-10 text-center">#</th>
                                                            <th class="py-2.5 px-3 text-center">Milestone Stage</th>
                                                            <th class="py-2.5 px-3 text-center">Due Date</th>
                                                            <th class="py-2.5 px-3 text-center">Milestone Amount (₹)</th>
                                                            <th class="py-2.5 px-3 text-center">Status</th>
                                                            <th class="py-2.5 px-3 text-center">Amount Paid (₹)</th>
                                                            <th class="py-2.5 px-3 text-center">Balance Due (₹)</th>
                                                            <th class="py-2.5 px-3 text-center">Overdue Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-medium">
                                                        <template x-for="m in row.milestones" :key="m.installment_no">
                                                            <tr class="hover:bg-slate-50">
                                                                <td class="py-2 px-3 text-center font-mono text-slate-400" x-text="m.installment_no"></td>
                                                                <td class="py-2 px-3 font-bold text-slate-800" x-text="m.label"></td>
                                                                <td class="py-2 px-3 font-mono text-slate-600" x-text="m.due_date"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-bold text-slate-900" x-text="'₹ ' + formatMoney(m.amount)"></td>
                                                                <td class="py-2 px-3 text-center">
                                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border"
                                                                          :class="m.status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                                                                          x-text="m.status"></span>
                                                                </td>
                                                                <td class="py-2 px-3 text-right font-mono font-bold" 
                                                                    :class="m.status === 'Paid' ? 'text-emerald-700' : 'text-slate-900'"
                                                                    x-text="'₹ ' + formatMoney(m.paid_amount)"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-black" 
                                                                    :class="m.balance > 0 ? 'text-amber-700' : 'text-slate-400'"
                                                                    x-text="'₹ ' + formatMoney(m.balance)"></td>
                                                                <td class="py-2 px-3 text-center font-mono font-bold"
                                                                    :class="m.days_overdue > 0 ? 'text-rose-600' : 'text-slate-400'"
                                                                    x-text="m.days_overdue > 0 ? m.days_overdue + 'd' : '—'"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>

                                            {{-- SubTab 2: Payment Receipts --}}
                                            <div x-show="subTab === 'payments'" class="pt-3 overflow-x-auto" style="display: none;">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-slate-100 text-[9.5px] font-black text-slate-600 uppercase tracking-wider border-b text-center">
                                                            <th class="py-2.5 px-3 w-10 text-center">#</th>
                                                            <th class="py-2.5 px-3 text-center">Receipt / Ref No</th>
                                                            <th class="py-2.5 px-3 text-center">Receipt Date</th>
                                                            <th class="py-2.5 px-3 text-center">Payment Mode</th>
                                                            <th class="py-2.5 px-3 text-center">Bank Details</th>
                                                            <th class="py-2.5 px-3 text-center">Amount (₹)</th>
                                                            <th class="py-2.5 px-3 text-center">Realization Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-medium">
                                                        <template x-for="r in row.receipts" :key="r.id">
                                                            <tr class="hover:bg-slate-50">
                                                                <td class="py-2 px-3 text-center font-mono text-slate-400" x-text="r.index"></td>
                                                                <td class="py-2 px-3 font-mono font-bold text-slate-900" x-text="r.receipt_no"></td>
                                                                <td class="py-2 px-3 font-mono text-slate-600" x-text="r.receipt_date"></td>
                                                                <td class="py-2 px-3 font-semibold text-slate-700" x-text="r.payment_mode"></td>
                                                                <td class="py-2 px-3 text-slate-600 truncate max-w-[150px]" x-text="r.drawee_bank || '—'"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-black text-emerald-700" x-text="'₹ ' + formatMoney(r.amount)"></td>
                                                                <td class="py-2 px-3 text-center">
                                                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-2xs inline-flex items-center gap-1"
                                                                          :class="r.realization_status === 'realized' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-300'">
                                                                        <span x-text="r.realization_status === 'realized' ? '✅' : '⏳'"></span>
                                                                        <span x-text="r.realization_label"></span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <template x-if="row.receipts.length === 0">
                                                            <tr>
                                                                <td colspan="7" class="py-6 text-center text-slate-400 italic">No receipts recorded for this booking.</td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                    <tbody x-show="filteredRows.length === 0">
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                No customer collection or dues matching the selected filter criteria.
                            </td>
                        </tr>
                    </tbody>

                    {{-- ── GRAND TOTAL SUMMARY FOOTER (NORMAL LIGHT THEME) ── --}}
                    <tfoot class="bg-slate-100 border-t-2 border-slate-300 border-b-2 border-slate-200 font-mono text-xs shadow-2xs">
                        <tr class="font-bold text-slate-900">
                            <td colspan="3" class="px-4 py-3.5 text-center font-sans font-black uppercase tracking-wider text-slate-800">
                                <span class="text-[#8a7522]">Grand Summary</span> (<span x-text="filteredRows.length"></span> Bookings)
                            </td>
                            <td class="px-4 py-3.5 text-right font-black font-mono text-slate-900 text-xs" x-text="'₹ ' + formatMoney(summaryKPIs.total_agreement_value)"></td>
                            <td class="px-4 py-3.5 text-right font-black font-mono text-emerald-700 text-xs" x-text="'₹ ' + formatMoney(summaryKPIs.total_collected)"></td>
                            <td class="px-4 py-3.5 text-right font-black font-mono text-rose-700 text-xs" x-text="'₹ ' + formatMoney(summaryKPIs.total_outstanding)"></td>
                            <td class="px-4 py-3.5 text-right font-black font-mono text-red-700 text-xs" x-text="'₹ ' + formatMoney(summaryKPIs.total_overdue)"></td>
                            <td :colspan="isPrinting ? 1 : 2" class="px-4 py-3.5 text-center font-sans text-[10px] font-black text-slate-500 uppercase tracking-wider">
                                Audited Totals
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Dynamic Reactive Pagination (collection-forecast style, 50 entries per page default) --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 print:hidden">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="font-medium">
                        Showing <span class="font-bold text-slate-800" x-text="filteredRows.length ? (currentPage - 1) * perPage + 1 : 0"></span> to 
                        <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredRows.length)"></span> of 
                        <span class="font-bold text-slate-800" x-text="filteredRows.length"></span> entries
                    </div>
                    <div class="flex items-center gap-1.5 pl-3 border-l border-slate-200">
                        <span class="text-slate-400 font-medium">Rows per page:</span>
                        <select x-model.number="perPage" @change="currentPage = 1; window.scrollTo({ top: 400, behavior: 'smooth' })"
                                class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 hover:border-[#a38c29] focus:border-[#a38c29] focus:outline-none transition cursor-pointer shadow-2xs">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                {{-- Pagination Buttons (Always visible when filtered rows exist) --}}
                <div class="flex items-center gap-1" x-show="filteredRows.length > 0">
                    <button type="button" 
                            @click="if(currentPage > 1) { currentPage--; window.scrollTo({ top: 400, behavior: 'smooth' }); }" 
                            :disabled="currentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition cursor-pointer">
                        Previous
                    </button>
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p; window.scrollTo({ top: 400, behavior: 'smooth' });" 
                                x-show="p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2)"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white border-[#a38c29]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                                class="w-8 h-8 rounded-lg border text-xs font-bold transition flex items-center justify-center cursor-pointer">
                            <span x-text="p"></span>
                        </button>
                    </template>
                    <button type="button" 
                            @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({ top: 400, behavior: 'smooth' }); }" 
                            :disabled="currentPage === totalPages"
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition cursor-pointer">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
function customerCollectionDuesApp() {
    return {
        rows: @js($rows),
        allCustomersList: @js($allCustomers),
        asOfDate: '{{ $asOfDate->format("Y-m-d") }}',
        
        // Common pagination with 50 entries
        currentPage: 1,
        perPage: 50,

        filters: {
            search: '',
            customer_id: '{{ request("customer_id") ? (is_array(request("customer_id")) ? request("customer_id")[0] : request("customer_id")) : "all" }}',
            unit_type: '{{ $selectedUnitType ?? "all" }}',
            as_of_date: '{{ $asOfDate->format("Y-m-d") }}',
            aging_bucket: 'all',
            realization_status: 'all',
            financial_year: 'all',
            overdue_only: false,
        },

        expandedRows: [],
        charts: {},
        isPrinting: false,

        init() {
            this.$nextTick(() => {
                this.initCharts();
            });
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
            this.filters.search = '';
            this.filters.customer_id = 'all';
            this.filters.unit_type = 'all';
            this.filters.aging_bucket = 'all';
            this.filters.realization_status = 'all';
            this.filters.financial_year = 'all';
            this.filters.overdue_only = false;
            this.currentPage = 1;
            this.expandedRows = [];
            this.updateCharts();
        },

        getFinancialYearFromDate(dateStr) {
            if (!dateStr || dateStr === '—') return null;
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                const month = parseInt(parts[1], 10);
                const year = parseInt(parts[2], 10);
                if (month >= 4) {
                    return `FY ${year}-${String(year + 1).slice(-2)}`;
                } else {
                    return `FY ${year - 1}-${String(year).slice(-2)}`;
                }
            }
            return null;
        },

        toggleRow(saleId) {
            if (this.expandedRows.includes(saleId)) {
                this.expandedRows = [];
            } else {
                this.expandedRows = [saleId];
            }
        },

        formatMoney(amount) {
            if (isNaN(amount) || amount === null) return '0.00';
            return Number(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        formatShortMoney(amount) {
            const val = Number(amount || 0);
            if (val >= 10000000) {
                return (val / 10000000).toFixed(2) + ' Cr';
            } else if (val >= 100000) {
                return (val / 100000).toFixed(2) + ' L';
            }
            return this.formatMoney(val);
        },

        getBucketBadgeClass(bucket) {
            switch(bucket) {
                case '0-30':
                    return 'bg-emerald-50 text-emerald-700 border-emerald-200';
                case '31-60':
                    return 'bg-amber-50 text-amber-700 border-amber-200';
                case '61-90':
                    return 'bg-orange-50 text-orange-700 border-orange-200';
                case '90+':
                    return 'bg-rose-50 text-rose-700 border-rose-200';
                default:
                    return 'bg-blue-50 text-blue-700 border-blue-200';
            }
        },

        getAgeingBucketLabel() {
            switch(this.filters.aging_bucket) {
                case 'current': return 'Current / Not Due';
                case '0-30': return '0 - 30 Days';
                case '31-60': return '31 - 60 Days';
                case '61-90': return '61 - 90 Days';
                case '90+': return '> 90 Days';
                default: return 'All Ageing Buckets';
            }
        },

        getRealizationStatusLabel() {
            switch(this.filters.realization_status) {
                case 'realized_only': return 'Fully Realized Only';
                case 'has_pending': return 'Has Pending Cheques';
                default: return 'All Realization';
            }
        },

        get filteredRows() {
            return this.rows.filter(row => {
                // Search query
                if (this.filters.search) {
                    const q = this.filters.search.toLowerCase().trim();
                    const matchName = (row.customer_name || '').toLowerCase().includes(q);
                    const matchPhone = (row.customer_phone || '').toLowerCase().includes(q);
                    const matchEmail = (row.customer_email || '').toLowerCase().includes(q);
                    const matchUnit = (row.unit_display || '').toLowerCase().includes(q);
                    const matchProject = (row.project_name || '').toLowerCase().includes(q);
                    if (!matchName && !matchPhone && !matchEmail && !matchUnit && !matchProject) {
                        return false;
                    }
                }

                // Customer filter
                if (this.filters.customer_id && this.filters.customer_id !== 'all') {
                    if (String(row.customer_id) !== String(this.filters.customer_id)) {
                        return false;
                    }
                }

                // Unit Type filter
                if (this.filters.unit_type && this.filters.unit_type !== 'all') {
                    if ((row.unit_type || '').toLowerCase() !== this.filters.unit_type.toLowerCase()) {
                        return false;
                    }
                }

                // Aging Bucket filter
                if (this.filters.aging_bucket !== 'all') {
                    if (row.aging_bucket !== this.filters.aging_bucket) {
                        return false;
                    }
                }

                // Realization Status filter
                if (this.filters.realization_status === 'realized_only') {
                    if (row.pending_realization > 0) return false;
                } else if (this.filters.realization_status === 'has_pending') {
                    if (!row.pending_realization || row.pending_realization <= 0) return false;
                }

                // Financial Year filter
                if (this.filters.financial_year && this.filters.financial_year !== 'all') {
                    const rowFY = row.financial_year || this.getFinancialYearFromDate(row.agreement_date);
                    if (rowFY !== this.filters.financial_year) {
                        return false;
                    }
                }

                // Overdue Only filter
                if (this.filters.overdue_only) {
                    if (!row.overdue_amount || row.overdue_amount <= 0) {
                        return false;
                    }
                }

                return true;
            });
        },

        get totalPages() {
            return Math.ceil(this.filteredRows.length / this.perPage) || 1;
        },

        get paginatedRows() {
            if (this.isPrinting) {
                return this.filteredRows;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredRows.slice(start, start + this.perPage);
        },

        get summaryKPIs() {
            const list = this.filteredRows;
            const uniqueCusts = new Set(list.map(r => r.customer_id).filter(Boolean)).size;
            
            let totAgr = 0;
            let totColl = 0;
            let totPending = 0;
            let totOut = 0;
            let totOver = 0;

            list.forEach(r => {
                totAgr += Number(r.agreement_value || 0);
                totColl += Number(r.collected_amount || 0); // Realized collection
                totPending += Number(r.pending_realization || 0);
                totOut += Number(r.outstanding_amount || 0);
                totOver += Number(r.overdue_amount || 0);
            });

            const collPct = totAgr > 0 ? ((totColl / totAgr) * 100).toFixed(1) : '0.0';
            const outPct = totAgr > 0 ? ((totOut / totAgr) * 100).toFixed(1) : '0.0';
            const overPct = totOut > 0 ? ((totOver / totOut) * 100).toFixed(1) : '0.0';

            return {
                total_customers: uniqueCusts || list.length,
                total_agreement_value: totAgr,
                total_collected: totColl,
                total_collected_pct: collPct,
                total_pending_realization: totPending,
                total_outstanding: totOut,
                total_outstanding_pct: outPct,
                total_overdue: totOver,
                total_overdue_pct: overPct,
            };
        },

        initCharts() {
            if (typeof ApexCharts === 'undefined') return;

            const kpis = this.summaryKPIs;

            // ── 1. Financial Ratio Donut Chart ──
            const ratioEl = document.getElementById('financialRatioChart');
            if (ratioEl) {
                const currentNotDue = Math.max(0, kpis.total_outstanding - kpis.total_overdue);
                const values = [
                    Number(kpis.total_collected.toFixed(2)),
                    Number(kpis.total_pending_realization.toFixed(2)),
                    Number(currentNotDue.toFixed(2)),
                    Number(kpis.total_overdue.toFixed(2))
                ];

                const ratioOptions = {
                    series: values.some(v => v > 0) ? values : [1],
                    labels: values.some(v => v > 0) ? ['Realized Collected', 'Pending Cheques', 'Current (Not Due)', 'Overdue Dues'] : ['No Data'],
                    colors: ['#059669', '#D97706', '#2563EB', '#DC2626'],
                    chart: { type: 'donut', height: 230, fontFamily: 'Inter, sans-serif' },
                    dataLabels: { enabled: false },
                    legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                    tooltip: { y: { formatter: (val) => '₹ ' + this.formatMoney(val) } },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Agreement',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => this.formatShortMoney(kpis.total_agreement_value)
                                    }
                                }
                            }
                        }
                    }
                };
                this.charts.ratio = new ApexCharts(ratioEl, ratioOptions);
                this.charts.ratio.render();
            }

            // ── 2. Overdue Aging Bucket Bar Chart ──
            const agingEl = document.getElementById('agingBucketChart');
            if (agingEl) {
                const buckets = { '0-30': 0, '31-60': 0, '61-90': 0, '90+': 0, 'current': 0 };
                this.filteredRows.forEach(r => {
                    const b = r.aging_bucket || 'current';
                    buckets[b] = (buckets[b] || 0) + Number(r.overdue_amount || 0);
                });

                const agingOptions = {
                    series: [{
                        name: 'Overdue Amount',
                        data: [buckets['current'], buckets['0-30'], buckets['31-60'], buckets['61-90'], buckets['90+']]
                    }],
                    chart: { type: 'bar', height: 230, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3B82F6', '#10B981', '#F59E0B', '#F97316', '#EF4444'],
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 6,
                            columnWidth: '45%'
                        }
                    },
                    dataLabels: { enabled: false },
                    legend: { show: false },
                    xaxis: {
                        categories: ['Current', '0-30d', '31-60d', '61-90d', '90+d'],
                        labels: { style: { fontSize: '10px', fontWeight: 700 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (val) => this.formatShortMoney(val)
                        }
                    },
                    tooltip: { y: { formatter: (val) => '₹ ' + this.formatMoney(val) } }
                };
                this.charts.aging = new ApexCharts(agingEl, agingOptions);
                this.charts.aging.render();
            }

            // ── 3. Payment Mode Donut Chart ──
            const modeEl = document.getElementById('paymentModeChart');
            if (modeEl) {
                const modeSums = {};
                this.filteredRows.forEach(r => {
                    if (r.payment_modes) {
                        for (const [m, a] of Object.entries(r.payment_modes)) {
                            modeSums[m] = (modeSums[m] || 0) + Number(a || 0);
                        }
                    }
                });

                const modes = Object.keys(modeSums).length > 0 ? Object.keys(modeSums) : ['No Collections'];
                const values = Object.keys(modeSums).length > 0 ? Object.values(modeSums) : [1];

                const modeOptions = {
                    series: values,
                    labels: modes,
                    colors: ['#2563EB', '#F59E0B', '#8B5CF6', '#10B981', '#64748B'],
                    chart: { type: 'donut', height: 230, fontFamily: 'Inter, sans-serif' },
                    dataLabels: { enabled: false },
                    legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                    tooltip: { y: { formatter: (val) => '₹ ' + this.formatMoney(val) } },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Realized Paid',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => this.formatShortMoney(kpis.total_collected)
                                    }
                                }
                            }
                        }
                    }
                };
                this.charts.mode = new ApexCharts(modeEl, modeOptions);
                this.charts.mode.render();
            }
        },

        updateCharts() {
            this.currentPage = 1;
            if (!this.charts.ratio) return;
            const kpis = this.summaryKPIs;

            // Update Ratio
            const currentNotDue = Math.max(0, kpis.total_outstanding - kpis.total_overdue);
            const rValues = [
                Number(kpis.total_collected.toFixed(2)),
                Number(kpis.total_pending_realization.toFixed(2)),
                Number(currentNotDue.toFixed(2)),
                Number(kpis.total_overdue.toFixed(2))
            ];
            this.charts.ratio.updateSeries(rValues.some(v => v > 0) ? rValues : [1]);

            // Update Aging
            const buckets = { '0-30': 0, '31-60': 0, '61-90': 0, '90+': 0, 'current': 0 };
            this.filteredRows.forEach(r => {
                const b = r.aging_bucket || 'current';
                buckets[b] = (buckets[b] || 0) + Number(r.overdue_amount || 0);
            });
            this.charts.aging.updateSeries([{
                data: [buckets['current'], buckets['0-30'], buckets['31-60'], buckets['61-90'], buckets['90+']]
            }]);

            // Update Modes
            const modeSums = {};
            this.filteredRows.forEach(r => {
                if (r.payment_modes) {
                    for (const [m, a] of Object.entries(r.payment_modes)) {
                        modeSums[m] = (modeSums[m] || 0) + Number(a || 0);
                    }
                }
            });
            const modes = Object.keys(modeSums).length > 0 ? Object.keys(modeSums) : ['No Collections'];
            const mValues = Object.keys(modeSums).length > 0 ? Object.values(modeSums) : [1];
            this.charts.mode.updateOptions({ labels: modes });
            this.charts.mode.updateSeries(mValues);
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading. Please try again.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'TABASCO Human Capital';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Collection & Dues Register', {
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
                    { key: 'customer', width: 28 },
                    { key: 'phone', width: 18 },
                    { key: 'unit', width: 50 },
                    { key: 'agreement', width: 24 },
                    { key: 'collected', width: 24 },
                    { key: 'pending', width: 26 },
                    { key: 'outstanding', width: 24 },
                    { key: 'overdue', width: 20 },
                    { key: 'aging', width: 20 }
                ];

                // ── Row 1: Top Empty Spacer ──
                sheet.mergeCells('A1:J1');
                const spacer1 = sheet.getCell('A1');
                spacer1.value = '';
                spacer1.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(1).height = 18;

                // ── Row 2: Main Title Banner (#2C3E50 Dark Slate Navy) ──
                sheet.mergeCells('A2:J2');
                const titleCell = sheet.getCell('A2');
                titleCell.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - CUSTOMER COLLECTION & DUES REPORT';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 30;
                sheet.getRow(2).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 3: Subtitle Banner (#007398 Ocean Teal Blue) ──
                sheet.mergeCells('A3:J3');
                const subTitleCell = sheet.getCell('A3');
                subTitleCell.value = 'Customer Contract Audit & Realized Recovery Register';
                subTitleCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                subTitleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                subTitleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 24;
                sheet.getRow(3).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 4: Section Banner (#006039 Deep Forest Green) ──
                sheet.mergeCells('A4:J4');
                const secCell = sheet.getCell('A4');
                secCell.value = 'TRANSACTION DETAILS';
                secCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                secCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                secCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(4).height = 24;
                sheet.getRow(4).eachCell({ includeEmpty: true }, c => c.border = slateBorder);

                // ── Row 5: Middle Empty Spacer ──
                sheet.mergeCells('A5:J5');
                const spacer2 = sheet.getCell('A5');
                spacer2.value = '';
                spacer2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                sheet.getRow(5).height = 14;

                // ── Row 6: Table Headers (Unified in #34495E Navy Slate Blue) ──
                const headers = [
                    '#',
                    'Customer Name',
                    'Phone Number',
                    'Property / Project',
                    'Agreement Value (₹)',
                    'Realized Collected (₹)',
                    'Pending Realization (₹)',
                    'Outstanding Dues (₹)',
                    'Overdue (₹)',
                    'Aging Risk'
                ];

                const hRow = sheet.getRow(6);
                hRow.values = headers;
                hRow.height = 30;
                hRow.eachCell({ includeEmpty: true }, (c) => {
                    c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } }; // Blue color across all headers
                    c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    c.alignment = { vertical: 'middle', horizontal: 'center' };
                    c.border = slateBorder;
                });

                let rIdx = 7;
                this.filteredRows.forEach((r, i) => {
                    const row = sheet.getRow(rIdx);
                    row.values = [
                        i + 1,
                        r.customer_name,
                        r.customer_phone || '—',
                        `${r.unit_display}${r.is_exchanged ? ' [Exchanged]' : ''} (${r.project_name})${r.rate_per_sqft > 0 ? ' @ ₹' + Number(r.rate_per_sqft).toLocaleString('en-IN') + '/sq.ft' : ''}`,
                        Number(r.agreement_value || 0),
                        Number(r.collected_amount || 0),
                        Number(r.pending_realization || 0),
                        Number(r.outstanding_amount || 0),
                        Number(r.overdue_amount || 0),
                        (r.aging_bucket_label || 'CURRENT').toUpperCase()
                    ];

                    row.height = 24;
                    // Alternating background: Row 1 is #F0F8FF (Ice Blue), Row 2 is #FFFFFF (White)
                    const rowBg = (i % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';

                    row.eachCell({ includeEmpty: true }, (c, col) => {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        c.border = dataBorder;
                        c.alignment = { vertical: 'middle', horizontal: 'center' };

                        if (col === 2) {
                            // Customer Name bold
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        } else if (col === 6) {
                            // Realized Collected: Bold Forest Green (#008000)
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF008000' } };
                        } else if (col === 10) {
                            // Aging Risk / Status: Bold Royal Blue (#1D4ED8)
                            c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1D4ED8' } };
                        } else {
                            c.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                        }

                        if (col >= 5 && col <= 9) {
                            c.numFmt = '#,##0.00';
                        }
                    });

                    rIdx++;
                });

                // ── Footer Row: Grand Total Summary (#2C3E50 Dark Slate Navy, 36pt Height, Bold White 12pt Text, Centered in Blank Space) ──
                const totRow = sheet.getRow(rIdx);
                totRow.height = 36;
                const kpis = this.summaryKPIs;

                // Explicitly set values on cells
                sheet.getCell(`A${rIdx}`).value = 'TOTAL COLLECTION & DUES SUMMARY';
                sheet.getCell(`B${rIdx}`).value = '';
                sheet.getCell(`C${rIdx}`).value = '';
                sheet.getCell(`D${rIdx}`).value = '';
                sheet.getCell(`E${rIdx}`).value = Number(kpis.total_agreement_value || 0);
                sheet.getCell(`F${rIdx}`).value = Number(kpis.total_collected || 0);
                sheet.getCell(`G${rIdx}`).value = Number(kpis.total_pending_realization || 0);
                sheet.getCell(`H${rIdx}`).value = Number(kpis.total_outstanding || 0);
                sheet.getCell(`I${rIdx}`).value = Number(kpis.total_overdue || 0);
                sheet.getCell(`J${rIdx}`).value = '';

                // Perform merge AFTER values are set so master-slave relationship is preserved
                sheet.mergeCells(`A${rIdx}:D${rIdx}`);

                // Style all cells from Col 1 to 10
                for (let c = 1; c <= 10; c++) {
                    const cell = sheet.getRow(rIdx).getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: false };
                    cell.border = slateBorder;
                    if (c >= 5 && c <= 9) {
                        cell.numFmt = '#,##0.00';
                    }
                }

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Customer_Collection_Dues_Report_${new Date().toISOString().slice(0, 10)}.xlsx`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Export error:', err);
                alert('Excel Export failed: ' + err.message);
            }
        }
    };
}
</script>

</x-erp-layout>
