<x-erp-layout title="Vendor & Sundry Creditor Aging Report" headerTitle="Vendor & Sundry Creditor Aging Report">

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
        margin-bottom: 8px !important;
        padding-bottom: 6px !important;
    }

    /* 6 KPI Metric Cards in Print */
    #dashboard-stats {
        display: grid !important;
        grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
        gap: 6px !important;
        margin-bottom: 8px !important;
        width: 100% !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    #dashboard-stats > div {
        padding: 6px 8px !important;
        border-radius: 8px !important;
        border-width: 1px !important;
        border-left-width: 4px !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        min-width: 0 !important;
        overflow: hidden !important;
    }
    #dashboard-stats .text-xl {
        font-size: 9.5pt !important;
        line-height: 1.15 !important;
    }

    /* Charts in Print */
    #dashboard-charts {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 8px !important;
        margin-top: 12px !important;
        margin-bottom: 16px !important;
        width: 100% !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
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
}
</style>

<div class="max-w-[1850px] mx-auto space-y-6" 
     x-data="vendorAgingApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Accounting & Vendor Accounts Payable</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">VENDOR & SUNDRY CREDITOR AGING REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800" x-text="asOnDate"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Vendors:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredRows.length + ' Vendors'"></span></div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-black tracking-tight uppercase text-slate-900">Vendor & Sundry Creditor Aging Report</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/15 text-[#8a7522] text-[9px] font-black uppercase tracking-widest border border-[#a38c29]/30 shadow-2xs">Accounts Payable</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                        <span>Accounting</span>
                        <span>&rsaquo;</span>
                        <span>Reports</span>
                        <span>&rsaquo;</span>
                        <span class="text-[#8a7522] font-semibold">Vendor & Sundry Creditor Aging Report</span>
                    </div>
                </div>
            </div>

            {{-- Quick Metadata Badges --}}
            <div class="flex items-center gap-3 text-right">
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">As On Date</span>
                    <span class="text-xs font-mono font-black text-slate-900" x-text="asOnDate">{{ $asOnDate }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-white/90 border border-[#a38c29]/25 backdrop-blur-xs text-left shadow-2xs">
                    <span class="text-[9px] font-extrabold text-[#8a7522] uppercase tracking-widest block">Active Records</span>
                    <span class="text-xs font-mono font-black text-[#8a7522]" x-text="filteredRows.length + ' Vendors (' + allRows.length + ' Total)'"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. EXECUTIVE 6 FINANCIAL KPI METRIC CARDS ── --}}
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        
        {{-- Card 1: TOTAL VENDORS --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL VENDORS</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-2xl font-mono font-black text-slate-900 tracking-tight" x-text="summaryKPIs.total_vendors">{{ $kpis['total_vendors'] ?? count($rows) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1">Active Accounts</div>
            </div>
        </div>

        {{-- Card 2: TOTAL OUTSTANDING --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">TOTAL OUTSTANDING</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.total_outstanding)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.total_outstanding)">{{ $currency }} {{ number_format($kpis['total_outstanding'] ?? 0, 2) }}</div>
                <div class="text-[10px] text-[#8a7522] font-bold mt-1">Total Payables Due</div>
            </div>
        </div>

        {{-- Card 3: 0 - 30 DAYS --}}
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
                     :title="currency + ' ' + formatMoney(summaryKPIs.b0_30)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.b0_30)">{{ $currency }} {{ number_format($kpis['b0_30'] ?? 0, 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1" x-text="summaryKPIs.b0_30_pct + '% of Total'">{{ $kpis['b0_30_pct'] ?? 0 }}% of Total</div>
            </div>
        </div>

        {{-- Card 4: 31 - 60 DAYS --}}
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
                     :title="currency + ' ' + formatMoney(summaryKPIs.b31_60)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.b31_60)">{{ $currency }} {{ number_format($kpis['b31_60'] ?? 0, 2) }}</div>
                <div class="text-[10px] text-amber-600 font-bold mt-1" x-text="summaryKPIs.b31_60_pct + '% of Total'">{{ $kpis['b31_60_pct'] ?? 0 }}% of Total</div>
            </div>
        </div>

        {{-- Card 5: 61 - 90 DAYS --}}
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
                     :title="currency + ' ' + formatMoney(summaryKPIs.b61_90)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.b61_90)">{{ $currency }} {{ number_format($kpis['b61_90'] ?? 0, 2) }}</div>
                <div class="text-[10px] text-orange-600 font-bold mt-1" x-text="summaryKPIs.b61_90_pct + '% of Total'">{{ $kpis['b61_90_pct'] ?? 0 }}% of Total</div>
            </div>
        </div>

        {{-- Card 6: 90+ DAYS --}}
        <div class="bg-white p-4 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">90+ DAYS</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-700 tracking-tight truncate"
                     :title="currency + ' ' + formatMoney(summaryKPIs.b90_plus)"
                     x-text="currency + ' ' + formatMoney(summaryKPIs.b90_plus)">{{ $currency }} {{ number_format($kpis['b90_plus'] ?? 0, 2) }}</div>
                <div class="text-[10px] text-rose-600 font-bold mt-1" x-text="summaryKPIs.b90_plus_pct + '% Overdue Risk'">{{ $kpis['b90_plus_pct'] ?? 0 }}% Overdue Risk</div>
            </div>
        </div>

    </div>

    {{-- ── 3. VISUAL ANALYTICAL CHARTS ── --}}
    <div id="dashboard-charts" class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        {{-- Chart 1: Aging Summary (Donut Chart) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Aging Summary</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Overdue share across delinquency buckets</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <div id="agingDonutChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

        {{-- Chart 2: Top 5 Overdue Vendors (90+ Days) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Top 5 Overdue Vendors (90+ Days)</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Critical high-risk creditor balances</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            </div>
            
            {{-- Clean horizontal progress bars matching screenshot --}}
            <div class="pt-2 space-y-3 min-h-[210px] flex flex-col justify-center">
                <template x-for="(topV, tIdx) in top5Vendors" :key="tIdx">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 truncate max-w-[200px]" x-text="topV.name"></span>
                            <span class="font-mono font-bold text-slate-900" x-text="formatMoney(topV.amount)"></span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500"
                                 :style="'width: ' + ((topV.amount / (top5Vendors[0]?.amount || 1)) * 100) + '%; background-color: ' + getTop5Color(tIdx)"></div>
                        </div>
                    </div>
                </template>
                <div x-show="top5Vendors.length === 0" class="py-6 text-center text-slate-400 text-xs italic">
                    No 90+ days overdue balance in filtered vendors.
                </div>
            </div>
        </div>

        {{-- Chart 3: Aging by Bucket (Bar/Column Chart) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider" x-text="'Aging by Bucket (' + currency + ')'"></h3>
                    <p class="text-[10px] text-slate-400 font-medium">Distribution by chronological aging buckets</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            </div>
            <div id="agingBucketBarChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

    </div>

    {{-- ── 4. ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (DIRECTLY BEFORE TABLE - THEME STYLE - ZERO RELOAD) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-sm transition-all mb-4 print:hidden">
        <div class="flex items-center gap-2 w-full flex-wrap xl:flex-nowrap">
            
            {{-- 1. Search Vendor Name / Company ID --}}
            <div class="relative min-w-[170px] flex-[1.3]" style="flex: 1.3 1 0%;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       x-model="filters.search"
                       @input.debounce.200ms="currentPage = 1; updateCharts()"
                       placeholder="Search vendor name / company ID..."
                       class="w-full erp-search-input pl-8 pr-6 text-xs h-[38px]">
                <template x-if="filters.search">
                    <button type="button" @click="filters.search = ''; currentPage = 1; updateCharts()" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
                </template>
            </div>

            {{-- 2. Vendor Search & Select Dropdown --}}
            <div class="relative min-w-[150px] flex-1" style="flex: 1 1 0%;"
                 x-data="{
                     open: false,
                     search: '',
                     get selectedVendorObj() {
                         return allVendorsList.find(v => String(v.name).toLowerCase() === String(filters.vendor).toLowerCase() || String(v.id) === String(filters.vendor));
                     },
                     get filteredVendors() {
                         const q = (this.search || '').toLowerCase().trim();
                         if (!q) return allVendorsList;
                         return allVendorsList.filter(v => 
                             (v.name && v.name.toLowerCase().includes(q)) || 
                             (v.id && v.id.toLowerCase().includes(q))
                         );
                     },
                     select(name) {
                         filters.vendor = name;
                         currentPage = 1;
                         updateCharts();
                         this.open = false;
                         this.search = '';
                     },
                     clear() {
                         filters.vendor = 'all';
                         currentPage = 1;
                         updateCharts();
                         this.open = false;
                         this.search = '';
                     }
                 }"
                 :class="open ? 'z-30' : ''"
                 @click.outside="open = false">

                <button type="button"
                        @click="open = !open; if (open) { $nextTick(() => $refs.vendorSearchInput?.focus()); }"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    
                    <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="truncate text-xs font-bold"
                              :class="filters.vendor !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="filters.vendor !== 'all' ? filters.vendor : 'All Vendors'">All Vendors</span>
                    </div>

                    <div class="flex items-center gap-1 shrink-0 ml-1">
                        <template x-if="filters.vendor !== 'all'">
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
                                   x-ref="vendorSearchInput"
                                   placeholder="Type to search vendor..."
                                   @keydown.escape="open = false"
                                   class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                            <template x-if="search">
                                <button type="button" @click="search = ''; $refs.vendorSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                            </template>
                        </div>
                    </div>

                    {{-- All Vendors Option --}}
                    <button type="button" @click="clear()"
                            class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer"
                            :class="filters.vendor === 'all' ? 'bg-[#a38c29]/10 text-[#8a7522] font-black' : ''">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>— All Vendors —</span>
                    </button>

                    {{-- Options List --}}
                    <div class="overflow-y-auto flex-1 p-1 space-y-0.5 max-h-52">
                        <template x-for="v in filteredVendors" :key="v.id">
                            <button type="button"
                                    @click="select(v.name)"
                                    :class="filters.vendor === v.name ? 'bg-[#a38c29]/15 text-[#8a7522] font-black' : 'hover:bg-slate-50 text-slate-700'"
                                    class="w-full px-2.5 py-1.5 text-left text-xs rounded-xl transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div :class="filters.vendor === v.name ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                         class="w-5 h-5 rounded-full font-bold text-[9px] flex items-center justify-center shrink-0 transition-colors"
                                         x-text="(v.name || '?').charAt(0).toUpperCase()">
                                    </div>
                                    <span class="truncate text-xs" :class="filters.vendor === v.name ? 'text-[#8a7522] font-bold' : 'text-slate-800'" x-text="v.name"></span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono shrink-0" x-text="v.id"></span>
                            </button>
                        </template>
                        <div x-show="filteredVendors.length === 0" class="py-4 text-center text-slate-400 text-xs">
                            No vendors found
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Project Filter Dropdown --}}
            <div class="relative min-w-[130px] flex-[0.95]" style="flex: 0.95 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="truncate text-xs font-bold"
                              :class="filters.project !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="filters.project === 'all' ? 'All Projects' : filters.project">All Projects</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[180px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.project = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.project === 'all' ? 'selected-all' : ''">
                            <span>All Projects</span>
                        </div>
                        @foreach($allProjects as $proj)
                            <div @click="filters.project = '{{ $proj }}'; currentPage = 1; updateCharts(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filters.project === '{{ $proj }}' ? 'selected' : ''">
                                <span>{{ $proj }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. Ageing Bucket Filter Dropdown --}}
            <div class="relative min-w-[150px] flex-[1.1]" style="flex: 1.1 1 0%;" x-data="{ open: false }" :class="open ? 'z-30' : ''">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="erp-dropdown-trigger px-2.5"
                        :class="open ? 'active' : ''">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate text-xs font-bold"
                              :class="filters.aging_bucket !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                              x-text="getAgeingBucketLabel()">All Ageing Buckets</span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="erp-dropdown-popover min-w-[185px] w-max">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="filters.aging_bucket = 'all'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === 'all' ? 'selected-all' : ''">
                            <span>All Ageing Buckets</span>
                        </div>
                        <div @click="filters.aging_bucket = '0-30'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '0-30' ? 'selected' : ''">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>0 - 30 Days</span>
                            </div>
                        </div>
                        <div @click="filters.aging_bucket = '31-60'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '31-60' ? 'selected' : ''">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>31 - 60 Days</span>
                            </div>
                        </div>
                        <div @click="filters.aging_bucket = '61-90'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '61-90' ? 'selected' : ''">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                <span>61 - 90 Days</span>
                            </div>
                        </div>
                        <div @click="filters.aging_bucket = '90+'; currentPage = 1; updateCharts(); open = false"
                             class="erp-dropdown-option"
                             :class="filters.aging_bucket === '90+' ? 'selected' : ''">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>90+ Days (Critical)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Action Buttons: Overdue (90+) Toggle & Reset Filters --}}
            <div class="flex items-center gap-2 shrink-0">
                
                {{-- Overdue Only Toggle Button --}}
                <button type="button" 
                        @click="filters.overdue_only = !filters.overdue_only; currentPage = 1; updateCharts()"
                        :class="filters.overdue_only ? 'bg-rose-600 text-white border-rose-700 shadow-sm ring-2 ring-rose-200' : 'bg-[#f8fafc] text-slate-700 border-slate-300 hover:bg-slate-100'"
                        class="h-[38px] px-3.5 rounded-xl border text-xs font-extrabold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs shrink-0 active:scale-95"
                        title="Filter Critical 90+ Days Overdue Vendors Only">
                    <span class="w-2 h-2 rounded-full shrink-0" :class="filters.overdue_only ? 'bg-white animate-pulse' : 'bg-rose-500'"></span>
                    <span>Overdue (90+)</span>
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

    {{-- ── 5. PROFESSIONAL DATA TABLE CARD (PLACED DIRECTLY AFTER THE FILTER) ── --}}
    <div id="vendor-aging-table-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:p-0 print:border-none print:shadow-none print:rounded-none">
        
        {{-- Table Card Header: Title + Subtitle + Export Buttons --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white print:px-2 print:py-2">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Vendor & Sundry Creditor Aging Details
                </h3>
                <div class="flex items-center gap-2 mt-1 pl-3">
                    <span class="text-xs font-black text-[#a38c29]" x-text="filteredRows.length + ' Vendors'">32 Vendors</span>
                    <span class="text-slate-300">•</span>
                    <p class="text-[10.5px] font-medium text-slate-500">Comprehensive chronological aging categorized into 0-30, 31-60, 61-90, and 90+ delinquency buckets.</p>
                </div>
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

        {{-- Table Container --}}
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="erp-table-header" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">
                    <tr class="erp-table-header border-b border-[#443f38] text-left uppercase text-[10px] font-extrabold tracking-wider" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">
                        <th class="erp-table-header py-3 px-3 w-12 text-center text-white" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">#</th>
                        <th class="erp-table-header py-3 px-4 min-w-[160px] text-white" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">Vendor Name</th>
                        <th class="erp-table-header py-3 px-3 min-w-[110px] text-white" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">Company ID</th>
                        <th class="erp-table-header py-3 px-4 min-w-[160px] text-right text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;" x-text="'Total Outstanding (' + currency + ')'">Total Outstanding (₹)</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-right text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;" x-text="'0 - 30 Days (' + currency + ')'">0 - 30 Days (₹)</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-right text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;" x-text="'31 - 60 Days (' + currency + ')'">31 - 60 Days (₹)</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-right text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;" x-text="'61 - 90 Days (' + currency + ')'">61 - 90 Days (₹)</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-right text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;" x-text="'90+ Days (' + currency + ')'">90+ Days (₹)</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-center text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">Last Payment Date</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-white whitespace-nowrap" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">Last Payment Ref.</th>
                        <th class="erp-table-header py-3 px-3 min-w-[130px] text-center text-white print:hidden" style="background-color: var(--erp-table-header-bg, #534E47) !important; color: #ffffff !important;">Actions</th>
                    </tr>
                </thead>
                <template x-for="(row, idx) in paginatedRows" :key="row.id || idx">
                    <tbody class="divide-y divide-slate-100 border-b border-slate-200">
                        <tr class="hover:bg-amber-50/40 transition-colors cursor-pointer group"
                            :class="expandedVendorId === row.id ? 'bg-amber-50/60 font-medium' : (idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/40')"
                            @click="toggleExpand(row.id)">
                            
                            {{-- Row Index with Expand indicator --}}
                            <td class="py-3 px-3 text-center text-slate-400 font-mono text-[11px]">
                                <div class="flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#a38c29] transition-transform duration-200"
                                         :class="expandedVendorId === row.id ? 'rotate-90 text-[#a38c29]' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span x-text="(currentPage - 1) * pageSize + idx + 1"></span>
                                </div>
                            </td>
                            
                            {{-- Vendor Name --}}
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 group-hover:text-[#a38c29] transition-colors flex items-center gap-1.5">
                                    <span x-text="row.name"></span>
                                    <span x-show="row.invoices && row.invoices.length > 0" class="text-[9px] px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 font-extrabold" x-text="row.invoices.length + ' inv'"></span>
                                </div>
                            </td>

                            {{-- Company ID --}}
                            <td class="py-3 px-3">
                                <span class="font-mono text-[11px] font-bold text-slate-700 px-2 py-0.5 rounded bg-slate-100 border border-slate-200" x-text="row.company_id"></span>
                            </td>

                            {{-- Total Outstanding --}}
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap" x-text="formatMoney(row.total_outstanding)"></td>

                            {{-- 0 - 30 Days --}}
                            <td class="py-3 px-3 text-right font-mono text-emerald-600 font-semibold whitespace-nowrap" x-text="formatMoney(row.bucket_0_30)"></td>

                            {{-- 31 - 60 Days --}}
                            <td class="py-3 px-3 text-right font-mono text-amber-600 font-semibold whitespace-nowrap" x-text="formatMoney(row.bucket_31_60)"></td>

                            {{-- 61 - 90 Days --}}
                            <td class="py-3 px-3 text-right font-mono text-orange-600 font-semibold whitespace-nowrap" x-text="formatMoney(row.bucket_61_90)"></td>

                            {{-- 90+ Days --}}
                            <td class="py-3 px-3 text-right font-mono text-rose-600 font-bold whitespace-nowrap" x-text="formatMoney(row.bucket_90_plus)"></td>

                            {{-- Last Payment Date --}}
                            <td class="py-3 px-3 text-center font-mono text-slate-600 whitespace-nowrap" x-text="row.last_payment_date || '—'"></td>

                            {{-- Last Payment Ref --}}
                            <td class="py-3 px-3 font-mono text-slate-600 text-[11px] whitespace-nowrap" x-text="row.last_payment_ref || '—'"></td>

                            {{-- Actions --}}
                            <td class="py-3 px-3 text-center print:hidden" @click.stop>
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Theme Color View Modal Icon Button --}}
                                    <button type="button" @click="openVendorModal(row)"
                                            class="w-7 h-7 rounded-lg text-[#a38c29] bg-[#a38c29]/10 hover:bg-[#a38c29] hover:text-white border border-[#a38c29]/30 transition-all flex items-center justify-center cursor-pointer shadow-2xs group"
                                            title="View Vendor Details">
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Expand Inline Button --}}
                                    <button type="button" @click="toggleExpand(row.id)"
                                            class="px-2 py-1 rounded-lg text-[11px] font-bold transition-all inline-flex items-center gap-1 cursor-pointer shadow-2xs"
                                            :class="expandedVendorId === row.id ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-[#8a7522] border border-slate-200'"
                                            :title="expandedVendorId === row.id ? 'Hide Invoices' : 'View Invoices'">
                                        <span x-text="expandedVendorId === row.id ? 'Hide' : 'Invoices'"></span>
                                        <svg class="w-3 h-3 transition-transform duration-200" :class="expandedVendorId === row.id ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>

                        {{-- ── EXPANDED PART: UNPAID INVOICE DETAILS DIRECTLY IN THE TABLE ── --}}
                        <tr x-show="expandedVendorId === row.id" x-cloak class="bg-amber-50/20">
                            <td colspan="11" class="p-3 sm:p-4 bg-slate-50/75 border-y border-amber-200/60">
                                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                                    
                                    {{-- Sub-header --}}
                                    <div class="px-4 py-2.5 bg-gradient-to-r from-amber-50/50 via-white to-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-black uppercase text-slate-900 tracking-wide">
                                                Unpaid Invoice Details — <span class="text-[#8a7522]" x-text="row.name"></span>
                                            </h4>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[11px] font-bold text-slate-600" x-text="(row.invoices ? row.invoices.length : 0) + ' Invoices'"></span>
                                            <button type="button" @click.stop="toggleExpand(row.id)" class="text-slate-400 hover:text-slate-700 text-xs px-2 py-0.5 rounded hover:bg-slate-100 font-bold transition">
                                                ✕ Close
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Sub-table of Invoices --}}
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left border-collapse text-xs">
                                            <thead>
                                                <tr class="bg-slate-100 text-slate-700 uppercase text-[10px] font-black tracking-wider border-b border-slate-200">
                                                    <th class="py-2.5 px-3 w-10 text-center">#</th>
                                                    <th class="py-2.5 px-4">Invoice No.</th>
                                                    <th class="py-2.5 px-3">Invoice Date</th>
                                                    <th class="py-2.5 px-3">Due Date</th>
                                                    <th class="py-2.5 px-3 text-right" x-text="'Invoice Amount (' + currency + ')'"></th>
                                                    <th class="py-2.5 px-3 text-right" x-text="'Paid Amount (' + currency + ')'"></th>
                                                    <th class="py-2.5 px-3 text-right" x-text="'Outstanding Amount (' + currency + ')'"></th>
                                                    <th class="py-2.5 px-3 text-center">Days Outstanding</th>
                                                    <th class="py-2.5 px-4 text-center">Aging Bucket</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                <template x-for="(inv, iIdx) in (row.invoices || [])" :key="inv.invoice_no || iIdx">
                                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                                        <td class="py-2.5 px-3 text-center font-mono text-[11px] text-slate-400" x-text="iIdx + 1"></td>
                                                        <td class="py-2.5 px-4 font-mono font-bold text-blue-800" x-text="inv.invoice_no"></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600" x-text="inv.invoice_date"></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600" x-text="inv.due_date"></td>
                                                        <td class="py-2.5 px-3 text-right font-mono text-slate-800" x-text="formatMoney(inv.invoice_amount)"></td>
                                                        <td class="py-2.5 px-3 text-right font-mono text-slate-500" x-text="formatMoney(inv.paid_amount)"></td>
                                                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900" x-text="formatMoney(inv.outstanding_amount)"></td>
                                                        <td class="py-2.5 px-3 text-center font-mono font-black"
                                                            :class="inv.days_outstanding > 90 ? 'text-rose-600' : (inv.days_outstanding > 60 ? 'text-orange-600' : (inv.days_outstanding > 30 ? 'text-amber-600' : 'text-emerald-600'))"
                                                            x-text="inv.days_outstanding">
                                                        </td>
                                                        <td class="py-2.5 px-4 text-center">
                                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide border shadow-2xs"
                                                                  :class="getBucketBadgeClass(inv.bucket)">
                                                                <span x-text="inv.bucket"></span>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template x-if="!row.invoices || row.invoices.length === 0">
                                                    <tr>
                                                        <td colspan="9" class="py-6 text-center text-slate-400 italic font-medium">
                                                            No outstanding bills or invoices recorded for this vendor.
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </td>
                        </tr>

                    </tbody>
                </template>

                <tbody x-show="paginatedRows.length === 0">
                    <tr>
                        <td colspan="11" class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                            No vendor accounts match the selected filter criteria.
                        </td>
                    </tr>
                </tbody>

                {{-- Table Grand Totals Footer --}}
                <tfoot class="bg-slate-100/90 font-black border-t-2 border-slate-300">
                    <tr class="text-slate-900">
                        <td colspan="3" class="py-3.5 px-4 uppercase tracking-wider text-xs font-black text-slate-800">
                            <span class="text-[#8a7522]">Grand Total</span> (<span x-text="filteredRows.length">{{ count($rows) }}</span> Vendors)
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono text-xs font-black text-slate-900 whitespace-nowrap" x-text="formatMoney(summaryKPIs.total_outstanding)">{{ number_format($kpis['total_outstanding'] ?? 0, 2) }}</td>
                        <td class="py-3.5 px-3 text-right font-mono text-xs font-black text-emerald-700 whitespace-nowrap" x-text="formatMoney(summaryKPIs.b0_30)">{{ number_format($kpis['b0_30'] ?? 0, 2) }}</td>
                        <td class="py-3.5 px-3 text-right font-mono text-xs font-black text-amber-700 whitespace-nowrap" x-text="formatMoney(summaryKPIs.b31_60)">{{ number_format($kpis['b31_60'] ?? 0, 2) }}</td>
                        <td class="py-3.5 px-3 text-right font-mono text-xs font-black text-orange-700 whitespace-nowrap" x-text="formatMoney(summaryKPIs.b61_90)">{{ number_format($kpis['b61_90'] ?? 0, 2) }}</td>
                        <td class="py-3.5 px-3 text-right font-mono text-xs font-black text-rose-700 whitespace-nowrap" x-text="formatMoney(summaryKPIs.b90_plus)">{{ number_format($kpis['b90_plus'] ?? 0, 2) }}</td>
                        <td colspan="3" class="print:hidden"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Table Pagination Footer --}}
        <div class="p-3.5 sm:p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 bg-white">
            <div x-text="'Showing ' + (filteredRows.length > 0 ? (currentPage - 1) * pageSize + 1 : 0) + ' to ' + Math.min(currentPage * pageSize, filteredRows.length) + ' of ' + filteredRows.length + ' entries'"></div>

            <div class="flex items-center gap-1" x-show="filteredRows.length > 0">
                <button type="button" @click="currentPage > 1 ? currentPage-- : null"
                        :disabled="currentPage === 1"
                        class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 transition-all font-semibold cursor-pointer">
                    Previous
                </button>

                <template x-for="p in totalPages" :key="p">
                    <button type="button" @click="currentPage = p"
                            class="w-7 h-7 rounded-lg text-xs font-bold transition-all cursor-pointer"
                            :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-2xs' : 'border border-slate-200 text-slate-700 hover:bg-slate-50'"
                            x-text="p">
                    </button>
                </template>

                <button type="button" @click="currentPage < totalPages ? currentPage++ : null"
                        :disabled="currentPage >= totalPages"
                        class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 transition-all font-semibold cursor-pointer">
                    Next
                </button>
            </div>
        </div>

    </div>

    {{-- ── 6. VIEW VENDOR AGING DETAILS MODAL (THEME STYLE) ── --}}
    <div x-show="vendorModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-none text-left"
         style="display: none;" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
         <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="vendorModalOpen = false">
              
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Vendor Aging Log</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5">
                              Aging &amp; Outstanding Details — <span class="text-[#d9bf3b]" x-text="modalVendor ? modalVendor.name : ''"></span>
                          </h2>
                      </div>
                      <button type="button" @click="vendorModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer">✕</button>
                  </div>
              </div>

              <template x-if="modalVendor">
                  <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto font-sans text-xs bg-slate-50/40">
                      
                      {{-- Primary Vendor & Summary 4-Column Card --}}
                      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs">
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Vendor Name</span>
                              <strong class="text-slate-900 text-xs font-bold block mt-1" x-text="modalVendor.name"></strong>
                              <span class="text-[10px] text-slate-500 font-mono block mt-0.5" x-text="modalVendor.company_id"></span>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Outstanding</span>
                              <strong class="text-slate-900 text-sm font-mono font-black block mt-0.5" x-text="currency + ' ' + formatMoney(modalVendor.total_outstanding)"></strong>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Project</span>
                              <span class="text-slate-700 text-xs font-semibold block mt-1 truncate" x-text="modalVendor.project || '—'"></span>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Last Payment</span>
                              <strong class="text-slate-800 text-xs font-mono font-bold block mt-1" x-text="modalVendor.last_payment_date || '—'"></strong>
                              <span class="text-[10px] text-slate-400 font-mono block mt-0.5" x-text="modalVendor.last_payment_ref || ''"></span>
                          </div>
                      </div>

                      {{-- Aging Buckets Breakdown (4 Accent Cards) --}}
                      <div>
                          <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Aging Delinquency Buckets</span>
                          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                              <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-200/70 shadow-2xs">
                                  <span class="text-[9px] font-black text-emerald-800 uppercase tracking-wide block">0 - 30 Days</span>
                                  <strong class="text-emerald-700 text-sm font-mono font-extrabold block mt-1" x-text="currency + ' ' + formatMoney(modalVendor.bucket_0_30)"></strong>
                              </div>
                              <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-200/70 shadow-2xs">
                                  <span class="text-[9px] font-black text-amber-800 uppercase tracking-wide block">31 - 60 Days</span>
                                  <strong class="text-amber-700 text-sm font-mono font-extrabold block mt-1" x-text="currency + ' ' + formatMoney(modalVendor.bucket_31_60)"></strong>
                              </div>
                              <div class="p-3 bg-orange-50/50 rounded-xl border border-orange-200/70 shadow-2xs">
                                  <span class="text-[9px] font-black text-orange-800 uppercase tracking-wide block">61 - 90 Days</span>
                                  <strong class="text-orange-700 text-sm font-mono font-extrabold block mt-1" x-text="currency + ' ' + formatMoney(modalVendor.bucket_61_90)"></strong>
                              </div>
                              <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-200/70 shadow-2xs">
                                  <span class="text-[9px] font-black text-rose-800 uppercase tracking-wide block">90+ Days (Critical)</span>
                                  <strong class="text-rose-700 text-sm font-mono font-extrabold block mt-1" x-text="currency + ' ' + formatMoney(modalVendor.bucket_90_plus)"></strong>
                              </div>
                          </div>
                      </div>

                      {{-- Invoices Sub-Table inside Modal --}}
                      <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
                          <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                              <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Unpaid Invoices Breakdown</span>
                              <span class="text-[11px] font-bold text-slate-500 font-mono" x-text="(modalVendor.invoices ? modalVendor.invoices.length : 0) + ' Invoices'"></span>
                          </div>
                          <div class="overflow-x-auto custom-scrollbar">
                              <table class="w-full text-left border-collapse text-xs">
                                  <thead>
                                      <tr class="bg-slate-100/80 text-slate-700 uppercase text-[9px] font-black tracking-wider border-b border-slate-200">
                                          <th class="py-2 px-3 w-8 text-center">#</th>
                                          <th class="py-2 px-3">Invoice No.</th>
                                          <th class="py-2 px-3">Invoice Date</th>
                                          <th class="py-2 px-3">Due Date</th>
                                          <th class="py-2 px-3 text-right">Invoice Amount</th>
                                          <th class="py-2 px-3 text-right">Paid Amount</th>
                                          <th class="py-2 px-3 text-right">Outstanding</th>
                                          <th class="py-2 px-3 text-center">Days Overdue</th>
                                          <th class="py-2 px-3 text-center">Bucket</th>
                                      </tr>
                                  </thead>
                                  <tbody class="divide-y divide-slate-100">
                                      <template x-for="(inv, iIdx) in (modalVendor.invoices || [])" :key="inv.invoice_no || iIdx">
                                          <tr class="hover:bg-slate-50 transition-colors">
                                              <td class="py-2 px-3 text-center font-mono text-[10px] text-slate-400" x-text="iIdx + 1"></td>
                                              <td class="py-2 px-3 font-mono font-bold text-blue-800" x-text="inv.invoice_no"></td>
                                              <td class="py-2 px-3 font-mono text-slate-600 text-[11px]" x-text="inv.invoice_date"></td>
                                              <td class="py-2 px-3 font-mono text-slate-600 text-[11px]" x-text="inv.due_date"></td>
                                              <td class="py-2 px-3 text-right font-mono text-slate-800" x-text="formatMoney(inv.invoice_amount)"></td>
                                              <td class="py-2 px-3 text-right font-mono text-slate-500" x-text="formatMoney(inv.paid_amount)"></td>
                                              <td class="py-2 px-3 text-right font-mono font-bold text-slate-900" x-text="formatMoney(inv.outstanding_amount)"></td>
                                              <td class="py-2 px-3 text-center font-mono font-black"
                                                  :class="inv.days_outstanding > 90 ? 'text-rose-600' : (inv.days_outstanding > 60 ? 'text-orange-600' : (inv.days_outstanding > 30 ? 'text-amber-600' : 'text-emerald-600'))"
                                                  x-text="inv.days_outstanding"></td>
                                              <td class="py-2 px-3 text-center">
                                                  <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border"
                                                        :class="getBucketBadgeClass(inv.bucket)">
                                                      <span x-text="inv.bucket"></span>
                                                  </span>
                                              </td>
                                          </tr>
                                      </template>
                                      <template x-if="!modalVendor.invoices || modalVendor.invoices.length === 0">
                                          <tr>
                                              <td colspan="9" class="py-6 text-center text-slate-400 italic">
                                                  No outstanding bills or invoices recorded for this vendor.
                                              </td>
                                          </tr>
                                      </template>
                                  </tbody>
                              </table>
                          </div>
                      </div>

                  </div>
              </template>
              
              {{-- Footer --}}
              <div class="px-6 py-3 border-t border-slate-100 flex justify-end bg-white">
                  <button type="button" @click="vendorModalOpen = false" 
                          class="px-5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">
                      Close
                  </button>
              </div>
         </div>
    </div>

</div>

{{-- ── 7. ALPINE.JS APP CONTROLLER & ZERO-RELOAD REACTIVE LOGIC ── --}}
<script>
function vendorAgingApp() {
    return {
        // Master Data Sources from Controller
        allRows: @json($allVendors),
        allVendorsList: @json($vendorList),
        allProjectsList: @json($allProjects),

        // Filter States (Zero page refresh!)
        asOnDate: '{{ $asOnDate }}',
        currency: '{{ $currency }}',
        filters: {
            search: '',
            vendor: 'all',
            project: 'all',
            aging_bucket: 'all',
            overdue_only: false,
        },

        // Pagination
        currentPage: 1,
        pageSize: 10,

        // Drilldown / Expanded Inline Row State
        expandedVendorId: null,
        selectedVendorDetail: null,
        isDetailsExpanded: true,

        // View Modal State (Theme Style)
        vendorModalOpen: false,
        modalVendor: null,

        openVendorModal(vendor) {
            this.modalVendor = vendor;
            this.vendorModalOpen = true;
        },

        // Chart References
        charts: {
            donut: null,
            bar: null,
        },

        init() {
            this.$nextTick(() => {
                this.initCharts();
            });
        },

        // 100% Client-side Filter without refreshing page!
        get filteredRows() {
            let res = [...this.allRows];

            // 1. Search Query
            if (this.filters.search && this.filters.search.trim() !== '') {
                const q = this.filters.search.toLowerCase().trim();
                res = res.filter(r => 
                    (r.name && r.name.toLowerCase().includes(q)) ||
                    (r.company_id && r.company_id.toLowerCase().includes(q)) ||
                    (r.last_payment_ref && r.last_payment_ref.toLowerCase().includes(q)) ||
                    (r.project && r.project.toLowerCase().includes(q))
                );
            }

            // 2. Vendor Filter
            if (this.filters.vendor && this.filters.vendor !== 'all' && this.filters.vendor !== 'All Vendors') {
                const vTarget = this.filters.vendor.toLowerCase().trim();
                res = res.filter(r => 
                    (r.name && r.name.toLowerCase() === vTarget) || 
                    (r.company_id && r.company_id.toLowerCase() === vTarget)
                );
            }

            // 3. Project Filter
            if (this.filters.project && this.filters.project !== 'all' && this.filters.project !== 'All Projects') {
                const pTarget = this.filters.project.toLowerCase().trim();
                res = res.filter(r => r.project && r.project.toLowerCase().includes(pTarget));
            }

            // 4. Aging Bucket Filter
            if (this.filters.aging_bucket && this.filters.aging_bucket !== 'all') {
                if (this.filters.aging_bucket === '0-30') {
                    res = res.filter(r => Number(r.bucket_0_30 || 0) > 0);
                } else if (this.filters.aging_bucket === '31-60') {
                    res = res.filter(r => Number(r.bucket_31_60 || 0) > 0);
                } else if (this.filters.aging_bucket === '61-90') {
                    res = res.filter(r => Number(r.bucket_61_90 || 0) > 0);
                } else if (this.filters.aging_bucket === '90+') {
                    res = res.filter(r => Number(r.bucket_90_plus || 0) > 0);
                }
            }

            // 5. Overdue Only Filter (delinquent balance > 0)
            if (this.filters.overdue_only) {
                res = res.filter(r => Number(r.total_outstanding || 0) > 0);
            }

            return res;
        },

        get paginatedRows() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredRows.slice(start, start + this.pageSize);
        },

        get totalPages() {
            return Math.ceil(this.filteredRows.length / this.pageSize) || 1;
        },

        get summaryKPIs() {
            const rows = this.filteredRows;
            const totalVendors = rows.length;
            const totalOutstanding = rows.reduce((acc, r) => acc + Number(r.total_outstanding || 0), 0);
            const b0_30 = rows.reduce((acc, r) => acc + Number(r.bucket_0_30 || 0), 0);
            const b31_60 = rows.reduce((acc, r) => acc + Number(r.bucket_31_60 || 0), 0);
            const b61_90 = rows.reduce((acc, r) => acc + Number(r.bucket_61_90 || 0), 0);
            const b90_plus = rows.reduce((acc, r) => acc + Number(r.bucket_90_plus || 0), 0);

            return {
                total_vendors: totalVendors,
                total_outstanding: totalOutstanding,
                b0_30: b0_30,
                b0_30_pct: totalOutstanding > 0 ? (b0_30 / totalOutstanding * 100).toFixed(1) : '0.0',
                b31_60: b31_60,
                b31_60_pct: totalOutstanding > 0 ? (b31_60 / totalOutstanding * 100).toFixed(1) : '0.0',
                b61_90: b61_90,
                b61_90_pct: totalOutstanding > 0 ? (b61_90 / totalOutstanding * 100).toFixed(1) : '0.0',
                b90_plus: b90_plus,
                b90_plus_pct: totalOutstanding > 0 ? (b90_plus / totalOutstanding * 100).toFixed(1) : '0.0',
            };
        },

        get top5Vendors() {
            const rows = this.filteredRows;
            const overdue = rows.filter(r => Number(r.bucket_90_plus || 0) > 0);
            if (overdue.length > 0) {
                const sorted = [...overdue].sort((a, b) => Number(b.bucket_90_plus || 0) - Number(a.bucket_90_plus || 0));
                return sorted.slice(0, 5).map(v => ({
                    name: v.name,
                    amount: Number(v.bucket_90_plus || 0)
                }));
            }
            const sortedOut = [...rows].filter(r => Number(r.total_outstanding || 0) > 0)
                .sort((a, b) => Number(b.total_outstanding || 0) - Number(a.total_outstanding || 0));
            return sortedOut.slice(0, 5).map(v => ({
                name: v.name,
                amount: Number(v.total_outstanding || 0)
            }));
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.vendor = 'all';
            this.filters.project = 'all';
            this.filters.aging_bucket = 'all';
            this.filters.overdue_only = false;
            this.currentPage = 1;
            this.expandedVendorId = null;
            this.selectedVendorDetail = null;

            this.updateCharts();
        },

        getAgeingBucketLabel() {
            const b = this.filters.aging_bucket;
            if (b === '0-30') return '0 - 30 Days';
            if (b === '31-60') return '31 - 60 Days';
            if (b === '61-90') return '61 - 90 Days';
            if (b === '90+') return '90+ Days (Critical)';
            return 'All Ageing Buckets';
        },

        toggleExpand(vendorId) {
            if (this.expandedVendorId === vendorId) {
                this.expandedVendorId = null;
            } else {
                this.expandedVendorId = vendorId;
                const r = this.allRows.find(v => v.id === vendorId);
                if (r) this.selectedVendorDetail = r;
            }
        },

        selectVendor(row, scroll = false) {
            this.toggleExpand(row.id);
        },

        formatMoney(num) {
            return Number(num || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        formatShortMoney(num) {
            const val = Number(num || 0);
            if (val >= 10000000) {
                return (val / 10000000).toFixed(2) + 'Cr';
            }
            if (val >= 1000000) {
                return (val / 1000000).toFixed(2) + 'M';
            }
            if (val >= 1000) {
                return (val / 1000).toFixed(1) + 'K';
            }
            return val.toFixed(0);
        },

        getTop5Color(idx) {
            const colors = ['#E11D48', '#F43F5E', '#FB7185', '#FDA4AF', '#FECDD3'];
            return colors[idx] || '#F43F5E';
        },

        getBucketBadgeClass(bucket) {
            if (!bucket) return 'bg-slate-100 text-slate-700 border-slate-200';
            const b = bucket.toLowerCase();
            if (b.includes('0 - 30') || b.includes('0-30')) {
                return 'bg-emerald-50 text-emerald-700 border-emerald-200';
            }
            if (b.includes('31 - 60') || b.includes('31-60')) {
                return 'bg-amber-50 text-amber-700 border-amber-200';
            }
            if (b.includes('61 - 90') || b.includes('61-90')) {
                return 'bg-orange-50 text-orange-700 border-orange-200';
            }
            if (b.includes('90+') || b.includes('90')) {
                return 'bg-rose-50 text-rose-700 border-rose-200';
            }
            return 'bg-slate-100 text-slate-700 border-slate-200';
        },

        initCharts() {
            const kpis = this.summaryKPIs;

            // 1. Donut Chart (Aging Summary)
            const donutEl = document.getElementById('agingDonutChart');
            if (donutEl) {
                const values = [kpis.b0_30, kpis.b31_60, kpis.b61_90, kpis.b90_plus];
                const donutOptions = {
                    series: values.some(v => v > 0) ? values : [1],
                    labels: ['0 - 30 Days', '31 - 60 Days', '61 - 90 Days', '90+ Days'],
                    colors: ['#10B981', '#F59E0B', '#F97316', '#EF4444'],
                    chart: { type: 'donut', height: 235, fontFamily: 'Inter, sans-serif' },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        markers: { radius: 12 },
                        formatter: (seriesName, opts) => {
                            const val = opts.w.globals.series[opts.seriesIndex];
                            const pct = kpis.total_outstanding > 0 ? (val / kpis.total_outstanding * 100).toFixed(1) : '0';
                            return seriesName + ': ' + this.formatShortMoney(val) + ' (' + pct + '%)';
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => this.currency + ' ' + this.formatMoney(val)
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Outstanding',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => this.currency + ' ' + this.formatShortMoney(kpis.total_outstanding)
                                    }
                                }
                            }
                        }
                    }
                };
                this.charts.donut = new ApexCharts(donutEl, donutOptions);
                this.charts.donut.render();
            }

            // 2. Bar / Column Chart (Aging by Bucket)
            const barEl = document.getElementById('agingBucketBarChart');
            if (barEl) {
                const barOptions = {
                    series: [{
                        name: 'Outstanding Amount',
                        data: [
                            Number(kpis.b0_30 || 0),
                            Number(kpis.b31_60 || 0),
                            Number(kpis.b61_90 || 0),
                            Number(kpis.b90_plus || 0)
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 235,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif'
                    },
                    colors: ['#10B981', '#F59E0B', '#F97316', '#EF4444'],
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 6,
                            columnWidth: '50%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: (val) => this.formatShortMoney(val),
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            fontWeight: 800,
                            colors: ['#334155']
                        }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: ['0 - 30 Days', '31 - 60 Days', '61 - 90 Days', '90+ Days'],
                        labels: {
                            style: { fontSize: '10.5px', fontWeight: 700, colors: '#64748B' }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: (val) => this.formatShortMoney(val),
                            style: { fontSize: '10px', fontWeight: 600, colors: '#64748B' }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => this.currency + ' ' + this.formatMoney(val)
                        }
                    }
                };
                this.charts.bar = new ApexCharts(barEl, barOptions);
                this.charts.bar.render();
            }
        },

        updateCharts() {
            // Keep selected detail in sync
            if (this.selectedVendorDetail && !this.filteredRows.some(r => r.id === this.selectedVendorDetail.id)) {
                this.selectedVendorDetail = this.filteredRows.length > 0 ? this.filteredRows[0] : null;
            }

            if (!this.charts.donut || !this.charts.bar) return;
            const kpis = this.summaryKPIs;

            // Update Donut Series
            const values = [kpis.b0_30, kpis.b31_60, kpis.b61_90, kpis.b90_plus];
            this.charts.donut.updateSeries(values.some(v => v > 0) ? values : [1]);

            // Update Bar Series
            this.charts.bar.updateSeries([{
                data: [
                    Number(kpis.b0_30 || 0),
                    Number(kpis.b31_60 || 0),
                    Number(kpis.b61_90 || 0),
                    Number(kpis.b90_plus || 0)
                ]
            }]);
        },

        async exportToExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading. Please try again.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'TABASCO ERP';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Vendor Aging Details', {
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
                    { key: 'vendor', width: 32 },
                    { key: 'code', width: 16 },
                    { key: 'total', width: 22 },
                    { key: 'b0_30', width: 20 },
                    { key: 'b31_60', width: 20 },
                    { key: 'b61_90', width: 20 },
                    { key: 'b90_plus', width: 20 },
                    { key: 'last_date', width: 18 },
                    { key: 'last_ref', width: 20 },
                ];

                // Main Title Banner
                sheet.mergeCells('A2:J2');
                const titleCell = sheet.getCell('A2');
                titleCell.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - VENDOR & SUNDRY CREDITOR AGING REPORT';
                titleCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 28;

                // Subtitle
                sheet.mergeCells('A3:J3');
                const subCell = sheet.getCell('A3');
                subCell.value = `As On Date: ${this.asOnDate} | Currency: ${this.currency} | Active Records: ${this.filteredRows.length}`;
                subCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                subCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF8A7522' } };
                subCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(3).height = 22;

                // Headers
                const headers = [
                    '#',
                    'Vendor Name',
                    'Company ID',
                    `Total Outstanding (${this.currency})`,
                    `0 - 30 Days (${this.currency})`,
                    `31 - 60 Days (${this.currency})`,
                    `61 - 90 Days (${this.currency})`,
                    `90+ Days (${this.currency})`,
                    'Last Payment Date',
                    'Last Payment Ref.'
                ];

                const hRow = sheet.getRow(5);
                hRow.values = headers;
                hRow.height = 26;
                hRow.eachCell({ includeEmpty: true }, (c) => {
                    c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    c.alignment = { vertical: 'middle', horizontal: 'left' };
                    c.border = slateBorder;
                });

                let rIdx = 6;
                this.filteredRows.forEach((r, i) => {
                    const row = sheet.getRow(rIdx);
                    row.values = [
                        i + 1,
                        r.name,
                        r.company_id,
                        Number(r.total_outstanding || 0),
                        Number(r.bucket_0_30 || 0),
                        Number(r.bucket_31_60 || 0),
                        Number(r.bucket_61_90 || 0),
                        Number(r.bucket_90_plus || 0),
                        r.last_payment_date || '—',
                        r.last_payment_ref || '—'
                    ];

                    row.height = 22;
                    const rowBg = (i % 2 === 0) ? 'FFF8FAFC' : 'FFFFFFFF';

                    row.eachCell({ includeEmpty: true }, (c, col) => {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        c.border = dataBorder;
                        c.alignment = { vertical: 'middle', horizontal: 'left' };
                        c.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };

                        if (col >= 4 && col <= 8) {
                            c.numFmt = '#,##0.00';
                            c.alignment = { vertical: 'middle', horizontal: 'right' };
                        }
                    });

                    rIdx++;
                });

                // Grand Total Row
                const totRow = sheet.getRow(rIdx);
                totRow.height = 30;
                sheet.getCell(`A${rIdx}`).value = 'TOTAL VENDOR PAYABLES';
                sheet.getCell(`D${rIdx}`).value = Number(this.summaryKPIs.total_outstanding || 0);
                sheet.getCell(`E${rIdx}`).value = Number(this.summaryKPIs.b0_30 || 0);
                sheet.getCell(`F${rIdx}`).value = Number(this.summaryKPIs.b31_60 || 0);
                sheet.getCell(`G${rIdx}`).value = Number(this.summaryKPIs.b61_90 || 0);
                sheet.getCell(`H${rIdx}`).value = Number(this.summaryKPIs.b90_plus || 0);

                sheet.mergeCells(`A${rIdx}:C${rIdx}`);
                for (let c = 1; c <= 10; c++) {
                    const cell = sheet.getRow(rIdx).getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.border = slateBorder;
                    if (c >= 4 && c <= 8) {
                        cell.numFmt = '#,##0.00';
                        cell.alignment = { vertical: 'middle', horizontal: 'right' };
                    }
                }

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Vendor_Aging_Report_${new Date().toISOString().slice(0, 10)}.xlsx`;
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
