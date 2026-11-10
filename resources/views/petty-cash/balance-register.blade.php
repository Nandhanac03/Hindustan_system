@extends('layouts.erp')

@section('content')
{{-- ExcelJS Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

@php
    $selectedSiteId = request('project_id', $selectedProject ?? '');
    $selectedSiteObj = $selectedSiteId ? $projects->firstWhere('id', $selectedSiteId) : null;
    $selectedSiteName = $selectedSiteObj ? $selectedSiteObj->name : 'All Sites';
@endphp

<div class="w-full px-6 py-6 bg-[#f8f9fa] min-h-screen font-sans" x-data="pettyCashRegisterApp()">
    
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
        <div id="petty-cash-metrics" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
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

        <!-- Summaries Section (Executive Theme with ERP Table Header Color) -->
        <div id="petty-cash-summaries" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Card 1: Petty Cash Summary -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 erp-table-header border-b border-slate-700 text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
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
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 erp-table-header border-b border-slate-700 text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
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
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-5 py-3.5 erp-table-header border-b border-slate-700 text-white flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                                    <td class="py-2.5 text-right font-mono font-semibold text-slate-800 text-[11px]">
                                        @if(!empty($financialYear) && $financialYear !== 'All' && $financialYear !== 'Custom')
                                            FY {{ $financialYear }}
                                        @elseif(!empty($fromDate) && !empty($toDate))
                                            {{ \Carbon\Carbon::parse($fromDate)->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d-M-Y') }}
                                        @elseif(!empty($fromDate))
                                            From {{ \Carbon\Carbon::parse($fromDate)->format('d-M-Y') }}
                                        @elseif(!empty($toDate))
                                            Up to {{ \Carbon\Carbon::parse($toDate)->format('d-M-Y') }}
                                        @else
                                            {{ date('d-M-Y') }}
                                        @endif
                                    </td>
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

        <!-- Filter Area (Single Row Compact Alignment with Financial Year Selector) -->
        <style>
            @media (min-width: 1024px) {
                .br-filter-bar {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    align-items: center !important;
                    gap: 0.45rem !important;
                }
                .br-filter-grid {
                    display: grid !important;
                    grid-template-columns: minmax(120px, 1.15fr) minmax(115px, 1.05fr) minmax(100px, 0.95fr) minmax(105px, 0.92fr) minmax(105px, 0.92fr) minmax(100px, 0.9fr) !important;
                    gap: 0.45rem !important;
                    flex: 1 1 0% !important;
                    min-width: 0 !important;
                    align-items: center !important;
                }
            }
            .br-filter-bar .erp-search-input,
            .br-filter-bar .erp-dropdown-trigger,
            .br-filter-bar input[type="date"],
            .br-filter-bar .br-reset-btn {
                height: 38px !important;
                min-height: 38px !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                font-size: 11px !important;
                box-sizing: border-box !important;
            }
        </style>
        <div class="br-filter-bar bg-white rounded-2xl border border-slate-200/90 p-3 mb-6 shadow-sm flex flex-wrap lg:flex-nowrap items-center justify-between gap-2 transition-all">
            <div class="br-filter-grid grid grid-cols-1 sm:grid-cols-2 items-center gap-2 flex-1 min-w-0 w-full">
                {{-- Search Input --}}
                <div class="relative group min-w-0">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" @input.debounce.350ms="updateFilters()" placeholder="Search Voucher..." 
                           class="w-full erp-search-input pl-8 pr-7 !text-[11px] !h-[38px]">
                    <div x-show="searchQuery" class="absolute inset-y-0 right-0 pr-2 flex items-center" style="display: none;">
                        <button type="button" @click="searchQuery = ''; updateFilters()"
                                class="p-0.5 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Site Dropdown (Custom Gold Popover) --}}
                <div class="relative w-full min-w-0" @click.outside="projectDropdownOpen = false">
                    <input type="hidden" name="project_id" :value="selectedProjectId">
                    <button type="button"
                            @click="projectDropdownOpen = !projectDropdownOpen; if(projectDropdownOpen) { fyDropdownOpen = false; statusDropdownOpen = false; $nextTick(() => $refs.projSearchInput?.focus()); }"
                            class="erp-dropdown-trigger !px-2.5 !h-[38px]"
                            :class="projectDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="truncate text-[11px] font-bold"
                                  :class="selectedProjectId ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="selectedProjectName"></span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <template x-if="selectedProjectId">
                                <span @click.stop="selectProject('', 'All Sites')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3 h-3 text-[#a38c29] transition-transform duration-200" :class="projectDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    {{-- Project Popover Menu --}}
                    <div x-show="projectDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover min-w-[240px]"
                         style="display: none;">
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="projectSearch" x-ref="projSearchInput" placeholder="Search site..."
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="projectDropdownOpen = false">
                                <template x-if="projectSearch">
                                    <button type="button" @click="projectSearch = ''; $refs.projSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectProject('', 'All Sites')"
                                 x-show="!projectSearch || 'All Sites'.toLowerCase().includes(projectSearch.toLowerCase())"
                                 class="erp-dropdown-option"
                                 :class="!selectedProjectId ? 'selected-all' : ''">
                                <span>All Sites</span>
                            </div>
                            @foreach($projects as $p)
                                <div @click="selectProject('{{ $p->id }}', '{{ addslashes($p->name) }}')"
                                     x-show="!projectSearch || '{{ strtolower(addslashes($p->name)) }}'.includes(projectSearch.toLowerCase())"
                                     class="erp-dropdown-option"
                                     :class="String(selectedProjectId) === '{{ (string)$p->id }}' ? 'selected' : ''">
                                    <span class="truncate">{{ $p->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Financial Year (FY) Filter Dropdown (Custom Gold Popover) --}}
                <div class="relative w-full min-w-0" @click.outside="fyDropdownOpen = false">
                    <button type="button"
                            @click="fyDropdownOpen = !fyDropdownOpen; if(fyDropdownOpen) { projectDropdownOpen = false; statusDropdownOpen = false; }"
                            class="erp-dropdown-trigger !px-2.5 !h-[38px]"
                            :class="fyDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="truncate text-[11px] font-bold"
                                  :class="(selectedFinancialYear && selectedFinancialYear !== 'All') ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="getSelectedFyName()">All FY</span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <template x-if="selectedFinancialYear && selectedFinancialYear !== 'All'">
                                <span @click.stop="selectFinancialYear('All')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear Financial Year">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3 h-3 text-[#a38c29] transition-transform duration-200" :class="fyDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    {{-- Financial Year Popover Menu --}}
                    <div x-show="fyDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover min-w-[200px]"
                         style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-60">
                            <div @click="selectFinancialYear('All')"
                                 class="erp-dropdown-option"
                                 :class="(!selectedFinancialYear || selectedFinancialYear === 'All') ? 'selected-all' : ''">
                                <span>All Financial Years</span>
                            </div>
                            <template x-for="fy in financialYearsList" :key="fy.value">
                                <div @click="selectFinancialYear(fy.value)"
                                     class="erp-dropdown-option flex items-center justify-between"
                                     :class="selectedFinancialYear === fy.value ? 'selected' : ''">
                                    <span x-text="fy.label"></span>
                                    <span x-show="fy.range" class="text-[10px] text-slate-400 font-mono ml-2" x-text="fy.range"></span>
                                </div>
                            </template>
                            <div @click="selectFinancialYear('Custom')"
                                 class="erp-dropdown-option"
                                 :class="selectedFinancialYear === 'Custom' ? 'selected' : ''">
                                <span>Custom Date Range</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- From Date --}}
                <div class="relative min-w-0">
                    <input type="date" id="brFromDateInput" x-model="fromDate" @change="onCustomDateChange()"
                           class="w-full px-2.5 h-[38px] bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-[11px] font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- To Date --}}
                <div class="relative min-w-0">
                    <input type="date" id="brToDateInput" x-model="toDate" @change="onCustomDateChange()"
                           class="w-full px-2.5 h-[38px] bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-[11px] font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- Status Dropdown (Custom Gold Popover) --}}
                <div class="relative w-full min-w-0" @click.outside="statusDropdownOpen = false">
                    <input type="hidden" name="status" :value="selectedStatus">
                    <button type="button"
                            @click="statusDropdownOpen = !statusDropdownOpen; if(statusDropdownOpen) { projectDropdownOpen = false; fyDropdownOpen = false; }"
                            class="erp-dropdown-trigger !px-2.5 !h-[38px]"
                            :class="statusDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            <span class="truncate text-[11px] font-bold"
                                  :class="selectedStatus ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="getStatusLabel()">All Statuses</span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <template x-if="selectedStatus">
                                <span @click.stop="selectStatus('')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3 h-3 text-[#a38c29] transition-transform duration-200" :class="statusDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    {{-- Status Popover Menu --}}
                    <div x-show="statusDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full"
                         style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectStatus('')"
                                 class="erp-dropdown-option"
                                 :class="!selectedStatus ? 'selected-all' : ''">
                                <span>All Statuses</span>
                            </div>
                            <div @click="selectStatus('active')"
                                 class="erp-dropdown-option"
                                 :class="selectedStatus === 'active' ? 'selected' : ''">
                                <span>Active</span>
                            </div>
                            <div @click="selectStatus('pending')"
                                 class="erp-dropdown-option"
                                 :class="selectedStatus === 'pending' ? 'selected' : ''">
                                <span>Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="br-reset-btn inline-flex items-center justify-center gap-1.5 rounded-xl theme-btn px-4 py-2 h-[38px] text-[11px] font-extrabold text-white shadow-xs transition duration-200 flex-shrink-0 uppercase tracking-wider group cursor-pointer active:scale-95 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-white transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>
        </div>

        <!-- Recent Transactions Table Container (Designed identical to Sales Return / Cancellation module) -->
        <div id="petty-cash-table-container" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col w-full mb-6">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-[11px] border-collapse whitespace-nowrap">
                    <thead class="sales-desk-table-header text-white uppercase tracking-wider text-[10px] font-bold">
                        <tr class="sales-desk-table-header border-b">
                            <th class="px-4 py-3.5 text-left sales-desk-table-header">Date</th>
                            <th class="px-4 py-3.5 text-left sales-desk-table-header">Voucher No.</th>
                            <th class="px-4 py-3.5 text-left sales-desk-table-header">Type</th>
                            <th class="px-4 py-3.5 text-right sales-desk-table-header">Cash In (₹)</th>
                            <th class="px-4 py-3.5 text-right sales-desk-table-header">Cash Out (₹)</th>
                            <th class="px-4 py-3.5 text-right sales-desk-table-header">Balance (₹)</th>
                            <th class="px-4 py-3.5 text-left sales-desk-table-header">Reference</th>
                            <th class="px-4 py-3.5 text-center sales-desk-table-header">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-705">
                        <template x-for="txn in paginatedTransactions()" :key="txn.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3.5 text-slate-500 font-bold text-left" x-text="txn.date_formatted || txn.date"></td>
                                <td class="px-4 py-3.5 text-left">
                                    <span class="text-primary font-bold uppercase tracking-wide" x-text="txn.voucher_number"></span>
                                </td>
                                <td class="px-4 py-3.5 text-left font-bold text-slate-900" x-text="txn.type_label" :title="txn.type_label"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold" 
                                    :class="txn.cash_in > 0 ? 'text-emerald-600' : 'text-slate-400'" 
                                    x-text="txn.cash_in > 0 ? ('₹ ' + Number(txn.cash_in).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})) : '—'"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold" 
                                    :class="txn.cash_out > 0 ? 'text-rose-600' : 'text-slate-400'" 
                                    x-text="txn.cash_out > 0 ? ('₹ ' + Number(txn.cash_out).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})) : '—'"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900" 
                                    x-text="'₹ ' + Number(txn.balance).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></td>
                                <td class="px-4 py-3.5 text-left font-medium text-slate-500" x-text="txn.reference || '—'"></td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button" 
                                            @click="showTransactionModalFromData(txn)" 
                                            class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" 
                                            title="View Transaction Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="transactions.length === 0">
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic font-medium">No transactions found for this date.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination Controls (Exact Match to Sales Return / Cancellation module) --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between" x-show="transactions.length > 0">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900" x-text="showingFrom()"></span> TO 
                    <span class="text-slate-900" x-text="showingTo()"></span> OF 
                    <span class="text-slate-900" x-text="transactions.length"></span> TRANSACTIONS
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage <= 1"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        PREV
                    </button>
                    
                    {{-- Page Numbers --}}
                    <template x-for="p in getPageNumbers()" :key="p">
                        <span class="inline-flex items-center gap-1">
                            <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                            <button type="button" x-show="p !== '...'"
                                    @click="currentPage = p"
                                    x-text="p"
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs"
                                    :class="currentPage === p ? 'bg-primary text-white border border-primary' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                        </span>
                    </template>
                    
                    <button type="button" @click="if(currentPage < getTotalPages()) currentPage++" 
                            :disabled="currentPage >= getTotalPages()"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pettyCashRegisterApp() {
            return {
                searchQuery: '{{ addslashes(request('search', '')) }}',
                selectedProjectId: '{{ $selectedSiteId }}',
                selectedProjectName: '{{ addslashes($selectedSiteName) }}',
                projectDropdownOpen: false,
                projectSearch: '',
                selectedFinancialYear: '{{ $financialYear ?? request('financial_year', 'All') }}',
                fyDropdownOpen: false,
                fromDate: '{{ $fromDate ?? request('from_date', '') }}',
                toDate: '{{ $toDate ?? request('to_date', '') }}',
                financialYearsList: [
                    { value: '2026-27', label: 'FY 2026-27', range: 'Apr 2026 - Mar 2027', from: '2026-04-01', to: '2027-03-31' },
                    { value: '2025-26', label: 'FY 2025-26', range: 'Apr 2025 - Mar 2026', from: '2025-04-01', to: '2026-03-31' },
                    { value: '2024-25', label: 'FY 2024-25', range: 'Apr 2024 - Mar 2025', from: '2024-04-01', to: '2025-03-31' },
                    { value: '2023-24', label: 'FY 2023-24', range: 'Apr 2023 - Mar 2024', from: '2023-04-01', to: '2024-03-31' },
                    { value: '2022-23', label: 'FY 2022-23', range: 'Apr 2022 - Mar 2023', from: '2022-04-01', to: '2023-03-31' },
                    { value: '2021-22', label: 'FY 2021-22', range: 'Apr 2021 - Mar 2022', from: '2021-04-01', to: '2022-03-31' },
                    { value: '2020-21', label: 'FY 2020-21', range: 'Apr 2020 - Mar 2021', from: '2020-04-01', to: '2021-03-31' },
                    { value: '2019-20', label: 'FY 2019-20', range: 'Apr 2019 - Mar 2020', from: '2019-04-01', to: '2020-03-31' },
                    { value: '2018-19', label: 'FY 2018-19', range: 'Apr 2018 - Mar 2019', from: '2018-04-01', to: '2019-03-31' },
                    { value: '2017-18', label: 'FY 2017-18', range: 'Apr 2017 - Mar 2018', from: '2017-04-01', to: '2018-03-31' },
                    { value: '2016-17', label: 'FY 2016-17', range: 'Apr 2016 - Mar 2017', from: '2016-04-01', to: '2017-03-31' },
                    { value: '2015-16', label: 'FY 2015-16', range: 'Apr 2015 - Mar 2016', from: '2015-04-01', to: '2016-03-31' },
                    { value: '2014-15', label: 'FY 2014-15', range: 'Apr 2014 - Mar 2015', from: '2014-04-01', to: '2015-03-31' },
                ],
                selectedStatus: '{{ request('status', '') }}',
                statusDropdownOpen: false,
                selectedDate: '{{ $selectedDate }}',
                isLoading: false,
                transactions: @js($transactions),
                currentPage: 1,
                perPage: 10,

                paginatedTransactions() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.transactions.slice(start, start + this.perPage);
                },

                getTotalPages() {
                    return Math.ceil(this.transactions.length / this.perPage) || 1;
                },

                getPageNumbers() {
                    const totalPages = this.getTotalPages();
                    const current = this.currentPage;
                    const pages = [];
                    if (totalPages <= 7) {
                        for (let i = 1; i <= totalPages; i++) pages.push(i);
                    } else {
                        if (current <= 4) {
                            for (let i = 1; i <= 5; i++) pages.push(i);
                            pages.push('...');
                            pages.push(totalPages);
                        } else if (current >= totalPages - 3) {
                            pages.push(1);
                            pages.push('...');
                            for (let i = totalPages - 4; i <= totalPages; i++) pages.push(i);
                        } else {
                            pages.push(1);
                            pages.push('...');
                            for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                            pages.push('...');
                            pages.push(totalPages);
                        }
                    }
                    return pages;
                },

                showingFrom() {
                    if (this.transactions.length === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },

                showingTo() {
                    return Math.min(this.currentPage * this.perPage, this.transactions.length);
                },

                showTransactionModalFromData(txn) {
                    document.getElementById('modal-voucher').innerText = txn.voucher_number || '-';
                    document.getElementById('modal-date').innerText = txn.date_formatted || txn.date || '-';
                    document.getElementById('modal-type').innerText = txn.type_label || '-';
                    
                    const cashIn = parseFloat(txn.cash_in) || 0;
                    document.getElementById('modal-cashin').innerText = cashIn > 0 ? '+ ₹ ' + Number(cashIn).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-';
                    
                    const cashOut = parseFloat(txn.cash_out) || 0;
                    document.getElementById('modal-cashout').innerText = cashOut > 0 ? '- ₹ ' + Number(cashOut).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-';
                    
                    document.getElementById('modal-balance').innerText = '₹ ' + Number(parseFloat(txn.balance) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('modal-reference').innerText = (txn.reference && txn.reference !== 'N/A') ? txn.reference : '-';

                    const modal = document.getElementById('transaction-modal');
                    const modalContent = document.getElementById('transaction-modal-content');
                    
                    modal.classList.remove('hidden');
                    void modal.offsetWidth;
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                },

                selectProject(id, name) {
                    this.selectedProjectId = id;
                    this.selectedProjectName = name;
                    this.projectDropdownOpen = false;
                    this.$nextTick(() => {
                        this.updateFilters();
                    });
                },

                getSelectedFyName() {
                    if (!this.selectedFinancialYear || this.selectedFinancialYear === 'All') return 'All FY';
                    if (this.selectedFinancialYear === 'Custom') return 'Custom Date';
                    const found = this.financialYearsList.find(x => x.value === this.selectedFinancialYear);
                    return found ? found.label : ('FY ' + this.selectedFinancialYear);
                },

                selectFinancialYear(fyVal) {
                    this.selectedFinancialYear = fyVal;
                    this.fyDropdownOpen = false;
                    this.selectedDate = '';
                    if (fyVal === 'All') {
                        this.fromDate = '';
                        this.toDate = '';
                        this.$nextTick(() => this.updateFilters());
                    } else if (fyVal === 'Custom') {
                        const fromEl = document.getElementById('brFromDateInput');
                        if (fromEl) fromEl.focus();
                        if (this.fromDate || this.toDate) {
                            this.$nextTick(() => this.updateFilters());
                        }
                    } else {
                        const found = this.financialYearsList.find(x => x.value === fyVal);
                        if (found) {
                            this.fromDate = found.from;
                            this.toDate = found.to;
                        }
                        this.$nextTick(() => this.updateFilters());
                    }
                },

                onCustomDateChange() {
                    this.selectedDate = '';
                    if (this.fromDate || this.toDate) {
                        const matchedFy = this.financialYearsList.find(
                            x => x.from === this.fromDate && x.to === this.toDate
                        );
                        this.selectedFinancialYear = matchedFy ? matchedFy.value : 'Custom';
                    } else {
                        this.selectedFinancialYear = 'All';
                    }
                    this.$nextTick(() => this.updateFilters());
                },

                selectStatus(s) {
                    this.selectedStatus = s;
                    this.statusDropdownOpen = false;
                    this.$nextTick(() => {
                        this.updateFilters();
                    });
                },

                getStatusLabel() {
                    if (!this.selectedStatus) return 'All Statuses';
                    return this.selectedStatus === 'active' ? 'Active' : (this.selectedStatus === 'pending' ? 'Pending' : this.selectedStatus);
                },

                updateFilters() {
                    this.isLoading = true;
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.selectedProjectId) params.append('project_id', this.selectedProjectId);
                    if (this.selectedFinancialYear) params.append('financial_year', this.selectedFinancialYear);
                    if (this.fromDate) params.append('from_date', this.fromDate);
                    if (this.toDate) params.append('to_date', this.toDate);
                    if (this.selectedStatus) params.append('status', this.selectedStatus);
                    if (this.selectedDate) params.append('date', this.selectedDate);

                    const baseUrl = '{{ route('petty-cash.balance-register') }}';
                    const fetchUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');

                    fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newMetrics = doc.getElementById('petty-cash-metrics');
                        const curMetrics = document.getElementById('petty-cash-metrics');
                        if (newMetrics && curMetrics) {
                            curMetrics.innerHTML = newMetrics.innerHTML;
                        }

                        const newSummaries = doc.getElementById('petty-cash-summaries');
                        const curSummaries = document.getElementById('petty-cash-summaries');
                        if (newSummaries && curSummaries) {
                            curSummaries.innerHTML = newSummaries.innerHTML;
                        }

                        const newTxnData = doc.getElementById('petty-cash-txns-data');
                        const curTxnData = document.getElementById('petty-cash-txns-data');
                        if (newTxnData && curTxnData) {
                            curTxnData.textContent = newTxnData.textContent;
                            try {
                                this.transactions = JSON.parse(newTxnData.textContent);
                                this.currentPage = 1;
                            } catch (e) {
                                console.error('Error parsing updated transactions:', e);
                            }
                        }
                    })
                    .catch(err => console.error('Error fetching balance register:', err))
                    .finally(() => {
                        this.isLoading = false;
                    });
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.selectedProjectId = '';
                    this.selectedProjectName = 'All Sites';
                    this.projectSearch = '';
                    this.projectDropdownOpen = false;
                    this.selectedFinancialYear = 'All';
                    this.fyDropdownOpen = false;
                    this.fromDate = '';
                    this.toDate = '';
                    this.selectedStatus = '';
                    this.statusDropdownOpen = false;
                    this.selectedDate = '';
                    this.$nextTick(() => {
                        this.updateFilters();
                    });
                }
            };
        }

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

                // Determine active selected project name for header title
                let activeProjName = '';
                const projSelect = document.querySelector('select[name="project_id"]');
                if (projSelect && projSelect.selectedIndex >= 0) {
                    const selectedOpt = projSelect.options[projSelect.selectedIndex];
                    if (selectedOpt && selectedOpt.value && selectedOpt.text && selectedOpt.text.trim().toLowerCase() !== 'all sites') {
                        activeProjName = selectedOpt.text.trim().toUpperCase();
                    }
                }
                if (!activeProjName && projSelect && projSelect.options.length > 1) {
                    for (let i = 0; i < projSelect.options.length; i++) {
                        if (projSelect.options[i].value) {
                            activeProjName = projSelect.options[i].text.trim().toUpperCase();
                            break;
                        }
                    }
                }
                if (!activeProjName) {
                    activeProjName = 'SITE PROJECT';
                }

                const reportTitleText = activeProjName + ' - PETTY CASH BALANCE REGISTER';

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
                row2.height = 36;
                worksheet.mergeCells('A2:H2');
                const titleCell = worksheet.getCell('A2');
                titleCell.value = reportTitleText;
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row2.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } }; // Theme Golden Color
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF8A7522' } },
                        bottom: { style: 'thin', color: { argb: 'FF8A7522' } },
                        left: { style: 'thin', color: { argb: 'FF8A7522' } },
                        right: { style: 'thin', color: { argb: 'FF8A7522' } }
                    };
                }

                // ── 4. Banner 2: Subtitle / Context (Row 3) ──
                const row3 = worksheet.getRow(3);
                row3.height = 26;
                worksheet.mergeCells('A3:H3');
                const subCell = worksheet.getCell('A3');
                subCell.value = 'PETTY CASH & SITE EXPENSE BALANCE AUDIT REGISTER';
                subCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                subCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row3.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } }; // Green
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF047857' } },
                        bottom: { style: 'thin', color: { argb: 'FF047857' } },
                        left: { style: 'thin', color: { argb: 'FF047857' } },
                        right: { style: 'thin', color: { argb: 'FF047857' } }
                    };
                }

                // ── 5. Banner 3: Transaction Details (Row 4) ──
                const row4 = worksheet.getRow(4);
                row4.height = 26;
                worksheet.mergeCells('A4:H4');
                const bannerCell = worksheet.getCell('A4');
                bannerCell.value = '1. FINANCIAL TRANSACTIONS & CASH FLOW AUDIT DESK';
                bannerCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                bannerCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= totalCols; c++) {
                    const cell = row4.getCell(c);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF8A7522' } }; // Theme Golden Color
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF6B5A19' } },
                        bottom: { style: 'thin', color: { argb: 'FF6B5A19' } },
                        left: { style: 'thin', color: { argb: 'FF6B5A19' } },
                        right: { style: 'thin', color: { argb: 'FF6B5A19' } }
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
                headerRow.height = 32;

                for (let c = 1; c <= totalCols; c++) {
                    const cell = headerRow.getCell(c);
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };
                    let headerBg = 'FFA38C29'; // Theme Golden Color for cols 1-4
                    if (c >= 5 && c <= 7) {
                        headerBg = 'FF0B3B2E'; // Green for cash flow cols
                    } else if (c === 8) {
                        headerBg = 'FF8A7522'; // Theme Golden Color for reference col
                    }
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: headerBg }
                    };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
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
                        cell.alignment = {
                            horizontal: 'center',
                            vertical: 'middle',
                            wrapText: true
                        };
                        cell.border = {
                            top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                            right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                        };

                        if (c === 3) {
                            cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        } else if (c === 5 || c === 6 || c === 7) {
                            cell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                            if (c === 5 && cashInVal > 0) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF15803D' } };
                            } else if (c === 6 && cashOutVal > 0) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFDC2626' } };
                            } else if (c === 7) {
                                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1E293B' } };
                            }
                        }
                    }

                    currentRowIdx++;
                });

                // ── 9. Bottom Summary / Total Row (Row 7 + N) ──
                const totalRow = worksheet.getRow(currentRowIdx);
                totalRow.height = 34;
                worksheet.mergeCells(`A${currentRowIdx}:F${currentRowIdx}`);

                const totalLabelCell = worksheet.getCell(`A${currentRowIdx}`);
                totalLabelCell.value = 'TOTAL CLOSING BALANCE';
                totalLabelCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                totalLabelCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                const totalValCell = worksheet.getCell(`G${currentRowIdx}`);
                totalValCell.value = lastBalance;
                totalValCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                totalValCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                totalValCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                for (let c = 1; c <= totalCols; c++) {
                    const cell = totalRow.getCell(c);
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'FF0B3B2E' } // Dark Green Footer Background
                    };
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF047857' } },
                        bottom: { style: 'thin', color: { argb: 'FF047857' } },
                        left: { style: 'thin', color: { argb: 'FF047857' } },
                        right: { style: 'thin', color: { argb: 'FF047857' } }
                    };
                }

                // ── 10. Generate & Download ──
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                anchor.download = 'petty-cash-balance-register.xlsx';
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