@extends('layouts.erp')

@section('content')
{{-- ExcelJS Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<div class="w-full px-6 py-6 bg-[#f8f9fa] min-h-screen font-sans">
    
    <!-- Breadcrumb & Top Action Header (Modern Clean Layout) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">HOME</a>
            <span class="text-slate-300">›</span>
            <span>PETTY CASH & SITE EXPENSE</span>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-black">PETTY CASH BALANCE REGISTER</span>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <button type="button" onclick="exportBalanceRegisterExcel()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>EXCEL REPORT</span>
            </button>
        </div>
    </div>

    <div id="petty-cash-content" class="relative">
        <script id="petty-cash-txns-data" type="application/json">@json($transactions)</script>
        <!-- 4 Metric KPI Cards Grid (Matches Partner Statements Design) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
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
                        ₹ {{ number_format($openingBalance, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">From Previous Day</p>
                </div>
            </div>

            <!-- Card 2: Cash In (Today) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Cash In (Today)</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        ₹ {{ number_format($cashIn, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Bank Withdrawals & Receipts</p>
                </div>
            </div>

            <!-- Card 3: Cash Out (Today) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Cash Out (Today)</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                        ₹ {{ number_format($cashOut, 2) }}
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
                        ₹ {{ number_format($closingBalance, 2) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Current Cash In Hand</p>
                </div>
            </div>
        </div>

        <!-- Summaries Section (Executive Rich Gold Theme) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Card 1: Petty Cash Summary -->
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 bg-gradient-to-r from-[#a38c29] to-[#8a7520] border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <h2 class="text-[11px] font-black text-white uppercase tracking-widest">PETTY CASH SUMMARY</h2>
                        </div>
                        <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Site Info</span>
                    </div>
                    <div class="p-5">
                        <table class="w-full text-xs">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Site</td>
                                    <td class="py-2.5 text-right font-bold text-slate-900 truncate max-w-[160px]" title="{{ $siteName }}">{{ $siteName }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Cash Box / Incharge</td>
                                    <td class="py-2.5 text-right font-bold text-slate-900">{{ $cashBoxIncharge }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Cash Box Code</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-slate-900">{{ $cashBoxCode }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Last Updated</td>
                                    <td class="py-2.5 text-right font-mono font-semibold text-slate-800 text-[11px]">{{ $lastUpdated }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <div class="p-3.5 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-wider text-[#8a7522]">UPDATED BY</div>
                            <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Responsible Officer</div>
                        </div>
                        <div class="text-sm font-mono font-black text-[#8a7522]">{{ $updatedBy }}</div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Today's Transaction Summary -->
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 bg-gradient-to-r from-[#a38c29] to-[#8a7520] border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <h2 class="text-[11px] font-black text-white uppercase tracking-widest">TRANSACTION FLOW</h2>
                        </div>
                        <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Today's Flow</span>
                    </div>
                    <div class="p-5">
                        <table class="w-full text-xs">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Bank Withdrawal (Contra)</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-[#10b981]">+ ₹ {{ number_format($bankWithdrawal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Site Expenses</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-[#ef4444]">- ₹ {{ number_format($siteExpenses, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Recorded Transactions</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-slate-800">{{ $transactions->count() }} Entries</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Audit Status</td>
                                    <td class="py-2.5 text-right font-bold text-emerald-700">Reconciled</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <div class="p-3.5 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-wider text-[#8a7522]">NET CASH FLOW</div>
                            <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Net movement today</div>
                        </div>
                        <div class="text-base font-mono font-black {{ $netCashFlow >= 0 ? 'text-[#10b981]' : 'text-[#ef4444]' }}">
                            {{ $netCashFlow >= 0 ? '+' : '' }} ₹ {{ number_format($netCashFlow, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Balance Snapshot -->
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 bg-gradient-to-r from-[#a38c29] to-[#8a7520] border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h2 class="text-[11px] font-black text-white uppercase tracking-widest">BALANCE SNAPSHOT</h2>
                        </div>
                        <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Live Balance</span>
                    </div>
                    <div class="p-5">
                        <table class="w-full text-xs">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Opening Balance</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-slate-900">₹ {{ number_format($openingBalance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Add: Cash In (Today)</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-[#10b981]">₹ {{ number_format($cashIn, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Less: Cash Out (Today)</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-[#ef4444]">₹ {{ number_format($cashOut, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 font-semibold text-slate-600">Register Audit Date</td>
                                    <td class="py-2.5 text-right font-mono font-semibold text-slate-800 text-[11px]">{{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('d-M-Y') : date('d-M-Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <div class="p-3.5 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-wider text-[#8a7522]">CLOSING BALANCE</div>
                            <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Current cash in hand</div>
                        </div>
                        <div class="text-lg font-mono font-black text-[#8a7522]">₹ {{ number_format($closingBalance, 2) }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Filter Area (Positioned directly above the data table) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 mb-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
            <form method="GET" action="{{ route('petty-cash.balance-register') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full m-0" id="filter-form">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
                    {{-- Pro Light Search Input --}}
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Voucher..." 
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-sm">
                    </div>

                    {{-- Site Dropdown --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <select name="project_id"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm appearance-none">
                            <option value="" {{ request('project_id') === '' || (request()->has('project_id') && !request('project_id')) ? 'selected' : '' }}>All Sites</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <select name="status"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm appearance-none">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Date Filter --}}
                    <div class="relative">
                        <input type="date" name="date" value="{{ $selectedDate }}"
                               class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-sm appearance-none">
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('petty-cash.balance-register') }}"
                       class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer whitespace-nowrap group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Recent Transactions Table (Theme matched) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8 relative">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-[14px] font-extrabold text-[#a38c29] uppercase tracking-wider">Recent Transactions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-[#a38c29] text-white">
                        <tr>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Date</th>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Voucher No.</th>
                            <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-wide">Type</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Cash In (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Cash Out (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide">Balance (₹)</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide">Reference</th>
                            <th class="px-5 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($transactions as $txn)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-[11px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($txn->date)->format('d-M-Y') }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-[11px] font-bold text-[#a38c29] uppercase">{{ $txn->voucher_number }}</span>
                                </td>
                                <td class="px-5 py-4 text-[11px] font-bold text-[#1e2a5e]">{{ $txn->type_label }}</td>
                                <td class="px-5 py-4 text-right text-[11px] font-bold text-[#1e2a5e]">
                                    {{ $txn->cash_in > 0 ? number_format($txn->cash_in, 2) : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right text-[11px] font-bold text-[#1e2a5e]">
                                    {{ $txn->cash_out > 0 ? number_format($txn->cash_out, 2) : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right text-[11px] font-bold text-[#1e2a5e]">
                                    {{ number_format($txn->balance, 2) }}
                                </td>
                                <td class="px-5 py-4 text-left text-[11px] font-medium text-gray-500">
                                    {{ $txn->reference ?: '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button type="button" 
                                            onclick="showTransactionModal(this)" 
                                            data-voucher="{{ $txn->voucher_number }}"
                                            data-date="{{ \Carbon\Carbon::parse($txn->date)->format('d-M-Y') }}"
                                            data-type="{{ $txn->type_label }}"
                                            data-cashin="{{ $txn->cash_in > 0 ? number_format($txn->cash_in, 2) : '0.00' }}"
                                            data-cashout="{{ $txn->cash_out > 0 ? number_format($txn->cash_out, 2) : '0.00' }}"
                                            data-balance="{{ number_format($txn->balance, 2) }}"
                                            data-reference="{{ $txn->reference ?: 'N/A' }}"
                                            class="text-[#a38c29] hover:text-[#8a7522] transition-colors focus:outline-none" title="View Transaction Details">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-[12px] font-bold text-gray-400 uppercase tracking-wider">No transactions found for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            const contentContainer = document.getElementById('petty-cash-content');

            if (filterForm && contentContainer) {
                let fetchController = null;

                function fetchData() {
                    const url = new URL(filterForm.action);
                    const formData = new FormData(filterForm);
                    const searchParams = new URLSearchParams(formData);
                    url.search = searchParams.toString();

                    if (fetchController) {
                        fetchController.abort();
                    }
                    fetchController = new AbortController();

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: fetchController.signal
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newContent = doc.getElementById('petty-cash-content');
                        if (newContent) {
                            contentContainer.innerHTML = newContent.innerHTML;
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Error fetching data:', error);
                        }
                    });
                }

                // Debounce helper
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func(...args), wait);
                    };
                }

                const debouncedFetch = debounce(fetchData, 400);

                // Auto-fetch on change
                document.body.addEventListener('change', function(e) {
                    if (e.target.closest('#filter-form') && (e.target.tagName === 'SELECT' || e.target.type === 'date')) {
                        fetchData();
                    }
                });

                document.body.addEventListener('input', function(e) {
                    if (e.target.closest('#filter-form') && e.target.name === 'search') {
                        debouncedFetch();
                    }
                });

                document.body.addEventListener('submit', function(e) {
                    if (e.target.id === 'filter-form') {
                        e.preventDefault();
                        fetchData();
                    }
                });
                
                document.body.addEventListener('reset', function(e) {
                    if (e.target.closest('#filter-form')) {
                        setTimeout(() => fetchData(), 10);
                    }
                });
            }
        });

        // Modal Functions
        function showTransactionModal(btn) {
            document.getElementById('modal-voucher').innerText = btn.getAttribute('data-voucher');
            document.getElementById('modal-date').innerText = btn.getAttribute('data-date');
            document.getElementById('modal-type').innerText = btn.getAttribute('data-type');
            
            const cashIn = btn.getAttribute('data-cashin');
            document.getElementById('modal-cashin').innerText = cashIn !== '0.00' ? '+ ₹ ' + cashIn : '-';
            
            const cashOut = btn.getAttribute('data-cashout');
            document.getElementById('modal-cashout').innerText = cashOut !== '0.00' ? '- ₹ ' + cashOut : '-';
            
            document.getElementById('modal-balance').innerText = '₹ ' + btn.getAttribute('data-balance');
            document.getElementById('modal-reference').innerText = btn.getAttribute('data-reference');

            const modal = document.getElementById('transaction-modal');
            const modalContent = document.getElementById('transaction-modal-content');
            
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }

        function closeTransactionModal() {
            const modal = document.getElementById('transaction-modal');
            const modalContent = document.getElementById('transaction-modal-content');
            
            if (modalContent) {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }

        // Close modal on Escape key press
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('transaction-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeTransactionModal();
                }
            }
        });

        // ExcelJS Export implementation matching executive design
        async function exportBalanceRegisterExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Hindustan ERP';
                workbook.lastModifiedBy = 'Hindustan ERP';
                workbook.created = new Date();
                workbook.modified = new Date();

                const worksheet = workbook.addWorksheet('Report Ledger', {
                    views: [{ showGridLines: true }]
                });

                // ── 1. Column Definitions ──
                const totalCols = 8;
                worksheet.columns = [
                    { key: 'sl', width: 8 },          // Col 1: SL NO
                    { key: 'date', width: 16 },        // Col 2: Date
                    { key: 'voucher', width: 22 },     // Col 3: Voucher No.
                    { key: 'particulars', width: 38 }, // Col 4: Type / Particulars
                    { key: 'cash_in', width: 20 },     // Col 5: Cash In (₹)
                    { key: 'cash_out', width: 20 },    // Col 6: Cash Out (₹)
                    { key: 'balance', width: 20 },     // Col 7: Balance (₹)
                    { key: 'reference', width: 24 }    // Col 8: Reference
                ];

                // ── 2. Spacing Row 1 ──
                worksheet.getRow(1).height = 15;

                // ── 3. Banner 1: Company / Report Title (Row 2) ──
                const row2 = worksheet.getRow(2);
                row2.height = 32;
                worksheet.mergeCells('A2:H2');
                const titleCell = worksheet.getCell('A2');
                titleCell.value = 'HINDUSTAN ERP : PETTY CASH BALANCE REGISTER';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
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

                // ── 4. Banner 2: Subtitle / Context (Row 3) ──
                const row3 = worksheet.getRow(3);
                row3.height = 24;
                worksheet.mergeCells('A3:H3');
                const subCell = worksheet.getCell('A3');
                subCell.value = 'Petty Cash & Site Expense Balance Audit';
                subCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                subCell.alignment = { horizontal: 'center', vertical: 'middle' };
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

                // ── 5. Banner 3: Transaction Details (Row 4) ──
                const row4 = worksheet.getRow(4);
                row4.height = 24;
                worksheet.mergeCells('A4:H4');
                const bannerCell = worksheet.getCell('A4');
                bannerCell.value = 'TRANSACTION DETAILS';
                bannerCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                bannerCell.alignment = { horizontal: 'center', vertical: 'middle' };
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

                // ── 7. Table Column Headers (Row 6) ──
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
                    cell.alignment = {
                        horizontal: (c === 5 || c === 6 || c === 7 ? 'right' : (c === 4 || c === 8 ? 'left' : 'center')),
                        vertical: 'middle',
                        indent: (c === 4 || c === 8 ? 1 : 0)
                    };
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
                const dataScript = document.getElementById('petty-cash-txns-data');
                if (dataScript) {
                    try {
                        txnsData = JSON.parse(dataScript.textContent || '[]');
                    } catch (e) {
                        console.error('Error parsing embedded data:', e);
                    }
                }

                // Fallback to table DOM rows if dataScript was empty
                if (!txnsData || txnsData.length === 0) {
                    const tableRows = document.querySelectorAll('#petty-cash-content table tbody tr');
                    tableRows.forEach(tr => {
                        const tds = tr.querySelectorAll('td');
                        if (tds.length >= 7 && !tr.innerText.includes('No transactions found')) {
                            const btn = tr.querySelector('button[data-voucher]');
                            if (btn) {
                                txnsData.push({
                                    date: btn.getAttribute('data-date'),
                                    voucher_number: btn.getAttribute('data-voucher'),
                                    type_label: btn.getAttribute('data-type'),
                                    cash_in: parseFloat(btn.getAttribute('data-cashin').replace(/,/g, '')) || 0,
                                    cash_out: parseFloat(btn.getAttribute('data-cashout').replace(/,/g, '')) || 0,
                                    balance: parseFloat(btn.getAttribute('data-balance').replace(/,/g, '')) || 0,
                                    reference: btn.getAttribute('data-reference') !== 'N/A' ? btn.getAttribute('data-reference') : '-'
                                });
                            }
                        }
                    });
                }

                let currentRowIdx = 7;
                let lastBalance = 0;

                txnsData.forEach((txn, index) => {
                    const rowNum = index + 1;
                    const cashInVal = parseFloat(txn.cash_in) || 0;
                    const cashOutVal = parseFloat(txn.cash_out) || 0;
                    const balanceVal = parseFloat(txn.balance) || 0;
                    lastBalance = balanceVal;

                    let formattedDate = txn.date || '-';
                    if (formattedDate.includes('-') && formattedDate.length === 10) {
                        const parts = formattedDate.split('-');
                        if (parts[0].length === 4) {
                            formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                        }
                    }

                    const dataRow = worksheet.getRow(currentRowIdx);
                    dataRow.values = [
                        rowNum,
                        formattedDate,
                        txn.voucher_number || '-',
                        txn.type_label || '-',
                        cashInVal > 0 ? cashInVal : 0,
                        cashOutVal > 0 ? cashOutVal : 0,
                        balanceVal,
                        (txn.reference && txn.reference !== 'N/A') ? txn.reference : '-'
                    ];
                    dataRow.height = 25;

                    const isEven = (index + 1) % 2 === 0;
                    const rowBg = isEven ? 'FFFFFFFF' : 'FFF0F8FF';

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

                        if (c === 1 || c === 2) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else if (c === 3) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        } else if (c === 4) {
                            cell.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };
                        } else if (c === 5) {
                            cell.alignment = { horizontal: 'right', vertical: 'middle' };
                            cell.numFormat = '#,##0.00';
                            if (cashInVal > 0) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF008000' } };
                            }
                        } else if (c === 6) {
                            cell.alignment = { horizontal: 'right', vertical: 'middle' };
                            cell.numFormat = '#,##0.00';
                            if (cashOutVal > 0) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFDC2626' } };
                            }
                        } else if (c === 7) {
                            cell.alignment = { horizontal: 'right', vertical: 'middle' };
                            cell.numFormat = '#,##0.00';
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1E293B' } };
                        } else if (c === 8) {
                            cell.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };
                        }
                    }

                    currentRowIdx++;
                });

                // ── 9. Bottom Summary / Total Row (Row 7 + N) (Matching Image 2) ──
                const totalRow = worksheet.getRow(currentRowIdx);
                totalRow.height = 36;
                worksheet.mergeCells(`A${currentRowIdx}:F${currentRowIdx}`);

                const totalLabelCell = worksheet.getCell(`A${currentRowIdx}`);
                totalLabelCell.value = 'TOTAL CLOSING BALANCE';
                totalLabelCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                totalLabelCell.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                const totalValCell = worksheet.getCell(`G${currentRowIdx}`);
                totalValCell.value = lastBalance;
                totalValCell.numFormat = '#,##0.00';
                totalValCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                totalValCell.alignment = { horizontal: 'right', vertical: 'middle' };

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
                const todayStr = new Date().toISOString().split('T')[0].replace(/-/g, '');
                anchor.download = `HindustanERP_PettyCash_BalanceRegister_${todayStr}.xlsx`;
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

    <!-- Transaction Details Modal -->
    <div id="transaction-modal" 
         class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/80 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 cursor-pointer" 
         onclick="if(event.target === this) closeTransactionModal()">
        <div class="bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl mx-auto transform transition-all scale-95 opacity-0 overflow-hidden border-0 cursor-default" 
             id="transaction-modal-content" 
             onclick="event.stopPropagation()">
            <!-- Header (Premium Dark Theme matching collection forecast) -->
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">PETTY CASH & SITE EXPENSE</span>
                        <h3 class="font-black text-base uppercase tracking-wider text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            TRANSACTION DETAILS
                        </h3>
                    </div>
                    <button type="button" onclick="closeTransactionModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 bg-white space-y-5 font-sans text-xs">
                
                <!-- Section 1: Overview Grid Details Card -->
                <div class="p-4 bg-gradient-to-r from-amber-50/60 via-slate-50 to-amber-50/40 rounded-xl border border-amber-200/70 shadow-2xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 w-28 shrink-0">Voucher No.</span>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-mono font-black text-xs rounded-lg border border-blue-200/80 uppercase" id="modal-voucher"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 w-28 shrink-0">Transaction Date</span>
                                <span class="font-bold text-slate-800 font-mono text-xs" id="modal-date"></span>
                            </div>
                        </div>
                        <div class="space-y-2.5">
                            <div class="flex items-start gap-2">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 w-28 shrink-0 pt-0.5">Type / Particulars</span>
                                <span class="font-bold text-slate-900 leading-tight" id="modal-type"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 w-28 shrink-0">Reference No.</span>
                                <span class="font-mono font-semibold text-slate-600 text-xs" id="modal-reference"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Financial Stat Cards (3 Columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- Cash In -->
                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-3.5 text-center transition-all hover:shadow-sm">
                        <div class="text-[9.5px] font-extrabold text-emerald-700 uppercase tracking-wider flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Cash In
                        </div>
                        <div class="text-base font-black text-emerald-700 font-mono mt-1" id="modal-cashin">-</div>
                    </div>

                    <!-- Cash Out -->
                    <div class="bg-rose-50/70 border border-rose-200/80 rounded-xl p-3.5 text-center transition-all hover:shadow-sm">
                        <div class="text-[9.5px] font-extrabold text-rose-600 uppercase tracking-wider flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Cash Out
                        </div>
                        <div class="text-base font-black text-rose-700 font-mono mt-1" id="modal-cashout">-</div>
                    </div>

                    <!-- Running Balance -->
                    <div class="bg-slate-50 border border-slate-200/90 rounded-xl p-3.5 text-center transition-all hover:shadow-sm">
                        <div class="text-[9.5px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                            Running Balance
                        </div>
                        <div class="text-base font-black text-slate-900 font-mono mt-1" id="modal-balance">-</div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-0 rounded-b-2xl flex items-center justify-end">
                <button type="button" onclick="closeTransactionModal()" class="px-6 py-2.5 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer active:scale-95">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection