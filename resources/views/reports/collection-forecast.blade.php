<x-erp-layout title="Collection Forecast & Overdue Reports" headerTitle="Business Reports Center">

{{-- ExcelJS Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 5mm 6mm 5mm 6mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 8.5pt !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print\:hidden, header, nav, aside, footer, button, select, input, .custom-scrollbar::-webkit-scrollbar {
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
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    thead {
        display: table-header-group !important;
    }
    tr {
        page-break-inside: avoid !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }

    /* Print 4 KPI Cards Flex Row */
    .print-kpi-row {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        gap: 8px !important;
        width: 100% !important;
        margin-bottom: 8px !important;
    }
    .print-kpi-row > div {
        flex: 1 1 0% !important;
        box-sizing: border-box !important;
    }

    /* Print Charts Side-by-Side Row (Page 1) */
    .print-charts-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: stretch !important;
        justify-content: space-between !important;
        gap: 10px !important;
        width: 100% !important;
        margin-bottom: 10px !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    .print-chart-box-left {
        width: 48% !important;
        flex: 0 0 48% !important;
        box-sizing: border-box !important;
        padding: 8px 10px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        background: #ffffff !important;
    }
    .print-chart-box-right {
        width: 50.5% !important;
        flex: 0 0 50.5% !important;
        box-sizing: border-box !important;
        padding: 8px 10px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        background: #ffffff !important;
    }
    .print-donut-size {
        width: 130px !important;
        height: 130px !important;
    }
    .print-bar-size {
        width: 100% !important;
        height: 155px !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto p-6 space-y-6" x-data="collectionForecastApp()" x-init="init()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Receivable & Risk Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">COLLECTION FORECAST & OVERDUE AGEING AUDIT REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800" x-text="new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800" x-text="filters.as_of_date || 'Current Live Date'"></span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]" x-text="filteredInstallments.length + ' Installments'"></span></div>
            </div>
        </div>
    </div>

    <!-- ── EXECUTIVE PRINT KPI CARDS (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:grid grid-cols-4 gap-3 mb-3 print-kpi-row">
        <div class="border border-slate-300 rounded-xl p-2.5 bg-slate-50/50">
            <span class="text-[8.5px] font-black uppercase text-slate-500 block">Total Outstanding</span>
            <strong class="text-sm font-black text-slate-900 font-mono block mt-0.5" x-text="'₹ ' + formatNumber(kpis.total_outstanding)"></strong>
            <span class="text-[8px] text-slate-500 font-bold" x-text="kpis.total_customers + ' Customer Accounts'"></span>
        </div>
        <div class="border border-rose-300 rounded-xl p-2.5 bg-rose-50/30">
            <span class="text-[8.5px] font-black uppercase text-rose-600 block">Total Overdue</span>
            <strong class="text-sm font-black text-rose-700 font-mono block mt-0.5" x-text="'₹ ' + formatNumber(kpis.total_overdue)"></strong>
            <span class="text-[8px] text-rose-600 font-bold" x-text="kpis.overdue_customers + ' Overdue Accounts'"></span>
        </div>
        <div class="border border-emerald-300 rounded-xl p-2.5 bg-emerald-50/30">
            <span class="text-[8.5px] font-black uppercase text-emerald-600 block">Current (Not Due)</span>
            <strong class="text-sm font-black text-emerald-700 font-mono block mt-0.5" x-text="'₹ ' + formatNumber(kpis.current_not_due)"></strong>
            <span class="text-[8px] text-emerald-600 font-bold">Within Credit Period</span>
        </div>
        <div class="border border-blue-300 rounded-xl p-2.5 bg-blue-50/30">
            <span class="text-[8.5px] font-black uppercase text-blue-600 block">Expected Realization</span>
            <strong class="text-sm font-black text-blue-700 font-mono block mt-0.5" x-text="'₹ ' + formatNumber(kpis.expected_collection)"></strong>
            <span class="text-[8px] text-blue-600 font-bold">Probability Weighted</span>
        </div>
    </div>

    <!-- Header Section (Web Only) -->
    <div class="flex justify-between items-start print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Collection Forecast & Overdue Reports</h1>
            <p class="text-sm text-slate-500 mt-1">Ageing analysis of outstanding customer dues with automated reminder generation</p>
        </div>
    </div>

    <!-- KPIs (Web Only) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 print:hidden">
        <!-- Total Outstanding -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Outstanding</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-[#a38c29]" x-text="'₹ ' + formatNumber(kpis.total_outstanding)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-[#a38c29]" x-text="kpis.total_customers + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Total Overdue -->
        <div class="bg-white border-y border-r border-l-4 border-l-rose-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.2)] hover:border-r-rose-500/20 hover:border-y-rose-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Overdue</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-rose-600" x-text="'₹ ' + formatNumber(kpis.total_overdue)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-rose-500" x-text="kpis.overdue_customers + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Current / Not Due -->
        <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Current / Not Due</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-emerald-600" x-text="'₹ ' + formatNumber(kpis.current_not_due)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-emerald-500" x-text="(kpis.total_customers - kpis.overdue_customers) + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Expected Collection -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#3b82f6] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)] hover:border-r-[#3b82f6]/20 hover:border-y-[#3b82f6]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Expected Collection</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-blue-600" x-text="'₹ ' + formatNumber(kpis.expected_collection)"></h3>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#3b82f6] mt-1 inline-block">Probability Weighted</span>
            </div>
        </div>
    </div>

    <!-- Charts & Ageing Summary (Visible on Web & Print/PDF Side-by-Side on Page 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6 print-charts-container">
        <!-- Donut Chart & Ageing Summary Table -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm p-5 print:p-2.5 print-chart-box-left">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-800" x-text="isOverdueMode ? 'Ageing Summary (Overdue)' : '1-Year Collection Forecast'"></h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5" x-text="isOverdueMode ? 'Breakdown of dues by overdue age buckets' : 'Upcoming collection timeline horizons'"></p>
                </div>
                <template x-if="!isOverdueMode">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider shadow-2xs print:hidden">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        No Overdue Dues
                    </span>
                </template>
            </div>

            <div class="flex flex-col md:flex-row print:flex-row items-center justify-center gap-4 print:gap-2">
                <div id="donutChart" class="w-48 h-48 print-donut-size shrink-0"></div>
                <div class="flex-1 w-full">
                    <table class="w-full text-xs print:text-[9.5px]">
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="bucket in summaryTableBuckets" :key="bucket.key">
                                <tr class="py-1.5">
                                    <td class="py-1 flex items-center gap-1.5 text-slate-600">
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="'background-color: ' + bucket.color"></span>
                                        <span class="font-medium truncate" x-text="bucket.label"></span>
                                    </td>
                                    <td class="py-1 text-right font-semibold text-slate-800 whitespace-nowrap">
                                        <span x-text="'₹ ' + formatNumber(bucket.amount)"></span>
                                        <span class="text-slate-400 font-normal ml-0.5 text-[10.5px] print:text-[8.5px]" x-text="'(' + bucket.pct + '%)'"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td class="py-2 font-bold text-slate-800" x-text="isOverdueMode ? 'Total Overdue' : 'Total Outstanding'"></td>
                                <td class="py-2 text-right font-bold text-slate-800 whitespace-nowrap" x-text="'₹ ' + formatNumber(isOverdueMode ? kpis.total_overdue : kpis.total_outstanding)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bar Chart & Ageing Distribution -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 shadow-sm p-5 print:p-2.5 print-chart-box-right">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h3 class="text-sm font-bold text-slate-800" x-text="isOverdueMode ? 'Ageing Distribution' : 'Monthly Inflow Forecast (Next 12 Months)'"></h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5" x-text="isOverdueMode ? 'Overdue exposure grouped by risk buckets' : 'Month-by-month scheduled receivable timeline'"></p>
                </div>
                <template x-if="!isOverdueMode">
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 uppercase tracking-wider print:hidden">
                        12-Month Schedule
                    </span>
                </template>
            </div>
            <div id="barChart" class="w-full h-64 print-bar-size"></div>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (DIRECTLY ABOVE TABLE) (Web Only) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all mb-4 print:hidden">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 flex-1">
                
                {{-- 1. As On Date Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date"
                           x-model="filters.as_of_date"
                           @change="currentPage = 1; updateCharts()"
                           title="As On Date"
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- 2. Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <select x-model="filters.project_id" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 3. Customer Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <select x-model="filters.customer_id" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 4. Ageing Bucket Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <select x-model="filters.ageing_bucket" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Ageing Buckets</option>
                        <option value="Current">Current (Not Due)</option>
                        <option value="0-30">0-30 Days</option>
                        <option value="31-60">31-60 Days</option>
                        <option value="61-90">61-90 Days</option>
                        <option value="91-120">91-120 Days</option>
                        <option value="120+">> 120 Days</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 5. Risk Level Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <select x-model="filters.risk_level" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Risk Levels</option>
                        <option value="None">None</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                        <option value="Severe">Severe</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 6. Reminder Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <select x-model="filters.reminder_status" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Reminder Statuses</option>
                        <option value="Sent">Sent</option>
                        <option value="Pending">Pending</option>
                        <option value="Failed">Failed</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>
        </div>
    </div>

    <!-- ── WEB DATA TABLE CARD (Web View Only with Pagination) ── -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden print:hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Overdue Installments Directory
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all overdue installments and forecast status.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" @click="exportExcel()" class="h-[42px] px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" @click="window.print()" class="h-[42px] px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-4 py-3 text-white font-extrabold">Customer</th>
                        <th class="px-4 py-3 text-white font-extrabold">Sale No.</th>
                        <th class="px-4 py-3 text-white font-extrabold">Unit</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Inst. No.</th>
                        <th class="px-4 py-3 text-white font-extrabold">Due Date</th>
                        <th class="px-4 py-3 text-right text-white font-extrabold">Outstanding</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Days Overdue</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Ageing</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Risk</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Reminder Level</th>
                        <th class="px-3 py-3 text-center text-white font-extrabold">Last Reminder</th>
                        <th class="px-4 py-3 text-center text-white font-extrabold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="inst in paginatedInstallments" :key="inst.id">
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-4 py-2.5 text-xs font-bold text-slate-700" x-text="inst.customer_name"></td>
                            <td class="px-4 py-2.5 text-xs font-black text-slate-800 uppercase tracking-wide" x-text="inst.sale_number"></td>
                            <td class="px-4 py-2.5 text-xs font-bold text-slate-600 leading-tight truncate max-w-[140px]" :title="inst.unit_name" x-text="inst.unit_name"></td>
                            <td class="px-3 py-2.5 text-center text-xs font-black text-slate-400" x-text="inst.installment_no"></td>
                            <td class="px-4 py-2.5 text-xs font-bold text-slate-600" x-text="inst.due_date_formatted"></td>
                            <td class="px-4 py-2.5 text-right text-xs font-black text-slate-900 tracking-tight" x-text="'₹ ' + formatNumber(inst.calculated_outstanding)"></td>
                            <td class="px-3 py-2.5 text-center text-xs font-black text-slate-500" x-text="inst.days_overdue > 0 ? inst.days_overdue : '-'"></td>
                            <td class="px-3 py-2.5 text-center text-xs font-black" :class="getAgeingColor(inst.ageing_bucket)" x-text="inst.ageing_bucket === '120+' ? '> 120 Days' : (inst.ageing_bucket + (inst.ageing_bucket !== 'Current' ? ' Days' : ''))"></td>
                            <td class="px-3 py-2.5 text-center text-xs font-black"><span class="inline-block" :class="getRiskColor(inst.risk_level)" x-text="inst.risk_level"></span></td>
                            <td class="px-3 py-2.5 text-center text-xs font-black text-slate-600" x-text="inst.reminder_level"></td>
                            <td class="px-3 py-2.5 text-center text-xs font-bold text-slate-400" x-text="inst.last_reminder_date"></td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button type="button" @click.prevent="openModal(inst.modal_payload)" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <a :href="'tel:' + (inst.modal_payload ? inst.modal_payload.customer.mobile : '')" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Call">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredInstallments.length === 0">
                        <td colspan="12" class="px-5 py-8 text-center text-slate-500 italic">No installments found for the given criteria.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Dynamic Reactive Pagination --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500">
            <div class="font-medium">
                Showing <span class="font-bold text-slate-800" x-text="filteredInstallments.length ? (currentPage - 1) * perPage + 1 : 0"></span> to 
                <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredInstallments.length)"></span> of 
                <span class="font-bold text-slate-800" x-text="filteredInstallments.length"></span> entries
            </div>
            <div class="flex items-center gap-1" x-show="totalPages > 1">
                <button type="button" @click="if(currentPage > 1) { currentPage--; window.scrollTo({ top: 400, behavior: 'smooth' }); }" :disabled="currentPage === 1"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition">
                    Previous
                </button>
                <template x-for="p in totalPages" :key="p">
                    <button type="button" @click="currentPage = p; window.scrollTo({ top: 400, behavior: 'smooth' });" 
                            x-show="p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2)"
                            :class="currentPage === p ? 'bg-[#a38c29] text-white border-[#a38c29]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                            class="w-8 h-8 rounded-lg border text-xs font-bold transition flex items-center justify-center cursor-pointer">
                        <span x-text="p"></span>
                    </button>
                </template>
                <button type="button" @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({ top: 400, behavior: 'smooth' }); }" :disabled="currentPage === totalPages"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- ── COMPLETE EXECUTIVE PRINTABLE DATA TABLE (FULL RECORDSET FOR PRINT/PDF) ── -->
    <div class="hidden print:block mb-8">
        <div class="border border-slate-300 rounded-xl overflow-hidden">
            <table class="w-full text-[9.5px] text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b-2 border-[#8a7522] text-[9px] font-black uppercase tracking-wider">
                        <th class="px-3 py-2 text-center text-white w-10">SL NO</th>
                        <th class="px-3 py-2 text-white">CUSTOMER NAME</th>
                        <th class="px-3 py-2 text-center text-white">PHONE NUMBER</th>
                        <th class="px-3 py-2 text-white">UNIT NO</th>
                        <th class="px-3 py-2 text-center text-white">DUE DATE</th>
                        <th class="px-3 py-2 text-right text-white">OUTSTANDING (₹)</th>
                        <th class="px-3 py-2 text-center text-white">DAYS OVERDUE</th>
                        <th class="px-3 py-2 text-center text-white">AGEING BUCKET</th>
                        <th class="px-3 py-2 text-center text-white">RISK LEVEL</th>
                        <th class="px-3 py-2 text-center text-white">LAST REMINDER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(inst, idx) in filteredInstallments" :key="inst.id">
                        <tr class="border-b border-slate-200 text-slate-800">
                            <td class="px-3 py-2 text-center font-bold text-slate-500" x-text="idx + 1"></td>
                            <td class="px-3 py-2 font-bold text-slate-900" x-text="inst.customer_name"></td>
                            <td class="px-3 py-2 text-center font-mono text-slate-600" x-text="inst.modal_payload?.customer?.mobile || '-'"></td>
                            <td class="px-3 py-2 font-bold text-slate-700" x-text="inst.unit_name"></td>
                            <td class="px-3 py-2 text-center font-mono text-slate-700" x-text="inst.due_date_formatted"></td>
                            <td class="px-3 py-2 text-right font-mono font-bold text-slate-900" x-text="'₹ ' + formatNumber(inst.calculated_outstanding)"></td>
                            <td class="px-3 py-2 text-center font-bold" :class="inst.days_overdue > 0 ? 'text-rose-600' : 'text-slate-400'" x-text="inst.days_overdue > 0 ? (inst.days_overdue + 'd') : '-'"></td>
                            <td class="px-3 py-2 text-center font-bold" x-text="inst.ageing_bucket === '120+' ? '>120 Days' : (inst.ageing_bucket ? inst.ageing_bucket + (inst.ageing_bucket !== 'Current' ? ' Days' : '') : '-')"></td>
                            <td class="px-3 py-2 text-center font-bold"><span class="inline-block" :class="getRiskColor(inst.risk_level)" x-text="inst.risk_level"></span></td>
                            <td class="px-3 py-2 text-center text-slate-500" x-text="inst.last_reminder_date || '-'"></td>
                        </tr>
                    </template>
                    <tr class="bg-slate-100 font-bold border-t-2 border-slate-400">
                        <td colspan="5" class="px-4 py-2.5 text-right uppercase tracking-wider text-[10px] text-slate-800">TOTAL SUMMARY:</td>
                        <td class="px-3 py-2.5 text-right font-mono text-[10px] text-slate-900 font-black" x-text="'₹ ' + formatNumber(kpis.total_outstanding)"></td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



    <!-- OVERDUE INSTALLMENT DETAILS MODAL -->
    <template x-teleport="body">
        <div x-show="isModalOpen" 
             class="fixed inset-0 top-0 left-0 w-screen h-screen z-[99999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            
            <div class="w-full max-w-4xl bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border-0 transform transition-all my-auto" @click.outside="closeModal()">
                <!-- Modal Header -->
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-0">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">REPORTS & OVERDUE</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white">OVERDUE INSTALLMENT DETAILS</h3>
                        </div>
                        <button type="button" @click="closeModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-5 md:p-6 space-y-3.5 bg-white font-sans text-xs" x-show="modalData">
                    <template x-if="modalData">
                        <div class="space-y-3.5">
                            <!-- Section 1: Customer & Booking Details (Compact Card) -->
                            <div class="p-3.5 bg-gradient-to-r from-amber-50/70 via-white to-amber-50/70 rounded-2xl border border-amber-200/80 shadow-2xs">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-xs">
                                    <!-- Customer Info -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Customer</span>
                                            <span class="font-black text-slate-900 truncate" x-text="modalData.customer.name"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Phone</span>
                                            <a :href="'tel:' + modalData.customer.mobile" class="font-bold text-[#a38c29] hover:underline flex items-center gap-1 font-mono text-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                <span x-text="modalData.customer.mobile"></span>
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Email</span>
                                            <span class="font-semibold text-slate-700 truncate text-[11px]" x-text="modalData.customer.email"></span>
                                        </div>
                                        <div class="flex items-center gap-2" x-show="modalData.customer.address && modalData.customer.address !== '-'">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Address</span>
                                            <span class="font-medium text-slate-600 truncate text-[11px]" x-text="modalData.customer.address"></span>
                                        </div>
                                    </div>

                                    <!-- Booking Info -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Sale No.</span>
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-800 font-mono font-black text-[10px] rounded border border-slate-200" x-text="modalData.booking.sale_no"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Project</span>
                                            <span class="font-bold text-slate-800 truncate" x-text="modalData.booking.project"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Unit</span>
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-900 font-bold text-[10px] rounded border border-amber-200" x-text="modalData.booking.unit"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-slate-400 w-24 shrink-0">Booking Date</span>
                                            <span class="font-bold text-slate-700 font-mono text-[11px]" x-text="modalData.booking.booking_date"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Outstanding Summary Cards (5 Pill Cards in 1 Row) -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                <div class="bg-indigo-50/60 border border-indigo-150 rounded-xl p-2.5 text-center">
                                    <div class="text-[9px] font-extrabold text-indigo-700 uppercase tracking-wider">Total Outstanding</div>
                                    <div class="text-xs sm:text-sm font-black text-indigo-950 font-mono mt-0.5">₹ <span x-text="modalData.summary.total_outstanding"></span></div>
                                </div>
                                <div class="bg-amber-50/60 border border-amber-150 rounded-xl p-2.5 text-center">
                                    <div class="text-[9px] font-extrabold text-amber-700 uppercase tracking-wider">Total Overdue</div>
                                    <div class="text-xs sm:text-sm font-black text-amber-950 font-mono mt-0.5">₹ <span x-text="modalData.summary.total_overdue"></span></div>
                                </div>
                                <div class="bg-rose-50/60 border border-rose-150 rounded-xl p-2.5 text-center">
                                    <div class="text-[9px] font-extrabold text-rose-600 uppercase tracking-wider">Days Overdue</div>
                                    <div class="text-xs sm:text-sm font-black text-rose-700 font-mono mt-0.5"><span x-text="modalData.summary.days_overdue"></span> Days</div>
                                </div>
                                <div class="bg-emerald-50/60 border border-emerald-150 rounded-xl p-2.5 text-center">
                                    <div class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-wider">Ageing Bucket</div>
                                    <div class="text-xs font-black text-emerald-800 mt-1 uppercase" x-text="modalData.summary.ageing_bucket"></div>
                                </div>
                                <div class="bg-purple-50/60 border border-purple-150 rounded-xl p-2.5 text-center col-span-2 sm:col-span-1">
                                    <div class="text-[9px] font-extrabold text-purple-700 uppercase tracking-wider">Risk Level</div>
                                    <div class="text-xs font-black text-purple-800 mt-1 uppercase" x-text="modalData.summary.risk_level"></div>
                                </div>
                            </div>

                            <!-- Section 3: Installment Schedule Breakdown & Reminders Log -->
                            <div class="space-y-2 pt-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="modalActiveTab = 'schedule'" 
                                                :class="modalActiveTab === 'schedule' ? 'bg-[#a38c29] text-white shadow-2xs font-black' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold'"
                                                class="px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer flex items-center gap-1.5">
                                            <span>Schedule Breakdown</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold" :class="modalActiveTab === 'schedule' ? 'bg-black/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="modalData.installments ? modalData.installments.length : 0"></span>
                                        </button>
                                        <button type="button" @click="modalActiveTab = 'reminders'" 
                                                :class="modalActiveTab === 'reminders' ? 'bg-[#a38c29] text-white shadow-2xs font-black' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold'"
                                                class="px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider transition cursor-pointer flex items-center gap-1.5">
                                            <span>Reminders Log</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold" :class="modalActiveTab === 'reminders' ? 'bg-black/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="modalData.reminders ? modalData.reminders.length : 0"></span>
                                        </button>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-400 hidden sm:inline" x-show="modalActiveTab === 'schedule' && modalData.installments && modalData.installments.length > 4">
                                        Scroll inside table for full schedule
                                    </span>
                                </div>

                                <!-- Schedule Table View -->
                                <div x-show="modalActiveTab === 'schedule'" class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                                    <div class="max-h-40 overflow-y-auto custom-scrollbar">
                                        <table class="w-full text-xs text-left border-collapse">
                                            <thead class="bg-[#a38c29] text-white text-[9.5px] font-extrabold uppercase tracking-wider sticky top-0 z-10 border-b border-[#8a7522]">
                                                <tr>
                                                    <th class="px-3 py-2 text-white">No.</th>
                                                    <th class="px-3 py-2 text-white">Inst. Date</th>
                                                    <th class="px-3 py-2 text-white">Due Date</th>
                                                    <th class="px-3 py-2 text-right text-white">Amount</th>
                                                    <th class="px-3 py-2 text-right text-white">Paid</th>
                                                    <th class="px-3 py-2 text-right text-white">Outstanding</th>
                                                    <th class="px-3 py-2 text-center text-white">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                <template x-for="inst in modalData.installments" :key="inst.no">
                                                    <tr :class="inst.is_current ? 'bg-amber-50/80 font-bold border-l-2 border-l-[#a38c29]' : 'hover:bg-slate-50/80'" class="transition-colors">
                                                        <td class="px-3 py-1.5 text-[11px] font-black text-slate-600" x-text="inst.no"></td>
                                                        <td class="px-3 py-1.5 text-[11px] text-slate-600" x-text="inst.inst_date"></td>
                                                        <td class="px-3 py-1.5 text-[11px] text-slate-600" x-text="inst.due_date"></td>
                                                        <td class="px-3 py-1.5 text-right text-[11px] font-black text-slate-900 font-mono" x-text="'₹' + inst.amount"></td>
                                                        <td class="px-3 py-1.5 text-right text-[11px] font-bold text-emerald-600 font-mono" x-text="'₹' + inst.paid"></td>
                                                        <td class="px-3 py-1.5 text-right text-[11px] font-black text-rose-600 font-mono" x-text="'₹' + inst.outstanding"></td>
                                                        <td class="px-3 py-1.5 text-center">
                                                            <span :class="inst.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (inst.status === 'partial' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200')" 
                                                                  class="px-2 py-0.5 rounded text-[8.5px] font-bold uppercase tracking-wider border inline-block" 
                                                                  x-text="inst.status"></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Reminders Table View -->
                                <div x-show="modalActiveTab === 'reminders'" class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs" style="display: none;">
                                    <div class="max-h-40 overflow-y-auto custom-scrollbar">
                                        <table class="w-full text-xs text-left border-collapse">
                                            <thead class="bg-[#a38c29] text-white text-[9.5px] font-extrabold uppercase tracking-wider sticky top-0 z-10 border-b border-[#8a7522]">
                                                <tr>
                                                    <th class="px-3 py-2 text-white">Reminder No.</th>
                                                    <th class="px-3 py-2 text-white">Date</th>
                                                    <th class="px-3 py-2 text-white">Type</th>
                                                    <th class="px-3 py-2 text-white">Channel</th>
                                                    <th class="px-3 py-2 text-center text-white">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                <template x-if="!modalData.reminders || modalData.reminders.length === 0">
                                                    <tr>
                                                        <td colspan="5" class="px-3 py-6 text-center text-xs font-bold text-slate-400 italic">No reminders sent yet for this installment.</td>
                                                    </tr>
                                                </template>
                                                <template x-for="rem in modalData.reminders" :key="rem.no">
                                                    <tr class="hover:bg-slate-50 transition-colors">
                                                        <td class="px-3 py-1.5 text-[11px] font-mono font-bold text-slate-700" x-text="rem.no"></td>
                                                        <td class="px-3 py-1.5 text-[11px] text-slate-600" x-text="rem.date"></td>
                                                        <td class="px-3 py-1.5 text-[11px] font-bold text-slate-800" x-text="rem.type"></td>
                                                        <td class="px-3 py-1.5 text-[11px] text-slate-500" x-text="rem.channel"></td>
                                                        <td class="px-3 py-1.5 text-center">
                                                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[8.5px] font-bold uppercase tracking-widest inline-block" x-text="rem.status"></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 bg-slate-50 border-0 rounded-b-2xl flex items-center justify-between">
                    <div class="text-[11px] text-slate-500 font-semibold truncate hidden sm:block">
                        <template x-if="modalData">
                            <span>Sale #<strong class="text-slate-800" x-text="modalData.booking.sale_no"></strong> &middot; <span x-text="modalData.customer.name"></span></span>
                        </template>
                    </div>
                    <div class="flex items-center gap-2.5 ml-auto">
                        <button type="button" @click="closeModal()" 
                                class="px-6 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                            CANCEL
                        </button>
                        <a :href="'tel:' + (modalData ? modalData.customer.mobile : '')" 
                           class="px-6 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>CALL CUSTOMER</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function collectionForecastApp() {
    return {
        allInstallments: @json($allInstallmentsFormatted ?? []),
        projects: @json($projects ?? []),
        filters: {
            as_of_date: '{{ request('as_of_date', '') }}',
            project_id: '{{ request('project_id', '') }}',
            customer_id: '{{ request('customer_id', '') }}',
            ageing_bucket: '{{ request('ageing_bucket', '') }}',
            risk_level: '{{ request('risk_level', '') }}',
            reminder_status: '{{ request('reminder_status', '') }}',
        },
        currentPage: 1,
        perPage: 50,
        isModalOpen: false,
        modalData: null,
        modalActiveTab: 'schedule',
        donutChartInstance: null,
        barChartInstance: null,

        init() {
            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        get processedInstallments() {
            const asOfStr = this.filters.as_of_date || '{{ now()->format('Y-m-d') }}';
            const asOf = new Date(asOfStr + 'T00:00:00');

            return this.allInstallments.map(inst => {
                let daysOverdue = 0;
                if (inst.due_date_raw) {
                    const dueDate = new Date(inst.due_date_raw + 'T00:00:00');
                    const diffTime = asOf.getTime() - dueDate.getTime();
                    daysOverdue = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                }

                let bucket = 'Current';
                let risk = 'None';
                let prob = 1.0;

                if (daysOverdue > 0) {
                    if (daysOverdue <= 30) { bucket = '0-30'; risk = 'Low'; prob = 0.90; }
                    else if (daysOverdue <= 60) { bucket = '31-60'; risk = 'Medium'; prob = 0.70; }
                    else if (daysOverdue <= 90) { bucket = '61-90'; risk = 'High'; prob = 0.40; }
                    else if (daysOverdue <= 120) { bucket = '91-120'; risk = 'Critical'; prob = 0.20; }
                    else { bucket = '120+'; risk = 'Severe'; prob = 0.05; }
                } else {
                    daysOverdue = 0;
                }

                let targetLevel = 'Pending';
                if (daysOverdue >= 90) targetLevel = 'Legal';
                else if (daysOverdue >= 60) targetLevel = 'Final';
                else if (daysOverdue >= 30) targetLevel = 'Second';
                else if (daysOverdue >= 7) targetLevel = 'First';
                else if (daysOverdue > 0) targetLevel = 'Soft';

                const outAmt = parseFloat(inst.calculated_outstanding) || 0;
                const fAmt = outAmt * prob;

                return {
                    ...inst,
                    days_overdue: daysOverdue,
                    ageing_bucket: bucket,
                    risk_level: risk,
                    forecast_amount: fAmt,
                    suggested_reminder_level: targetLevel,
                    reminder_level: inst.last_reminder_level || (daysOverdue > 0 ? targetLevel : '-')
                };
            });
        },

        get filteredInstallments() {
            return this.processedInstallments.filter(inst => {
                if (this.filters.project_id && String(inst.project_id) !== String(this.filters.project_id)) return false;
                if (this.filters.customer_id && String(inst.customer_id) !== String(this.filters.customer_id)) return false;
                if (this.filters.ageing_bucket && this.filters.ageing_bucket !== 'All' && inst.ageing_bucket !== this.filters.ageing_bucket) return false;
                if (this.filters.risk_level && this.filters.risk_level !== 'All' && inst.risk_level !== this.filters.risk_level) return false;
                if (this.filters.reminder_status && this.filters.reminder_status !== 'All') {
                    if (this.filters.reminder_status === 'Sent' && inst.reminder_status !== 'Sent') return false;
                    if (this.filters.reminder_status === 'Pending' && inst.reminder_status !== 'Pending') return false;
                    if (this.filters.reminder_status === 'Failed' && inst.reminder_status !== 'Failed') return false;
                }
                return true;
            }).sort((a, b) => b.days_overdue - a.days_overdue);
        },

        get kpis() {
            let totalOutstanding = 0;
            let totalOverdue = 0;
            let currentNotDue = 0;
            let expectedCollection = 0;
            const allCusts = new Set();
            const overdueCusts = new Set();

            this.filteredInstallments.forEach(inst => {
                const out = parseFloat(inst.calculated_outstanding) || 0;
                totalOutstanding += out;
                if (inst.customer_id) allCusts.add(inst.customer_id);

                if (inst.days_overdue > 0) {
                    totalOverdue += out;
                    if (inst.customer_id) overdueCusts.add(inst.customer_id);
                    expectedCollection += (parseFloat(inst.forecast_amount) || 0);
                } else {
                    currentNotDue += out;
                    expectedCollection += out;
                }
            });

            return {
                total_outstanding: totalOutstanding,
                total_customers: allCusts.size,
                total_overdue: totalOverdue,
                overdue_customers: overdueCusts.size,
                current_not_due: currentNotDue,
                expected_collection: expectedCollection
            };
        },

        get isOverdueMode() {
            return this.kpis.total_overdue > 0;
        },

        get upcomingScheduleData() {
            const baseDate = this.filters.as_of_date ? new Date(this.filters.as_of_date + 'T00:00:00') : new Date();
            
            // Generate next 12 monthly slots
            const months = [];
            for (let i = 0; i < 12; i++) {
                const d = new Date(baseDate.getFullYear(), baseDate.getMonth() + i, 1);
                const monthKey = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                const label = d.toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
                months.push({
                    key: monthKey,
                    label: label,
                    amount: 0
                });
            }

            // 5 Timeline Horizon Buckets (Exact same color scheme as shown in the image)
            const horizons = {
                '0-3M': { label: '0 - 3 Months', amount: 0, color: '#4f46e5' },
                '3-6M': { label: '3 - 6 Months', amount: 0, color: '#f59e0b' },
                '6-9M': { label: '6 - 9 Months', amount: 0, color: '#22c55e' },
                '9-12M': { label: '9 - 12 Months', amount: 0, color: '#f97316' },
                '>12M': { label: '> 12 Months', amount: 0, color: '#ec4899' }
            };

            this.filteredInstallments.forEach(inst => {
                const out = parseFloat(inst.calculated_outstanding) || 0;
                if (out <= 0) return;

                if (inst.due_date_raw) {
                    const instDate = new Date(inst.due_date_raw + 'T00:00:00');
                    const diffDays = Math.floor((instDate.getTime() - baseDate.getTime()) / (1000 * 60 * 60 * 24));
                    const instMonthKey = instDate.getFullYear() + '-' + String(instDate.getMonth() + 1).padStart(2, '0');

                    const mObj = months.find(m => m.key === instMonthKey);
                    if (mObj) {
                        mObj.amount += out;
                    }

                    if (diffDays <= 90) {
                        horizons['0-3M'].amount += out;
                    } else if (diffDays <= 180) {
                        horizons['3-6M'].amount += out;
                    } else if (diffDays <= 270) {
                        horizons['6-9M'].amount += out;
                    } else if (diffDays <= 365) {
                        horizons['9-12M'].amount += out;
                    } else {
                        horizons['>12M'].amount += out;
                    }
                } else {
                    horizons['0-3M'].amount += out;
                }
            });

            return {
                months: months,
                horizons: Object.values(horizons)
            };
        },

        get donutChartData() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                return {
                    labels: ['0-30 Days', '31-60 Days', '61-90 Days', '91-120 Days', '> 120 Days'],
                    amounts: [summary['0-30'], summary['31-60'], summary['61-90'], summary['91-120'], summary['120+']],
                    colors: ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899']
                };
            } else {
                const hData = this.upcomingScheduleData.horizons;
                const amounts = hData.map(h => h.amount);
                const hasNonZero = amounts.some(a => a > 0);
                return {
                    labels: hData.map(h => h.label),
                    amounts: hasNonZero ? amounts : [1, 1, 1, 1, 1],
                    colors: ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899']
                };
            }
        },

        get barChartData() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                return {
                    seriesName: 'Overdue Amount',
                    labels: ['0-30 Days', '31-60 Days', '61-90 Days', '91-120 Days', '> 120 Days'],
                    amounts: [summary['0-30'], summary['31-60'], summary['61-90'], summary['91-120'], summary['120+']],
                    colors: ['#ef4444']
                };
            } else {
                const mData = this.upcomingScheduleData.months;
                return {
                    seriesName: 'Scheduled Inflow',
                    labels: mData.map(m => m.label),
                    amounts: mData.map(m => m.amount),
                    colors: ['#ef4444']
                };
            }
        },

        get summaryTableBuckets() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                const total = this.kpis.total_overdue;
                return [
                    { key: '0-30', label: '0-30 Days', amount: summary['0-30'], color: '#4f46e5', pct: total > 0 ? (summary['0-30'] / total * 100).toFixed(1) : '0.0' },
                    { key: '31-60', label: '31-60 Days', amount: summary['31-60'], color: '#f59e0b', pct: total > 0 ? (summary['31-60'] / total * 100).toFixed(1) : '0.0' },
                    { key: '61-90', label: '61-90 Days', amount: summary['61-90'], color: '#22c55e', pct: total > 0 ? (summary['61-90'] / total * 100).toFixed(1) : '0.0' },
                    { key: '91-120', label: '91-120 Days', amount: summary['91-120'], color: '#f97316', pct: total > 0 ? (summary['91-120'] / total * 100).toFixed(1) : '0.0' },
                    { key: '120+', label: '> 120 Days', amount: summary['120+'], color: '#ec4899', pct: total > 0 ? (summary['120+'] / total * 100).toFixed(1) : '0.0' },
                ];
            } else {
                const horizons = this.upcomingScheduleData.horizons;
                const total = this.kpis.total_outstanding;
                return horizons.map(h => ({
                    key: h.label,
                    label: h.label,
                    amount: h.amount,
                    color: h.color,
                    pct: total > 0 ? (h.amount / total * 100).toFixed(1) : '0.0'
                }));
            }
        },

        get totalPages() {
            return Math.ceil(this.filteredInstallments.length / this.perPage) || 1;
        },

        get paginatedInstallments() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredInstallments.slice(start, start + this.perPage);
        },

        resetFilters() {
            this.filters = {
                as_of_date: '',
                project_id: '',
                customer_id: '',
                ageing_bucket: '',
                risk_level: '',
                reminder_status: ''
            };
            this.currentPage = 1;
            this.updateCharts();
        },

        openModal(data) {
            this.modalData = data;
            this.modalActiveTab = 'schedule';
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = '';
            setTimeout(() => { this.modalData = null; }, 300);
        },

        formatNumber(val) {
            if (!val || isNaN(val)) return '0';
            return Number(Math.round(val)).toLocaleString('en-IN');
        },

        getAgeingColor(bucket) {
            switch(bucket) {
                case '31-60': return 'text-amber-500';
                case '61-90': return 'text-orange-500';
                case '91-120': return 'text-rose-500';
                case '120+': return 'text-red-600 font-semibold';
                case 'Current': return 'text-slate-500';
                default: return 'text-emerald-500';
            }
        },

        getRiskColor(risk) {
            const r = (risk || '').toLowerCase().trim();
            switch(r) {
                case 'high':
                case 'critical':
                case 'severe':
                    return 'bg-rose-100 text-rose-800 border border-rose-200 px-2.5 py-0.5 rounded-md font-black';
                case 'medium':
                    return 'bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-md font-black';
                case 'low':
                    return 'bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-md font-black';
                default:
                    return 'bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-0.5 rounded-md font-bold';
            }
        },

        renderCharts() {
            const donutData = this.donutChartData;
            const barData = this.barChartData;

            // Donut Chart
            const donutEl = document.querySelector("#donutChart");
            if (donutEl) {
                donutEl.innerHTML = '';
                const donutOptions = {
                    series: donutData.amounts,
                    labels: donutData.labels,
                    chart: {
                        type: 'donut',
                        height: 200,
                        fontFamily: 'inherit',
                    },
                    colors: donutData.colors,
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '60%',
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return val.toFixed(0) + "%";
                        },
                        dropShadow: {
                            enabled: false
                        }
                    },
                    stroke: {
                        show: true,
                        colors: '#ffffff',
                        width: 2
                    },
                    legend: {
                        show: false
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                            }
                        }
                    }
                };

                this.donutChartInstance = new ApexCharts(donutEl, donutOptions);
                this.donutChartInstance.render();
            }

            // Bar Chart
            const barEl = document.querySelector("#barChart");
            if (barEl) {
                barEl.innerHTML = '';
                const barOptions = {
                    series: [{
                        name: barData.seriesName || 'Amount',
                        data: barData.amounts
                    }],
                    chart: {
                        type: 'bar',
                        height: 250,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit',
                    },
                    colors: barData.colors || ['#3b82f6'],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '45%',
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            if (!val || val === 0) return '';
                            if (val >= 100000) {
                                return '₹' + (val / 100000).toFixed(1) + 'L';
                            }
                            return '₹' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(val);
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            colors: ["#475569"]
                        }
                    },
                    xaxis: {
                        categories: barData.labels,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: '#64748b',
                                fontSize: '11px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                if (val >= 100000) {
                                    return (val / 100000).toFixed(0) + "L";
                                }
                                return val;
                            },
                            style: {
                                colors: '#64748b',
                                fontSize: '11px'
                            }
                        },
                        title: {
                            text: 'Amount (₹)',
                            style: {
                                color: '#64748b',
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                            }
                        }
                    }
                };

                this.barChartInstance = new ApexCharts(barEl, barOptions);
                this.barChartInstance.render();
            }
        },

        updateCharts() {
            this.$nextTick(() => {
                const donutData = this.donutChartData;
                const barData = this.barChartData;

                if (this.donutChartInstance) {
                    this.donutChartInstance.updateOptions({
                        series: donutData.amounts,
                        labels: donutData.labels,
                        colors: donutData.colors
                    });
                }
                if (this.barChartInstance) {
                    this.barChartInstance.updateOptions({
                        xaxis: {
                            categories: barData.labels
                        },
                        colors: barData.colors
                    });
                    this.barChartInstance.updateSeries([{
                        name: barData.seriesName || 'Amount',
                        data: barData.amounts
                    }]);
                }
            });
        },

        async exportExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('Excel generation library is still loading. Please try again in a few moments.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Hindustan ERP';
                workbook.lastModifiedBy = 'Hindustan ERP';
                workbook.created = new Date();
                workbook.modified = new Date();

                const worksheet = workbook.addWorksheet('Report Ledger', {
                    views: [{ showGridLines: true }],
                    pageSetup: {
                        paperSize: 9, // A4
                        orientation: 'landscape',
                        fitToPage: true,
                        fitToWidth: 1,
                        fitToHeight: 0
                    }
                });

                // Calculate dynamic width for Unit No based on data
                let maxUnitLen = 30;
                (this.filteredInstallments || []).forEach(inst => {
                    const len = (inst.unit_name || '').length;
                    if (len > maxUnitLen) maxUnitLen = len;
                });
                const calculatedUnitWidth = Math.min(Math.max(maxUnitLen + 4, 65), 90);

                // Column Setup (11 Clean Spacious Columns with wider Unit No)
                const columnsConfig = [
                    { header: 'SL NO', key: 'sl', width: 8 },
                    { header: 'Customer Name', key: 'customer', width: 26 },
                    { header: 'Phone Number', key: 'phone', width: 18 },
                    { header: 'Project Name', key: 'project', width: 45 },
                    { header: 'Unit No', key: 'unit', width: calculatedUnitWidth },
                    { header: 'Due Date', key: 'due_date', width: 16 },
                    { header: 'Outstanding (₹)', key: 'outstanding', width: 22 },
                    { header: 'Days Overdue', key: 'days_overdue', width: 15 },
                    { header: 'Ageing Bucket', key: 'ageing', width: 18 },
                    { header: 'Risk Level', key: 'risk', width: 15 },
                    { header: 'Last Reminder', key: 'reminder', width: 16 }
                ];

                worksheet.columns = columnsConfig.map(col => ({ width: col.width }));

                const totalCols = 11;

                // ── 1. Empty Spacer Row 1 ──
                const row1 = worksheet.getRow(1);
                row1.height = 20;

                // ── 2. Main Title Banner (Row 2) ──
                let projectTitle = 'ALL PROJECTS';
                if (this.filters.project_id && this.projects) {
                    const selectedProj = (this.projects || []).find(p => String(p.id) === String(this.filters.project_id));
                    if (selectedProj && selectedProj.name) {
                        projectTitle = selectedProj.name.toUpperCase();
                    }
                }

                const row2 = worksheet.getRow(2);
                row2.values = [`${projectTitle} - COLLECTION FORECAST & OVERDUE REPORT`];
                worksheet.mergeCells('A2:K2');
                row2.height = 32;
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row2.getCell(c);
                    cell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } }; // Theme Gold
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF8A7522' } },
                        bottom: { style: 'thin', color: { argb: 'FF8A7522' } },
                        left: { style: 'thin', color: { argb: 'FF8A7522' } },
                        right: { style: 'thin', color: { argb: 'FF8A7522' } }
                    };
                }

                // ── 3. Subheader Banner (Row 3) ──
                const asOfStr = this.filters.as_of_date || 'Current Live Date';
                const totalRecs = this.filteredInstallments.length;
                const currentDateStr = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

                const row3 = worksheet.getRow(3);
                row3.values = [`Generated On: ${currentDateStr} | As On Date: ${asOfStr} | Total Records: ${totalRecs} Installment(s)`];
                worksheet.mergeCells('A3:K3');
                row3.height = 25;
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row3.getCell(c);
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } }; // Dark Theme Green
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF047857' } },
                        bottom: { style: 'thin', color: { argb: 'FF047857' } },
                        left: { style: 'thin', color: { argb: 'FF047857' } },
                        right: { style: 'thin', color: { argb: 'FF047857' } }
                    };
                }

                // ── 4. Section Banner (Row 4) ──
                const row4 = worksheet.getRow(4);
                row4.values = ['TRANSACTION & OVERDUE AGEING DETAILS'];
                worksheet.mergeCells('A4:K4');
                row4.height = 24;
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row4.getCell(c);
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF8A7522' } }; // Gold Dark
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF6B5B1E' } },
                        bottom: { style: 'thin', color: { argb: 'FF6B5B1E' } },
                        left: { style: 'thin', color: { argb: 'FF6B5B1E' } },
                        right: { style: 'thin', color: { argb: 'FF6B5B1E' } }
                    };
                }

                // ── 5. Empty Spacer Row 5 ──
                const row5 = worksheet.getRow(5);
                row5.height = 15;

                // ── 6. Table Column Headers (Row 6) ──
                const headerRow = worksheet.getRow(6);
                headerRow.values = columnsConfig.map(col => col.header);
                headerRow.height = 28;

                for (let c = 1; c <= totalCols; c++) {
                    const cell = headerRow.getCell(c);
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Center align all column headers
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'FF0B3B2E' } // Deep Dark Emerald Green Header
                    };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF047857' } },
                        bottom: { style: 'thin', color: { argb: 'FF047857' } },
                        left: { style: 'thin', color: { argb: 'FF047857' } },
                        right: { style: 'thin', color: { argb: 'FF047857' } }
                    };
                }

                // ── 7. Data Rows (Row 7 onwards) ──
                let totalOutstanding = 0;
                let currentRowIdx = 7;

                this.filteredInstallments.forEach((inst, index) => {
                    const rowNum = index + 1;
                    const phone = (inst.modal_payload && inst.modal_payload.customer && inst.modal_payload.customer.mobile) 
                                  ? inst.modal_payload.customer.mobile 
                                  : (inst.customer_phone || '-');
                    const outstandingVal = parseFloat(inst.calculated_outstanding) || 0;
                    totalOutstanding += outstandingVal;
                    const daysVal = parseInt(inst.days_overdue) || 0;
                    const ageingLabel = inst.ageing_bucket === '120+' ? '> 120 Days' : (inst.ageing_bucket ? inst.ageing_bucket + (inst.ageing_bucket !== 'Current' ? ' Days' : '') : '-');

                    const dataRow = worksheet.getRow(currentRowIdx);
                    dataRow.values = [
                        rowNum,
                        inst.customer_name || '-',
                        phone,
                        inst.project_name || '-',
                        inst.unit_name || '-',
                        inst.due_date_formatted || '-',
                        outstandingVal,
                        daysVal > 0 ? daysVal : 0,
                        ageingLabel,
                        inst.risk_level || 'Normal',
                        inst.last_reminder_date || '-'
                    ];
                    dataRow.height = 24;

                    const isEven = (index + 1) % 2 === 0;
                    const rowBg = isEven ? 'FFFFFFFF' : 'FFF8FAF5';

                    for (let c = 1; c <= totalCols; c++) {
                        const cell = dataRow.getCell(c);
                        cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                        cell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: { argb: rowBg }
                        };
                        cell.border = {
                            top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                        };

                        // Center alignment + wrapText for all data cells
                        cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                        if (c === 2) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        } else if (c === 3) {
                            cell.numFormat = '@';
                        } else if (c === 7) {
                            cell.numFormat = '#,##0.00';
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF047857' } };
                        }

                        // Risk Level Background Colored Cell
                        if (c === 10) {
                            const risk = (inst.risk_level || '').toLowerCase().trim();
                            if (risk === 'high' || risk === 'critical' || risk === 'severe') {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF991B1B' } };
                                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFCDD2' } };
                            } else if (risk === 'medium') {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF92400E' } };
                                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFE082' } };
                            } else if (risk === 'low') {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF065F46' } };
                                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC8E6C9' } };
                            } else {
                                cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF64748B' } };
                            }
                        }
                    }

                    currentRowIdx++;
                });

                // ── 8. Bottom Summary / Total Row (Green Background Footer) ──
                const totalRow = worksheet.getRow(currentRowIdx);
                totalRow.height = 32;
                worksheet.mergeCells(`A${currentRowIdx}:F${currentRowIdx}`);

                const totalLabelCell = worksheet.getCell(`A${currentRowIdx}`);
                totalLabelCell.value = 'TOTAL OUTSTANDING AMOUNT';
                totalLabelCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                totalLabelCell.alignment = { horizontal: 'center', vertical: 'middle' };

                const totalValCell = worksheet.getCell(`G${currentRowIdx}`);
                totalValCell.value = totalOutstanding;
                totalValCell.numFormat = '#,##0.00';
                totalValCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                totalValCell.alignment = { horizontal: 'center', vertical: 'middle' };

                for (let c = 1; c <= totalCols; c++) {
                    const cell = totalRow.getCell(c);
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'FF0B3B2E' } // Green Background Footer
                    };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF047857' } },
                        bottom: { style: 'thin', color: { argb: 'FF047857' } },
                        left: { style: 'thin', color: { argb: 'FF047857' } },
                        right: { style: 'thin', color: { argb: 'FF047857' } }
                    };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                }

                // ── 9. Generate & Trigger Download ──
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;

                anchor.download = 'collection-forecast-report.xlsx';
                document.body.appendChild(anchor);
                anchor.click();
                document.body.removeChild(anchor);
                window.URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Excel Export Error:', err);
                alert('Failed to generate Excel report: ' + err.message);
            }
        }
    };
}
</script>

</x-erp-layout>
