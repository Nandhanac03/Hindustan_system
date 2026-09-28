<x-erp-layout title="Executive Dashboard Analytics & Profitability" headerTitle="Business Reports Center">

<style>
@media print {
    @page {
        size: landscape !important;
        margin: 6mm 8mm !important;
    }
    *, *::before, *::after {
        box-sizing: border-box !important;
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
        break-inside: avoid !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }

    /* Outer Container Wrapper (Remove Outer Border in PDF) */
    .print-card-wrapper {
        border: none !important;
        border-style: none !important;
        border-width: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 !important;
    }

    /* Print 5 KPI Cards Flex Row (Exact Match with Image 1) */
    .print-kpi-row {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        gap: 8px !important;
        width: 100% !important;
        margin-bottom: 8px !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    .print-kpi-row > div {
        flex: 1 1 0% !important;
        box-sizing: border-box !important;
        padding: 6px 8px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background: #ffffff !important;
    }

    /* Print Charts Side-by-Side Row (Exact Match with Image 1) */
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
    .print-chart-box {
        width: 49% !important;
        flex: 0 0 49% !important;
        box-sizing: border-box !important;
        padding: 8px 10px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        overflow: hidden !important;
    }

    .print-chart-size {
        width: 100% !important;
        height: 150px !important;
    }

    /* Print Section Containers Margin & Page Breaks */
    .print-section {
        margin-bottom: 10px !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-4 border-b-2 border-[#a38c29] pb-3">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Business Reports &amp; Executive Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">EXECUTIVE DASHBOARD ANALYTICS &amp; PROFITABILITY REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ date('d-M-Y') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Scope:</span> <span class="font-bold text-[#a38c29]">All Active Projects</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Projects:</span> <span class="font-mono font-bold text-slate-800">{{ $dashboardData['total_projects'] }} Active</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Net Profit:</span> <span class="font-mono font-bold text-emerald-700">₹{{ number_format($dashboardData['profit'], 0) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Dashboard Card Container --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6 print:p-0 print:border-none print:shadow-none print:space-y-4 print-card-wrapper">
        
        {{-- Dashboard Web Header (Hidden on Print) --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-150 pb-4 print:hidden">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-slate-900 text-white rounded-xl shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 002-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight uppercase">Dashboard Analytics & Profitability</h3>
                    <p class="text-xs text-slate-400">High-level financial KPIs, property metrics, and profitability breakdown</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2.5 items-center">
                <button type="button" onclick="printCleanPDF()" 
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
                <button @click="exportCurrentTable()" 
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </button>
            </div>
        </div>

        {{-- Executive KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 print-kpi-row">
            <div class="bg-white border border-slate-200/80 border-l-4 border-l-slate-800 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest print:text-[8px]">Total Projects</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"/></svg>
                    </div>
                </div>
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block print:text-base">{{ $dashboardData['total_projects'] }}</span>
            </div>

            <div class="bg-white border border-slate-200/80 border-l-4 border-l-blue-600 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest print:text-[8px]">Units (Sold / Total)</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </div>
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block print:text-base">{{ $dashboardData['sold_units'] }} <span class="text-slate-400 text-lg print:text-xs">/ {{ $dashboardData['total_units'] }}</span></span>
            </div>

            <div class="bg-white border border-slate-200/80 border-l-4 border-l-emerald-500 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest print:text-[8px]">Total Collections</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block print:text-base">₹{{ number_format($dashboardData['collections'], 0) }}</span>
            </div>

            <div class="bg-white border border-slate-200/80 border-l-4 border-l-amber-500 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest print:text-[8px]">Outstanding Receivable</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block print:text-base">₹{{ number_format($dashboardData['outstanding'], 0) }}</span>
            </div>

            <div class="bg-white border border-slate-200/80 border-l-4 border-l-[#a38c29] rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest print:text-[8px]">Net Calculated Profit</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block print:text-base">₹{{ number_format($dashboardData['profit'], 0) }}</span>
            </div>
        </div>

        {{-- Dashboard Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 print-charts-container">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm print-chart-box">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider print:text-[10px]">Sold vs Unsold Units</h4>
                        <p class="text-[11px] text-slate-400 print:text-[8.5px]">Inventory allocation ratio</p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase print:hidden">Property Units</span>
                </div>
                <div id="soldUnsoldChart" class="w-full h-60 print-chart-size"></div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm print-chart-box">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider print:text-[10px]">Collections vs Expected</h4>
                        <p class="text-[11px] text-slate-400 print:text-[8.5px]">Received vs pending receivables</p>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg uppercase print:hidden">Financials</span>
                </div>
                <div id="collectionsExpectedChart" class="w-full h-60 print-chart-size"></div>
            </div>
        </div>

        {{-- Bank Loan EMI alerts --}}
        @if($dashboardData['loan_emi_alerts']->isNotEmpty())
        <div class="bg-gradient-to-r from-rose-50 to-rose-100/50 border border-rose-200 rounded-2xl p-5 shadow-sm space-y-3 print-section print:p-3 print:bg-rose-50/30">
            <div class="flex items-center justify-between">
                <h4 class="text-xs font-extrabold text-rose-900 uppercase tracking-wider flex items-center gap-2 print:text-[10px]">
                    <div class="p-1 bg-rose-200 rounded-lg text-rose-700 print:hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    Bank Loan EMI Alerts (Upcoming 30 Days)
                </h4>
                <span class="px-2.5 py-0.5 bg-rose-200 text-rose-900 text-[10px] font-extrabold rounded-full uppercase print:hidden">Action Required</span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-rose-200/80 bg-white">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-rose-800 text-white text-[10px] uppercase tracking-wider border-b border-rose-900 font-bold print:bg-rose-800 print:text-white">
                            <th class="px-4 py-2.5 print:px-2.5 print:py-1.5">Project</th>
                            <th class="px-4 py-2.5 print:px-2.5 print:py-1.5">Lender</th>
                            <th class="px-4 py-2.5 print:px-2.5 print:py-1.5">Due Date</th>
                            <th class="px-4 py-2.5 text-right print:px-2.5 print:py-1.5">EMI Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rose-100 font-semibold font-mono text-slate-800">
                        @foreach($dashboardData['loan_emi_alerts'] as $alert)
                        <tr class="hover:bg-rose-50/50 transition-colors {{ $loop->even ? 'print:bg-rose-50/40' : '' }}">
                            <td class="px-4 py-2.5 font-sans font-bold text-slate-900 print:px-2.5 print:py-1.5">{{ $alert->loan?->project?->name }}</td>
                            <td class="px-4 py-2.5 font-sans text-slate-700 print:px-2.5 print:py-1.5">{{ $alert->loan?->lender_name }}</td>
                            <td class="px-4 py-2.5 text-rose-700 print:px-2.5 print:py-1.5">{{ $alert->due_date?->format('d M Y') }}</td>
                            <td class="px-4 py-2.5 text-right text-rose-700 font-bold print:px-2.5 print:py-1.5">₹{{ number_format($alert->emi_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Project Profitability Grid --}}
        <div class="space-y-4 print-section">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider print:text-[11px]">Project Profitability Analysis</h4>
                    <p class="text-[11px] text-slate-400 print:text-[9px]">Detailed breakdown of expected vs actual revenue, costs, and profit margin per project</p>
                </div>
            </div>
            <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm print:rounded-none print:border-slate-300">
                <table id="reportsTable" class="w-full text-xs text-left border-collapse border border-slate-300">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest print:bg-[#a38c29] print:text-white">
                            <th class="px-4 py-3.5 text-white font-extrabold rounded-tl-2xl print:px-2.5 print:py-2 print:rounded-none border border-slate-300">Project</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Expected Revenue</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Actual Revenue</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Partner Payouts</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Brokerage</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Material Costs</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Contractor Payments</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Total Cost</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold print:px-2.5 print:py-2 border border-slate-300">Net Profit</th>
                            <th class="px-4 py-3.5 text-right text-white font-extrabold rounded-tr-2xl print:px-2.5 print:py-2 print:rounded-none border border-slate-300">Margin %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                        @php
                            $totExpected = 0;
                            $totActual = 0;
                            $totPartner = 0;
                            $totBrokerage = 0;
                            $totMaterial = 0;
                            $totContractor = 0;
                            $totCost = 0;
                            $totProfit = 0;
                        @endphp
                        @foreach($dashboardData['project_profitability'] as $row)
                        @php
                            $totExpected += $row['expected_revenue'];
                            $totActual += $row['actual_revenue'];
                            $totPartner += $row['partner_payouts'];
                            $totBrokerage += $row['brokerage_costs'];
                            $totMaterial += $row['material_costs'];
                            $totContractor += $row['contractor_payments'];
                            $totCost += $row['total_cost'];
                            $totProfit += $row['profit'];
                            $rowBg = $loop->even ? 'print:bg-[#F6F3E9]/40' : 'bg-white';
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $rowBg }}">
                            <td class="px-4 py-3 font-sans font-extrabold text-slate-900 print:px-2.5 print:py-1.5 border border-slate-300">{{ $row['project']->name }}</td>
                            <td class="px-4 py-3 text-right print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['expected_revenue'], 0) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['actual_revenue'], 0) }}</td>
                            <td class="px-4 py-3 text-right text-rose-600 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['partner_payouts'], 0) }}</td>
                            <td class="px-4 py-3 text-right text-rose-600 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['brokerage_costs'], 0) }}</td>
                            <td class="px-4 py-3 text-right text-rose-600 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['material_costs'], 0) }}</td>
                            <td class="px-4 py-3 text-right text-rose-600 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['contractor_payments'], 0) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-rose-700 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['total_cost'], 0) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900 print:px-2.5 print:py-1.5 border border-slate-300">₹{{ number_format($row['profit'], 0) }}</td>
                            <td class="px-4 py-3 text-right print:px-2.5 print:py-1.5 border border-slate-300">
                                <span class="inline-block px-2 py-0.5 rounded font-sans font-extrabold text-[10px] uppercase {{ $row['margin'] > 15 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ number_format($row['margin'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @php
                            $overallMargin = $totActual > 0 ? ($totProfit / $totActual) * 100 : 0;
                        @endphp
                        <tr class="bg-slate-100 font-bold text-slate-900 text-[10px] border-t-2 border-slate-400">
                            <td class="px-4 py-3 text-right uppercase tracking-wider print:px-2.5 print:py-2 font-black border border-slate-300">Grand Total Summary:</td>
                            <td class="px-4 py-3 text-right font-mono print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totExpected, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-emerald-900 font-black print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totActual, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-rose-800 print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totPartner, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-rose-800 print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totBrokerage, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-rose-800 print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totMaterial, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-rose-800 print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totContractor, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-rose-900 font-black print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totCost, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-slate-900 font-black print:px-2.5 print:py-2 border border-slate-300">₹{{ number_format($totProfit, 0) }}</td>
                            <td class="px-4 py-3 text-right font-mono text-slate-900 font-black print:px-2.5 print:py-2 border border-slate-300">{{ number_format($overallMargin, 1) }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Hidden Formatted Excel Export Table for Dashboard --}}
        <div class="hidden">
            <table id="dashboardExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
                <colgroup>
                    <col width="70" style="width: 55pt;" />   {{-- SL NO --}}
                    <col width="260" style="width: 195pt;" /> {{-- PROJECT NAME --}}
                    <col width="180" style="width: 135pt;" /> {{-- EXPECTED REVENUE --}}
                    <col width="180" style="width: 135pt;" /> {{-- ACTUAL REVENUE --}}
                    <col width="180" style="width: 135pt;" /> {{-- PARTNER PAYOUTS --}}
                    <col width="180" style="width: 135pt;" /> {{-- BROKERAGE --}}
                    <col width="180" style="width: 135pt;" /> {{-- MATERIAL COSTS --}}
                    <col width="180" style="width: 135pt;" /> {{-- CONTRACTOR PAYMENTS --}}
                    <col width="180" style="width: 135pt;" /> {{-- TOTAL COST --}}
                    <col width="180" style="width: 135pt;" /> {{-- NET PROFIT --}}
                    <col width="140" style="width: 105pt;" /> {{-- MARGIN % --}}
                </colgroup>
                <thead>
                    {{-- Row 1: Spacer --}}
                    <tr height="20" style="height: 20pt;" data-no-border="true">
                        <th colspan="11" style="background-color: #ffffff; border: none;"></th>
                    </tr>
                    {{-- Row 2: Main Header --}}
                    <tr height="38" style="height: 38pt;">
                        <th colspan="11" bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #8A7522; font-family: 'Calibri', 'Aptos', sans-serif;">
                            @if(request('project_id') && ($activeProj = \App\Models\Project::find(request('project_id')))) {{ strtoupper($activeProj->name) }} - @endif EXECUTIVE FINANCIAL SUMMARY & PROJECT PROFITABILITY OVERVIEW
                        </th>
                    </tr>
                    {{-- Row 3: Spacer --}}
                    <tr height="15" style="height: 15pt;" data-no-border="true">
                        <th colspan="11" style="background-color: #ffffff; border: none;"></th>
                    </tr>
                    {{-- Summary KPI Rows --}}
                    <tr height="30" style="height: 30pt;">
                        <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #8A7522;">TOTAL PROJECTS:</td>
                        <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #0F172A;">{{ $dashboardData['total_projects'] }} Active</td>
                        <td colspan="2" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #047857;">TOTAL COLLECTIONS:</td>
                        <td colspan="5" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #047857;" data-format="₹#,##0.00">₹{{ number_format($dashboardData['collections'], 2) }}</td>
                    </tr>
                    <tr height="30" style="height: 30pt;">
                        <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #BE123C;">UNITS (SOLD / TOTAL):</td>
                        <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #BE123C;">{{ $dashboardData['sold_units'] }} / {{ $dashboardData['total_units'] }}</td>
                        <td colspan="2" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #B45309;">OUTSTANDING RECEIVABLE:</td>
                        <td colspan="5" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #B45309;" data-format="₹#,##0.00">₹{{ number_format($dashboardData['outstanding'], 2) }}</td>
                    </tr>
                    {{-- Row 8: Spacer --}}
                    <tr height="15" style="height: 15pt;" data-no-border="true">
                        <th colspan="11" style="background-color: #ffffff; border: none;"></th>
                    </tr>
                    {{-- Row 9: Table Headers --}}
                    <tr height="32" style="height: 32pt;">
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">SL NO</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PROJECT NAME</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">EXPECTED REVENUE (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">ACTUAL REVENUE (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PARTNER PAYOUTS (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">BROKERAGE (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">MATERIAL COSTS (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">CONTRACTOR PAYMENTS (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">TOTAL COST (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">NET PROFIT (₹)</th>
                        <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">MARGIN %</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totExpected = 0;
                        $totActual = 0;
                        $totPartner = 0;
                        $totBrokerage = 0;
                        $totMaterial = 0;
                        $totContractor = 0;
                        $totCost = 0;
                        $totProfit = 0;
                    @endphp
                    @foreach($dashboardData['project_profitability'] as $idx => $row)
                    @php
                        $totExpected += $row['expected_revenue'];
                        $totActual += $row['actual_revenue'];
                        $totPartner += $row['partner_payouts'];
                        $totBrokerage += $row['brokerage_costs'];
                        $totMaterial += $row['material_costs'];
                        $totContractor += $row['contractor_payments'];
                        $totCost += $row['total_cost'];
                        $totProfit += $row['profit'];
                        $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F8FAF5';
                    @endphp
                    <tr height="25" style="height: 25pt;">
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $idx + 1 }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $row['project']->name }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $row['expected_revenue'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #047857;">{{ $row['actual_revenue'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $row['partner_payouts'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $row['brokerage_costs'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $row['material_costs'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $row['contractor_payments'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $row['total_cost'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #0f172a;">{{ $row['profit'] }}</td>
                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'0.0%'; color: {{ $row['margin'] > 15 ? '#047857' : '#b45309' }};">{{ number_format($row['margin'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $overallMargin = $totActual > 0 ? ($totProfit / $totActual) * 100 : 0;
                    @endphp
                    <tr height="40" style="height: 40pt; font-weight: bold; color: #ffffff;">
                        <td colspan="2" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif;">GRAND TOTAL SUMMARY</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totExpected }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totActual }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totPartner }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totBrokerage }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totMaterial }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totContractor }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totCost }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totProfit }}</td>
                        <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif;">{{ number_format($overallMargin, 1) }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@include('reports.partials.script')

<script>
function printCleanPDF() {
    const origTitle = document.title;
    document.title = '';
    window.print();
    setTimeout(() => {
        document.title = origTitle;
    }, 1000);
}
</script>

</x-erp-layout>
