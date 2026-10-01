<x-erp-layout title="Contractor Statement" headerTitle="Business Reports Center">

{{-- ExcelJS Corporate Export Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
/* CRITICAL: @page must be defined at the stylesheet root for Chromium/WebKit to honor landscape mode */
@page {
    size: landscape;
    margin: 6mm 8mm 6mm 8mm;
}

@media screen {
    .print-exec-header,
    .reports-banner-container.print-exec-banner,
    .print-table-header-box {
        display: none !important;
    }
    .contractor-statement-container {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    .contractor-table-card {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }
    .contractor-table-scroll {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .dues-comparison-card {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }
    #supplierPayablesChart {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }
}

@media print {
    @page {
        size: landscape;
        margin: 6mm 8mm 6mm 8mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 7.5pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 1. Complete Web Clutter & Sidebar Removal */
    .web-only-workspace,
    .print\:hidden,
    [class*="print:hidden"],
    header, nav, aside, footer, button, select, input, 
    .custom-scrollbar::-webkit-scrollbar, [class*="nav"], [class*="sidebar"] {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        max-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        overflow: hidden !important;
    }

    /* 2. Reset ERP Layout Parent Flex Containers & Left Sidebar Padding */
    div.lg\:pl-72, [class*="lg:pl-72"], [class*="pl-72"] {
        padding-left: 0 !important;
        margin-left: 0 !important;
        width: 100% !important;
        min-height: auto !important;
        height: auto !important;
        display: block !important;
        flex: none !important;
    }
    main, .flex-1.p-6, main.flex-1 {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: auto !important;
        height: auto !important;
        display: block !important;
        flex: none !important;
    }
    .max-w-\[1800px\], .max-w-3xl {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* 3. Executive Corporate Letterhead Header (Exact Reference Standard - Picture 2) */
    .print-exec-header {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 6px !important;
        padding-bottom: 5px !important;
        border-bottom: 2px solid #a38c29 !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        background: transparent !important;
    }

    /* 4. Top Banner Print Styling (Matching Sales Cancellation Report Standard) */
    .reports-banner-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        padding: 10px 14px !important;
        border: 1px solid #fde68a !important;
        border-radius: 12px !important;
        background: #fffdf5 !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        box-sizing: border-box !important;
    }

    /* 5. Contractor Dues vs Payments Comparison Card (Visible in Print & Screen) */
    .dues-comparison-card {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        margin-top: 0 !important;
        margin-bottom: 6px !important;
        padding: 5px 10px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .dues-comparison-card #supplierPayablesChart {
        height: 110px !important;
        max-height: 110px !important;
        overflow: hidden !important;
    }
    .dues-comparison-card .apexcharts-canvas {
        margin: 0 auto !important;
        height: 110px !important;
        overflow: hidden !important;
    }
    .dues-comparison-card .apexcharts-canvas svg {
        height: 110px !important;
    }
    .apexcharts-legend,
    .dues-comparison-card .apexcharts-legend {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        pointer-events: none !important;
    }

    /* 6. Table Container: Completely Transparent Block Flow */
    .contractor-table-card {
        display: block !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        page-break-before: auto !important;
        break-before: auto !important;
    }

    .print-table-header-box {
        display: block !important;
        padding: 4px 8px !important;
        margin-top: 0 !important;
        margin-bottom: 4px !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 5px !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        box-sizing: border-box !important;
    }

    .overflow-x-auto {
        display: block !important;
        overflow: visible !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }

    /* 7. Table Formatting: Clean Modern Corporate Styling */
    table#reportsTable {
        display: table !important;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7pt !important;
        margin: 0 !important;
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table#reportsTable colgroup {
        display: table-column-group !important;
    }
    table#reportsTable thead {
        display: table-header-group !important;
    }
    table#reportsTable thead tr {
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }
    table#reportsTable thead th {
        background-color: #a38c29 !important;
        color: #ffffff !important;
        font-size: 6.8pt !important;
        font-weight: 800 !important;
        padding: 6px 6px !important;
        border: none !important;
        border-right: 0.5pt solid rgba(255,255,255,0.35) !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        letter-spacing: 0.25px !important;
        text-transform: uppercase !important;
        box-sizing: border-box !important;
    }
    table#reportsTable tbody {
        display: table-row-group !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }
    table#reportsTable tbody tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table#reportsTable tbody td {
        padding: 5px 6px !important;
        font-size: 6.8pt !important;
        border: none !important;
        border-bottom: 0.5pt solid #e2e8f0 !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
        line-height: 1.3 !important;
    }
    table#reportsTable tbody tr:nth-child(even) td {
        background-color: #fbfaf6 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    table#reportsTable tfoot {
        display: table-footer-group !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    table#reportsTable tfoot td {
        padding: 6px 6px !important;
        font-size: 7pt !important;
        background-color: #f8fafc !important;
        border-top: 1.5pt solid #a38c29 !important;
        border-bottom: 1.5pt solid #a38c29 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: 800 !important;
    }

    /* Reset font size on child elements inside table */
    table#reportsTable td div, 
    table#reportsTable td span {
        font-size: inherit;
    }
    table#reportsTable td .text-\[10px\], 
    table#reportsTable td .text-\[9px\] {
        font-size: 6pt !important;
    }
    table#reportsTable td .rounded-full, 
    table#reportsTable td .rounded {
        font-size: 5.5pt !important;
        padding: 1.5px 4px !important;
        line-height: 1.1 !important;
    }

    /* Allow standard text cells to wrap nicely */
    .whitespace-nowrap:not(.amount-cell):not(th) {
        white-space: normal !important;
    }
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }

    /* Strict formatting for monetary cells */
    .amount-cell, table#reportsTable td.amount-cell {
        white-space: nowrap !important;
        font-size: 7pt !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        text-align: right !important;
        letter-spacing: -0.2px !important;
        padding-right: 6px !important;
    }
}
</style>

