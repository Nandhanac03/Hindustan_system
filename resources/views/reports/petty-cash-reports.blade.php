@extends('layouts.erp')

@section('title', 'Petty Cash Reports - Hindustan Real Estate ERP')

@section('content')
{{-- ExcelJS Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 8mm 8mm 10mm 8mm;
    }
    *, *::before, *::after {
        box-sizing: border-box !important;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 9pt !important;
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
}
</style>

@php
    $selectedProjectId = request('project_id', $reportData['project_id'] ?? null);
    $currentProject = $projects->firstWhere('id', $selectedProjectId) ?? ($projects->first() ?? (object)['name' => 'All Sites', 'id' => '']);
    $entriesCollection = collect($reportData['entries'] ?? []);
    $contraEntries = $entriesCollection->where('type', 'Contra');
    $expenseEntries = $entriesCollection->where('type', 'Expense');
    $netPeriodChange = ($reportData['total_cash_in'] ?? 0) - ($reportData['total_cash_out'] ?? 0);
    $siteName = $reportData['site_name'] ?? ($currentProject->name ?? 'All Sites');
    $cashBoxIncharge = $reportData['cash_box_incharge'] ?? (auth()->check() ? auth()->user()->name : 'Owner');
    $cashBoxCode = $reportData['cash_box_code'] ?? 'PC-HEV-01-001';
    $lastUpdated = $reportData['last_updated'] ?? date('d-M-Y h:i A');
    $updatedBy = $reportData['updated_by'] ?? (auth()->check() ? auth()->user()->name : 'Owner');
@endphp

<div class="w-full px-6 py-6 bg-[#f8f9fa] min-h-screen font-sans print:px-0 print:py-0 print:min-h-0 print:bg-white">
    
    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Petty Cash &amp; Site Expense Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">PETTY CASH STATEMENT &amp; AUDIT REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Site Name:</span> <span class="font-bold text-slate-900">{{ $siteName }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Period:</span> <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($reportData['from_date'] ?? date('Y-m-01'))->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($reportData['to_date'] ?? date('Y-m-d'))->format('d-M-Y') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ date('d-M-Y h:i A') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-[#a38c29]">{{ count($reportData['entries'] ?? []) }} Entries</span></div>
            </div>
        </div>
    </div>

    <!-- ── EXECUTIVE PRINT KPI CARDS (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:grid grid-cols-4 gap-3 mb-5">
        <div class="border border-slate-300 rounded-xl p-3 bg-slate-50/50">
            <span class="text-[8.5px] font-black uppercase text-slate-500 block">Opening Balance</span>
            <strong class="text-sm font-black text-slate-900 font-mono block mt-0.5">₹ {{ number_format($reportData['opening_balance'] ?? 0, 2) }}</strong>
            <span class="text-[8px] text-slate-500 font-bold">As on {{ \Carbon\Carbon::parse($reportData['from_date'])->format('d-M-Y') }}</span>
        </div>
        <div class="border border-emerald-300 rounded-xl p-3 bg-emerald-50/30">
            <span class="text-[8.5px] font-black uppercase text-emerald-600 block">Cash In (Period)</span>
            <strong class="text-sm font-black text-emerald-700 font-mono block mt-0.5">₹ {{ number_format($reportData['total_cash_in'] ?? 0, 2) }}</strong>
            <span class="text-[8px] text-emerald-600 font-bold">Bank Withdrawals &amp; Receipts</span>
        </div>
        <div class="border border-rose-300 rounded-xl p-3 bg-rose-50/30">
            <span class="text-[8.5px] font-black uppercase text-rose-600 block">Cash Out (Period)</span>
            <strong class="text-sm font-black text-rose-700 font-mono block mt-0.5">₹ {{ number_format($reportData['total_cash_out'] ?? 0, 2) }}</strong>
            <span class="text-[8px] text-rose-600 font-bold">Site Expenses</span>
        </div>
        <div class="border border-slate-700 rounded-xl p-3 bg-slate-100/60">
            <span class="text-[8.5px] font-black uppercase text-slate-700 block">Closing Balance</span>
            <strong class="text-sm font-black text-slate-900 font-mono block mt-0.5">₹ {{ number_format($reportData['closing_balance'] ?? 0, 2) }}</strong>
            <span class="text-[8px] text-slate-600 font-bold">Current Cash In Hand</span>
        </div>
    </div>

    <!-- ── Breadcrumb & Top Action Header (Web Only) ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 print:hidden">
        <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">HOME</a>
            <span class="text-slate-300">›</span>
            <span>PETTY CASH &amp; SITE EXPENSE</span>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-black">PETTY CASH REPORTS</span>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <!-- Print / Export PDF Button -->
            <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2.5 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>EXPORT PDF</span>
            </button>

            <!-- Excel Report Button -->
            <button type="button" onclick="exportPettyCashReportExcel()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>EXPORT EXCEL</span>
            </button>
        </div>
    </div>

    <div id="petty-cash-report-content" class="relative">
        <script id="petty-cash-report-data" type="application/json">@json($reportData['entries'] ?? [])</script>

        <!-- ── 4 Metric KPI Cards Grid (Web Only) ── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 print:hidden">
            
            <!-- Card 1: Opening Balance -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Opening Balance</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        ₹ {{ number_format($reportData['opening_balance'] ?? 0, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">As on {{ \Carbon\Carbon::parse($reportData['from_date'])->format('d-M-Y') }}</p>
                </div>
            </div>

            <!-- Card 2: Cash In (Period) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Cash In (Period)</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        ₹ {{ number_format($reportData['total_cash_in'] ?? 0, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Bank Withdrawals &amp; Receipts</p>
                </div>
            </div>

            <!-- Card 3: Cash Out (Period) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Cash Out (Period)</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                        ₹ {{ number_format($reportData['total_cash_out'] ?? 0, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Site Expenses</p>
                </div>
            </div>

            <!-- Card 4: Closing Balance -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-slate-700 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-slate-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(51,65,85,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 border border-slate-200 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Closing Balance</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        ₹ {{ number_format($reportData['closing_balance'] ?? 0, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Current Cash In Hand</p>
                </div>
            </div>
        </div>

        <!-- ── Petty Cash Transaction Ledger Table Card (Web View) ── -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8 print:hidden relative">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-[14px] font-extrabold text-[#a38c29] uppercase tracking-wider">Petty Cash Transaction Ledger</h3>
                <span class="text-[11px] bg-slate-100 text-slate-700 px-3 py-1 rounded-full font-bold">
                    <span>{{ count($reportData['entries'] ?? []) }}</span> Entries
                </span>
            </div>

            <!-- Ledger Table -->
            <div id="ledgerTableContainer" class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap" id="pettyCashReportTable">
                    <thead class="bg-[#a38c29] text-white">
                        <tr>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Date</th>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Voucher No.</th>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Type</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Cash In (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Cash Out (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Balance (₹)</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="reportTableBody">
                        @forelse($reportData['entries'] ?? [] as $txn)
                            @php
                                $isOpening = ($txn->type === 'Opening' || $txn->particulars === 'Opening Balance');
                                $typeDisplay = $txn->type === 'Contra' ? 'Contra - ' . $txn->particulars : ($txn->type === 'Expense' ? 'Site Expense - ' . $txn->particulars : $txn->particulars);
                            @endphp
                            <tr class="transaction-row hover:bg-gray-50 transition-colors">
                                
                                <td class="px-5 py-4 text-[11px] font-bold text-gray-700">{{ $txn->date }}</td>
                                
                                <td class="px-5 py-4">
                                    @if(!$isOpening && $txn->voucher_number !== '-')
                                        <span class="text-[11px] font-bold text-[#a38c29] uppercase cursor-pointer hover:underline"
                                              onclick="showDetailModal(this)"
                                              data-voucher="{{ $txn->voucher_number }}"
                                              data-date="{{ $txn->date }}"
                                              data-type="{{ $typeDisplay }}"
                                              data-cashin="{{ $txn->cash_in > 0 ? number_format($txn->cash_in, 2) : '0.00' }}"
                                              data-cashout="{{ $txn->cash_out > 0 ? number_format($txn->cash_out, 2) : '0.00' }}"
                                              data-balance="{{ number_format($txn->balance, 2) }}"
                                              data-reference="{{ ($txn->reference_no && $txn->reference_no !== '-') ? $txn->reference_no : 'N/A' }}">
                                            {{ $txn->voucher_number }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-bold text-[11px]">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-[11px] font-bold text-[#1e2a5e]">
                                    {{ $typeDisplay }}
                                </td>

                                <td class="px-5 py-4 text-right text-[11px] font-bold {{ $txn->cash_in > 0 ? 'text-[#1e2a5e]' : 'text-gray-400' }}">
                                    {{ $txn->cash_in > 0 ? number_format($txn->cash_in, 2) : '-' }}
                                </td>

                                <td class="px-5 py-4 text-right text-[11px] font-bold {{ $txn->cash_out > 0 ? 'text-[#1e2a5e]' : 'text-gray-400' }}">
                                    {{ $txn->cash_out > 0 ? number_format($txn->cash_out, 2) : '-' }}
                                </td>

                                <td class="px-5 py-4 text-right text-[11px] font-bold text-[#1e2a5e]">
                                    {{ number_format($txn->balance, 2) }}
                                </td>

                                <td class="px-5 py-4 text-left text-[11px] font-medium text-gray-500">
                                    {{ ($txn->reference_no && $txn->reference_no !== '-') ? $txn->reference_no : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-[12px] font-bold text-gray-400 uppercase tracking-wider">No transactions found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ── COMPLETE EXECUTIVE PRINTABLE DATA TABLE (FOR PRINT / PDF) ── -->
        <div class="hidden print:block mb-8">
            <div class="border border-slate-300 rounded-xl overflow-hidden">
                <table class="w-full text-[9.5px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b-2 border-[#8a7522] text-[9px] font-black uppercase tracking-wider">
                            <th class="px-3 py-2 text-center text-white w-10">SL NO</th>
                            <th class="px-3 py-2 text-white">DATE</th>
                            <th class="px-3 py-2 text-white">VOUCHER NO.</th>
                            <th class="px-3 py-2 text-white">TYPE / PARTICULARS</th>
                            <th class="px-3 py-2 text-right text-white">CASH IN (₹)</th>
                            <th class="px-3 py-2 text-right text-white">CASH OUT (₹)</th>
                            <th class="px-3 py-2 text-right text-white">BALANCE (₹)</th>
                            <th class="px-3 py-2 text-left text-white">REFERENCE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($reportData['entries'] ?? [] as $index => $txn)
                            @php
                                $typeDisplay = $txn->type === 'Contra' ? 'Contra - ' . $txn->particulars : ($txn->type === 'Expense' ? 'Site Expense - ' . $txn->particulars : $txn->particulars);
                            @endphp
                            <tr class="border-b border-slate-200 text-slate-800">
                                <td class="px-3 py-2 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 font-bold text-slate-900">{{ $txn->date }}</td>
                                <td class="px-3 py-2 font-mono font-bold text-[#a38c29] uppercase">{{ $txn->voucher_number ?? '—' }}</td>
                                <td class="px-3 py-2 font-bold text-slate-700">{{ $typeDisplay }}</td>
                                <td class="px-3 py-2 text-right font-mono font-bold {{ $txn->cash_in > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                    {{ $txn->cash_in > 0 ? number_format($txn->cash_in, 2) : '-' }}
                                </td>
                                <td class="px-3 py-2 text-right font-mono font-bold {{ $txn->cash_out > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                    {{ $txn->cash_out > 0 ? number_format($txn->cash_out, 2) : '-' }}
                                </td>
                                <td class="px-3 py-2 text-right font-mono font-black text-slate-900">
                                    {{ number_format($txn->balance, 2) }}
                                </td>
                                <td class="px-3 py-2 text-left font-mono text-slate-600">
                                    {{ ($txn->reference_no && $txn->reference_no !== '-') ? $txn->reference_no : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-slate-500 italic">No transactions found for this period.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-slate-100 font-bold border-t-2 border-slate-400">
                            <td colspan="4" class="px-4 py-2.5 text-right uppercase tracking-wider text-[10px] text-slate-800">TOTAL SUMMARY:</td>
                            <td class="px-3 py-2.5 text-right font-mono text-[10px] text-emerald-800 font-black">₹ {{ number_format($reportData['total_cash_in'] ?? 0, 2) }}</td>
                            <td class="px-3 py-2.5 text-right font-mono text-[10px] text-rose-800 font-black">₹ {{ number_format($reportData['total_cash_out'] ?? 0, 2) }}</td>
                            <td class="px-3 py-2.5 text-right font-mono text-[10px] text-slate-900 font-black">₹ {{ number_format($reportData['closing_balance'] ?? 0, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



    </div>

</div>


<!-- ── TRANSACTION DETAILS MODAL ── -->
<div id="transactionDetailModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 print:hidden transition-opacity duration-300" style="display: none;" onclick="if(event.target === this) closeDetailModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border-0 border-none ring-0 outline-none flex flex-col transform transition-all">
        
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 px-6 py-4 border-0 border-none flex-shrink-0">
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-amber-400/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-400/20 flex items-center justify-center text-amber-400 shadow-inner">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="px-2 py-0.5 rounded bg-amber-400/20 text-amber-300 text-[9px] font-black uppercase tracking-widest whitespace-nowrap">Petty Cash</span>
                        <h2 class="text-xs font-black text-white uppercase tracking-wider mt-0.5" id="modalHeaderTitle">Transaction Voucher Details</h2>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer border-0 border-none">✕</button>
            </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 bg-white border-0 border-none">
            <table class="w-full text-xs border-0 border-none">
                <tbody class="divide-y divide-slate-100 border-0 border-none">
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider w-1/3 border-0 border-none">Voucher No.</td>
                        <td class="py-2.5 text-right font-mono font-bold text-[#a38c29] border-0 border-none" id="modalVoucherNo">—</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Date</td>
                        <td class="py-2.5 text-right font-bold text-slate-800 border-0 border-none" id="modalDate">—</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Type / Narration</td>
                        <td class="py-2.5 text-right font-bold text-[#1e2a5e] border-0 border-none" id="modalType">—</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Cash In</td>
                        <td class="py-2.5 text-right font-mono font-bold text-emerald-600 border-0 border-none" id="modalCashIn">₹ 0.00</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Cash Out</td>
                        <td class="py-2.5 text-right font-mono font-bold text-rose-600 border-0 border-none" id="modalCashOut">₹ 0.00</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Running Balance</td>
                        <td class="py-2.5 text-right font-mono font-black text-slate-900 border-0 border-none" id="modalBalance">₹ 0.00</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Reference</td>
                        <td class="py-2.5 text-right font-mono text-slate-600 border-0 border-none" id="modalReference">N/A</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50/70 flex items-center justify-end rounded-b-2xl border-0 border-none">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer border-0 border-none shadow-none">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    // Modal Handling
    function showDetailModal(btn) {
        if (!btn || !btn.dataset) return;
        const d = btn.dataset;
        openDetailModal(d.voucher, d.date, d.type, d.cashin, d.cashout, d.balance, d.reference);
    }

    function openDetailModal(voucher, date, type, cashIn, cashOut, balance, reference) {
        try {
            document.getElementById('modalVoucherNo').innerText = voucher || '—';
            document.getElementById('modalDate').innerText = date || '—';
            document.getElementById('modalType').innerText = type || '—';
            document.getElementById('modalCashIn').innerText = '₹ ' + (cashIn || '0.00');
            document.getElementById('modalCashOut').innerText = '₹ ' + (cashOut || '0.00');
            document.getElementById('modalBalance').innerText = '₹ ' + (balance || '0.00');
            document.getElementById('modalReference').innerText = reference || 'N/A';

            const modal = document.getElementById('transactionDetailModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Error opening detail modal:', err);
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('transactionDetailModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
        }
    });

    // ExcelJS Multi-Tier Export matching executive company standard
    async function exportPettyCashReportExcel() {
        if (typeof ExcelJS === 'undefined') {
            alert('ExcelJS is loading. Please try again in a moment.');
            return;
        }

        try {
            // ── Determine Dynamic Project / Site Name ──
            let activeProjName = '';
            const projSelect = document.querySelector('select[name="project_id"]') || document.querySelector('select[x-model="filters.project_id"]');
            if (projSelect && projSelect.selectedIndex >= 0) {
                const opt = projSelect.options[projSelect.selectedIndex];
                if (opt && opt.value && opt.text && !['all projects', 'all sites'].includes(opt.text.trim().toLowerCase())) {
                    activeProjName = opt.text.trim().toUpperCase();
                }
            }
            if (!activeProjName && projSelect && projSelect.options.length > 1) {
                for (let i = 0; i < projSelect.options.length; i++) {
                    if (projSelect.options[i].value && !['all projects', 'all sites'].includes(projSelect.options[i].text.trim().toLowerCase())) {
                        activeProjName = projSelect.options[i].text.trim().toUpperCase();
                        break;
                    }
                }
            }
            if (!activeProjName) {
                const bladeSite = "{{ addslashes($siteName) }}".trim();
                if (bladeSite && !['all projects', 'all sites'].includes(bladeSite.toLowerCase())) {
                    activeProjName = bladeSite.toUpperCase();
                }
            }
            if (!activeProjName) activeProjName = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD';

            const reportTitleText = activeProjName + ' - PETTY CASH STATEMENT REPORT';

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'Hindustan ERP';
            workbook.lastModifiedBy = 'Hindustan ERP';
            workbook.created = new Date();
            workbook.modified = new Date();

            const worksheet = workbook.addWorksheet('Petty Cash Report', {
                views: [{ showGridLines: true }]
            });

            // ── 1. Column Definitions ──
            const totalCols = 8;
            worksheet.columns = [
                { key: 'sl', width: 10 },          // Col 1: SL NO
                { key: 'date', width: 16 },        // Col 2: Date
                { key: 'voucher', width: 22 },     // Col 3: Voucher No.
                { key: 'particulars', width: 40 }, // Col 4: Type / Particulars
                { key: 'cash_in', width: 22 },     // Col 5: Cash In (₹)
                { key: 'cash_out', width: 22 },    // Col 6: Cash Out (₹)
                { key: 'balance', width: 22 },     // Col 7: Balance (₹)
                { key: 'reference', width: 24 }    // Col 8: Reference
            ];

            // ── 2. Spacing Row 1 ──
            worksheet.getRow(1).height = 15;

            // ── 3. Banner 1: Project Header / Title (Row 2) - Dark Slate Blue (#2C3E50) ──
            const row2 = worksheet.getRow(2);
            row2.height = 32;
            worksheet.mergeCells('A2:H2');
            const titleCell = worksheet.getCell('A2');
            titleCell.value = reportTitleText;
            titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
            titleCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
            for (let c = 1; c <= totalCols; c++) {
                const cell = row2.getCell(c);
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };
            }

            // ── 4. Banner 2: Context / Subtitle (Row 3) - Teal Blue (#007398) ──
            const row3 = worksheet.getRow(3);
            row3.height = 24;
            worksheet.mergeCells('A3:H3');
            const subCell = worksheet.getCell('A3');
            
            let siteHeaderPrefix = '';
            if (activeProjName && !['TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD', 'ALL PROJECTS', 'ALL SITES', 'SITE PROJECT'].includes(activeProjName.trim().toUpperCase())) {
                siteHeaderPrefix = 'Site: ' + activeProjName + ' | ';
            }
            
            subCell.value = siteHeaderPrefix + 'Period: {{ \Carbon\Carbon::parse($reportData['from_date'] ?? date('Y-m-01'))->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($reportData['to_date'] ?? date('Y-m-d'))->format('d-M-Y') }}';
            subCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
            subCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
            for (let c = 1; c <= totalCols; c++) {
                const cell = row3.getCell(c);
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };
            }

            // ── 5. Banner 3: Transaction Details (Row 4) - Deep Green (#006039) ──
            const row4 = worksheet.getRow(4);
            row4.height = 24;
            worksheet.mergeCells('A4:H4');
            const bannerCell = worksheet.getCell('A4');
            bannerCell.value = 'TRANSACTION DETAILS';
            bannerCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
            bannerCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
            for (let c = 1; c <= totalCols; c++) {
                const cell = row4.getCell(c);
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };
            }

            // ── 6. Spacing Row 5 ──
            worksheet.getRow(5).height = 10;

            // ── 7. Table Column Headers (Row 6) - Dark Slate Gray (#34495E) ──
            const headerRow = worksheet.getRow(6);
            headerRow.values = [
                'SL NO',
                'Date',
                'Voucher No.',
                'Type / Particulars',
                'Cash In (₹)',
                'Cash Out (₹)',
                'Balance (₹)',
                'Reference'
            ];
            headerRow.height = 30;

            for (let c = 1; c <= totalCols; c++) {
                const cell = headerRow.getCell(c);
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FF34495E' }
                };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };
            }

            // ── 8. Retrieve Data Rows ──
            let txnsData = [];
            const dataScript = document.getElementById('petty-cash-report-data');
            if (dataScript) {
                try {
                    txnsData = JSON.parse(dataScript.textContent || '[]');
                } catch (e) {
                    console.error('Error parsing embedded data:', e);
                }
            }

            // Fallback to table DOM rows if dataScript was empty
            if (!txnsData || txnsData.length === 0) {
                const tableRows = document.querySelectorAll('#pettyCashReportTable tbody tr');
                tableRows.forEach(tr => {
                    const tds = tr.querySelectorAll('td');
                    if (tds.length >= 7 && !tr.innerText.includes('No transactions found')) {
                        const btn = tr.querySelector('span[data-voucher]');
                        if (btn) {
                            txnsData.push({
                                date: btn.getAttribute('data-date'),
                                voucher_number: btn.getAttribute('data-voucher'),
                                type_label: btn.getAttribute('data-type'),
                                cash_in: parseFloat((btn.getAttribute('data-cashin') || '0').replace(/,/g, '')) || 0,
                                cash_out: parseFloat((btn.getAttribute('data-cashout') || '0').replace(/,/g, '')) || 0,
                                balance: parseFloat((btn.getAttribute('data-balance') || '0').replace(/,/g, '')) || 0,
                                reference_no: btn.getAttribute('data-reference') !== 'N/A' ? btn.getAttribute('data-reference') : '-'
                            });
                        }
                    }
                });
            }

            let currentRowIdx = 7;
            let lastBalance = 0;
            let sumCashIn = 0;
            let sumCashOut = 0;

            txnsData.forEach((txn, index) => {
                const rowNum = index + 1;
                const cashInVal = parseFloat(txn.cash_in) || 0;
                const cashOutVal = parseFloat(txn.cash_out) || 0;
                const balanceVal = parseFloat(txn.balance) || 0;
                lastBalance = balanceVal;
                sumCashIn += cashInVal;
                sumCashOut += cashOutVal;

                let formattedDate = txn.date || '-';
                if (formattedDate.includes('-') && formattedDate.length === 10) {
                    const parts = formattedDate.split('-');
                    if (parts[0].length === 4) {
                        formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                    }
                }

                let typeDisplay = txn.type_label || txn.particulars || '-';
                if (!txn.type_label && txn.type) {
                    typeDisplay = txn.type === 'Contra' ? ('Contra - ' + (txn.particulars || '')) : (txn.type === 'Expense' ? ('Site Expense - ' + (txn.particulars || '')) : (txn.particulars || ''));
                }

                const dataRow = worksheet.getRow(currentRowIdx);
                dataRow.values = [
                    rowNum,
                    formattedDate,
                    txn.voucher_number || '-',
                    typeDisplay,
                    cashInVal > 0 ? cashInVal : 0,
                    cashOutVal > 0 ? cashOutVal : 0,
                    balanceVal,
                    (txn.reference_no && txn.reference_no !== '-' && txn.reference_no !== 'N/A') ? txn.reference_no : (txn.reference || '-')
                ];
                dataRow.height = 26;

                const isEven = (index + 1) % 2 === 0;
                const rowBg = isEven ? 'FFFFFFFF' : 'FFF8FAFC';

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
                    // Center align all data cells
                    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                    if (c === 3) {
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF17365D' } };
                    } else if (c === 5 || c === 6 || c === 7) {
                        cell.numFormat = '#,##0.00';
                        if (c === 5 && cashInVal > 0) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0B3B2E' } };
                        } else if (c === 6 && cashOutVal > 0) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFDC2626' } };
                        } else if (c === 7) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF17365D' } };
                        }
                    }
                }

                currentRowIdx++;
            });

            // ── 9. Bottom Summary / Total Row (#2C3E50) ──
            const totalRow = worksheet.getRow(currentRowIdx);
            totalRow.height = 36;

            worksheet.mergeCells(`A${currentRowIdx}:F${currentRowIdx}`);
            const totalLabelCell = worksheet.getCell(`A${currentRowIdx}`);
            totalLabelCell.value = 'TOTAL CLOSING BALANCE';
            totalLabelCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            totalLabelCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

            const totalBalCell = worksheet.getCell(`G${currentRowIdx}`);
            totalBalCell.value = lastBalance;
            totalBalCell.numFormat = '#,##0.00';
            totalBalCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            totalBalCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

            for (let c = 1; c <= totalCols; c++) {
                const cell = totalRow.getCell(c);
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FF2C3E50' }
                };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };
            }

            // ── 10. Generate & Download ──
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.download = 'petty-cash-statement-report.xlsx';
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
            window.URL.revokeObjectURL(url);
        } catch (err) {
            console.error('Excel Export Error:', err);
            alert('Failed to generate Excel report: ' + err.message);
        }
    }
</script>
@endsection
