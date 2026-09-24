<x-erp-layout title="Unit Exchange Report" headerTitle="Business Reports Center">

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
    
    /* Container Reset for Print */
    .max-w-\[1800px\], .max-w-3xl, .reports-main-card, .reports-table-card, .overflow-x-auto {
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
        margin-bottom: 12px !important;
        padding-bottom: 8px !important;
    }

    /* Top Banner Print Styling (Box above Graph) */
    .reports-banner-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-top: 4px !important;
        margin-bottom: 14px !important;
        padding: 12px 16px !important;
        border: 1px solid #e6d594 !important;
        border-radius: 10px !important;
        background: #fffdf5 !important;
        page-break-inside: avoid !important;
        box-sizing: border-box !important;
    }

    /* Horizontal Chart Card in PDF/Print */
    .reports-charts-container {
        display: block !important;
        width: 100% !important;
        margin-top: 6px !important;
        margin-bottom: 16px !important;
        page-break-inside: avoid !important;
        box-sizing: border-box !important;
    }
    .reports-chart-timeline-card {
        width: 100% !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 12px 16px 14px 16px !important;
        background: #fafbfc !important;
        box-sizing: border-box !important;
    }
    #unitExchangesChart {
        width: 100% !important;
        height: 145px !important;
        min-height: 145px !important;
    }
    .apexcharts-canvas {
        width: 100% !important;
        margin: 0 auto !important;
    }

    /* Table Formatting for Print */
    .reports-table-card {
        margin-top: 6px !important;
        border: none !important;
    }
    .reports-table-header-box {
        padding: 6px 4px 10px 4px !important;
    }
    table, table#reportsTable {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7.2pt !important;
    }
    table#reportsTable colgroup {
        display: table-column-group !important;
    }
    thead {
        display: table-header-group !important;
    }
    tr {
        page-break-inside: avoid !important;
    }
    th, td, table#reportsTable th, table#reportsTable td {
        padding: 6.5px 3.5px !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        white-space: normal !important;
        box-sizing: border-box !important;
        line-height: 1.3 !important;
    }
    th, table#reportsTable th {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-size: 6.8pt !important;
        padding: 7px 3.5px !important;
    }
    td, table#reportsTable td {
        border-bottom: 0.5pt solid #e2e8f0 !important;
        font-size: 7pt !important;
    }
    
    /* Allow cell text to break and wrap nicely */
    .whitespace-nowrap {
        white-space: normal !important;
    }
    .max-w-\[170px\] {
        max-width: 100% !important;
    }
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }

    /* Date and Status Font Size Reduction in Print/PDF */
    .print-date-col, table#reportsTable td.print-date-col {
        font-size: 5.5pt !important;
        white-space: nowrap !important;
        letter-spacing: -0.15px !important;
        padding: 6.5px 2px !important;
    }
    .print-status-col, table#reportsTable td.print-status-col {
        font-size: 5.2pt !important;
        white-space: nowrap !important;
        padding: 6.5px 2px !important;
    }
    .print-status-col span, table#reportsTable td.print-status-col span {
        font-size: 5pt !important;
        padding: 2px 4px !important;
        line-height: 1 !important;
        display: inline-block !important;
        white-space: nowrap !important;
        letter-spacing: 0.2px !important;
        border-radius: 3px !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Asset Transfer & Portfolio Audit</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">UNIT EXCHANGE & TRANSFER AUDIT REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800">Current Live Date</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]">{{ $exchangeEntries->total() ?? $exchangeEntries->count() }} Records</span></div>
            </div>
        </div>
    </div>

    <div class="print:hidden">
        @include('reports.partials.nav')
    </div>

    <div class="reports-main-card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6 print:p-0 print:border-none print:shadow-none">
        <div class="space-y-6">
            {{-- Top Header & Action Banner (Light Golden Theme) --}}
            <div class="reports-banner-container flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-[#a38c29]/10 via-[#b89635]/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/25 shadow-sm text-slate-900 relative overflow-hidden print:p-4 print:mb-4">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#8a7522] shadow-2xs">
                            <svg class="w-5 h-5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Unit Exchange Report</h3>
                            <span class="text-[10px] font-bold text-[#8a7522] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Unit Transfer & Exchange Audit</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Comprehensive audit trail of unit transfers, exchanges, applied customer equities, and updated contracts.</p>
                </div>
                
                {{-- Action Buttons & Total Stats --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0 relative z-10">
                    <div class="px-3.5 py-2 bg-[#a38c29]/10 border border-[#a38c29]/25 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-[#8a7522]">Total Exchanged Units</span>
                        <span class="text-xs font-black text-[#5c4a10] font-mono">{{ $exchangeChartData['total_count'] ?? $exchangeEntries->total() }} Units</span>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-250/20 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Transferred Equity</span>
                        <span class="text-xs font-black text-emerald-900 font-mono">₹{{ number_format($exchangeChartData['total_equity'] ?? 0, 2) }}</span>
                    </div>
                    <div class="px-3.5 py-2 bg-slate-100 border border-slate-200 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-slate-600">Total Contract Value</span>
                        <span class="text-xs font-black text-slate-800 font-mono">₹{{ number_format($exchangeChartData['total_contract'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Interactive Trend Chart --}}
            <div class="reports-charts-container print:mb-5 print:mt-3">
                <div class="reports-chart-timeline-card bg-[#fafbfc] border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-2 print:p-3.5">
                    <div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2.5 print:pb-1.5 mb-1.5">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs print:text-[10px] font-black text-slate-800 uppercase tracking-wider truncate">
                                    UNIT EXCHANGES & APPLIED EQUITY TREND
                                </span>
                            </div>
                            <span class="text-[10px] print:text-[9px] text-slate-400 font-mono shrink-0">Monthly Timeline (₹)</span>
                        </div>
                        <div id="unitExchangesChart" class="w-full h-48 print:h-38"></div>
                    </div>

                    {{-- Dedicated Clean Legend Below Chart --}}
                    <div class="flex items-center justify-center gap-6 pt-2.5 border-t border-slate-100/80 text-xs print:text-[9.5px] font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-1.5 rounded-xs bg-[#2563eb] inline-block shrink-0"></span>
                            <span>Transferred Equity Applied (₹)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter, Print & Export Bar directly above Table (Web Only) --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50 print:hidden">
                @include('reports.partials.filter-bar', ['formId' => 'exchangeFilterForm', 'actionRoute' => route('reports.exchange_report'), 'exportLabel' => 'Export Exchanges'])
            </div>

            {{-- Exchange Ledger Table Card --}}
            <div class="reports-table-card bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:border-none print:shadow-none print:mt-4">
                <div class="reports-table-header-box px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex items-center justify-between print:px-4 print:py-2.5">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">
                            Unit Exchange & Transfer Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Verified audit trail of exchanged old units, newly booked replacement units, transferred paid equities, and updated booking statuses.</p>
                    </div>
                </div>

                <div class="w-full overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                        <colgroup class="hidden print:table-column-group">
                            <col style="width: 7.5%;" />  {{-- Exchange No. --}}
                            <col style="width: 6.5%;" />  {{-- Date --}}
                            <col style="width: 14%;" />   {{-- Project --}}
                            <col style="width: 14.5%;" /> {{-- Old Unit Details --}}
                            <col style="width: 11.5%;" /> {{-- Old Customer --}}
                            <col style="width: 14.5%;" /> {{-- New Unit Details --}}
                            <col style="width: 11.5%;" /> {{-- New Customer --}}
                            <col style="width: 8%;" />    {{-- Equity Applied (Paid) --}}
                            <col style="width: 8%;" />    {{-- Contract Value --}}
                            <col style="width: 4%;" />    {{-- Status --}}
                        </colgroup>
                        <thead class="bg-[#a38c29] text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <tr class="border-b border-white/30">
                                <th rowspan="2" class="px-4 py-3 text-left align-middle border-r border-white/30 whitespace-nowrap">Exchange No.</th>
                                <th rowspan="2" class="px-3 py-3 text-left align-middle border-r border-white/30 whitespace-nowrap">Date</th>
                                <th rowspan="2" class="px-3 py-3 text-left align-middle border-r border-white/40">Project</th>
                                
                                {{-- Old Unit Main Header (Golden Theme with White Border) --}}
                                <th colspan="2" class="px-3 py-2 text-center bg-[#8f7a23] text-white border-r border-l border-white font-black tracking-wider">
                                    Old Unit (Cancelled)
                                </th>
                                
                                {{-- New Unit Main Header (Golden Theme with White Border) --}}
                                <th colspan="2" class="px-3 py-2 text-center bg-[#84711f] text-white border-r border-white font-black tracking-wider">
                                    New Unit (Booked)
                                </th>
                                
                                <th rowspan="2" class="px-3 py-3 text-right align-middle border-r border-white/30 whitespace-nowrap">Equity Applied (Paid)</th>
                                <th rowspan="2" class="px-3 py-3 text-right align-middle border-r border-white/30 whitespace-nowrap">Contract Value</th>
                                <th rowspan="2" class="px-3 py-3 text-center align-middle whitespace-nowrap">Status</th>
                            </tr>
                            <tr class="border-b border-white/40 text-[9.5px]">
                                {{-- Old Unit Subheaders (Golden Theme with White Border) --}}
                                <th class="px-3 py-1.5 text-left bg-[#8f7a23] text-amber-100 border-l border-r border-white/40 font-extrabold uppercase tracking-wider whitespace-nowrap">Unit Details</th>
                                <th class="px-3 py-1.5 text-left bg-[#8f7a23] text-amber-100 border-r border-white font-extrabold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                
                                {{-- New Unit Subheaders (Golden Theme with White Border) --}}
                                <th class="px-3 py-1.5 text-left bg-[#84711f] text-amber-100 border-r border-white/40 font-extrabold uppercase tracking-wider whitespace-nowrap">Unit Details</th>
                                <th class="px-3 py-1.5 text-left bg-[#84711f] text-amber-100 border-r border-white font-extrabold uppercase tracking-wider whitespace-nowrap">Customer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                            @forelse($exchangeEntries as $index => $row)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                {{-- Exchange No. --}}
                                <td class="px-4 py-3 font-bold text-primary font-mono text-left whitespace-nowrap">
                                    {{ $row->exchange_no }}
                                </td>

                                {{-- Date --}}
                                <td class="px-3 py-3 text-slate-500 text-left whitespace-nowrap text-[11px] font-sans print-date-col">
                                    {{ $row->exchange_date ? \Carbon\Carbon::parse($row->exchange_date)->format('d M Y') : '—' }}
                                </td>

                                {{-- Project --}}
                                <td class="px-3 py-3 text-left text-slate-800 font-bold max-w-[170px] truncate" title="{{ $row->project?->name ?? 'N/A' }}">
                                    {{ $row->project?->name ?? '—' }}
                                </td>

                                {{-- Old Unit Details --}}
                                <td class="px-3 py-3 text-left text-slate-700 bg-amber-50/20 border-l border-slate-100 whitespace-nowrap">
                                    {{ $row->old_unit_details ?? '—' }}
                                </td>

                                {{-- Old Customer --}}
                                <td class="px-3 py-3 text-left font-bold text-slate-900 bg-amber-50/20 border-r border-slate-100 whitespace-nowrap">
                                    {{ $row->old_customer_name ?? '—' }}
                                </td>

                                {{-- New Unit Details --}}
                                <td class="px-3 py-3 text-left text-slate-800 font-semibold bg-emerald-50/15 whitespace-nowrap">
                                    {{ $row->new_unit_details ?? '—' }}
                                </td>

                                {{-- New Customer --}}
                                <td class="px-3 py-3 text-left font-bold text-slate-900 bg-emerald-50/15 border-r border-slate-100 whitespace-nowrap">
                                    {{ $row->new_customer_name ?? '—' }}
                                </td>

                                {{-- Equity Applied (Paid) --}}
                                <td class="px-3 py-3 text-right font-mono font-bold text-emerald-700 whitespace-nowrap">
                                    ₹{{ number_format($row->equity_paid ?? $row->transferred_equity ?? 0, 2) }}
                                </td>

                                {{-- Contract Value --}}
                                <td class="px-3 py-3 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                    ₹{{ number_format($row->new_unit_value ?? $row->total_amount ?? 0, 2) }}
                                </td>

                                {{-- Status --}}
                                <td class="px-3 py-3 text-center whitespace-nowrap print-status-col">
                                    <span class="px-2.5 py-1 rounded-md text-[9.5px] font-black uppercase bg-blue-50 text-blue-700 border border-blue-200">
                                        Exchanged
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">No unit exchanges registered.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="print:hidden">{{ $exchangeEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="exchangeExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />   {{-- SL No --}}
            <col width="140" style="width: 105pt;" />  {{-- Exchange No --}}
            <col width="120" style="width: 90pt;" />   {{-- Date --}}
            <col width="220" style="width: 165pt;" />  {{-- Project --}}
            <col width="220" style="width: 165pt;" />  {{-- Old Unit Details --}}
            <col width="180" style="width: 135pt;" />  {{-- Old Customer --}}
            <col width="220" style="width: 165pt;" />  {{-- New Unit Details --}}
            <col width="180" style="width: 135pt;" />  {{-- New Customer --}}
            <col width="180" style="width: 135pt;" />  {{-- Equity Applied (Paid) --}}
            <col width="180" style="width: 135pt;" />  {{-- Contract Value --}}
            <col width="120" style="width: 90pt;" />   {{-- Status --}}
        </colgroup>
        <thead>
            {{-- Empty Spacer Row Top --}}
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="11" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="11" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    {{ strtoupper($projects->firstWhere('id', request('project_id'))?->name ?? ($projects->first()?->name ?? 'PROJECT')) }} - UNIT EXCHANGE REPORT
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="11" bgcolor="#007398" style="background-color: #007398; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    Unit Transfer & Exchange Audit
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="11" bgcolor="#006039" style="background-color: #006039; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    TRANSACTION DETAILS
                </th>
            </tr>
            {{-- Empty Spacer Row Middle --}}
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="11" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL No</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Exchange No.</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Date</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Project</th>
                <th bgcolor="#8f7a23" style="background-color: #8f7a23; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Old Unit Details</th>
                <th bgcolor="#8f7a23" style="background-color: #8f7a23; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Old Customer</th>
                <th bgcolor="#84711f" style="background-color: #84711f; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">New Unit Details</th>
                <th bgcolor="#84711f" style="background-color: #84711f; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">New Customer</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Equity Applied (Paid) (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Contract Value (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalEquity = 0;
                $totalContract = 0;
            @endphp
            @foreach($exchangeEntries as $index => $row)
                @php 
                    $totalEquity += (float)($row->equity_paid ?? $row->transferred_equity ?? 0);
                    $totalContract += (float)($row->new_unit_value ?? $row->total_amount ?? 0);
                    $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F0F8FF';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $loop->iteration }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $row->exchange_no }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'yyyy-mm-dd'; color: #000000;">{{ $row->exchange_date ? \Carbon\Carbon::parse($row->exchange_date)->format('Y-m-d') : '' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $row->project?->name ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $row->old_unit_details ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $row->old_customer_name ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $row->new_unit_details ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $row->new_customer_name ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ $row->equity_paid ?? $row->transferred_equity ?? 0 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $row->new_unit_value ?? $row->total_amount ?? 0 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #1d4ed8;">Exchanged</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($exchangeEntries) > 0)
        <tfoot>
            <tr height="36" style="height: 36pt; font-weight: bold; color: #ffffff;">
                <td colspan="8" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL EXCHANGE EQUITY</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalEquity }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalContract }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
