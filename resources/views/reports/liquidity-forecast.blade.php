<x-erp-layout title="Cash Flow & Liquidity Forecast" headerTitle="Cash Flow & Liquidity Forecast">

{{-- ExcelJS CDN Library for Executive Client-side Excel Export --}}
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
    color: #7c691c !important;
    font-weight: 800;
}
.erp-dropdown-option.selected-all {
    background-color: #f1f5f9;
    color: #475569;
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
        size: landscape;
        margin: 8mm;
    }
    html, body {
        background: #ffffff !important;
        font-size: 8pt !important;
    }
    .print\:hidden, header, nav, aside, footer, button, .no-print {
        display: none !important;
    }
}
</style>

<div class="max-w-[1850px] mx-auto space-y-5" 
     x-data="liquidityForecastApp()">

    {{-- ── 1. EXECUTIVE TOP HEADER BANNER (GOLDEN & WHITE THEME - EXACT MATCH TO IMAGE) ── --}}
    <div class="bg-gradient-to-r from-[#fdfaf3] via-[#f8f3e6] to-white rounded-2xl border border-[#a38c29]/30 p-5 sm:p-6 shadow-sm relative overflow-hidden text-slate-900">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-[#a38c29]/12 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-full bg-gradient-to-l from-[#a38c29]/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center shadow-md shadow-[#a38c29]/20 shrink-0 border border-amber-300/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-black tracking-tight uppercase text-slate-900">CASH FLOW & LIQUIDITY FORECAST</h1>
                        <!-- <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">ACCOUNTS & TREASURY</span> -->
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                        <span>Accounting</span>
                        <span>&rsaquo;</span>
                        <span>Reports</span>
                        <span>&rsaquo;</span>
                        <span class="text-[#8a7522] font-semibold">Cash Flow & Liquidity Forecast</span>
                    </div>
                </div>
            </div>

            {{-- Quick Metadata Badges (Live updating without page reload) --}}
            <div class="flex items-center gap-3 text-right">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">As On Date</span>
                    <span class="text-xs font-mono font-black text-slate-900" x-text="filters.as_on_date">{{ $asOnDate }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Forecast Horizon</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="filters.period + ' Days (' + kpis.status + ')'">Next 90 Days</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 6 FINANCIAL KPI METRIC CARDS (EXACT CARD UI & COLORS FROM IMAGE) ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        
        {{-- Card 1: TOTAL LIQUID CASH (Blue Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL LIQUID CASH</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(kpis.current_liquid_cash)"
                     x-text="currency + ' ' + formatMoney(kpis.current_liquid_cash)">
                    {{ $currency }} {{ number_format($kpis['current_liquid_cash'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-blue-600 font-bold mt-1">Bank + Petty Cash Balances</div>
            </div>
        </div>

        {{-- Card 2: TOTAL INFLOWS (Gold Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL INFLOWS</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(kpis.total_projected_inflows)"
                     x-text="currency + ' ' + formatMoney(kpis.total_projected_inflows)">
                    {{ $currency }} {{ number_format($kpis['total_projected_inflows'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-[#8a7522] font-bold mt-1">Total Collections Due</div>
            </div>
        </div>

        {{-- Card 3: 0 - 30 DAYS (Emerald Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider" x-text="filters.financial_year !== 'All' ? 'APR - JUL (T1)' : '0 - 30 DAYS'">0 - 30 DAYS</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p1)"
                     x-text="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p1)">
                    {{ $currency }} {{ number_format($summaryTable['net_cash_flow']['p1'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1">Net Projected Cash Flow</div>
            </div>
        </div>

        {{-- Card 4: 31 - 60 DAYS (Amber Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-amber-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider" x-text="filters.financial_year !== 'All' ? 'AUG - NOV (T2)' : '31 - 60 DAYS'">31 - 60 DAYS</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-amber-800 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p2)"
                     x-text="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p2)">
                    {{ $currency }} {{ number_format($summaryTable['net_cash_flow']['p2'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-amber-600 font-bold mt-1">Net Projected Cash Flow</div>
            </div>
        </div>

        {{-- Card 5: 61 - 90 DAYS (Orange Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-orange-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider" x-text="filters.financial_year !== 'All' ? 'DEC - MAR (T3)' : '61 - 90 DAYS'">61 - 90 DAYS</span>
                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-orange-800 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p3)"
                     x-text="currency + ' ' + formatMoney(summaryTable.net_cash_flow?.p3)">
                    {{ $currency }} {{ number_format($summaryTable['net_cash_flow']['p3'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-orange-600 font-bold mt-1">Net Projected Cash Flow</div>
            </div>
        </div>

        {{-- Card 6: TOTAL OUTFLOWS (Rose Accent) --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL OUTFLOWS</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(kpis.total_projected_outflows)"
                     x-text="currency + ' ' + formatMoney(kpis.total_projected_outflows)">
                    {{ $currency }} {{ number_format($kpis['total_projected_outflows'] ?? 0, 2) }}
                </div>
                <div class="text-[10px] text-rose-600 font-bold mt-1"
                     x-text="'Net: ' + currency + ' ' + formatCompact(kpis.net_liquidity) + ' (' + kpis.status + ')'">
                    Net: {{ $currency }} {{ number_format($kpis['net_liquidity'] ?? 0, 2) }} ({{ $kpis['status'] ?? 'Surplus' }})
                </div>
            </div>
        </div>

    </div>

    {{-- ── 3. ZERO-RELOAD FILTER BAR (INSTANT CLIENT-SIDE FILTERING - NO LOADING) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all mb-4 print:hidden">
        <div class="flex items-center gap-2.5 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Search Transactions --}}
            <div class="relative min-w-[180px] flex-[1.4]" style="flex: 1.4 1 0%;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       x-model="txSearch"
                       @input="currentPage = 1; triggerLoadingEffect()"
                       placeholder="Search transaction party name, reference or description..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="txSearch">
                    <button type="button" @click="txSearch = ''; currentPage = 1; triggerLoadingEffect()" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
                </template>
            </div>

            {{-- 2. Project Filter Dropdown (Zero Reload) --}}
            <div class="relative min-w-[160px] flex-1" style="flex: 1 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="truncate text-xs font-bold"
                              :class="filters.project_id !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="getProjectLabel()">All Projects</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[220px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectProject('all'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.project_id === 'all' ? 'selected-all' : ''">
                            <span>— All Projects —</span>
                        </div>
                        @foreach($allProjects as $proj)
                            <div @click="selectProject('{{ $proj['id'] }}'); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.project_id === '{{ $proj['id'] }}' ? 'selected' : ''">
                                <span>{{ $proj['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2b. Financial Year (FY) Filter Dropdown (Custom Gold Popover - Zero Reload) --}}
            <div class="relative min-w-[130px] flex-[0.85]" style="flex: 0.85 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''" @click.outside="open = false">
                <button type="button"
                        @click="open = !open"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                        <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
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
                     class="erp-dropdown-popover min-w-[205px] w-max"
                     style="display: none;">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-60">
                        <div @click="selectFinancialYear('All'); open = false"
                             class="erp-dropdown-option"
                             :class="(!filters.financial_year || filters.financial_year === 'All') ? '!bg-[#a38c29] !text-white font-extrabold' : ''">
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
                    </div>
                </div>
            </div>

            {{-- 3. Forecast Period Dropdown (Zero Reload) --}}
            <div class="relative min-w-[140px] flex-[0.9]" style="flex: 0.9 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate text-xs font-bold text-slate-900" x-text="'Next ' + filters.period + ' Days'">Next 90 Days</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[160px]">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectPeriod('90'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.period === '90' ? 'selected' : ''">
                            <span>Next 90 Days</span>
                        </div>
                        <div @click="selectPeriod('60'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.period === '60' ? 'selected' : ''">
                            <span>Next 60 Days</span>
                        </div>
                        <div @click="selectPeriod('30'); open = false"
                             class="erp-dropdown-option"
                             :class="filters.period === '30' ? 'selected' : ''">
                            <span>Next 30 Days</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. As On Date Input --}}
            <div class="relative min-w-[130px] flex-[0.8]" style="flex: 0.8 1 0%;">
                <input type="text"
                       x-model="filters.as_on_date"
                       @change="triggerLoadingEffect()"
                       placeholder="DD/MM/YYYY"
                       class="w-full erp-search-input px-3 text-xs font-bold text-slate-800">
            </div>

            {{-- 5. Action Buttons (RESET FILTERS + EXPORT EXCEL ONLY) --}}
            <div class="flex items-center gap-2 shrink-0">
                
                {{-- Reset Filters Button (Single Theme Button - Zero Reload) --}}
                <button type="button" 
                        @click="resetFilters()"
                        class="theme-btn h-[38px] px-4 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap"
                        title="Reset All Filters">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>RESET FILTERS</span>
                </button>

                {{-- Export Excel Button --}}
                <button type="button" 
                        @click="exportToExcel()" 
                        class="px-3.5 py-2 h-[38px] bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer shrink-0 uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Export Excel</span>
                </button>

            </div>

        </div>
    </div>

    {{-- ── 4. VISUAL CHARTS ROW (PROJECTED CASH FLOW + OUTFLOW BREAKDOWN) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Left 2/3 (8 cols): Projected Cash Flow Combo Chart --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider" x-text="'Projected Cash Flow (Next ' + filters.period + ' Days)'">
                        Projected Cash Flow (Next 90 Days)
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Monthly Inflows & Outflows with Closing Liquidity Run-Rate</p>
                </div>
                {{-- Custom Interactive Legend Badges --}}
                <div class="flex items-center gap-3 text-xs font-bold">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-xs bg-[#10b981]"></span>
                        <span class="text-slate-600">Inflows</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-xs bg-[#f43f5e]"></span>
                        <span class="text-slate-600">Outflows</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-1 bg-[#2563eb] rounded-full"></span>
                        <span class="text-slate-600">Closing Balance</span>
                    </div>
                </div>
            </div>

            <div class="w-full mt-2">
                <div id="cashflowComboChart" class="w-full min-h-[320px]"></div>
            </div>
        </div>

        {{-- Right 1/3 (4 cols): Projected Outflows by Category Donut Chart --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider" x-text="'Projected Outflows by Category (Next ' + filters.period + ' Days)'">
                    Projected Outflows by Category (Next 90 Days)
                </h3>
                <p class="text-xs text-slate-400 font-medium">Expenditure allocation and commitment share</p>
            </div>

            {{-- Donut Chart --}}
            <div class="w-full my-2 flex items-center justify-center">
                <div id="outflowDonutChart" class="w-full max-w-[280px] min-h-[220px]"></div>
            </div>

            {{-- Category Legend List with Amounts & Percentages --}}
            <div class="space-y-2 pt-2 border-t border-slate-100">
                <template x-for="(cat, idx) in categoryOutflows" :key="idx">
                    <div class="flex items-center justify-between text-xs py-1 px-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-xs shrink-0" :style="'background-color: ' + cat.color"></span>
                            <span class="text-slate-700 font-bold truncate" x-text="cat.name"></span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono font-bold text-slate-900" x-text="currency + ' ' + formatCompact(cat.amount)"></span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600" x-text="cat.percentage + '%'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    {{-- ── 5. DETAILED BREAKDOWN ROW (CASH FLOW SUMMARY TABLE + CLOSING BALANCE & INSIGHTS) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Left 2/3 (8 cols): Cash Flow Summary (₹) Detailed Statement --}}
        <div class="relative lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
            {{-- Loading Spinner Overlay (Exact Partner Contributions Style) --}}
            <div x-show="isLoading" x-cloak
                 class="absolute inset-0 bg-white/70 backdrop-blur-[1px] flex items-center justify-center z-30 transition-opacity rounded-2xl print:hidden"
                 style="display: none;">
                <div class="flex items-center gap-2.5 px-4 py-2.5 bg-slate-900/90 text-white rounded-xl shadow-xl text-xs font-bold">
                    <svg class="animate-spin w-4 h-4 text-[#a38c29]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Updating Liquidity Forecast...</span>
                </div>
            </div>

            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider" x-text="'Cash Flow Summary (' + currency + ')'">
                        Cash Flow Summary (₹)
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Categorized periodic inflow vs outflow distribution</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Forecast Horizon</span>
                    <span class="text-xs font-mono font-bold text-slate-700" x-text="filters.period + ' Calendar Days'">90 Calendar Days</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="global-table-header">
                        <tr>
                            <th class="py-3 px-3 text-center w-10">#</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-3 text-right" x-text="periods.p1Label"></th>
                            <th class="py-3 px-3 text-right" x-text="periods.p2Label"></th>
                            <th class="py-3 px-3 text-right" x-text="periods.p3Label"></th>
                            <th class="py-3 px-4 text-right" x-text="'Total (' + filters.period + ' Days)'"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        
                        {{-- ── PROJECTED INFLOWS SECTION ── --}}
                        <tr class="bg-emerald-50/70 border-t border-b border-emerald-200/60 font-black text-emerald-900">
                            <td class="py-2 px-3 text-center text-emerald-600 font-mono">-</td>
                            <td colspan="5" class="py-2 px-4 uppercase tracking-wider text-[11px] text-emerald-800">
                                Projected Inflows
                            </td>
                        </tr>
                        <template x-for="(inflow, idx) in (summaryTable.inflows || [])" :key="'in-' + idx">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400" x-text="idx + 1"></td>
                                <td class="py-2.5 px-4 font-bold text-slate-800" x-text="inflow.name"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(inflow.p1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(inflow.p2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(inflow.p3)"></td>
                                <td class="py-2.5 px-4 text-right font-mono font-black text-slate-900 bg-slate-50/50" x-text="formatMoney(inflow.total)"></td>
                            </tr>
                        </template>

                        {{-- Total Projected Inflows --}}
                        <tr class="bg-emerald-100/60 font-black text-emerald-950 border-t border-b border-emerald-200">
                            <td class="py-2.5 px-3 text-center font-mono"></td>
                            <td class="py-2.5 px-4 uppercase tracking-wider text-xs font-black text-emerald-900">Total Projected Inflows</td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(summaryTable.total_inflows?.p1)"></td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(summaryTable.total_inflows?.p2)"></td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(summaryTable.total_inflows?.p3)"></td>
                            <td class="py-2.5 px-4 text-right font-mono font-black text-emerald-900 bg-emerald-200/40 text-sm" x-text="formatMoney(summaryTable.total_inflows?.total)"></td>
                        </tr>

                        {{-- ── PROJECTED OUTFLOWS SECTION ── --}}
                        <tr class="bg-rose-50/70 border-t border-b border-rose-200/60 font-black text-rose-900">
                            <td class="py-2 px-3 text-center text-rose-600 font-mono">-</td>
                            <td colspan="5" class="py-2 px-4 uppercase tracking-wider text-[11px] text-rose-800">
                                Projected Outflows
                            </td>
                        </tr>
                        <template x-for="(outflow, idx) in (summaryTable.outflows || [])" :key="'out-' + idx">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400" x-text="idx + 3"></td>
                                <td class="py-2.5 px-4 font-bold text-slate-800" x-text="outflow.name"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(outflow.p1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(outflow.p2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold" x-text="formatMoney(outflow.p3)"></td>
                                <td class="py-2.5 px-4 text-right font-mono font-black text-slate-900 bg-slate-50/50" x-text="formatMoney(outflow.total)"></td>
                            </tr>
                        </template>

                        {{-- Total Projected Outflows --}}
                        <tr class="bg-rose-100/60 font-black text-rose-950 border-t border-b border-rose-200">
                            <td class="py-2.5 px-3 text-center font-mono"></td>
                            <td class="py-2.5 px-4 uppercase tracking-wider text-xs font-black text-rose-900">Total Projected Outflows</td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(summaryTable.total_outflows?.p1)"></td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(summaryTable.total_outflows?.p2)"></td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(summaryTable.total_outflows?.p3)"></td>
                            <td class="py-2.5 px-4 text-right font-mono font-black text-rose-900 bg-rose-200/40 text-sm" x-text="formatMoney(summaryTable.total_outflows?.total)"></td>
                        </tr>

                        {{-- ── GRAND NET PROJECTED CASH FLOW (SURPLUS / DEFICIT) ── --}}
                        <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-slate-300">
                            <td class="py-3 px-3 text-center font-mono">★</td>
                            <td class="py-3 px-4 uppercase tracking-wider text-xs font-black text-slate-900">
                                Net Projected Cash Flow (Surplus / Deficit)
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-black"
                                :class="(summaryTable.net_cash_flow?.p1 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                x-text="formatMoney(summaryTable.net_cash_flow?.p1)">
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-black"
                                :class="(summaryTable.net_cash_flow?.p2 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                x-text="formatMoney(summaryTable.net_cash_flow?.p2)">
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-black"
                                :class="(summaryTable.net_cash_flow?.p3 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                x-text="formatMoney(summaryTable.net_cash_flow?.p3)">
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-black text-sm bg-slate-200/70"
                                :class="(summaryTable.net_cash_flow?.total || 0) >= 0 ? 'text-emerald-800' : 'text-rose-800'"
                                x-text="formatMoney(summaryTable.net_cash_flow?.total)">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right 1/3 (4 cols): Projected Closing Cash Balance Chart + Key Insights --}}
        <div class="lg:col-span-4 space-y-5">
            
            {{-- Mini Bar Chart: Projected Closing Cash Balance --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs">
                <div class="border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Projected Closing Cash Balance</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Trajectory of liquid reserves by milestone end-date</p>
                </div>
                <div class="w-full mt-2">
                    <div id="closingBalanceMiniChart" class="w-full min-h-[160px]"></div>
                </div>
            </div>

            {{-- Key Insights Card --}}
            <div class="bg-amber-50/60 rounded-2xl border border-amber-200/80 p-5 shadow-2xs">
                <div class="flex items-center gap-2 border-b border-amber-200/60 pb-2.5 mb-3">
                    <span class="text-base">💡</span>
                    <h3 class="text-xs font-black text-amber-950 uppercase tracking-wider">Key Insights</h3>
                </div>
                <ul class="space-y-2.5 text-xs text-slate-700 font-medium leading-relaxed">
                    <template x-for="(insight, idx) in keyInsights" :key="'ins-' + idx">
                        <li class="flex items-start gap-2">
                            <span class="text-amber-600 font-black shrink-0 mt-0.5">•</span>
                            <span x-text="insight"></span>
                        </li>
                    </template>
                </ul>
            </div>

        </div>

    </div>

    {{-- ── 6. BOTTOM SECTION: UPCOMING KEY TRANSACTIONS (NEXT 90 DAYS) ── --}}
    <div class="relative bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
        {{-- Loading Spinner Overlay (Exact Partner Contributions Style) --}}
        <div x-show="isLoading" x-cloak
             class="absolute inset-0 bg-white/70 backdrop-blur-[1px] flex items-center justify-center z-30 transition-opacity rounded-2xl print:hidden"
             style="display: none;">
            <div class="flex items-center gap-2.5 px-4 py-2.5 bg-slate-900/90 text-white rounded-xl shadow-xl text-xs font-bold">
                <svg class="animate-spin w-4 h-4 text-[#a38c29]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Updating Liquidity Forecast...</span>
            </div>
        </div>
        
        {{-- Card Header & Filter Tabs --}}
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider" x-text="'Upcoming Key Transactions (Next ' + filters.period + ' Days)'">
                    Upcoming Key Transactions (Next 90 Days)
                </h3>
                <p class="text-xs text-slate-400 font-medium">Detailed schedule of milestone receivables, RA settlements, vendor bills & bank EMIs</p>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                <template x-for="tab in transactionTabs" :key="tab.id">
                    <button type="button"
                            @click="activeTxTab = tab.id; currentPage = 1; triggerLoadingEffect()"
                            :class="activeTxTab === tab.id ? 'bg-[#a38c29] text-white font-black shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold'"
                            class="px-3 py-1.5 rounded-xl text-xs transition-all cursor-pointer"
                            x-text="tab.label">
                    </button>
                </template>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="global-table-header">
                    <tr>
                        <th class="py-3 px-3 text-center w-10">#</th>
                        <th class="py-3 px-3.5">Date</th>
                        <th class="py-3 px-3.5">Type</th>
                        <th class="py-3 px-3.5">Reference No.</th>
                        <th class="py-3 px-4">Party Name</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-right" x-text="'Amount (' + currency + ')'">Amount (₹)</th>
                        <th class="py-3 px-3.5 text-center">Category</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <template x-for="(tx, index) in paginatedTransactions" :key="index">
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-3 text-center font-mono text-slate-400" x-text="(currentPage - 1) * perPage + index + 1"></td>
                            <td class="py-2.5 px-3.5 font-mono font-bold text-slate-800" x-text="tx.date"></td>
                            <td class="py-2.5 px-3.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider inline-block"
                                      :class="getTxTypeBadgeClass(tx.type)"
                                      x-text="tx.type">
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 font-mono font-bold text-slate-800" x-text="tx.ref_no"></td>
                            <td class="py-2.5 px-4 font-bold text-slate-900" x-text="tx.party_name"></td>
                            <td class="py-2.5 px-4 text-slate-600 text-xs" x-text="tx.description"></td>
                            <td class="py-2.5 px-4 text-right font-mono font-black"
                                :class="tx.flow_type === 'inflow' ? 'text-emerald-700' : 'text-slate-900'"
                                x-text="formatMoney(tx.amount)">
                            </td>
                            <td class="py-2.5 px-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold tracking-wider inline-block"
                                      :class="getCategoryBadgeClass(tx.category)"
                                      x-text="tx.category">
                                </span>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredTransactions.length === 0">
                        <td colspan="8" class="py-8 text-center text-slate-400 italic text-xs">
                            No upcoming transactions match the selected filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Table Footer Summary & Pagination Controls --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-2xl" x-show="filteredTransactions.length > 0">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900" x-text="(currentPage - 1) * perPage + 1"></span> TO 
                <span class="text-slate-900" x-text="Math.min(currentPage * perPage, filteredTransactions.length)"></span> OF 
                <span class="text-slate-900" x-text="filteredTransactions.length"></span> TRANSACTIONS
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="if(currentPage > 1) { currentPage--; triggerLoadingEffect(); }" 
                        :disabled="currentPage <= 1"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                    PREV
                </button>
                
                {{-- Page Numbers --}}
                <template x-for="(p, pIdx) in getPageNumbers()" :key="pIdx">
                    <span class="inline-flex items-center gap-1">
                        <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                        <button type="button" x-show="p !== '...'"
                                @click="currentPage = p; triggerLoadingEffect();"
                                x-text="p"
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs cursor-pointer"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white border border-[#a38c29]' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"></button>
                    </span>
                </template>
                
                <button type="button" @click="if(currentPage < totalPages) { currentPage++; triggerLoadingEffect(); }" 
                        :disabled="currentPage >= totalPages"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer">
                    NEXT
                </button>
            </div>
        </div>

    </div>

</div>

<script>
function liquidityForecastApp() {
    return {
        currency: @json($currency ?? '₹'),
        defaultAsOnDate: @json($asOnDate ?? now()->format('d/m/Y')),
        defaultProjectId: @json((string)$selectedProjectId ?? 'all'),
        filters: {
            as_on_date: @json($asOnDate ?? now()->format('d/m/Y')),
            period: '90',
            project_id: @json((string)$selectedProjectId ?? 'all'),
            financial_year: 'All'
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
        allProjects: @json($allProjects ?? []),
        projectDatasets: @json($projectDatasets ?? []),
        kpis: @json($kpis ?? []),
        periods: @json($periods ?? []),
        summaryTable: @json($summaryTable ?? []),
        chartData: @json($chartData ?? []),
        categoryOutflows: @json($categoryOutflowBreakdown ?? []),
        allTransactions: @json($upcomingTransactions ?? []),
        keyInsights: @json($keyInsights ?? []),
        activeTxTab: 'all',
        txSearch: '',
        currentPage: 1,
        perPage: 50,
        transactionTabs: [
            { id: 'all', label: 'All' },
            { id: 'Customer Receipt', label: 'Customer Receipts' },
            { id: 'Contractor Payment', label: 'Contractor Payments' },
            { id: 'Vendor Payment', label: 'Vendor Payments' },
            { id: 'Bank Loan EMI', label: 'Bank Loan EMIs' },
            { id: 'Brokerage Payout', label: 'Brokerage Payouts' },
            { id: 'Site Expense', label: 'Site Expenses' }
        ],
        comboChartInstance: null,
        donutChartInstance: null,
        closingBarChartInstance: null,

        // Loading Effect State
        isLoading: false,
        _loadingTimer: null,

        triggerLoadingEffect() {
            this.isLoading = true;
            if (this._loadingTimer) {
                clearTimeout(this._loadingTimer);
            }
            this._loadingTimer = setTimeout(() => {
                this.isLoading = false;
            }, 220);
        },

        init() {
            this.$nextTick(() => {
                this.renderComboChart();
                this.renderDonutChart();
                this.renderClosingBarChart();
            });
        },

        getProjectLabel() {
            if (this.filters.project_id === 'all') return 'All Projects';
            const p = this.allProjects.find(item => String(item.id) === String(this.filters.project_id));
            return p ? p.name : 'All Projects';
        },

        getSelectedFyName() {
            if (!this.filters.financial_year || this.filters.financial_year === 'All') return 'All FY';
            const found = this.financialYearsList.find(x => x.value === this.filters.financial_year);
            return found ? found.label : ('FY ' + this.filters.financial_year);
        },

        selectProject(id) {
            this.triggerLoadingEffect();
            this.filters.project_id = String(id);
            if (this.filters.financial_year && this.filters.financial_year !== 'All') {
                this.applyFinancialYearFilter();
            } else {
                this.applyDataset();
            }
        },

        selectFinancialYear(fyVal) {
            this.triggerLoadingEffect();
            this.filters.financial_year = fyVal;
            this.currentPage = 1;
            if (!fyVal || fyVal === 'All') {
                this.applyDataset();
                if (this.filters.period !== '90') {
                    this.applyPeriodFilter();
                }
            } else {
                this.applyFinancialYearFilter();
            }
        },

        selectPeriod(period) {
            this.triggerLoadingEffect();
            this.filters.period = String(period);
            if (this.filters.financial_year && this.filters.financial_year !== 'All') {
                this.applyFinancialYearFilter();
            } else {
                this.applyPeriodFilter();
            }
        },

        applyFinancialYearFilter() {
            const ds = this.projectDatasets[this.filters.project_id] || this.projectDatasets['all'] || {};
            const fy = this.financialYearsList.find(x => x.value === this.filters.financial_year);
            if (!fy || !ds.kpis) {
                this.applyDataset();
                return;
            }

            const rawItems = ds.allTimeTransactions || ds.upcomingTransactions || [];
            const fyItems = rawItems.filter(tx => tx.date_raw && tx.date_raw >= fy.from && tx.date_raw <= fy.to);

            const startYear = parseInt(fy.from.slice(0, 4), 10);
            const t1End = `${startYear}-07-31`;
            const t2End = `${startYear}-11-30`;

            const p1Label = `Apr - Jul ${startYear}`;
            const p2Label = `Aug - Nov ${startYear}`;
            const p3Label = `Dec ${startYear} - Mar ${startYear + 1}`;

            const sums = {
                milestones: [0, 0, 0],
                receipts: [0, 0, 0],
                ra_bills: [0, 0, 0],
                vendor_bills: [0, 0, 0],
                loan_emis: [0, 0, 0],
                brokerage: [0, 0, 0],
                petty_cash: [0, 0, 0]
            };

            fyItems.forEach(tx => {
                const key = tx.source_key;
                if (!sums[key]) return;
                const amt = parseFloat(tx.amount) || 0;
                if (tx.date_raw <= t1End) sums[key][0] += amt;
                else if (tx.date_raw <= t2End) sums[key][1] += amt;
                else sums[key][2] += amt;
            });

            const tot = (arr) => arr[0] + arr[1] + arr[2];

            const inP1 = sums.milestones[0] + sums.receipts[0];
            const inP2 = sums.milestones[1] + sums.receipts[1];
            const inP3 = sums.milestones[2] + sums.receipts[2];
            const totalIn = inP1 + inP2 + inP3;

            const outP1 = sums.ra_bills[0] + sums.vendor_bills[0] + sums.loan_emis[0] + sums.brokerage[0] + sums.petty_cash[0];
            const outP2 = sums.ra_bills[1] + sums.vendor_bills[1] + sums.loan_emis[1] + sums.brokerage[1] + sums.petty_cash[1];
            const outP3 = sums.ra_bills[2] + sums.vendor_bills[2] + sums.loan_emis[2] + sums.brokerage[2] + sums.petty_cash[2];
            const totalOut = outP1 + outP2 + outP3;

            const netP1 = inP1 - outP1;
            const netP2 = inP2 - outP2;
            const netP3 = inP3 - outP3;
            const totalNet = totalIn - totalOut;

            const currCash = ds.kpis.current_liquid_cash || 0;
            const closeP1 = currCash + netP1;
            const closeP2 = closeP1 + netP2;
            const closeP3 = closeP2 + netP3;

            this.periods = { p1Label, p2Label, p3Label };

            this.kpis = {
                current_liquid_cash: currCash,
                total_projected_inflows: totalIn,
                total_projected_outflows: totalOut,
                net_liquidity: closeP3,
                total_net_cash_flow: totalNet,
                status: closeP3 >= 0 ? 'Surplus' : 'Deficit'
            };

            this.summaryTable = {
                inflows: [
                    { name: 'Customer Milestone Payments (Installments)', p1: sums.milestones[0], p2: sums.milestones[1], p3: sums.milestones[2], total: tot(sums.milestones) },
                    { name: 'Pending Cheque / Receipt Realizations', p1: sums.receipts[0], p2: sums.receipts[1], p3: sums.receipts[2], total: tot(sums.receipts) }
                ],
                total_inflows: { p1: inP1, p2: inP2, p3: inP3, total: totalIn },
                outflows: [
                    { name: 'Contractor RA Bills Payables', p1: sums.ra_bills[0], p2: sums.ra_bills[1], p3: sums.ra_bills[2], total: tot(sums.ra_bills) },
                    { name: 'Vendor & Site Expense Payables', p1: sums.vendor_bills[0], p2: sums.vendor_bills[1], p3: sums.vendor_bills[2], total: tot(sums.vendor_bills) },
                    { name: 'Bank Loan EMIs & Interest Schedules', p1: sums.loan_emis[0], p2: sums.loan_emis[1], p3: sums.loan_emis[2], total: tot(sums.loan_emis) },
                    { name: 'Brokerage & Commission Payables', p1: sums.brokerage[0], p2: sums.brokerage[1], p3: sums.brokerage[2], total: tot(sums.brokerage) },
                    { name: 'Pending Petty Cash Expenses', p1: sums.petty_cash[0], p2: sums.petty_cash[1], p3: sums.petty_cash[2], total: tot(sums.petty_cash) }
                ],
                total_outflows: { p1: outP1, p2: outP2, p3: outP3, total: totalOut },
                net_cash_flow: { p1: netP1, p2: netP2, p3: netP3, total: totalNet }
            };

            const totalRa = tot(sums.ra_bills);
            const totalLoans = tot(sums.loan_emis);
            const totalBrokerage = tot(sums.brokerage);
            const totalVendor = tot(sums.vendor_bills);
            const totalPetty = tot(sums.petty_cash);

            this.categoryOutflows = [
                { name: 'Contractor RA Bills', amount: totalRa, percentage: totalOut > 0 ? Math.round((totalRa / totalOut) * 1000) / 10 : 0, color: '#F97316' },
                { name: 'Bank Loan EMIs', amount: totalLoans, percentage: totalOut > 0 ? Math.round((totalLoans / totalOut) * 1000) / 10 : 0, color: '#10B981' },
                { name: 'Brokerage Commissions', amount: totalBrokerage, percentage: totalOut > 0 ? Math.round((totalBrokerage / totalOut) * 1000) / 10 : 0, color: '#8B5CF6' },
                { name: 'Vendor Material Bills', amount: totalVendor, percentage: totalOut > 0 ? Math.round((totalVendor / totalOut) * 1000) / 10 : 0, color: '#3B82F6' },
                { name: 'Pending Petty Cash Expenses', amount: totalPetty, percentage: totalOut > 0 ? Math.round((totalPetty / totalOut) * 1000) / 10 : 0, color: '#EC4899' }
            ];

            this.chartData = {
                categories: ['Current Balance', 'Apr - Jul', 'Aug - Nov', 'Dec - Mar'],
                inflows: [0, Math.round(inP1 * 100) / 100, Math.round(inP2 * 100) / 100, Math.round(inP3 * 100) / 100],
                outflows: [0, Math.round(outP1 * 100) / 100, Math.round(outP2 * 100) / 100, Math.round(outP3 * 100) / 100],
                closing_balances: [Math.round(currCash * 100) / 100, Math.round(closeP1 * 100) / 100, Math.round(closeP2 * 100) / 100, Math.round(closeP3 * 100) / 100],
                closing_bars: [Math.round(currCash * 100) / 100, Math.round(closeP1 * 100) / 100, Math.round(closeP2 * 100) / 100, Math.round(closeP3 * 100) / 100]
            };

            let peakLabel = 'Apr - Jul';
            if (outP2 > outP1 && outP2 >= outP3) peakLabel = 'Aug - Nov';
            else if (outP3 > outP1 && outP3 > outP2) peakLabel = 'Dec - Mar';

            this.keyInsights = [
                `${fy.label} (${fy.range}): Scheduled inflows total ${this.currency} ${this.formatMoney(totalIn)} against outflows of ${this.currency} ${this.formatMoney(totalOut)}.`,
                `Projected ${fy.label} ending cash reserve stands at ${this.currency} ${this.formatMoney(closeP3)} (${totalNet >= 0 ? 'Net Surplus' : 'Net Deficit'}).`,
                `Highest scheduled outflow concentration in ${fy.label} falls in ${peakLabel} (${this.currency} ${this.formatMoney(Math.max(outP1, outP2, outP3))}).`,
                `Matching transaction schedule contains ${fyItems.length} real entries within ${fy.label}.`
            ];

            this.allTransactions = fyItems;
            this.currentPage = 1;
            this.updateCharts();
        },

        applyDataset() {
            const ds = this.projectDatasets[this.filters.project_id] || this.projectDatasets['all'] || {};
            if (ds.kpis) {
                this.kpis = JSON.parse(JSON.stringify(ds.kpis));
                this.summaryTable = JSON.parse(JSON.stringify(ds.summaryTable));
                this.chartData = JSON.parse(JSON.stringify(ds.chartData));
                this.categoryOutflows = JSON.parse(JSON.stringify(ds.categoryOutflowBreakdown));
                this.allTransactions = JSON.parse(JSON.stringify(ds.upcomingTransactions));
                this.keyInsights = JSON.parse(JSON.stringify(ds.keyInsights));
                this.periods = JSON.parse(JSON.stringify(ds.periods));
                this.currentPage = 1;
                this.updateCharts();
            }
        },

        applyPeriodFilter() {
            const days = parseInt(this.filters.period, 10) || 90;
            const ds = this.projectDatasets[this.filters.project_id] || this.projectDatasets['all'] || {};
            if (!ds.kpis || !ds.summaryTable) return;

            const ti = ds.summaryTable.total_inflows || {};
            const to = ds.summaryTable.total_outflows || {};
            const nc = ds.summaryTable.net_cash_flow || {};

            let inflowSum = 0;
            let outflowSum = 0;
            let netSum = 0;
            let sliceCount = 4;

            if (days === 30) {
                inflowSum = ti.p1 || 0;
                outflowSum = to.p1 || 0;
                netSum = nc.p1 || 0;
                sliceCount = 2; // Current + P1
            } else if (days === 60) {
                inflowSum = (ti.p1 || 0) + (ti.p2 || 0);
                outflowSum = (to.p1 || 0) + (to.p2 || 0);
                netSum = (nc.p1 || 0) + (nc.p2 || 0);
                sliceCount = 3; // Current + P1 + P2
            } else {
                inflowSum = ti.total || 0;
                outflowSum = to.total || 0;
                netSum = nc.total || 0;
                sliceCount = 4; // Current + P1 + P2 + P3
            }

            const endLiquidCash = (ds.kpis.current_liquid_cash || 0) + netSum;

            this.kpis = {
                current_liquid_cash: ds.kpis.current_liquid_cash,
                total_projected_inflows: inflowSum,
                total_projected_outflows: outflowSum,
                net_liquidity: endLiquidCash,
                total_net_cash_flow: netSum,
                status: endLiquidCash >= 0 ? 'Surplus' : 'Deficit'
            };

            this.chartData = {
                categories: (ds.chartData.categories || []).slice(0, sliceCount),
                inflows: (ds.chartData.inflows || []).slice(0, sliceCount),
                outflows: (ds.chartData.outflows || []).slice(0, sliceCount),
                closing_balances: (ds.chartData.closing_balances || []).slice(0, sliceCount),
                closing_bars: (ds.chartData.closing_bars || []).slice(0, sliceCount)
            };

            this.updateCharts();
        },

        resetFilters() {
            this.triggerLoadingEffect();
            this.filters.project_id = String(this.defaultProjectId);
            this.filters.financial_year = 'All';
            this.filters.period = '90';
            this.filters.as_on_date = this.defaultAsOnDate;
            this.txSearch = '';
            this.activeTxTab = 'all';
            this.currentPage = 1;
            this.applyDataset();
        },

        get filteredTransactions() {
            let list = this.allTransactions;
            if (this.filters.financial_year && this.filters.financial_year !== 'All') {
                const fy = this.financialYearsList.find(x => x.value === this.filters.financial_year);
                if (fy) {
                    list = list.filter(tx => tx.date_raw && tx.date_raw >= fy.from && tx.date_raw <= fy.to);
                }
            }
            if (this.activeTxTab !== 'all') {
                list = list.filter(tx => tx.type === this.activeTxTab);
            }
            if (this.txSearch.trim()) {
                const q = this.txSearch.toLowerCase().trim();
                list = list.filter(tx => 
                    (tx.party_name && tx.party_name.toLowerCase().includes(q)) ||
                    (tx.ref_no && tx.ref_no.toLowerCase().includes(q)) ||
                    (tx.description && tx.description.toLowerCase().includes(q)) ||
                    (tx.category && tx.category.toLowerCase().includes(q))
                );
            }
            return list;
        },

        get totalPages() {
            return Math.ceil(this.filteredTransactions.length / this.perPage) || 1;
        },

        get paginatedTransactions() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredTransactions.slice(start, start + this.perPage);
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
            const n = parseFloat(num) || 0;
            if (n >= 10000000) return (n / 10000000).toFixed(2) + ' Cr';
            if (n >= 100000) return (n / 100000).toFixed(2) + ' L';
            if (n >= 1000) return (n / 1000).toFixed(1) + ' K';
            return n.toFixed(2);
        },

        getTxTypeBadgeClass(type) {
            switch(type) {
                case 'Customer Receipt':
                    return 'bg-emerald-100 text-emerald-800 border border-emerald-300/60';
                case 'Contractor Payment':
                    return 'bg-orange-100 text-orange-800 border border-orange-300/60';
                case 'Vendor Payment':
                    return 'bg-blue-100 text-blue-800 border border-blue-300/60';
                case 'Bank Loan EMI':
                    return 'bg-purple-100 text-purple-800 border border-purple-300/60';
                case 'Brokerage Payout':
                    return 'bg-indigo-100 text-indigo-800 border border-indigo-300/60';
                case 'Site Expense':
                    return 'bg-pink-100 text-pink-800 border border-pink-300/60';
                default:
                    return 'bg-slate-100 text-slate-700 border border-slate-300/60';
            }
        },

        getCategoryBadgeClass(category) {
            if (category && category.includes('Inflow')) {
                return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            }
            if (category && category.includes('Contractor')) {
                return 'bg-orange-50 text-orange-700 border border-orange-200';
            }
            if (category && category.includes('Vendor')) {
                return 'bg-blue-50 text-blue-700 border border-blue-200';
            }
            if (category && category.includes('Loan')) {
                return 'bg-purple-50 text-purple-700 border border-purple-200';
            }
            if (category && category.includes('Brokerage')) {
                return 'bg-indigo-50 text-indigo-700 border border-indigo-200';
            }
            return 'bg-rose-50 text-rose-700 border border-rose-200';
        },

        updateCharts() {
            if (this.comboChartInstance) {
                this.comboChartInstance.updateOptions({
                    series: [
                        { name: 'Inflows', type: 'column', data: this.chartData.inflows || [] },
                        { name: 'Outflows', type: 'column', data: this.chartData.outflows || [] },
                        { name: 'Closing Balance', type: 'line', data: this.chartData.closing_balances || [] }
                    ],
                    labels: this.chartData.categories || []
                });
            }

            if (this.donutChartInstance) {
                this.donutChartInstance.updateOptions({
                    series: this.categoryOutflows.map(c => c.amount),
                    labels: this.categoryOutflows.map(c => c.name),
                    colors: this.categoryOutflows.map(c => c.color)
                });
            }

            if (this.closingBarChartInstance) {
                const defaultLabels = (this.filters.financial_year && this.filters.financial_year !== 'All')
                    ? ['Current', 'Apr - Jul', 'Aug - Nov', 'Dec - Mar']
                    : ['Current', '30 Days', '60 Days', '90 Days'];
                const miniLabels = defaultLabels.slice(0, (this.chartData.closing_bars || []).length);
                this.closingBarChartInstance.updateOptions({
                    series: [{
                        name: 'Projected Liquidity',
                        data: this.chartData.closing_bars || []
                    }],
                    xaxis: {
                        categories: miniLabels
                    }
                });
            }
        },

        renderComboChart() {
            const chartEl = document.querySelector('#cashflowComboChart');
            if (!chartEl) return;
            if (this.comboChartInstance) this.comboChartInstance.destroy();

            const options = {
                series: [
                    {
                        name: 'Inflows',
                        type: 'column',
                        data: this.chartData.inflows || []
                    },
                    {
                        name: 'Outflows',
                        type: 'column',
                        data: this.chartData.outflows || []
                    },
                    {
                        name: 'Closing Balance',
                        type: 'line',
                        data: this.chartData.closing_balances || []
                    }
                ],
                chart: {
                    height: 330,
                    type: 'line',
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                stroke: {
                    width: [0, 0, 3],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 4
                    }
                },
                colors: ['#10b981', '#f43f5e', '#2563eb'],
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [2],
                    formatter: (val) => this.formatCompact(val),
                    style: {
                        fontSize: '11px',
                        fontWeight: '700',
                        colors: ['#1e293b']
                    },
                    background: {
                        enabled: true,
                        borderRadius: 4,
                        padding: 4,
                        opacity: 0.9,
                        borderColor: '#93c5fd'
                    }
                },
                labels: this.chartData.categories || [],
                xaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: '#64748b'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => this.formatCompact(val),
                        style: {
                            fontSize: '10px',
                            fontWeight: 600,
                            colors: '#64748b'
                        }
                    }
                },
                legend: { show: false },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (val) => this.currency + ' ' + this.formatMoney(val)
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                }
            };

            this.comboChartInstance = new ApexCharts(chartEl, options);
            this.comboChartInstance.render();
        },

        renderDonutChart() {
            const chartEl = document.querySelector('#outflowDonutChart');
            if (!chartEl) return;
            if (this.donutChartInstance) this.donutChartInstance.destroy();

            const seriesData = this.categoryOutflows.map(c => c.amount);
            const labelsData = this.categoryOutflows.map(c => c.name);
            const colorsData = this.categoryOutflows.map(c => c.color);

            const options = {
                series: seriesData,
                labels: labelsData,
                colors: colorsData,
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    color: '#64748b'
                                },
                                value: {
                                    show: true,
                                    fontSize: '13px',
                                    fontWeight: 900,
                                    color: '#0f172a',
                                    formatter: (val) => this.formatCompact(val)
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total Outflows',
                                    fontSize: '10px',
                                    fontWeight: 700,
                                    color: '#64748b',
                                    formatter: (w) => {
                                        const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return this.formatCompact(sum);
                                    }
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
                        formatter: (val) => this.currency + ' ' + this.formatMoney(val)
                    }
                }
            };

            this.donutChartInstance = new ApexCharts(chartEl, options);
            this.donutChartInstance.render();
        },

        renderClosingBarChart() {
            const chartEl = document.querySelector('#closingBalanceMiniChart');
            if (!chartEl) return;
            if (this.closingBarChartInstance) this.closingBarChartInstance.destroy();

            const options = {
                series: [{
                    name: 'Projected Liquidity',
                    data: this.chartData.closing_bars || []
                }],
                chart: {
                    type: 'bar',
                    height: 160,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        borderRadius: 4,
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                colors: ['#3b82f6', '#0ea5e9', '#06b6d4', '#10b981'],
                dataLabels: {
                    enabled: true,
                    formatter: (val) => this.formatCompact(val),
                    offsetY: -20,
                    style: {
                        fontSize: '9.5px',
                        fontWeight: 700,
                        colors: ['#334155']
                    }
                },
                xaxis: {
                    categories: ['Current', '30 Days', '60 Days', '90 Days'],
                    labels: {
                        style: {
                            fontSize: '9.5px',
                            fontWeight: 600,
                            colors: '#64748b'
                        }
                    }
                },
                yaxis: {
                    show: false
                },
                legend: { show: false },
                grid: { show: false },
                tooltip: {
                    y: {
                        formatter: (val) => this.currency + ' ' + this.formatMoney(val)
                    }
                }
            };

            this.closingBarChartInstance = new ApexCharts(chartEl, options);
            this.closingBarChartInstance.render();
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('Excel generation library is still loading. Please try again.');
                return;
            }

            const activeProjName = (this.filters.project_id && this.filters.project_id !== 'all'
                ? this.getProjectLabel()
                : (this.allProjects[0]?.name || 'Tabasco Hindustan Infra Developers Pvt. Ltd')
            ).toUpperCase();

            const workbook = new ExcelJS.Workbook();
            workbook.creator = activeProjName;
            workbook.created = new Date();

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
            const totalBorder = {
                top: { style: 'medium', color: { argb: 'FF1E293B' } },
                bottom: { style: 'medium', color: { argb: 'FF1E293B' } },
                left: { style: 'thin', color: { argb: 'FF475569' } },
                right: { style: 'thin', color: { argb: 'FF475569' } }
            };
            const whiteFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
            const titleFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
            const subFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
            const secFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
            const colHdrFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };

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
                tCell.font = { name: 'Calibri', size: 15, bold: true, color: { argb: 'FFFFFFFF' } };
                tCell.fill = titleFill;
                tCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 32;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(2).getCell(c).border = slateBorder;

                // Row 3: Subtitle Banner (#007398 Ocean Teal Blue)
                sheet.mergeCells(`A3:${lastColLetter}3`);
                const sCell = sheet.getCell('A3');
                sCell.value = subtitleText;
                sCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                sCell.fill = subFill;
                sCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 24;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(3).getCell(c).border = slateBorder;

                // Row 4: Section Banner (#006039 Deep Forest Green)
                sheet.mergeCells(`A4:${lastColLetter}4`);
                const secCell = sheet.getCell('A4');
                secCell.value = sectionText;
                secCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                secCell.fill = secFill;
                secCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(4).height = 24;
                for (let c = 1; c <= totalCols; c++) sheet.getRow(4).getCell(c).border = slateBorder;

                // Row 5: Middle Empty Spacer
                sheet.mergeCells(`A5:${lastColLetter}5`);
                const sp2 = sheet.getCell('A5');
                sp2.value = '';
                sp2.fill = whiteFill;
                sheet.getRow(5).height = 14;
            };

            // ── SHEET 1: CASH FLOW FORECAST ──
            const summarySheet = workbook.addWorksheet('Cash Flow Forecast', {
                views: [{ state: 'frozen', ySplit: 6, showGridLines: true }]
            });

            summarySheet.columns = [
                { width: 8 },
                { width: 42 },
                { width: 26 },
                { width: 26 },
                { width: 26 },
                { width: 28 }
            ];

            addExecutiveBanner(
                summarySheet,
                'F',
                6,
                `${activeProjName} - CASH FLOW & LIQUIDITY FORECAST REPORT`,
                `As On Date: ${this.filters.as_on_date}   |   Financial Year: ${this.getSelectedFyName()}   |   Horizon: Next ${this.filters.period} Days   |   Current Cash: ${this.currency} ${this.formatMoney(this.kpis.current_liquid_cash)}`,
                `LIQUIDITY PROJECTION MATRIX (${this.currency})`
            );

            // Row 6: Table Headers (#34495E Navy Slate Blue)
            const tableHeaders = ['#', 'Description', this.periods.p1Label, this.periods.p2Label, this.periods.p3Label, `Total (${this.filters.period} Days)`];
            const headerRow = summarySheet.getRow(6);
            headerRow.values = tableHeaders;
            headerRow.height = 30;
            headerRow.eachCell({ includeEmpty: true }, (cell) => {
                cell.fill = colHdrFill;
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                cell.border = slateBorder;
            });

            let sRowIdx = 7;

            // Inflows Section Subheader
            const inHeader = summarySheet.getRow(sRowIdx);
            inHeader.values = ['+', 'PROJECTED INFLOWS', '', '', '', ''];
            inHeader.height = 24;
            inHeader.eachCell({ includeEmpty: true }, (cell, col) => {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFECFDF5' } };
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF065F46' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = dataBorder;
            });
            sRowIdx++;

            (this.summaryTable.inflows || []).forEach((item, idx) => {
                const r = summarySheet.getRow(sRowIdx);
                r.values = [
                    idx + 1,
                    item.name,
                    Number(item.p1 || 0),
                    Number(item.p2 || 0),
                    Number(item.p3 || 0),
                    Number(item.total || 0)
                ];
                r.height = 24;
                const rowBg = (idx % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';
                r.eachCell({ includeEmpty: true }, (cell, col) => {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                    cell.border = dataBorder;
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.font = {
                        name: 'Calibri',
                        size: 11,
                        bold: (col === 2 || col === 6),
                        color: { argb: (col === 6 ? 'FF008000' : 'FF000000') }
                    };
                    if (col >= 3 && col <= 6) cell.numFmt = '#,##0.00';
                });
                sRowIdx++;
            });

            const inTotal = summarySheet.getRow(sRowIdx);
            inTotal.values = [
                '',
                'Total Projected Inflows',
                Number(this.summaryTable.total_inflows?.p1 || 0),
                Number(this.summaryTable.total_inflows?.p2 || 0),
                Number(this.summaryTable.total_inflows?.p3 || 0),
                Number(this.summaryTable.total_inflows?.total || 0)
            ];
            inTotal.height = 26;
            inTotal.eachCell({ includeEmpty: true }, (cell, col) => {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFD1FAE5' } };
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF065F46' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = slateBorder;
                if (col >= 3 && col <= 6) cell.numFmt = '#,##0.00';
            });
            sRowIdx++;

            // Outflows Section Subheader
            const outHeader = summarySheet.getRow(sRowIdx);
            outHeader.values = ['-', 'PROJECTED OUTFLOWS', '', '', '', ''];
            outHeader.height = 24;
            outHeader.eachCell({ includeEmpty: true }, (cell) => {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF1F2' } };
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF9F1239' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = dataBorder;
            });
            sRowIdx++;

            (this.summaryTable.outflows || []).forEach((item, idx) => {
                const r = summarySheet.getRow(sRowIdx);
                r.values = [
                    idx + 1,
                    item.name,
                    Number(item.p1 || 0),
                    Number(item.p2 || 0),
                    Number(item.p3 || 0),
                    Number(item.total || 0)
                ];
                r.height = 24;
                const rowBg = (idx % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';
                r.eachCell({ includeEmpty: true }, (cell, col) => {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                    cell.border = dataBorder;
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.font = {
                        name: 'Calibri',
                        size: 11,
                        bold: (col === 2 || col === 6),
                        color: { argb: (col === 6 ? 'FFB91C1C' : 'FF000000') }
                    };
                    if (col >= 3 && col <= 6) cell.numFmt = '#,##0.00';
                });
                sRowIdx++;
            });

            const outTotal = summarySheet.getRow(sRowIdx);
            outTotal.values = [
                '',
                'Total Projected Outflows',
                Number(this.summaryTable.total_outflows?.p1 || 0),
                Number(this.summaryTable.total_outflows?.p2 || 0),
                Number(this.summaryTable.total_outflows?.p3 || 0),
                Number(this.summaryTable.total_outflows?.total || 0)
            ];
            outTotal.height = 26;
            outTotal.eachCell({ includeEmpty: true }, (cell, col) => {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFE4E6' } };
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF9F1239' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = slateBorder;
                if (col >= 3 && col <= 6) cell.numFmt = '#,##0.00';
            });
            sRowIdx++;

            // Footer Row: Net Projected Cash Flow (#2C3E50 Dark Slate Navy)
            const netRow = summarySheet.getRow(sRowIdx);
            netRow.height = 36;
            summarySheet.getCell(`A${sRowIdx}`).value = 'NET PROJECTED CASH FLOW (SURPLUS / DEFICIT)';
            summarySheet.getCell(`C${sRowIdx}`).value = Number(this.summaryTable.net_cash_flow?.p1 || 0);
            summarySheet.getCell(`D${sRowIdx}`).value = Number(this.summaryTable.net_cash_flow?.p2 || 0);
            summarySheet.getCell(`E${sRowIdx}`).value = Number(this.summaryTable.net_cash_flow?.p3 || 0);
            summarySheet.getCell(`F${sRowIdx}`).value = Number(this.summaryTable.net_cash_flow?.total || 0);
            summarySheet.mergeCells(`A${sRowIdx}:B${sRowIdx}`);
            for (let c = 1; c <= 6; c++) {
                const cell = netRow.getCell(c);
                cell.fill = titleFill;
                cell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = totalBorder;
                if (c >= 3 && c <= 6) cell.numFmt = '#,##0.00';
            }

            // ── SHEET 2: UPCOMING TRANSACTIONS ──
            const txSheet = workbook.addWorksheet('Upcoming Transactions', {
                views: [{ state: 'frozen', ySplit: 6, showGridLines: true }]
            });

            txSheet.columns = [
                { width: 8 },
                { width: 16 },
                { width: 24 },
                { width: 20 },
                { width: 28 },
                { width: 42 },
                { width: 22 },
                { width: 22 }
            ];

            const txList = this.filteredTransactions || [];

            addExecutiveBanner(
                txSheet,
                'H',
                8,
                `${activeProjName} - UPCOMING KEY TRANSACTIONS REPORT`,
                `Financial Year: ${this.getSelectedFyName()}   |   Period: Next ${this.filters.period} Days   |   Currency: ${this.currency}   |   Records: ${txList.length}`,
                'TRANSACTION DETAILS'
            );

            const txHeaders = ['#', 'Date', 'Type', 'Reference No.', 'Party Name', 'Description', `Amount (${this.currency})`, 'Category'];
            const txHeaderRow = txSheet.getRow(6);
            txHeaderRow.values = txHeaders;
            txHeaderRow.height = 30;
            txHeaderRow.eachCell({ includeEmpty: true }, (cell) => {
                cell.fill = colHdrFill;
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                cell.border = slateBorder;
            });

            let tRowIdx = 7;
            let txGrandTotal = 0;
            txList.forEach((tx, idx) => {
                const amt = parseFloat(tx.amount) || 0;
                txGrandTotal += amt;
                const isInflow = (tx.category || '').toLowerCase().includes('inflow');

                const r = txSheet.getRow(tRowIdx);
                r.values = [
                    idx + 1,
                    tx.date,
                    tx.type,
                    tx.ref_no,
                    tx.party_name,
                    tx.description,
                    amt,
                    (tx.category || '').toUpperCase()
                ];
                r.height = 24;
                const rowBg = (idx % 2 === 0) ? 'FFF0F8FF' : 'FFFFFFFF';

                r.eachCell({ includeEmpty: true }, (cell, col) => {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                    cell.border = dataBorder;
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };

                    if (col === 5) {
                        cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF000000' } };
                    } else if (col === 7) {
                        cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: isInflow ? 'FF008000' : 'FF000000' } };
                        cell.numFmt = '#,##0.00';
                    } else if (col === 8) {
                        cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: isInflow ? 'FF008000' : 'FF1D4ED8' } };
                    } else {
                        cell.font = { name: 'Calibri', size: 11, color: { argb: 'FF000000' } };
                    }
                });
                tRowIdx++;
            });

            // Footer Row: Total Scheduled Transactions (#2C3E50 Dark Slate Navy)
            const txTotRow = txSheet.getRow(tRowIdx);
            txTotRow.height = 36;
            txSheet.getCell(`A${tRowIdx}`).value = `TOTAL SCHEDULED TRANSACTIONS (${txList.length} ENTRIES)`;
            txSheet.getCell(`G${tRowIdx}`).value = txGrandTotal;
            txSheet.getCell(`H${tRowIdx}`).value = '';
            txSheet.mergeCells(`A${tRowIdx}:F${tRowIdx}`);
            for (let c = 1; c <= 8; c++) {
                const cell = txTotRow.getCell(c);
                cell.fill = titleFill;
                cell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = totalBorder;
                if (c === 7) cell.numFmt = '#,##0.00';
            }

            // Download file
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Liquidity_Forecast_Report.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
}
</script>

</x-erp-layout>
