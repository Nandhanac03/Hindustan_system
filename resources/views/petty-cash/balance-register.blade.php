@extends('layouts.erp')

@section('content')
<div class="w-full px-6 py-6 bg-[#f8f9fa] min-h-screen font-sans">
    
    <!-- Header Card (Breadcrumb & Title) -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6 shadow-sm">
        <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
            <span class="hover:text-gray-600 cursor-pointer">Home</span>
            <span class="mx-2">></span>
            <span class="hover:text-gray-600 cursor-pointer">Petty Cash & Site Expense</span>
            <span class="mx-2">></span>
            <span class="text-[#a38c29]">Petty Cash Balance Register</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="text-[#a38c29]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-[18px] font-bold text-gray-800 m-0">Petty Cash Balance Register</h3>
                <span class="bg-[#e6f4ea] text-[#1e8e3e] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ml-2">Real-time Tracking</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-extrabold transition-all duration-200 uppercase tracking-wider shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Top Filter Area (Matching Units Module Structure) -->
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

    <div id="petty-cash-content" class="relative">
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

        <!-- Summaries Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Petty Cash Summary -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Petty Cash Summary</h5>
                <table class="w-full text-[12px]">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Site</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $siteName }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Cash Box / Incharge</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $cashBoxIncharge }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Cash Box Code</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $cashBoxCode }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Last Updated</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $lastUpdated }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Updated By</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $updatedBy }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Today's Transaction Summary -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Today's Transaction Summary</h5>
                <table class="w-full text-[12px]">
                    <thead>
                        <tr>
                            <th class="pb-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Particulars</th>
                            <th class="pb-2 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        <tr>
                            <td class="py-3 font-bold text-gray-700 uppercase text-[10px] tracking-wider">Bank Withdrawal (Contra)</td>
                            <td class="py-3 text-right text-[#10b981] font-bold">+ {{ number_format($bankWithdrawal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 font-bold text-gray-700 uppercase text-[10px] tracking-wider">Site Expenses</td>
                            <td class="py-3 text-right text-[#ef4444] font-bold">- {{ number_format($siteExpenses, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td class="pt-4 font-bold text-gray-800 uppercase text-[11px] tracking-wider">Net Cash Flow</td>
                            <td class="pt-4 text-right font-bold text-[14px] {{ $netCashFlow >= 0 ? 'text-[#10b981]' : 'text-[#ef4444]' }}">
                                {{ $netCashFlow >= 0 ? '+' : '' }} {{ number_format($netCashFlow, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Balance Snapshot -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Balance Snapshot</h5>
                <table class="w-full text-[12px]">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Opening Balance</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">₹ {{ number_format($openingBalance, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Add: Cash In (Today)</td>
                            <td class="py-2.5 text-right font-bold text-[#10b981]">₹ {{ number_format($cashIn, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Less: Cash Out (Today)</td>
                            <td class="py-2.5 text-right font-bold text-[#ef4444]">₹ {{ number_format($cashOut, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td class="pt-4 font-bold text-gray-800 uppercase text-[11px] tracking-wider">Closing Balance</td>
                            <td class="pt-4 text-right font-bold text-[14px] text-[#a38c29]">₹ {{ number_format($closingBalance, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>

    <!-- Transaction Details Modal -->
    <div id="transaction-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all scale-95 opacity-0" id="transaction-modal-content">
            <!-- Header (Premium Dark Theme) -->
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 rounded-t-xl">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Petty Cash</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Transaction Details
                        </h2>
                    </div>
                    <button type="button" onclick="closeTransactionModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <!-- Body -->
            <div class="px-6 py-5 bg-slate-50/50">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-1/3">Voucher No.</td>
                            <td class="py-3 text-right font-bold text-blue-600 uppercase" id="modal-voucher"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Date</td>
                            <td class="py-3 text-right font-bold text-gray-800" id="modal-date"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Type / Particulars</td>
                            <td class="py-3 text-right font-bold text-gray-800" id="modal-type"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Cash In</td>
                            <td class="py-3 text-right font-bold text-[#10b981]" id="modal-cashin"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Cash Out</td>
                            <td class="py-3 text-right font-bold text-[#ef4444]" id="modal-cashout"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Running Balance</td>
                            <td class="py-3 text-right font-bold text-gray-800" id="modal-balance"></td>
                        </tr>
                        <tr>
                            <td class="py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Reference</td>
                            <td class="py-3 text-right font-bold text-gray-600" id="modal-reference"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end bg-slate-50 rounded-b-xl">
                <button type="button" onclick="closeTransactionModal()" class="px-5 py-2 border border-slate-250 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection