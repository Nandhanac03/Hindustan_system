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
                        <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">ACCOUNTS & TREASURY</span>
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
                <div class="text-[10px] text-blue-600 font-bold mt-1">Active Bank Accounts</div>
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
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">0 - 30 DAYS</span>
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
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">31 - 60 DAYS</span>
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
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">61 - 90 DAYS</span>
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
                       placeholder="Search transaction party name, reference or description..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="txSearch">
                    <button type="button" @click="txSearch = ''; currentPage = 1" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
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
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
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
    <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
        
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
                            @click="activeTxTab = tab.id; currentPage = 1"
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
        <div class="p-4 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-bold text-slate-600">
            <span x-text="'Showing ' + (filteredTransactions.length ? ((currentPage - 1) * perPage + 1) : 0) + ' to ' + Math.min(currentPage * perPage, filteredTransactions.length) + ' of ' + filteredTransactions.length + ' scheduled transactions'"></span>
            
            <div class="flex items-center gap-2">
                <button type="button"
                        @click="if (currentPage > 1) currentPage--"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-300'"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition">
                    &lsaquo; Prev
                </button>
                <span class="text-xs text-slate-500 font-medium" x-text="'Page ' + currentPage + ' of ' + Math.max(1, totalPages)"></span>
                <button type="button"
                        @click="if (currentPage < totalPages) currentPage++"
                        :disabled="currentPage >= totalPages"
                        :class="currentPage >= totalPages ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-300'"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition">
                    Next &rsaquo;
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
            project_id: @json((string)$selectedProjectId ?? 'all')
        },
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
        perPage: 10,
        transactionTabs: [
            { id: 'all', label: 'All' },
            { id: 'Customer Receipt', label: 'Customer Receipts' },
            { id: 'Contractor Payment', label: 'Contractor Payments' },
            { id: 'Vendor Payment', label: 'Vendor Payments' },
            { id: 'Bank Loan EMI', label: 'Bank Loan EMIs' },
            { id: 'Site Expense', label: 'Site Expenses' }
        ],
        comboChartInstance: null,
        donutChartInstance: null,
        closingBarChartInstance: null,

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

        selectProject(id) {
            this.filters.project_id = String(id);
            this.applyDataset();
        },

        selectPeriod(period) {
            this.filters.period = String(period);
            this.applyPeriodFilter();
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
            // Period filter adjustment for 30 / 60 / 90 days run-rate
            const days = parseInt(this.filters.period, 10) || 90;
            const ds = this.projectDatasets[this.filters.project_id] || this.projectDatasets['all'] || {};
            if (!ds.kpis) return;

            let scale = 1.0;
            if (days === 30) scale = 0.33;
            else if (days === 60) scale = 0.67;

            this.kpis = {
                current_liquid_cash: ds.kpis.current_liquid_cash,
                total_projected_inflows: ds.kpis.total_projected_inflows * scale,
                total_projected_outflows: ds.kpis.total_projected_outflows * scale,
                net_liquidity: ds.kpis.current_liquid_cash + ((ds.kpis.total_projected_inflows - ds.kpis.total_projected_outflows) * scale),
                status: (ds.kpis.current_liquid_cash + ((ds.kpis.total_projected_inflows - ds.kpis.total_projected_outflows) * scale)) >= 0 ? 'Surplus' : 'Deficit'
            };

            this.updateCharts();
        },

        resetFilters() {
            this.filters.project_id = String(this.defaultProjectId);
            this.filters.period = '90';
            this.filters.as_on_date = this.defaultAsOnDate;
            this.txSearch = '';
            this.activeTxTab = 'all';
            this.currentPage = 1;
            this.applyDataset();
        },

        get filteredTransactions() {
            let list = this.allTransactions;
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
                this.closingBarChartInstance.updateOptions({
                    series: [{
                        name: 'Projected Liquidity',
                        data: this.chartData.closing_bars || []
                    }]
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

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'Tabasco ERP';
            workbook.created = new Date();

            // ── SHEET 1: CASH FLOW SUMMARY ──
            const summarySheet = workbook.addWorksheet('Cash Flow Forecast');
            summarySheet.views = [{ showGridLines: true }];

            // Global Header Style
            const headerFill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'FF534E47' }
            };
            const headerFont = {
                name: 'Segoe UI',
                size: 9.5,
                bold: true,
                color: { argb: 'FFFFFFFF' }
            };
            const borderThin = {
                top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                bottom: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
            };

            // Title Banner
            summarySheet.mergeCells('A1:F1');
            const titleRow = summarySheet.getCell('A1');
            titleRow.value = 'TABASCO REAL ESTATE ERP - CASH FLOW & LIQUIDITY FORECAST';
            titleRow.font = { name: 'Segoe UI', size: 13, bold: true, color: { argb: 'FF1E293B' } };
            titleRow.alignment = { horizontal: 'center', vertical: 'middle' };
            summarySheet.getRow(1).height = 28;

            summarySheet.mergeCells('A2:F2');
            const metaRow = summarySheet.getCell('A2');
            metaRow.value = `As On Date: ${this.filters.as_on_date} | Forecast Horizon: Next ${this.filters.period} Days | Currency: ${this.currency}`;
            metaRow.font = { name: 'Segoe UI', size: 9, italic: true, color: { argb: 'FF64748B' } };
            metaRow.alignment = { horizontal: 'center', vertical: 'middle' };
            summarySheet.getRow(2).height = 18;

            // Blank Row
            summarySheet.addRow([]);

            // KPI Header Summary
            summarySheet.mergeCells('A4:C4');
            summarySheet.getCell('A4').value = `Current Liquid Cash: ${this.currency} ${this.formatMoney(this.kpis.current_liquid_cash)}`;
            summarySheet.getCell('A4').font = { bold: true, color: { argb: 'FF065F46' } };

            summarySheet.mergeCells('D4:F4');
            summarySheet.getCell('D4').value = `Net Liquidity (${this.filters.period} Days): ${this.currency} ${this.formatMoney(this.kpis.net_liquidity)} (${this.kpis.status})`;
            summarySheet.getCell('D4').font = { bold: true, color: { argb: 'FF8A7522' } };
            summarySheet.getCell('D4').alignment = { horizontal: 'right' };

            // Summary Table Header
            const tableHeaders = ['#', 'Description', this.periods.p1Label, this.periods.p2Label, this.periods.p3Label, `Total (${this.filters.period} Days)`];
            const headerRow = summarySheet.addRow(tableHeaders);
            headerRow.height = 24;
            headerRow.eachCell((cell) => {
                cell.fill = headerFill;
                cell.font = headerFont;
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            });

            // Inflows
            const inHeader = summarySheet.addRow(['-', 'PROJECTED INFLOWS', '', '', '', '']);
            inHeader.font = { bold: true, color: { argb: 'FF065F46' } };
            inHeader.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFECFDF5' } };

            (this.summaryTable.inflows || []).forEach((item, idx) => {
                const r = summarySheet.addRow([
                    idx + 1,
                    item.name,
                    item.p1,
                    item.p2,
                    item.p3,
                    item.total
                ]);
                r.getCell(1).alignment = { horizontal: 'center' };
                r.getCell(2).alignment = { horizontal: 'left' };
                for (let c = 3; c <= 6; c++) {
                    r.getCell(c).alignment = { horizontal: 'right' };
                    r.getCell(c).numFmt = '#,##0.00';
                }
            });

            const inTotal = summarySheet.addRow([
                '',
                'Total Projected Inflows',
                this.summaryTable.total_inflows?.p1 || 0,
                this.summaryTable.total_inflows?.p2 || 0,
                this.summaryTable.total_inflows?.p3 || 0,
                this.summaryTable.total_inflows?.total || 0
            ]);
            inTotal.font = { bold: true, color: { argb: 'FF065F46' } };
            inTotal.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFD1FAE5' } };
            for (let c = 3; c <= 6; c++) {
                inTotal.getCell(c).numFmt = '#,##0.00';
                inTotal.getCell(c).alignment = { horizontal: 'right' };
            }

            // Outflows
            const outHeader = summarySheet.addRow(['-', 'PROJECTED OUTFLOWS', '', '', '', '']);
            outHeader.font = { bold: true, color: { argb: 'FF9F1239' } };
            outHeader.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF1F2' } };

            (this.summaryTable.outflows || []).forEach((item, idx) => {
                const r = summarySheet.addRow([
                    idx + 3,
                    item.name,
                    item.p1,
                    item.p2,
                    item.p3,
                    item.total
                ]);
                r.getCell(1).alignment = { horizontal: 'center' };
                r.getCell(2).alignment = { horizontal: 'left' };
                for (let c = 3; c <= 6; c++) {
                    r.getCell(c).alignment = { horizontal: 'right' };
                    r.getCell(c).numFmt = '#,##0.00';
                }
            });

            const outTotal = summarySheet.addRow([
                '',
                'Total Projected Outflows',
                this.summaryTable.total_outflows?.p1 || 0,
                this.summaryTable.total_outflows?.p2 || 0,
                this.summaryTable.total_outflows?.p3 || 0,
                this.summaryTable.total_outflows?.total || 0
            ]);
            outTotal.font = { bold: true, color: { argb: 'FF9F1239' } };
            outTotal.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFE4E6' } };
            for (let c = 3; c <= 6; c++) {
                outTotal.getCell(c).numFmt = '#,##0.00';
                outTotal.getCell(c).alignment = { horizontal: 'right' };
            }

            // Net Cash Flow
            const netRow = summarySheet.addRow([
                '★',
                'Net Projected Cash Flow (Surplus / Deficit)',
                this.summaryTable.net_cash_flow?.p1 || 0,
                this.summaryTable.net_cash_flow?.p2 || 0,
                this.summaryTable.net_cash_flow?.p3 || 0,
                this.summaryTable.net_cash_flow?.total || 0
            ]);
            netRow.font = { bold: true, size: 10, color: { argb: 'FF1E293B' } };
            netRow.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF1F5F9' } };
            for (let c = 3; c <= 6; c++) {
                netRow.getCell(c).numFmt = '#,##0.00';
                netRow.getCell(c).alignment = { horizontal: 'right' };
            }

            // Borders & Column Widths
            summarySheet.columns = [
                { width: 6 },
                { width: 38 },
                { width: 22 },
                { width: 22 },
                { width: 22 },
                { width: 24 }
            ];

            // ── SHEET 2: UPCOMING TRANSACTIONS ──
            const txSheet = workbook.addWorksheet('Upcoming Transactions');
            txSheet.views = [{ showGridLines: true }];

            const txHeaders = ['#', 'Date', 'Type', 'Reference No.', 'Party Name', 'Description', `Amount (${this.currency})`, 'Category'];
            const txHeaderRow = txSheet.addRow(txHeaders);
            txHeaderRow.height = 24;
            txHeaderRow.eachCell((cell) => {
                cell.fill = headerFill;
                cell.font = headerFont;
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            });

            this.allTransactions.forEach((tx, idx) => {
                const r = txSheet.addRow([
                    idx + 1,
                    tx.date,
                    tx.type,
                    tx.ref_no,
                    tx.party_name,
                    tx.description,
                    parseFloat(tx.amount) || 0,
                    tx.category
                ]);
                r.getCell(1).alignment = { horizontal: 'center' };
                r.getCell(2).alignment = { horizontal: 'center' };
                r.getCell(3).alignment = { horizontal: 'center' };
                r.getCell(4).alignment = { horizontal: 'center' };
                r.getCell(5).alignment = { horizontal: 'left' };
                r.getCell(6).alignment = { horizontal: 'left' };
                r.getCell(7).alignment = { horizontal: 'right' };
                r.getCell(7).numFmt = '#,##0.00';
                r.getCell(8).alignment = { horizontal: 'center' };

                r.eachCell(cell => cell.border = borderThin);
            });

            txSheet.columns = [
                { width: 6 },
                { width: 14 },
                { width: 20 },
                { width: 18 },
                { width: 28 },
                { width: 38 },
                { width: 18 },
                { width: 18 }
            ];

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
