@extends('layouts.erp')

@section('title', 'Contractor RA Progress Bills Directory & Ledger')

@section('content')
<div x-data="raBillManagement()" class="p-6 space-y-6 bg-slate-50 min-h-screen">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>EXPENSES & VENDORS</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">CONTRACTOR RA PROGRESS BILLS & LEDGER</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Contractor Running Account (RA) Bills & Ledger</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Progress Billing & Statement</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="addContractorModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>+ Register Contractor</span>
            </button>

            <button type="button" @click="addModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Contractor RA Bill</span>
            </button>
        </div>
    </div>

    <!-- ── SUCCESS & ERROR ALERTS ── -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
        </div>
    @endif

    <!-- ── TOP SECTION TAB NAVIGATION SWITCHER ── -->
    <div class="flex items-center gap-3 border-b border-slate-200/80 pb-1">
        <button type="button" @click="activeTab = 'bills'"
                :class="activeTab === 'bills' ? 'bg-[#a38c29] text-white shadow-md border-[#8a7522]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                class="px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2.5 cursor-pointer border">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>SECTION 1: RA BILLS & PAYMENT RELEASE</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="activeTab === 'bills' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">{{ $raBills->count() }} Records</span>
        </button>

        <button type="button" @click="activeTab = 'ledger'"
                :class="activeTab === 'ledger' ? 'bg-[#a38c29] text-white shadow-md border-[#8a7522]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                class="px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2.5 cursor-pointer border">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>SECTION 2: CONTRACTOR MASTER & LEDGER VIEW</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="activeTab === 'ledger' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">{{ count($contractorLedgerSummaries ?? []) }} Contractors</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- ── SECTION 1: CONTRACTOR RA PROGRESS BILLS & DISBURSEMENTS TABLE ── -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'bills'" x-cloak class="space-y-6">

        <!-- Executive KPI Metrics Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Metric 1: Total Gross Claimed -->
            <div class="group bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-slate-800 shadow-xs hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">TOTAL RA CLAIMED</span>
                    <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 group-hover:scale-110 group-hover:bg-slate-200 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                </div>
                <div class="text-xl font-mono font-black text-slate-900 mt-2">₹{{ number_format((float) $totalGross, 2) }}</div>
                <div class="text-[10px] text-slate-400 font-semibold mt-1">{{ $raBills->count() }} Total RA Bills</div>
            </div>

            <!-- Metric 2: Total Corrections -->
            <div class="group bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-amber-500 shadow-xs hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">TOTAL CORRECTIONS</span>
                    <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 group-hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </span>
                </div>
                <div class="text-xl font-mono font-black text-amber-700 mt-2">-₹{{ number_format((float) $totalCorrections, 2) }}</div>
                <div class="text-[10px] text-amber-600 font-semibold mt-1">Site Engineer Adjustments</div>
            </div>

            <!-- Metric 3: Total Net Approved Payable -->
            <div class="group bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-500 shadow-xs hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">NET APPROVED PAYABLE</span>
                    <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 group-hover:bg-blue-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="text-xl font-mono font-black text-blue-900 mt-2">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-semibold mt-1">Approved for Disbursement</div>
            </div>

            <!-- Metric 4: Total Paid -->
            <div class="group bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">TOTAL DISBURSED (PAID)</span>
                    <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 group-hover:bg-emerald-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                </div>
                <div class="text-xl font-mono font-black text-emerald-800 mt-2">₹{{ number_format((float) $totalPaid, 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-semibold mt-1">Corporate Treasury Outflow</div>
            </div>

            <!-- Metric 5: Balance Amount -->
            <div class="group bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-rose-500 shadow-xs hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider">OUTSTANDING BALANCE</span>
                    <span class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 group-hover:bg-rose-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="text-xl font-mono font-black text-rose-800 mt-2">₹{{ number_format((float) $totalBalance, 2) }}</div>
                <div class="text-[10px] text-rose-600 font-semibold mt-1">Pending Future Release</div>
            </div>
        </div>

        <!-- Excel-Matched RA Progress Bills Register Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Table Control Toolbar -->
            <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Contractor RA Bills Register</span>
                    <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ $raBills->count() }} Records</span>
                </div>

                <div class="flex items-center gap-3">
                    <input type="text" x-model="searchQuery" placeholder="Search RA Bill #, Contractor..."
                           class="px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-72 shadow-2xs">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                        <tr class="text-left">
                            <th class="px-2 py-2.5 text-left w-[75px]">RA BILL NO</th>
                            <th class="px-2 py-2.5 text-left w-[145px]">CONTRACTOR / PROJECT / UNIT</th>
                            <th class="px-2 py-2.5 text-left w-[100px]">SUBMIT / VERIFIED</th>
                            <th class="px-2 py-2.5 text-left w-[90px]">RA BILL AMOUNT</th>
                            <th class="px-2 py-2.5 text-left w-[80px]">CORRECTION</th>
                            <th class="px-2 py-2.5 text-left bg-[#8a7522]/40 w-[90px]">AFTER CORRECTION</th>
                            <th class="px-2 py-2.5 text-left w-[100px]">DUE / PAID DATE</th>
                            <th class="px-2 py-2.5 text-left text-emerald-200 w-[85px]">PAID AMOUNT</th>
                            <th class="px-2 py-2.5 text-left text-rose-200 w-[85px]">BALANCE AMOUNT</th>
                            <th class="px-2 py-2.5 text-left w-[75px]">STATUS</th>
                            <th class="px-2 py-2.5 text-left w-[105px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                        @forelse($raBills as $bill)
                            @php
                                $payments = $bill->payments;
                                $netApproved = (float) $bill->net_approved_amount;
                                $runningBal = $netApproved;
                                $totalInstallments = $payments->count();
                                $rowspan = max(1, $totalInstallments);
                            @endphp

                            @if($totalInstallments === 0)
                                <!-- Bill with 0 payment disbursements -->
                                <tr class="hover:bg-amber-50/20 transition-colors border-b-2 border-slate-200">
                                    <td class="px-2 py-2 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                        <span class="inline-block px-1.5 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-[10px] whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                                    </td>

                                    <td class="px-2 py-2 align-middle">
                                        <div class="font-black text-slate-900 text-[11px] leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                        <div class="text-[9.5px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                        @if($bill->unit_name || $bill->unit)
                                            <div class="mt-0.5">
                                                <span class="inline-flex items-center gap-1 px-1 py-0.2 bg-amber-100/90 text-amber-950 border border-amber-300/70 rounded text-[8.5px] font-black uppercase tracking-wider whitespace-nowrap shadow-2xs">
                                                    <span>Unit: {{ $bill->unit_name ?: ($bill->unit->door_no ?? '') }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono align-middle">
                                        <div class="text-slate-700 font-bold text-[10px]">
                                            {{ $bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—' }}
                                        </div>
                                        @if($bill->verified_date)
                                            <div class="text-[9.5px] text-emerald-700 font-bold mt-0.5 whitespace-nowrap" title="Verified By: {{ $bill->engineer_name }}">
                                                Ver: {{ $bill->verified_date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-[8.5px] text-slate-500 font-semibold truncate max-w-[95px]">
                                                By: {{ $bill->engineer_name ?: 'Engineer' }}
                                            </div>
                                        @else
                                            <div class="text-[9.5px] text-amber-600 italic font-medium mt-0.5">Unverified</div>
                                        @endif
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono font-bold text-slate-900 align-middle">
                                        ₹{{ number_format((float) $bill->gross_amount, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono text-amber-700 font-bold align-middle">
                                        {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                        ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono align-middle">
                                        <div class="text-slate-700 font-bold text-[10px]">
                                            Due: {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                                        </div>
                                        <div class="text-[9.5px] text-slate-400 font-semibold mt-0.5">Paid: —</div>
                                    </td>

                                    <td class="px-2 py-2 text-left font-mono font-bold text-slate-400 align-middle">₹0.00</td>
                                    <td class="px-2 py-2 text-left font-mono font-black text-rose-700 align-middle">
                                        ₹{{ number_format((float) $bill->balance_amount, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-left whitespace-nowrap align-middle">
                                        @if($bill->verified_date)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                <span>PENDING</span>
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                                <span>SUBMITTED</span>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-2 py-2 text-left whitespace-nowrap align-middle">
                                        <div class="inline-flex items-center justify-start gap-1">
                                            @if($bill->verified_date)
                                                <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                        class="px-2 py-0.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer border border-[#a38c29]/40"
                                                        title="Verified By: {{ $bill->engineer_name }}. Click to view or update sign-off.">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Verified</span>
                                                </button>
                                            @else
                                                <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                        class="px-2 py-0.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                        title="Engineer Sign-off & Apply Correction">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>Verify</span>
                                                </button>
                                            @endif

                                            @if($bill->verified_date && (float)$bill->balance_amount > 0)
                                                <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                        class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                        title="Disburse Staggered Payment">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                    <span>Pay</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <!-- Multi-Installment Disbursements Row -->
                                @foreach($payments as $pIdx => $payment)
                                    @php
                                        $paidInst = (float) $payment->paid_amount;
                                        $runningBal = max(0.00, $runningBal - $paidInst);
                                        $isCleared = ($runningBal <= 0.001);
                                        $isLastPayment = ($pIdx === $totalInstallments - 1);
                                    @endphp
                                    <tr class="hover:bg-amber-50/20 transition-colors {{ $isLastPayment ? 'border-b-2 border-slate-200' : 'border-b border-slate-100' }} {{ $pIdx > 0 ? 'bg-slate-50/30' : '' }}">
                                        @if($pIdx === 0)
                                            <td class="px-2 py-2 text-left align-middle border-r border-slate-200/50 bg-slate-50/50" rowspan="{{ $rowspan }}">
                                                <span class="inline-block px-1.5 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-[10px] whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                                            </td>

                                            <td class="px-2 py-2 align-middle" rowspan="{{ $rowspan }}">
                                                <div class="font-black text-slate-900 text-[11px] leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                                <div class="text-[9.5px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                                @if($bill->unit_name || $bill->unit)
                                                    <div class="mt-0.5">
                                                        <span class="inline-flex items-center gap-1 px-1 py-0.2 bg-amber-100/90 text-amber-950 border border-amber-300/70 rounded text-[8.5px] font-black uppercase tracking-wider whitespace-nowrap shadow-2xs">
                                                            <span>Unit: {{ $bill->unit_name ?: ($bill->unit->door_no ?? '') }}</span>
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-2 py-2 text-left font-mono align-middle" rowspan="{{ $rowspan }}">
                                                <div class="text-slate-700 font-bold text-[10px]">
                                                    {{ $bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—' }}
                                                </div>
                                                @if($bill->verified_date)
                                                    <div class="text-[9.5px] text-emerald-700 font-bold mt-0.5 whitespace-nowrap" title="Verified By: {{ $bill->engineer_name }}">
                                                        Ver: {{ $bill->verified_date->format('d/m/Y') }}
                                                    </div>
                                                    <div class="text-[8.5px] text-slate-500 font-semibold truncate max-w-[95px]">
                                                        By: {{ $bill->engineer_name ?: 'Engineer' }}
                                                    </div>
                                                @else
                                                    <div class="text-[9.5px] text-amber-600 italic font-medium mt-0.5">Unverified</div>
                                                @endif
                                            </td>

                                            <td class="px-2 py-2 text-left font-mono font-bold text-slate-900 align-middle" rowspan="{{ $rowspan }}">
                                                ₹{{ number_format((float) $bill->gross_amount, 2) }}
                                            </td>

                                            <td class="px-2 py-2 text-left font-mono text-amber-700 font-bold align-middle" rowspan="{{ $rowspan }}">
                                                {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                                            </td>

                                            <td class="px-2 py-2 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle" rowspan="{{ $rowspan }}">
                                                ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                                            </td>
                                        @endif

                                        <td class="px-2 py-2 text-left font-mono align-middle">
                                            <div class="text-slate-700 font-bold text-[10px]">
                                                Due: {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                                            </div>
                                            <div class="text-[9.5px] text-emerald-700 font-bold mt-0.5 whitespace-nowrap">
                                                Paid: {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '—' }}
                                            </div>
                                        </td>

                                        <td class="px-2 py-2 text-left font-mono font-bold text-emerald-700 align-middle">
                                            ₹{{ number_format($paidInst, 2) }}
                                        </td>

                                        <td class="px-2 py-2 text-left font-mono font-black align-middle {{ $isCleared ? 'text-slate-500' : 'text-rose-700' }}">
                                            ₹{{ number_format($runningBal, 2) }}
                                        </td>

                                        <td class="px-2 py-2 text-left whitespace-nowrap align-middle">
                                            @if($isCleared)
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider whitespace-nowrap">
                                                    <svg class="w-2.5 h-2.5 text-[#087443] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>CLEARED</span>
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 shrink-0"></span>
                                                    <span>PENDING</span>
                                                </span>
                                            @endif
                                        </td>

                                        @if($pIdx === 0)
                                            <td class="px-2 py-2 text-left whitespace-nowrap align-middle" rowspan="{{ $rowspan }}">
                                                <div class="inline-flex items-center justify-start gap-1">
                                                    @if($bill->verified_date)
                                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                                class="px-2 py-0.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer border border-[#a38c29]/40"
                                                                title="Verified By: {{ $bill->engineer_name }}. Click to view or update sign-off.">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                            <span>Verified</span>
                                                        </button>
                                                    @else
                                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                                class="px-2 py-0.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                                title="Engineer Sign-off & Apply Correction">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            <span>Verify</span>
                                                        </button>
                                                    @endif

                                                    @if($bill->verified_date && (float)$bill->balance_amount > 0)
                                                        <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                                class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                                title="Disburse Staggered Payment">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                            <span>Pay</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                    No Contractor RA Progress Bills recorded yet. Click "+ Contractor RA Bill" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ── SECTION 2: CONTRACTOR MASTER & LEDGER VIEW ── -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'ledger'" x-cloak class="space-y-6">

        <!-- Toolbar & Filter Header -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Searchable Contractor Select Dropdown -->
                <div class="relative w-full" x-data="{ open: false, search: '' }" @click.outside="open = false">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Contractor</label>
                    
                    <button type="button" @click="open = !open" 
                            class="px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-full shadow-2xs flex items-center justify-between gap-2 hover:border-[#a38c29] transition">
                        <span class="truncate" x-text="getSelectedContractorName()"></span>
                        <div class="flex items-center gap-1 shrink-0">
                            <template x-if="selectedLedgerContractorId">
                                <span @click.stop="selectedLedgerContractorId = ''; search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-200 transition" title="Clear selection">✕</span>
                            </template>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Searchable Dropdown Menu -->
                    <div x-show="open" x-transition.opacity.duration.150ms 
                         class="absolute top-full left-0 mt-1 w-full bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                         style="display: none;">
                        
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="search" placeholder="Type contractor name to filter..." 
                                   class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:bg-white transition"
                                   @keydown.escape="open = false" autofocus>
                            <template x-if="search">
                                <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                            </template>
                        </div>

                        <div class="max-h-56 overflow-y-auto space-y-0.5 text-xs font-semibold">
                            <button type="button" @click="selectedLedgerContractorId = ''; open = false; search = '';" 
                                    class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                    :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': !selectedLedgerContractorId }">
                                <span>All Contractors</span>
                                <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + (allContractors ? allContractors.length : 0) + ')'"></span>
                            </button>
                            
                            <template x-for="cont in getFilteredContractorsList(search)" :key="cont.id">
                                <button type="button" @click="selectedLedgerContractorId = cont.id; open = false; search = '';" 
                                        class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                        :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': selectedLedgerContractorId == cont.id }">
                                    <span class="truncate" x-text="cont.name"></span>
                                    <span class="text-[9px] text-slate-400 font-mono" x-text="cont.gstin || cont.type || ''"></span>
                                </button>
                            </template>
                            
                            <div x-show="getFilteredContractorsList(search).length === 0" class="px-3 py-3 text-center text-slate-400 text-xs italic">
                                No contractors found.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Search Particulars / Ref # -->
                <div class="w-full">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Search Particulars / Ref #</label>
                    <div class="relative w-full">
                        <input type="text" x-model="ledgerSearchQuery" placeholder="Search bill #, voucher #, project..."
                               class="w-full px-3.5 py-2.5 pr-7 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                        <template x-if="ledgerSearchQuery">
                            <button type="button" @click="ledgerSearchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="addContractorModalOpen = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Register Contractor Master</span>
                </button>
            </div>
        </div>

        <!-- Filtered Contractor Ledger KPI Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-600 shadow-xs">
                <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">TOTAL NET CLAIMS ACCRUED</span>
                <div class="text-xl font-mono font-black text-blue-900 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></div>
                <div class="text-[10px] text-slate-400 font-semibold mt-1">Verified RA Bill Liability</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-600 shadow-xs">
                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">TOTAL DISBURSEMENTS RELEASED</span>
                <div class="text-xl font-mono font-black text-emerald-800 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().paid)"></div>
                <div class="text-[10px] text-slate-400 font-semibold mt-1">Paid Outflow via Treasury</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-rose-600 shadow-xs">
                <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider block">OUTSTANDING LEDGER BALANCE</span>
                <div class="text-xl font-mono font-black text-rose-800 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></div>
                <div class="text-[10px] text-slate-400 font-semibold mt-1">Payable Remaining</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-slate-800 shadow-xs">
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">REGISTERED CONTRACTORS</span>
                <div class="text-xl font-mono font-black text-slate-900 mt-1">{{ count($contractors) }} Payees</div>
                <div class="text-[10px] text-slate-400 font-semibold mt-1">Master Accounts Linked</div>
            </div>
        </div>

        <!-- ── SUB-SECTION 2A: CONTRACTOR RUNNING ACCOUNT LEDGER STATEMENT TABLE ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Contractor Account Statement Ledger</span>
                    <span class="text-[11px] bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full font-bold" x-text="filteredLedgerEntries().length + ' Transactions'"></span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white border-b border-slate-800 text-[9.5px] font-black uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-left w-[95px]">DATE</th>
                            <th class="px-3 py-3 text-left w-[150px]">CONTRACTOR</th>
                            <th class="px-3 py-3 text-left w-[130px]">PROJECT / UNIT</th>
                            <th class="px-3 py-3 text-left">EVENT PARTICULARS</th>
                            <th class="px-3 py-3 text-left w-[110px]">REF / VOUCHER #</th>
                            <th class="px-3 py-3 text-right w-[95px]">GROSS (₹)</th>
                            <th class="px-3 py-3 text-right w-[90px]">CORR. (₹)</th>
                            <th class="px-3 py-3 text-right text-blue-300 w-[110px]">NET ACCRUED (₹)</th>
                            <th class="px-3 py-3 text-right text-emerald-300 w-[110px]">RELEASED (₹)</th>
                            <th class="px-3 py-3 text-right text-rose-300 w-[110px]">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                        <template x-for="(entry, index) in filteredLedgerEntries()" :key="index">
                            <tr class="hover:bg-slate-50 transition" :class="entry.type === 'CLAIM' ? 'bg-white' : 'bg-emerald-50/20'">
                                <td class="px-3 py-2.5 font-mono text-slate-700" x-text="entry.date_formatted"></td>
                                <td class="px-3 py-2.5 font-bold text-slate-900" x-text="entry.contractor_name"></td>
                                <td class="px-3 py-2.5">
                                    <div class="text-slate-900 font-bold text-[10.5px]" x-text="entry.project_name"></div>
                                    <div class="text-[9px] text-slate-500" x-show="entry.unit_name" x-text="'Unit: ' + entry.unit_name"></div>
                                </td>
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <span x-show="entry.type === 'CLAIM'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-blue-100 text-blue-900 uppercase">VERIFIED CLAIM</span>
                                        <span x-show="entry.type === 'DISBURSEMENT'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-emerald-100 text-emerald-900 uppercase">PAYMENT RELEASE</span>
                                        <span class="text-slate-800 text-[11px]" x-text="entry.particulars"></span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 font-mono font-bold text-slate-700" x-text="entry.ref_no"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-slate-800" x-text="entry.gross_amount > 0 ? '₹' + numberFormat(entry.gross_amount) : '—'"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-amber-700" x-text="entry.correction_amount > 0 ? '-₹' + numberFormat(entry.correction_amount) : '—'"></td>
                                <td class="px-3 py-2.5 text-right font-mono font-black text-blue-900 bg-blue-50/30" x-text="entry.net_approved > 0 ? '₹' + numberFormat(entry.net_approved) : '—'"></td>
                                <td class="px-3 py-2.5 text-right font-mono font-black text-emerald-800 bg-emerald-50/30" x-text="entry.paid_amount > 0 ? '₹' + numberFormat(entry.paid_amount) : '—'"></td>
                                <td class="px-3 py-2.5 text-right">
                                    <template x-if="entry.voucher_id">
                                        <a :href="'/vouchers/' + entry.voucher_id + '/payment-voucher-print'" target="_blank"
                                           class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9.5px] font-bold inline-flex items-center gap-1">
                                            <span>Voucher</span>
                                        </a>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredLedgerEntries().length === 0">
                            <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">
                                No ledger transactions found matching the filter criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── SUB-SECTION 2B: CONTRACTOR MASTER DIRECTORY TABLE ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Contractor Master Directory</span>
                    <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ count($contractorLedgerSummaries ?? []) }} Registered</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#2a2415] text-white text-[9.5px] font-black uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">CONTRACTOR NAME / ACCOUNT CODE</th>
                            <th class="px-4 py-3">TAX & LEGAL IDS</th>
                            <th class="px-4 py-3">CONTACT INFO</th>
                            <th class="px-4 py-3 text-right">TOTAL CLAIMED (₹)</th>
                            <th class="px-4 py-3 text-right">TOTAL DISBURSED (₹)</th>
                            <th class="px-4 py-3 text-right">BALANCE PAYABLE (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                        @forelse(($contractorLedgerSummaries ?? []) as $cSummary)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-black text-slate-900">{{ $cSummary['name'] }}</div>
                                    <div class="font-mono text-[10px] text-blue-600 font-bold mt-0.5">{{ $cSummary['account_code'] }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-700">
                                    <div>GSTIN: <span class="font-mono font-bold">{{ $cSummary['gstin'] ?: 'N/A' }}</span></div>
                                    <div class="text-[10px] text-slate-400">PAN: <span class="font-mono font-bold">{{ $cSummary['pan'] ?: 'N/A' }}</span></div>
                                </td>
                                <td class="px-4 py-3 text-slate-700">
                                    <div>{{ $cSummary['phone'] ?: 'No Phone' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $cSummary['email'] ?: 'No Email' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">
                                    ₹{{ number_format($cSummary['total_net_approved'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700">
                                    ₹{{ number_format($cSummary['total_paid'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-black text-rose-700">
                                    ₹{{ number_format($cSummary['total_balance'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-slate-400 italic">
                                    No contractor master accounts registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ── MODAL 1: LOG NEW CONTRACTOR RA BILL ── -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="addModalOpen = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONTRACTOR RA BILLS</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">LOG NEW CONTRACTOR RA PROGRESS BILL</h3>
                </div>
                <button type="button" @click="addModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('expenses.ra-bills.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('ra_bill_number') ? 'text-rose-600' : '' }}">RA BILL NO <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="ra_bill_number" value="{{ old('ra_bill_number') }}" placeholder="e.g. 1 or RA-001" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('ra_bill_number') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('ra_bill_number')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('submit_date') ? 'text-rose-600' : '' }}">CONTRACTOR SUBMIT DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="submit_date" value="{{ old('submit_date', date('Y-m-d')) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('submit_date') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('submit_date')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('contractor_id') ? 'text-rose-600' : '' }}">CONTRACTOR NAME <span class="text-rose-500 font-bold">*</span></label>
                        <select name="contractor_id" x-model="selectedContractorId" required
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('contractor_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Contractor</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ (old('contractor_id') == $contractor->id || (empty(old('contractor_id')) && count($contractors) === 1)) ? 'selected' : '' }}>
                                    {{ $contractor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contractor_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('project_id') ? 'text-rose-600' : '' }}">SITE PROJECT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="project_id" x-model="selectedProjectId" @change="filterUnits()" required
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('project_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Project</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (old('project_id') == $proj->id || (empty(old('project_id')) && count($projects) === 1)) ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('unit_id') ? 'text-rose-600' : '' }}">UNIT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="unit_id" x-model="selectedUnitId" required
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('unit_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Unit</option>
                            <template x-for="u in availableUnits" :key="u.id">
                                <option :value="u.id" x-text="u.door_no" :selected="selectedUnitId == u.id"></option>
                            </template>
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('gross_amount') ? 'text-rose-600' : '' }}">RA BILL GROSS AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount') }}" placeholder="5000000" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-sm font-mono font-bold focus:outline-none transition-all {{ $errors->has('gross_amount') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('gross_amount')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">RA BILL DUE DATE</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">REMARKS / NOTES</label>
                    <textarea name="remarks" rows="2" placeholder="Notes regarding progress work done..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">{{ old('remarks') }}</textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md border border-[#a38c29]/40 cursor-pointer">SAVE RA BILL</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 2: SITE ENGINEER VERIFICATION & CORRECTIONS ── -->
    <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="verifyModalOpen = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">ENGINEER VERIFICATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">SITE ENGINEER VERIFICATION & CORRECTION SIGN-OFF</h3>
                </div>
                <button type="button" @click="verifyModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/verify` : '#'" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- KPI Summary Card -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl grid grid-cols-3 gap-4 items-center text-xs">
                    <div>
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">RA BILL NO.</span>
                        <span class="text-xs font-mono font-black text-slate-900" x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">GROSS CLAIMED</span>
                        <span class="text-xs font-mono font-black text-slate-900" x-text="selectedBill ? '₹' + numberFormat(selectedBill.gross_amount) : ''"></span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">STATUS</span>
                        <span x-show="selectedBill && selectedBill.status === 'cleared'" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Cleared</span>
                        </span>
                        <span x-show="selectedBill && selectedBill.status !== 'cleared' && selectedBill.verified_date" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Verified</span>
                        </span>
                        <span x-show="selectedBill && !selectedBill.verified_date" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <span>Submitted</span>
                        </span>
                    </div>
                </div>

                <!-- Verification Already Done Banner -->
                <template x-if="selectedBill && selectedBill.verified_date">
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-emerald-800 font-extrabold">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>VERIFICATION ALREADY DONE</span>
                        </div>
                        <div class="text-slate-700 font-semibold">
                            Verified By: <span class="font-bold text-slate-900" x-text="selectedBill.engineer_name || 'Engineer'"></span>
                        </div>
                    </div>
                </template>

                <!-- Form Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">VERIFIED DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="verified_date" x-model="verifyDateInput" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SITE ENGINEER (FROM MASTER) <span class="text-rose-500 font-bold">*</span></label>
                        <select name="engineer_id" x-model="selectedEngineerId" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                            <option value="">Select Verifying Engineer</option>
                            @foreach($engineers as $eng)
                                <option value="{{ $eng->id }}" :selected="selectedEngineerId == {{ $eng->id }}">
                                    {{ $eng->name }} {{ $eng->designation ? '('.$eng->designation.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">CORRECTION OF BILL (DEDUCTION ₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="correction_amount" x-model="correctionInput" @input="recalcNet()" required
                               class="w-full px-3.5 py-2.5 bg-amber-50/60 border border-amber-200 rounded-xl text-sm font-mono font-black text-amber-950 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                        <p class="mt-1 text-[10px] font-bold text-slate-500" x-text="selectedBill ? 'Max Correction: ₹' + numberFormat(selectedBill.gross_amount) : ''"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-1.5">NET RA PAYABLE AFTER CORRECTION</label>
                        <div class="w-full px-3.5 py-2.5 bg-blue-50/70 border border-blue-200 rounded-xl text-sm font-mono font-black text-blue-950 flex items-center min-h-[42px]"
                             x-text="'₹ ' + numberFormat(calculatedNet)"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">VERIFICATION REMARKS</label>
                    <textarea name="remarks" rows="2" x-model="verifyRemarksInput" placeholder="Details of corrections/retentions applied..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="verifyModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md border border-[#a38c29]/40 cursor-pointer">
                        <span x-text="selectedBill && selectedBill.verified_date ? 'UPDATE VERIFICATION SIGN-OFF' : 'CONFIRM SIGN-OFF'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 3: STAGGERED DISBURSEMENT RELEASE ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="disburseModalOpen = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 text-[9px] font-black uppercase tracking-wider rounded border border-emerald-500/40 mb-1">PAYMENT DISBURSEMENT</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DISBURSE STAGGERED CONTRACTOR PAYMENT</h3>
                </div>
                <button type="button" @click="disburseModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/disburse` : '#'" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Summary Card -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl grid grid-cols-3 gap-3 text-center text-xs">
                    <div class="border-r border-slate-200 pr-2">
                        <span class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">RA BILL NO.</span>
                        <span class="text-xs font-mono font-black text-slate-900 mt-0.5 block" x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span>
                    </div>
                    <div class="border-r border-slate-200 pr-2">
                        <span class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">NET APPROVED</span>
                        <span class="text-xs font-mono font-black text-blue-900 mt-0.5 block" x-text="selectedBill ? '₹' + numberFormat(selectedBill.net_approved_amount) : ''"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold text-rose-700 uppercase tracking-wider">OUTSTANDING BAL.</span>
                        <span class="text-xs font-mono font-black text-rose-700 mt-0.5 block" x-text="selectedBill ? '₹' + numberFormat(selectedBill.balance_amount) : ''"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSEMENT DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1.5">PAID AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" :max="selectedBill ? selectedBill.balance_amount : 0" required
                               class="w-full px-3.5 py-2.5 bg-emerald-50/70 border border-emerald-300 rounded-xl text-sm font-mono font-black text-emerald-950 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
                               oninput="window.updateAmountInWordsForInput && window.updateAmountInWordsForInput(this)">
                        <p class="mt-1 text-[10px] font-bold text-slate-500" x-text="selectedBill ? 'Max Payable Balance: ₹' + numberFormat(selectedBill.balance_amount) : ''"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                        <select name="company_bank_account_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }} (Bal: ₹{{ number_format((float)$bank->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_mode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                            <option value="NEFT">NEFT Transfer</option>
                            <option value="RTGS">RTGS Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI / Net Banking</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">REFERENCE NO (CHEQUE # / UTR #)</label>
                    <input type="text" name="reference_no" placeholder="e.g. UTR123456789 or Chq #000123"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="disburseModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">
                        RELEASE PAYMENT & PRINT VOUCHER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 4: REGISTER NEW CONTRACTOR MASTER ── -->
    <div x-show="addContractorModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="addContractorModalOpen = false">
            <div class="bg-slate-900 p-5 text-white flex items-center justify-between border-b border-slate-800">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-blue-500/20 text-blue-300 text-[9px] font-black uppercase tracking-wider rounded border border-blue-500/40 mb-1">CONTRACTOR MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">REGISTER NEW CONTRACTOR MASTER</h3>
                </div>
                <button type="button" @click="addContractorModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('expenses.ra-bills.contractor.store') }}" method="POST" class="p-6 space-y-4 text-xs font-semibold">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">CONTRACTOR / COMPANY NAME <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. BuildRight Constructions Pvt Ltd"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                        <input type="text" name="phone" placeholder="e.g. +91 9876543210"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                        <input type="email" name="email" placeholder="e.g. contact@builder.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">GSTIN</label>
                        <input type="text" name="gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAN NUMBER</label>
                        <input type="text" name="pan" placeholder="AABCB1234C"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all uppercase">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">OFFICE ADDRESS</label>
                    <textarea name="address" rows="2" placeholder="Street, City, Pin details..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addContractorModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE CONTRACTOR</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function raBillManagement() {
    return {
        activeTab: '{{ request('tab', 'bills') }}',
        searchQuery: '',
        ledgerSearchQuery: '',
        selectedLedgerContractorId: '',
        addModalOpen: {{ $errors->has('ra_bill_number') || $errors->has('contractor_id') || $errors->has('gross_amount') ? 'true' : 'false' }},
        addContractorModalOpen: {{ $errors->has('name') ? 'true' : 'false' }},
        verifyModalOpen: false,
        disburseModalOpen: false,
        selectedBill: null,
        correctionInput: 0,
        calculatedNet: 0,
        allContractors: @json($contractors),
        allProjects: @json($projects),
        selectedContractorId: '{{ old('contractor_id') }}',
        selectedProjectId: '{{ old('project_id') }}',
        selectedUnitId: '{{ old('unit_id') }}',
        allUnits: @json($units),
        availableUnits: [],
        allEngineers: @json($engineers),
        selectedEngineerId: '',
        verifyDateInput: '{{ date("Y-m-d") }}',
        verifyRemarksInput: '',
        contractorLedgerSummaries: @json($contractorLedgerSummaries ?? []),
        allLedgerEntries: @json($allLedgerEntries ?? []),

        init() {
            if (!this.selectedContractorId && this.allContractors && this.allContractors.length === 1) {
                this.selectedContractorId = String(this.allContractors[0].id);
            }
            if (!this.selectedProjectId && this.allProjects && this.allProjects.length === 1) {
                this.selectedProjectId = String(this.allProjects[0].id);
            }
            this.filterUnits();
        },

        filterUnits() {
            if (!this.selectedProjectId) {
                this.availableUnits = this.allUnits;
            } else {
                this.availableUnits = this.allUnits.filter(u => u.project_id == this.selectedProjectId);
            }
            if (!this.selectedUnitId && this.availableUnits && this.availableUnits.length === 1) {
                this.selectedUnitId = String(this.availableUnits[0].id);
            }
        },

        getSelectedContractorName() {
            if (!this.selectedLedgerContractorId) return 'All Contractors';
            const c = (this.allContractors || []).find(x => x.id == this.selectedLedgerContractorId);
            return c ? c.name : 'All Contractors';
        },

        getFilteredContractorsList(search = '') {
            if (!search) return this.allContractors || [];
            const q = search.toLowerCase().trim();
            return (this.allContractors || []).filter(c => (c.name || '').toLowerCase().includes(q));
        },

        filteredLedgerEntries() {
            let entries = this.allLedgerEntries;
            if (this.selectedLedgerContractorId) {
                entries = entries.filter(e => e.contractor_id == this.selectedLedgerContractorId);
            }
            if (this.ledgerSearchQuery) {
                const q = this.ledgerSearchQuery.toLowerCase().trim();
                entries = entries.filter(e =>
                    (e.contractor_name && e.contractor_name.toLowerCase().includes(q)) ||
                    (e.project_name && e.project_name.toLowerCase().includes(q)) ||
                    (e.unit_name && e.unit_name.toLowerCase().includes(q)) ||
                    (e.particulars && e.particulars.toLowerCase().includes(q)) ||
                    (e.ra_bill_number && e.ra_bill_number.toLowerCase().includes(q)) ||
                    (e.ref_no && e.ref_no.toLowerCase().includes(q))
                );
            }
            return entries;
        },

        getLedgerTotals() {
            const entries = this.filteredLedgerEntries();
            const netClaimed = entries.reduce((acc, e) => acc + (parseFloat(e.net_approved) || 0), 0);
            const paid = entries.reduce((acc, e) => acc + (parseFloat(e.paid_amount) || 0), 0);
            return {
                netClaimed: netClaimed,
                paid: paid,
                balance: netClaimed - paid
            };
        },

        openVerifyModal(bill) {
            this.selectedBill = bill;
            this.correctionInput = bill.correction_amount || 0;
            this.calculatedNet = Math.max(0, bill.gross_amount - this.correctionInput);
            this.verifyRemarksInput = bill.remarks || '';

            if (bill.verified_date) {
                this.verifyDateInput = String(bill.verified_date).substring(0, 10);
            } else {
                this.verifyDateInput = '{{ date("Y-m-d") }}';
            }

            let matchedEng = this.allEngineers.find(e => bill.engineer_name && bill.engineer_name.toLowerCase().includes(e.name.toLowerCase()));
            this.selectedEngineerId = matchedEng ? matchedEng.id : (bill.engineer_id || '');

            this.verifyModalOpen = true;
        },

        openDisburseModal(bill) {
            this.selectedBill = bill;
            this.disburseModalOpen = true;
        },

        recalcNet() {
            if (!this.selectedBill) return;
            const gross = parseFloat(this.selectedBill.gross_amount) || 0;
            let corr = parseFloat(this.correctionInput) || 0;
            if (corr > gross) {
                corr = gross;
                this.correctionInput = gross;
            }
            this.calculatedNet = Math.max(0, gross - corr);
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