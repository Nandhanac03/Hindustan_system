@extends('layouts.erp')

@section('title', 'Petty Cash Reports - Hindustan Real Estate ERP')

@section('content')
@php
    $currentProject = $projects->firstWhere('id', $reportData['project_id']) ?? ($projects->first() ?? (object)['name' => 'All Sites', 'id' => '']);
    $entriesCollection = collect($reportData['entries']);
    $contraEntries = $entriesCollection->where('type', 'Contra');
    $expenseEntries = $entriesCollection->where('type', 'Expense');
    $netPeriodChange = $reportData['total_cash_in'] - $reportData['total_cash_out'];
@endphp

<div class="space-y-4">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white px-5 py-4 rounded-xl shadow-sm border border-slate-200 print:hidden">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Petty Cash Reports</h1>
            <p class="text-xs text-slate-500 mt-1">Home / Petty Cash &amp; Site Expense / Petty Cash Reports</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <!-- Export Excel Button (Cancellation Style) -->
            <button type="button" onclick="exportToExcel()"
                    class="px-5 py-2.5 bg-[#059669] hover:bg-[#047857] text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md shadow-emerald-700/20 hover:shadow-lg transition-all duration-300 flex items-center gap-2.5 cursor-pointer group active:scale-95">
                <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="tracking-wide">EXPORT EXCEL</span>
            </button>

            <!-- Print Button -->
            <button type="button" onclick="window.print()"
                    class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-sm hover:shadow transition-all duration-300 flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- ── SUMMARY KPI CARDS (Cancellation & Additional Work Style) ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Opening Balance -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all group-hover:bg-[#a38c29] group-hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">OPENING BALANCE</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight group-hover:text-[#a38c29] transition-colors">₹{{ number_format((float) $reportData['opening_balance'], 2) }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1">As on {{ \Carbon\Carbon::parse($reportData['from_date'])->format('d-M-Y') }}</p>
            </div>
        </div>

        <!-- Card 2: Total Cash Out -->
        <div class="bg-white border-y border-r border-l-4 border-l-amber-500 border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 transition-all group-hover:bg-amber-500 group-hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">TOTAL CASH OUT</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-amber-700 tracking-tight group-hover:text-amber-800 transition-colors">-₹{{ number_format((float) $reportData['total_cash_out'], 2) }}</h3>
                <p class="text-[10px] font-bold text-amber-600 mt-1">{{ $expenseEntries->count() }} Site Disbursements</p>
            </div>
        </div>

        <!-- Card 3: Total Cash In -->
        <div class="bg-white border-y border-r border-l-4 border-l-blue-500 border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all group-hover:bg-blue-500 group-hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">TOTAL CASH IN</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $reportData['total_cash_in'], 2) }}</h3>
                <p class="text-[10px] font-bold text-blue-600 mt-1">{{ $contraEntries->count() }} Replenishments</p>
            </div>
        </div>

        <!-- Card 4: Closing Balance -->
        <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all group-hover:bg-emerald-500 group-hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4m-10 12h8m-8 4h8m-16 0h.01M3 16h.01M3 12h.01M3 8h.01M3 4h.01"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">CLOSING BALANCE</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors">₹{{ number_format((float) $reportData['closing_balance'], 2) }}</h3>
                <p class="text-[10px] font-bold text-emerald-600 mt-1">Current Cash In Hand</p>
            </div>
        </div>
    </div>

    <!-- ── NAVIGATION TABS BAR (Exact Cancellation Charges Capsule Tab Design) ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 print:hidden">
        
        <!-- Tab Capsule Container (Matches Cancellation Charges & Additional Work) -->
        <div class="bg-white rounded-2xl p-1.5 border border-slate-200/90 shadow-2xs inline-flex flex-wrap items-center gap-1.5" id="reportTabs">
            
            <!-- Tab 1: Balance Register (Active by default) -->
            <button type="button" onclick="switchTab('balance-register')" id="tab-btn-balance-register" 
                    class="tab-btn px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center gap-2 bg-[#a38c29] text-white shadow-xs cursor-pointer">
                <span class="tab-icon-box w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span>Balance Register</span>
                <span class="tab-badge px-2 py-0.5 rounded-md text-[10px] font-black bg-[#8a7522] text-white">{{ count($reportData['entries']) }}</span>
            </button>

            <!-- Tab 2: Expense Report -->
            <button type="button" onclick="switchTab('expense-report')" id="tab-btn-expense-report" 
                    class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer">
                <span class="tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <span>Expense Report</span>
                <span class="tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">{{ $expenseEntries->count() }}</span>
            </button>

            <!-- Tab 3: Contra Report -->
            <button type="button" onclick="switchTab('contra-report')" id="tab-btn-contra-report" 
                    class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer">
                <span class="tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span>Contra Report</span>
                <span class="tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">{{ $contraEntries->count() }}</span>
            </button>

            <!-- Tab 4: Category Summary -->
            <button type="button" onclick="switchTab('category-summary')" id="tab-btn-category-summary" 
                    class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer">
                <span class="tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                </span>
                <span>Category Summary</span>
            </button>

        </div>

        <!-- Clean Quick Search Input Box -->
        <div class="relative w-full sm:w-72 self-center">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="tableSearchInput" onkeyup="filterTable()" placeholder="Search voucher, particulars, reference..." 
                   class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition-all shadow-2xs">
        </div>
    </div>

    <!-- ── DIRECTORY TABLE CARD (Exact Cancellation Charges Directory Header & Table) ── -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Directory Header with Vertical Gold Accent Bar -->
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    <span id="currentTabHeading">PETTY CASH TRANSACTION &amp; BALANCE REGISTER</span>
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3" id="currentTabSubtitle">Directory of all cash inward claims, site expenses, and contra replenish vouchers.</p>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-[11px] bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full font-bold">
                    <span id="tableRecordCount">{{ count($reportData['entries']) }}</span> Records
                </span>
                <div class="text-[10.5px] text-slate-500 font-semibold hidden md:block">
                    <span>Site: <strong class="text-slate-800 font-bold">{{ $currentProject->name ?? 'All Sites' }}</strong></span>
                    <span class="text-slate-300 mx-1.5">•</span>
                    <span>Period: <strong class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($reportData['from_date'])->format('d-M-Y') }}</strong> to <strong class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($reportData['to_date'])->format('d-M-Y') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- ── TABLE COMPONENT (Signature Gold Thead) ── -->
        <div id="ledgerTableContainer">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="pettyCashTable">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr class="text-left">
                            <th class="px-4 py-3 text-left w-[95px]">DATE</th>
                            <th class="px-4 py-3 text-left w-[120px]">VOUCHER NO</th>
                            <th class="px-4 py-3 text-left min-w-[220px]">PARTICULARS</th>
                            <th class="px-4 py-3 text-right w-[110px]">CASH IN (₹)</th>
                            <th class="px-4 py-3 text-right w-[110px]">CASH OUT (₹)</th>
                            <th class="px-4 py-3 text-right bg-[#8a7522] text-amber-100 w-[125px] border-x border-[#7a671b]">RUNNING BALANCE (₹)</th>
                            <th class="px-4 py-3 text-center w-[100px]">STATUS / TYPE</th>
                            <th class="px-4 py-3 text-left w-[110px]">REFERENCE</th>
                            <th class="px-4 py-3 text-center w-[80px] print:hidden">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold" id="tableBody">
                        @forelse($reportData['entries'] as $entry)
                        <tr class="transaction-row hover:bg-slate-50 transition-colors border-b border-slate-100" 
                            data-type="{{ $entry->type }}" 
                            data-voucher="{{ strtolower($entry->voucher_number) }}" 
                            data-particulars="{{ strtolower($entry->particulars) }}" 
                            data-reference="{{ strtolower($entry->reference_no) }}">
                            
                            <!-- Date -->
                            <td class="px-4 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/40 font-mono text-slate-700 whitespace-nowrap text-[10.5px]">
                                {{ $entry->date }}
                            </td>

                            <!-- Voucher No -->
                            <td class="px-4 py-3 text-left align-middle whitespace-nowrap">
                                @if($entry->voucher_number && $entry->voucher_number !== '-')
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-900 rounded font-mono font-extrabold text-[10.5px] whitespace-nowrap shadow-2xs cursor-pointer transition"
                                          onclick="showDetailModal(this)"
                                          data-voucher="{{ $entry->voucher_number }}"
                                          data-date="{{ $entry->date }}"
                                          data-particulars="{{ htmlspecialchars($entry->particulars, ENT_QUOTES, 'UTF-8') }}"
                                          data-cashin="{{ $entry->cash_in }}"
                                          data-cashout="{{ $entry->cash_out }}"
                                          data-balance="{{ $entry->balance }}"
                                          data-type="{{ $entry->type }}"
                                          data-reference="{{ $entry->reference_no }}">
                                        {{ $entry->voucher_number }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-mono">—</span>
                                @endif
                            </td>

                            <!-- Particulars -->
                            <td class="px-4 py-3 align-middle text-slate-900 font-bold">
                                {{ $entry->particulars }}
                            </td>

                            <!-- Cash In -->
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-emerald-600 align-middle whitespace-nowrap">
                                {{ $entry->cash_in > 0 ? '+₹' . number_format($entry->cash_in, 2) : '—' }}
                            </td>

                            <!-- Cash Out -->
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-amber-600 align-middle whitespace-nowrap">
                                {{ $entry->cash_out > 0 ? '-₹' . number_format($entry->cash_out, 2) : '—' }}
                            </td>

                            <!-- Running Balance -->
                            <td class="px-4 py-3 text-right font-mono font-black text-[#a38c29] bg-amber-50/30 align-middle whitespace-nowrap border-x border-amber-200/30">
                                ₹{{ number_format($entry->balance, 2) }}
                            </td>

                            <!-- Status / Type -->
                            <td class="px-4 py-3 text-center whitespace-nowrap align-middle">
                                @if($entry->type === 'Contra')
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>CONTRA</span>
                                    </span>
                                @elseif($entry->type === 'Expense')
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>EXPENSE</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-700 border border-slate-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <span>OPENING</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Reference -->
                            <td class="px-4 py-3 font-mono text-slate-500 text-[10px] align-middle whitespace-nowrap">
                                {{ $entry->reference_no !== '-' ? $entry->reference_no : '—' }}
                            </td>

                            <!-- Actions (Only Eye Icon) -->
                            <td class="px-4 py-3 text-center whitespace-nowrap align-middle print:hidden">
                                @if($entry->voucher_number && $entry->voucher_number !== '-')
                                    <button type="button" 
                                            onclick="showDetailModal(this)"
                                            data-voucher="{{ $entry->voucher_number }}"
                                            data-date="{{ $entry->date }}"
                                            data-particulars="{{ htmlspecialchars($entry->particulars, ENT_QUOTES, 'UTF-8') }}"
                                            data-cashin="{{ $entry->cash_in }}"
                                            data-cashout="{{ $entry->cash_out }}"
                                            data-balance="{{ $entry->balance }}"
                                            data-type="{{ $entry->type }}"
                                            data-reference="{{ $entry->reference_no }}"
                                            class="w-7 h-7 rounded-lg bg-amber-50/80 hover:bg-[#a38c29] text-[#a38c29] hover:text-white border border-[#a38c29]/30 inline-flex items-center justify-center transition-all duration-200 cursor-pointer shadow-2xs group"
                                            title="View Voucher Details">
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400 text-xs font-bold uppercase tracking-wider">
                                No records found for the selected period and site.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if(count($reportData['entries']) > 0)
                    <tfoot class="bg-slate-50 text-slate-900 font-bold border-t-2 border-slate-300 text-xs">
                        <tr>
                            <td colspan="3" class="px-4 py-3 font-black uppercase text-slate-900 tracking-wider">
                                Period Total Summary
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-emerald-700">
                                +₹{{ number_format($reportData['total_cash_in'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-amber-700">
                                -₹{{ number_format($reportData['total_cash_out'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-[#a38c29] bg-amber-50/50 border-x border-amber-200/50">
                                ₹{{ number_format($reportData['closing_balance'], 2) }}
                            </td>
                            <td colspan="3" class="px-4 py-3 text-slate-500 text-[10.5px] font-semibold text-center">
                                Cash in Hand
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            @if(count($reportData['entries']) > 0)
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500 font-semibold print:hidden">
                <div>
                    Showing <span class="font-bold text-slate-800" id="visibleCount">{{ count($reportData['entries']) }}</span> of <span class="font-bold text-slate-800">{{ count($reportData['entries']) }}</span> entries
                </div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                    Hindustan ERP Verified Ledger
                </div>
            </div>
            @endif
        </div>

        <!-- ── CATEGORY SUMMARY VIEW (TAB 4) ── -->
        <div id="categorySummaryContainer" class="hidden p-5 space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                
                <!-- Site Expenses Breakdown -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Site Expense Disbursements</h3>
                            <p class="text-[11px] text-slate-500 font-semibold">Categorized expenditure for the period</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-200">
                            Total: ₹{{ number_format($reportData['total_cash_out'], 2) }}
                        </span>
                    </div>

                    @if($expenseEntries->count() > 0)
                        @php
                            $groupedExpenses = $expenseEntries->groupBy('particulars')->map(function($items) {
                                return [
                                    'particulars' => $items->first()->particulars,
                                    'total' => $items->sum('cash_out'),
                                    'count' => $items->count()
                                ];
                            })->sortByDesc('total');
                        @endphp
                        <div class="space-y-3.5">
                            @foreach($groupedExpenses as $item)
                            @php
                                $percent = $reportData['total_cash_out'] > 0 ? round(($item['total'] / $reportData['total_cash_out']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold mb-1">
                                    <span class="text-slate-800">
                                        {{ $item['particulars'] }}
                                        <span class="text-[10px] text-slate-400 font-normal">({{ $item['count'] }} vouchers)</span>
                                    </span>
                                    <span class="font-mono text-slate-900 font-extrabold">₹{{ number_format($item['total'], 2) }} ({{ $percent }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-amber-500 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-400 text-xs">
                            No expense disbursements recorded for this period.
                        </div>
                    @endif
                </div>

                <!-- Contra / Cash Inflows Breakdown -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Inflows &amp; Contra Withdrawals</h3>
                            <p class="text-[11px] text-slate-500 font-semibold">Bank cash receipts and cash box replenishments</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Total: ₹{{ number_format($reportData['total_cash_in'], 2) }}
                        </span>
                    </div>

                    @if($contraEntries->count() > 0)
                        @php
                            $groupedContra = $contraEntries->groupBy('particulars')->map(function($items) {
                                return [
                                    'particulars' => $items->first()->particulars,
                                    'total' => $items->sum('cash_in'),
                                    'count' => $items->count()
                                ];
                            })->sortByDesc('total');
                        @endphp
                        <div class="space-y-3.5">
                            @foreach($groupedContra as $item)
                            @php
                                $percent = $reportData['total_cash_in'] > 0 ? round(($item['total'] / $reportData['total_cash_in']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold mb-1">
                                    <span class="text-slate-800">
                                        {{ $item['particulars'] }}
                                        <span class="text-[10px] text-slate-400 font-normal">({{ $item['count'] }} entries)</span>
                                    </span>
                                    <span class="font-mono text-slate-900 font-extrabold">₹{{ number_format($item['total'], 2) }} ({{ $percent }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-600 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-400 text-xs">
                            No cash inflows recorded for this period.
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

</div>

<!-- ── TRANSACTION DETAILS MODAL ── -->
<div id="transactionDetailModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs items-center justify-center p-4 print:hidden transition-opacity duration-300" style="display: none;" onclick="if(event.target === this) closeDetailModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border-0 border-none ring-0 outline-none flex flex-col transform transition-all">
        <!-- Dark & Yellow Color Theme Modal Header -->
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
                <tbody class="border-0 border-none">
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider w-1/3 border-0 border-none">Voucher No.</td>
                        <td class="py-2.5 text-right font-mono font-bold text-[#a38c29] border-0 border-none" id="modalVoucherNo"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Date</td>
                        <td class="py-2.5 text-right font-bold text-slate-800 border-0 border-none" id="modalDate"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Transaction Type</td>
                        <td class="py-2.5 text-right font-bold border-0 border-none" id="modalType"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Particulars</td>
                        <td class="py-2.5 text-right font-semibold text-slate-900 border-0 border-none" id="modalParticulars"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Cash In</td>
                        <td class="py-2.5 text-right font-mono font-bold text-emerald-600 border-0 border-none" id="modalCashIn"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Cash Out</td>
                        <td class="py-2.5 text-right font-mono font-bold text-amber-700 border-0 border-none" id="modalCashOut"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Running Balance</td>
                        <td class="py-2.5 text-right font-mono font-black text-slate-900 border-0 border-none" id="modalBalance"></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-0 border-none">Reference</td>
                        <td class="py-2.5 text-right font-mono text-slate-600 border-0 border-none" id="modalReference"></td>
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
    let currentActiveTab = 'balance-register';

    const tabConfigs = {
        'balance-register': {
            heading: 'PETTY CASH TRANSACTION & BALANCE REGISTER',
            subtitle: 'Directory of all cash inward claims, site expenses, and contra replenish vouchers.',
            filterType: 'all',
            activeBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center gap-2 bg-[#a38c29] text-white shadow-xs cursor-pointer',
            activeIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center shrink-0',
            activeBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-black bg-[#8a7522] text-white',
            inactiveBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer',
            inactiveIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0',
            inactiveBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600'
        },
        'expense-report': {
            heading: 'SITE EXPENSE DISBURSEMENTS REGISTER',
            subtitle: 'Directory of all site expense disbursements and vouchers.',
            filterType: 'Expense',
            activeBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center gap-2 bg-[#a38c29] text-white shadow-xs cursor-pointer',
            activeIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center shrink-0',
            activeBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-black bg-[#8a7522] text-white',
            inactiveBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer',
            inactiveIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0',
            inactiveBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600'
        },
        'contra-report': {
            heading: 'CONTRA REPLENISHMENTS & INFLOWS REGISTER',
            subtitle: 'Directory of all contra withdrawals and cash box replenishments.',
            filterType: 'Contra',
            activeBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center gap-2 bg-[#a38c29] text-white shadow-xs cursor-pointer',
            activeIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center shrink-0',
            activeBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-black bg-[#8a7522] text-white',
            inactiveBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer',
            inactiveIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0',
            inactiveBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600'
        },
        'category-summary': {
            heading: 'CATEGORY WISE EXPENSE & INFLOW SUMMARY',
            subtitle: 'Comprehensive categorized expense breakdown and inflow analysis.',
            filterType: null,
            activeBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 flex items-center gap-2 bg-[#a38c29] text-white shadow-xs cursor-pointer',
            activeIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center shrink-0',
            activeBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-black bg-[#8a7522] text-white',
            inactiveBtnClass: 'tab-btn px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer',
            inactiveIconClass: 'tab-icon-box w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0',
            inactiveBadgeClass: 'tab-badge px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600'
        }
    };

    function switchTab(tabId) {
        currentActiveTab = tabId;
        const config = tabConfigs[tabId] || tabConfigs['balance-register'];

        // Reset all tabs to inactive styling
        Object.keys(tabConfigs).forEach(id => {
            const btn = document.getElementById('tab-btn-' + id);
            if (!btn) return;
            btn.className = config.inactiveBtnClass;
            
            const iconBox = btn.querySelector('.tab-icon-box');
            if (iconBox) {
                iconBox.className = config.inactiveIconClass;
            }

            const badge = btn.querySelector('.tab-badge');
            if (badge) {
                badge.className = config.inactiveBadgeClass;
            }
        });

        // Set active tab styling (Gold Background, white text)
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.className = config.activeBtnClass;
            const iconBox = activeBtn.querySelector('.tab-icon-box');
            if (iconBox) {
                iconBox.className = config.activeIconClass;
            }
            const badge = activeBtn.querySelector('.tab-badge');
            if (badge) {
                badge.className = config.activeBadgeClass;
            }
        }

        const tableContainer = document.getElementById('ledgerTableContainer');
        const categoryContainer = document.getElementById('categorySummaryContainer');
        const heading = document.getElementById('currentTabHeading');
        const subtitle = document.getElementById('currentTabSubtitle');

        if (heading) {
            heading.innerText = config.heading;
        }
        if (subtitle) {
            subtitle.innerText = config.subtitle;
        }

        if (tabId === 'category-summary') {
            tableContainer.classList.add('hidden');
            categoryContainer.classList.remove('hidden');
        } else {
            tableContainer.classList.remove('hidden');
            categoryContainer.classList.add('hidden');
            filterTableByType(config.filterType);
        }
    }

    let activeTypeFilter = 'all';

    function filterTableByType(type) {
        activeTypeFilter = type;
        filterTable();
    }

    function filterTable() {
        const query = (document.getElementById('tableSearchInput')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.transaction-row');
        let count = 0;

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type');
            const voucher = row.getAttribute('data-voucher') || '';
            const particulars = row.getAttribute('data-particulars') || '';
            const reference = row.getAttribute('data-reference') || '';

            let matchType = (activeTypeFilter === 'all') || (activeTypeFilter === rowType);
            let matchQuery = !query || voucher.includes(query) || particulars.includes(query) || reference.includes(query);

            if (matchType && matchQuery) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        const tableCountSpan = document.getElementById('tableRecordCount');
        if (tableCountSpan) {
            tableCountSpan.innerText = count;
        }
        const countSpan = document.getElementById('visibleCount');
        if (countSpan) {
            countSpan.innerText = count;
        }
    }

    function showDetailModal(btn) {
        if (!btn || !btn.dataset) return;
        const d = btn.dataset;
        openDetailModal(d.voucher, d.date, d.particulars, d.cashin, d.cashout, d.balance, d.type, d.reference);
    }

    function openDetailModal(voucher, date, particulars, cashIn, cashOut, balance, type, reference) {
        try {
            const vEl = document.getElementById('modalVoucherNo');
            if (vEl) vEl.innerText = voucher || '—';

            const dEl = document.getElementById('modalDate');
            if (dEl) dEl.innerText = date || '—';

            const pEl = document.getElementById('modalParticulars');
            if (pEl) pEl.innerText = particulars || '—';
            
            const typeEl = document.getElementById('modalType');
            if (typeEl) {
                typeEl.innerHTML = `<span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider ${type === 'Contra' ? 'bg-emerald-100/90 text-emerald-800' : (type === 'Expense' ? 'bg-amber-100/90 text-amber-800' : 'bg-slate-100 text-slate-700')}">${type || '—'}</span>`;
            }

            const inEl = document.getElementById('modalCashIn');
            if (inEl) {
                const parsedIn = parseFloat(cashIn);
                inEl.innerText = (!isNaN(parsedIn) && parsedIn > 0) ? ('+₹' + numberFormat(parsedIn)) : '—';
            }

            const outEl = document.getElementById('modalCashOut');
            if (outEl) {
                const parsedOut = parseFloat(cashOut);
                outEl.innerText = (!isNaN(parsedOut) && parsedOut > 0) ? ('-₹' + numberFormat(parsedOut)) : '—';
            }

            const balEl = document.getElementById('modalBalance');
            if (balEl) {
                const parsedBal = parseFloat(balance);
                balEl.innerText = '₹' + numberFormat(!isNaN(parsedBal) ? parsedBal : 0);
            }

            const refEl = document.getElementById('modalReference');
            if (refEl) {
                refEl.innerText = (reference && reference !== '-') ? reference : '—';
            }

            const modal = document.getElementById('transactionDetailModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Error in openDetailModal:', err);
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

    function numberFormat(val) {
        return Number(val || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function exportToExcel() {
        const table = document.getElementById('pettyCashTable');
        if (!table) return;

        let csv = [];
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            if (row.style.display === 'none') return;
            let rowData = [];
            const cols = row.querySelectorAll('th, td');
            cols.forEach((col, idx) => {
                // Skip Action column
                if (idx === cols.length - 1 && col.innerText.trim() === 'ACTIONS') return;
                if (idx === cols.length - 1 && col.querySelector('button')) return;

                let text = col.innerText.trim().replace(/"/g, '""').replace(/\n/g, ' ');
                rowData.push('"' + text + '"');
            });
            if (rowData.length > 0) {
                csv.push(rowData.join(','));
            }
        });

        const csvContent = '\uFEFF' + csv.join('\r\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        const siteName = "{{ addslashes($currentProject->name ?? 'Site') }}".replace(/[^a-zA-Z0-9]/g, '_');
        link.setAttribute('href', url);
        link.setAttribute('download', `Petty_Cash_Report_${siteName}_{{ $reportData['from_date'] }}_to_{{ $reportData['to_date'] }}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<style>
@media print {
    body {
        background: #fff !important;
        font-size: 11px !important;
    }
    aside, nav, header {
        display: none !important;
    }
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    th, td {
        border: 1px solid #cbd5e1 !important;
        padding: 5px 8px !important;
    }
    th {
        background-color: #a38c29 !important;
        color: #fff !important;
    }
}
</style>
@endsection
