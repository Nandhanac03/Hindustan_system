@extends('layouts.erp')

@section('title', 'Site Expense Payment Release Desk')

@section('content')
<div x-data="siteExpensePaymentRelease()" class="space-y-6">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>SITE EXPENSE MANAGEMENT</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">SITE EXPENSE PAYMENT RELEASE</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Site Expense Treasury Payment Release Desk</span>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">Disbursements & Payment Vouchers</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('site-expenses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Site Expenses</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
                @if(session('print_voucher_id'))
                    <a href="{{ url('/vouchers/' . session('print_voucher_id') . '/payment-voucher-print') }}" target="_blank"
                       class="ml-3 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shadow-2xs">
                        <span>🖨 Open Printed Voucher</span>
                    </a>
                @endif
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif

    <!-- ── EXECUTIVE TREASURY KPI METRICS BAR (MATCHING CONTRACTOR PAYMENT RELEASE STYLING) ── -->
    <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Approved Site Expenses -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL APPROVED SITE EXPENSES</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $totalApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5 ">Total Approved Site Liability</div>
            </div>
        </div>

        <!-- Card 2: Total Disbursed (Paid) -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DISBURSED (PAID)</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors">₹{{ number_format((float) $totalPaid, 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5 ">Corporate Treasury Outflows</div>
            </div>
        </div>

        <!-- Card 3: Pending Disbursement Balances -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">PENDING DISBURSEMENT BALANCES</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors">₹{{ number_format((float) $totalBalance, 2) }}</div>
                <div class="text-[10px] text-rose-600 font-bold mt-1.5 pt-1.5 ">Outstanding Balance Remaining</div>
            </div>
        </div>

        <!-- Card 4: Ready For Payment -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-slate-800 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">READY FOR PAYMENT</span>
                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-800 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight group-hover:text-slate-800 transition-colors">{{ $readyCount }} Expenses</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Approved & Unpaid Vouchers</div>
            </div>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm mb-6 transition-all relative" :class="{ 'opacity-50 pointer-events-none': isLoading }">
        <form method="GET" action="{{ route('site-expenses.payment-release') }}" @submit.prevent="submitSearch" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                
                {{-- 1. Project Filter (Defaults to First Project) --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    @php
                        $defaultProjectId = $projects->first()->id ?? null;
                        $selectedProject = request('project_id', $defaultProjectId);
                    @endphp
                    <select name="project_id" @change="submitSearch"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $selectedProject == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 2. Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select name="payment_status" @change="submitSearch"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Payment Statuses</option>
                        <option value="pending_disbursement" {{ request('payment_status') === 'pending_disbursement' ? 'selected' : '' }}>Pending Disbursement</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Cleared</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                
                {{-- 4. Search Box --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Voucher #, Vendor..."
                           class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                </div>
            </div>

            <div class="flex gap-2">
                @if(request()->hasAny(['project_id', 'payment_status', 'search']))
                    <a href="{{ route('site-expenses.payment-release') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </a>
                @else
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>SEARCH</span>
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- ── PAYMENT DISBURSAL DESK TABLE ── -->
    <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Site Expense Payment Release Register</span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ $siteExpenses->total() }} Records</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3 py-3 text-left w-[130px]">VOUCHER NO</th>
                        <th class="px-3 py-3 text-left w-[180px]">PROJECT / FLOOR</th>
                        <th class="px-3 py-3 text-left w-[140px]">EXPENSE CATEGORY</th>
                        <th class="px-3 py-3 text-left w-[170px]">PAYEE / VENDOR</th>
                        <th class="px-3 py-3 text-left w-[120px]">NET APPROVED (₹)</th>
                        <th class="px-3 py-3 text-left text-emerald-100 w-[120px]">AMOUNT (₹)</th>
                        <th class="px-3 py-3 text-left text-rose-100 w-[120px]">BALANCE DUE (₹)</th>
                        <th class="px-3 py-3 text-left w-[110px]">STATUS</th>
                        <th class="px-3 py-3 text-center w-[140px]">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                    @forelse($siteExpenses as $expense)
                        @php
                            $bal = (float) $expense->balance_amount;
                            $net = (float) $expense->net_amount;
                            $paid = (float) $expense->paid_amount;
                            $isCleared = ($bal <= 0.001);
                            $paymentsCount = $expense->payments->count();
                        @endphp
                        <tbody x-data="{ showHistory: false }" class="border-b border-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <!-- Column 1: Voucher No + Date + Part-Paid Accordion Toggle -->
                                <td class="px-3 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-xs whitespace-nowrap shadow-2xs">{{ $expense->voucher_number }}</span>
                                        <div class="text-[10px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($expense->voucher_date)->format('d/m/Y') }}</div>
                                        @if($paymentsCount > 0)
                                            <button type="button" @click="showHistory = !showHistory"
                                                    class="px-1.5 py-0.5 bg-[#a38c29]/15 hover:bg-[#a38c29]/30 text-[#7a681d] rounded font-black text-[10px] cursor-pointer inline-flex items-center gap-1 transition shadow-2xs border border-[#a38c29]/40"
                                                    title="Toggle Part-by-Part Payment History">
                                                <span x-text="showHistory ? '▲ Hide History' : '▼ ' + {{ $paymentsCount }} + ' Part Paid'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <!-- Column 2: Project / Floor -->
                                <td class="px-3 py-3 align-middle">
                                    <div class="font-black text-slate-900 text-xs leading-tight">{{ $expense->project->name ?? 'Global Project' }}</div>
                                    <div class="text-[10px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $expense->floor->name ?? ($expense->tower_block_tag ?: 'General Site') }}</div>
                                </td>

                                <!-- Column 3: Expense Category -->
                                <td class="px-3 py-3 align-middle">
                                    <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-[10px] font-bold border border-slate-200/60">
                                        {{ $expense->expense_category_name }}
                                    </span>
                                </td>

                                <!-- Column 4: Payee / Vendor -->
                                <td class="px-3 py-3 align-middle">
                                    <div class="font-black text-slate-900 text-xs leading-tight">{{ $expense->payee_display_name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase font-medium mt-0.5">
                                        {{ $expense->vendor_id ? 'Registered Vendor' : ($expense->payee_type === 'registered' ? 'Registered Payee' : 'One-Time Payee') }}
                                    </div>
                                </td>

                                <!-- Column 5: Net Approved (₹) -->
                                <td class="px-3 py-3 text-left font-mono font-black text-[14px] text-blue-900 bg-blue-50/30 align-middle">
                                    ₹{{ number_format($net, 2) }}
                                </td>

                                <!-- Column 6: Amount (₹) (Paid Amount) -->
                                <td class="px-3 py-3 text-left font-mono font-bold text-[14px] text-emerald-700 align-middle">
                                    <div>₹{{ number_format($paid, 2) }}</div>
                                    @if($paymentsCount > 0)
                                        <div class="text-[9px] text-[#7a681d] font-bold">{{ $paymentsCount }} Installment(s)</div>
                                    @endif
                                </td>

                                <!-- Column 7: Balance Due (₹) -->
                                <td class="px-3 py-3 text-left font-mono font-black text-[14px] align-middle {{ $isCleared ? 'text-slate-400' : 'text-rose-700' }}">
                                    ₹{{ number_format($bal, 2) }}
                                </td>

                                <!-- Column 8: Status -->
                                <td class="px-3 py-3 text-left whitespace-nowrap align-middle">
                                    @if($isCleared)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <svg class="w-2.5 h-2.5 text-[#087443]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>CLEARED</span>
                                        </span>
                                    @elseif($paid > 0)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span>PARTIALLY PAID</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span>PENDING RELEASE</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Column 9: Action -->
                                <td class="px-3 py-3 text-center whitespace-nowrap align-middle">
                                    @if(!$isCleared)
                                        <button type="button" @click="openDisburseModal({{ json_encode($expense) }})"
                                                class="w-7 h-7 inline-flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-full hover:bg-emerald-600 hover:text-white transition-colors cursor-pointer group"
                                                title="Disburse Payment">
                                            <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </button>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Fully Paid</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Part-by-Part Payment History Expandable Accordion -->
                            @if($paymentsCount > 0)
                                <tr x-show="showHistory" x-cloak class="bg-amber-50/20 border-b border-[#a38c29]/30" x-transition.opacity>
                                    <td colspan="9" class="p-4">
                                        <div class="bg-white rounded-xl p-4 border border-[#a38c29]/30 shadow-sm space-y-3 text-left">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                                <span class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span>PART-BY-PART PAYMENT DISBURSEMENT HISTORY — VOUCHER #{{ $expense->voucher_number }}</span>
                                                </span>
                                                <span class="text-[10.5px] font-bold text-slate-600">Total Outflow Disbursed: <strong class="text-emerald-700 font-mono font-black text-xs">₹{{ number_format($paid, 2) }}</strong></span>
                                            </div>

                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left border-collapse text-[10.5px]">
                                                    <thead>
                                                        <tr class="bg-[#a38c29] text-white text-[9px] font-black uppercase tracking-wider border-b border-[#8a7522]">
                                                            <th class="px-3 py-2">INSTALLMENT #</th>
                                                            <th class="px-3 py-2">DISBURSEMENT DATE</th>
                                                            <th class="px-3 py-2">CORPORATE BANK / LOAN ACCOUNT</th>
                                                            <th class="px-3 py-2">PAYMENT MODE & REF #</th>
                                                            <th class="px-3 py-2">REMARKS</th>
                                                            <th class="px-3 py-2 text-right text-emerald-100">DISBURSED AMOUNT (₹)</th>
                                                            <th class="px-3 py-2 text-right">ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                                                        @foreach($expense->payments as $index => $pay)
                                                            <tr class="hover:bg-amber-50/30">
                                                                <td class="px-3 py-2 font-black text-slate-800">
                                                                    <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#a38c29] rounded font-mono text-[9.5px] font-bold">Part {{ $index + 1 }}</span>
                                                                </td>
                                                                <td class="px-3 py-2 font-mono text-slate-900 font-bold">
                                                                    {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') : '—' }}
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <div class="font-bold text-slate-900">
                                                                        {{ $pay->companyBankAccount ? $pay->companyBankAccount->bank_name : ($pay->loan ? $pay->loan->lender_name : 'Corporate Bank Account') }}
                                                                    </div>
                                                                    <div class="text-[9px] text-slate-500 font-mono">
                                                                        A/C: {{ $pay->companyBankAccount ? $pay->companyBankAccount->account_number : ($pay->loan ? $pay->loan->account_number : '—') }}
                                                                    </div>
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    @php
                                                                        $modeRaw = $pay->payment_mode ?? 'Bank Transfer';
                                                                        $modeLabel = str_replace('_', ' ', ucwords(strtolower($modeRaw), '_'));
                                                                    @endphp
                                                                    <span class="px-1.5 py-0.2 rounded bg-blue-100 text-blue-900 text-[8.5px] font-black uppercase">{{ $modeLabel }}</span>
                                                                    <span class="font-mono text-slate-700 font-bold ml-1">{{ $pay->reference_no ?: '—' }}</span>
                                                                </td>
                                                                <td class="px-3 py-2 text-slate-600 text-xs">
                                                                    {{ $pay->remarks ?: '—' }}
                                                                </td>
                                                                <td class="px-3 py-2 text-right font-mono font-black text-emerald-800 bg-emerald-50/30">
                                                                    ₹{{ number_format((float)$pay->paid_amount, 2) }}
                                                                </td>
                                                                <td class="px-3 py-2 text-right">
                                                                    @if($pay->voucher_id)
                                                                        <a href="{{ url('/vouchers/' . $pay->voucher_id . '/payment-voucher-print') }}" target="_blank"
                                                                           class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9px] font-bold inline-flex items-center gap-1 cursor-pointer shadow-2xs"
                                                                           title="Print Voucher for Part {{ $index + 1 }}">
                                                                            <span>🖨 Print Voucher</span>
                                                                        </a>
                                                                    @else
                                                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9px] font-bold border border-emerald-200">Disbursed</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                    No Site Expenses found matching the criteria.
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siteExpenses->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/40">
                {{ $siteExpenses->links() }}
            </div>
        @endif
    </div>

    <!-- ── MODAL: STAGGERED DISBURSEMENT RELEASE (OPTIMIZED FOR LAPTOP & DESKTOP) ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-2xl max-w-4xl w-full shadow-2xl overflow-hidden transform transition-all my-auto flex flex-col max-h-[92vh] border-0 ring-0 outline-none" @click.away="disburseModalOpen = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-3.5 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-0.5">Payment Disbursement</p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white">Disburse Staggered Site Expense Payment</h2>
                    </div>
                    <button type="button" @click="disburseModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="selectedExpense ? '{{ url('site-expenses') }}/' + selectedExpense.id + '/disburse' : '#'" method="POST" target="_blank" novalidate @submit="if(!validateDisburse()) { $event.preventDefault(); } else { disburseModalOpen = false; setTimeout(() => window.location.reload(), 1200); }" class="flex flex-col flex-1 overflow-hidden bg-white">
                @csrf

                <div class="p-5 space-y-3 overflow-y-auto flex-1">
                    <!-- Summary Card -->
                    <div class="p-3 bg-slate-50 border border-slate-200/90 rounded-xl grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="border-r border-slate-200/80 pr-2">
                            <span class="block text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">VOUCHER NO.</span>
                            <span class="text-xs font-mono font-extrabold text-slate-900 mt-0.5 block" x-text="selectedExpense ? selectedExpense.voucher_number : ''"></span>
                        </div>
                        <div class="border-r border-slate-200/80 pr-2">
                            <span class="block text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">NET APPROVED</span>
                            <span class="text-xs font-mono font-extrabold text-blue-900 mt-0.5 block" x-text="selectedExpense ? '₹' + numberFormat(selectedExpense.net_amount) : ''"></span>
                        </div>
                        <div>
                            <span class="block text-[9.5px] font-bold text-rose-700 uppercase tracking-wider">OUTSTANDING BAL.</span>
                            <span class="text-xs font-mono font-extrabold text-rose-700 mt-0.5 block" x-text="selectedExpense ? '₹' + numberFormat(selectedExpense.balance_amount) : ''"></span>
                        </div>
                        
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSEMENT DATE <span class="text-rose-500 font-bold">*</span></label>
                            <input type="date" name="payment_date" x-model="disbursePaymentDate" required
                                   class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all"
                                   :class="(hasAttemptedDisburseSubmit && !disbursePaymentDate) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 border-slate-200 focus:ring-2 focus:ring-emerald-500'">
                            <p x-show="hasAttemptedDisburseSubmit && !disbursePaymentDate" class="mt-1 text-[10px] font-bold text-rose-600">The disbursement date field is required.</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                                <button type="button" 
                                        @click="disbursePaidAmount = selectedExpense ? selectedExpense.balance_amount : ''; $nextTick(() => { const el = $el.closest('form').querySelector('input[name=\'paid_amount\']'); if(el && window.updateAmountInWordsForInput) window.updateAmountInWordsForInput(el); })"
                                        class="text-[10px] font-bold text-[#a38c29] hover:underline cursor-pointer">
                                    Pay Full Balance
                                </button>
                            </div>
                            <input type="number" step="0.01" min="0.01" name="paid_amount" x-model="disbursePaidAmount" :max="selectedExpense ? selectedExpense.balance_amount : null" placeholder="Enter amount to pay (e.g. 50000)..." required
                                   oninput="window.updateAmountInWordsForInput && window.updateAmountInWordsForInput(this)"
                                   class="w-full px-3 py-2 border rounded-xl text-xs font-mono font-black text-slate-900 focus:outline-none transition-all shadow-2xs"
                                   :class="(hasAttemptedDisburseSubmit && (!disbursePaidAmount || parseFloat(disbursePaidAmount) <= 0 || (selectedExpense && parseFloat(disbursePaidAmount) > parseFloat(selectedExpense.balance_amount)))) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'">
                            <p x-show="hasAttemptedDisburseSubmit && (!disbursePaidAmount || parseFloat(disbursePaidAmount) <= 0)" class="mt-1 text-[10px] font-bold text-rose-600">The amount field is required.</p>
                            <p x-show="selectedExpense && disbursePaidAmount && parseFloat(disbursePaidAmount) > parseFloat(selectedExpense.balance_amount)" class="mt-1 text-[10px] font-bold text-rose-600">Amount cannot exceed outstanding balance of ₹<span x-text="numberFormat(selectedExpense.balance_amount)"></span>.</p>
                            
                            {{-- Amount in Words Under Input Box --}}
                            <div class="amount-in-words-label text-[10px] text-amber-800 font-extrabold capitalize mt-1.5 px-2.5 py-1 rounded-lg bg-amber-50/90 border border-amber-200/80 tracking-wide transition-all leading-snug break-words block w-full shadow-xs"
                                 x-show="disbursePaidAmount && parseFloat(disbursePaidAmount) > 0"
                                 x-text="numberToWords(disbursePaidAmount)">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAYMENT SOURCE <span class="text-rose-500 font-bold">*</span></label>
                            <select name="payment_source_type" x-model="sourceType" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] focus:outline-none transition-all">
                                <option value="bank">Company Bank Account</option>
                                <option value="loan">Project Bank Loan</option>
                            </select>
                        </div>

                        <div x-show="sourceType === 'bank'" class="relative" @click.outside="bankOpen = false">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                            <input type="hidden" name="company_bank_account_id" :value="selectedBankId" :required="sourceType === 'bank'">

                            <!-- Trigger Button -->
                            <div @click="bankOpen = !bankOpen; if(bankOpen) $nextTick(() => $refs.payBankSearch?.focus())"
                                 class="w-full min-h-[38px] px-3 py-2 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs"
                                 :class="(hasAttemptedDisburseSubmit && sourceType === 'bank' && !selectedBankId) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white border-slate-200 hover:border-[#a38c29]/60'">
                                <template x-if="selectedAccount">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#8a7522] rounded-md font-bold text-[10px]" x-text="selectedAccount.bank_name"></span>
                                        <span class="font-bold text-slate-800 truncate" x-text="selectedAccount.account_name || selectedAccount.bank_name"></span>
                                        <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (selectedAccount.account_number || '—') + ')'"></span>
                                    </div>
                                </template>
                                <template x-if="!selectedAccount">
                                    <span class="text-slate-400 font-normal">Select Company Bank Account...</span>
                                </template>
                                <svg class="w-4 h-4 text-slate-400 transition-transform shrink-0 ml-1.5" :class="bankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <p x-show="hasAttemptedDisburseSubmit && sourceType === 'bank' && !selectedBankId" class="mt-1 text-[10px] font-bold text-rose-600">The bank account field is required.</p>

                            {{-- Selected Bank Balance in Words Only --}}
                            <div class="mt-1.5 flex items-baseline justify-between gap-2 text-[11px]" x-show="selectedAccount">
                                <span class="text-slate-500 font-medium shrink-0">Selected Bank Balance:</span>
                                <span class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                      x-text="numberToWords(selectedAccount?.current_balance || 0)"></span>
                            </div>

                            <!-- Dropdown Search Menu -->
                            <div x-show="bankOpen" x-transition class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden max-h-56 flex flex-col" style="display: none;">
                                <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                                    <div class="relative">
                                        <input type="text" x-ref="payBankSearch" x-model="bankSearch" placeholder="Search bank name, account no, branch..." class="w-full pl-7 pr-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                        <svg class="w-3 h-3 text-slate-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </div>
                                <div class="overflow-y-auto divide-y divide-slate-100">
                                    <template x-for="acc in filteredBankAccounts" :key="acc.id">
                                        <div @click="selectedBankId = acc.id; bankOpen = false; bankSearch = ''"
                                             class="px-3 py-2 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                             :class="selectedBankId == acc.id ? 'bg-[#a38c29]/10 font-bold border-l-4 border-l-[#a38c29]' : ''">
                                            <div class="flex flex-col min-w-0 pr-2">
                                                <div class="flex items-center gap-1.5 truncate">
                                                    <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                    <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                                </div>
                                                <div class="text-[9px] text-slate-400 font-mono mt-0.5" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                            </div>
                                            <div class="text-right font-mono shrink-0">
                                                <div class="text-[8px] text-slate-400 uppercase font-sans">Current Balance</div>
                                                <div class="font-bold text-slate-800 text-[11px]" x-text="'₹ ' + numberFormat(acc.current_balance || 0)"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="filteredBankAccounts.length === 0">
                                        <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="sourceType === 'loan'" style="display: none;">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">SELECT PROJECT LOAN <span class="text-rose-500 font-bold">*</span></label>
                            <select name="loan_id" x-model="selectedLoanId" :required="sourceType === 'loan'" 
                                    class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all"
                                    :class="(hasAttemptedDisburseSubmit && sourceType === 'loan' && !selectedLoanId) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 border-slate-200 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]'">
                                <option value="">Select Project Loan</option>
                                @foreach($loans as $l)
                                    <option value="{{ $l->id }}">
                                        {{ $l->lender_name }} — Loan A/C: {{ $l->account_number }} (Outstanding: ₹{{ number_format((float)$l->outstanding_balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="hasAttemptedDisburseSubmit && sourceType === 'loan' && !selectedLoanId" class="mt-1 text-[10px] font-bold text-rose-600">The loan account field is required.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                            <select name="payment_mode" x-model="disbursePaymentMode" required 
                                    class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all"
                                    :class="(hasAttemptedDisburseSubmit && !disbursePaymentMode) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 border-slate-200 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]'">
                                @foreach(($paymentModes ?? []) as $pm)
                                    @php
                                        $pmCode = is_object($pm) ? ($pm->code ?? $pm->name) : $pm;
                                        $pmName = is_object($pm) ? ($pm->name ?? $pm->code) : $pm;
                                    @endphp
                                    <option value="{{ $pmCode }}">{{ $pmName }}</option>
                                @endforeach
                            </select>
                            <p x-show="hasAttemptedDisburseSubmit && !disbursePaymentMode" class="mt-1 text-[10px] font-bold text-rose-600">The payment mode field is required.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">REFERENCE NO (CHEQUE # / UTR #) <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="reference_no" x-model="disburseRefNo" placeholder="e.g. UTR123456789 or Chq #000123" required
                                   class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs"
                                   :class="(hasAttemptedDisburseSubmit && !disburseRefNo) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'">
                            <p x-show="hasAttemptedDisburseSubmit && !disburseRefNo" class="mt-1 text-[10px] font-bold text-rose-600">The reference number field is required.</p>
                        </div>
                    </div>

                    <!-- ── LIVE BANK BALANCE & EXPENSE OUTFLOW ANALYSIS STRIP (WHEN SOURCE IS BANK) ── -->
                    <div x-show="sourceType === 'bank'" class="p-3.5 bg-slate-50 border border-slate-200/90 rounded-2xl shadow-2xs space-y-2.5">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2.5">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-800">Bank Balance &amp; Expense Outflow Analysis</span>
                            </div>
                            <div>
                                <span x-show="isBankSufficient()" class="px-2.5 py-1 rounded-full text-[9.5px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Sufficient Bank Balance</span>
                                </span>
                                <span x-show="!isBankSufficient()" class="px-2.5 py-1 rounded-full text-[9.5px] font-black bg-rose-100 text-rose-800 border border-rose-300 inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Insufficient Funds: ₹<span x-text="numberFormat(getShortfall())"></span>)</span>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- 1. Bank Account Balance & Post-Payment Balance -->
                            <div class="p-4 bg-white rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:border-slate-300 transition-all">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-wider">CURRENT BANK BALANCE</span>
                                    <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                </div>
                                <div class="font-mono font-black text-slate-900 text-xl sm:text-2xl mt-0.5" x-text="'₹ ' + numberFormat(getBankBalance())"></div>
                                
                                <div class="mt-3 pt-2.5 border-t border-slate-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600">Post-Payment:</span>
                                        <strong class="font-mono font-black text-base sm:text-lg" :class="getPostBankBalance() >= 0 ? 'text-emerald-700' : 'text-rose-600'" x-text="'₹ ' + numberFormat(getPostBankBalance())"></strong>
                                    </div>
                                    <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-50">
                                        <span class="text-slate-500 font-semibold">Bank:</span>
                                        <span class="font-black text-slate-900 text-xs sm:text-sm" x-text="getSelectedBankName()"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. This Site Expense Remaining Balance -->
                            <div class="p-4 bg-white rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:border-slate-300 transition-all">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-wider">REMAINING VOUCHER BALANCE</span>
                                    <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                </div>
                                <div class="font-mono font-black text-xl sm:text-2xl mt-0.5" :class="getExpenseRemaining() == 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="'₹ ' + numberFormat(getExpenseRemaining())"></div>
                                
                                <div class="mt-3 pt-2.5 border-t border-slate-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600">Current Due:</span>
                                        <span class="font-mono font-black text-base sm:text-lg text-slate-800" x-text="'₹ ' + numberFormat(selectedExpense ? selectedExpense.balance_amount : 0)"></span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-50">
                                        <span class="text-slate-500 font-semibold">Voucher:</span>
                                        <span class="font-mono font-black text-slate-900 text-xs sm:text-sm" x-text="selectedExpense ? selectedExpense.voucher_number : '-'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSEMENT REMARKS / AUDIT NOTES</label>
                        <textarea name="remarks" rows="2" placeholder="e.g. Disbursed against site material delivery inspection..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                    </div>
                </div>

                <!-- Pinned Footer - Guaranteed Always Visible on All Laptops -->
                <div class="p-3.5 px-6 flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 flex-shrink-0">
                    <button type="button" @click="disburseModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/20 border border-[#a38c29]/40 cursor-pointer">
                        RELEASE PAYMENT &amp; PRINT VOUCHER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POPUP ERROR ALERT MODAL ── -->
    <div x-show="openErrorModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/75 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-rose-200 p-6 text-center transform transition-all" @click.away="openErrorModal = false">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4 border border-rose-200 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-[#a38c29] text-[10px] font-black uppercase tracking-widest mb-1">TREASURY ALERT</p>
            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-2">Disbursement Failed</h3>
            <div class="text-xs text-rose-800 font-bold bg-rose-50/90 p-4 rounded-2xl border border-rose-200/80 mb-5 text-center leading-relaxed shadow-xs">
                @if(session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if($errors->any())
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                @endif
            </div>
            <button type="button" @click="openErrorModal = false"
                    class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition cursor-pointer">
                CLOSE ALERT &amp; SELECT VALID BANK
            </button>
        </div>
    </div>

</div>

<script>
function siteExpensePaymentRelease() {
    return {
        disburseModalOpen: false,
        openErrorModal: {{ ($errors->any() || session('error')) ? 'true' : 'false' }},
        selectedExpense: null,
        sourceType: 'bank',
        selectedBankId: '{{ $companyBankAccounts->first()?->id ?? "" }}',
        selectedLoanId: '',
        disbursePaidAmount: '',
        companyBankAccounts: @json($companyBankAccounts ?? []),
        payeeBalances: @json($payeeBalances ?? []),
        bankOpen: false,
        bankSearch: '',
        
        isLoading: false,
        hasAttemptedDisburseSubmit: false,
        disbursePaymentDate: '{{ date("Y-m-d") }}',
        disbursePaymentMode: '{!! isset($paymentModes[0]) ? (is_object($paymentModes[0]) ? ($paymentModes[0]->code ?? $paymentModes[0]->name) : $paymentModes[0]) : "" !!}',
        disburseRefNo: '',

        async submitSearch(event) {
            this.isLoading = true;
            const form = event.target.closest('form') || document.querySelector('form[action="{{ route('site-expenses.payment-release') }}"]');
            const url = new URL(form.action);
            const formData = new FormData(form);
            const searchParams = new URLSearchParams();
            
            formData.forEach((value, key) => {
                if (value) searchParams.append(key, value);
            });
            url.search = searchParams.toString();
            
            // update URL silently without reloading
            window.history.pushState({}, '', url);

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Replace table container
                const newTable = doc.querySelector('#table-container'); 
                const currentTable = document.querySelector('#table-container');
                if (currentTable && newTable) {
                    currentTable.innerHTML = newTable.innerHTML;
                }
                
                // Replace dashboard stats
                const newDashboard = doc.querySelector('#dashboard-stats');
                const currentDashboard = document.querySelector('#dashboard-stats');
                if (currentDashboard && newDashboard) {
                    currentDashboard.innerHTML = newDashboard.innerHTML;
                }
            } catch (error) {
                console.error('Filter error', error);
                // Fallback to normal submit
                window.location.href = url.toString();
            } finally {
                this.isLoading = false;
            }
        },

        validateDisburse() {
            this.hasAttemptedDisburseSubmit = true;
            if (!this.disbursePaymentDate || !this.disbursePaidAmount || parseFloat(this.disbursePaidAmount) <= 0 || 
                (this.sourceType === 'bank' && !this.selectedBankId) || 
                (this.sourceType === 'loan' && !this.selectedLoanId) || 
                !this.disbursePaymentMode || !this.disburseRefNo) {
                return false;
            }
            if (this.selectedExpense && parseFloat(this.disbursePaidAmount) > parseFloat(this.selectedExpense.balance_amount)) {
                return false;
            }
            return true;
        },

        get selectedAccount() {
            if (!this.selectedBankId) return null;
            return this.companyBankAccounts.find(x => x.id == this.selectedBankId) || null;
        },

        get filteredBankAccounts() {
            if (!this.bankSearch) return this.companyBankAccounts;
            const q = this.bankSearch.toLowerCase().trim();
            return this.companyBankAccounts.filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
        },

        openDisburseModal(expense) {
            this.selectedExpense = expense;
            this.sourceType = expense.payment_source_type || 'bank';
            this.disbursePaidAmount = '';
            this.bankOpen = false;
            this.bankSearch = '';
            
            this.hasAttemptedDisburseSubmit = false;
            this.disbursePaymentDate = '{{ date("Y-m-d") }}';
            this.disbursePaymentMode = '{!! isset($paymentModes[0]) ? (is_object($paymentModes[0]) ? ($paymentModes[0]->code ?? $paymentModes[0]->name) : $paymentModes[0]) : "" !!}';
            this.disburseRefNo = '';
            this.selectedLoanId = '';
            
            if (!this.selectedBankId && this.companyBankAccounts.length > 0) {
                this.selectedBankId = this.companyBankAccounts[0].id;
            }
            this.disburseModalOpen = true;

            this.$nextTick(() => {
                const inputEl = document.querySelector('input[name="paid_amount"]');
                if (inputEl && window.updateAmountInWordsForInput) {
                    window.updateAmountInWordsForInput(inputEl);
                }
            });
        },

        numberToWords(val) {
            let num = parseFloat(val) || 0;
            if (num <= 0) return '';
            let integerPart = Math.floor(num);
            let decimalPart = Math.round((num - integerPart) * 100);

            const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            function toWords(n) {
                if (n < 20) return a[n];
                let digit = n % 10;
                return b[Math.floor(n / 10)] + (digit ? ' ' + a[digit] : '');
            }

            let str = '';
            let crore = Math.floor(integerPart / 10000000);
            integerPart %= 10000000;
            let lakh = Math.floor(integerPart / 100000);
            integerPart %= 100000;
            let thousand = Math.floor(integerPart / 1000);
            integerPart %= 1000;
            let hundred = Math.floor(integerPart / 100);
            let rest = integerPart % 100;

            if (crore > 0) str += toWords(crore) + ' Crore ';
            if (lakh > 0) str += toWords(lakh) + ' Lakh ';
            if (thousand > 0) str += toWords(thousand) + ' Thousand ';
            if (hundred > 0) str += toWords(hundred) + ' Hundred ';
            if (rest > 0) str += (str !== '' ? 'and ' : '') + toWords(rest) + ' ';

            let res = str.trim() ? str.trim() + ' Rupees' : '';
            if (decimalPart > 0) {
                let paiseStr = toWords(decimalPart) + ' Paise';
                res = res ? res + ' and ' + paiseStr : paiseStr;
            }
            return res ? res + ' Only' : '';
        },

        getSelectedBankName() {
            if (!this.selectedBankId) return '-';
            const b = this.companyBankAccounts.find(x => x.id == this.selectedBankId);
            return b ? (b.bank_name || 'Bank Account') : '-';
        },

        getBankBalance() {
            if (!this.selectedBankId) return 0;
            const b = this.companyBankAccounts.find(x => x.id == this.selectedBankId);
            return b ? parseFloat(b.current_balance) || 0 : 0;
        },

        getPostBankBalance() {
            const current = this.getBankBalance();
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return current - paid;
        },

        isBankSufficient() {
            if (this.sourceType !== 'bank') return true;
            return this.getPostBankBalance() >= 0;
        },

        getShortfall() {
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            const current = this.getBankBalance();
            return Math.max(0, paid - current);
        },

        getExpenseRemaining() {
            const expenseBal = parseFloat(this.selectedExpense?.balance_amount) || 0;
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return Math.max(0, expenseBal - paid);
        },

        getPayeeTotalDues() {
            if (!this.selectedExpense) return 0;
            const vId = this.selectedExpense.vendor_id;
            const pId = this.selectedExpense.payee_id;
            const cName = this.selectedExpense.casual_payee_name;

            if (this.payeeBalances) {
                const key = vId ? ('v_' + vId) : (pId ? ('p_' + pId) : ('c_' + cName));
                if (this.payeeBalances[key] !== undefined) {
                    return parseFloat(this.payeeBalances[key]) || 0;
                }
            }
            return parseFloat(this.selectedExpense.balance_amount) || 0;
        },

        getPayeePostDues() {
            const currentTotal = this.getPayeeTotalDues();
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return Math.max(0, currentTotal - paid);
        },

        numberFormat(val) {
            return (parseFloat(val) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    };
}
</script>
@endsection
