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
                <span class="text-[#a38c29] font-bold">PAYMENT RELEASE DESK</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Site Expense Payment Release Desk</span>
                <span class="text-xs bg-amber-100 text-[#8a7522] px-2.5 py-0.5 rounded-full font-bold">Disbursements & Payment Vouchers</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('site-expenses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Site Expenses</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75">✕</button>
        </div>
    @endif

    <!-- ── EXECUTIVE TREASURY KPI METRICS BAR ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Approved Site Expenses -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">TOTAL APPROVED EXPENSES</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $totalApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5 border-t border-blue-50">Total Approved Site Liability</div>
            </div>
        </div>

        <!-- Card 2: Total Disbursed (Paid) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">TOTAL DISBURSED (PAID)</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors">₹{{ number_format((float) $totalPaid, 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5 border-t border-emerald-50">Corporate Treasury Outflows</div>
            </div>
        </div>

        <!-- Card 3: Pending Disbursement Balances -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">PENDING DISBURSEMENT</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors">₹{{ number_format((float) $totalBalance, 2) }}</div>
                <div class="text-[10px] text-rose-600 font-bold mt-1.5 pt-1.5 border-t border-rose-50">Outstanding Balance Remaining</div>
            </div>
        </div>

        <!-- Card 4: Ready For Payment -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">READY FOR PAYMENT</span>
                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-800 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight group-hover:text-slate-800 transition-colors">{{ $readyCount }} Expenses</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Approved & Unpaid Vouchers</div>
            </div>
        </div>
    </div>

    <!-- ── PAYMENT DISBURSAL DESK TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        {{-- Table Filter Bar --}}
        <form method="GET" action="{{ route('site-expenses.payment-release') }}" class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Site Expense Payment Register</span>
                <span class="text-[11px] bg-amber-100 text-[#8a7522] px-2.5 py-0.5 rounded-full font-bold">{{ $siteExpenses->total() }} Records</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Project Filter --}}
                <select name="project_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="payment_status" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                    <option value="">All Payment Statuses</option>
                    <option value="pending_disbursement" {{ request('payment_status') === 'pending_disbursement' ? 'selected' : '' }}>Pending Disbursement (Balance > 0)</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid (₹0 Paid)</option>
                    <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Fully Paid (Cleared)</option>
                </select>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Voucher #, Vendor..."
                           class="px-3.5 py-2 pl-8 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-64 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                @if(request()->hasAny(['project_id', 'payment_status', 'search']))
                    <a href="{{ route('site-expenses.payment-release') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3.5 py-3 text-left w-[130px]">VOUCHER NO</th>
                        <th class="px-3.5 py-3 text-left w-[180px]">PROJECT / FLOOR</th>
                        <th class="px-3.5 py-3 text-left w-[150px]">EXPENSE CATEGORY</th>
                        <th class="px-3.5 py-3 text-left w-[170px]">PAYEE / VENDOR</th>
                        <th class="px-3.5 py-3 text-right w-[120px]">NET AMOUNT (₹)</th>
                        <th class="px-3.5 py-3 text-right text-emerald-100 w-[120px]">PAID AMOUNT (₹)</th>
                        <th class="px-3.5 py-3 text-right text-rose-100 w-[120px]">BALANCE DUE (₹)</th>
                        <th class="px-3.5 py-3 text-center w-[110px]">STATUS</th>
                        <th class="px-3.5 py-3 text-right w-[140px]">ACTION</th>
                    </tr>
                </thead>
                @forelse($siteExpenses as $expense)
                    @php
                        $bal = (float) $expense->balance_amount;
                        $net = (float) $expense->net_amount;
                        $paid = (float) $expense->paid_amount;
                        $isCleared = ($bal <= 0.001);
                        $paymentsCount = $expense->payments->count();
                    @endphp
                    <tbody x-data="{ showHistory: false }" class="border-b border-slate-100 hover:bg-slate-50/60 transition-colors text-xs font-semibold">
                        <tr>
                            <td class="px-3.5 py-3">
                                <span class="font-mono font-black text-slate-900">{{ $expense->voucher_number }}</span>
                                <div class="text-[10px] text-slate-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($expense->voucher_date)->format('d/m/Y') }}</div>
                            </td>

                            <td class="px-3.5 py-3">
                                <div class="font-bold text-slate-900">{{ $expense->project->name ?? 'Global Project' }}</div>
                                <div class="text-[10px] text-slate-400 font-semibold">{{ $expense->floor->name ?? ($expense->tower_block_tag ?: 'General Site') }}</div>
                            </td>

                            <td class="px-3.5 py-3">
                                <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-[10px] font-bold">
                                    {{ $expense->expense_category_name }}
                                </span>
                            </td>

                            <td class="px-3.5 py-3">
                                <div class="font-bold text-slate-900">{{ $expense->payee_display_name }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-medium">
                                    {{ $expense->payee_type === 'registered' ? 'Registered Master Payee' : 'One-Time Payee' }}
                                </div>
                            </td>

                            <td class="px-3.5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($net, 2) }}
                            </td>

                            <td class="px-3.5 py-3 text-right font-mono font-black text-emerald-700">
                                ₹{{ number_format($paid, 2) }}
                            </td>

                            <td class="px-3.5 py-3 text-right font-mono font-black text-rose-700">
                                ₹{{ number_format($bal, 2) }}
                            </td>

                            <td class="px-3.5 py-3 text-center">
                                @if($isCleared)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                                        <span>✓ Paid</span>
                                    </span>
                                @elseif($paid > 0)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                                        <span>Partially Paid</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-800 border border-rose-300 inline-flex items-center gap-1">
                                        <span>Unpaid</span>
                                    </span>
                                @endif
                            </td>

                            <td class="px-3.5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(!$isCleared)
                                        <button type="button" @click="openDisburseModal(@js($expense))"
                                                class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-black uppercase tracking-wider shadow-xs hover:shadow transition inline-flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Disburse</span>
                                        </button>
                                    @endif

                                    @if($paymentsCount > 0)
                                        <button type="button" @click="showHistory = !showHistory"
                                                class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase transition inline-flex items-center gap-1.5 cursor-pointer shadow-2xs"
                                                :class="showHistory ? 'bg-[#a38c29] text-white border-[#8a7522]' : 'bg-amber-50 hover:bg-amber-100 text-[#a38c29] border-amber-300/80'"
                                                :title="showHistory ? 'Hide Installments' : 'View ' + {{ $paymentsCount }} + ' Installment(s)'">
                                            <svg class="w-3 h-3 text-[#a38c29]" :class="showHistory ? 'text-white' : 'text-[#a38c29]'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span>{{ $paymentsCount }} {{ Str::plural('Payout', $paymentsCount) }}</span>
                                            <svg class="w-3 h-3 transition-transform duration-200" :class="showHistory ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Payment Installment History Accordion with Gold ERP Theme --}}
                        @if($paymentsCount > 0)
                            <tr x-show="showHistory" x-cloak class="bg-amber-50/20 border-b border-[#a38c29]/30" x-transition.opacity>
                                <td colspan="9" class="p-3.5 sm:p-4">
                                    <div class="bg-white rounded-xl p-4 border border-[#a38c29]/30 shadow-sm space-y-3 text-left">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-2.5 gap-2">
                                            <span class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] flex items-center justify-center border border-amber-200/50">
                                                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                </div>
                                                <span>DISBURSEMENT INSTALLMENT LOG — VOUCHER #{{ $expense->voucher_number }}</span>
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-600">
                                                Total Outflow Disbursed: <strong class="text-emerald-700 font-mono font-black text-xs">₹{{ number_format($paid, 2) }}</strong>
                                            </span>
                                        </div>

                                        <div class="overflow-x-auto rounded-lg border border-slate-200/80">
                                            <table class="w-full text-left text-[11px] border-collapse">
                                                <thead>
                                                    <tr class="bg-[#a38c29] text-white text-[9px] font-black uppercase tracking-wider border-b border-[#8a7522]">
                                                        <th class="px-3 py-2.5">INSTALLMENT #</th>
                                                        <th class="px-3 py-2.5">DISBURSEMENT DATE</th>
                                                        <th class="px-3 py-2.5">CORPORATE BANK / LOAN ACCOUNT</th>
                                                        <th class="px-3 py-2.5">PAYMENT MODE & REF #</th>
                                                        <th class="px-3 py-2.5">REMARKS</th>
                                                        <th class="px-3 py-2.5 text-right text-emerald-100">DISBURSED AMOUNT (₹)</th>
                                                        <th class="px-3 py-2.5 text-right">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                                                    @foreach($expense->payments as $index => $pay)
                                                        <tr class="hover:bg-amber-50/30 transition">
                                                            <td class="px-3 py-2.5">
                                                                <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#a38c29] rounded font-mono text-[9.5px] font-bold border border-[#a38c29]/20">Part {{ $index + 1 }}</span>
                                                            </td>
                                                            <td class="px-3 py-2.5 font-mono text-slate-900 font-bold">
                                                                {{ \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-3 py-2.5">
                                                                <div class="font-bold text-slate-900">
                                                                    {{ $pay->companyBankAccount ? $pay->companyBankAccount->bank_name : ($pay->loan ? $pay->loan->lender_name : 'Corporate Bank Account') }}
                                                                </div>
                                                                <div class="text-[9.5px] text-slate-500 font-mono">
                                                                    A/C: {{ $pay->companyBankAccount ? $pay->companyBankAccount->account_number : ($pay->loan ? $pay->loan->account_number : '—') }}
                                                                </div>
                                                            </td>
                                                            <td class="px-3 py-2.5">
                                                                @php
                                                                    $modeRaw = $pay->payment_mode ?? 'Bank Transfer';
                                                                    $modeLabel = str_replace('_', ' ', ucwords(strtolower($modeRaw), '_'));
                                                                @endphp
                                                                <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-900 text-[8.5px] font-black uppercase tracking-wider">{{ $modeLabel }}</span>
                                                                <span class="font-mono text-slate-700 font-bold ml-1.5">{{ $pay->reference_no ?: '—' }}</span>
                                                            </td>
                                                            <td class="px-3 py-2.5 text-slate-600 text-xs">
                                                                {{ $pay->remarks ?: '—' }}
                                                            </td>
                                                            <td class="px-3 py-2.5 text-right font-mono font-black text-emerald-800 bg-emerald-50/40">
                                                                ₹{{ number_format((float)$pay->paid_amount, 2) }}
                                                            </td>
                                                            <td class="px-3 py-2.5 text-right">
                                                                @if($pay->voucher_id)
                                                                    <a href="/vouchers/{{ $pay->voucher_id }}/payment-voucher-print" target="_blank"
                                                                       class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9px] font-bold inline-flex items-center gap-1 cursor-pointer shadow-2xs"
                                                                       title="Print Payment Voucher">
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
            </table>
        </div>

        @if($siteExpenses->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $siteExpenses->links() }}
            </div>
        @endif
    </div>

    <!-- ── POPUP MODAL: DISBURSE SITE EXPENSE PAYMENT ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="disburseModalOpen = false">
            <div class="bg-[#2a2415] px-6 py-4 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 text-[9px] font-bold uppercase tracking-wider rounded border border-emerald-500/40 mb-0.5">TREASURY DISBURSEMENT</span>
                    <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-white">DISBURSE SITE EXPENSE PAYMENT</h3>
                </div>
                <button type="button" @click="disburseModalOpen = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form :action="selectedExpense ? '{{ url('site-expenses') }}/' + selectedExpense.id + '/disburse' : '#'" method="POST" class="px-6 pt-3.5 pb-6 space-y-3.5">
                @csrf

                <!-- Summary Header Card -->
                <div class="p-3 bg-slate-50 border border-slate-200/90 rounded-xl grid grid-cols-3 gap-3 text-center text-xs">
                    <div class="border-r border-slate-200/80 pr-2">
                        <span class="block text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">VOUCHER NO.</span>
                        <span class="text-xs font-mono font-extrabold text-slate-900 mt-0.5 block" x-text="selectedExpense ? selectedExpense.voucher_number : ''"></span>
                    </div>
                    <div class="border-r border-slate-200/80 pr-2">
                        <span class="block text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">NET BILL AMOUNT</span>
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
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-emerald-900 uppercase tracking-wider mb-1">DISBURSEMENT AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" :max="selectedExpense ? selectedExpense.balance_amount : 0" required
                               class="w-full px-3 py-2 bg-emerald-50/70 border border-emerald-300 rounded-xl text-xs font-mono font-extrabold text-emerald-950 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                        <p class="mt-1 text-[10px] font-bold text-slate-500" x-text="selectedExpense ? 'Max Payable Balance: ₹' + numberFormat(selectedExpense.balance_amount) : ''"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAYMENT SOURCE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_source_type" x-model="sourceType" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                            <option value="bank">Company Bank Account</option>
                            <option value="loan">Project Bank Loan</option>
                        </select>
                    </div>

                    <div x-show="sourceType === 'bank'">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="company_bank_account_id" :required="sourceType === 'bank'" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }} (Bal: ₹{{ number_format((float)$bank->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="sourceType === 'loan'" style="display: none;">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">SELECT PROJECT LOAN <span class="text-rose-500 font-bold">*</span></label>
                        <select name="loan_id" :required="sourceType === 'loan'" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                            @foreach($loans as $l)
                                <option value="{{ $l->id }}">
                                    {{ $l->lender_name }} — Loan A/C: {{ $l->account_number }} (Outstanding: ₹{{ number_format((float)$l->outstanding_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_mode" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                            @foreach(($paymentModes ?? []) as $pm)
                                @php
                                    $pmCode = is_object($pm) ? ($pm->code ?? $pm->name) : $pm;
                                    $pmName = is_object($pm) ? ($pm->name ?? $pm->code) : $pm;
                                @endphp
                                <option value="{{ $pmCode }}">{{ $pmName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">REFERENCE NO (UTR / CHEQUE #)</label>
                        <input type="text" name="reference_no" placeholder="e.g. UTR987654321 or Chq #000456"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSEMENT REMARKS / AUDIT NOTES</label>
                    <textarea name="remarks" rows="2" placeholder="e.g. Part payment released as per milestone inspection..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="disburseModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-600/20 cursor-pointer inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>CONFIRM & DISBURSE</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function siteExpensePaymentRelease() {
    return {
        disburseModalOpen: false,
        selectedExpense: null,
        sourceType: 'bank',

        openDisburseModal(expense) {
            this.selectedExpense = expense;
            this.sourceType = expense.payment_source_type || 'bank';
            this.disburseModalOpen = true;

            this.$nextTick(() => {
                const amountInput = document.querySelector('input[name="paid_amount"]');
                if (amountInput && expense.balance_amount) {
                    amountInput.value = parseFloat(expense.balance_amount).toFixed(2);
                }
            });
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