<div class="contractor-statement-container w-full max-w-[1800px] mx-auto space-y-4 min-w-0" x-data="contractorStatementApp()">

    @php
        $selectedProjectId = request('project_id');
        if($selectedProjectId && $selectedProjectId !== 'all') {
            $matchedProject = $projects->firstWhere('id', $selectedProjectId);
            $selectedProjectName = $matchedProject ? $matchedProject->name : 'Selected Project';
        } elseif($selectedProjectId === 'all') {
            $selectedProjectName = 'All Projects';
        } elseif($projects->isNotEmpty()) {
            $selectedProjectName = $projects->first()->name;
        } else {
            $selectedProjectName = 'All Projects';
        }

        $contractorsList = $suppliers->map(function($s) {
            return [
                'id' => (string)($s->filter_value ?? $s->id),
                'name' => $s->name,
                'phone' => $s->phone ?? '',
                'gstin' => $s->gstin ?? '',
            ];
        })->values();

        $activeContractorIds = $contractorIds ?? [];
        if (empty($activeContractorIds) && request('contractor_id')) {
            $raw = request('contractor_id');
            $activeContractorIds = is_array($raw) ? $raw : explode(',', $raw);
        }
        $activeContractorIds = array_values(array_filter(array_map('strval', $activeContractorIds)));

        $activeContractorObjs = $suppliers->filter(function($s) use ($activeContractorIds) {
            $idStr = (string)($s->filter_value ?? $s->id);
            return in_array($idStr, $activeContractorIds) || in_array((string)$s->id, $activeContractorIds);
        });

        if ($activeContractorObjs->count() === 1) {
            $activeContractorName = $activeContractorObjs->first()->name;
        } elseif ($activeContractorObjs->count() > 1) {
            $activeContractorName = $activeContractorObjs->count() . ' Contractors Selected';
        } else {
            $activeContractorName = 'All Contractors';
        }

        $statusColorMap = [
            'cleared' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'partially_paid' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'pending' => 'bg-orange-50 text-orange-700 border border-orange-200',
            'submitted' => 'bg-purple-50 text-purple-700 border border-purple-200',
        ];

        // Prepare full dataset for reactive Alpine live filtering (zero page reloads)
        $rawBillsList = $allSupplierContractorEntries->map(function($row) use ($statusColorMap) {
            $st = $row->status ?? 'pending';
            return [
                'id' => $row->id,
                'contractor_id' => (string)($row->contractor_id ?? ''),
                'contractor_name' => $row->contractor_name ?: ($row->contractor?->name ?? 'General Contractor'),
                'contractor_phone' => $row->contractor?->phone ?? '',
                'ra_bill_number' => $row->ra_bill_number ?: '-',
                'verified_date' => $row->verified_date ? \Carbon\Carbon::parse($row->verified_date)->format('d-M-Y') : '',
                'submit_date' => $row->submit_date ? \Carbon\Carbon::parse($row->submit_date)->format('d-M-Y') : '',
                'project_id' => (string)($row->project_id ?? ''),
                'project_name' => $row->project?->name ?? 'General Project',
                'unit_label' => ($row->unit_name ?: $row->unit?->door_no) ? ('Unit: ' . ($row->unit_name ?: $row->unit?->door_no)) : '',
                'net_approved_amount' => (float)$row->net_approved_amount,
                'paid_amount' => (float)$row->paid_amount,
                'balance_amount' => (float)$row->balance_amount,
                'status' => $st,
                'status_badge' => $statusColorMap[$st] ?? 'bg-slate-100 text-slate-600 border border-slate-200',
                'status_label' => strtoupper(str_replace('_', ' ', $st)),
            ];
        })->values();
    @endphp

    <!-- ── 1. EXECUTIVE PRINT HEADER (EXACT SALES CANCELLATION REPORT STANDARD) ── -->
    <div class="hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Receivable & Risk Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">CONTRACTOR STATEMENT & RUNNING ACCOUNT (RA) BILL PAYABLES REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">As On Date:</span> <span class="font-mono font-bold text-slate-800">Current Live Date</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]"><span x-text="filteredBills.length">{{ $totalBillsCount ?? $supplierContractorEntries->total() }}</span> Records</span></div>
            </div>
        </div>
    </div>

    {{-- Top Header & Action Banner --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 relative z-10 print:mb-4">
        <div>
            <h1 class="text-lg md:text-xl font-black text-slate-900 uppercase tracking-tight">CONTRACTOR DUES & STATEMENT PAYABLES</h1>
            <p class="text-[11px] md:text-xs text-slate-500 mt-1 font-medium">Verified audit trail of RA bills, approved work payables, payments, and balance liabilities.</p>
        </div>
        <div class="shrink-0">
            <div class="inline-flex items-center px-4 py-1.5 md:py-2 bg-white border border-slate-200 rounded-full text-[10px] md:text-xs font-bold text-slate-700 shadow-sm">
                <span class="text-slate-400 mr-1.5 font-semibold">Project:</span> Tabasco Hindustan Infra Developers Pvt. Ltd
            </div>
        </div>
    </div>

    {{-- Independent KPI Stat Tiles Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 print:grid-cols-4 print:gap-3">
        
        {{-- Card 1: Net Approved Dues --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Net Approved Dues</span>
                </div>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300 font-mono" x-text="'₹' + formatCurrency(filteredTotals.dues)">₹{{ number_format($totalApprovedDues ?? 0, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total verified work billed</p>
            </div>
        </div>

        {{-- Card 2: Total Paid Amount --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Paid Amount</span>
                </div>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300" x-text="'₹' + formatCurrency(filteredTotals.paids)">₹{{ number_format($totalPaidAmount ?? 0, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Disbursed to contractors</p>
            </div>
        </div>

        {{-- Card 3: Balance Liability --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Balance Liability</span>
                </div>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300" x-text="'₹' + formatCurrency(filteredTotals.balances)">₹{{ number_format($totalBalanceDue ?? 0, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Remaining payable liability</p>
            </div>
        </div>

        {{-- Card 4: Total RA Bills --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-slate-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-slate-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(71,85,105,0.15)] cursor-pointer">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-600 border border-slate-200/60 transition-all duration-300 group-hover:bg-slate-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total RA Bills</span>
                </div>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-slate-600 transition-colors duration-300 font-mono" x-text="filteredBills.length">{{ $totalBillsCount ?? $supplierContractorEntries->total() }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Recorded billing entries</p>
            </div>
        </div>
    </div>

    <!-- ── 4. CONTRACTOR DUES VS PAYMENTS COMPARISON (VISIBLE ON SCREEN AND IN PDF PRINT) ── -->
    <div class="dues-comparison-card bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm">
        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2 mb-2">
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">Contractor Dues vs Payments Comparison</h4>
            </div>
            <div class="flex items-center gap-4 text-[9.5px] font-bold uppercase tracking-wider">
                <span class="flex items-center gap-1.5 text-orange-600"><span class="w-3 h-2 rounded-xs bg-[#f97316] inline-block"></span>Net Approved Dues</span>
                <span class="flex items-center gap-1.5 text-emerald-600"><span class="w-3 h-2 rounded-xs bg-[#10b981] inline-block"></span>Paid Amount</span>
            </div>
        </div>
        <div id="supplierPayablesChart" class="w-full"></div>
    </div>

    {{-- ── 5. FILTER, PRINT & EXPORT BAR (MATCHING EMI-COLLECTIONS 1-ROW STANDARD) ── --}}
    <div class="print:hidden bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
        <div class="flex items-center gap-2.5 shrink-0 w-full flex-wrap sm:flex-nowrap">
            {{-- Contractor Multi-Select Dropdown Filter --}}
            <div class="flex-1 min-w-[280px] relative" @click.outside="openContractor = false">
                <div class="relative w-full">
                    <div role="button" tabindex="0"
                         @click="openContractor = !openContractor; if (openContractor) { $nextTick(() => $refs.contractorSearchInput?.focus({ preventScroll: true })); }"
                         :class="openContractor ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                         class="w-full min-h-[42px] px-3.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs">
                        
                        {{-- When contractors are selected --}}
                        <template x-if="selectedContractors.length > 0">
                            <div class="flex flex-wrap items-center gap-1.5 overflow-hidden min-w-0 flex-1 py-0.5">
                                <template x-if="selectedContractors.length <= 2">
                                    <template x-for="c in selectedContractors" :key="c.id">
                                        <span class="inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded-lg bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-[11px] font-bold">
                                            <span x-text="c.name" class="whitespace-nowrap max-w-[150px] truncate"></span>
                                            <button type="button" @click.stop="removeContractor(c.id)" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors" title="Remove">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </span>
                                    </template>
                                </template>
                                <template x-if="selectedContractors.length > 2">
                                    <span class="inline-flex items-center gap-1.5 pl-2.5 pr-1.5 py-1 rounded-lg bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-[11px] font-bold">
                                        <span x-text="selectedContractors.length + ' Contractors Selected'"></span>
                                        <button type="button" @click.stop="clearContractor()" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors" title="Clear all">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </template>

                        {{-- When no contractor selected --}}
                        <template x-if="selectedContractors.length === 0">
                            <div class="flex items-center gap-2 text-slate-500 font-bold px-1">
                                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Filter by Contractors (Multi-Select)</span>
                            </div>
                        </template>

                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="selectedContractors.length > 0">
                                <span @click.stop="clearContractor()" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear all selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="openContractor ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Dropdown popover with search & multi-select checkboxes --}}
                    <div x-show="openContractor"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute left-0 right-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-96 flex flex-col z-[100]"
                         style="display: none;">
                        
                        {{-- Search Input Box --}}
                        <div class="p-2.5 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text"
                                       x-model="contractorSearch"
                                       x-ref="contractorSearchInput"
                                       placeholder="Search contractor name or phone number..."
                                       @keydown.escape="openContractor = false"
                                       class="w-full pl-8 pr-7 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                <template x-if="contractorSearch">
                                    <button type="button" @click="contractorSearch = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>

                        {{-- Quick Action Bar: Select All / Deselect All --}}
                        <div class="flex items-center justify-between px-3 py-1.5 bg-slate-50/60 border-b border-slate-100 text-[11px] font-bold">
                            <button type="button" @click="selectAllContractors()" class="text-[#8a7522] hover:text-[#6d5b18] hover:underline flex items-center gap-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-[#8a7522]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Select All</span>
                            </button>
                            <button type="button" @click="deselectAllContractors()" class="text-slate-500 hover:text-rose-600 flex items-center gap-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Clear Selection</span>
                            </button>
                        </div>

                        {{-- Scrollable Contractor Items with Checkboxes --}}
                        <div class="overflow-y-auto flex-1 p-1.5 space-y-1 custom-scrollbar">
                            <template x-for="c in filteredContractors" :key="c.id">
                                <div @click="toggleContractor(c.id)"
                                     :class="isContractorSelected(c.id) ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                     class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2.5 group cursor-pointer font-medium select-none">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        {{-- Checkbox --}}
                                        <div :class="isContractorSelected(c.id) ? 'bg-[#a38c29] border-[#a38c29] text-white' : 'bg-white border-slate-300 group-hover:border-[#a38c29]'"
                                             class="w-4 h-4 rounded border flex items-center justify-center shrink-0 transition-colors">
                                            <template x-if="isContractorSelected(c.id)">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-xs truncate leading-snug" :class="isContractorSelected(c.id) ? 'text-[#8a7522]' : 'text-slate-800'" x-text="c.name"></p>
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 font-mono mt-0.5" x-show="c.phone || c.gstin">
                                                <span class="flex items-center gap-1" x-show="c.phone">
                                                    <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                    <span x-text="c.phone"></span>
                                                </span>
                                                <span x-show="c.gstin" class="text-[9px] text-slate-400 font-mono" x-text="'GST: ' + c.gstin"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="isContractorSelected(c.id)">
                                        <span class="text-[10px] font-black uppercase text-[#8a7522] bg-[#a38c29]/15 px-1.5 py-0.5 rounded shrink-0">Selected</span>
                                    </template>
                                </div>
                            </template>
                            <div x-show="filteredContractors.length === 0" class="p-4 text-center text-xs text-slate-400 italic">
                                No contractor found matching "<span x-text="contractorSearch"></span>".
                            </div>
                        </div>

                        {{-- Sticky Footer with Live Filter Status & Done Button --}}
                        <div class="p-2.5 bg-slate-50 border-t border-slate-200/90 flex items-center justify-between gap-2 sticky bottom-0 z-10">
                            <div class="text-[11px] font-bold text-slate-500 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-slate-900 font-black" x-text="selectedContractorIds.length"></span>
                                <span x-text="selectedContractorIds.length === 1 ? 'Contractor Active' : 'Contractors Active'"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <template x-if="selectedContractorIds.length > 0">
                                    <button type="button" @click="clearContractor()" class="px-2.5 py-1 text-[11px] font-bold text-slate-500 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                                        Clear All
                                    </button>
                                </template>
                                <button type="button" @click="openContractor = false" class="px-4 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black rounded-xl transition shadow-xs flex items-center gap-1 cursor-pointer">
                                    <span>Done</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. EXPORT EXCEL Button (Green) --}}
            <button type="button" @click="exportContractorExcel()" 
                    class="h-[42px] px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98] whitespace-nowrap shrink-0"
                    title="Export Contractor Statement to Excel">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>EXPORT EXCEL</span>
            </button>

            {{-- 3. EXPORT PDF / PRINT Button (Red) --}}
            <button type="button" @click="printReport()" 
                    class="h-[42px] px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98] whitespace-nowrap shrink-0"
                    title="Export / Print Contractor Statement PDF">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>EXPORT PDF</span>
            </button>
        </div>
    </div>

    <!-- ── 6. DETAILED LEDGER TABLE CARD (MATCHING EMI-COLLECTIONS CORPORATE STANDARD) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden contractor-table-card">
        {{-- Section Sub-Header Box (Visible in On-Screen Only) --}}
        <div class="print:hidden px-6 py-4 bg-amber-50/40 border-b border-slate-200/90 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">
                    CONTRACTOR STATEMENT & RUNNING ACCOUNT (RA) BILL PAYABLES LEDGER
                </h4>
                <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Consolidated audit ledger of verified contractor RA bills, approved payables, disbursements & liabilities.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3.5 py-1.5 bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs">
                    Active Project: {{ $selectedProjectName }}
                </span>
                @if($activeContractorObjs->count() === 1)
                    <span class="px-3.5 py-1.5 bg-amber-100/70 text-[#8a7522] border border-amber-200 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs">
                        Contractor: {{ $activeContractorObjs->first()->name }}
                    </span>
                @elseif($activeContractorObjs->count() > 1)
                    <span class="px-3.5 py-1.5 bg-amber-100/70 text-[#8a7522] border border-amber-200 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs">
                        Contractors: {{ $activeContractorObjs->count() }} Selected
                    </span>
                @endif
            </div>
        </div>

        {{-- Print Preview Specific Minimal Header (Print Only) --}}
        <div class="print-table-header-box mb-2 hidden print:block">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-[8.5pt] font-black uppercase tracking-wider text-slate-800 leading-tight">Verified RA Bills & Payables Detailed Ledger</h4>
                    <p class="text-[7pt] text-slate-500 font-medium">Showing {{ $totalBillsCount ?? $supplierContractorEntries->total() }} transactions for {{ $activeContractorName }}</p>
                </div>
                <div class="flex items-center gap-2 text-[7pt] font-bold uppercase tracking-wider">
                    <span class="px-2 py-0.5 rounded bg-amber-100/80 text-[#8a7522] border border-amber-200">Active Project: {{ $selectedProjectName }}</span>
                    @if($activeContractorObjs->count() === 1)
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">Contractor: {{ $activeContractorObjs->first()->name }}</span>
                    @elseif($activeContractorObjs->count() > 1)
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">Contractors: {{ $activeContractorObjs->count() }} Selected</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto custom-scrollbar contractor-table-scroll">
            <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-2.5 py-3.5 text-center text-white font-extrabold whitespace-nowrap w-10 border-r border-white/20">#</th>
                        <th class="px-3.5 py-3.5 text-left text-white font-extrabold whitespace-nowrap min-w-[150px] border-r border-white/20">Contractor Name</th>
                        <th class="px-3 py-3.5 text-left text-white font-extrabold whitespace-nowrap min-w-[115px] border-r border-white/20">RA Bill # / Ref</th>
                        <th class="px-3.5 py-3.5 text-left text-white font-extrabold whitespace-nowrap min-w-[170px] border-r border-white/20">Project / Work Location</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold whitespace-nowrap min-w-[120px] border-r border-white/20">Net Approved Dues</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold whitespace-nowrap min-w-[110px] border-r border-white/20">Paid Amount</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold whitespace-nowrap min-w-[110px] border-r border-white/20">Balance Due</th>
                        <th class="px-2 py-3.5 text-center text-white font-extrabold whitespace-nowrap w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                    <template x-for="(row, index) in filteredBills" :key="row.id">
                        <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium">
                            <td class="px-2.5 py-3 text-center text-slate-400 font-bold font-mono text-[11px] border-r border-slate-100" x-text="index + 1"></td>
                            <td class="px-3.5 py-3 text-left font-sans border-r border-slate-100">
                                <div class="font-extrabold text-slate-900 text-xs" x-text="row.contractor_name"></div>
                                <template x-if="row.contractor_phone">
                                    <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                        <svg class="w-2.5 h-2.5 text-slate-400 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span x-text="row.contractor_phone"></span>
                                    </div>
                                </template>
                            </td>
                            <td class="px-3.5 py-3 text-left text-indigo-750 font-bold border-r border-slate-100">
                                <div class="font-mono" x-text="row.ra_bill_number || '-'"></div>
                                <template x-if="row.verified_date">
                                    <div class="text-[9.5px] text-slate-400 font-normal" x-text="'Verified: ' + row.verified_date"></div>
                                </template>
                                <template x-if="!row.verified_date && row.submit_date">
                                    <div class="text-[9.5px] text-slate-400 font-normal" x-text="'Submitted: ' + row.submit_date"></div>
                                </template>
                            </td>
                            <td class="px-3.5 py-3 text-left font-sans border-r border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 print:hidden">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 leading-tight text-xs" x-text="row.project_name"></div>
                                        <template x-if="row.unit_label">
                                            <div class="inline-flex items-center gap-1 mt-0.5 px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-[9.5px] font-semibold text-slate-600" x-text="row.unit_label"></div>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3.5 py-3 text-right font-mono font-bold text-slate-900 whitespace-nowrap border-r border-slate-100" x-text="'₹' + formatCurrency(row.net_approved_amount)"></td>
                            <td class="px-3.5 py-3 text-right font-mono font-bold text-emerald-700 whitespace-nowrap border-r border-slate-100" x-text="'₹' + formatCurrency(row.paid_amount)"></td>
                            <td class="px-3.5 py-3 text-right font-mono font-bold text-rose-600 whitespace-nowrap border-r border-slate-100" x-text="'₹' + formatCurrency(row.balance_amount)"></td>
                            <td class="px-2 py-3 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider inline-block" :class="row.status_badge" x-text="row.status_label"></span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredBills.length === 0">
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400 italic">No contractor statements or RA bills found matching criteria.</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 text-slate-900 font-bold border-t-2 border-[#a38c29] text-[11px]">
                        <td colspan="4" class="px-3.5 py-3 text-left font-black uppercase tracking-wider border-r border-slate-200">
                            Total Summary (<span x-text="filteredBills.length"></span> Bills)
                        </td>
                        <td class="px-3.5 py-3 text-right font-mono font-black text-slate-900 whitespace-nowrap border-r border-slate-200" x-text="'₹' + formatCurrency(filteredTotals.dues)">
                            ₹{{ number_format($totalApprovedDues ?? 0, 2) }}
                        </td>
                        <td class="px-3.5 py-3 text-right font-mono font-black text-emerald-700 whitespace-nowrap border-r border-slate-200" x-text="'₹' + formatCurrency(filteredTotals.paids)">
                            ₹{{ number_format($totalPaidAmount ?? 0, 2) }}
                        </td>
                        <td class="px-3.5 py-3 text-right font-mono font-black text-rose-600 whitespace-nowrap border-r border-slate-200" x-text="'₹' + formatCurrency(filteredTotals.balances)">
                            ₹{{ number_format($totalBalanceDue ?? 0, 2) }}
                        </td>
                        <td class="px-2 py-3 text-center font-bold text-slate-500 uppercase text-[9.5px]">
                            <span x-text="filteredBills.length"></span> Records
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-2xl print:hidden">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                Showing <span class="text-slate-900 font-black" x-text="filteredBills.length > 0 ? 1 : 0"></span> to 
                <span class="text-slate-900 font-black" x-text="filteredBills.length"></span> of 
                <span class="text-slate-900 font-black" x-text="filteredBills.length"></span> Contractor Statements
            </div>
        </div>
    </div>
</div>

{{-- Excel Export Script & Alpine Controller --}}
<script>
function contractorStatementApp() {
    return {
        allBills: {{ Js::from($rawBillsList) }},
        contractors: {{ Js::from($contractorsList) }},
        selectedProjectName: '{{ addslashes($selectedProjectName) }}',
        serverChartData: {{ Js::from($supplierChartData ?? ['labels' => [], 'dues' => [], 'paids' => []]) }},
        filters: {
            contractor_id: {{ Js::from(is_array(request('contractor_id')) ? implode(',', request('contractor_id')) : (string)request('contractor_id', '')) }},
            status: '{{ request('status', '') }}',
            search: '{{ request('search', '') }}',
        },
        selectedContractorIds: {{ Js::from($activeContractorIds) }},
        openContractor: false,
        contractorSearch: '',
        chartInstance: null,

        init() {
            this.$nextTick(() => {
                this.initChart();
            });
        },

        formatCurrency(num) {
            return Number(num || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        get selectedContractors() {
            return this.contractors.filter(c => this.selectedContractorIds.includes(String(c.id)));
        },

        isContractorSelected(id) {
            return this.selectedContractorIds.includes(String(id));
        },

        isBillMatchingSelected(b) {
            if (this.selectedContractorIds.length === 0) return true;
            const bName = String(b.contractor_name || '').toLowerCase().trim();
            const bId = String(b.contractor_id || '').trim();

            return this.selectedContractorIds.some(cId => {
                const strCId = String(cId).toLowerCase().trim();
                const matchingContractor = this.contractors.find(c => String(c.id).toLowerCase().trim() === strCId);
                const targetName = matchingContractor ? matchingContractor.name.toLowerCase().trim() : strCId;

                // 1. Primary match: bill contractor name matches target contractor name
                if (bName && targetName && (bName === targetName || bName.includes(targetName) || targetName.includes(bName))) {
                    return true;
                }

                // 2. Fallback if bill has no contractor_name: match by contractor_id
                if (!bName && bId && bId === String(matchingContractor?.id || cId).trim()) {
                    return true;
                }

                return false;
            });
        },

        get filteredBills() {
            let list = this.allBills;
            if (this.selectedContractorIds.length > 0) {
                list = list.filter(b => this.isBillMatchingSelected(b));
            }
            if (this.filters.status) {
                const st = this.filters.status.toLowerCase();
                list = list.filter(b => (b.status || '').toLowerCase() === st);
            }
            return list;
        },

        get filteredTotals() {
            let dues = 0, paids = 0, balances = 0;
            this.filteredBills.forEach(b => {
                dues += Number(b.net_approved_amount) || 0;
                paids += Number(b.paid_amount) || 0;
                balances += Number(b.balance_amount) || 0;
            });
            return { dues, paids, balances };
        },

        toggleContractor(id) {
            const strId = String(id);
            const idx = this.selectedContractorIds.indexOf(strId);
            if (idx > -1) {
                this.selectedContractorIds.splice(idx, 1);
            } else {
                this.selectedContractorIds.push(strId);
            }
            this.updateChart();
            this.syncUrl();
        },

        selectAllContractors() {
            this.selectedContractorIds = this.contractors.map(c => String(c.id));
            this.updateChart();
            this.syncUrl();
        },

        deselectAllContractors() {
            this.selectedContractorIds = [];
            this.updateChart();
            this.syncUrl();
        },

        removeContractor(id) {
            this.selectedContractorIds = this.selectedContractorIds.filter(x => String(x) !== String(id));
            this.updateChart();
            this.syncUrl();
        },

        clearContractor() {
            this.selectedContractorIds = [];
            this.updateChart();
            this.syncUrl();
        },

        syncUrl() {
            const params = new URLSearchParams(window.location.search);
            params.delete('contractor_id');
            params.delete('contractor_id[]');
            this.selectedContractorIds.forEach(id => {
                params.append('contractor_id[]', id);
            });
            params.delete('page');
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState(null, '', newUrl);
        },

        get filteredContractors() {
            if (!this.contractorSearch.trim()) return this.contractors;
            const q = this.contractorSearch.toLowerCase();
            return this.contractors.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.phone && c.phone.includes(q)) ||
                (c.gstin && c.gstin.toLowerCase().includes(q))
            );
        },

        getSelectedContractorName() {
            if (this.selectedContractorIds.length === 1) {
                const c = this.contractors.find(x => String(x.id) === String(this.selectedContractorIds[0]));
                if (c) return c.name;
            } else if (this.selectedContractorIds.length > 1) {
                return `${this.selectedContractorIds.length} Contractors Selected`;
            }
            if (this.filters.search) return 'Search: ' + this.filters.search;
            return '-- All Contractors / Firms --';
        },

        get activeContractorName() {
            if (this.selectedContractorIds.length === 1) {
                const c = this.contractors.find(x => String(x.id) === String(this.selectedContractorIds[0]));
                return c ? c.name : this.selectedContractorIds[0];
            } else if (this.selectedContractorIds.length > 1) {
                return `${this.selectedContractorIds.length} Contractors Selected`;
            }
            if (this.filters.search) return 'Search: ' + this.filters.search;
            return 'All Contractors';
        },

        resetFilters() {
            this.selectedContractorIds = [];
            this.filters.status = '';
            this.filters.search = '';
            this.updateChart();
            this.syncUrl();
        },

        handleSearchEnter() {
            if (this.filteredContractors.length > 0) {
                this.toggleContractor(this.filteredContractors[0].id);
            }
        },

        getChartData() {
            const summary = {};
            this.filteredBills.forEach(b => {
                const name = b.contractor_name || 'General Contractor';
                if (!summary[name]) {
                    summary[name] = { dues: 0, paids: 0 };
                }
                summary[name].dues += Number(b.net_approved_amount) || 0;
                summary[name].paids += Number(b.paid_amount) || 0;
            });

            const labels = Object.keys(summary);
            const dues = labels.map(k => summary[k].dues);
            const paids = labels.map(k => summary[k].paids);

            return { labels, dues, paids };
        },

        initChart() {
            const el = document.querySelector("#supplierPayablesChart");
            if (!el || typeof ApexCharts === 'undefined') return;

            const data = this.getChartData();

            const labels = (data.labels && data.labels.length > 0) ? data.labels : ['No Contractor Data'];
            const dues = (data.dues && data.dues.length > 0) ? data.dues : [0];
            const paids = (data.paids && data.paids.length > 0) ? data.paids : [0];

            this.chartInstance = new ApexCharts(el, {
                series: [
                    { name: 'Net Approved Dues', data: dues },
                    { name: 'Paid Amount', data: paids }
                ],
                chart: { 
                    type: 'bar', 
                    height: 200, 
                    toolbar: { show: false }, 
                    fontFamily: 'Inter, sans-serif',
                    animations: { enabled: true, easing: 'easeinout', speed: 500 }
                },
                legend: {
                    show: false
                },
                colors: ['#f97316', '#10b981'],
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                plotOptions: { 
                    bar: { 
                        horizontal: false,
                        columnWidth: labels.length <= 2 ? '22%' : (labels.length <= 5 ? '34%' : '50%'), 
                        borderRadius: 6,
                        borderRadiusApplication: 'end'
                    } 
                },
                xaxis: { 
                    categories: labels,
                    labels: {
                        style: { colors: '#64748b', fontSize: '11px', fontWeight: 700 }
                    },
                    axisBorder: { show: true, color: '#e2e8f0' }
                },
                yaxis: { 
                    labels: { 
                        style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 },
                        formatter: (v) => '₹' + (v >= 10000000 ? (v/10000000).toFixed(2)+'Cr' : (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : (v || 0)))) 
                    } 
                },
                grid: { 
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: 'light',
                    y: { formatter: (val) => '₹' + (Number(val) || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 }) }
                }
            });
            this.chartInstance.render();
        },

        updateChart() {
            if (!this.chartInstance) return;
            const data = this.getChartData();
            this.chartInstance.updateOptions({
                xaxis: { categories: data.labels.length ? data.labels : ['No Data'] },
                plotOptions: {
                    bar: {
                        columnWidth: data.labels.length <= 2 ? '22%' : (data.labels.length <= 5 ? '34%' : '50%')
                    }
                }
            });
            this.chartInstance.updateSeries([
                { name: 'Net Approved Dues', data: data.dues },
                { name: 'Paid Amount', data: data.paids }
            ]);
        },

        printReport() {
            window.print();
        },

        async exportContractorExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS is loading, please try again in a moment.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = this.selectedProjectName || 'TABASCO ERP';
                workbook.lastModifiedBy = this.selectedProjectName || 'TABASCO ERP';
                workbook.created = new Date();
                workbook.modified = new Date();

                const worksheet = workbook.addWorksheet('Contractor Statement');
                worksheet.views = [{ showGridLines: true }];

                const totalCols = 9;
                worksheet.columns = [
                    { key: 'sl', width: 10 },              // Col 1: SL NO
                    { key: 'date', width: 16 },            // Col 2: Date
                    { key: 'contractor', width: 34 },      // Col 3: Contractor Name
                    { key: 'bill_no', width: 22 },         // Col 4: RA Bill # / Ref
                    { key: 'project', width: 36 },         // Col 5: Project / Work Location
                    { key: 'approved_amount', width: 22 }, // Col 6: Net Approved Dues (₹)
                    { key: 'paid_amount', width: 22 },     // Col 7: Paid Amount (₹)
                    { key: 'balance_amount', width: 22 },  // Col 8: Balance Due (₹)
                    { key: 'status', width: 18 },          // Col 9: Status
                ];

                // 1. Title Banner Row 1 (#2C3E50)
                const row1 = worksheet.getRow(1);
                row1.height = 36;
                worksheet.mergeCells('A1:I1');
                const titleCell = worksheet.getCell('A1');
                const projTitle = (this.selectedProjectName || 'TABASCO ERP').toUpperCase();
                titleCell.value = `${projTitle} - CONTRACTOR STATEMENT & PAYABLES REPORT`;
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row1.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };
                }

                // 2. Subtitle Row 2 (#007398)
                const row2 = worksheet.getRow(2);
                row2.height = 26;
                worksheet.mergeCells('A2:I2');
                const subCell = worksheet.getCell('A2');
                const activeName = this.activeContractorName || 'All Contractors';
                const statusName = this.filters.status ? this.filters.status.toUpperCase() : 'All Statuses';
                subCell.value = `Contractor: ${activeName} | Project: ${this.selectedProjectName} | Status: ${statusName} | Generated: {{ now()->format('d/m/Y') }}`;
                subCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                subCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row2.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };
                }

                // 3. Section Banner Row 3 (#006039)
                const row3 = worksheet.getRow(3);
                row3.height = 26;
                worksheet.mergeCells('A3:I3');
                const bannerCell = worksheet.getCell('A3');
                bannerCell.value = 'TRANSACTION DETAILS & CONTRACTOR PAYABLES AUDIT LEDGER';
                bannerCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                bannerCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row3.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };
                }

                // 4. Spacer Row 4
                worksheet.getRow(4).height = 10;

                // 5. Table Header Row 5 (#34495E)
                const headerRow = worksheet.getRow(5);
                headerRow.height = 32;
                const headers = ['SL NO', 'Date', 'Contractor Name', 'RA Bill # / Ref', 'Project / Work Location', 'Net Approved Dues (₹)', 'Paid Amount (₹)', 'Balance Due (₹)', 'Status'];
                headers.forEach((h, i) => {
                    const cell = headerRow.getCell(i + 1);
                    cell.value = h;
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };
                });

                // 6. Populate Rows
                let currentRowIndex = 6;
                this.filteredBills.forEach((item, idx) => {
                    const row = worksheet.getRow(currentRowIndex);
                    row.height = 32;

                    row.getCell(1).value = idx + 1;
                    row.getCell(2).value = item.verified_date || item.submit_date || '-';
                    row.getCell(3).value = item.contractor_name;
                    row.getCell(4).value = item.ra_bill_number;
                    row.getCell(5).value = item.project_name + (item.unit_label ? ' (' + item.unit_label + ')' : '');
                    row.getCell(6).value = item.net_approved_amount || 0;
                    row.getCell(7).value = item.paid_amount || 0;
                    row.getCell(8).value = item.balance_amount || 0;
                    row.getCell(9).value = item.status_label || 'PENDING';

                    const isEven = (idx % 2 === 1);
                    const rowBg = isEven ? 'FFF8FAFC' : 'FFFFFFFF';

                    for (let c = 1; c <= totalCols; c++) {
                        const cell = row.getCell(c);
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        cell.border = {
                            top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                        };

                        if (c === 1 || c === 2 || c === 9) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else if (c === 3 || c === 5) {
                            cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        } else if (c === 4) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else {
                            cell.alignment = { horizontal: 'right', vertical: 'middle' };
                        }

                        if (c === 3) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };
                        } else if (c === 4) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF17365D' } };
                        } else if (c >= 6 && c <= 8) {
                            cell.numFmt = '#,##0.00';
                            if (c === 6) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };
                            } else if (c === 7) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0B3B2E' } };
                            } else if (c === 8) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: item.balance_amount > 0 ? 'FFDC2626' : 'FF0B3B2E' } };
                            }
                        } else if (c === 9) {
                            cell.font = { name: 'Calibri', size: 9, bold: true, color: { argb: 'FF475569' } };
                        } else {
                            cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                        }
                    }
                    currentRowIndex++;
                });

                // 7. Grand Totals Row
                const totalsRow = worksheet.getRow(currentRowIndex);
                totalsRow.height = 36;
                worksheet.mergeCells(`A${currentRowIndex}:E${currentRowIndex}`);
                const totLabel = worksheet.getCell(`A${currentRowIndex}`);
                totLabel.value = 'TOTAL CONTRACTOR PAYABLES & DUES SUMMARY';
                totLabel.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                totLabel.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                totalsRow.getCell(6).value = this.totalApprovedDues;
                totalsRow.getCell(6).numFmt = '#,##0.00';
                totalsRow.getCell(6).font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                totalsRow.getCell(6).alignment = { horizontal: 'right', vertical: 'middle' };

                totalsRow.getCell(7).value = this.totalPaidAmount;
                totalsRow.getCell(7).numFmt = '#,##0.00';
                totalsRow.getCell(7).font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                totalsRow.getCell(7).alignment = { horizontal: 'right', vertical: 'middle' };

                totalsRow.getCell(8).value = this.totalBalanceDue;
                totalsRow.getCell(8).numFmt = '#,##0.00';
                totalsRow.getCell(8).font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                totalsRow.getCell(8).alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 1; c <= totalCols; c++) {
                    const cell = totalsRow.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };
                }

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                const cleanProj = (this.selectedProjectName || 'Project').replace(/[^a-zA-Z0-9_\-\s]/g, '').trim().replace(/\s+/g, '_');
                const cleanContractorName = (activeName || 'Contractor').replace(/[^a-zA-Z0-9_\-\s]/g, '').trim().replace(/\s+/g, '_');
                link.setAttribute('download', `${cleanProj}_${cleanContractorName}_Statement_Report.xlsx`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } catch (err) {
                console.error('Excel Export Error:', err);
                alert('An error occurred during Excel export: ' + err.message);
            }
        }
    };
}
</script>
</div>
</x-erp-layout>
