@extends('layouts.erp')

@section('title', 'Daily Site Expenses - Hindustan Real Estate ERP')

@section('content')
<div class="p-6 h-full overflow-y-auto" x-data="dailySiteExpenses()">
    <div class="w-full space-y-6">
        <!-- Flash & Error Notifications -->
        @if(session('status') || session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') ?? session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-800 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        <!-- Breadcrumb & Top Action Header (Identical to Contractor Master Format) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Petty Cash & Site Expense</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Daily Site Expenses</span>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <button type="button" @click="exportExcel()" :disabled="isExporting" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer disabled:opacity-60">
                    <svg x-show="!isExporting" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <svg x-show="isExporting" class="w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="isExporting ? 'Exporting...' : 'Excel Report'">Excel Report</span>
                </button>
                <button type="button" @click="openExpenseModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span> New Expense</span>
                </button>
            </div>
        </div>

        <!-- Executive KPI Metric Cards (Matching Customer Ledger Format) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Expenses --}}
            <div class="bg-gradient-to-br from-white via-white to-blue-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-blue-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Site Expenses</span>
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100 group-hover:scale-105 transition-transform">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono" x-text="'₹ ' + formattedTotalAmount">₹ {{ number_format($totalAmount, 2) }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold block">Filtered Site Outflow</span>
                </div>
            </div>

            {{-- Card 2: Petty Cash Balance --}}
            <div class="bg-gradient-to-br from-white via-white to-emerald-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-emerald-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Petty Cash Balance</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100 group-hover:scale-105 transition-transform">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-base lg:text-lg font-black text-emerald-700 truncate font-mono" x-text="formatCurrency(availableBalance)">₹ {{ number_format($availableBalance ?? 0, 2) }}</div>
                    <span class="text-[10px] text-emerald-600/80 font-semibold block">Current In-Hand Balance</span>
                </div>
            </div>

            {{-- Card 3: Monthly Outflow --}}
            <div class="bg-gradient-to-br from-white via-white to-rose-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-rose-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">This Month Outflow</span>
                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs border border-rose-100 group-hover:scale-105 transition-transform">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-base lg:text-lg font-black text-rose-700 truncate font-mono" x-text="formatCurrency(thisMonthTotal)">₹ {{ number_format($thisMonthTotal ?? 0, 2) }}</div>
                    <span class="text-[10px] text-rose-600/80 font-semibold block">Month-to-Date Total</span>
                </div>
            </div>

            {{-- Card 4: Voucher Entries --}}
            <div class="bg-gradient-to-br from-white via-white to-amber-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Voucher Entries</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs border border-amber-200/60 group-hover:scale-105 transition-transform">
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono" x-text="pagination.total">{{ $expenses->total() }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold block">Recorded Vouchers</span>
                </div>
            </div>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel (Matching Units Design) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3 flex-1">
                {{-- Pro Search Input --}}
                <div class="relative group col-span-1 sm:col-span-2 xl:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Voucher, Particulars..." 
                           x-model="filters.search" @input.debounce.300ms="fetchExpenses(1)"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="filters.search" @click="filters.search = ''; fetchExpenses(1)"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Site / Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                    </div>
                    <select x-model="filters.project_id" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- From Date --}}
                <div class="relative">
                    <input type="date" x-model="filters.from_date" @change="fetchExpenses(1)"
                           class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- To Date --}}
                <div class="relative">
                    <input type="date" x-model="filters.to_date" @change="fetchExpenses(1)"
                           class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- Category Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <select x-model="filters.category" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="All">All Categories</option>
                        <option value="Refreshments">Refreshments</option>
                        <option value="Transport">Transport</option>
                        <option value="Minor Tools">Minor Tools</option>
                        <option value="Stationery">Stationery</option>
                        <option value="Labour Welfare">Labour Welfare</option>
                        <option value="Electrical Material">Electrical Material</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Payment Mode Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <select x-model="filters.payment_mode" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="All">All Modes</option>
                        @if(isset($paymentModes))
                            @foreach($paymentModes as $mode)
                                <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                            @endforeach
                        @else
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <button @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8f7a22]">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Voucher No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Particulars</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Payment Mode</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Bill No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-right">Amount (₹)</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="expense in expensesList" :key="expense.id">
                            <tr class="hover:bg-[#f8f9fa] transition-colors group">
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600" x-text="expense.formatted_date"></td>
                                <td class="px-6 py-4 text-[12px] font-bold text-[#a38c29]" x-text="expense.voucher_number"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-700" x-text="expense.category"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600 truncate max-w-[200px]" :title="expense.narration" x-text="expense.narration"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600" x-text="expense.payment_mode"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-500" x-text="expense.bill_no || '-'"></td>
                                <td class="px-6 py-4 text-[12px] font-bold text-gray-900 text-right" x-text="expense.amount.toFixed(2)"></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openViewModal(expense)" class="w-8 h-8 rounded-xl bg-[#f0e8d5] hover:bg-[#e4dac0] text-[#917d23] transition-all inline-flex items-center justify-center cursor-pointer shadow-2xs active:scale-95" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button type="button" @click="openEditModal(expense)" class="w-8 h-8 rounded-xl bg-[#dce6d5] hover:bg-[#cfdcc3] text-[#0d7a54] transition-all inline-flex items-center justify-center cursor-pointer shadow-2xs active:scale-95" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="expensesList.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-[13px] font-bold text-gray-500">No expenses found</p>
                                        <p class="text-[11px] text-gray-400 mt-1">Adjust your search query or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot x-show="expensesList.length > 0">
                        <tr class="bg-gray-50 border-t border-gray-200">
                            <td colspan="6" class="px-6 py-3 text-[11px] font-bold text-gray-700">Total</td>
                            <td class="px-6 py-3 text-[13px] font-extrabold text-[#a38c29] text-right" x-text="formattedTotalAmount"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-white">
                <p class="text-[11px] text-gray-500 font-medium">
                    Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of <span x-text="pagination.total || 0"></span> entries
                </p>
                <div class="flex items-center gap-1" x-show="pagination.last_page > 1">
                    <button type="button" @click="fetchExpenses(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-600 disabled:opacity-40 hover:bg-gray-50 cursor-pointer">Prev</button>
                    <span class="px-2 text-xs font-bold text-gray-700" x-text="`Page ${pagination.current_page} of ${pagination.last_page}`"></span>
                    <button type="button" @click="fetchExpenses(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-600 disabled:opacity-40 hover:bg-gray-50 cursor-pointer">Next</button>
            </div>
        </div>

        <!-- New Expense Modal -->
        <div x-show="showExpenseModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div x-show="showExpenseModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                 @click="showExpenseModal = false" aria-hidden="true"></div>

            <div x-show="showExpenseModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                 class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col my-auto z-10"
                 @click.stop>
            
            <form id="newExpenseForm" action="{{ route('petty-cash.store-expense') }}" method="POST" enctype="multipart/form-data" novalidate @submit.prevent="submitExpenseForm('save_post')" class="flex flex-col h-full overflow-hidden">
                @csrf
                <input type="hidden" name="submit_action" x-model="submitAction">
                
                <!-- Theme Modal Header -->
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/30 text-[#e6ca65] flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <p class="text-[#a38c29] text-[10px] font-black uppercase tracking-widest mb-0.5">Petty Cash Expense Entry</p>
                                <h3 class="text-base font-extrabold text-white">Add New Expense</h3>
                            </div>
                        </div>
                        <button type="button" @click="showExpenseModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 cursor-pointer text-xs font-bold">✕</button>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row flex-1 overflow-y-auto">
                    <!-- Left Form Column -->
                    <div class="flex-1 p-6 space-y-5">
                        
                        <!-- 1. Expense Classification -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">1. Expense Classification</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Voucher No.</label>
                                    <input type="text" name="voucher_number" value="EXP-{{ rand(1000, 9999) }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 h-10 text-xs font-bold font-mono text-slate-700 outline-none cursor-not-allowed shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Expense Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="transaction_date" x-model="newExpense.transaction_date" @input="expenseErrors.transaction_date = ''"
                                           :class="expenseErrors.transaction_date ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                    <p x-show="expenseErrors.transaction_date" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="expenseErrors.transaction_date"></span>
                                    </p>
                                    @error('transaction_date') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Project <span class="text-rose-500">*</span></label>
                                    <select name="project_id" x-model="newExpense.project_id" @change="expenseErrors.project_id = ''"
                                            :class="expenseErrors.project_id ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                            class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs cursor-pointer">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="expenseErrors.project_id" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="expenseErrors.project_id"></span>
                                    </p>
                                    @error('project_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. Payment & Outflow Amount -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">2. Payment & Outflow Details</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Category <span class="text-rose-500">*</span></label>
                                    <select name="category" x-model="newExpense.category" @change="expenseErrors.category = ''"
                                            :class="expenseErrors.category ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                            class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs cursor-pointer">
                                        <option value="Refreshments">Refreshments</option>
                                        <option value="Transport">Transport</option>
                                        <option value="Minor Tools">Minor Tools</option>
                                        <option value="Stationery">Stationery</option>
                                        <option value="Labour Welfare">Labour Welfare</option>
                                        <option value="Electrical Material">Electrical Material</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <p x-show="expenseErrors.category" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="expenseErrors.category"></span>
                                    </p>
                                    @error('category') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Amount (₹) <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-bold text-slate-400 text-xs">₹</span>
                                        <input type="number" step="0.01" name="amount" x-model="newExpenseAmount" @input="expenseErrors.amount = ''" placeholder="0.00" 
                                               :class="(expenseErrors.amount || isInsufficient) ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/40 text-rose-900' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                               class="w-full bg-white border rounded-xl pl-8 pr-3.5 h-10 text-xs font-black font-mono text-slate-900 outline-none transition shadow-2xs">
                                    </div>
                                    <p x-show="expenseErrors.amount" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="expenseErrors.amount"></span>
                                    </p>
                                    @error('amount') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Payment Mode <span class="text-rose-500">*</span></label>
                                    <select name="payment_mode" x-model="newExpense.payment_mode" @change="expenseErrors.payment_mode = ''"
                                            :class="expenseErrors.payment_mode ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                            class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs cursor-pointer">
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="expenseErrors.payment_mode" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="expenseErrors.payment_mode"></span>
                                    </p>
                                    @error('payment_mode') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 3. Bill / Invoice & Attachment -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">3. Bill / Invoice & Description</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bill / Invoice No.</label>
                                    <input type="text" name="bill_no" placeholder="e.g. INV-2026/09" class="w-full bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bill Date</label>
                                    <input type="date" name="bill_date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Attach Receipt / Bill</label>
                                    <div class="relative">
                                        <input type="file" name="attachment" id="attachment" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Choose File...'">
                                        <div class="w-full bg-slate-50 hover:bg-white border border-dashed border-slate-300 hover:border-[#a38c29] rounded-xl px-3 h-10 flex items-center justify-between transition shadow-2xs">
                                            <div class="flex items-center gap-1.5 text-slate-600 truncate mr-1">
                                                <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span id="file-name" class="text-[11px] font-medium truncate">Choose File...</span>
                                            </div>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider shrink-0 bg-slate-100 px-1.5 py-0.5 rounded">PDF/IMG</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Particulars / Narration <span class="text-rose-500">*</span></label>
                                <textarea name="particulars" rows="2" x-model="newExpense.particulars" @input="expenseErrors.particulars = ''" placeholder="Enter expense details (e.g. Tea & Snacks for Site Team)" 
                                          :class="expenseErrors.particulars ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                          class="w-full bg-white border rounded-xl p-3 text-xs font-medium text-slate-800 outline-none transition shadow-2xs resize-none"></textarea>
                                <p x-show="expenseErrors.particulars" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="expenseErrors.particulars"></span>
                                </p>
                                @error('particulars') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar Column -->
                    <div class="w-full md:w-[320px] bg-slate-50/70 border-t md:border-t-0 md:border-l border-slate-200 p-5 flex flex-col justify-between gap-4">
                        <!-- Live Balance & Payout Breakdown Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 border-l-[6px] p-4 shadow-2xs relative overflow-hidden transition-all"
                             :class="isInsufficient ? 'border-l-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-l-[#a38c29]'">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                                <span class="text-[10px] font-black text-slate-700 uppercase tracking-wider">Petty Cash Live Status</span>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider"
                                      :class="isInsufficient ? 'bg-rose-600 text-white' : 'bg-[#a38c29] text-white'">
                                    <span x-text="isInsufficient ? 'EXCEEDED' : 'LIVE'"></span>
                                </span>
                            </div>
                            
                            <div class="space-y-2.5 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 font-medium">In-Hand Cash:</span>
                                    <span class="font-bold font-mono text-slate-900" x-text="formatCurrency(availableBalance)">₹ {{ number_format($availableBalance, 2) }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 font-medium">Expense Payout:</span>
                                    <span class="font-bold font-mono text-amber-700" x-text="formatCurrency(parsedAmount)">₹ 0.00</span>
                                </div>

                                <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                                    <span class="font-bold text-slate-700">Projected Balance:</span>
                                    <span class="font-mono font-black text-sm"
                                          :class="isInsufficient ? 'text-rose-600' : 'text-emerald-600'"
                                          x-text="formatCurrency(balanceAfterPayout)">₹ {{ number_format($availableBalance, 2) }}</span>
                                </div>
                            </div>

                            <!-- Live Insufficient Warning Alert -->
                            <template x-if="isInsufficient">
                                <div class="mt-3 p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-bold space-y-1 shadow-2xs">
                                    <div class="flex items-center gap-1.5 uppercase font-black tracking-wide text-rose-900">
                                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Insufficient Balance!</span>
                                    </div>
                                    <p class="text-[10px] leading-tight text-rose-700 font-medium">
                                        Payout exceeds available petty cash by <strong x-text="formatCurrency(parsedAmount - availableBalance)"></strong>.
                                    </p>
                                </div>
                            </template>

                            <!-- Zero Balance Warning Alert -->
                            <template x-if="availableBalance <= 0">
                                <div class="mt-3 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-bold space-y-1 shadow-2xs">
                                    <div class="flex items-center gap-1.5 uppercase font-black tracking-wide text-amber-900">
                                        <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>No Cash Available!</span>
                                    </div>
                                    <p class="text-[10px] leading-tight text-amber-700 font-medium">
                                        Petty cash balance is ₹ 0.00. Please deposit funds or perform Bank Cash Withdrawal first.
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Category Summary Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-700 mb-2.5 flex items-center gap-1.5">
                                    <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                                    This Month Outflow by Category
                                </h4>
                                <div class="max-h-36 overflow-y-auto">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th class="text-left text-[9px] font-bold text-slate-400 uppercase tracking-wider pb-1.5">Category</th>
                                                <th class="text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider pb-1.5">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50 font-sans">
                                            @php $catTotal = 0; @endphp
                                            @foreach($categorySummary as $catName => $catAmount)
                                                @php $catTotal += $catAmount; @endphp
                                                <tr>
                                                    <td class="text-[10px] font-medium text-slate-700 py-1">{{ $catName }}</td>
                                                    <td class="text-[10px] font-bold font-mono text-slate-900 text-right py-1">₹ {{ number_format($catAmount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-200 mt-2 flex items-center justify-between">
                                <span class="text-[10px] font-black text-slate-900 uppercase">Total Outflow</span>
                                <span class="text-xs font-black font-mono text-[#8a7522]">₹ {{ number_format($catTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between flex-shrink-0">
                    <button type="button" @click="showExpenseModal = false" class="px-5 py-2.5 text-xs font-extrabold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition-colors uppercase tracking-wider shadow-2xs cursor-pointer">
                        Cancel
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="submitExpenseForm('save_new')" 
                                :disabled="isInsufficient || availableBalance <= 0"
                                :class="(isInsufficient || availableBalance <= 0) ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400 border-slate-300' : 'text-[#8a7522] bg-white border border-[#a38c29]/50 hover:bg-[#a38c29]/10'"
                                class="px-5 py-2.5 text-xs font-extrabold rounded-xl transition-all uppercase tracking-wider shadow-2xs cursor-pointer">
                            Save & New
                        </button>
                        <button type="button" @click="submitExpenseForm('save_post')" 
                                :disabled="isInsufficient || availableBalance <= 0"
                                :class="(isInsufficient || availableBalance <= 0) ? 'opacity-40 cursor-not-allowed bg-slate-300 border-slate-300 text-slate-500' : 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white shadow-md shadow-[#a38c29]/25 hover:shadow-lg active:scale-95'"
                                class="px-6 py-2.5 text-xs font-black rounded-xl transition-all uppercase tracking-wider cursor-pointer">
                            Save & Post
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Details Modal -->
    <div x-show="showViewModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div x-show="showViewModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
             @click="showViewModal = false"></div>
        
        <div x-show="showViewModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
             class="relative w-full max-w-2xl bg-white rounded-2xl text-left overflow-hidden shadow-2xl my-auto z-10"
             @click.stop>
            
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 border-b border-[#a38c29]/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Expense Voucher</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5" x-text="selectedExp.voucher_number || 'EXP-0000'"></h2>
                    </div>
                    <button type="button" @click="showViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer font-bold">✕</button>
                </div>
            </div>

            <template x-if="selectedExp">
                <div class="bg-white rounded-b-2xl">
                    <div class="p-5 space-y-3 bg-slate-50/50 text-xs font-sans">
                        {{-- Card 1: Top Hero Summary (Project, Amount & Created By in 1 Horizontal Card) --}}
                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <div class="sm:col-span-5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Project / Site</span>
                                <span class="text-sm font-extrabold text-slate-900 block truncate" x-text="selectedExp.project_name || 'Site'"></span>
                                <span class="text-[10px] text-slate-500 font-medium block truncate mt-0.5" 
                                      x-text="'Category: ' + (selectedExp.category || 'General')"></span>
                            </div>
                            <div class="sm:col-span-4 sm:border-l sm:border-slate-100 sm:pl-3">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Expense Amount</span>
                                <span class="text-base font-extrabold text-slate-900 font-mono mt-0.5 block" x-text="selectedExp.formatted_amount || ('₹ ' + Number(selectedExp.amount || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}))"></span>
                            </div>
                            <div class="sm:col-span-3 sm:text-right sm:border-l sm:border-slate-100 sm:pl-3">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Recorded By</span>
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase inline-block border bg-amber-50 text-[#8a7522] border-[#a38c29]/30"
                                      x-text="selectedExp.created_by || 'Admin'"></span>
                            </div>
                        </div>

                        {{-- Card 2: 4-Column Metadata Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Expense Date</span>
                                <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedExp.formatted_date || selectedExp.transaction_date || '—'"></span>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</span>
                                <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedExp.payment_mode || 'Cash'"></span>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Bill / Ref No</span>
                                <span class="text-xs font-mono font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedExp.bill_no || '—'"></span>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Bill Date</span>
                                <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedExp.formatted_bill_date || '—'"></span>
                            </div>
                        </div>

                        {{-- Card 3: Remarks Details --}}
                        <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Particulars / Narration</span>
                            <span class="text-xs font-medium text-slate-700 mt-0.5 block italic" x-text="selectedExp.particulars || selectedExp.narration || 'No particulars provided'"></span>
                        </div>

                        {{-- Card 4: Attachment (if available) --}}
                        <template x-if="selectedExp.attachment_url">
                            <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl flex items-center justify-between shadow-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-xs" x-text="selectedExp.attachment_name || 'Attachment File'"></span>
                                </div>
                                <a :href="selectedExp.attachment_url" target="_blank" class="px-3.5 py-1.5 bg-[#a38c29] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#8f7a22] transition shadow-xs flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>View File</span>
                                </a>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end bg-slate-50 rounded-b-2xl">
                        <button type="button" @click="showViewModal = false" class="px-5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide cursor-pointer shadow-2xs">Close</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div x-show="showEditModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
             @click="showEditModal = false"></div>

        <div x-show="showEditModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
             class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col my-auto z-10"
             @click.stop>
            
            <form :action="updateUrl" method="POST" enctype="multipart/form-data" novalidate @submit.prevent="submitEditForm($event)" class="flex flex-col h-full overflow-hidden">
                @csrf
                @method('PUT')
                <!-- Header -->
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/30 text-[#e6ca65] flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <p class="text-[#a38c29] text-[10px] font-black uppercase tracking-widest mb-0.5" x-text="'VOUCHER: ' + (selectedExp.voucher_number || '')"></p>
                                <h3 class="text-base font-extrabold text-white">Edit Daily Expense</h3>
                            </div>
                        </div>
                        <button type="button" @click="showEditModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 cursor-pointer text-xs font-bold">✕</button>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row flex-1 overflow-y-auto">
                    <!-- Left Form Column -->
                    <div class="flex-1 p-6 space-y-5">
                        
                        <!-- 1. Expense Classification -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">1. Expense Classification</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Voucher No.</label>
                                    <input type="text" :value="selectedExp.voucher_number" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 h-10 text-xs font-bold font-mono text-slate-700 outline-none cursor-not-allowed shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Expense Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="transaction_date" x-model="selectedExp.transaction_date" @input="editErrors.transaction_date = ''"
                                           :class="editErrors.transaction_date ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                    <p x-show="editErrors.transaction_date" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="editErrors.transaction_date"></span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Project / Site</label>
                                    <input type="text" :value="selectedExp.project_name || 'Tabasco Hindustan Infra'" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-700 outline-none cursor-not-allowed shadow-2xs">
                                </div>
                            </div>
                        </div>

                        <!-- 2. Payment & Outflow Details -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">2. Payment & Outflow Details</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Category <span class="text-rose-500">*</span></label>
                                    <select name="category" x-model="selectedExp.category" @change="editErrors.category = ''"
                                            :class="editErrors.category ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                            class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs cursor-pointer">
                                        <option value="Refreshments">Refreshments</option>
                                        <option value="Transport">Transport</option>
                                        <option value="Minor Tools">Minor Tools</option>
                                        <option value="Stationery">Stationery</option>
                                        <option value="Labour Welfare">Labour Welfare</option>
                                        <option value="Electrical Material">Electrical Material</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <p x-show="editErrors.category" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="editErrors.category"></span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Amount (₹) <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-bold text-slate-400 text-xs">₹</span>
                                        <input type="number" step="0.01" name="amount" x-model="selectedExp.amount" @input="editErrors.amount = ''" placeholder="0.00" 
                                               :class="(editErrors.amount || isEditInsufficient) ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/40 text-rose-900' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                               class="w-full bg-white border rounded-xl pl-8 pr-3.5 h-10 text-xs font-black font-mono text-slate-900 outline-none transition shadow-2xs">
                                    </div>
                                    <p x-show="editErrors.amount" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="editErrors.amount"></span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Payment Mode <span class="text-rose-500">*</span></label>
                                    <select name="payment_mode" x-model="selectedExp.payment_mode" @change="editErrors.payment_mode = ''"
                                            :class="editErrors.payment_mode ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                            class="w-full bg-white border rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs cursor-pointer">
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="editErrors.payment_mode" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="editErrors.payment_mode"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Bill / Invoice & Description -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-1 border-b border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-700">3. Bill / Invoice & Description</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bill / Invoice No.</label>
                                    <input type="text" name="bill_no" x-model="selectedExp.bill_no" placeholder="e.g. INV-2026/09" class="w-full bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bill Date</label>
                                    <input type="date" name="bill_date" x-model="selectedExp.bill_date" class="w-full bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 h-10 text-xs font-semibold text-slate-800 outline-none transition shadow-2xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Replace Receipt (Optional)</label>
                                    <div class="relative">
                                        <input type="file" name="attachment" id="edit-attachment" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" onchange="document.getElementById('edit-file-name').textContent = this.files[0] ? this.files[0].name : 'Choose File...'">
                                        <div class="w-full bg-slate-50 hover:bg-white border border-dashed border-slate-300 hover:border-[#a38c29] rounded-xl px-3 h-10 flex items-center justify-between transition shadow-2xs">
                                            <div class="flex items-center gap-1.5 text-slate-600 truncate mr-1">
                                                <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span id="edit-file-name" class="text-[11px] font-medium truncate" x-text="selectedExp.attachment_name || 'Choose File...'"></span>
                                            </div>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider shrink-0 bg-slate-100 px-1.5 py-0.5 rounded">PDF/IMG</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Particulars / Narration <span class="text-rose-500">*</span></label>
                                <textarea name="particulars" rows="2" x-model="selectedExp.particulars" @input="editErrors.particulars = ''" placeholder="Enter expense details..." 
                                          :class="editErrors.particulars ? 'border-rose-400 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                          class="w-full bg-white border rounded-xl p-3 text-xs font-medium text-slate-800 outline-none transition shadow-2xs resize-none"></textarea>
                                <p x-show="editErrors.particulars" x-cloak class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="editErrors.particulars"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar Column -->
                    <div class="w-full md:w-[320px] bg-slate-50/70 border-t md:border-t-0 md:border-l border-slate-200 p-5 flex flex-col justify-between gap-4">
                        <!-- Live Calculation Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 border-l-[6px] p-4 shadow-2xs relative overflow-hidden transition-all"
                             :class="isEditInsufficient ? 'border-l-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-l-[#a38c29]'">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                                <span class="text-[10px] font-black text-slate-700 uppercase tracking-wider">Live Payout Update</span>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider"
                                      :class="isEditInsufficient ? 'bg-rose-600 text-white' : 'bg-[#a38c29] text-white'">
                                    <span x-text="isEditInsufficient ? 'EXCEEDED' : 'LIVE'"></span>
                                </span>
                            </div>

                            <div class="space-y-2.5 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 font-medium">Available Balance:</span>
                                    <span class="font-bold font-mono text-slate-900" x-text="formatCurrency(availableBalance)"></span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="font-medium">+ Original Amount:</span>
                                    <span class="font-bold font-mono text-slate-700" x-text="formatCurrency(originalEditAmount)"></span>
                                </div>
                                <div class="flex items-center justify-between text-amber-800 font-medium">
                                    <span>= Effective Capacity:</span>
                                    <span class="font-bold font-mono text-amber-900" x-text="formatCurrency(effectiveEditBalance)"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 font-medium">Updated Payout:</span>
                                    <span class="font-bold font-mono text-amber-700" x-text="formatCurrency(parsedEditAmount)"></span>
                                </div>
                                <div class="pt-2 border-t border-slate-200 flex items-center justify-between font-bold">
                                    <span :class="isEditInsufficient ? 'text-rose-700' : 'text-slate-800'">Projected Balance:</span>
                                    <span class="font-mono font-black text-sm"
                                          :class="isEditInsufficient ? 'text-rose-600' : 'text-emerald-600'"
                                          x-text="formatCurrency(balanceAfterEdit)"></span>
                                </div>
                            </div>

                            <!-- Insufficient Balance Warning Banner -->
                            <template x-if="isEditInsufficient">
                                <div class="mt-3 p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-bold space-y-1 shadow-2xs">
                                    <div class="flex items-center gap-1.5 uppercase font-black tracking-wide text-rose-900">
                                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Insufficient Balance!</span>
                                    </div>
                                    <p class="text-[10px] leading-tight text-rose-700 font-medium">
                                        Updated amount exceeds effective capacity by <strong x-text="formatCurrency(parsedEditAmount - effectiveEditBalance)"></strong>.
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Current File / Expense Info Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-700 mb-2.5 flex items-center gap-1.5">
                                    <div class="w-1 h-3.5 bg-[#a38c29] rounded-full"></div>
                                    Current Expense Info
                                </h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-slate-400 font-bold uppercase">Recorded By:</span>
                                        <span class="font-bold text-slate-800" x-text="selectedExp.created_by || 'Admin'"></span>
                                    </div>
                                    <template x-if="selectedExp.attachment_url">
                                        <div class="pt-2 border-t border-slate-100 mt-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Attached Receipt</span>
                                            <a :href="selectedExp.attachment_url" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#8a7522] hover:underline">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span class="truncate max-w-[180px]" x-text="selectedExp.attachment_name || 'View Receipt'"></span>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between flex-shrink-0">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-xs font-extrabold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition uppercase tracking-wider shadow-2xs cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            :disabled="isEditInsufficient"
                            :class="isEditInsufficient ? 'opacity-40 cursor-not-allowed bg-slate-300 border-slate-300 text-slate-500' : 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white shadow-md shadow-[#a38c29]/25 hover:shadow-lg active:scale-95'"
                            class="px-6 py-2.5 text-xs font-black rounded-xl transition-all uppercase tracking-wider cursor-pointer">
                        Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
function dailySiteExpenses() {
    return {
        isExporting: false,
        showExpenseModal: {{ session('show_expense_modal') || $errors->any() ? 'true' : 'false' }},
        showViewModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedExp: {},
        updateUrl: '',
        deleteUrl: '',
        newExpense: {
            transaction_date: '{{ old('transaction_date', date('Y-m-d')) }}',
            project_id: '{{ old('project_id', $projects->first()->id ?? '') }}',
            category: '{{ old('category', 'Refreshments') }}',
            payment_mode: '{{ old('payment_mode', (isset($paymentModes) && count($paymentModes) > 0) ? $paymentModes->first()->name : 'Cash') }}',
            particulars: `{{ old('particulars', '') }}`,
        },
        submitAction: 'save_post',
        newExpenseAmount: '{{ old('amount', '') }}',
        availableBalance: {{ (float)($availableBalance ?? 0) }},
        thisMonthTotal: {{ (float)($thisMonthTotal ?? 0) }},
        totalAmountRaw: {{ (float)($totalAmount ?? 0) }},
        expenseErrors: {
            transaction_date: '',
            project_id: '',
            category: '',
            amount: '',
            payment_mode: '',
            particulars: '',
        },
        editErrors: {
            transaction_date: '',
            category: '',
            payment_mode: '',
            amount: '',
            particulars: '',
        },

        get parsedAmount() {
            const val = parseFloat(this.newExpenseAmount);
            return isNaN(val) ? 0 : val;
        },

        get balanceAfterPayout() {
            return this.availableBalance - this.parsedAmount;
        },

        get isInsufficient() {
            return this.parsedAmount > this.availableBalance && this.parsedAmount > 0;
        },

        formatCurrency(val) {
            const num = parseFloat(val) || 0;
            return '₹ ' + num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        filters: {
            search: '',
            project_id: '{{ $selectedProject }}',
            from_date: '{{ $fromDate }}',
            to_date: '{{ $toDate }}',
            category: '{{ $category }}',
            payment_mode: '{{ $paymentMode }}'
        },
        expensesList: [],
        formattedTotalAmount: '{{ number_format($totalAmount, 2) }}',
        pagination: {
            current_page: {{ $expenses->currentPage() }},
            last_page: {{ $expenses->lastPage() }},
            from: {{ $expenses->firstItem() ?? 0 }},
            to: {{ $expenses->lastItem() ?? 0 }},
            total: {{ $expenses->total() }}
        },

        init() {
            this.expensesList = {!! json_encode(collect($expenses->items())->map(function($e) use ($category, $selectedProject, $siteName) {
                $catName = ($category && $category !== 'All') ? $category : (explode('-', $e->narration)[0] ?? 'General');
                $parts = explode('-', $e->narration);
                $particularsText = count($parts) > 1 ? trim(implode('-', array_slice($parts, 1))) : $e->narration;
                return [
                    'id' => $e->id,
                    'voucher_number' => $e->voucher_number,
                    'transaction_date' => \Carbon\Carbon::parse($e->transaction_date)->format('Y-m-d'),
                    'formatted_date' => \Carbon\Carbon::parse($e->transaction_date)->format('d-M-Y'),
                    'project_id' => $e->pettyCashBox?->project_id ?? $selectedProject,
                    'project_name' => $e->pettyCashBox?->project?->name ?? $siteName ?? 'Site',
                    'category' => trim($catName),
                    'particulars' => trim($particularsText),
                    'narration' => $e->narration,
                    'payment_mode' => $e->payment_mode ?? 'Cash',
                    'bill_no' => $e->reference_no ?? '',
                    'bill_date' => $e->bill_date ? \Carbon\Carbon::parse($e->bill_date)->format('Y-m-d') : '',
                    'formatted_bill_date' => $e->bill_date ? \Carbon\Carbon::parse($e->bill_date)->format('d-M-Y') : '',
                    'amount' => (float)$e->cash_out,
                    'formatted_amount' => '₹ ' . number_format($e->cash_out, 2),
                    'attachment_url' => $e->attachment_path ? asset($e->attachment_path) : '',
                    'attachment_name' => $e->attachment_path ? basename($e->attachment_path) : '',
                    'created_by' => $e->creator?->name ?? 'System Admin',
                ];
            })) !!};
        },

        openExpenseModal() {
            this.expenseErrors = {
                transaction_date: '',
                project_id: '',
                category: '',
                amount: '',
                payment_mode: '',
                particulars: '',
            };
            this.showExpenseModal = true;
        },

        submitExpenseForm(action) {
            this.submitAction = action;
            this.expenseErrors = {
                transaction_date: '',
                project_id: '',
                category: '',
                amount: '',
                payment_mode: '',
                particulars: '',
            };
            let hasError = false;

            if (!this.newExpense.transaction_date) {
                this.expenseErrors.transaction_date = 'Expense date is required.';
                hasError = true;
            }
            if (!this.newExpense.project_id) {
                this.expenseErrors.project_id = 'Please select a project.';
                hasError = true;
            }
            if (!this.newExpense.category) {
                this.expenseErrors.category = 'Please select a category.';
                hasError = true;
            }
            const amt = parseFloat(this.newExpenseAmount);
            if (!this.newExpenseAmount || isNaN(amt) || amt <= 0) {
                this.expenseErrors.amount = 'Please enter an expense amount.';
                hasError = true;
            } else if (this.isInsufficient) {
                this.expenseErrors.amount = 'Amount exceeds available petty cash balance.';
                hasError = true;
            }
            if (!this.newExpense.payment_mode) {
                this.expenseErrors.payment_mode = 'Please select a payment mode.';
                hasError = true;
            }
            if (!this.newExpense.particulars || !this.newExpense.particulars.trim()) {
                this.expenseErrors.particulars = 'Please enter particulars / narration.';
                hasError = true;
            }

            if (hasError) {
                return false;
            }

            document.getElementById('newExpenseForm').submit();
        },

        fetchExpenses(page = 1) {
            let params = new URLSearchParams();
            params.append('page', page);
            if (this.filters.project_id) params.append('project_id', this.filters.project_id);
            if (this.filters.from_date) params.append('from_date', this.filters.from_date);
            if (this.filters.to_date) params.append('to_date', this.filters.to_date);
            if (this.filters.category) params.append('category', this.filters.category);
            if (this.filters.payment_mode) params.append('payment_mode', this.filters.payment_mode);
            if (this.filters.search) params.append('search', this.filters.search);

            fetch('{{ route('petty-cash.daily-site-expenses') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.expensesList = data.expenses;
                this.formattedTotalAmount = data.totalAmount;
                this.totalAmountRaw = data.totalAmountRaw;
                this.availableBalance = data.availableBalance;
                this.thisMonthTotal = data.thisMonthTotal;
                this.pagination = data.pagination;
            })
            .catch(err => {
                console.error('Error fetching expenses:', err);
            });
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.project_id = '{{ $projects->first()->id ?? '' }}';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.filters.category = 'All';
            this.filters.payment_mode = 'All';
            this.fetchExpenses(1);
        },

        originalEditAmount: 0,

        get parsedEditAmount() {
            const val = parseFloat(this.selectedExp.amount);
            return isNaN(val) ? 0 : val;
        },

        get effectiveEditBalance() {
            return this.availableBalance + this.originalEditAmount;
        },

        get balanceAfterEdit() {
            return this.effectiveEditBalance - this.parsedEditAmount;
        },

        get isEditInsufficient() {
            return this.parsedEditAmount > this.effectiveEditBalance && this.parsedEditAmount > 0;
        },

        openViewModal(exp) {
            this.selectedExp = exp;
            this.showViewModal = true;
        },

        openEditModal(exp) {
            this.selectedExp = Object.assign({}, exp);
            this.originalEditAmount = parseFloat(exp.amount) || 0;
            this.updateUrl = '{{ url('petty-cash/daily-site-expenses') }}/' + exp.id;
            this.editErrors = {
                transaction_date: '',
                category: '',
                payment_mode: '',
                amount: '',
                particulars: '',
            };
            this.showEditModal = true;
        },

        submitEditForm(event) {
            this.editErrors = {
                transaction_date: '',
                category: '',
                payment_mode: '',
                amount: '',
                particulars: '',
            };
            let hasError = false;

            if (!this.selectedExp.transaction_date) {
                this.editErrors.transaction_date = 'Date is required.';
                hasError = true;
            }
            if (!this.selectedExp.category) {
                this.editErrors.category = 'Please select a category.';
                hasError = true;
            }
            if (!this.selectedExp.payment_mode) {
                this.editErrors.payment_mode = 'Please select a payment mode.';
                hasError = true;
            }
            const amt = parseFloat(this.selectedExp.amount);
            if (!this.selectedExp.amount || isNaN(amt) || amt <= 0) {
                this.editErrors.amount = 'Please enter an amount.';
                hasError = true;
            } else if (this.isEditInsufficient) {
                this.editErrors.amount = 'Amount exceeds effective available petty cash.';
                hasError = true;
            }
            if (!this.selectedExp.particulars || !this.selectedExp.particulars.trim()) {
                this.editErrors.particulars = 'Please enter particulars.';
                hasError = true;
            }

            if (hasError) {
                return false;
            }

            event.target.submit();
        },

        openDeleteModal(exp) {
            this.selectedExp = exp;
            this.deleteUrl = '{{ url('petty-cash/daily-site-expenses') }}/' + exp.id;
            this.showDeleteModal = true;
        },

        async exportExcel() {
            if (this.isExporting) return;

            if (typeof ExcelJS === 'undefined') {
                alert('Excel library is still loading. Please wait a few seconds and try again.');
                return;
            }

            this.isExporting = true;

            try {
                // 1. Fetch all expenses matching current filters
                let params = new URLSearchParams();
                if (this.filters.project_id) params.append('project_id', this.filters.project_id);
                if (this.filters.from_date) params.append('from_date', this.filters.from_date);
                if (this.filters.to_date) params.append('to_date', this.filters.to_date);
                if (this.filters.category) params.append('category', this.filters.category);
                if (this.filters.payment_mode) params.append('payment_mode', this.filters.payment_mode);
                if (this.filters.search) params.append('search', this.filters.search);
                params.append('export', '1');

                const res = await fetch('{{ route('petty-cash.daily-site-expenses') }}?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                const list = (data.expenses && data.expenses.length > 0) ? data.expenses : this.expensesList;

                if (!list || list.length === 0) {
                    alert('No expenses found matching the current filters to export.');
                    this.isExporting = false;
                    return;
                }

                const totalExpenseAmount = parseFloat(data.totalAmountRaw ?? this.totalAmountRaw ?? 0);
                const availableBalanceVal = parseFloat(data.availableBalance ?? this.availableBalance ?? 0);
                const thisMonthTotalVal = parseFloat(data.thisMonthTotal ?? this.thisMonthTotal ?? 0);
                const voucherCountVal = data.totalCount ?? this.pagination.total ?? list.length;

                // 2. Initialize Workbook & Sheet
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Hindustan Real Estate & Infrastructure ERP';
                workbook.created = new Date();

                const worksheet = workbook.addWorksheet('Daily Site Expenses', {
                    views: [{ showGridLines: true }]
                });

                // Column definitions
                worksheet.columns = [
                    { key: 'sl', width: 9 },         // A: SL NO
                    { key: 'date', width: 16 },       // B: DATE
                    { key: 'voucher', width: 22 },    // C: VOUCHER NO
                    { key: 'project', width: 34 },    // D: PROJECT / SITE
                    { key: 'category', width: 24 },   // E: CATEGORY
                    { key: 'particulars', width: 44 },// F: PARTICULARS
                    { key: 'mode', width: 18 },       // G: PAYMENT MODE
                    { key: 'bill_no', width: 20 },    // H: BILL / REF NO
                    { key: 'amount', width: 22 }      // I: AMOUNT (₹)
                ];

                const thinBorder = {
                    top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };

                const headerBorder = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };

                // Row 1: Spacer
                worksheet.addRow([]);
                worksheet.getRow(1).height = 16;

                // Row 2: Top Title Banner (Navy Slate #2C3E50)
                worksheet.mergeCells('A2:I2');
                const row2 = worksheet.getRow(2);
                row2.height = 36;
                const cellA2 = worksheet.getCell('A2');
                cellA2.value = 'HINDUSTAN REAL ESTATE & INFRASTRUCTURE - DAILY SITE EXPENSES DIRECTORY';
                cellA2.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                cellA2.alignment = { horizontal: 'center', vertical: 'middle' };
                for (let c = 1; c <= 9; c++) {
                    worksheet.getCell(2, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    worksheet.getCell(2, c).border = headerBorder;
                }

                // Row 3: Subtitle Banner (Cyan/Ocean Blue #007398)
                const nowStr = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                worksheet.mergeCells('A3:I3');
                const row3 = worksheet.getRow(3);
                row3.height = 25;
                const cellA3 = worksheet.getCell('A3');
                cellA3.value = `Comprehensive Daily Site Outflow & Petty Cash Expenses | Generated On: ${nowStr}`;
                cellA3.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                cellA3.alignment = { horizontal: 'center', vertical: 'middle' };
                for (let c = 1; c <= 9; c++) {
                    worksheet.getCell(3, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                    worksheet.getCell(3, c).border = headerBorder;
                }

                // Row 4: Section Banner (Deep Green #006039)
                worksheet.mergeCells('A4:I4');
                const row4 = worksheet.getRow(4);
                row4.height = 25;
                const cellA4 = worksheet.getCell('A4');
                cellA4.value = 'EXPENSE SUMMARY & PETTY CASH KPI';
                cellA4.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                cellA4.alignment = { horizontal: 'center', vertical: 'middle' };
                for (let c = 1; c <= 9; c++) {
                    worksheet.getCell(4, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF006039' } };
                    worksheet.getCell(4, c).border = headerBorder;
                }

                // Row 5: Spacer
                worksheet.addRow([]);
                worksheet.getRow(5).height = 14;

                // Row 6: KPI Summary Row 1
                worksheet.mergeCells('A6:B6');
                worksheet.mergeCells('C6:D6');
                worksheet.mergeCells('E6:F6');
                worksheet.mergeCells('G6:I6');
                const row6 = worksheet.getRow(6);
                row6.height = 30;

                const cellA6 = worksheet.getCell('A6');
                cellA6.value = 'TOTAL SITE EXPENSES:';
                cellA6.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF334155' } };
                cellA6.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                const cellC6 = worksheet.getCell('C6');
                cellC6.value = totalExpenseAmount;
                cellC6.numFormat = '#,##0.00_ ';
                cellC6.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF0F172A' } };
                cellC6.alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 1; c <= 4; c++) {
                    worksheet.getCell(6, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF1F5F9' } };
                    worksheet.getCell(6, c).border = thinBorder;
                }

                const cellE6 = worksheet.getCell('E6');
                cellE6.value = 'PETTY CASH BALANCE:';
                cellE6.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF047857' } };
                cellE6.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                const cellG6 = worksheet.getCell('G6');
                cellG6.value = availableBalanceVal;
                cellG6.numFormat = '#,##0.00_ ';
                cellG6.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF047857' } };
                cellG6.alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 5; c <= 9; c++) {
                    worksheet.getCell(6, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFECFDF5' } };
                    worksheet.getCell(6, c).border = thinBorder;
                }

                // Row 7: KPI Summary Row 2
                worksheet.mergeCells('A7:B7');
                worksheet.mergeCells('C7:D7');
                worksheet.mergeCells('E7:F7');
                worksheet.mergeCells('G7:I7');
                const row7 = worksheet.getRow(7);
                row7.height = 30;

                const cellA7 = worksheet.getCell('A7');
                cellA7.value = 'THIS MONTH OUTFLOW:';
                cellA7.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFBE123C' } };
                cellA7.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                const cellC7 = worksheet.getCell('C7');
                cellC7.value = thisMonthTotalVal;
                cellC7.numFormat = '#,##0.00_ ';
                cellC7.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFBE123C' } };
                cellC7.alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 1; c <= 4; c++) {
                    worksheet.getCell(7, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF1F2' } };
                    worksheet.getCell(7, c).border = thinBorder;
                }

                const cellE7 = worksheet.getCell('E7');
                cellE7.value = 'TOTAL VOUCHER ENTRIES:';
                cellE7.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFB45309' } };
                cellE7.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                const cellG7 = worksheet.getCell('G7');
                cellG7.value = `${voucherCountVal} Vouchers`;
                cellG7.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFB45309' } };
                cellG7.alignment = { horizontal: 'right', vertical: 'middle' };

                for (let c = 5; c <= 9; c++) {
                    worksheet.getCell(7, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFEF3C7' } };
                    worksheet.getCell(7, c).border = thinBorder;
                }

                // Row 8: Spacer
                worksheet.addRow([]);
                worksheet.getRow(8).height = 14;

                // Row 9: Table Header Row
                const headerTitles = ['SL NO', 'DATE', 'VOUCHER NO.', 'PROJECT / SITE', 'CATEGORY', 'PARTICULARS / PURPOSE', 'PAYMENT MODE', 'BILL / REF NO.', 'AMOUNT (₹)'];
                const row9 = worksheet.addRow(headerTitles);
                row9.height = 30;
                for (let c = 1; c <= 9; c++) {
                    const hCell = worksheet.getCell(9, c);
                    hCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    hCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    hCell.border = headerBorder;
                    hCell.alignment = {
                        horizontal: (c === 1 || c === 2 || c === 7 || c === 8) ? 'center' : (c === 9 ? 'right' : 'left'),
                        vertical: 'middle',
                        indent: (c === 3 || c === 4 || c === 5 || c === 6) ? 1 : 0
                    };
                }

                // Data Rows
                let dataGrandTotal = 0;
                list.forEach((exp, idx) => {
                    const rowNum = 10 + idx;
                    const isEven = idx % 2 === 0;
                    const rowBg = isEven ? 'FFFFFFFF' : 'FFF0F8FF';
                    const amt = parseFloat(exp.amount) || 0;
                    dataGrandTotal += amt;

                    const row = worksheet.addRow([
                        idx + 1,
                        exp.formatted_date || '',
                        exp.voucher_number || '',
                        exp.project_name || '',
                        exp.category || '',
                        exp.particulars || '',
                        exp.payment_mode || '',
                        exp.bill_no || '—',
                        amt
                    ]);
                    row.height = 25;

                    for (let c = 1; c <= 9; c++) {
                        const cell = worksheet.getCell(rowNum, c);
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        cell.border = thinBorder;
                        cell.font = {
                            name: 'Calibri',
                            size: 10,
                            bold: (c === 1 || c === 3 || c === 5 || c === 9),
                            color: { argb: (c === 3 ? 'FF1E40AF' : 'FF000000') }
                        };

                        if (c === 1) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else if (c === 2) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else if (c === 3 || c === 4 || c === 5 || c === 6) {
                            cell.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };
                        } else if (c === 7 || c === 8) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        } else if (c === 9) {
                            cell.alignment = { horizontal: 'right', vertical: 'middle' };
                            cell.numFormat = '#,##0.00_ ';
                        }
                    }
                });

                // Total Summary Row
                const totalRowNum = 10 + list.length;
                worksheet.mergeCells(`A${totalRowNum}:H${totalRowNum}`);
                const totalRow = worksheet.getRow(totalRowNum);
                totalRow.height = 36;

                const labelCell = worksheet.getCell(`A${totalRowNum}`);
                labelCell.value = 'TOTAL SUMMARY';
                labelCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                labelCell.alignment = { horizontal: 'left', vertical: 'middle', indent: 1 };

                for (let c = 1; c <= 8; c++) {
                    worksheet.getCell(totalRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    worksheet.getCell(totalRowNum, c).border = headerBorder;
                }

                const sumCell = worksheet.getCell(`I${totalRowNum}`);
                sumCell.value = totalExpenseAmount > 0 ? totalExpenseAmount : dataGrandTotal;
                sumCell.numFormat = '#,##0.00_ ';
                sumCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                sumCell.alignment = { horizontal: 'right', vertical: 'middle' };
                sumCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                sumCell.border = headerBorder;

                // Save & Download XLSX
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                anchor.download = `HindustanERP_Daily_Site_Expenses_${new Date().toISOString().slice(0, 10)}.xlsx`;
                anchor.click();
                window.URL.revokeObjectURL(url);

            } catch (err) {
                console.error('Error generating Excel report:', err);
                alert('An error occurred while generating the Excel report.');
            } finally {
                this.isExporting = false;
            }
        }
    };
}
</script>
@endsection
