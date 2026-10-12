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
                        <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <tr class="erp-table-header border-b border-slate-700">
                                <th class="px-4 py-3 text-white font-extrabold text-center w-12">SL No</th>
                                <th class="px-4 py-3 text-white font-extrabold whitespace-nowrap">Ref Code No.</th>
                                <th class="px-4 py-3 text-white font-extrabold">Customer Entity</th>
                                <th class="px-4 py-3 text-white font-extrabold">Unit Details</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Contract Value</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Paid Amount</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right">Cancellation Fee</th>
                                <th class="px-4 py-3 text-white font-extrabold">Payable / Receivable</th>
                                <th class="px-4 py-3 text-white font-extrabold text-center print:hidden w-24">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-655 font-mono">
                            @forelse($salesReturns as $index => $ret)
                            @php
                                $cancelLog = $ret->statusLogs->first(fn($l) => in_array($l->event_type, ['cancellation', 'return']));
                                $cancelledByName = $cancelLog?->performedBy?->name ?? 'Authorized Administrator';
                                $cancelledLogReason = $cancelLog?->reason ?? null;

                                $approvedRefundAmount = (float)$ret->refund_amount + (float)($ret->refund_paid ?? 0);
                                $runningDisbursed = 0;
                                $refundPaymentsWithLedger = array_map(function($rp, $idx) use (&$runningDisbursed, $approvedRefundAmount) {
                                    $amt = (float)($rp['amount'] ?? 0);
                                    $runningDisbursed += $amt;
                                    $rp['sl'] = $idx + 1;
                                    $rp['cumulative'] = $runningDisbursed;
                                    $rp['balance'] = max(0, $approvedRefundAmount - $runningDisbursed);
                                    return $rp;
                                }, $ret->refund_payments ?? [], array_keys($ret->refund_payments ?? []));

                                $saleModalPayload = [
                                    'id' => $ret->id,
                                    'sale_number' => $ret->sale_number,
                                    'status' => $ret->status,
                                    'sale_date' => $ret->sale_date ? $ret->sale_date->format('d M Y') : ($ret->agreement_date ? $ret->agreement_date->format('d M Y') : '—'),
                                    'agreement_date' => $ret->agreement_date ? $ret->agreement_date->format('d M Y') : '—',
                                    'cancelled_at' => $ret->cancelled_at ? \Carbon\Carbon::parse($ret->cancelled_at)->format('d M Y, h:i A') : ($ret->updated_at ? $ret->updated_at->format('d M Y, h:i A') : '—'),
                                    'cancelled_by' => $cancelledByName,
                                    'cancellation_reason' => $ret->cancellation_reason ?: ($cancelledLogReason ?: 'Customer Cancellation Request'),
                                    'notes' => $ret->notes ?: ($ret->remarks ?: '—'),
                                    'contract_value' => (float)$ret->total_amount,
                                    'base_amount' => (float)($ret->base_amount ?? $ret->sale_amount ?? 0),
                                    'gst_percentage' => (float)($ret->gst_percentage ?? 0),
                                    'gst_amount' => (float)($ret->gst_amount ?? 0),
                                    'rate_per_sqft' => (float)($ret->rate_per_sqft ?? 0),
                                    'total_paid' => (float)($ret->total_paid ?? 0),
                                    'cancellation_fee' => (float)($ret->cancellation_fee ?? 0),
                                    'additional_refund_amount' => (float)($ret->additional_refund_amount ?? 0),
                                    'refund_amount' => (float)$ret->refund_amount + (float)($ret->refund_paid ?? 0),
                                    'refund_paid' => (float)($ret->refund_paid ?? 0),
                                    'refund_balance' => max(0, (float)$ret->refund_amount),
                                    'payment_plan' => $ret->payment_plan ?: '—',
                                    'customer' => [
                                        'name' => $ret->customer?->name ?? '—',
                                        'phone' => $ret->customer?->phone ?? '—',
                                        'email' => $ret->customer?->email ?? '—',
                                        'address' => $ret->customer?->address ?? '—',
                                    ],
                                    'project' => [
                                        'name' => $ret->project?->name ?? '—',
                                        'code' => $ret->project?->code ?? '—',
                                        'location' => $ret->project?->location ?? '—',
                                        'city' => $ret->project?->city ?? '—',
                                    ],
                                    'unit' => [
                                        'door_no' => $ret->unit?->door_no ?? '—',
                                        'floor' => $ret->unit?->floor?->name ?? '—',
                                        'type' => $ret->unit?->unitType?->name ?? '—',
                                        'category' => $ret->unit?->unitType?->category ?? '—',
                                        'built_up_area' => $ret->unit?->built_up_area ?? 0,
                                        'carpet_area' => $ret->unit?->carpet_area ?? 0,
                                    ],
                                    'broker' => $ret->broker ? [
                                        'name' => $ret->broker->name,
                                        'phone' => $ret->broker->phone ?? '—',
                                    ] : null,
                                    'receipts' => $ret->receipts->map(function($rc) {
                                        return [
                                            'id' => $rc->id,
                                            'receipt_date' => $rc->receipt_date ? $rc->receipt_date->format('d M Y') : '—',
                                            'amount' => (float)$rc->amount,
                                            'payment_mode' => $rc->payment_mode ?? '—',
                                            'reference_no' => $rc->reference_no ?? '—',
                                            'bank_name' => $rc->bank?->bank_name ?? ($rc->companyBankAccount?->bank_name ?? '—'),
                                            'status' => $rc->realization_status_label ?? ($rc->realization_status ?? 'Realized'),
                                        ];
                                    })->values()->all(),
                                    'refund_payments' => $refundPaymentsWithLedger,
                                ];
                            @endphp
                            <tr class="hover:bg-rose-50/10 transition-colors duration-150 font-medium text-xs print:text-[9.5px]">
                                {{-- Hidden container ensuring strict valid HTML5 DOM parsing --}}
                                <td class="hidden" style="display: none;">
                                    <script type="application/json" id="cancel-sale-data-{{ $ret->id }}">
                                        {!! json_encode($saleModalPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}
                                    </script>
                                </td>

                                <td class="px-4 py-3 text-center font-bold text-slate-400 print:text-slate-700">{{ ($salesReturns->currentPage() - 1) * $salesReturns->perPage() + $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-rose-700 whitespace-nowrap">
                                    <button type="button" @click.stop="openCancelModalById({{ $ret->id }})" 
                                            class="text-left font-bold text-rose-700 hover:text-[#a38c29] hover:underline transition-colors inline-flex items-center gap-1.5 cursor-pointer group"
                                            title="Click to view full cancellation details">
                                        <span>{{ $ret->sale_number }}</span>
                                        <svg class="w-3 h-3 opacity-40 group-hover:opacity-100 text-[#a38c29] transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </button>
                                </td>
                                <td class="px-4 py-3 font-sans text-slate-800 font-bold whitespace-nowrap">{{ $ret->customer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 font-sans">
                                    <div class="font-bold text-slate-800">{{ $ret->project?->name ?? '—' }}</div>
                                    <div class="text-[10px] text-slate-400">Unit: {{ $ret->unit?->door_no ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">₹{{ number_format($ret->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600 font-bold whitespace-nowrap">₹{{ number_format($ret->total_paid ?? 0.00, 2) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600 font-bold whitespace-nowrap">₹{{ number_format($ret->cancellation_fee, 2) }}</td>
                                @php
                                    $refPaid = (float)($ret->refund_paid ?? 0);
                                    $refLiability = (float)$ret->refund_amount + $refPaid;
                                @endphp
                                @if($refLiability > 0)
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-orange-600 font-bold">Payable (₹{{ number_format($ret->refund_amount, 2) }})</span>
                                    <span class="text-[10px] text-slate-500 font-semibold block">Paid: ₹{{ number_format($refPaid, 2) }} / Total: ₹{{ number_format($refLiability, 2) }}</span>
                                </td>
                                @else
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-teal-600 font-bold">Receivable (₹{{ number_format(max(0, (float)$ret->cancellation_fee - (float)($ret->total_paid ?? 0)), 2) }})</span>
                                </td>
                                @endif
                                <td class="px-4 py-3 text-center print:hidden whitespace-nowrap">
                                    <button type="button" @click.stop="openCancelModalById({{ $ret->id }})"
                                            class="w-7 h-7 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white transition-all inline-flex items-center justify-center shadow-2xs"
                                            title="View Cancellation & Refund Report">
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <!-- <span>View</span> -->
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="print:hidden">{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
    </div>

    <!-- ── CANCELLATION & REFUND AUDIT REPORT VIEW MODAL ── -->
    <div x-show="showCancelModal" 
         x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-5 modal-backdrop bg-slate-900/60 print:hidden" 
         style="display: none; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);" 
         x-transition.opacity
         @keydown.escape.window="closeCancelModal()">

        <div class="w-full max-w-5xl max-h-[92vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-fade-in-up" 
             @click.away="closeCancelModal()">
            
            <!-- Modal Header (Matching sales?tab=sale-return View Modal Style) -->
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-3.5 border-b border-primary-500/10 shrink-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-primary/20 text-primary text-[9px] font-bold uppercase tracking-widest whitespace-nowrap"
                                  x-text="(selectedCancelSale?.status || 'cancelled').toUpperCase()">CANCELLED</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-white tracking-tight mt-1 uppercase">CANCELLATION DETAILS</h2>
                    </div>
                    <button type="button" @click="closeCancelModal()" 
                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 shadow-sm cursor-pointer"
                            title="Close">✕</button>
                </div>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto bg-slate-50/60 space-y-5 flex-1 custom-scrollbar">

                <!-- Interactive Tabs Bar (Inside Modal Body) -->
                <div class="flex items-center gap-2 p-1 shadow-2xs w-fit overflow-x-auto">
                    <button type="button" @click="cancelModalTab = 'dossier'"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 cursor-pointer shrink-0"
                            :class="cancelModalTab === 'dossier' ? 'bg-[#a38c29] text-white shadow-sm ring-1 ring-[#d4af37]/50' : 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>1. Cancellation Details</span>
                    </button>
                    <!-- <button type="button" @click="cancelModalTab = 'receipts'"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 cursor-pointer shrink-0"
                            :class="cancelModalTab === 'receipts' ? 'bg-[#a38c29] text-white shadow-sm ring-1 ring-[#d4af37]/50' : 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>2. Advance Receipts</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-extrabold"
                              :class="cancelModalTab === 'receipts' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                              x-text="selectedCancelSale?.receipts?.length || 0">0</span>
                    </button> -->
                    <button type="button" @click="cancelModalTab = 'refunds'"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 cursor-pointer shrink-0"
                            :class="cancelModalTab === 'refunds' ? 'bg-[#a38c29] text-white shadow-sm ring-1 ring-[#d4af37]/50' : 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900'">
                        <span class="font-black text-xs leading-none">₹</span>
                        <span>3. Refund Payout Summary</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-extrabold"
                              :class="cancelModalTab === 'refunds' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                              x-text="selectedCancelSale?.refund_payments?.length || 0">0</span>
                    </button>
                </div>

                {{-- ══ TAB 1: CANCELLATION DOSSIER ══ --}}
                <div x-show="cancelModalTab === 'dossier'" class="space-y-5">
                    <!-- Banner Card: Primary Parties & Dates -->
                <div class="bg-white rounded-2xl p-5 md:p-6 border border-slate-200/90 shadow-2xs grid grid-cols-1 md:grid-cols-3 gap-6 md:divide-x divide-slate-100">
                    <!-- Customer Entity -->
                    <div class="space-y-2 md:pr-5">
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>CUSTOMER Details</span>
                        </div>
                        <div class="text-sm font-black text-slate-900" x-text="selectedCancelSale?.customer?.name || '—'"></div>
                        <div class="text-[11px] text-slate-500 font-medium space-y-1">
                            <div class="flex items-center gap-1.5" x-show="selectedCancelSale?.customer?.phone">
                                <span class="text-slate-400">Phone:</span>
                                <span class="font-mono font-bold text-slate-700" x-text="selectedCancelSale?.customer?.phone"></span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="selectedCancelSale?.customer?.email">
                                <span class="text-slate-400">Email:</span>
                                <span class="text-slate-700 truncate font-semibold" x-text="selectedCancelSale?.customer?.email"></span>
                            </div>
                            <div class="flex items-start gap-1.5 pt-0.5" x-show="selectedCancelSale?.customer?.address">
                                <span class="text-slate-400">Address:</span>
                                <span class="text-slate-700 font-medium leading-tight" x-text="selectedCancelSale?.customer?.address"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Returned Unit -->
                    <div class="space-y-2 md:px-5 border-t-0">
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>RETURNED UNIT</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 text-xs font-black font-mono"
                                  x-text="'UNIT: ' + (selectedCancelSale?.unit?.door_no || '—')"></span>
                            <span class="text-slate-300">•</span>
                            <span class="text-xs font-bold text-slate-800" x-text="(selectedCancelSale?.unit?.floor || '') + ' (' + (selectedCancelSale?.unit?.type || '') + ')'"></span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-medium space-y-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-slate-400">Project:</span>
                                <span class="font-semibold text-slate-700" x-text="selectedCancelSale?.project?.name || '—'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps & Status -->
                    <div class="space-y-2 md:pl-5 border-t-0">
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>CANCELLATION TIMELINE</span>
                        </div>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 text-[11px]">Booking / Agreement:</span>
                                <span class="font-bold text-slate-800 text-[11px]" x-text="selectedCancelSale?.agreement_date || selectedCancelSale?.sale_date || '—'"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 text-[11px]">Cancellation Date:</span>
                                <span class="font-bold text-rose-700 font-mono text-[11px]" x-text="selectedCancelSale?.cancelled_at || '—'"></span>
                            </div>
                            <div class="flex items-center justify-between pt-1.5 border-t border-slate-100">
                                <span class="text-slate-500 text-[11px]">Refund Status:</span>
                                <span class="px-2 py-0.5 rounded text-[9.5px] font-extrabold uppercase tracking-wide"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800 border border-emerald-300': getCancelRefundStatus(selectedCancelSale) === 'Completed' || getCancelRefundStatus(selectedCancelSale) === 'Settled',
                                          'bg-blue-100 text-blue-800 border border-blue-300': getCancelRefundStatus(selectedCancelSale) === 'Partially Paid',
                                          'bg-amber-100 text-amber-800 border border-amber-300': getCancelRefundStatus(selectedCancelSale) === 'Pending'
                                      }"
                                      x-text="getCancelRefundStatus(selectedCancelSale)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financial Ledger Key Performance Indicators (6 Metric Cards Always Visible) --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <!-- 1. Contract Value -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-slate-700">
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block truncate">CONTRACT VALUE</span>
                        <span class="text-sm font-black font-mono text-slate-900 block truncate" x-text="fmtMoney(selectedCancelSale?.contract_value)">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate">Agreed Value</span>
                    </div>

                    <!-- 2. Paid Till Cancellation -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-emerald-500">
                        <span class="text-[9px] font-black uppercase tracking-wider text-emerald-700 block truncate">TOTAL PAID AMOUNT</span>
                        <span class="text-sm font-black font-mono text-emerald-600 block truncate" x-text="fmtMoney(selectedCancelSale?.total_paid)">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate" x-text="(selectedCancelSale?.receipts?.length || 0) + ' Receipts Recorded'"></span>
                    </div>

                    <!-- 3. Cancellation Fee Retained -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-rose-500">
                        <span class="text-[9px] font-black uppercase tracking-wider text-rose-700 block truncate">CANCELLATION FEE</span>
                        <span class="text-sm font-black font-mono text-rose-600 block truncate" x-text="fmtMoney(selectedCancelSale?.cancellation_fee)">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate">Penalty / Retained</span>
                    </div>

                    <!-- 4. Approved Refund Amount -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-[#a38c29]">
                        <span class="text-[9px] font-black uppercase tracking-wider text-[#a38c29] block truncate">APPROVED NET REFUND</span>
                        <span class="text-sm font-black font-mono text-[#a38c29] block truncate" x-text="fmtMoney(selectedCancelSale?.refund_amount)">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate">Approved Liability</span>
                    </div>

                    <!-- 5. Disbursed Refund Paid -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-emerald-500">
                        <span class="text-[9px] font-black uppercase tracking-wider text-emerald-700 block truncate">REFUND PAID OUT</span>
                        <span class="text-sm font-black font-mono text-emerald-600 block truncate" x-text="fmtMoney(selectedCancelSale?.refund_paid)">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate" x-text="(selectedCancelSale?.refund_payments?.length || 0) + ' Vouchers Paid'"></span>
                    </div>

                    <!-- 6. Pending Refund Balance -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-1 border-l-4 border-l-amber-500">
                        <span class="text-[9px] font-black uppercase tracking-wider text-amber-700 block truncate">PENDING BALANCE DUE</span>
                        <span class="text-sm font-black font-mono text-amber-600 block truncate" 
                              x-text="fmtMoney(Math.max(0, (selectedCancelSale?.refund_amount || 0) - (selectedCancelSale?.refund_paid || 0)))">₹0.00</span>
                        <span class="text-[9px] text-slate-400 font-medium block truncate">Remaining Payable</span>
                    </div>
                </div>

                <!-- Two-Column Cards: Detailed Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- Panel 1: Cancellation Reason & Audit Details -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs space-y-3.5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Cancellation Audit Details</h3>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-mono font-bold">Audit Verified</span>
                            </div>

                            <div class="space-y-2.5 text-xs">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-0.5">Primary Cancellation Reason</span>
                                    <div class="p-2.5 rounded-xl bg-rose-50/60 border border-rose-100 text-slate-900 font-bold"
                                         x-text="selectedCancelSale?.cancellation_reason || 'Customer Cancellation Request'"></div>
                                </div>

                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-0.5">Detailed Remarks / Registry Notes</span>
                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-700 leading-relaxed font-medium"
                                         x-text="selectedCancelSale?.notes || 'No additional cancellation notes provided.'"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Cancellation Fee Deduction</span>
                                        <span class="text-xs font-bold text-rose-700 font-mono" x-text="fmtMoney(selectedCancelSale?.cancellation_fee)"></span>
                                    </div>
                                    <div x-show="Number(selectedCancelSale?.additional_refund_amount || 0) > 0">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 block">Additional Approved Refund</span>
                                        <span class="text-xs font-bold text-emerald-700 font-mono" x-text="'+ ' + fmtMoney(selectedCancelSale?.additional_refund_amount)"></span>
                                    </div>
                                    <div :class="Number(selectedCancelSale?.additional_refund_amount || 0) > 0 ? 'col-span-2' : ''">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Net Refund Calculation</span>
                                        <span class="text-xs font-bold text-slate-800 font-mono" 
                                              x-text="'Paid ' + fmtMoney(selectedCancelSale?.total_paid) + ' - Fee ' + fmtMoney(selectedCancelSale?.cancellation_fee) + (Number(selectedCancelSale?.additional_refund_amount || 0) > 0 ? ' + Addl ' + fmtMoney(selectedCancelSale?.additional_refund_amount) : '')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 2: Property & Agreement Specifications -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs space-y-3.5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Unit & Commercial Terms</h3>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-mono font-bold" x-text="selectedCancelSale?.project?.code || 'HEV'"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-xs">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Project Location</span>
                                    <span class="font-bold text-slate-800" x-text="(selectedCancelSale?.project?.location || '') + (selectedCancelSale?.project?.city ? ', ' + selectedCancelSale?.project?.city : '') || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Rate per Sq.Ft</span>
                                    <span class="font-bold text-slate-800 font-mono" x-text="fmtMoney(selectedCancelSale?.rate_per_sqft)"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Base Value</span>
                                    <span class="font-bold text-slate-800 font-mono" x-text="fmtMoney(selectedCancelSale?.base_amount)"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">GST Taxes</span>
                                    <span class="font-bold text-slate-800 font-mono" x-text="fmtMoney(selectedCancelSale?.gst_amount) + (selectedCancelSale?.gst_percentage ? ' (' + selectedCancelSale?.gst_percentage + '%)' : '')"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Payment Plan</span>
                                    <span class="font-bold text-slate-800 uppercase" x-text="selectedCancelSale?.payment_plan || 'Lump Sum'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Broker</span>
                                    <span class="font-bold text-slate-800" x-text="selectedCancelSale?.broker?.name || 'Direct / None'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TAB 2: CUSTOMER ADVANCE RECEIPTS LEDGER ══ --}}
                <div x-show="cancelModalTab === 'receipts'" class="space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">₹</div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">
                                    Customer Advance Receipts (Collected Prior To Cancellation)
                                </h4>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-700 font-mono"
                                  x-text="'Total Paid: ' + fmtMoney(selectedCancelSale?.total_paid)"></span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left border-collapse">
                                <thead class="bg-slate-100/80 text-slate-600 text-[10px] font-black uppercase tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5 text-center w-12">#</th>
                                        <th class="px-4 py-2.5">Receipt Date</th>
                                        <th class="px-4 py-2.5">Payment Mode</th>
                                        <th class="px-4 py-2.5">Reference / Cheque No.</th>
                                        <th class="px-4 py-2.5">Bank / Company Account</th>
                                        <th class="px-4 py-2.5 text-center">Realization Status</th>
                                        <th class="px-4 py-2.5 text-right font-mono">Amount (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    <template x-for="(rc, i) in (selectedCancelSale?.receipts || [])" :key="rc.id || i">
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-2.5 text-center text-slate-400 font-bold" x-text="i + 1"></td>
                                            <td class="px-4 py-2.5 font-bold text-slate-900" x-text="rc.receipt_date || '—'"></td>
                                            <td class="px-4 py-2.5 font-semibold text-slate-700" x-text="rc.payment_mode || '—'"></td>
                                            <td class="px-4 py-2.5 font-mono text-slate-600" x-text="rc.reference_no || '—'"></td>
                                            <td class="px-4 py-2.5 text-slate-600" x-text="rc.bank_name || '—'"></td>
                                            <td class="px-4 py-2.5 text-center">
                                                <span class="px-2 py-0.5 rounded text-[9.5px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200"
                                                      x-text="rc.status || 'Realized'"></span>
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-mono font-bold text-emerald-600 text-xs" x-text="fmtMoney(rc.amount)"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="!selectedCancelSale?.receipts || selectedCancelSale?.receipts.length === 0">
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No customer advance receipts were recorded prior to cancellation.</td>
                                    </tr>
                                </tbody>
                                <tfoot x-show="selectedCancelSale?.receipts && selectedCancelSale?.receipts.length > 0" class="bg-slate-50/80 border-t border-slate-200 text-xs font-bold">
                                    <tr>
                                        <td colspan="6" class="px-4 py-2.5 text-slate-700 uppercase text-[10px] tracking-wider text-right">TOTAL ADVANCE RECEIPTS COLLECTED</td>
                                        <td class="px-4 py-2.5 text-right font-mono font-black text-emerald-700 text-xs" x-text="fmtMoney(selectedCancelSale?.total_paid)"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ TAB 3: REFUND DISBURSEMENT VOUCHERS LEDGER ══ --}}
                <div x-show="cancelModalTab === 'refunds'" class="space-y-4">
                    {{-- Status Banner --}}
                    <div class="p-3.5 rounded-xl border flex items-center justify-between gap-4 text-xs font-semibold"
                         :class="Number(selectedCancelSale?.refund_amount || 0) > Number(selectedCancelSale?.refund_paid || 0) ? 'bg-amber-50/90 border-amber-200 text-amber-900' : 'bg-emerald-50/90 border-emerald-200 text-emerald-900'">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full"
                                  :class="Number(selectedCancelSale?.refund_amount || 0) > Number(selectedCancelSale?.refund_paid || 0) ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500'"></span>
                            <span x-show="Number(selectedCancelSale?.refund_amount || 0) > Number(selectedCancelSale?.refund_paid || 0)">
                                Pending Refund Liability of <strong class="font-mono text-amber-950 font-bold" x-text="fmtMoney(Math.max(0, (selectedCancelSale?.refund_amount || 0) - (selectedCancelSale?.refund_paid || 0)))"></strong> remaining to be disbursed.
                            </span>
                            <span x-show="Number(selectedCancelSale?.refund_amount || 0) <= Number(selectedCancelSale?.refund_paid || 0)">
                                All refund liabilities for this cancellation have been fully paid and settled.
                            </span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded"
                              :class="Number(selectedCancelSale?.refund_amount || 0) > Number(selectedCancelSale?.refund_paid || 0) ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
                              x-text="Number(selectedCancelSale?.refund_amount || 0) > Number(selectedCancelSale?.refund_paid || 0) ? 'Payout Due' : 'Fully Settled'"></span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">₹</div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">
                                    Customer Refund Disbursement Vouchers (Paid Back)
                                </h4>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-700 font-mono"
                                  x-text="'Total Disbursed: ' + fmtMoney(selectedCancelSale?.refund_paid)"></span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left border-collapse">
                                <thead class="bg-slate-100/80 text-slate-600 text-[10px] font-black uppercase tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5 text-center w-12">#</th>
                                        <th class="px-4 py-2.5">Voucher Number</th>
                                        <th class="px-4 py-2.5">Disbursement Date</th>
                                        <th class="px-4 py-2.5">Payment Mode</th>
                                        <th class="px-4 py-2.5">Source Bank Account</th>
                                        <th class="px-4 py-2.5">Remarks</th>
                                        <th class="px-4 py-2.5 text-right font-mono">Disbursed (₹)</th>
                                        <th class="px-4 py-2.5 text-right font-mono">Balance (₹)</th>
                                        <th class="px-4 py-2.5 text-center">Processed By</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    <template x-for="(rp, i) in (selectedCancelSale?.refund_payments || [])" :key="rp.voucher_id || i">
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-2.5 text-center text-slate-400 font-bold" x-text="i + 1"></td>
                                            <td class="px-4 py-2.5 font-bold text-slate-900 font-mono" x-text="rp.voucher_number || '—'"></td>
                                            <td class="px-4 py-2.5 font-semibold text-slate-800" x-text="rp.date || '—'"></td>
                                            <td class="px-4 py-2.5 text-slate-700" x-text="rp.payment_mode || '—'"></td>
                                            <td class="px-4 py-2.5 text-slate-600" x-text="rp.bank_name || '—'"></td>
                                            <td class="px-4 py-2.5 text-slate-500 italic text-[11px]" x-text="rp.remarks || '—'"></td>
                                            <td class="px-4 py-2.5 text-right font-mono font-bold text-emerald-600 text-xs" x-text="fmtMoney(rp.amount)"></td>
                                            <td class="px-4 py-2.5 text-right font-mono font-bold text-amber-600 text-xs" x-text="fmtMoney(rp.balance)"></td>
                                            <td class="px-4 py-2.5 text-center text-slate-600 text-[11px]" x-text="rp.processed_by || 'Accountant'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="!selectedCancelSale?.refund_payments || selectedCancelSale?.refund_payments.length === 0">
                                        <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic">
                                            <div class="space-y-1">
                                                <div>No refund disbursement vouchers have been issued yet.</div>
                                                <div class="text-[11px] font-bold text-amber-700"
                                                     x-show="Number(selectedCancelSale?.refund_amount || 0) > 0"
                                                     x-text="'Net approved refund amount of ' + fmtMoney(selectedCancelSale?.refund_amount) + ' is pending disbursement.'"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot x-show="selectedCancelSale?.refund_payments && selectedCancelSale?.refund_payments.length > 0" class="bg-slate-50/80 border-t border-slate-200 text-xs font-bold">
                                    <tr>
                                        <td colspan="6" class="px-4 py-2.5 text-slate-700 uppercase text-[10px] tracking-wider text-right">TOTAL REFUND DISBURSED</td>
                                        <td class="px-4 py-2.5 text-right font-mono font-black text-emerald-700 text-xs" x-text="fmtMoney(selectedCancelSale?.refund_paid)"></td>
                                        <td class="px-4 py-2.5 text-right font-mono font-black text-amber-700 text-xs" x-text="fmtMoney(Math.max(0, (selectedCancelSale?.refund_amount || 0) - (selectedCancelSale?.refund_paid || 0)))"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-2 border-slate-200/90 bg-white flex items-center justify-end gap-3 shrink-0">
                <button type="button" @click="closeCancelModal()"
                        class="px-5 py-2.5 rounded-xl border border-slate-250 hover:bg-slate-100 text-slate-700 text-xs font-extrabold uppercase tracking-wider transition shadow-2xs active:scale-95 cursor-pointer">
                    Close
                </button>
            </div>

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
                    $totalRefund += (float)$ret->refund_amount + (float)($ret->refund_paid ?? 0);
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
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ (float)$ret->refund_amount + (float)($ret->refund_paid ?? 0) }}</td>
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
