<x-erp-layout title="Sales Cancellation Report" headerTitle="Business Reports Center">

<style>
@media print {
    @page {
        size: landscape;
        margin: 8mm 8mm 10mm 8mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 9pt !important;
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
    
    /* Top Banner Print Styling */
    .reports-banner-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-bottom: 14px !important;
        padding: 12px 16px !important;
        border: 1px solid #fecdd3 !important;
        border-radius: 12px !important;
        background: #fff1f2 !important;
        page-break-inside: avoid !important;
        box-sizing: border-box !important;
    }

    /* Horizontal Side-by-Side Charts in PDF/Print */
    .reports-charts-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: stretch !important;
        justify-content: space-between !important;
        gap: 16px !important;
        width: 100% !important;
        margin-top: 14px !important;
        margin-bottom: 22px !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        page-break-inside: avoid !important;
        box-sizing: border-box !important;
    }
    .reports-chart-timeline-card {
        flex: 0 0 calc(62% - 8px) !important;
        width: calc(62% - 8px) !important;
        max-width: calc(62% - 8px) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 16px 18px 16px 18px !important;
        background: #fafbfc !important;
        box-sizing: border-box !important;
    }
    .reports-chart-donut-card {
        flex: 0 0 calc(38% - 8px) !important;
        width: calc(38% - 8px) !important;
        max-width: calc(38% - 8px) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 16px 18px 16px 18px !important;
        background: #fafbfc !important;
        box-sizing: border-box !important;
    }
    #salesReturnChart, #salesReturnDonutChart {
        width: 100% !important;
    }
    #salesReturnChart svg, #salesReturnDonutChart svg {
        width: 100% !important;
        max-width: 100% !important;
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
    th {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .reports-main-card, .reports-table-card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Receivable & Risk Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">SALES CANCELLATION & REFUND AUDIT REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800">Current Live Date</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]">{{ $salesReturns->total() ?? $salesReturns->count() }} Records</span></div>
            </div>
        </div>
    </div>

    <div class="print:hidden">
        @include('reports.partials.nav')
    </div>

    <div class="reports-main-card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6 print:p-0 print:border-none print:shadow-none">
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
            <div class="reports-banner-container flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-rose-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-rose-200/30 shadow-sm text-slate-900 relative overflow-hidden print:p-4 print:mb-4">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-rose-500/15 rounded-xl border border-rose-200 text-rose-700 shadow-2xs">
                            <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Sales Cancellation Report</h3>
                            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest bg-rose-500/15 px-2.5 py-0.5 rounded border border-rose-200">Cancellation & Refund Audit Trail</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Audit cancelled bookings, cancellation fees retained by the business, and refund liabilities.</p>
                </div>
                
                {{-- Action Buttons & Total Stats --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0 relative z-10">
                    <div class="px-3.5 py-2 bg-rose-50 border border-rose-200 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-rose-700">Total Cancellation Fee</span>
                        <span class="text-xs font-black text-rose-900 font-mono">₹{{ number_format($salesReturnChartData['total_fee'] ?? 0, 2) }}</span>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-250/20 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Refund Payable</span>
                        <span class="text-xs font-black text-emerald-900 font-mono">₹{{ number_format($salesReturnChartData['total_refund'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Dual Interactive Charts Grid (Horizontal Side-by-Side in Web & PDF) --}}
            <div class="reports-charts-container grid grid-cols-1 lg:grid-cols-12 gap-6 print:gap-4 print:mb-5 print:mt-3">
                {{-- Timeline Area Chart (approx 62% width) --}}
                <div class="reports-chart-timeline-card lg:col-span-8 bg-[#fafbfc] border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-2 print:p-3.5">
                    <div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2.5 print:pb-1.5 mb-1.5">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs print:text-[10px] font-black text-slate-800 uppercase tracking-wider truncate">
                                    CANCELLATION FEES & REFUND OUTFLOW TREND
                                </span>
                            </div>
                            <span class="text-[10px] print:text-[9px] text-slate-400 font-mono shrink-0">Monthly Timeline (₹)</span>
                        </div>
                        <div id="salesReturnChart" class="w-full h-48 print:h-38"></div>
                    </div>

                    {{-- Dedicated Clean Legend Below Area Chart --}}
                    <div class="flex items-center justify-center gap-6 pt-2.5 border-t border-slate-100/80 text-xs print:text-[9.5px] font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-1.5 rounded-xs bg-[#ef4444] inline-block shrink-0"></span>
                            <span>Cancellation Fees Retained (₹)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-1.5 rounded-xs bg-[#00b074] inline-block shrink-0"></span>
                            <span>Refund Amount Payable (₹)</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Breakdown Chart (approx 38% width) --}}
                <div class="reports-chart-donut-card lg:col-span-4 bg-[#fafbfc] border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-2 print:p-3.5">
                    <div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2.5 print:pb-1.5 mb-1.5">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs print:text-[10px] font-black text-slate-800 uppercase tracking-wider truncate">
                                    CANCELLATION FEE VS REFUND BREAKDOWN
                                </span>
                            </div>
                            <span class="text-[10px] print:text-[9px] text-slate-400 font-mono shrink-0">Distribution</span>
                        </div>
                        <div id="salesReturnDonutChart" class="w-full h-48 print:h-38 flex items-center justify-center"></div>
                    </div>

                    {{-- Dedicated Clean Legend Below Donut/Pie Chart --}}
                    <div class="flex items-center justify-center gap-5 pt-2.5 border-t border-slate-100/80 text-xs print:text-[9.5px] font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-1.5 rounded-xs bg-[#ef4444] inline-block shrink-0"></span>
                            <span>Cancellation Fee Retained</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-1.5 rounded-xs bg-[#00b074] inline-block shrink-0"></span>
                            <span>Refund Payable</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter, Print & Export Bar directly above Table (Web Only) --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50 print:hidden">
                @include('reports.partials.filter-bar', ['formId' => 'salesReturnFilterForm', 'actionRoute' => route('reports.sales_return'), 'exportLabel' => 'Export Cancellations'])
            </div>

            {{-- Sales Cancellation Table Card --}}
            <div class="reports-table-card bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden print:border-none print:shadow-none">
                <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex items-center justify-between print:px-4 print:py-2.5">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">
                            Chronological Sales Cancellation & Refund Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Verified audit trail of cancelled or returned units, cancellation fees, and refund liabilities.</p>
                    </div>
                </div>

                <div class="w-full overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-4 py-3 text-white font-extrabold text-center w-12">SL No</th>
                                <th class="px-4 py-3 text-white font-extrabold whitespace-nowrap">Ref Code No.</th>
                                <th class="px-4 py-3 text-white font-extrabold">Customer Entity</th>
                                <th class="px-4 py-3 text-white font-extrabold">Returned Property Unit</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Contract Value</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Paid Amount</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Cancellation Fee</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-655 font-mono">
                            @forelse($salesReturns as $index => $ret)
                            <tr class="hover:bg-rose-50/10 transition-colors duration-150 font-medium text-xs print:text-[9.5px]">
                                <td class="px-4 py-3 text-center font-bold text-slate-400 print:text-slate-700">{{ ($salesReturns->currentPage() - 1) * $salesReturns->perPage() + $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-rose-700 whitespace-nowrap">{{ $ret->sale_number }}</td>
                                <td class="px-4 py-3 font-sans text-slate-800 font-bold whitespace-nowrap">{{ $ret->customer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 font-sans">
                                    <div class="font-bold text-slate-800">{{ $ret->project?->name ?? '—' }}</div>
                                    <div class="text-[10px] text-slate-400">Unit: {{ $ret->unit?->door_no ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">₹{{ number_format($ret->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600 font-bold whitespace-nowrap">₹{{ number_format($ret->total_paid ?? 0.00, 2) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600 font-bold whitespace-nowrap">₹{{ number_format($ret->cancellation_fee, 2) }}</td>
                                <td class="px-4 py-3 text-right text-emerald-700 font-bold whitespace-nowrap">₹{{ number_format($ret->refund_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="print:hidden">{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="saleReturnExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="140" style="width: 105pt;" />  {{-- Ref Code No. --}}
            <col width="260" style="width: 195pt;" />  {{-- Customer Entity --}}
            <col width="300" style="width: 225pt;" />  {{-- Returned Property Unit --}}
            <col width="160" style="width: 120pt;" />  {{-- Contract Value --}}
            <col width="160" style="width: 120pt;" />  {{-- Paid Amount --}}
            <col width="160" style="width: 120pt;" />  {{-- Cancellation Fee --}}
            <col width="160" style="width: 120pt;" />  {{-- Refund Amount --}}
        </colgroup>
        <thead>
            {{-- Empty Spacer Row Top --}}
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    {{ strtoupper($projects->firstWhere('id', request('project_id'))?->name ?? ($projects->first()?->name ?? 'PROJECT')) }} - SALES CANCEL REPORT
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#007398" style="background-color: #007398; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    Cancellation & Refund Audit Trail
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#006039" style="background-color: #006039; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    LEDGER DETAILS
                </th>
            </tr>
            {{-- Empty Spacer Row Middle --}}
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Ref Code No.</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Customer Entity</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Returned Property Unit</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Contract Value (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Paid Amount (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Cancellation Fee (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Refund Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalContract = 0;
                $totalPaid = 0;
                $totalFee = 0;
                $totalRefund = 0;
            @endphp
            @foreach($salesReturns as $index => $ret)
                @php 
                    $totalContract += (float)$ret->total_amount;
                    $totalPaid += (float)($ret->total_paid ?? 0);
                    $totalFee += (float)$ret->cancellation_fee;
                    $totalRefund += (float)$ret->refund_amount;
                    $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F0F8FF'; // Light blue for zebra striping
                    $unitDetails = ($ret->project?->name ?? '—') . ' (Unit: ' . ($ret->unit?->door_no ?? '—') . ')';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000; mso-number-format:'\@';">{{ $ret->sale_number }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $ret->customer?->name ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $unitDetails }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $ret->total_amount }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ $ret->total_paid ?? 0.00 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $ret->cancellation_fee }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ $ret->refund_amount }}</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($salesReturns) > 0)
        <tfoot>
            <tr height="36" style="height: 36pt; font-weight: bold; color: #ffffff;">
                <td colspan="3" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL CANCELLATION LEDGER</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalContract }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalPaid }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalFee }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 13pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalRefund }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
