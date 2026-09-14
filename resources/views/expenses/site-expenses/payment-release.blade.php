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
                    <a href="/vouchers/{{ session('print_voucher_id') }}/payment-voucher-print" target="_blank"
                       class="ml-3 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shadow-2xs">
                        <span>🖨 Open Printed Voucher</span>
                    </a>
                @endif
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif

    <!-- ── EXECUTIVE TREASURY KPI METRICS BAR (MATCHING CONTRACTOR PAYMENT RELEASE STYLING) ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Approved Site Expenses -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">TOTAL APPROVED SITE EXPENSES</span>
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
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
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
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">PENDING DISBURSEMENT BALANCES</span>
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
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-slate-800 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
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

    <!-- ── PAYMENT DISBURSAL DESK TABLE (MATCHING CONTRACTOR RELEASE REGISTER) ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        {{-- Table Filter Bar --}}
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Site Expense Payment Release Register</span>
                <span class="text-[11px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">{{ $siteExpenses->total() }} Records</span>
            </div>

            <form method="GET" action="{{ route('site-expenses.payment-release') }}" class="flex flex-wrap items-center gap-2.5">
                {{-- Project Filter --}}
                <select name="project_id" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="payment_status" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                    <option value="">All Payment Statuses</option>
                    <option value="pending_disbursement" {{ request('payment_status') === 'pending_disbursement' ? 'selected' : '' }}>Pending Disbursement</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Cleared</option>
                </select>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Voucher #, Vendor..."
                           class="px-3.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none w-64 sm:w-72 shadow-2xs">
                </div>

                @if(request()->hasAny(['project_id', 'payment_status', 'search']))
                    <a href="{{ route('site-expenses.payment-release') }}" class="px-2.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                        ✕ Reset
                    </a>
                @endif
            </form>
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
                        <th class="px-3 py-3 text-right w-[140px]">ACTION</th>
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
                                <td class="px-3 py-3 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                    ₹{{ number_format($net, 2) }}
                                </td>

                                <!-- Column 6: Amount (₹) (Paid Amount) -->
                                <td class="px-3 py-3 text-left font-mono font-bold text-emerald-700 align-middle">
                                    <div>₹{{ number_format($paid, 2) }}</div>
                                    @if($paymentsCount > 0)
                                        <div class="text-[10px] text-[#7a681d] font-bold">{{ $paymentsCount }} Installment(s)</div>
                                    @endif
                                </td>

                                <!-- Column 7: Balance Due (₹) -->
                                <td class="px-3 py-3 text-left font-mono font-black align-middle {{ $isCleared ? 'text-slate-400' : 'text-rose-700' }}">
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            <span>PARTIALLY PAID</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            <span>PENDING RELEASE</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Column 9: Action -->
                                <td class="px-3 py-3 text-right whitespace-nowrap align-middle">
                                    @if(!$isCleared)
                                        <button type="button" @click="openDisburseModal({{ json_encode($expense) }})"
                                                class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                title="Disburse Staggered Payment Release">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Disburse Payment</span>
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
                                                                        <a href="/vouchers/{{ $pay->voucher_id }}/payment-voucher-print" target="_blank"
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

    <!-- ── MODAL: STAGGERED DISBURSEMENT RELEASE (MATCHING CONTRACTOR RELEASE MODAL) ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden transform transition-all" @click.away="disburseModalOpen = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Payment Disbursement</p>
                        <h2 class="text-lg font-extrabold text-white">Disburse Staggered Site Expense Payment</h2>
                    </div>
                    <button type="button" @click="disburseModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="selectedExpense ? '{{ url('site-expenses') }}/' + selectedExpense.id + '/disburse' : '#'" method="POST" target="_blank" @submit="disburseModalOpen = false; setTimeout(() => window.location.reload(), 1200)" class="px-6 pt-3.5 pb-6 space-y-3.5">
                @csrf

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
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" x-model="disbursePaidAmount" :max="selectedExpense ? selectedExpense.balance_amount : 0" required
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-mono font-black text-slate-900 focus:outline-none transition-all shadow-2xs"
                               oninput="window.updateAmountInWordsForInput && window.updateAmountInWordsForInput(this)">
                        <div class="mt-1 flex items-center justify-between text-[10px]">
                            <span class="font-bold text-slate-500" x-text="selectedExpense ? 'Max Payable: ₹' + numberFormat(selectedExpense.balance_amount) : ''"></span>
                            <span class="font-mono font-extrabold text-[#a38c29]" x-show="disbursePaidAmount > 0" x-text="'Amount: ₹' + numberFormat(disbursePaidAmount)"></span>
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

                    <div x-show="sourceType === 'bank'">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="company_bank_account_id" x-model="selectedBankId" :required="sourceType === 'bank'" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] focus:outline-none transition-all">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1 flex items-center justify-between text-[10px]">
                            <span class="text-slate-500 font-bold">Bank Balance:</span>
                            <span class="font-mono font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md" x-text="'₹' + numberFormat(getBankBalance())"></span>
                        </div>
                    </div>

                    <div x-show="sourceType === 'loan'" style="display: none;">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">SELECT PROJECT LOAN <span class="text-rose-500 font-bold">*</span></label>
                        <select name="loan_id" :required="sourceType === 'loan'" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] focus:outline-none transition-all">
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
                        <select name="payment_mode" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] focus:outline-none transition-all">
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
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">REFERENCE NO (CHEQUE # / UTR #) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="reference_no" placeholder="e.g. UTR123456789 or Chq #000123" required
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs">
                    </div>
                </div>

                <!-- ── LIVE BANK BALANCE ANALYSIS STRIP (WHEN SOURCE IS BANK) ── -->
                <div x-show="sourceType === 'bank'" class="p-3.5 bg-slate-50 border border-slate-200/90 rounded-2xl shadow-2xs space-y-2.5">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" :class="isBankSufficient() ? 'bg-emerald-500 shadow-xs' : 'bg-rose-500 animate-ping'"></span>
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-800">Bank Balance & Expense Outflow Analysis</span>
                        </div>
                        <div>
                            <span x-show="isBankSufficient()" class="px-2.5 py-1 rounded-full text-[9.5px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>✓ Sufficient Bank Balance</span>
                            </span>
                            <span x-show="!isBankSufficient()" class="px-2.5 py-1 rounded-full text-[9.5px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>⚠️ Insufficient Funds (Shortfall: ₹<span x-text="numberFormat(getShortfall())"></span>)</span>
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <!-- 1. Bank Account Balance & Post-Payment Balance -->
                        <div class="p-2.5 bg-white rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
                            <span class="block text-[9px] font-black text-slate-500 uppercase tracking-wider">Current Bank Balance</span>
                            <div class="font-mono font-black text-slate-900 text-sm mt-1" x-text="'₹' + numberFormat(getBankBalance())"></div>
                            <div class="text-[9.5px] font-bold text-slate-500 mt-1.5 pt-1.5 border-t border-slate-100 flex items-center justify-between">
                                <span>Post-Payment:</span>
                                <strong :class="getPostBankBalance() >= 0 ? 'text-emerald-700 font-mono font-black' : 'text-rose-600 font-mono font-black'" x-text="'₹' + numberFormat(getPostBankBalance())"></strong>
                            </div>
                        </div>

                        <!-- 2. This Site Expense Remaining Balance -->
                        <div class="p-2.5 bg-white rounded-xl border border-slate-200 shadow-2xs flex flex-col justify-between">
                            <span class="block text-[9px] font-black text-slate-500 uppercase tracking-wider">Remaining Voucher Balance</span>
                            <div class="font-mono font-black text-sm mt-1" :class="getExpenseRemaining() == 0 ? 'text-emerald-700' : 'text-rose-700'" x-text="'₹' + numberFormat(getExpenseRemaining())"></div>
                            <div class="text-[9.5px] font-bold text-slate-500 mt-1.5 pt-1.5 border-t border-slate-100 flex items-center justify-between">
                                <span>Current Due:</span>
                                <span class="font-mono font-bold text-slate-800" x-text="'₹' + numberFormat(selectedExpense ? selectedExpense.balance_amount : 0)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSEMENT REMARKS / AUDIT NOTES</label>
                    <textarea name="remarks" rows="2" placeholder="e.g. Disbursed against site material delivery inspection..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
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
        disbursePaidAmount: '',
        companyBankAccounts: @json($companyBankAccounts ?? []),

        openDisburseModal(expense) {
            this.selectedExpense = expense;
            this.sourceType = expense.payment_source_type || 'bank';
            this.disbursePaidAmount = expense.balance_amount || '';
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
