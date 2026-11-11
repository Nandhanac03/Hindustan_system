<x-erp-layout title="Partner Capital & Shareholding Ledger Statement" headerTitle="Partner Capital & Shareholding Ledger Statement">

{{-- ExcelJS CDN Library for Client-side Excel Export --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
{{-- ApexCharts CDN Library --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
/* 🎨 Theme styles for ERP Dropdown & Filter Components */
.theme-btn {
    background: linear-gradient(135deg, #a38c29 0%, #8a7522 100%) !important;
    color: #ffffff !important;
    border: 1px solid #7c691c !important;
}
.theme-btn:hover {
    background: linear-gradient(135deg, #8a7522 0%, #736118 100%) !important;
    box-shadow: 0 4px 12px rgba(163, 140, 41, 0.3) !important;
}
.erp-search-input {
    height: 38px;
    border: 1px solid #cbd5e1;
    border-radius: 0.75rem;
    background-color: #f8fafc;
    color: #1e293b;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
    outline: none;
}
.erp-search-input:focus {
    background-color: #ffffff;
    border-color: #a38c29;
    box-shadow: 0 0 0 3px rgba(163, 140, 41, 0.15);
}
.erp-dropdown-trigger {
    width: 100%;
    height: 38px;
    padding: 0 0.75rem;
    border: 1px solid #cbd5e1 !important;
    background-color: #f8fafc;
    color: #1e293b;
    border-radius: 0.75rem;
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease-in-out;
    outline: none !important;
}
.erp-dropdown-trigger:hover {
    background-color: #ffffff;
    border-color: #a38c29 !important;
}
.erp-dropdown-trigger.active,
.erp-dropdown-trigger:focus {
    background-color: #ffffff !important;
    border-color: #a38c29 !important;
    box-shadow: 0 0 0 3px rgba(163, 140, 41, 0.15) !important;
}
.erp-dropdown-popover {
    position: absolute;
    left: 0;
    right: 0;
    z-index: 50;
    margin-top: 0.375rem;
    background-color: #ffffff;
    border: 2px solid rgba(163, 140, 41, 0.4) !important;
    border-radius: 0.75rem;
    box-shadow: 0 12px 36px -6px rgba(163, 140, 41, 0.25) !important;
    overflow: hidden;
    max-height: 16rem;
    display: flex;
    flex-direction: column;
}
.erp-dropdown-option {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.15s ease-in-out;
}
.erp-dropdown-option:hover {
    background-color: rgba(163, 140, 41, 0.1) !important;
    color: #7c691c !important;
}
.erp-dropdown-option.selected {
    background-color: rgba(163, 140, 41, 0.15) !important;
    border-left: 4px solid #a38c29 !important;
    color: #7c691c !important;
    font-weight: 800;
}
.erp-dropdown-option.selected-all {
    background-color: #a38c29 !important;
    color: #ffffff !important;
    font-weight: 800;
}

/* Global Table Header Theme Style */
.global-table-header th {
    background-color: var(--erp-table-header-bg, #534E47) !important;
    color: #ffffff !important;
    font-size: 0.7rem !important;
    font-weight: 800 !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase !important;
}

@media print {
    @page {
        size: A4 landscape;
        margin: 8mm;
    }
    html, body {
        background: #ffffff !important;
        font-size: 13.5px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .lg\:pl-44 {
        padding-left: 0 !important;
    }
    main {
        padding: 0 !important;
    }
    .print\:hidden, header, nav, aside, footer, button, .no-print {
        display: none !important;
    }
    .shadow-sm, .shadow-xs, .shadow-md {
        box-shadow: none !important;
    }
    .overflow-x-auto {
        overflow: visible !important;
    }
    .print-banner-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 12px !important;
    }
    #dashboard-stats {
        display: grid !important;
        grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
        gap: 8px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    #dashboard-stats > div {
        padding: 8px 10px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    #dashboard-stats .print-kpi-val {
        font-size: 10.5pt !important;
        line-height: 1.25 !important;
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: nowrap !important;
    }
    #dashboard-stats .print-kpi-label,
    #dashboard-stats .print-kpi-sub {
        font-size: 6.8pt !important;
        line-height: 1.2 !important;
    }
    .print-3-col-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 10px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    .print-3-col-grid > div {
        grid-column: span 1 / span 1 !important;
        padding: 12px !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
    table {
        page-break-inside: auto;
        width: 100% !important;
    }
    table th {
        font-size: 8.5pt !important;
        padding: 8px 8px !important;
    }
    table tbody td {
        font-size: 9.5pt !important;
        padding: 8px 8px !important;
        line-height: 1.35 !important;
    }
    table tfoot td {
        font-size: 10pt !important;
        padding: 9px 8px !important;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>

<div class="max-w-[1850px] mx-auto space-y-5" 
     x-data="capitalStatementsApp()">

    {{-- ── 1. EXECUTIVE TOP HEADER BANNER (OUR THEME TOP BOXES UI) ── --}}
    <div class="bg-gradient-to-r from-[#fdfaf3] via-[#f8f3e6] to-white rounded-2xl border border-[#a38c29]/30 p-5 sm:p-6 shadow-sm relative overflow-hidden text-slate-900">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-[#a38c29]/12 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-full bg-gradient-to-l from-[#a38c29]/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 print-banner-row">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center shadow-md shadow-[#a38c29]/20 shrink-0 border border-amber-300/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-lg sm:text-xl font-black tracking-tight uppercase text-slate-900">PARTNER CAPITAL &amp; SHAREHOLDING LEDGER STATEMENT</h1>
                        <!-- <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">EQUITY &amp; CAPITAL</span> -->
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                        <span>Accounting</span>
                        <span>&rsaquo;</span>
                        <span>Reports</span>
                        <span>&rsaquo;</span>
                        <span class="text-[#8a7522] font-semibold">Partner Capital &amp; Shareholding Ledger</span>
                    </div>
                </div>
            </div>

            {{-- Quick Metadata Badges (Top Right Boxes) --}}
            <div class="flex items-center gap-3 text-right shrink-0">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Financial Year</span>
                    <span class="text-xs font-mono font-black text-slate-900" x-text="getSelectedFyName()">All FY</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Statement Period</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="getPeriodLabel()">All Dates</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 6 FINANCIAL KPI METRIC CARDS (OUR THEME CARD UI) ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        
        {{-- Card 1: TOTAL PARTNERS (Blue Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL PARTNERS</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-2xl font-mono font-black text-slate-900 tracking-tight truncate" x-text="summaryKPIs.total_partners">0</div>
                <div class="print-kpi-sub text-[10px] text-blue-600 font-bold mt-1">Active Equity Partners</div>
            </div>
        </div>

        {{-- Card 2: TOTAL OPENING CAPITAL (Gold Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL OPENING CAPITAL</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-xl font-mono font-black text-[#a38c29] tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_opening)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_opening)">
                </div>
                <div class="print-kpi-sub text-[10px] text-[#8a7522] font-bold mt-1">Brought Forward Reserves</div>
            </div>
        </div>

        {{-- Card 3: TOTAL CAPITAL INFUSIONS (Emerald Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL CAPITAL INFUSIONS</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-xl font-mono font-black text-emerald-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_infusions)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_infusions)">
                </div>
                <div class="print-kpi-sub text-[10px] text-emerald-600 font-bold mt-1">Capital Contributed</div>
            </div>
        </div>

        {{-- Card 4: TOTAL DRAWINGS (Amber/Coral Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-amber-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL DRAWINGS</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-xl font-mono font-black text-amber-800 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_drawings)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_drawings)">
                </div>
                <div class="print-kpi-sub text-[10px] text-amber-600 font-bold mt-1">Payouts &amp; Withdrawals</div>
            </div>
        </div>

        {{-- Card 5: TOTAL PROFIT ALLOCATION (Orange Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-orange-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL PROFIT ALLOCATION</span>
                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-xl font-mono font-black text-orange-800 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_profit)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_profit)">
                </div>
                <div class="print-kpi-sub text-[10px] text-orange-600 font-bold mt-1">Agreed Share Allocation</div>
            </div>
        </div>

        {{-- Card 6: TOTAL CLOSING CAPITAL (Rose Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="print-kpi-label text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL CLOSING CAPITAL</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="print-kpi-val text-xl font-mono font-black text-rose-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_closing)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_closing)">
                </div>
                <div class="print-kpi-sub text-[10px] text-rose-600 font-bold mt-1">Net Partners Equity</div>
            </div>
        </div>

    </div>

    {{-- ── 3. ULTRA-CLEAN THEME FILTER BAR (ZERO RELOAD) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all print:hidden">
        <div class="flex items-center gap-2.5 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Search Partner Name / ID --}}
            <div class="relative min-w-[180px] flex-[1.3]" style="flex: 1.3 1 0%;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       x-model="filters.search"
                       @input="currentPage = 1"
                       placeholder="Search partner name or ID..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="filters.search">
                    <button type="button" @click="filters.search = ''; currentPage = 1" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
                </template>
            </div>

            {{-- 2. All Partners Dropdown --}}
            <div class="relative min-w-[155px] flex-1" style="flex: 1 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''" @click.outside="open = false">
                <button type="button" @click="open = !open"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="truncate text-xs font-bold"
                              :class="filters.partner_id !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="getSelectedPartnerName()">All Partners</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[200px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectPartnerFilter('all'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.partner_id === 'all' ? 'selected-all' : ''">
                            <span>All Partners</span>
                        </div>
                        <template x-for="p in partnersData" :key="p.id">
                            <div @click="selectPartnerFilter(p.id); open = false"
                                 class="erp-dropdown-option flex items-center justify-between"
                                 :class="String(filters.partner_id) === String(p.id) ? 'selected' : ''">
                                <span x-text="p.name"></span>
                                <span class="text-[10px] font-mono text-slate-400 ml-2" x-text="p.partner_code"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 3. Financial Year (FY) Dropdown --}}
            <div class="relative min-w-[130px] flex-[0.9]" style="flex: 0.9 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''" @click.outside="open = false">
                <button type="button" @click="open = !open"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate text-xs font-bold"
                              :class="(filters.financial_year && filters.financial_year !== 'All') ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="getSelectedFyName()">All FY</span>
                    </div>
                    <div class="flex items-center gap-1 shrink-0 ml-1">
                        <template x-if="filters.financial_year && filters.financial_year !== 'All'">
                            <span @click.stop="selectFinancialYear('All'); open = false" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear Financial Year">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </template>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="erp-dropdown-popover min-w-[205px] w-max"
                     style="display: none;">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-60">
                        <div @click="selectFinancialYear('All'); open = false"
                             class="erp-dropdown-option"
                             :class="(!filters.financial_year || filters.financial_year === 'All') ? 'selected-all' : ''">
                            <span>All Financial Years</span>
                        </div>
                        <template x-for="fy in financialYearsList" :key="fy.value">
                            <div @click="selectFinancialYear(fy.value); open = false"
                                 class="erp-dropdown-option flex items-center justify-between"
                                 :class="filters.financial_year === fy.value ? 'selected' : ''">
                                <span x-text="fy.label"></span>
                                <span x-show="fy.range" class="text-[10px] text-slate-400 font-mono ml-2" x-text="fy.range"></span>
                            </div>
                        </template>
                        <div @click="selectFinancialYear('Custom'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.financial_year === 'Custom' ? 'selected' : ''">
                            <span>Custom Date Range</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. From Date --}}
            <div class="relative min-w-[130px] flex-[0.8]" style="flex: 0.8 1 0%;">
                <input type="date"
                       id="capitalFromDate"
                       x-model="filters.from_date"
                       @change="onCustomDateChange()"
                       class="w-full erp-search-input px-2.5 text-xs font-bold text-slate-800">
            </div>

            {{-- 5. To Date --}}
            <div class="relative min-w-[130px] flex-[0.8]" style="flex: 0.8 1 0%;">
                <input type="date"
                       id="capitalToDate"
                       x-model="filters.to_date"
                       @change="onCustomDateChange()"
                       class="w-full erp-search-input px-2.5 text-xs font-bold text-slate-800">
            </div>

            {{-- 6. Reset Filters Action --}}
            <button type="button" 
                    @click="resetFilters()"
                    class="theme-btn h-[38px] px-4 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap"
                    title="Reset All Filters">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>RESET FILTERS</span>
            </button>

        </div>
    </div>

    {{-- ── 4. PARTNER CAPITAL & SHAREHOLDING SUMMARY TABLE ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    <span>Partner Capital &amp; Shareholding Summary</span>
                    <span class="text-[10px] font-black text-[#a38c29] bg-[#a38c29]/10 px-2 py-0.5 rounded-full uppercase tracking-wider" x-text="summaryRows.length + ' Active Partners'"></span>
                </h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5 pl-3">
                    Partner-wise capital contributions, drawings, profit share allocations &amp; net closing equity
                </p>
            </div>

            {{-- Export Buttons (Export Excel & Export PDF) --}}
            <div class="flex items-center gap-2.5 print:hidden">
                <button type="button" @click="exportToExcel()" 
                        class="h-[38px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" @click="window.print()" 
                        class="h-[38px] px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="global-table-header">
                    <tr>
                        <th class="py-3 px-3 text-center w-10">#</th>
                        <th class="py-3 px-3.5">Partner ID</th>
                        <th class="py-3 px-4">Partner Name</th>
                        <th class="py-3 px-3 text-right">Shareholding (%)</th>
                        <th class="py-3 px-3.5 text-right" x-text="'Opening Capital (' + currency + ')'">Opening Capital (₹)</th>
                        <th class="py-3 px-3.5 text-right" x-text="'Capital Infusions (' + currency + ')'">Capital Infusions (₹)</th>
                        <th class="py-3 px-3.5 text-right" x-text="'Drawings (' + currency + ')'">Drawings (₹)</th>
                        <th class="py-3 px-3.5 text-right" x-text="'Profit / (Loss) Allocation (' + currency + ')'">Profit / (Loss) Allocation (₹)</th>
                        <th class="py-3 px-4 text-right" x-text="'Closing Capital (' + currency + ')'">Closing Capital (₹)</th>
                        <th class="py-3 px-3 text-center print:hidden">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <template x-for="(row, idx) in summaryRows" :key="row.id">
                        <tr class="hover:bg-amber-50/40 transition-colors cursor-pointer"
                            :class="String(activeDetailPartnerId) === String(row.id) ? 'bg-amber-50/50' : ''"
                            @click="selectDetailPartner(row.id)">
                            <td class="py-3 px-3 text-center font-mono text-slate-400" x-text="idx + 1"></td>
                            <td class="py-3 px-3.5">
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 text-[11px]" x-text="row.partner_code"></span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900" x-text="row.name"></div>
                                <div class="text-[10px] text-slate-400 font-medium" x-text="row.role"></div>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-800" x-text="row.share_pct.toFixed(2) + '%'"></td>
                            <td class="py-3 px-3.5 text-right font-mono font-semibold text-slate-700" x-text="formatMoney(row.opening_capital)"></td>
                            <td class="py-3 px-3.5 text-right font-mono font-bold text-emerald-700" x-text="formatMoney(row.capital_infusions)"></td>
                            <td class="py-3 px-3.5 text-right font-mono font-bold text-rose-600" x-text="formatMoney(row.drawings)"></td>
                            <td class="py-3 px-3.5 text-right font-mono font-bold"
                                :class="row.profit_allocation >= 0 ? 'text-emerald-700' : 'text-rose-600'"
                                x-text="formatMoney(row.profit_allocation)"></td>
                            <td class="py-3 px-4 text-right font-mono font-black text-slate-900"
                                :class="row.closing_capital >= 0 ? 'text-[#8a7522]' : 'text-rose-700'"
                                x-text="formatMoney(row.closing_capital)"></td>
                            <td class="py-3 px-3 text-center print:hidden" @click.stop>
                                <button type="button"
                                        @click="selectDetailPartner(row.id)"
                                        :class="String(activeDetailPartnerId) === String(row.id) ? 'bg-[#a38c29] text-white border-[#a38c29]' : 'bg-white text-slate-600 border-slate-200 hover:border-[#a38c29] hover:text-[#a38c29]'"
                                        class="w-7 h-7 rounded-lg border inline-flex items-center justify-center transition-all shadow-2xs cursor-pointer"
                                        title="View Partner Ledger Details">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="summaryRows.length === 0">
                        <td colspan="10" class="py-8 text-center text-slate-400 italic text-xs">No partners match the selected filter criteria.</td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-100/90 font-black border-t-2 border-slate-300" x-show="summaryRows.length > 0">
                    <tr class="text-slate-900 text-xs">
                        <td colspan="3" class="py-3 px-4 uppercase tracking-wider font-black text-slate-900">
                            <span class="text-[#8a7522]">Total</span> (<span x-text="summaryRows.length"></span> Partners)
                        </td>
                        <td class="py-3 px-3 text-right font-mono font-black text-slate-900" x-text="summaryKPIs.total_share_pct.toFixed(2) + '%'"></td>
                        <td class="py-3 px-3.5 text-right font-mono font-black text-slate-900" x-text="formatMoney(summaryKPIs.total_opening)"></td>
                        <td class="py-3 px-3.5 text-right font-mono font-black text-emerald-700" x-text="formatMoney(summaryKPIs.total_infusions)"></td>
                        <td class="py-3 px-3.5 text-right font-mono font-black text-rose-700" x-text="formatMoney(summaryKPIs.total_drawings)"></td>
                        <td class="py-3 px-3.5 text-right font-mono font-black text-emerald-700" x-text="formatMoney(summaryKPIs.total_profit)"></td>
                        <td class="py-3 px-4 text-right font-mono font-black text-[#8a7522] text-sm" x-text="formatMoney(summaryKPIs.total_closing)"></td>
                        <td class="print:hidden"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ── 5. PARTNER LEDGER DETAILS SECTION (3-CARD BREAKDOWN + TRANSACTION LEDGER) ── --}}
    <div id="partner-ledger-details" class="space-y-5" x-show="activePartnerDetail">
        
        {{-- Section Subheader Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 px-5 py-3.5 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                <span>Partner Ledger Details &mdash;</span>
                <span class="text-[#8a7522]" x-text="activePartnerDetail ? (activePartnerDetail.name + ' (' + activePartnerDetail.partner_code + ')') : ''"></span>
            </h2>

            {{-- Partner Quick Switch Pills --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                <template x-for="p in partnersData" :key="'pill-' + p.id">
                    <button type="button"
                            @click="selectDetailPartner(p.id)"
                            :class="String(activeDetailPartnerId) === String(p.id) ? 'bg-[#a38c29] text-white font-black shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold'"
                            class="px-3 py-1 rounded-xl text-xs transition-all cursor-pointer"
                            x-text="p.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- 3-Card Breakdown: Partner Info | Capital Summary | Shareholding Distribution Donut --}}
        <div class="print-3-col-grid grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            {{-- Card 1: Partner Information (4 cols) --}}
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 border-l-[6px] border-l-blue-500 p-5 shadow-2xs flex flex-col justify-between">
                <div class="border-b border-slate-100 pb-2.5 mb-3 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Partner Information</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200" x-text="activePartnerDetail?.account_code"></span>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500 font-semibold">Partner ID</span>
                        <span class="font-mono font-bold text-slate-900" x-text="activePartnerDetail?.partner_code"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500 font-semibold">Partner Name</span>
                        <span class="font-bold text-slate-900" x-text="activePartnerDetail?.name"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500 font-semibold">Shareholding</span>
                        <span class="font-mono font-black text-[#8a7522]" x-text="(activePartnerDetail?.share_pct || 0).toFixed(2) + '%'"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500 font-semibold">PAN / Tax ID</span>
                        <span class="font-mono font-semibold text-slate-700" x-text="activePartnerDetail?.pan || '—'"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500 font-semibold">Contact</span>
                        <span class="font-mono font-semibold text-slate-700" x-text="activePartnerDetail?.phone || '—'"></span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-500 font-semibold">Email</span>
                        <span class="font-semibold text-slate-700" x-text="activePartnerDetail?.email || '—'"></span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Capital Summary (4 cols) --}}
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 border-l-[6px] border-l-[#a38c29] p-5 shadow-2xs flex flex-col justify-between">
                <div class="border-b border-slate-100 pb-2.5 mb-3 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider" x-text="'Capital Summary (' + currency + ')'">Capital Summary (₹)</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-[#8a7522] border border-amber-200" x-text="getSelectedFyName()"></span>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-semibold">Opening Capital</span>
                        <span class="font-mono font-bold text-slate-800" x-text="formatMoney(activePartnerDetail?.opening_capital)"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-semibold">Capital Infusions (+)</span>
                        <span class="font-mono font-bold text-emerald-700" x-text="formatMoney(activePartnerDetail?.capital_infusions)"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-semibold">Drawings (-)</span>
                        <span class="font-mono font-bold text-rose-600" x-text="formatMoney(activePartnerDetail?.drawings)"></span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-semibold">Profit / Loss Allocation (+)</span>
                        <span class="font-mono font-bold"
                              :class="(activePartnerDetail?.profit_allocation || 0) >= 0 ? 'text-emerald-700' : 'text-rose-600'"
                              x-text="formatMoney(activePartnerDetail?.profit_allocation)"></span>
                    </div>
                    <div class="flex items-center justify-between pt-2.5 mt-1 border-t-2 border-slate-200 bg-[#fdfaf3] px-3 py-2 rounded-xl">
                        <span class="font-black text-slate-900 uppercase tracking-wider text-xs">Closing Capital</span>
                        <span class="font-mono font-black text-base text-[#8a7522]" x-text="currency + ' ' + formatMoney(activePartnerDetail?.closing_capital)"></span>
                    </div>
                </div>
            </div>

            {{-- Card 3: Shareholding Distribution Donut Chart (4 cols) --}}
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 border-l-[6px] border-l-emerald-500 p-5 shadow-2xs flex flex-col justify-between">
                <div class="border-b border-slate-100 pb-2.5 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Shareholding Distribution</h3>
                    <span class="text-[10px] font-bold text-emerald-700 uppercase">Agreed Ratio</span>
                </div>
                <div class="grid grid-cols-12 items-center gap-2 my-auto pt-2">
                    <div class="col-span-6 flex items-center justify-center">
                        <div id="shareholdingDonutChart" class="w-full max-w-[180px] min-h-[160px]"></div>
                    </div>
                    <div class="col-span-6 space-y-2.5">
                        <template x-for="(p, idx) in summaryRows" :key="'leg-' + p.id">
                            <div class="flex items-center justify-between text-xs p-1.5 rounded-lg hover:bg-slate-50 transition cursor-pointer"
                                 @click="selectDetailPartner(p.id)">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-xs shrink-0" :style="'background-color: ' + getPartnerColor(idx)"></span>
                                    <span class="font-bold text-slate-700 truncate" x-text="p.name"></span>
                                </div>
                                <span class="font-mono font-black text-slate-900 shrink-0 ml-1" x-text="p.share_pct.toFixed(1) + '%'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── 6. TRANSACTION LEDGER TABLE FOR SELECTED PARTNER ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-white">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        <span>Transaction Ledger &mdash;</span>
                        <span class="text-[#8a7522]" x-text="activePartnerDetail?.name"></span>
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5 pl-3">
                        Chronological capital movements, infusions, drawings &amp; profit allocations with running capital balance
                    </p>
                </div>

                {{-- Entry Type Filter Tabs --}}
                <div class="flex items-center gap-1.5 flex-wrap">
                    <template x-for="tab in ['All', 'Opening', 'Infusion', 'Drawing', 'Profit']" :key="tab">
                        <button type="button"
                                @click="activeEntryTypeTab = tab; currentPage = 1"
                                :class="activeEntryTypeTab === tab ? 'bg-[#a38c29] text-white font-black shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold'"
                                class="px-3 py-1 rounded-xl text-xs transition-all cursor-pointer"
                                x-text="tab">
                        </button>
                    </template>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="global-table-header">
                        <tr>
                            <th class="py-3 px-3 text-center w-10">#</th>
                            <th class="py-3 px-3.5">Date</th>
                            <th class="py-3 px-3.5">Voucher No.</th>
                            <th class="py-3 px-4">Particulars</th>
                            <th class="py-3 px-3.5">Reference</th>
                            <th class="py-3 px-3 text-center">Type</th>
                            <th class="py-3 px-3.5 text-right" x-text="'Credit (+) (' + currency + ')'">Credit (+) (₹)</th>
                            <th class="py-3 px-3.5 text-right" x-text="'Debit (-) (' + currency + ')'">Debit (-) (₹)</th>
                            <th class="py-3 px-4 text-right" x-text="'Balance (' + currency + ')'">Balance (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <template x-for="(tx, idx) in paginatedLedger" :key="idx">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400" x-text="(currentPage - 1) * perPage + idx + 1"></td>
                                <td class="py-2.5 px-3.5 font-mono font-bold text-slate-800" x-text="tx.date"></td>
                                <td class="py-2.5 px-3.5 font-mono font-bold text-[#8a7522]" x-text="tx.voucher_no"></td>
                                <td class="py-2.5 px-4 font-semibold text-slate-800" x-text="tx.particulars"></td>
                                <td class="py-2.5 px-3.5 text-slate-500 text-xs" x-text="tx.reference"></td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border inline-block"
                                          :class="getTypeBadgeClass(tx.entry_type)"
                                          x-text="tx.entry_type">
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-bold"
                                    :class="tx.credit > 0 ? 'text-emerald-700' : 'text-slate-400'"
                                    x-text="formatMoney(tx.credit)"></td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-bold"
                                    :class="tx.debit > 0 ? 'text-rose-600' : 'text-slate-400'"
                                    x-text="formatMoney(tx.debit)"></td>
                                <td class="py-2.5 px-4 text-right font-mono font-black text-slate-900"
                                    x-text="formatMoney(tx.running_balance)"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredPartnerLedger.length === 0">
                            <td colspan="9" class="py-8 text-center text-slate-400 italic text-xs">No capital ledger entries found for the selected period / filter.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer (Matching exact ERP pagination UI, 50 per page) --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-2xl print:hidden" x-show="filteredPartnerLedger.length > 0">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900" x-text="(currentPage - 1) * perPage + 1"></span> TO 
                    <span class="text-slate-900" x-text="Math.min(currentPage * perPage, filteredPartnerLedger.length)"></span> OF 
                    <span class="text-slate-900" x-text="filteredPartnerLedger.length"></span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage <= 1"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                        PREV
                    </button>
                    <template x-for="(p, pIdx) in getPageNumbers()" :key="pIdx">
                        <span class="inline-flex items-center gap-1">
                            <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                            <button type="button" x-show="p !== '...'"
                                    @click="currentPage = p"
                                    x-text="p"
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs cursor-pointer"
                                    :class="currentPage === p ? 'bg-[#a38c29] text-white border border-[#a38c29]' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"></button>
                        </span>
                    </template>
                    <button type="button" @click="if(currentPage < totalPages) currentPage++" 
                            :disabled="currentPage >= totalPages"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
function capitalStatementsApp() {
    return {
        currency: @json($currency ?? '₹'),
        projectName: @json(strtoupper($projectName ?? 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD')),
        partnersData: @json($partnersData ?? []),
        rawLedgerEvents: @json($ledgerEvents ?? []),
        rawProfitEvents: @json($profitEvents ?? []),
        filters: {
            search: '',
            partner_id: 'all',
            financial_year: 'All',
            from_date: '',
            to_date: ''
        },
        financialYearsList: [
            { value: '2026-27', label: 'FY 2026-27', from: '2026-04-01', to: '2027-03-31', range: 'Apr 26 - Mar 27' },
            { value: '2025-26', label: 'FY 2025-26', from: '2025-04-01', to: '2026-03-31', range: 'Apr 25 - Mar 26' },
            { value: '2024-25', label: 'FY 2024-25', from: '2024-04-01', to: '2025-03-31', range: 'Apr 24 - Mar 25' },
            { value: '2023-24', label: 'FY 2023-24', from: '2023-04-01', to: '2024-03-31', range: 'Apr 23 - Mar 24' },
            { value: '2022-23', label: 'FY 2022-23', from: '2022-04-01', to: '2023-03-31', range: 'Apr 22 - Mar 23' },
            { value: '2021-22', label: 'FY 2021-22', from: '2021-04-01', to: '2022-03-31', range: 'Apr 21 - Mar 22' },
            { value: '2020-21', label: 'FY 2020-21', from: '2020-04-01', to: '2021-03-31', range: 'Apr 20 - Mar 21' },
            { value: '2019-20', label: 'FY 2019-20', from: '2019-04-01', to: '2020-03-31', range: 'Apr 19 - Mar 20' },
            { value: '2018-19', label: 'FY 2018-19', from: '2018-04-01', to: '2019-03-31', range: 'Apr 18 - Mar 19' },
            { value: '2017-18', label: 'FY 2017-18', from: '2017-04-01', to: '2018-03-31', range: 'Apr 17 - Mar 18' },
            { value: '2016-17', label: 'FY 2016-17', from: '2016-04-01', to: '2017-03-31', range: 'Apr 16 - Mar 17' },
            { value: '2015-16', label: 'FY 2015-16', from: '2015-04-01', to: '2016-03-31', range: 'Apr 15 - Mar 16' },
            { value: '2014-15', label: 'FY 2014-15', from: '2014-04-01', to: '2015-03-31', range: 'Apr 14 - Mar 15' }
        ],
        activeDetailPartnerId: null,
        activeEntryTypeTab: 'All',
        currentPage: 1,
        perPage: 50,
        donutChartInstance: null,
        partnerColors: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#14B8A6'],

        init() {
            if (this.partnersData.length > 0) {
                this.activeDetailPartnerId = this.partnersData[0].id;
            }
            this.$nextTick(() => {
                this.renderDonutChart();
            });
        },

        getPartnerColor(idx) {
            return this.partnerColors[idx % this.partnerColors.length];
        },

        getSelectedPartnerName() {
            if (this.filters.partner_id === 'all') return 'All Partners';
            const p = this.partnersData.find(x => String(x.id) === String(this.filters.partner_id));
            return p ? p.name : 'All Partners';
        },

        getSelectedFyName() {
            if (!this.filters.financial_year || this.filters.financial_year === 'All') return 'All FY';
            if (this.filters.financial_year === 'Custom') return 'Custom Date';
            const found = this.financialYearsList.find(x => x.value === this.filters.financial_year);
            return found ? found.label : ('FY ' + this.filters.financial_year);
        },

        getPeriodLabel() {
            if (this.filters.from_date && this.filters.to_date) {
                return this.formatDisplayDate(this.filters.from_date) + ' - ' + this.formatDisplayDate(this.filters.to_date);
            }
            if (this.filters.from_date) return 'From ' + this.formatDisplayDate(this.filters.from_date);
            if (this.filters.to_date) return 'Up to ' + this.formatDisplayDate(this.filters.to_date);
            return 'All Dates';
        },

        formatDisplayDate(iso) {
            if (!iso) return '';
            const parts = iso.split('-');
            if (parts.length !== 3) return iso;
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        },

        selectPartnerFilter(id) {
            this.filters.partner_id = String(id);
            if (id !== 'all') {
                this.activeDetailPartnerId = id;
            } else if (this.summaryRows.length > 0) {
                this.activeDetailPartnerId = this.summaryRows[0].id;
            }
            this.currentPage = 1;
            this.$nextTick(() => this.updateDonutChart());
        },

        selectFinancialYear(fyVal) {
            this.filters.financial_year = fyVal;
            if (fyVal === 'All') {
                this.filters.from_date = '';
                this.filters.to_date = '';
            } else if (fyVal === 'Custom') {
                const el = document.getElementById('capitalFromDate');
                if (el) el.focus();
            } else {
                const found = this.financialYearsList.find(x => x.value === fyVal);
                if (found) {
                    this.filters.from_date = found.from;
                    this.filters.to_date = found.to;
                }
            }
            this.currentPage = 1;
            this.$nextTick(() => this.updateDonutChart());
        },

        onCustomDateChange() {
            if (this.filters.from_date || this.filters.to_date) {
                const matched = this.financialYearsList.find(
                    x => x.from === this.filters.from_date && x.to === this.filters.to_date
                );
                this.filters.financial_year = matched ? matched.value : 'Custom';
            } else {
                this.filters.financial_year = 'All';
            }
            this.currentPage = 1;
            this.$nextTick(() => this.updateDonutChart());
        },

        selectDetailPartner(partnerId) {
            this.activeDetailPartnerId = partnerId;
            this.currentPage = 1;
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.partner_id = 'all';
            this.filters.financial_year = 'All';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.activeEntryTypeTab = 'All';
            this.currentPage = 1;
            if (this.partnersData.length > 0) {
                this.activeDetailPartnerId = this.partnersData[0].id;
            }
            this.$nextTick(() => this.updateDonutChart());
        },

        get summaryRows() {
            const fromD = this.filters.from_date || '';
            const toD = this.filters.to_date || '';
            const q = (this.filters.search || '').toLowerCase().trim();

            // Profit pools before from_date & inside [from_date, to_date]
            let profitPrior = 0;
            let profitPeriod = 0;
            this.rawProfitEvents.forEach(pe => {
                if (fromD && pe.date_raw < fromD) {
                    profitPrior += Number(pe.amount || 0);
                } else if ((!fromD || pe.date_raw >= fromD) && (!toD || pe.date_raw <= toD)) {
                    profitPeriod += Number(pe.amount || 0);
                }
            });

            return this.partnersData.filter(p => {
                if (this.filters.partner_id !== 'all' && String(p.id) !== String(this.filters.partner_id)) {
                    return false;
                }
                if (q) {
                    const matchName = (p.name || '').toLowerCase().includes(q);
                    const matchCode = (p.partner_code || '').toLowerCase().includes(q) || (p.account_code || '').toLowerCase().includes(q);
                    if (!matchName && !matchCode) return false;
                }
                return true;
            }).map(p => {
                const shareRatio = (Number(p.share_pct) || 0) / 100.0;
                const priorProfitShare = profitPrior * shareRatio;
                const periodProfitShare = profitPeriod * shareRatio;

                let priorNetCap = 0;
                let infusions = 0;
                let drawings = 0;

                this.rawLedgerEvents.forEach(ev => {
                    if (Number(ev.partner_id) !== Number(p.id)) return;
                    const cr = Number(ev.credit || 0);
                    const dr = Number(ev.debit || 0);

                    if (fromD && ev.date_raw < fromD) {
                        priorNetCap += (cr - dr);
                    } else if ((!fromD || ev.date_raw >= fromD) && (!toD || ev.date_raw <= toD)) {
                        if (ev.entry_type === 'Infusion') infusions += cr;
                        else if (ev.entry_type === 'Drawing') drawings += dr;
                    }
                });

                const openingCapital = fromD ? (priorNetCap + priorProfitShare) : 0;
                const closingCapital = openingCapital + infusions - drawings + periodProfitShare;

                return {
                    ...p,
                    opening_capital: openingCapital,
                    capital_infusions: infusions,
                    drawings: drawings,
                    profit_allocation: periodProfitShare,
                    closing_capital: closingCapital
                };
            });
        },

        get summaryKPIs() {
            const rows = this.summaryRows;
            return {
                total_partners: rows.length,
                total_share_pct: rows.reduce((a, r) => a + Number(r.share_pct || 0), 0),
                total_opening: rows.reduce((a, r) => a + Number(r.opening_capital || 0), 0),
                total_infusions: rows.reduce((a, r) => a + Number(r.capital_infusions || 0), 0),
                total_drawings: rows.reduce((a, r) => a + Number(r.drawings || 0), 0),
                total_profit: rows.reduce((a, r) => a + Number(r.profit_allocation || 0), 0),
                total_closing: rows.reduce((a, r) => a + Number(r.closing_capital || 0), 0)
            };
        },

        get activePartnerDetail() {
            const rows = this.summaryRows;
            if (rows.length === 0) return null;
            const found = rows.find(r => String(r.id) === String(this.activeDetailPartnerId));
            return found || rows[0];
        },

        get partnerFullLedger() {
            const partner = this.activePartnerDetail;
            if (!partner) return [];

            const fromD = this.filters.from_date || '';
            const toD = this.filters.to_date || '';
            const entries = [];

            // 1. Opening Balance Row if prior period balance exists or date filter set
            if (fromD || partner.opening_capital !== 0) {
                const openBal = Number(partner.opening_capital || 0);
                entries.push({
                    date_raw: fromD || '0000-00-00',
                    date: fromD ? this.formatDisplayDate(fromD) : 'Opening',
                    voucher_no: 'OPN-BAL',
                    particulars: 'Opening Balance - Partner Capital B/F',
                    reference: 'Brought Forward',
                    entry_type: 'Opening',
                    credit: openBal >= 0 ? openBal : 0,
                    debit: openBal < 0 ? Math.abs(openBal) : 0
                });
            }

            // 2. Period Ledger Events (Infusions & Drawings)
            this.rawLedgerEvents.forEach(ev => {
                if (Number(ev.partner_id) !== Number(partner.id)) return;
                if (fromD && ev.date_raw < fromD) return;
                if (toD && ev.date_raw > toD) return;
                entries.push({ ...ev });
            });

            // 3. Profit / Loss Allocation Entry at period end
            const profitAlloc = Number(partner.profit_allocation || 0);
            if (Math.abs(profitAlloc) > 0.005) {
                const allocDateRaw = toD || new Date().toISOString().slice(0, 10);
                entries.push({
                    date_raw: allocDateRaw,
                    date: this.formatDisplayDate(allocDateRaw),
                    voucher_no: 'JV-PROFIT-ALLOC',
                    particulars: `Profit Allocation (${partner.share_pct.toFixed(2)}% Share) - ${this.getSelectedFyName()}`,
                    reference: 'Realized P&L Allocation',
                    entry_type: 'Profit',
                    credit: profitAlloc >= 0 ? profitAlloc : 0,
                    debit: profitAlloc < 0 ? Math.abs(profitAlloc) : 0
                });
            }

            // Sort chronologically while keeping Opening row first
            entries.sort((a, b) => {
                if (a.entry_type === 'Opening' && b.entry_type !== 'Opening') return -1;
                if (b.entry_type === 'Opening' && a.entry_type !== 'Opening') return 1;
                return (a.date_raw || '').localeCompare(b.date_raw || '');
            });

            // Compute running capital balance
            let runningBal = 0;
            return entries.map(e => {
                runningBal += (Number(e.credit || 0) - Number(e.debit || 0));
                return {
                    ...e,
                    running_balance: runningBal
                };
            });
        },

        get filteredPartnerLedger() {
            let list = this.partnerFullLedger;
            if (this.activeEntryTypeTab !== 'All') {
                list = list.filter(item => item.entry_type === this.activeEntryTypeTab);
            }
            return list;
        },

        get totalPages() {
            return Math.ceil(this.filteredPartnerLedger.length / this.perPage) || 1;
        },

        get paginatedLedger() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredPartnerLedger.slice(start, start + this.perPage);
        },

        getPageNumbers() {
            const totalPages = this.totalPages;
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

        formatMoney(num) {
            const n = parseFloat(num) || 0;
            return n.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        formatCompact(num) {
            const n = Math.abs(parseFloat(num) || 0);
            if (n >= 10000000) return (n / 10000000).toFixed(2) + ' Cr';
            if (n >= 100000) return (n / 100000).toFixed(2) + ' L';
            if (n >= 1000) return (n / 1000).toFixed(1) + ' K';
            return n.toFixed(2);
        },

        getTypeBadgeClass(type) {
            switch(type) {
                case 'Opening':
                    return 'bg-blue-50 text-blue-700 border-blue-200';
                case 'Infusion':
                    return 'bg-emerald-50 text-emerald-700 border-emerald-200';
                case 'Drawing':
                    return 'bg-rose-50 text-rose-700 border-rose-200';
                case 'Profit':
                    return 'bg-amber-50 text-amber-800 border-amber-200';
                default:
                    return 'bg-slate-100 text-slate-700 border-slate-200';
            }
        },

        renderDonutChart() {
            const el = document.querySelector('#shareholdingDonutChart');
            if (!el) return;
            if (this.donutChartInstance) this.donutChartInstance.destroy();

            const rows = this.summaryRows;
            const series = rows.map(r => Math.max(0, Number(r.share_pct || 0)));
            const labels = rows.map(r => r.name);
            const colors = rows.map((_, i) => this.getPartnerColor(i));

            const totalCap = this.summaryKPIs.total_closing || 0;

            const options = {
                series: series.length ? series : [100],
                labels: labels.length ? labels : ['No Data'],
                colors: colors.length ? colors : ['#cbd5e1'],
                chart: {
                    type: 'donut',
                    height: 175,
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { show: false },
                                value: {
                                    show: true,
                                    fontSize: '12px',
                                    fontWeight: 900,
                                    color: '#0f172a',
                                    formatter: () => this.currency + ' ' + this.formatCompact(totalCap)
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total Capital',
                                    fontSize: '9px',
                                    fontWeight: 700,
                                    color: '#64748b',
                                    formatter: () => this.currency + ' ' + this.formatCompact(totalCap)
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                stroke: { width: 2, colors: ['#ffffff'] },
                tooltip: {
                    y: {
                        formatter: (val) => val.toFixed(2) + '%'
                    }
                }
            };

            this.donutChartInstance = new ApexCharts(el, options);
            this.donutChartInstance.render();
        },

        updateDonutChart() {
            if (!this.donutChartInstance) {
                this.renderDonutChart();
                return;
            }
            const rows = this.summaryRows;
            const totalCap = this.summaryKPIs.total_closing || 0;
            this.donutChartInstance.updateOptions({
                series: rows.length ? rows.map(r => Math.max(0, Number(r.share_pct || 0))) : [100],
                labels: rows.length ? rows.map(r => r.name) : ['No Data'],
                colors: rows.length ? rows.map((_, i) => this.getPartnerColor(i)) : ['#cbd5e1'],
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                value: {
                                    formatter: () => this.currency + ' ' + this.formatCompact(totalCap)
                                },
                                total: {
                                    formatter: () => this.currency + ' ' + this.formatCompact(totalCap)
                                }
                            }
                        }
                    }
                }
            });
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('Excel generation library is still loading. Please try again.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            workbook.creator = this.projectName;
            workbook.created = new Date();

            const whiteFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
            const titleFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
            const titleFont = { name: 'Calibri', size: 15, bold: true, color: { argb: 'FFFFFFFF' } };
            const subFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
            const subFont = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
            const secFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
            const secFont = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
            const headerFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
            const headerFont = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            const headerBorder = {
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
            const zebraEvenFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF0F8FF' } };
            const zebraOddFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
            const totalFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
            const totalFont = { name: 'Calibri', size: 15, bold: true, color: { argb: 'FFFFFFFF' } };
            const totalBorder = {
                top: { style: 'medium', color: { argb: 'FF1E293B' } },
                bottom: { style: 'medium', color: { argb: 'FF1E293B' } },
                left: { style: 'thin', color: { argb: 'FF475569' } },
                right: { style: 'thin', color: { argb: 'FF475569' } }
            };

            const timestamp = new Date().toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
            const metaText = `Financial Year: ${this.getSelectedFyName()}   |   Period: ${this.getPeriodLabel()}   |   Currency: ${this.currency}   |   Generated: ${timestamp}`;

            const addExecutiveBanner = (sheet, lastColLetter, totalCols, titleText, subtitleText, sectionText) => {
                // Row 1: Top Empty Spacer
                sheet.mergeCells(`A1:${lastColLetter}1`);
                const sp1 = sheet.getCell('A1');
                sp1.value = '';
                sp1.fill = whiteFill;
                sheet.getRow(1).height = 18;

                // Row 2: Main Title Banner (#2C3E50 Dark Slate Navy)
                sheet.mergeCells(`A2:${lastColLetter}2`);
                const tCell = sheet.getCell('A2');
                tCell.value = titleText;
                tCell.font = titleFont;
                tCell.fill = titleFill;
                tCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 32;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(2).getCell(c).border = headerBorder;

                // Row 3: Subtitle Banner (#007398 Ocean Teal Blue)
                sheet.mergeCells(`A3:${lastColLetter}3`);
                const sCell = sheet.getCell('A3');
                sCell.value = subtitleText;
                sCell.font = subFont;
                sCell.fill = subFill;
                sCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 24;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(3).getCell(c).border = headerBorder;

                // Row 4: Section Banner (#006039 Deep Forest Green)
                sheet.mergeCells(`A4:${lastColLetter}4`);
                const secCell = sheet.getCell('A4');
                secCell.value = sectionText;
                secCell.font = secFont;
                secCell.fill = secFill;
                secCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(4).height = 24;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(4).getCell(c).border = headerBorder;

                // Row 5: Middle Empty Spacer
                sheet.mergeCells(`A5:${lastColLetter}5`);
                const sp2 = sheet.getCell('A5');
                sp2.value = '';
                sp2.fill = whiteFill;
                sheet.getRow(5).height = 14;
            };

            // ── SHEET 1: CAPITAL SUMMARY ──
            const s1 = workbook.addWorksheet('Capital Summary', {
                views: [{ state: 'frozen', ySplit: 6, showGridLines: true }]
            });

            addExecutiveBanner(
                s1,
                'I',
                9,
                `${this.projectName} - PARTNER CAPITAL & SHAREHOLDING SUMMARY`,
                metaText,
                'PARTNER CAPITAL & SHAREHOLDING DETAILS'
            );

            const hRow = s1.getRow(6);
            hRow.values = [
                '#', 'Partner ID', 'Partner Name', 'Shareholding (%)',
                `Opening Capital (${this.currency})`, `Capital Infusions (${this.currency})`,
                `Drawings (${this.currency})`, `Profit Allocation (${this.currency})`,
                `Closing Capital (${this.currency})`
            ];
            hRow.height = 34;
            hRow.eachCell({ includeEmpty: true }, (c) => {
                c.fill = headerFill;
                c.font = headerFont;
                c.border = headerBorder;
                c.alignment = {
                    horizontal: 'center',
                    vertical: 'middle',
                    wrapText: true
                };
            });

            let s1RowIdx = 7;
            this.summaryRows.forEach((r, idx) => {
                const row = s1.getRow(s1RowIdx);
                row.values = [
                    idx + 1, r.partner_code, r.name, Number(r.share_pct || 0),
                    Number(r.opening_capital || 0), Number(r.capital_infusions || 0),
                    Number(r.drawings || 0), Number(r.profit_allocation || 0),
                    Number(r.closing_capital || 0)
                ];
                row.height = 28;
                const rowFill = (idx % 2 === 0) ? zebraEvenFill : zebraOddFill;
                for (let i = 1; i <= 9; i++) {
                    const cell = row.getCell(i);
                    cell.fill = rowFill;
                    let textColor = 'FF1E293B';
                    if (i === 6) textColor = 'FF008000';
                    else if (i === 7) textColor = 'FFB91C1C';
                    else if (i === 8) textColor = (Number(r.profit_allocation || 0) >= 0 ? 'FF008000' : 'FFB91C1C');
                    else if (i === 9) textColor = 'FF1D4ED8';

                    cell.font = {
                        name: 'Calibri',
                        size: 12,
                        bold: (i === 3 || i === 6 || i === 8 || i === 9),
                        color: { argb: textColor }
                    };
                    cell.border = dataBorder;
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    if (i === 4) cell.numFmt = '0.00';
                    if (i >= 5) cell.numFmt = '#,##0.00';
                }
                s1RowIdx++;
            });

            const totRow = s1.getRow(s1RowIdx);
            totRow.values = [
                `TOTAL (${this.summaryKPIs.total_partners} PARTNERS)`, '', '',
                Number(this.summaryKPIs.total_share_pct || 0),
                Number(this.summaryKPIs.total_opening || 0),
                Number(this.summaryKPIs.total_infusions || 0),
                Number(this.summaryKPIs.total_drawings || 0),
                Number(this.summaryKPIs.total_profit || 0),
                Number(this.summaryKPIs.total_closing || 0)
            ];
            s1.mergeCells(`A${s1RowIdx}:C${s1RowIdx}`);
            totRow.height = 40;
            for (let i = 1; i <= 9; i++) {
                const cell = totRow.getCell(i);
                cell.fill = totalFill;
                cell.font = totalFont;
                cell.border = totalBorder;
                cell.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                if (i === 4) cell.numFmt = '0.00';
                if (i >= 5) cell.numFmt = '#,##0.00';
            }

            s1.columns = [
                { width: 10 }, { width: 18 }, { width: 26 }, { width: 22 },
                { width: 26 }, { width: 26 }, { width: 26 }, { width: 28 }, { width: 28 }
            ];

            // ── SHEET 2: PARTNER TRANSACTION LEDGER ──
            if (this.activePartnerDetail) {
                const s2 = workbook.addWorksheet(`${this.activePartnerDetail.name} Ledger`.slice(0, 31), {
                    views: [{ state: 'frozen', ySplit: 6, showGridLines: true }]
                });

                addExecutiveBanner(
                    s2,
                    'I',
                    9,
                    `${this.projectName} - PARTNER CAPITAL LEDGER (${this.activePartnerDetail.name.toUpperCase()} - ${this.activePartnerDetail.partner_code})`,
                    metaText,
                    'TRANSACTION DETAILS'
                );

                const lh = s2.getRow(6);
                lh.values = [
                    '#', 'Date', 'Voucher No.', 'Particulars', 'Reference', 'Type',
                    `Credit (+) (${this.currency})`, `Debit (-) (${this.currency})`, `Balance (${this.currency})`
                ];
                lh.height = 34;
                lh.eachCell({ includeEmpty: true }, (c) => {
                    c.fill = headerFill;
                    c.font = headerFont;
                    c.border = headerBorder;
                    c.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };
                });

                let s2RowIdx = 7;
                this.filteredPartnerLedger.forEach((tx, idx) => {
                    const row = s2.getRow(s2RowIdx);
                    row.values = [
                        idx + 1, tx.date, tx.voucher_no, tx.particulars, tx.reference,
                        (tx.entry_type || '').toUpperCase(), Number(tx.credit || 0), Number(tx.debit || 0),
                        Number(tx.running_balance || 0)
                    ];
                    row.height = 28;
                    const rowFill = (idx % 2 === 0) ? zebraEvenFill : zebraOddFill;
                    for (let i = 1; i <= 9; i++) {
                        const cell = row.getCell(i);
                        cell.fill = rowFill;
                        let textColor = 'FF1E293B';
                        if (i === 6) textColor = 'FF1D4ED8';
                        else if (i === 7 && Number(tx.credit || 0) > 0) textColor = 'FF008000';
                        else if (i === 8 && Number(tx.debit || 0) > 0) textColor = 'FFB91C1C';

                        cell.font = {
                            name: 'Calibri',
                            size: 12,
                            bold: (i === 6 || i === 7 || i === 9),
                            color: { argb: textColor }
                        };
                        cell.border = dataBorder;
                        cell.alignment = {
                            horizontal: 'center',
                            vertical: 'middle'
                        };
                        if (i >= 7) cell.numFmt = '#,##0.00';
                    }
                    s2RowIdx++;
                });

                const ledgerTotalCredit = this.filteredPartnerLedger.reduce((acc, tx) => acc + Number(tx.credit || 0), 0);
                const ledgerTotalDebit = this.filteredPartnerLedger.reduce((acc, tx) => acc + Number(tx.debit || 0), 0);
                const closingBal = this.filteredPartnerLedger.length
                    ? Number(this.filteredPartnerLedger[this.filteredPartnerLedger.length - 1].running_balance || 0)
                    : 0;

                const lTotRow = s2.getRow(s2RowIdx);
                lTotRow.values = [
                    `TOTAL (${this.filteredPartnerLedger.length} LEDGER ENTRIES)`, '', '', '', '', '',
                    ledgerTotalCredit, ledgerTotalDebit, closingBal
                ];
                s2.mergeCells(`A${s2RowIdx}:F${s2RowIdx}`);
                lTotRow.height = 40;
                for (let i = 1; i <= 9; i++) {
                    const cell = lTotRow.getCell(i);
                    cell.fill = totalFill;
                    cell.font = totalFont;
                    cell.border = totalBorder;
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    if (i >= 7) cell.numFmt = '#,##0.00';
                }

                s2.columns = [
                    { width: 10 }, { width: 18 }, { width: 24 }, { width: 48 },
                    { width: 28 }, { width: 18 }, { width: 24 }, { width: 24 }, { width: 26 }
                ];
            }

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Capital_Statements_Report.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
}
</script>

</x-erp-layout>

