@extends('layouts.erp')

@section('title', 'Contractor Payment Release Desk')

@section('content')
<div x-data="raBillPaymentRelease()" class="space-y-6">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>CONTRACTOR OPERATIONS</span>
                <span>›</span>
                <span class="text-emerald-700 font-bold">CONTRACTOR PAYMENT RELEASE</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Contractor Treasury Payment Release Desk</span>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">Disbursements & Payment Vouchers</span>
            </h1>
        </div>
    </div>

    <!-- Executive Treasury KPI Metrics Bar (Upgraded with Icons & Hover Effects) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Verified Payable Claims -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">VERIFIED PAYABLE CLAIMS</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5 border-t border-blue-50">Total Net Approved Liability</div>
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
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5 border-t border-emerald-50">Corporate Bank Account Outflows</div>
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
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight group-hover:text-slate-800 transition-colors">{{ $raBills->whereNotNull('verified_date')->where('balance_amount', '>', 0)->count() }} Bills</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Verified & Unpaid RA Bills</div>
            </div>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (MATCHING CHEQUE RECEIPT ENTRY) ── -->
    @php
        $filterProjects = $raBills->pluck('project')->filter()->unique('id')->sortBy('name');
        $defaultProjectId = $filterProjects->first()?->id ?? '';
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">

                {{-- 1. Contractor Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <select x-model="filterContractorId"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Contractors</option>
                        @php
                            $filterContractors = collect($contractors);
                            foreach($raBills as $b) {
                                if ($b->contractor_id && !$filterContractors->contains('id', $b->contractor_id)) {
                                    $cName = $b->contractor->name ?? $b->contractor_name;
                                    if ($cName) {
                                        $filterContractors->push((object)['id' => $b->contractor_id, 'name' => $cName]);
                                    }
                                }
                            }
                            $filterContractors = $filterContractors->unique('id')->sortBy('name');
                        @endphp
                        @foreach($filterContractors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 2. Project Filter (1st Project Default Selected) --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <select x-model="filterProjectId"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($filterProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 3. Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="filterStatus"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending </option>
                        <option value="partially_paid">Partially Paid</option>
                        <option value="cleared">Cleared / Paid</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>
        </div>
    </div>

    <!-- Payment Disbursal Desk Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Contractor Payment Release Register
                </span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold"
                      x-text="getVisibleCount() + ' Records'">
                    {{ $raBills->count() }} Records
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3 py-3 text-left w-[130px]">RA BILL NO</th>
                        <th class="px-3 py-3 text-left w-[180px]">CONTRACTOR / PROJECT</th>
                        <th class="px-3 py-3 text-left w-[110px]">VERIFIED DATE</th>
                        <th class="px-3 py-3 text-left w-[110px]">NET APPROVED (₹)</th>
                        <th class="px-3 py-3 text-left text-emerald-100 w-[110px]">AMOUNT (₹)</th>
                        <th class="px-3 py-3 text-left text-rose-100 w-[110px]">BALANCE DUE (₹)</th>
                        <th class="px-3 py-3 text-left w-[95px]">STATUS</th>
                        <th class="px-3 py-3 text-right w-[120px]">ACTION</th>
                    </tr>
                </thead>
                    @forelse($raBills as $bill)
                        @php
                            $isCleared = ((float)$bill->balance_amount <= 0.001);
                            $isVerified = !empty($bill->verified_date);
                            $isPartiallyPaid = ($isVerified && !$isCleared && (float)$bill->paid_amount > 0);
                            $paymentCount = $bill->payments->count();
                            $statusVal = $isCleared ? 'cleared' : ($isPartiallyPaid ? 'partially_paid' : ($isVerified ? 'pending' : 'unverified'));
                        @endphp
                        <tbody x-data="{ showHistory: false }"
                               x-show="matchesFilter('{{ $bill->contractor_id }}', '{{ $bill->project_id }}', '{{ $statusVal }}')"
                               class="border-b border-slate-100 divide-y divide-slate-100 text-xs font-semibold">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-xs whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                                        @if($paymentCount > 0)
                                            <button type="button" @click="showHistory = !showHistory"
                                                    class="px-1.5 py-0.5 bg-[#a38c29]/15 hover:bg-[#a38c29]/30 text-[#7a681d] rounded font-black text-[10px] cursor-pointer inline-flex items-center gap-1 transition shadow-2xs border border-[#a38c29]/40"
                                                    title="Toggle Part-by-Part Payment History">
                                                <span x-text="showHistory ? '▲ Hide History' : '▼ ' + {{ $paymentCount }} + ' Part Paid'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-middle">
                                    <div class="font-black text-slate-900 text-xs leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                    <div class="text-[10px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                </td>

                                <td class="px-3 py-3 text-left font-mono align-middle">
                                    @if($bill->verified_date)
                                        <div class="text-xs text-emerald-700 font-bold">
                                            {{ $bill->verified_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 truncate max-w-[100px]">By: {{ $bill->engineer_name ?: 'Engineer' }}</div>
                                    @else
                                        <span class="text-amber-600 text-[10px] italic font-semibold">Verification Pending</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                    ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-bold text-emerald-700 align-middle">
                                    <div>₹{{ number_format((float) $bill->paid_amount, 2) }}</div>
                                    @if($paymentCount > 0)
                                        <div class="text-[10px] text-[#7a681d] font-bold">{{ $paymentCount }} Installment(s)</div>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-black align-middle {{ $isCleared ? 'text-slate-400' : 'text-rose-700' }}">
                                    ₹{{ number_format((float) $bill->balance_amount, 2) }}
                                </td>

                                <td class="px-3 py-3 text-left whitespace-nowrap align-middle">
                                    @if($isCleared)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <svg class="w-2.5 h-2.5 text-[#087443]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>CLEARED</span>
                                        </span>
                                    @elseif($isPartiallyPaid)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center shadow-2xs uppercase tracking-wider">
                                            PARTIALLY PAID
                                        </span>
                                    @elseif($isVerified)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center shadow-2xs uppercase tracking-wider">
                                            PENDING RELEASE
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">UNVERIFIED</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-right whitespace-nowrap align-middle">
                                    @if($isVerified && !$isCleared)
                                        <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                title="Disburse Staggered Payment Release">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Disburse Payment</span>
                                        </button>
                                    @elseif($isCleared)
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Fully Paid</span>
                                    @else
                                        <span class="text-[10px] text-amber-600 font-semibold italic">Requires Verification</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Part-by-Part Payment History Expandable Accordion -->
                            @if($paymentCount > 0)
                                <tr x-show="showHistory" x-cloak class="bg-amber-50/20 border-b border-[#a38c29]/30" x-transition.opacity>
                                    <td colspan="8" class="p-4">
                                        <div class="bg-white rounded-xl p-4 border border-[#a38c29]/30 shadow-sm space-y-3">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                                <span class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span>PART-BY-PART PAYMENT DISBURSEMENT HISTORY — RA BILL #{{ $bill->ra_bill_number }}</span>
                                                </span>
                                                <span class="text-[10.5px] font-bold text-slate-600">Total Outflow Disbursed: <strong class="text-emerald-700 font-mono font-black text-xs">₹{{ number_format((float)$bill->paid_amount, 2) }}</strong></span>
                                            </div>

                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left border-collapse text-[10.5px]">
                                                    <thead>
                                                        <tr class="bg-[#a38c29] text-white text-[9px] font-black uppercase tracking-wider border-b border-[#8a7522]">
                                                            <th class="px-3 py-2">INSTALLMENT #</th>
                                                            <th class="px-3 py-2">DISBURSEMENT DATE</th>
                                                            <th class="px-3 py-2">CORPORATE BANK ACCOUNT</th>
                                                            <th class="px-3 py-2">PAYMENT MODE & REF #</th>
                                                            <th class="px-3 py-2 text-right text-emerald-100">DISBURSED AMOUNT (₹)</th>
                                                            <th class="px-3 py-2 text-right">ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                                                        @foreach($bill->payments as $index => $pay)
                                                            <tr class="hover:bg-amber-50/30">
                                                                <td class="px-3 py-2 font-black text-slate-800">
                                                                    <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#a38c29] rounded font-mono text-[9.5px] font-bold">Part {{ $index + 1 }}</span>
                                                                </td>
                                                                <td class="px-3 py-2 font-mono text-slate-900 font-bold">
                                                                    {{ $pay->payment_date ? $pay->payment_date->format('d/m/Y') : '—' }}
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <div class="font-bold text-slate-900">{{ $pay->companyBankAccount->bank_name ?? 'Corporate Bank Account' }}</div>
                                                                    <div class="text-[9px] text-slate-500 font-mono">A/C: {{ $pay->companyBankAccount->account_number ?? '—' }}</div>
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <span class="px-1.5 py-0.2 rounded bg-blue-100 text-blue-900 text-[8.5px] font-black uppercase">{{ $pay->payment_mode }}</span>
                                                                    <span class="font-mono text-slate-700 font-bold ml-1">{{ $pay->reference_no ?: '—' }}</span>
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
                        <tbody class="border-b border-slate-100">
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                    No Contractor RA Progress Bills pending for payment release.
                                </td>
                            </tr>
                        </tbody>
                    @endforelse

                    @if($raBills->isNotEmpty())
                        <tbody x-show="getVisibleCount() === 0" x-cloak class="border-b border-slate-100">
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <div class="font-bold text-xs text-slate-600">No RA bills found matching the selected filters.</div>
                                        <button type="button" @click="resetFilters()" class="mt-1 text-xs text-[#a38c29] hover:underline font-bold inline-flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Reset Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endif
            </table>
        </div>
    </div>

    <!-- ── MODAL: STAGGERED DISBURSEMENT RELEASE (COMPACT & SLEEK TYPOGRAPHY) ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border-0 ring-0 outline-none flex flex-col" @click.away="disburseModalOpen = false">
            {{-- Dark Header (Zero White Border / Fringe) --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[11px] font-bold uppercase tracking-widest mb-1">
                            Payment Disbursement
                        </p>
                        <h2 class="text-xl font-extrabold text-white tracking-tight">Disburse Staggered Contractor Payment</h2>
                    </div>
                    <button type="button" @click="disburseModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="selectedBill ? '{{ url('expenses/ra-bills') }}/' + selectedBill.id + '/disburse' : '#'" method="POST" target="_blank" @submit="disburseModalOpen = false; setTimeout(() => window.location.reload(), 1200)" class="bg-white p-6 flex flex-col gap-4 rounded-b-2xl">
                @csrf

                <!-- Summary Card -->
                <div class="p-4 bg-slate-50 border border-slate-200/90 rounded-2xl grid grid-cols-3 gap-3 text-center shadow-2xs">
                    <div class="border-r border-slate-200/80 pr-2">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">RA BILL NO.</span>
                        <span class="text-sm font-mono font-black text-slate-900 mt-1 block" x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span>
                    </div>
                    <div class="border-r border-slate-200/80 pr-2">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">NET APPROVED</span>
                        <span class="text-sm font-mono font-black text-blue-900 mt-1 block" x-text="selectedBill ? '₹ ' + numberFormat(selectedBill.net_approved_amount) : ''"></span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-rose-700 uppercase tracking-wider">OUTSTANDING BAL.</span>
                        <span class="text-sm font-mono font-black text-rose-700 mt-1 block" x-text="selectedBill ? '₹ ' + numberFormat(selectedBill.balance_amount) : ''"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSEMENT DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition-all shadow-2xs">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                            <button type="button" 
                                    @click="disbursePaidAmount = selectedBill ? selectedBill.balance_amount : ''; $nextTick(() => { const el = $el.closest('form').querySelector('input[name=\'paid_amount\']'); if(el && window.updateAmountInWordsForInput) window.updateAmountInWordsForInput(el); })"
                                    class="text-[10px] font-bold text-[#a38c29] hover:underline cursor-pointer">
                                Pay Full Balance
                            </button>
                        </div>
                        <input type="number" step="0.01" min="0.01" name="paid_amount" x-model="disbursePaidAmount" :max="selectedBill ? selectedBill.balance_amount : null" placeholder="Enter amount to disburse..." required
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-sm font-mono font-black text-slate-900 focus:outline-none transition-all shadow-2xs"
                               oninput="window.updateAmountInWordsForInput && window.updateAmountInWordsForInput(this)">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="company_bank_account_id" x-model="selectedBankId" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition-all shadow-2xs">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1.5 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Available Balance:</span>
                            <span class="font-mono font-extrabold text-slate-900 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-lg text-xs" x-text="'₹ ' + numberFormat(getBankBalance())"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_mode" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition-all shadow-2xs">
                            @foreach(($paymentModes ?? []) as $pm)
                                @php
                                    $pmCode = is_object($pm) ? ($pm->code ?? $pm->name) : $pm;
                                    $pmName = is_object($pm) ? ($pm->name ?? $pm->code) : $pm;
                                @endphp
                                <option value="{{ $pmCode }}">{{ $pmName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- ── LIVE BANK BALANCE & BILL SETTLEMENT INTELLIGENCE STRIP ── -->
                <div class="p-4 bg-slate-50 border border-slate-200/90 rounded-2xl shadow-2xs space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" :class="isBankSufficient() ? 'bg-emerald-500 shadow-xs' : 'bg-rose-500 animate-ping'"></span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">Bank Balance &amp; Bill Settlement Analysis</span>
                        </div>
                        <div>
                            <span x-show="isBankSufficient()" class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>✓ Sufficient Bank Balance</span>
                            </span>
                            <span x-show="!isBankSufficient()" class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>⚠️ Insufficient Funds (Shortfall: ₹ <span x-text="numberFormat(getShortfall())"></span>)</span>
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 1. Bank Account Balance -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs flex flex-col justify-between transition-all">
                            <span class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2.5">BANK ACCOUNT BALANCE</span>
                            
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Current:</span>
                                    <span class="font-mono font-black text-slate-900 text-lg md:text-xl" x-text="'₹ ' + numberFormat(getBankBalance())"></span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="text-xs font-bold text-slate-500 whitespace-nowrap">Post-Payment:</span>
                                    <span class="font-mono font-black text-lg md:text-xl" :class="getPostBankBalance() >= 0 ? 'text-emerald-700' : 'text-rose-600'" x-text="'₹ ' + numberFormat(getPostBankBalance())"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. RA Bill Balance -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs flex flex-col justify-between transition-all">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="block text-xs font-black text-slate-700 uppercase tracking-wider">RA BILL OUTSTANDING</span>
                                <span x-show="parseFloat(disbursePaidAmount) > 0 && getBillRemaining() == 0" class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Fully Settled
                                </span>
                                <span x-show="parseFloat(disbursePaidAmount) > 0 && getBillRemaining() > 0" class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Part Due
                                </span>
                                <span x-show="!parseFloat(disbursePaidAmount)" class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                    Pending Entry
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Current Due:</span>
                                    <span class="font-mono font-black text-slate-900 text-lg md:text-xl" x-text="'₹ ' + numberFormat(selectedBill ? selectedBill.balance_amount : 0)"></span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="text-xs font-bold text-slate-500 whitespace-nowrap">Post-Payment:</span>
                                    <span class="font-mono font-black text-lg md:text-xl" :class="getBillRemaining() == 0 ? 'text-emerald-700' : 'text-amber-700'" x-text="'₹ ' + numberFormat(getBillRemaining())"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">REFERENCE NO (CHEQUE # / UTR #) <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="reference_no" placeholder="e.g. UTR123456789 or Chq #000123" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs">
                </div>

                <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="disburseModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/25 border border-[#a38c29]/40 cursor-pointer active:scale-98">
                        RELEASE PAYMENT &amp; PRINT VOUCHER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POPUP ERROR ALERT MODAL (CENTERE OVERLAY POPUP ON ERROR) ── -->
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
                CLOSE ALERT & SELECT VALID BANK
            </button>
        </div>
    </div>

</div>

<script>
function raBillPaymentRelease() {
    const defaultProjectId = '{{ $defaultProjectId }}';
    return {
        filterContractorId: '',
        filterProjectId: defaultProjectId,
        filterStatus: '',
        allBills: [
            @foreach($raBills as $bill)
            @php
                $isCleared = ((float)$bill->balance_amount <= 0.001);
                $isVerified = !empty($bill->verified_date);
                $isPartiallyPaid = ($isVerified && !$isCleared && (float)$bill->paid_amount > 0);
                $bStatus = $isCleared ? 'cleared' : ($isPartiallyPaid ? 'partially_paid' : ($isVerified ? 'pending' : 'unverified'));
            @endphp
            {
                contractor_id: '{{ $bill->contractor_id }}',
                project_id: '{{ $bill->project_id }}',
                status: '{{ $bStatus }}',
            },
            @endforeach
        ],

        resetFilters() {
            this.filterContractorId = '';
            this.filterProjectId = defaultProjectId;
            this.filterStatus = '';
        },

        matchesFilter(contractorId, projectId, status) {
            if (this.filterContractorId && String(contractorId) !== String(this.filterContractorId)) {
                return false;
            }
            if (this.filterProjectId && String(projectId) !== String(this.filterProjectId)) {
                return false;
            }
            if (this.filterStatus && status !== this.filterStatus) {
                return false;
            }
            return true;
        },

        getVisibleCount() {
            return this.allBills.filter(b => {
                if (this.filterContractorId && String(b.contractor_id) !== String(this.filterContractorId)) {
                    return false;
                }
                if (this.filterProjectId && String(b.project_id) !== String(this.filterProjectId)) {
                    return false;
                }
                if (this.filterStatus && b.status !== this.filterStatus) {
                    return false;
                }
                return true;
            }).length;
        },

        disburseModalOpen: false,
        openErrorModal: {{ ($errors->any() || session('error')) ? 'true' : 'false' }},
        selectedBill: null,
        selectedBankId: '{{ $companyBankAccounts->first()?->id ?? "" }}',
        disbursePaidAmount: '',
        companyBankAccounts: @json($companyBankAccounts ?? []),
        contractorLedgerSummaries: @json($contractorLedgerSummaries ?? []),

        openDisburseModal(bill) {
            this.selectedBill = bill;
            this.disbursePaidAmount = '';
            if (!this.selectedBankId && this.companyBankAccounts.length > 0) {
                this.selectedBankId = this.companyBankAccounts[0].id;
            }
            this.disburseModalOpen = true;

            this.$nextTick(() => {
                const inputEl = document.querySelector('input[name="paid_amount"]');
                if (inputEl) {
                    inputEl.focus();
                    if (window.updateAmountInWordsForInput) {
                        window.updateAmountInWordsForInput(inputEl);
                    }
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
            return this.getPostBankBalance() >= 0;
        },

        getShortfall() {
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            const current = this.getBankBalance();
            return Math.max(0, paid - current);
        },

        getBillRemaining() {
            const billBal = parseFloat(this.selectedBill?.balance_amount) || 0;
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return Math.max(0, billBal - paid);
        },

        getContractorTotalDues() {
            if (!this.selectedBill) return 0;
            const cId = this.selectedBill.contractor_id;
            if (cId) {
                const summary = this.contractorLedgerSummaries.find(x => x.id == cId);
                if (summary) {
                    return parseFloat(summary.total_balance) || 0;
                }
            }
            return parseFloat(this.selectedBill.balance_amount) || 0;
        },

        getContractorPostDues() {
            const currentTotal = this.getContractorTotalDues();
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
