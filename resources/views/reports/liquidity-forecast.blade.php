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
    font-size: 0.68rem !important;
    font-weight: 800 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
}
</style>

<div class="max-w-[1850px] mx-auto space-y-5" 
     x-data="liquidityForecastApp()">

    {{-- ── 1. EXECUTIVE TOP HEADER BANNER ── --}}
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
                    <div class="flex items-center gap-2.5 flex-wrap">
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

            {{-- Quick Metadata Badges --}}
            <div class="flex items-center gap-3 text-right">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">As On Date</span>
                    <span class="text-xs font-mono font-black text-slate-900" x-text="filters.as_on_date">{{ $asOnDate }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Forecast Horizon</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="'Next ' + filters.period + ' Days (' + effectiveKpis.status + ')'">Next 90 Days</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 6 FINANCIAL KPI METRIC CARDS ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        
        {{-- Card 1: TOTAL LIQUID CASH (Split Free Cash vs RERA/Escrow - Requirement #5) --}}
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
                     :title="currency + ' ' + formatMoney(effectiveKpis.current_liquid_cash)"
                     x-text="currency + ' ' + formatMoney(effectiveKpis.current_liquid_cash)"></div>
                <div class="text-[10px] text-blue-700 font-bold mt-1 truncate"
                     :title="'Free Cash: ' + currency + ' ' + formatMoney(effectiveKpis.free_cash) + ' | RERA/Escrow: ' + currency + ' ' + formatMoney(effectiveKpis.restricted_escrow_cash)"
                     x-text="'Free: ' + currency + ' ' + formatCompact(effectiveKpis.free_cash) + ' | RERA: ' + currency + ' ' + formatCompact(effectiveKpis.restricted_escrow_cash)"></div>
            </div>
        </div>

        {{-- Card 2: TOTAL INFLOWS (Forward Forecast + Past Due Trigger - Requirement #1) --}}
        <div @click="openDrillDown('all', 'overdue', 'Overdue / Past Due Backlog')"
             class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer"
             title="Click to inspect Overdue / Past Due backlog">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider" x-text="'INFLOWS (' + filters.period + 'D)'"></span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(effectiveKpis.total_projected_inflows)"
                     x-text="currency + ' ' + formatMoney(effectiveKpis.total_projected_inflows)"></div>
                <div class="text-[10px] text-[#8a7522] font-bold mt-1 flex items-center justify-between">
                    <span x-text="'Past Due: ' + currency + ' ' + formatCompact(effectiveKpis.overdue_inflows)"></span>
                    <span class="underline text-[9px]">View</span>
                </div>
            </div>
        </div>

        {{-- Card 3: 0 - 30 DAYS NET (Clickable to Future Next 30d Drill-Down) --}}
        <div @click="openDrillDown('all', 'p1', 'Next 30 Days (Future Scheduled)')"
             class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer"
             title="Click to inspect Next 30 Days transactions">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">0 - 30 DAYS NET</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black tracking-tight truncate"
                     :class="effectiveSummary.net_cash_flow.p1 >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                     :title="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p1)"
                     x-text="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p1)"></div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1">Strict Future Net (W1–W4)</div>
            </div>
        </div>

        {{-- Card 4: 31 - 60 DAYS NET --}}
        <div @click="openDrillDown('all', 'p2', '31 - 60 Days (Future Scheduled)')"
             class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-amber-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer"
             title="Click to inspect 31-60 Days transactions">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">31 - 60 DAYS NET</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black tracking-tight truncate"
                     :class="effectiveSummary.net_cash_flow.p2 >= 0 ? 'text-amber-800' : 'text-rose-700'"
                     :title="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p2)"
                     x-text="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p2)"></div>
                <div class="text-[10px] text-amber-600 font-bold mt-1">Days 31–60 Net Run-rate</div>
            </div>
        </div>

        {{-- Card 5: 61 - 90 DAYS NET --}}
        <div @click="openDrillDown('all', 'p3', '61 - 90 Days (Future Scheduled)')"
             class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-orange-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer"
             title="Click to inspect 61-90 Days transactions">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">61 - 90 DAYS NET</span>
                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black tracking-tight truncate"
                     :class="effectiveSummary.net_cash_flow.p3 >= 0 ? 'text-orange-800' : 'text-rose-700'"
                     :title="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p3)"
                     x-text="currency + ' ' + formatMoney(effectiveSummary.net_cash_flow.p3)"></div>
                <div class="text-[10px] text-orange-600 font-bold mt-1">Days 61–90 Net Run-rate</div>
            </div>
        </div>

        {{-- Card 6: TOTAL OUTFLOWS (Sub-label shows Top Outflow Category & Share - Requirement #2) --}}
        <div @click="openDrillDown('all', 'overdue', 'Overdue / Past Due Backlog')"
             class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer"
             title="Click to inspect Overdue Payables & Outflows">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider" x-text="'OUTFLOWS (' + filters.period + 'D)'"></span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(effectiveKpis.total_projected_outflows)"
                     x-text="currency + ' ' + formatMoney(effectiveKpis.total_projected_outflows)"></div>
                <div class="text-[10px] text-rose-600 font-bold mt-1 truncate"
                     :title="'Top: ' + effectiveKpis.top_outflow_name + ' (' + effectiveKpis.top_outflow_pct + '%) | Past Due Outflows: ' + currency + ' ' + formatMoney(effectiveKpis.overdue_outflows)"
                     x-text="'Top: ' + effectiveKpis.top_outflow_name + ' (' + effectiveKpis.top_outflow_pct + '%)'"></div>
            </div>
        </div>

    </div>

    {{-- ── LOW CASH BUFFER ALERT BANNER (Requirement #7) ── --}}
    <div x-show="lowBalanceAlerts.length > 0"
         x-cloak
         class="bg-rose-50 border border-rose-300/80 rounded-2xl p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
        <div class="flex items-start sm:items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs font-black text-rose-900 uppercase tracking-wider">Low Cash Buffer Threshold Alert</div>
                <div class="text-xs text-rose-800 font-medium mt-0.5" x-text="lowBalanceAlerts.join(' • ')"></div>
            </div>
        </div>
        <span class="text-[10px] font-mono font-black bg-rose-600 text-white px-2.5 py-1 rounded-lg uppercase tracking-wider shrink-0">
            Min Buffer: <span x-text="currency + ' ' + formatCompact(minBuffer)"></span>
        </span>
    </div>

    {{-- ── 3. ZERO-RELOAD FILTER & TREASURY CONTROLS BAR ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all print:hidden">
        <div class="flex items-center gap-2.5 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Project Filter Dropdown (Zero Reload) --}}
            <div class="relative min-w-[175px] flex-[1.2]" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="truncate text-xs font-bold text-slate-900" x-text="getProjectLabel()">All Projects</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[230px] w-max">
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

            {{-- 2. Forecast Period Dropdown (Zero Reload) --}}
            <div class="relative min-w-[130px] flex-[0.8]" x-data="{ open: false }" :class="open ? 'z-30' : ''">
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
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[155px]">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectPeriod('90'); open = false" class="erp-dropdown-option" :class="filters.period === '90' ? 'selected' : ''"><span>Next 90 Days</span></div>
                        <div @click="selectPeriod('60'); open = false" class="erp-dropdown-option" :class="filters.period === '60' ? 'selected' : ''"><span>Next 60 Days</span></div>
                        <div @click="selectPeriod('30'); open = false" class="erp-dropdown-option" :class="filters.period === '30' ? 'selected' : ''"><span>Next 30 Days</span></div>
                    </div>
                </div>
            </div>

            {{-- 3. As On Date (AJAX fetch without reload) --}}
            <div class="relative min-w-[125px] flex-[0.75]" title="As On Date (DD/MM/YYYY) - press Enter or blur to recalculate">
                <input type="text"
                       x-model="filters.as_on_date"
                       @change="fetchByDate()"
                       @keydown.enter.prevent="fetchByDate()"
                       placeholder="DD/MM/YYYY"
                       class="w-full erp-search-input px-3 text-xs font-bold text-slate-800">
            </div>

            {{-- 4. Min Cash Buffer Setting (Requirement #7) --}}
            <div class="relative min-w-[165px] flex-[0.95]" title="Minimum Free Cash Buffer Threshold (₹)">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider">Buffer:</span>
                </div>
                <input type="number"
                       step="50000"
                       min="0"
                       x-model.number="minBuffer"
                       @input.debounce.200ms="updateCharts()"
                       class="w-full erp-search-input pl-14 pr-2.5 text-xs font-mono font-bold text-slate-900">
            </div>

            {{-- 5. Pending Cheque Collection Confidence Toggle (Requirement #8) --}}
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0" title="Pending Cheque Collection Confidence">
                <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider px-2 hidden 2xl:inline">Cheque Conf:</span>
                <button type="button"
                        @click="setChequeConfidence('100')"
                        :class="chequeConfidence === '100' ? 'bg-[#a38c29] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer">
                    100%
                </button>
                <button type="button"
                        @click="setChequeConfidence('80')"
                        :class="chequeConfidence === '80' ? 'bg-[#a38c29] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer">
                    80%
                </button>
                <button type="button"
                        @click="setChequeConfidence('delayed')"
                        :class="chequeConfidence === 'delayed' ? 'bg-[#a38c29] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer">
                    Delayed
                </button>
            </div>

            {{-- 6. Action Buttons (RESET FILTERS + EXPORT EXCEL) --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" 
                        @click="resetFilters()"
                        class="theme-btn h-[38px] px-3.5 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap"
                        title="Reset All Filters">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>RESET FILTERS</span>
                </button>

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
        
        {{-- Left 2/3 (8 cols): Projected Cash Flow Combo Chart with Weekly / 90-Day Toggle & Buffer Threshold Line --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider"
                        x-text="chartGranularity === 'weekly' ? 'First 30 Days Weekly Cash Flow (W1–W4)' : 'Projected Cash Flow (Next ' + filters.period + ' Days)'"></h3>
                    <p class="text-xs text-slate-400 font-medium">Forward cash movements from As On Date with closing trajectory &amp; Min Buffer line</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- View Mode Toggle (Requirement #9) --}}
                    <div class="flex items-center bg-slate-100 p-0.5 rounded-xl border border-slate-200">
                        <button type="button"
                                @click="chartGranularity = 'buckets'; updateCharts()"
                                :class="chartGranularity === 'buckets' ? 'bg-white text-slate-900 shadow-2xs font-black' : 'text-slate-500 font-bold'"
                                class="px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer">
                            90-Day Buckets
                        </button>
                        <button type="button"
                                @click="chartGranularity = 'weekly'; updateCharts()"
                                :class="chartGranularity === 'weekly' ? 'bg-[#a38c29] text-white shadow-2xs font-black' : 'text-slate-500 font-bold'"
                                class="px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer">
                            Next 30d Weekly
                        </button>
                    </div>

                    <div class="flex items-center gap-2.5 text-[11px] font-bold">
                        <span class="flex items-center gap-1 text-slate-600"><span class="w-2.5 h-2.5 rounded-xs bg-[#10b981]"></span>Inflows</span>
                        <span class="flex items-center gap-1 text-slate-600"><span class="w-2.5 h-2.5 rounded-xs bg-[#f43f5e]"></span>Outflows</span>
                        <span class="flex items-center gap-1 text-slate-600"><span class="w-2.5 h-1 bg-[#2563eb] rounded-full"></span>Closing</span>
                    </div>
                </div>
            </div>

            <div class="w-full mt-2">
                <div id="cashflowComboChart" class="w-full min-h-[320px]"></div>
            </div>
        </div>

        {{-- Right 1/3 (4 cols): Outflows by Category Donut Chart --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Outflows by Category</h3>
                    <p class="text-xs text-slate-400 font-medium">Scheduled 90d commitment share</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 uppercase">Click to Drill</span>
            </div>

            <div class="w-full my-2 flex items-center justify-center">
                <div id="outflowDonutChart" class="w-full max-w-[260px] min-h-[205px]"></div>
            </div>

            {{-- Category Legend List (Clickable to drill into category) --}}
            <div class="space-y-1 pt-2 border-t border-slate-100 max-h-48 overflow-y-auto">
                <template x-for="(cat, idx) in categoryOutflows" :key="idx">
                    <div @click="openDrillDown(cat.key, 'all_combined', cat.name)"
                         class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-amber-50/70 transition-colors cursor-pointer">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-xs shrink-0" :style="'background-color: ' + cat.color"></span>
                            <span class="text-slate-700 font-bold truncate" x-text="cat.name"></span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono font-bold text-slate-900" x-text="currency + ' ' + formatCompact(cat.display_amt)"></span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600 w-11 text-right" x-text="cat.percentage + '%'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    {{-- ── 5. DETAILED BREAKDOWN ROW (CASH FLOW SUMMARY WITH OVERDUE & WEEKLY TOGGLE + BEST/EXPECTED/WORST SCENARIOS & INSIGHTS) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Left 2/3 (8 cols): Cash Flow Summary (₹) Interactive Drill-Down Statement --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs flex flex-col justify-between">
            <div>
                <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider" x-text="'Cash Flow Summary (' + currency + ')'"></h3>
                        <p class="text-xs text-slate-400 font-medium">Strict future periods separated from Overdue / Past Due backlog • Click any value to drill down</p>
                    </div>
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 self-start sm:self-auto">
                        <button type="button"
                                @click="tableGranularity = 'buckets'"
                                :class="tableGranularity === 'buckets' ? 'bg-white text-slate-900 shadow-2xs font-black' : 'text-slate-500 font-bold'"
                                class="px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer">
                            Standard (Overdue + 90d)
                        </button>
                        <button type="button"
                                @click="tableGranularity = 'weekly'"
                                :class="tableGranularity === 'weekly' ? 'bg-[#a38c29] text-white shadow-2xs font-black' : 'text-slate-500 font-bold'"
                                class="px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer">
                            Next 30d Weekly (W1–W4)
                        </button>
                    </div>
                </div>

                {{-- Standard Table (Overdue / Next 30 / 31-60 / 61-90 / Forecast Total) --}}
                <div class="overflow-x-auto" x-show="tableGranularity === 'buckets'">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="global-table-header">
                            <tr>
                                <th class="py-3 px-2.5 text-center w-8">#</th>
                                <th class="py-3 px-3">Description</th>
                                <th @click="openDrillDown('all', 'overdue', 'Overdue / Past Due Items')"
                                    class="py-3 px-3 text-right cursor-pointer hover:underline"
                                    title="Click to inspect all Overdue / Past Due items">
                                    Overdue / Past Due ↗
                                </th>
                                <th @click="openDrillDown('all', 'p1', 'Next 30 Days (Future)')"
                                    class="py-3 px-3 text-right cursor-pointer hover:underline"
                                    x-text="'Next 30d ↗'"></th>
                                <th @click="openDrillDown('all', 'p2', '31 - 60 Days (Future)')"
                                    class="py-3 px-3 text-right cursor-pointer hover:underline"
                                    x-text="'31 - 60d ↗'"></th>
                                <th @click="openDrillDown('all', 'p3', '61 - 90 Days (Future)')"
                                    class="py-3 px-3 text-right cursor-pointer hover:underline"
                                    x-text="'61 - 90d ↗'"></th>
                                <th @click="openDrillDown('all', 'future', 'Total Next 90 Days (Future)')"
                                    class="py-3 px-3.5 text-right cursor-pointer hover:underline">
                                    Forecast (90d) ↗
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            {{-- PROJECTED INFLOWS --}}
                            <tr class="bg-emerald-50/70 border-t border-b border-emerald-200/60 font-black text-emerald-900">
                                <td class="py-2 px-2.5 text-center text-emerald-600 font-mono">-</td>
                                <td colspan="6" class="py-2 px-3 uppercase tracking-wider text-[11px] text-emerald-800">Projected Inflows</td>
                            </tr>
                            <template x-for="(inflow, idx) in (effectiveSummary.inflows || [])" :key="'in-' + idx">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-2.5 text-center font-mono text-slate-400" x-text="idx + 1"></td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800" x-text="inflow.name"></td>
                                    <td @click="inflow.overdue > 0 && openDrillDown(inflow.key, 'overdue', inflow.name + ' (Overdue)')"
                                        :class="inflow.overdue > 0 ? 'cursor-pointer hover:bg-amber-100/70 underline decoration-dotted text-amber-800 font-bold' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono bg-amber-50/30"
                                        x-text="formatMoney(inflow.overdue)"></td>
                                    <td @click="inflow.p1 > 0 && openDrillDown(inflow.key, 'p1', inflow.name + ' (Next 30d)')"
                                        :class="inflow.p1 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(inflow.p1)"></td>
                                    <td @click="inflow.p2 > 0 && openDrillDown(inflow.key, 'p2', inflow.name + ' (31-60d)')"
                                        :class="inflow.p2 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(inflow.p2)"></td>
                                    <td @click="inflow.p3 > 0 && openDrillDown(inflow.key, 'p3', inflow.name + ' (61-90d)')"
                                        :class="inflow.p3 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(inflow.p3)"></td>
                                    <td @click="inflow.total > 0 && openDrillDown(inflow.key, 'future', inflow.name + ' (90d Forecast)')"
                                        :class="inflow.total > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted text-slate-900 font-black' : 'text-slate-400 font-bold'"
                                        class="py-2.5 px-3.5 text-right font-mono bg-slate-50/60"
                                        x-text="formatMoney(inflow.total)"></td>
                                </tr>
                            </template>
                            <tr class="bg-emerald-100/60 font-black text-emerald-950 border-t border-b border-emerald-200">
                                <td class="py-2.5 px-2.5"></td>
                                <td class="py-2.5 px-3 uppercase tracking-wider text-xs font-black text-emerald-900">Total Projected Inflows</td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-amber-800 bg-amber-100/40" x-text="formatMoney(effectiveSummary.total_inflows?.overdue)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.p1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.p2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.p3)"></td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-black text-emerald-900 bg-emerald-200/40" x-text="formatMoney(effectiveSummary.total_inflows?.total)"></td>
                            </tr>

                            {{-- PROJECTED OUTFLOWS --}}
                            <tr class="bg-rose-50/70 border-t border-b border-rose-200/60 font-black text-rose-900">
                                <td class="py-2 px-2.5 text-center text-rose-600 font-mono">-</td>
                                <td colspan="6" class="py-2 px-3 uppercase tracking-wider text-[11px] text-rose-800">Projected Outflows</td>
                            </tr>
                            <template x-for="(outflow, idx) in (effectiveSummary.outflows || [])" :key="'out-' + idx">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-2.5 text-center font-mono text-slate-400" x-text="idx + 3"></td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800" x-text="outflow.name"></td>
                                    <td @click="outflow.overdue > 0 && openDrillDown(outflow.key, 'overdue', outflow.name + ' (Overdue)')"
                                        :class="outflow.overdue > 0 ? 'cursor-pointer hover:bg-amber-100/70 underline decoration-dotted text-rose-700 font-bold' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono bg-amber-50/30"
                                        x-text="formatMoney(outflow.overdue)"></td>
                                    <td @click="outflow.p1 > 0 && openDrillDown(outflow.key, 'p1', outflow.name + ' (Next 30d)')"
                                        :class="outflow.p1 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(outflow.p1)"></td>
                                    <td @click="outflow.p2 > 0 && openDrillDown(outflow.key, 'p2', outflow.name + ' (31-60d)')"
                                        :class="outflow.p2 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(outflow.p2)"></td>
                                    <td @click="outflow.p3 > 0 && openDrillDown(outflow.key, 'p3', outflow.name + ' (61-90d)')"
                                        :class="outflow.p3 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold text-slate-800' : 'text-slate-400'"
                                        class="py-2.5 px-3 text-right font-mono"
                                        x-text="formatMoney(outflow.p3)"></td>
                                    <td @click="outflow.total > 0 && openDrillDown(outflow.key, 'future', outflow.name + ' (90d Forecast)')"
                                        :class="outflow.total > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted text-slate-900 font-black' : 'text-slate-400 font-bold'"
                                        class="py-2.5 px-3.5 text-right font-mono bg-slate-50/60"
                                        x-text="formatMoney(outflow.total)"></td>
                                </tr>
                            </template>
                            <tr class="bg-rose-100/60 font-black text-rose-950 border-t border-b border-rose-200">
                                <td class="py-2.5 px-2.5"></td>
                                <td class="py-2.5 px-3 uppercase tracking-wider text-xs font-black text-rose-900">Total Projected Outflows</td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800 bg-amber-100/40" x-text="formatMoney(effectiveSummary.total_outflows?.overdue)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.p1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.p2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.p3)"></td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-black text-rose-900 bg-rose-200/40" x-text="formatMoney(effectiveSummary.total_outflows?.total)"></td>
                            </tr>

                            {{-- GRAND NET CASH FLOW --}}
                            <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-slate-300">
                                <td class="py-3 px-2.5 text-center font-mono">★</td>
                                <td class="py-3 px-3 uppercase tracking-wider text-xs font-black text-slate-900">Net Projected Cash Flow</td>
                                <td class="py-3 px-3 text-right font-mono font-black bg-amber-100/40"
                                    :class="(effectiveSummary.net_cash_flow?.overdue || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                    x-text="formatMoney(effectiveSummary.net_cash_flow?.overdue)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black"
                                    :class="(effectiveSummary.net_cash_flow?.p1 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                    x-text="formatMoney(effectiveSummary.net_cash_flow?.p1)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black"
                                    :class="(effectiveSummary.net_cash_flow?.p2 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                    x-text="formatMoney(effectiveSummary.net_cash_flow?.p2)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black"
                                    :class="(effectiveSummary.net_cash_flow?.p3 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                    x-text="formatMoney(effectiveSummary.net_cash_flow?.p3)"></td>
                                <td class="py-3 px-3.5 text-right font-mono font-black text-sm bg-slate-200/70"
                                    :class="(effectiveSummary.net_cash_flow?.total || 0) >= 0 ? 'text-emerald-800' : 'text-rose-800'"
                                    x-text="formatMoney(effectiveSummary.net_cash_flow?.total)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Weekly Breakdown Table (First 30 Days: Week 1 - Week 4) (Requirement #9) --}}
                <div class="overflow-x-auto" x-show="tableGranularity === 'weekly'" x-cloak>
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="global-table-header">
                            <tr>
                                <th class="py-3 px-2.5 text-center w-8">#</th>
                                <th class="py-3 px-3">Description</th>
                                <th @click="openDrillDown('all', 'w1', 'Week 1 (Days 0-6)')" class="py-3 px-3 text-right cursor-pointer hover:underline" x-text="periods.w1Label + ' ↗'"></th>
                                <th @click="openDrillDown('all', 'w2', 'Week 2 (Days 7-13)')" class="py-3 px-3 text-right cursor-pointer hover:underline" x-text="periods.w2Label + ' ↗'"></th>
                                <th @click="openDrillDown('all', 'w3', 'Week 3 (Days 14-20)')" class="py-3 px-3 text-right cursor-pointer hover:underline" x-text="periods.w3Label + ' ↗'"></th>
                                <th @click="openDrillDown('all', 'w4', 'Week 4 (Days 21-30)')" class="py-3 px-3 text-right cursor-pointer hover:underline" x-text="periods.w4Label + ' ↗'"></th>
                                <th @click="openDrillDown('all', 'p1', 'Total Next 30 Days')" class="py-3 px-3.5 text-right cursor-pointer hover:underline">Total (30d) ↗</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <tr class="bg-emerald-50/70 border-t border-b border-emerald-200/60 font-black text-emerald-900">
                                <td class="py-2 px-2.5 text-center font-mono">-</td>
                                <td colspan="6" class="py-2 px-3 uppercase tracking-wider text-[11px] text-emerald-800">Weekly Projected Inflows (Next 30 Days)</td>
                            </tr>
                            <template x-for="(inflow, idx) in (effectiveSummary.inflows || [])" :key="'win-' + idx">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-2.5 text-center font-mono text-slate-400" x-text="idx + 1"></td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800" x-text="inflow.name"></td>
                                    <td @click="inflow.w1 > 0 && openDrillDown(inflow.key, 'w1', inflow.name + ' (Week 1)')" :class="inflow.w1 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(inflow.w1)"></td>
                                    <td @click="inflow.w2 > 0 && openDrillDown(inflow.key, 'w2', inflow.name + ' (Week 2)')" :class="inflow.w2 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(inflow.w2)"></td>
                                    <td @click="inflow.w3 > 0 && openDrillDown(inflow.key, 'w3', inflow.name + ' (Week 3)')" :class="inflow.w3 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(inflow.w3)"></td>
                                    <td @click="inflow.w4 > 0 && openDrillDown(inflow.key, 'w4', inflow.name + ' (Week 4)')" :class="inflow.w4 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(inflow.w4)"></td>
                                    <td @click="inflow.p1 > 0 && openDrillDown(inflow.key, 'p1', inflow.name + ' (Next 30d)')" :class="inflow.p1 > 0 ? 'cursor-pointer hover:bg-emerald-50 underline decoration-dotted font-black text-slate-900' : 'text-slate-400'" class="py-2.5 px-3.5 text-right font-mono bg-slate-50/60" x-text="formatMoney(inflow.p1)"></td>
                                </tr>
                            </template>
                            <tr class="bg-emerald-100/60 font-black text-emerald-950 border-t border-b border-emerald-200">
                                <td class="py-2.5 px-2.5"></td>
                                <td class="py-2.5 px-3 uppercase tracking-wider text-xs font-black text-emerald-900">Total Weekly Inflows</td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.w1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.w2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.w3)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800" x-text="formatMoney(effectiveSummary.total_inflows?.w4)"></td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-black text-emerald-900 bg-emerald-200/40" x-text="formatMoney(effectiveSummary.total_inflows?.p1)"></td>
                            </tr>

                            <tr class="bg-rose-50/70 border-t border-b border-rose-200/60 font-black text-rose-900">
                                <td class="py-2 px-2.5 text-center font-mono">-</td>
                                <td colspan="6" class="py-2 px-3 uppercase tracking-wider text-[11px] text-rose-800">Weekly Projected Outflows (Next 30 Days)</td>
                            </tr>
                            <template x-for="(outflow, idx) in (effectiveSummary.outflows || [])" :key="'wout-' + idx">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-2.5 text-center font-mono text-slate-400" x-text="idx + 3"></td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800" x-text="outflow.name"></td>
                                    <td @click="outflow.w1 > 0 && openDrillDown(outflow.key, 'w1', outflow.name + ' (Week 1)')" :class="outflow.w1 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(outflow.w1)"></td>
                                    <td @click="outflow.w2 > 0 && openDrillDown(outflow.key, 'w2', outflow.name + ' (Week 2)')" :class="outflow.w2 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(outflow.w2)"></td>
                                    <td @click="outflow.w3 > 0 && openDrillDown(outflow.key, 'w3', outflow.name + ' (Week 3)')" :class="outflow.w3 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(outflow.w3)"></td>
                                    <td @click="outflow.w4 > 0 && openDrillDown(outflow.key, 'w4', outflow.name + ' (Week 4)')" :class="outflow.w4 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-semibold' : 'text-slate-400'" class="py-2.5 px-3 text-right font-mono" x-text="formatMoney(outflow.w4)"></td>
                                    <td @click="outflow.p1 > 0 && openDrillDown(outflow.key, 'p1', outflow.name + ' (Next 30d)')" :class="outflow.p1 > 0 ? 'cursor-pointer hover:bg-rose-50 underline decoration-dotted font-black text-slate-900' : 'text-slate-400'" class="py-2.5 px-3.5 text-right font-mono bg-slate-50/60" x-text="formatMoney(outflow.p1)"></td>
                                </tr>
                            </template>
                            <tr class="bg-rose-100/60 font-black text-rose-950 border-t border-b border-rose-200">
                                <td class="py-2.5 px-2.5"></td>
                                <td class="py-2.5 px-3 uppercase tracking-wider text-xs font-black text-rose-900">Total Weekly Outflows</td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.w1)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.w2)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.w3)"></td>
                                <td class="py-2.5 px-3 text-right font-mono font-black text-rose-800" x-text="formatMoney(effectiveSummary.total_outflows?.w4)"></td>
                                <td class="py-2.5 px-3.5 text-right font-mono font-black text-rose-900 bg-rose-200/40" x-text="formatMoney(effectiveSummary.total_outflows?.p1)"></td>
                            </tr>

                            <tr class="bg-slate-100/90 font-black text-slate-900 border-t-2 border-slate-300">
                                <td class="py-3 px-2.5 text-center font-mono">★</td>
                                <td class="py-3 px-3 uppercase tracking-wider text-xs font-black text-slate-900">Weekly Net Cash Flow</td>
                                <td class="py-3 px-3 text-right font-mono font-black" :class="(effectiveSummary.net_cash_flow?.w1 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="formatMoney(effectiveSummary.net_cash_flow?.w1)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black" :class="(effectiveSummary.net_cash_flow?.w2 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="formatMoney(effectiveSummary.net_cash_flow?.w2)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black" :class="(effectiveSummary.net_cash_flow?.w3 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="formatMoney(effectiveSummary.net_cash_flow?.w3)"></td>
                                <td class="py-3 px-3 text-right font-mono font-black" :class="(effectiveSummary.net_cash_flow?.w4 || 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="formatMoney(effectiveSummary.net_cash_flow?.w4)"></td>
                                <td class="py-3 px-3.5 text-right font-mono font-black text-sm bg-slate-200/70" :class="(effectiveSummary.net_cash_flow?.p1 || 0) >= 0 ? 'text-emerald-800' : 'text-rose-800'" x-text="formatMoney(effectiveSummary.net_cash_flow?.p1)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Bucket Drill-Down Bar (Replaces Permanent Bottom Table - Requirement #12) --}}
            <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Quick Bucket Drill-Down:</span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" @click="openDrillDown('all', 'overdue', 'Overdue / Past Due Backlog')"
                            class="px-2.5 py-1 rounded-lg bg-amber-100/80 hover:bg-amber-200 text-amber-900 border border-amber-300/70 text-[10px] font-extrabold uppercase tracking-wider cursor-pointer transition">
                        Overdue (<span x-text="overdueTransactions.length"></span>)
                    </button>
                    <button type="button" @click="openDrillDown('all', 'future', 'All Upcoming Future Transactions (Next 90d)')"
                            class="px-2.5 py-1 rounded-lg bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-extrabold uppercase tracking-wider cursor-pointer transition">
                        All Future (<span x-text="upcomingTransactions.length"></span>)
                    </button>
                    <button type="button" @click="openDrillDown('all', 'w1', 'Week 1 (Days 0-6)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">W1</button>
                    <button type="button" @click="openDrillDown('all', 'w2', 'Week 2 (Days 7-13)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">W2</button>
                    <button type="button" @click="openDrillDown('all', 'w3', 'Week 3 (Days 14-20)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">W3</button>
                    <button type="button" @click="openDrillDown('all', 'w4', 'Week 4 (Days 21-30)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">W4</button>
                    <button type="button" @click="openDrillDown('all', 'p2', '31 - 60 Days (Future)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">31–60d</button>
                    <button type="button" @click="openDrillDown('all', 'p3', '61 - 90 Days (Future)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold cursor-pointer">61–90d</button>
                </div>
            </div>
        </div>

        {{-- Right 1/3 (4 cols): Best/Expected/Worst Scenarios + Key Insights (Requirement #10) --}}
        <div class="lg:col-span-4 space-y-5">
            
            {{-- Best / Expected / Worst Scenarios Card (Replaced Duplicate Closing Cash Bar Chart) --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-3.5">
                <div class="border-b border-slate-100 pb-2.5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">90-Day Stress Scenarios</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Best, Expected &amp; Worst case ending liquidity</p>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-[9px] font-extrabold text-slate-600 uppercase">Sensitivity</span>
                </div>

                <div class="space-y-2.5">
                    {{-- Best Case --}}
                    <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200/70">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-emerald-900 uppercase tracking-wider" x-text="scenarios.best?.label"></span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase"
                                  :class="(scenarios.best?.ending_cash || 0) >= minBuffer ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'"
                                  x-text="(scenarios.best?.ending_cash || 0) >= minBuffer ? 'Above Buffer' : 'Below Buffer'"></span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1.5">
                            <span class="text-sm font-mono font-black text-emerald-800" x-text="currency + ' ' + formatMoney(scenarios.best?.ending_cash)"></span>
                            <span class="text-[10px] font-mono font-bold text-emerald-700" x-text="'Net: +' + formatCompact(scenarios.best?.net_flow)"></span>
                        </div>
                    </div>

                    {{-- Expected Case --}}
                    <div class="p-3 rounded-xl bg-amber-50/60 border border-[#a38c29]/30">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider" x-text="scenarios.expected?.label"></span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase"
                                  :class="(scenarios.expected?.ending_cash || 0) >= minBuffer ? 'bg-[#a38c29] text-white' : 'bg-rose-600 text-white'"
                                  x-text="(scenarios.expected?.ending_cash || 0) >= minBuffer ? 'Above Buffer' : 'Below Buffer'"></span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1.5">
                            <span class="text-sm font-mono font-black text-slate-900" x-text="currency + ' ' + formatMoney(scenarios.expected?.ending_cash)"></span>
                            <span class="text-[10px] font-mono font-bold text-[#8a7522]" x-text="'Net: ' + ((scenarios.expected?.net_flow || 0) >= 0 ? '+' : '') + formatCompact(scenarios.expected?.net_flow)"></span>
                        </div>
                    </div>

                    {{-- Worst Case --}}
                    <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-200/80">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-rose-900 uppercase tracking-wider" x-text="scenarios.worst?.label"></span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase"
                                  :class="(scenarios.worst?.ending_cash || 0) >= minBuffer ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'"
                                  x-text="(scenarios.worst?.ending_cash || 0) >= minBuffer ? 'Above Buffer' : 'Below Buffer'"></span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1.5">
                            <span class="text-sm font-mono font-black"
                                  :class="(scenarios.worst?.ending_cash || 0) >= 0 ? 'text-slate-900' : 'text-rose-700'"
                                  x-text="currency + ' ' + formatMoney(scenarios.worst?.ending_cash)"></span>
                            <span class="text-[10px] font-mono font-bold text-rose-700" x-text="'Net: ' + ((scenarios.worst?.net_flow || 0) >= 0 ? '+' : '') + formatCompact(scenarios.worst?.net_flow)"></span>
                        </div>
                    </div>
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

    {{-- ── 6. TRANSACTION DRILL-DOWN MODAL (Requirements #11 & #12) ── --}}
    <div x-show="drillModalOpen"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/65 text-left"
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.away="drillModalOpen = false">
            
            {{-- Dark Executive Modal Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 border-b border-[#a38c29]/20 shrink-0">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest">Bucket Drill-Down</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5" x-text="drillTitle"></h2>
                    </div>
                    <button type="button" @click="drillModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">✕</button>
                </div>
            </div>

            {{-- Modal Search & Bucket Switcher Bar --}}
            <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
                <div class="relative flex-1 max-w-md">
                    <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text"
                           x-model="txSearch"
                           @input="currentPage = 1"
                           placeholder="Search party name, reference no. or description..."
                           class="w-full erp-search-input pl-8 pr-6 text-xs h-[36px] bg-white">
                </div>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="text-emerald-700" x-text="'Inflows: ' + currency + ' ' + formatMoney(drillSummary.inflows)"></span>
                    <span class="text-rose-700" x-text="'Outflows: ' + currency + ' ' + formatMoney(drillSummary.outflows)"></span>
                </div>
            </div>

            {{-- Drill-Down Table (Merged Type / Flow Column - Requirement #11) --}}
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="global-table-header sticky top-0 z-10">
                        <tr>
                            <th class="py-3 px-3 text-center w-10">#</th>
                            <th class="py-3 px-3.5">Date</th>
                            <th class="py-3 px-3.5">Type / Flow</th>
                            <th class="py-3 px-3.5">Reference No.</th>
                            <th class="py-3 px-4">Party Name</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-right" x-text="'Amount (' + currency + ')'"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <template x-for="(tx, index) in paginatedTransactions" :key="index">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400" x-text="(currentPage - 1) * perPage + index + 1"></td>
                                <td class="py-2.5 px-3.5 font-mono font-bold text-slate-800 whitespace-nowrap" x-text="tx.date"></td>
                                <td class="py-2.5 px-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider inline-block"
                                          :class="getTxTypeBadgeClass(tx.type, tx.flow_type)"
                                          x-text="tx.flow_label || (tx.type + ' (' + (tx.flow_type === 'inflow' ? 'Inflow' : 'Outflow') + ')')">
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 font-mono font-bold text-slate-800 whitespace-nowrap" x-text="tx.ref_no"></td>
                                <td class="py-2.5 px-4 font-bold text-slate-900" x-text="tx.party_name"></td>
                                <td class="py-2.5 px-4 text-slate-600 text-xs" x-text="tx.description"></td>
                                <td class="py-2.5 px-4 text-right font-mono font-black whitespace-nowrap"
                                    :class="tx.flow_type === 'inflow' ? 'text-emerald-700' : 'text-rose-700'"
                                    x-text="(tx.flow_type === 'inflow' ? '+' : '-') + ' ' + formatMoney(tx.amount)">
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredDrillTransactions.length === 0">
                            <td colspan="7" class="py-10 text-center text-slate-400 italic text-xs">
                                No transactions match the selected bucket or search filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Modal Footer: 50-per-page Pagination + Close --}}
            <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900" x-text="filteredDrillTransactions.length ? ((currentPage - 1) * perPage + 1) : 0"></span> TO 
                    <span class="text-slate-900" x-text="Math.min(currentPage * perPage, filteredDrillTransactions.length)"></span> OF 
                    <span class="text-slate-900" x-text="filteredDrillTransactions.length"></span> TRANSACTIONS
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5" x-show="filteredDrillTransactions.length > 0">
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
                    <button type="button" @click="drillModalOpen = false" class="px-4 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-extrabold text-slate-700 uppercase tracking-wider cursor-pointer">Close</button>
                </div>
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
        minBuffer: 1000000,
        chequeConfidence: '100', // '100' | '80' | 'delayed'
        chartGranularity: 'buckets', // 'buckets' | 'weekly'
        tableGranularity: 'buckets', // 'buckets' | 'weekly'

        allProjects: @json($allProjects ?? []),
        projectDatasets: @json($projectDatasets ?? []),
        periods: @json($periods ?? []),
        kpis: @json($kpis ?? []),
        summaryTable: @json($summaryTable ?? []),
        chartData: @json($chartData ?? []),
        categoryOutflows: @json($categoryOutflowBreakdown ?? []),
        scenarios: @json($scenarios ?? []),
        keyInsights: @json($keyInsights ?? []),
        upcomingTransactions: @json($upcomingTransactions ?? []),
        overdueTransactions: @json($overdueTransactions ?? []),

        // Drill-down Modal State
        drillModalOpen: false,
        drillRowKey: 'all',
        drillBucket: 'future',
        drillTitle: 'Upcoming Transactions',
        txSearch: '',
        currentPage: 1,
        perPage: 50,

        comboChartInstance: null,
        donutChartInstance: null,

        init() {
            this.$nextTick(() => {
                this.renderComboChart();
                this.renderDonutChart();
            });
        },

        // Reactive Pending Cheque Confidence transformation (Requirement #8)
        get effectiveSummary() {
            const st = JSON.parse(JSON.stringify(this.summaryTable || {}));
            if (!st.inflows || !st.total_inflows) return st;

            const chqRow = st.inflows.find(r => r.key === 'inflow_cheques');
            const mRow = st.inflows.find(r => r.key === 'inflow_milestones');
            if (!chqRow || !mRow) return st;

            const origChqOverdue = chqRow.overdue || 0;
            const origChqP1 = chqRow.p1 || 0;
            const origChqP2 = chqRow.p2 || 0;
            const origChqP3 = chqRow.p3 || 0;

            if (this.chequeConfidence === '80') {
                chqRow.overdue = origChqOverdue * 0.80;
                chqRow.p1 = origChqP1 * 0.80;
                chqRow.p2 = origChqP2 * 0.80;
                chqRow.p3 = origChqP3 * 0.80;
                chqRow.w1 *= 0.80; chqRow.w2 *= 0.80; chqRow.w3 *= 0.80; chqRow.w4 *= 0.80;
                chqRow.total = chqRow.p1 + chqRow.p2 + chqRow.p3;
            } else if (this.chequeConfidence === 'delayed') {
                // Delayed: shift overdue & P1 uncleared cheques into 31-60 Days (p2)
                chqRow.overdue = 0;
                chqRow.p1 = 0;
                chqRow.w1 = 0; chqRow.w2 = 0; chqRow.w3 = 0; chqRow.w4 = 0;
                chqRow.p2 = origChqP2 + origChqP1 + origChqOverdue;
                chqRow.total = chqRow.p1 + chqRow.p2 + chqRow.p3;
            }

            const keys = ['overdue', 'p1', 'p2', 'p3', 'w1', 'w2', 'w3', 'w4', 'total'];
            keys.forEach(k => {
                st.total_inflows[k] = (mRow[k] || 0) + (chqRow[k] || 0);
                st.net_cash_flow[k] = (st.total_inflows[k] || 0) - (st.total_outflows[k] || 0);
            });

            return st;
        },

        get effectiveKpis() {
            const st = this.effectiveSummary;
            const days = parseInt(this.filters.period, 10) || 90;
            const ti = st.total_inflows || {};
            const to = st.total_outflows || {};
            const nc = st.net_cash_flow || {};

            let inflowSum = ti.total || 0;
            let outflowSum = to.total || 0;
            let netSum = nc.total || 0;

            if (days === 30) {
                inflowSum = ti.p1 || 0;
                outflowSum = to.p1 || 0;
                netSum = nc.p1 || 0;
            } else if (days === 60) {
                inflowSum = (ti.p1 || 0) + (ti.p2 || 0);
                outflowSum = (to.p1 || 0) + (to.p2 || 0);
                netSum = (nc.p1 || 0) + (nc.p2 || 0);
            }

            const endCash = (this.kpis.current_liquid_cash || 0) + netSum;

            return {
                current_liquid_cash: this.kpis.current_liquid_cash || 0,
                free_cash: this.kpis.free_cash || 0,
                restricted_escrow_cash: this.kpis.restricted_escrow_cash || 0,
                total_projected_inflows: inflowSum,
                total_projected_outflows: outflowSum,
                overdue_inflows: ti.overdue || 0,
                overdue_outflows: to.overdue || 0,
                net_liquidity: endCash,
                total_net_cash_flow: netSum,
                top_outflow_name: this.kpis.top_outflow_name || 'None',
                top_outflow_pct: this.kpis.top_outflow_pct || 0,
                status: endCash >= 0 ? 'Surplus' : 'Deficit'
            };
        },

        // Low Cash Buffer Alert Checks (Requirement #7)
        get lowBalanceAlerts() {
            const buf = parseFloat(this.minBuffer) || 0;
            if (buf <= 0) return [];
            const alerts = [];
            const ek = this.effectiveKpis;
            if ((ek.free_cash || 0) < buf) {
                alerts.push('Current Free Cash (' + this.currency + ' ' + this.formatMoney(ek.free_cash) + ') is below Min Buffer');
            }
            const st = this.effectiveSummary;
            const curr = ek.current_liquid_cash || 0;
            const cW1 = curr + (st.net_cash_flow?.w1 || 0);
            const cW2 = cW1 + (st.net_cash_flow?.w2 || 0);
            const cW3 = cW2 + (st.net_cash_flow?.w3 || 0);
            const cP1 = curr + (st.net_cash_flow?.p1 || 0);
            const cP2 = cP1 + (st.net_cash_flow?.p2 || 0);
            const cP3 = cP2 + (st.net_cash_flow?.p3 || 0);

            if (cW1 < buf) alerts.push('Week 1 projected closing drops to ' + this.currency + ' ' + this.formatMoney(cW1));
            if (cP1 < buf) alerts.push('Next 30d closing drops to ' + this.currency + ' ' + this.formatMoney(cP1));
            if (cP2 < buf) alerts.push('31-60d closing drops to ' + this.currency + ' ' + this.formatMoney(cP2));
            if (cP3 < buf) alerts.push('61-90d closing drops to ' + this.currency + ' ' + this.formatMoney(cP3));

            if ((this.scenarios.worst?.ending_cash || 0) < buf) {
                alerts.push('Worst-Case scenario drops to ' + this.currency + ' ' + this.formatMoney(this.scenarios.worst?.ending_cash));
            }
            return alerts;
        },

        getProjectLabel() {
            if (this.filters.project_id === 'all') return 'All Projects';
            const p = this.allProjects.find(item => String(item.id) === String(this.filters.project_id));
            return p ? p.name : 'All Projects';
        },

        selectProject(id) {
            this.filters.project_id = String(id);
            const ds = this.projectDatasets[this.filters.project_id] || this.projectDatasets['all'] || {};
            if (ds.kpis) {
                this.kpis = JSON.parse(JSON.stringify(ds.kpis));
                this.summaryTable = JSON.parse(JSON.stringify(ds.summaryTable));
                this.chartData = JSON.parse(JSON.stringify(ds.chartData));
                this.categoryOutflows = JSON.parse(JSON.stringify(ds.categoryOutflowBreakdown));
                this.scenarios = JSON.parse(JSON.stringify(ds.scenarios));
                this.keyInsights = JSON.parse(JSON.stringify(ds.keyInsights));
                this.periods = JSON.parse(JSON.stringify(ds.periods));
                this.upcomingTransactions = JSON.parse(JSON.stringify(ds.upcomingTransactions));
                this.overdueTransactions = JSON.parse(JSON.stringify(ds.overdueTransactions));
                this.updateCharts();
            }
        },

        selectPeriod(period) {
            this.filters.period = String(period);
            this.updateCharts();
        },

        setChequeConfidence(mode) {
            this.chequeConfidence = mode;
            this.updateCharts();
        },

        async fetchByDate() {
            try {
                const url = `{{ route('reports.liquidity_forecast') }}?ajax=1&as_on_date=${encodeURIComponent(this.filters.as_on_date)}&project_id=${encodeURIComponent(this.filters.project_id)}`;
                const res = await fetch(url);
                if (!res.ok) return;
                const data = await res.json();
                this.projectDatasets = data.projectDatasets || {};
                this.selectProject(this.filters.project_id);
            } catch (e) {
                console.error(e);
            }
        },

        resetFilters() {
            this.filters.project_id = String(this.defaultProjectId);
            this.filters.period = '90';
            this.filters.as_on_date = this.defaultAsOnDate;
            this.minBuffer = 1000000;
            this.chequeConfidence = '100';
            this.chartGranularity = 'buckets';
            this.tableGranularity = 'buckets';
            this.selectProject(this.defaultProjectId);
        },

        // Drill-down modal methods (Requirement #12)
        openDrillDown(rowKey, bucket, title) {
            this.drillRowKey = rowKey || 'all';
            this.drillBucket = bucket || 'future';
            this.drillTitle = title || 'Transaction Details';
            this.txSearch = '';
            this.currentPage = 1;
            this.drillModalOpen = true;
        },

        get filteredDrillTransactions() {
            let list = [];
            if (this.drillBucket === 'overdue') {
                list = this.overdueTransactions || [];
            } else if (this.drillBucket === 'all_combined') {
                list = [...(this.overdueTransactions || []), ...(this.upcomingTransactions || [])];
            } else if (this.drillBucket === 'future') {
                list = this.upcomingTransactions || [];
            } else if (['w1', 'w2', 'w3', 'w4'].includes(this.drillBucket)) {
                list = (this.upcomingTransactions || []).filter(t => t.week === this.drillBucket);
            } else {
                list = (this.upcomingTransactions || []).filter(t => t.bucket === this.drillBucket);
            }

            if (this.drillRowKey && this.drillRowKey !== 'all') {
                list = list.filter(t => t.row_key === this.drillRowKey);
            }

            if (this.txSearch.trim()) {
                const q = this.txSearch.toLowerCase().trim();
                list = list.filter(tx =>
                    (tx.party_name && tx.party_name.toLowerCase().includes(q)) ||
                    (tx.ref_no && tx.ref_no.toLowerCase().includes(q)) ||
                    (tx.description && tx.description.toLowerCase().includes(q)) ||
                    (tx.flow_label && tx.flow_label.toLowerCase().includes(q))
                );
            }
            return list;
        },

        get drillSummary() {
            let inflows = 0;
            let outflows = 0;
            this.filteredDrillTransactions.forEach(t => {
                if (t.flow_type === 'inflow') inflows += (parseFloat(t.amount) || 0);
                else outflows += (parseFloat(t.amount) || 0);
            });
            return { inflows, outflows };
        },

        get totalPages() {
            return Math.ceil(this.filteredDrillTransactions.length / this.perPage) || 1;
        },

        get paginatedTransactions() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredDrillTransactions.slice(start, start + this.perPage);
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
            const abs = Math.abs(n);
            const sign = n < 0 ? '-' : '';
            if (abs >= 10000000) return sign + (abs / 10000000).toFixed(2) + ' Cr';
            if (abs >= 100000) return sign + (abs / 100000).toFixed(2) + ' L';
            if (abs >= 1000) return sign + (abs / 1000).toFixed(1) + ' K';
            return n.toFixed(0);
        },

        getTxTypeBadgeClass(type, flowType) {
            if (flowType === 'inflow') {
                return 'bg-emerald-100 text-emerald-800 border border-emerald-300/60';
            }
            switch (type) {
                case 'Contractor Payment': return 'bg-orange-100 text-orange-800 border border-orange-300/60';
                case 'Vendor Payment': return 'bg-blue-100 text-blue-800 border border-blue-300/60';
                case 'Bank Loan EMI': return 'bg-purple-100 text-purple-800 border border-purple-300/60';
                case 'Brokerage Payout': return 'bg-indigo-100 text-indigo-800 border border-indigo-300/60';
                case 'Customer Refund': return 'bg-rose-100 text-rose-800 border border-rose-300/60';
                case 'Payroll': return 'bg-sky-100 text-sky-800 border border-sky-300/60';
                case 'GST / Statutory': return 'bg-red-100 text-red-800 border border-red-300/60';
                default: return 'bg-slate-100 text-slate-700 border border-slate-300/60';
            }
        },

        getComboSeriesAndLabels() {
            const st = this.effectiveSummary;
            const curr = this.kpis.current_liquid_cash || 0;
            const ti = st.total_inflows || {};
            const to = st.total_outflows || {};
            const nc = st.net_cash_flow || {};

            if (this.chartGranularity === 'weekly') {
                const cW1 = curr + (nc.w1 || 0);
                const cW2 = cW1 + (nc.w2 || 0);
                const cW3 = cW2 + (nc.w3 || 0);
                const cW4 = cW3 + (nc.w4 || 0);
                return {
                    labels: ['Current', 'Week 1 (0-6d)', 'Week 2 (7-13d)', 'Week 3 (14-20d)', 'Week 4 (21-30d)'],
                    inflows: [0, Math.round(ti.w1 || 0), Math.round(ti.w2 || 0), Math.round(ti.w3 || 0), Math.round(ti.w4 || 0)],
                    outflows: [0, Math.round(to.w1 || 0), Math.round(to.w2 || 0), Math.round(to.w3 || 0), Math.round(to.w4 || 0)],
                    closing: [Math.round(curr), Math.round(cW1), Math.round(cW2), Math.round(cW3), Math.round(cW4)]
                };
            }

            const days = parseInt(this.filters.period, 10) || 90;
            const sliceCount = days === 30 ? 2 : (days === 60 ? 3 : 4);
            const cP1 = curr + (nc.p1 || 0);
            const cP2 = cP1 + (nc.p2 || 0);
            const cP3 = cP2 + (nc.p3 || 0);

            return {
                labels: ['Current Balance', 'Next 30 Days', '31 - 60 Days', '61 - 90 Days'].slice(0, sliceCount),
                inflows: [0, Math.round(ti.p1 || 0), Math.round(ti.p2 || 0), Math.round(ti.p3 || 0)].slice(0, sliceCount),
                outflows: [0, Math.round(to.p1 || 0), Math.round(to.p2 || 0), Math.round(to.p3 || 0)].slice(0, sliceCount),
                closing: [Math.round(curr), Math.round(cP1), Math.round(cP2), Math.round(cP3)].slice(0, sliceCount)
            };
        },

        updateCharts() {
            const combo = this.getComboSeriesAndLabels();
            const buf = parseFloat(this.minBuffer) || 0;

            if (this.comboChartInstance) {
                this.comboChartInstance.updateOptions({
                    series: [
                        { name: 'Inflows', type: 'column', data: combo.inflows },
                        { name: 'Outflows', type: 'column', data: combo.outflows },
                        { name: 'Closing Balance', type: 'line', data: combo.closing }
                    ],
                    labels: combo.labels,
                    annotations: {
                        yaxis: buf > 0 ? [{
                            y: buf,
                            borderColor: '#e11d48',
                            strokeDashArray: 5,
                            borderWidth: 2,
                            label: {
                                borderColor: '#e11d48',
                                style: { color: '#fff', background: '#e11d48', fontSize: '10px', fontWeight: 700 },
                                text: 'Min Buffer: ' + this.currency + ' ' + this.formatCompact(buf)
                            }
                        }] : []
                    }
                });
            }

            if (this.donutChartInstance) {
                this.donutChartInstance.updateOptions({
                    series: this.categoryOutflows.map(c => c.display_amt),
                    labels: this.categoryOutflows.map(c => c.name),
                    colors: this.categoryOutflows.map(c => c.color)
                });
            }
        },

        renderComboChart() {
            const chartEl = document.querySelector('#cashflowComboChart');
            if (!chartEl) return;
            if (this.comboChartInstance) this.comboChartInstance.destroy();

            const combo = this.getComboSeriesAndLabels();
            const buf = parseFloat(this.minBuffer) || 0;

            const options = {
                series: [
                    { name: 'Inflows', type: 'column', data: combo.inflows },
                    { name: 'Outflows', type: 'column', data: combo.outflows },
                    { name: 'Closing Balance', type: 'line', data: combo.closing }
                ],
                chart: {
                    height: 330,
                    type: 'line',
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                stroke: { width: [0, 0, 3], curve: 'smooth' },
                plotOptions: { bar: { columnWidth: '40%', borderRadius: 4 } },
                colors: ['#10b981', '#f43f5e', '#2563eb'],
                annotations: {
                    yaxis: buf > 0 ? [{
                        y: buf,
                        borderColor: '#e11d48',
                        strokeDashArray: 5,
                        borderWidth: 2,
                        label: {
                            borderColor: '#e11d48',
                            style: { color: '#fff', background: '#e11d48', fontSize: '10px', fontWeight: 700 },
                            text: 'Min Buffer: ' + this.currency + ' ' + this.formatCompact(buf)
                        }
                    }] : []
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [2],
                    formatter: (val) => this.formatCompact(val),
                    style: { fontSize: '11px', fontWeight: '700', colors: ['#1e293b'] },
                    background: { enabled: true, borderRadius: 4, padding: 4, opacity: 0.9, borderColor: '#93c5fd' }
                },
                labels: combo.labels,
                xaxis: {
                    labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#64748b' } }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => this.formatCompact(val),
                        style: { fontSize: '10px', fontWeight: 600, colors: '#64748b' }
                    }
                },
                legend: { show: false },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: { formatter: (val) => this.currency + ' ' + this.formatMoney(val) }
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
            };

            this.comboChartInstance = new ApexCharts(chartEl, options);
            this.comboChartInstance.render();
        },

        renderDonutChart() {
            const chartEl = document.querySelector('#outflowDonutChart');
            if (!chartEl) return;
            if (this.donutChartInstance) this.donutChartInstance.destroy();

            const options = {
                series: this.categoryOutflows.map(c => c.display_amt),
                labels: this.categoryOutflows.map(c => c.name),
                colors: this.categoryOutflows.map(c => c.color),
                chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '11px', fontWeight: 700, color: '#64748b' },
                                value: {
                                    show: true, fontSize: '13px', fontWeight: 900, color: '#0f172a',
                                    formatter: (val) => this.formatCompact(val)
                                },
                                total: {
                                    show: true, showAlways: true, label: 'Total Outflows',
                                    fontSize: '10px', fontWeight: 700, color: '#64748b',
                                    formatter: (w) => this.formatCompact(w.globals.seriesTotals.reduce((a, b) => a + b, 0))
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                stroke: { width: 2, colors: ['#ffffff'] },
                tooltip: { y: { formatter: (val) => this.currency + ' ' + this.formatMoney(val) } }
            };

            this.donutChartInstance = new ApexCharts(chartEl, options);
            this.donutChartInstance.render();
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('Excel generation library is still loading. Please try again.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'Tabasco ERP';
            workbook.created = new Date();

            const headerFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF534E47' } };
            const headerFont = { name: 'Segoe UI', size: 9.5, bold: true, color: { argb: 'FFFFFFFF' } };
            const borderThin = {
                top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                bottom: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
            };

            const st = this.effectiveSummary;
            const ek = this.effectiveKpis;

            // ── SHEET 1: CASH FLOW SUMMARY ──
            const summarySheet = workbook.addWorksheet('Cash Flow Forecast');
            summarySheet.mergeCells('A1:G1');
            const titleRow = summarySheet.getCell('A1');
            titleRow.value = 'TABASCO REAL ESTATE ERP - CASH FLOW & LIQUIDITY FORECAST';
            titleRow.font = { name: 'Segoe UI', size: 13, bold: true, color: { argb: 'FF1E293B' } };
            titleRow.alignment = { horizontal: 'center', vertical: 'middle' };
            summarySheet.getRow(1).height = 28;

            summarySheet.mergeCells('A2:G2');
            const metaRow = summarySheet.getCell('A2');
            metaRow.value = `As On Date: ${this.filters.as_on_date} | Free Cash: ${this.currency} ${this.formatMoney(ek.free_cash)} | RERA/Escrow: ${this.currency} ${this.formatMoney(ek.restricted_escrow_cash)} | Cheque Confidence: ${this.chequeConfidence}`;
            metaRow.font = { name: 'Segoe UI', size: 9, italic: true, color: { argb: 'FF64748B' } };
            metaRow.alignment = { horizontal: 'center', vertical: 'middle' };
            summarySheet.getRow(2).height = 18;

            summarySheet.addRow([]);
            const tableHeaders = ['#', 'Description', 'Overdue / Past Due', this.periods.p1Label, this.periods.p2Label, this.periods.p3Label, 'Total Forecast (90d)'];
            const hRow = summarySheet.addRow(tableHeaders);
            hRow.height = 24;
            hRow.eachCell(cell => {
                cell.fill = headerFill;
                cell.font = headerFont;
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            });

            const addSummaryRows = (items, offsetIdx) => {
                items.forEach((item, idx) => {
                    const r = summarySheet.addRow([idx + offsetIdx, item.name, item.overdue, item.p1, item.p2, item.p3, item.total]);
                    r.getCell(1).alignment = { horizontal: 'center' };
                    r.getCell(2).alignment = { horizontal: 'left' };
                    for (let c = 3; c <= 7; c++) {
                        r.getCell(c).alignment = { horizontal: 'right' };
                        r.getCell(c).numFmt = '#,##0.00';
                    }
                    r.eachCell(cell => cell.border = borderThin);
                });
            };

            const inH = summarySheet.addRow(['-', 'PROJECTED INFLOWS', '', '', '', '', '']);
            inH.font = { bold: true, color: { argb: 'FF065F46' } };
            inH.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFECFDF5' } };
            addSummaryRows(st.inflows || [], 1);

            const inTot = summarySheet.addRow(['', 'Total Projected Inflows', st.total_inflows?.overdue || 0, st.total_inflows?.p1 || 0, st.total_inflows?.p2 || 0, st.total_inflows?.p3 || 0, st.total_inflows?.total || 0]);
            inTot.font = { bold: true, color: { argb: 'FF065F46' } };
            for (let c = 3; c <= 7; c++) { inTot.getCell(c).numFmt = '#,##0.00'; inTot.getCell(c).alignment = { horizontal: 'right' }; }

            const outH = summarySheet.addRow(['-', 'PROJECTED OUTFLOWS', '', '', '', '', '']);
            outH.font = { bold: true, color: { argb: 'FF9F1239' } };
            outH.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF1F2' } };
            addSummaryRows(st.outflows || [], 3);

            const outTot = summarySheet.addRow(['', 'Total Projected Outflows', st.total_outflows?.overdue || 0, st.total_outflows?.p1 || 0, st.total_outflows?.p2 || 0, st.total_outflows?.p3 || 0, st.total_outflows?.total || 0]);
            outTot.font = { bold: true, color: { argb: 'FF9F1239' } };
            for (let c = 3; c <= 7; c++) { outTot.getCell(c).numFmt = '#,##0.00'; outTot.getCell(c).alignment = { horizontal: 'right' }; }

            const netR = summarySheet.addRow(['★', 'Net Projected Cash Flow', st.net_cash_flow?.overdue || 0, st.net_cash_flow?.p1 || 0, st.net_cash_flow?.p2 || 0, st.net_cash_flow?.p3 || 0, st.net_cash_flow?.total || 0]);
            netR.font = { bold: true };
            for (let c = 3; c <= 7; c++) { netR.getCell(c).numFmt = '#,##0.00'; netR.getCell(c).alignment = { horizontal: 'right' }; }

            summarySheet.columns = [{ width: 6 }, { width: 38 }, { width: 20 }, { width: 22 }, { width: 22 }, { width: 22 }, { width: 22 }];

            // ── SHEET 2: UPCOMING TRANSACTIONS (FUTURE ONLY) ──
            const writeTxSheet = (sheetName, txList) => {
                const sh = workbook.addWorksheet(sheetName);
                const th = ['#', 'Date', 'Type / Flow', 'Reference No.', 'Party Name', 'Description', `Amount (${this.currency})`];
                const hr = sh.addRow(th);
                hr.height = 24;
                hr.eachCell(cell => { cell.fill = headerFill; cell.font = headerFont; cell.alignment = { horizontal: 'center', vertical: 'middle' }; });
                txList.forEach((tx, idx) => {
                    const r = sh.addRow([idx + 1, tx.date, tx.flow_label || tx.type, tx.ref_no, tx.party_name, tx.description, parseFloat(tx.amount) || 0]);
                    r.getCell(1).alignment = { horizontal: 'center' };
                    r.getCell(2).alignment = { horizontal: 'center' };
                    r.getCell(3).alignment = { horizontal: 'center' };
                    r.getCell(4).alignment = { horizontal: 'center' };
                    r.getCell(5).alignment = { horizontal: 'left' };
                    r.getCell(6).alignment = { horizontal: 'left' };
                    r.getCell(7).alignment = { horizontal: 'right' };
                    r.getCell(7).numFmt = '#,##0.00';
                    r.eachCell(cell => cell.border = borderThin);
                });
                sh.columns = [{ width: 6 }, { width: 14 }, { width: 26 }, { width: 18 }, { width: 28 }, { width: 40 }, { width: 18 }];
            };

            writeTxSheet('Upcoming Transactions', this.upcomingTransactions || []);
            writeTxSheet('Overdue & Past Due', this.overdueTransactions || []);

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
