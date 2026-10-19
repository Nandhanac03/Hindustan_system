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
                        <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <tr class="erp-table-header border-b border-white/20">
                                <th rowspan="2" class="px-4 py-3 text-left align-middle border-r border-white/20 whitespace-nowrap">Exchange No.</th>
                                <th rowspan="2" class="px-3 py-3 text-left align-middle border-r border-white/20 whitespace-nowrap">Date</th>
                                <th rowspan="2" class="px-3 py-3 text-left align-middle border-r border-white/20">Project</th>
                                
                                {{-- Old Unit Main Header --}}
                                <th colspan="2" class="px-3 py-2 text-center bg-black/20 text-white border-r border-l border-white/20 font-black tracking-wider">
                                    Old Unit (Cancelled)
                                </th>
                                
                                {{-- New Unit Main Header --}}
                                <th colspan="2" class="px-3 py-2 text-center bg-black/30 text-white border-r border-white/20 font-black tracking-wider">
                                    New Unit (Booked)
                                </th>
                                
                                <th rowspan="2" class="px-3 py-3 text-right align-middle border-r border-white/20 whitespace-nowrap">Equity Applied (Paid)</th>
                                <th rowspan="2" class="px-3 py-3 text-right align-middle border-r border-white/20 whitespace-nowrap">Contract Value</th>
                                <th rowspan="2" class="px-3 py-3 text-center align-middle border-r border-white/20 whitespace-nowrap">Status</th>
                                <th rowspan="2" class="px-3 py-3 text-center align-middle whitespace-nowrap print:hidden">Actions</th>
                            </tr>
                            <tr class="erp-table-header border-b border-white/20 text-[9.5px]">
                                {{-- Old Unit Subheaders --}}
                                <th class="px-3 py-1.5 text-left bg-black/20 text-amber-100 border-l border-r border-white/20 font-extrabold uppercase tracking-wider whitespace-nowrap">Unit Details</th>
                                <th class="px-3 py-1.5 text-left bg-black/20 text-amber-100 border-r border-white/20 font-extrabold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                
                                {{-- New Unit Subheaders --}}
                                <th class="px-3 py-1.5 text-left bg-black/30 text-amber-100 border-r border-white/20 font-extrabold uppercase tracking-wider whitespace-nowrap">Unit Details</th>
                                <th class="px-3 py-1.5 text-left bg-black/30 text-amber-100 border-r border-white/20 font-extrabold uppercase tracking-wider whitespace-nowrap">Customer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                            @forelse($exchangeEntries as $index => $row)
                            @php
                                $exLog = $row->statusLogs ? $row->statusLogs->firstWhere('event_type', 'exchanged') : null;
                                $snap = $exLog?->snapshot_data ?? [];
                                $newSale = $row->new_sale ?? null;
                                
                                // Old unit display formatting
                                $oldDoor = $snap['old_unit']['door_no'] ?? ($row->unit?->door_no ?? '—');
                                $oldType = $snap['old_unit']['unit_type_name'] ?? ($row->unit?->unitType?->name ?? 'Shop');
                                $oldFloor = $snap['old_unit']['floor_name'] ?? ($row->unit?->floor?->name ?? '—');

                                if ($row->saleUnits && $row->saleUnits->count() > 0) {
                                    $oldUnitStr = $row->saleUnits->map(function ($su) {
                                        $door = $su->unit?->door_no ?? '—';
                                        $type = $su->unit?->unitType?->name ?? 'Shop';
                                        $floor = $su->unit?->floor?->name ?? '—';
                                        return $door . ($type ? "({$type})" : "") . ($floor ? " - {$floor}" : "");
                                    })->filter()->implode(', ');
                                } else {
                                    $oldUnitStr = $oldDoor . ($oldType ? "({$oldType})" : "") . ($oldFloor ? " - {$oldFloor}" : "");
                                }
                                
                                // New unit display formatting
                                $newDoor = $newSale?->unit?->door_no ?? ($snap['exchange_meta']['target_door_no'] ?? '—');
                                $newType = $newSale?->unit?->unitType?->name ?? ($newSale?->unit?->type ?? 'Shop');
                                $newFloor = $newSale?->unit?->floor?->name ?? '—';
                                $newUnitStr = $newDoor . ($newType ? "({$newType})" : "") . ($newFloor ? " - {$newFloor}" : "");
                                
                                $oldVal = (float)($snap['old_sale']['total_amount'] ?? $row->total_amount ?? 0);
                                $oldDownPayment = (float)($row->old_down_payment ?? 0);
                                $newDownPayment = (float)($row->new_down_payment ?? 0);
                                $paidVal = (float)($row->equity_paid ?? ($oldDownPayment + $newDownPayment));
                                $newVal = (float)($newSale?->total_amount ?? ($row->new_unit_value ?? 0));
                                $netDue = round($newVal - $paidVal, 2);

                                $statusText = 'Fully Settled';
                                if ($netDue > 0) {
                                    $statusText = 'PAYABLE BY CUSTOMER';
                                } elseif ($netDue < 0) {
                                    $statusText = 'REFUNDABLE TO CUSTOMER';
                                }

                                $archivedMeta = $snap['exchange_meta'] ?? [];
                                $archivedOldSale = $snap['old_sale'] ?? [];
                                $archivedOldUnit = $snap['old_unit'] ?? [];
                                $archivedInstallments = $snap['installments'] ?? [];
                                $archivedReceipts = $snap['receipts'] ?? [];

                                $exchangeModalPayload = [
                                    'id' => $row->id,
                                    'customer' => [
                                        'name' => $row->customer?->name ?? ($snap['customer']['name'] ?? '—'),
                                        'phone' => $row->customer?->phone ?? ($snap['customer']['phone'] ?? ''),
                                    ],
                                    'sale_number' => $row->sale_number,
                                    'booking_date' => $row->sale_date ? \Carbon\Carbon::parse($row->sale_date)->format('d M Y') : '',
                                    'project' => [
                                        'name' => $row->project?->name ?? ($snap['project']['name'] ?? '—'),
                                        'code' => $row->project?->code ?? ($snap['project']['code'] ?? ''),
                                    ],
                                    'unit' => [
                                        'door_no' => $oldDoor,
                                        'unit_type_name' => $oldType,
                                        'floor_name' => $oldFloor,
                                    ],
                                    'notes' => $archivedMeta['exchange_reason'] ?? ($row->cancellation_reason ?: ($row->notes ?: 'sale')),
                                    'old_unit' => [
                                        'project_name' => $row->project?->name ?? ($snap['project']['name'] ?? '—'),
                                        'unit_details' => $oldUnitStr,
                                        'original_value' => $oldVal,
                                        'paid_amount' => $oldDownPayment,
                                    ],
                                    'new_unit' => [
                                        'project_name' => $newSale?->project?->name ?? ($row->project?->name ?? '—'),
                                        'unit_details' => $newUnitStr,
                                        'new_value' => $newVal,
                                        'transferred_equity' => $newDownPayment,
                                    ],
                                    'paid_amount' => $paidVal,
                                    'old_down_payment' => $oldDownPayment,
                                    'new_down_payment' => $newDownPayment,
                                    'net_balance_due' => abs($netDue),
                                    'net_due_raw' => $netDue,
                                    'status_text' => $statusText,
                                    'is_payable' => $netDue > 0,
                                    'is_refundable' => $netDue < 0,
                                    'has_snapshot' => !empty($snap),
                                    'snapshot' => [
                                        'archived_at' => $archivedMeta['exchanged_at'] ?? ($row->exchange_date ? \Carbon\Carbon::parse($row->exchange_date)->format('Y-m-d H:i:s') : ''),
                                        'old_sale_number' => $archivedOldSale['sale_number'] ?? $row->sale_number,
                                        'old_door_no' => $archivedOldUnit['door_no'] ?? $oldDoor,
                                        'old_type_and_floor' => trim(($archivedOldUnit['unit_type_name'] ?? $oldType) . ' • ' . ($archivedOldUnit['floor_name'] ?? $oldFloor), ' •'),
                                        'old_contract_value' => (float)($archivedOldSale['total_amount'] ?? $oldVal),
                                        'total_paid' => $oldDownPayment,
                                        'receipts_count' => count($archivedReceipts),
                                        'carry_forward' => !empty($archivedMeta['carry_forward']) ? 'YES (EQUITY TRANSFERRED)' : 'No',
                                        'processed_by' => $archivedMeta['exchanged_by_user'] ?? 'Owner',
                                        'installments' => $archivedInstallments,
                                        'receipts' => $archivedReceipts,
                                    ]
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                {{-- Hidden container ensuring strict valid HTML5 DOM parsing --}}
                                <td class="hidden" style="display: none;">
                                    <script type="application/json" id="exchange-sale-data-{{ $row->id }}">
                                        {!! json_encode($exchangeModalPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}
                                    </script>
                                </td>

                                {{-- Exchange No. --}}
                                <td class="px-4 py-3 font-bold text-primary font-mono text-left whitespace-nowrap">
                                    <button type="button" @click.stop="openExchangeModalById({{ $row->id }})"
                                            class="text-left font-bold text-primary hover:text-[#a38c29] hover:underline transition-colors inline-flex items-center gap-1.5 cursor-pointer group"
                                            title="Click to view full exchange details">
                                        <span>{{ $row->exchange_no }}</span>
                                        <svg class="w-3 h-3 opacity-40 group-hover:opacity-100 text-[#a38c29] transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </button>
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

                                {{-- Actions --}}
                                <td class="px-3 py-3 text-center print:hidden whitespace-nowrap">
                                    <button type="button" @click.stop="openExchangeModalById({{ $row->id }})"
                                            class="w-7 h-7 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white transition-all inline-flex items-center justify-center shadow-2xs cursor-pointer group"
                                            title="View Exchange Details">
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-slate-400 italic">No unit exchanges registered.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="print:hidden">{{ $exchangeEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>

    {{-- VIEW EXCHANGE DETAILS MODAL POPUP (EXACT SAME UI AS UNIT EXCHANGE) --}}
    <div x-show="showExchangeModal" 
         x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm print:hidden" 
         style="display: none;" 
         x-transition.opacity
         @keydown.escape.window="closeExchangeModal()">

        <div class="w-full max-w-4xl max-h-[90vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-fade-in my-auto" 
             @click.away="closeExchangeModal()">
            
            {{-- Header Combo --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 shrink-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Exchanged</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wider uppercase">EXCHANGE DETAILS</h2>
                    </div>
                    <button type="button" @click="closeExchangeModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 cursor-pointer" title="Close">✕</button>
                </div>
            </div>
            
            <div class="p-3.5 sm:p-4 space-y-2.5 font-sans text-xs bg-slate-50/50 flex-1 min-h-0 overflow-y-auto">
                    
                    {{-- Primary Customer & Transaction Details Overview Grid --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-2.5 shadow-2xs">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                            {{-- Customer Name --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Customer</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedExchangeSale?.customer?.name || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedExchangeSale?.customer?.phone" x-text="selectedExchangeSale?.customer?.phone || ''"></span>
                                </div>
                            </div>

                            {{-- Sale Reference --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Sale Ref</span>
                                    <span class="text-slate-800 font-mono text-xs font-bold truncate block mt-0.5" x-text="selectedExchangeSale?.sale_number || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block" x-show="selectedExchangeSale?.booking_date" x-text="selectedExchangeSale?.booking_date || ''"></span>
                                </div>
                            </div>

                            {{-- Project --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Project</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedExchangeSale?.project?.name || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedExchangeSale?.project?.code" x-text="'Code: ' + (selectedExchangeSale?.project?.code || '')"></span>
                                </div>
                            </div>

                            {{-- Unit / Door --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Unit</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedExchangeSale?.unit?.door_no || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedExchangeSale?.unit?.unit_type_name" x-text="(selectedExchangeSale?.unit?.unit_type_name || '') + (selectedExchangeSale?.unit?.floor_name ? ' • ' + selectedExchangeSale.unit.floor_name : '')"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Horizontal Notes / Narration Bar --}}
                    <div class="bg-white border border-slate-200/90 rounded-xl px-4 py-2 text-xs flex items-center gap-2 shadow-2xs">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest shrink-0">NOTES / NARRATION:</span>
                        <span class="text-slate-800 font-semibold truncate" x-text="selectedExchangeSale?.notes || '—'"></span>
                    </div>

                    {{-- Old vs New Unit Comparison Cards (2-Column Grid Format) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold">
                        {{-- Old Unit (Cancelled) --}}
                        <div class="bg-rose-50/40 border border-rose-200/60 rounded-xl p-3.5 space-y-2.5 shadow-2xs">
                            <h4 class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider flex items-center gap-1.5 pb-0.5">
                                <span>✕</span>
                                <span>OLD UNIT (CANCELLED)</span>
                            </h4>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">PROJECT</span>
                                    <span class="text-slate-800 font-semibold text-xs leading-snug block truncate" x-text="selectedExchangeSale?.old_unit?.project_name || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">UNIT DETAILS</span>
                                    <span class="text-slate-800 font-semibold text-xs leading-snug block truncate" x-text="selectedExchangeSale?.old_unit?.unit_details || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">ORIGINAL VALUE</span>
                                    <span class="text-rose-600 font-extrabold font-mono text-xs block" x-text="fmtMoney(selectedExchangeSale?.old_unit?.original_value)"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">OLD CONTRACT PAID</span>
                                    <span class="text-emerald-600 font-extrabold font-mono text-xs block" x-text="fmtMoney(selectedExchangeSale?.old_unit?.paid_amount)"></span>
                                </div>
                            </div>
                        </div>

                        {{-- New Unit (Booked) --}}
                        <div class="bg-emerald-50/40 border border-emerald-200/60 rounded-xl p-3.5 space-y-2.5 shadow-2xs">
                            <h4 class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider flex items-center gap-1.5 pb-0.5">
                                <span>✓</span>
                                <span>NEW UNIT (BOOKED)</span>
                            </h4>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">PROJECT</span>
                                    <span class="text-slate-800 font-semibold text-xs leading-snug block truncate" x-text="selectedExchangeSale?.new_unit?.project_name || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">UNIT DETAILS</span>
                                    <span class="text-slate-800 font-semibold text-xs leading-snug block truncate" x-text="selectedExchangeSale?.new_unit?.unit_details || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">NEW VALUE</span>
                                    <span class="text-emerald-700 font-extrabold font-mono text-xs block" x-text="fmtMoney(selectedExchangeSale?.new_unit?.new_value)"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">NEW CONTRACT PAID</span>
                                    <span class="text-emerald-600 font-extrabold font-mono text-xs block" x-text="fmtMoney(selectedExchangeSale?.new_unit?.transferred_equity)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Financial calculations --}}
                    <div class="bg-white border border-slate-200/90 rounded-xl p-3 grid grid-cols-4 gap-3 divide-x divide-slate-100 shadow-2xs">
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">OLD CONTRACT PAID</p>
                            <p class="text-sm font-extrabold text-rose-600 font-mono mt-0.5" x-text="fmtMoney(selectedExchangeSale?.old_down_payment)"></p>
                        </div>
                        <div class="text-center px-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">NEW CONTRACT PAID</p>
                            <p class="text-sm font-extrabold text-blue-600 font-mono mt-0.5" x-text="fmtMoney(selectedExchangeSale?.new_down_payment)"></p>
                        </div>
                        <div class="text-center px-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">TOTAL PAID</p>
                            <p class="text-sm font-extrabold text-emerald-600 font-mono mt-0.5" x-text="fmtMoney(selectedExchangeSale?.paid_amount)"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">PAYABLE / REFUNDABLE</p>
                            <p class="text-xs font-extrabold mt-1 uppercase" 
                               :class="selectedExchangeSale?.is_payable ? 'text-orange-600' : (selectedExchangeSale?.is_refundable ? 'text-teal-600' : 'text-slate-600')"
                               x-text="selectedExchangeSale?.status_text || '—'"></p>
                        </div>
                    </div>

                    {{-- ARCHIVED LOG SNAPSHOT CARD --}}
                    <template x-if="selectedExchangeSale?.has_snapshot">
                        <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 space-y-3 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>ARCHIVED UNIT EXCHANGE LOG & SOURCABLE SNAPSHOT</span>
                                </h4>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 text-[8px] font-bold uppercase font-mono"
                                      x-text="'ARCHIVED ' + (selectedExchangeSale?.snapshot?.archived_at || '')"></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                {{-- Box 1: Old Unit & Agreement Info --}}
                                <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">
                                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                                        <span>OLD UNIT & AGREEMENT INFO</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">SALE NO</span>
                                            <span class="font-bold text-slate-800 font-mono text-xs block truncate" x-text="selectedExchangeSale?.snapshot?.old_sale_number || '—'"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">UNIT</span>
                                            <span class="font-bold text-slate-800 text-xs block truncate" x-text="selectedExchangeSale?.snapshot?.old_door_no || '—'"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">UNIT TYPE & FLOOR</span>
                                            <span class="font-bold text-slate-800 text-xs block truncate" x-text="selectedExchangeSale?.snapshot?.old_type_and_floor || '—'"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">OLD CONTRACT VALUE</span>
                                            <span class="font-extrabold text-slate-900 font-mono text-xs block truncate" x-text="fmtMoney(selectedExchangeSale?.snapshot?.old_contract_value)"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Box 2: Transferred Financials & Receipts --}}
                                <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">
                                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>TRANSFERRED FINANCIALS & RECEIPTS</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">OLD CONTRACT PAID</span>
                                            <span class="font-extrabold text-rose-600 font-mono text-xs block truncate" x-text="fmtMoney(selectedExchangeSale?.old_down_payment)"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">NEW CONTRACT PAID</span>
                                            <span class="font-extrabold text-blue-600 font-mono text-xs block truncate" x-text="fmtMoney(selectedExchangeSale?.new_down_payment)"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">CARRIED FORWARD</span>
                                            <span class="font-bold text-emerald-600 text-[10px] uppercase font-mono block truncate" x-text="selectedExchangeSale?.snapshot?.carry_forward || 'No'"></span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">PROCESSED BY</span>
                                            <span class="font-bold text-slate-800 text-xs block truncate" x-text="selectedExchangeSale?.snapshot?.processed_by || 'Owner'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Collapsible Archived Receipts & EMI Schedule details --}}
                            <div x-data="{ openTables: false }" class="pt-1">
                                <button type="button" @click="openTables = !openTables"
                                        class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-50/90 via-amber-100/50 to-amber-50/90 hover:from-amber-100 hover:to-amber-100/80 border border-amber-300 text-amber-950 font-bold text-xs uppercase tracking-wider flex items-center justify-between transition-all shadow-2xs cursor-pointer">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#a38c29] animate-pulse" x-show="!openTables"></span>
                                        <span x-text="openTables ? 'Hide Detailed Receipts & EMI Schedule History' : 'Expand Full Receipts & EMI Schedule Audit Log'"></span>
                                    </span>
                                    <div class="flex items-center gap-1.5 text-[#a38c29] font-extrabold text-[11px]">
                                        <span x-text="openTables ? 'COLLAPSE' : 'VIEW DETAILS'"></span>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="openTables ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>

                                <div x-show="openTables" x-collapse x-transition.duration.300ms class="mt-3 space-y-3" x-data="{ activeAuditTab: 'installments' }">
                                    {{-- Sub-tabs --}}
                                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="activeAuditTab = 'installments'"
                                                    :class="activeAuditTab === 'installments' ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer">
                                                <span>EMI Schedule</span>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-mono"
                                                      :class="activeAuditTab === 'installments' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                                      x-text="selectedExchangeSale?.snapshot?.installments?.length || 0"></span>
                                            </button>

                                            <button type="button" @click="activeAuditTab = 'receipts'"
                                                    :class="activeAuditTab === 'receipts' ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer">
                                                <span>Receipts History</span>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-mono"
                                                      :class="activeAuditTab === 'receipts' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                                      x-text="selectedExchangeSale?.snapshot?.receipts?.length || 0"></span>
                                            </button>
                                        </div>

                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest hidden sm:inline-block">Archived Agreement Data</span>
                                    </div>

                                    {{-- Tab 1: EMI Installments Schedule --}}
                                    <div x-show="activeAuditTab === 'installments'" class="border border-slate-200/90 rounded-xl overflow-hidden bg-white shadow-2xs">
                                        <div class="overflow-x-auto max-h-56">
                                            <table class="w-full text-left text-xs border-collapse">
                                                <thead>
                                                    <tr class="bg-slate-100/90 border-b border-slate-200 text-[9px] font-extrabold text-slate-600 uppercase tracking-wider sticky top-0">
                                                        <th class="py-2.5 px-3.5 text-center w-12">#</th>
                                                        <th class="py-2.5 px-3.5">Installment Label</th>
                                                        <th class="py-2.5 px-3.5">Due Date</th>
                                                        <th class="py-2.5 px-3.5 text-center">Status</th>
                                                        <th class="py-2.5 px-3.5 text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    <template x-for="inst in (selectedExchangeSale?.snapshot?.installments || [])" :key="inst.id || inst.installment_no">
                                                        <tr class="hover:bg-amber-50/30 transition-colors">
                                                            <td class="py-2 px-3.5 text-center">
                                                                <span class="w-5 h-5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold font-mono inline-flex items-center justify-center" x-text="inst.installment_no"></span>
                                                            </td>
                                                            <td class="py-2 px-3.5">
                                                                <span class="font-bold text-slate-800" x-text="inst.label || ('EMI ' + inst.installment_no)"></span>
                                                            </td>
                                                            <td class="py-2 px-3.5 font-mono text-slate-600 text-[11px]" x-text="inst.due_date"></td>
                                                            <td class="py-2 px-3.5 text-center">
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider"
                                                                      :class="inst.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (inst.status === 'overdue' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600 border border-slate-200')">
                                                                    <span class="w-1.5 h-1.5 rounded-full"
                                                                          :class="inst.status === 'paid' ? 'bg-emerald-500' : (inst.status === 'overdue' ? 'bg-rose-500' : 'bg-slate-400')"></span>
                                                                    <span x-text="inst.status"></span>
                                                                </span>
                                                            </td>
                                                            <td class="py-2 px-3.5 text-right font-extrabold text-slate-900 font-mono text-xs" x-text="fmtMoney(inst.amount)"></td>
                                                        </tr>
                                                    </template>
                                                    <template x-if="!selectedExchangeSale?.snapshot?.installments || selectedExchangeSale?.snapshot?.installments?.length === 0">
                                                        <tr>
                                                            <td colspan="5" class="py-8 text-center text-slate-400 italic text-xs">
                                                                <div class="flex flex-col items-center justify-center gap-1">
                                                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                    <span>No EMI schedule configured prior to exchange</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Tab 2: Receipts History --}}
                                    <div x-show="activeAuditTab === 'receipts'" class="border border-slate-200/90 rounded-xl overflow-hidden bg-white shadow-2xs">
                                        <div class="overflow-x-auto max-h-56">
                                            <table class="w-full text-left text-xs border-collapse">
                                                <thead>
                                                    <tr class="bg-slate-100/90 border-b border-slate-200 text-[9px] font-extrabold text-slate-600 uppercase tracking-wider sticky top-0">
                                                        <th class="py-2.5 px-3.5 text-center w-12">#</th>
                                                        <th class="py-2.5 px-3.5">Receipt Date</th>
                                                        <th class="py-2.5 px-3.5">Mode</th>
                                                        <th class="py-2.5 px-3.5">Reference No</th>
                                                        <th class="py-2.5 px-3.5">Bank</th>
                                                        <th class="py-2.5 px-3.5 text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    <template x-for="(r, ri) in (selectedExchangeSale?.snapshot?.receipts || [])" :key="r.id || ri">
                                                        <tr class="hover:bg-amber-50/30 transition-colors">
                                                            <td class="py-2 px-3.5 text-center">
                                                                <span class="w-5 h-5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold font-mono inline-flex items-center justify-center" x-text="ri + 1"></span>
                                                            </td>
                                                            <td class="py-2 px-3.5 font-mono text-slate-600 text-[11px]" x-text="r.receipt_date || '—'"></td>
                                                            <td class="py-2 px-3.5 font-bold text-slate-800" x-text="r.payment_mode || '—'"></td>
                                                            <td class="py-2 px-3.5 font-mono text-slate-600 text-[11px]" x-text="r.reference_no || '—'"></td>
                                                            <td class="py-2 px-3.5 text-slate-700" x-text="r.bank_name || '—'"></td>
                                                            <td class="py-2 px-3.5 text-right font-extrabold text-emerald-600 font-mono text-xs" x-text="fmtMoney(r.amount)"></td>
                                                        </tr>
                                                    </template>
                                                    <template x-if="!selectedExchangeSale?.snapshot?.receipts || selectedExchangeSale?.snapshot?.receipts?.length === 0">
                                                        <tr>
                                                            <td colspan="6" class="py-8 text-center text-slate-400 italic text-xs">
                                                                <div class="flex flex-col items-center justify-center gap-1">
                                                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                    <span>No advance receipts recorded prior to exchange</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Sticky Modal Footer (Always visible outside scrolling body) --}}
                <div class="px-5 py-2.5 bg-white border-t border-slate-200/80 flex items-center justify-end shrink-0 shadow-2xs">
                    <button type="button" @click="closeExchangeModal()"
                            class="px-4 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase flex items-center gap-1.5 cursor-pointer shadow-2xs">
                        <span>✕</span>
                        <span>CLOSE</span>
                    </button>
                </div>
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
