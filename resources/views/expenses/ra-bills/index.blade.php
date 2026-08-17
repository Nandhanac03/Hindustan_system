@extends('layouts.erp')

@section('title', 'Contractor RA Progress Bills Directory')

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
                <span class="text-[#a38c29] font-bold">CONTRACTOR RA PROGRESS BILLS</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Contractor Running Account (RA) Bills</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Progress Billing</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="addModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span> Contractor RA Bill</span>
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

    <!-- ── EXECUTIVE KPI METRICS BAR (MATCHING EXECUTIVE DASHBOARD) ── -->
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

    <!-- ── EXCEL-MATCHED RA PROGRESS BILLS TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Table Control Toolbar -->
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700">Contractor RA Bills Register</span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full font-bold">{{ $raBills->count() }} Records</span>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="text" x-model="searchQuery" placeholder="Search RA Bill #, Contractor..."
                       class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-3.5 text-center">RA BILL NO</th>
                        <th class="px-3 py-3.5">CONTRACTOR / PROJECT / UNIT</th>
                        <th class="px-3 py-3.5 text-center">SUBMIT DATE</th>
                        <th class="px-3 py-3.5 text-right">RA BILL AMOUNT</th>
                        <th class="px-3 py-3.5 text-center">VERIFIED DATE</th>
                        <th class="px-3 py-3.5 text-right">CORRECTION</th>
                        <th class="px-3 py-3.5 text-right bg-[#8a7522]/30">AFTER CORRECTION</th>
                        <th class="px-3 py-3.5 text-center">DUE DATE</th>
                        <th class="px-3 py-3.5 text-center">PAID DATE</th>
                        <th class="px-3 py-3.5 text-right text-emerald-200">PAID AMOUNT</th>
                        <th class="px-3 py-3.5 text-right text-rose-200">BALANCE AMOUNT</th>
                        <th class="px-3 py-3.5 text-center">STATUS</th>
                        <th class="px-3 py-3.5 text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                    @forelse($raBills as $bill)
                        <tr class="hover:bg-slate-50 transition">
                            <!-- RA BILL NO -->
                            <td class="px-3 py-3.5 text-center font-mono font-black text-slate-900 bg-slate-50/50">
                                {{ $bill->ra_bill_number }}
                            </td>

                            <!-- CONTRACTOR / PROJECT / UNIT -->
                            <td class="px-3 py-3.5">
                                <div class="font-bold text-slate-900">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                <div class="text-[10px] text-slate-500 font-semibold flex items-center gap-1.5 mt-0.5">
                                    <span>{{ $bill->project->name ?? 'Site Project' }}</span>
                                    @if($bill->unit_name || $bill->unit)
                                        <span class="text-slate-300">•</span>
                                        <span class="bg-amber-100 text-amber-900 px-1.5 py-0.2 rounded text-[9px] font-bold">Unit: {{ $bill->unit_name ?: ($bill->unit->door_no ?? '') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- RA BILL SUBMIT DATE -->
                            <td class="px-3 py-3.5 text-center text-slate-600 font-mono">
                                {{ $bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—' }}
                            </td>

                            <!-- RA BILL AMOUNT (GROSS) -->
                            <td class="px-3 py-3.5 text-right font-mono font-bold text-slate-900">
                                ₹{{ number_format((float) $bill->gross_amount, 2) }}
                            </td>

                            <!-- RA BILL VERIFIED DATE -->
                            <td class="px-3 py-3.5 text-center font-mono">
                                @if($bill->verified_date)
                                    <span class="text-emerald-700 font-bold block">{{ $bill->verified_date->format('d/m/Y') }}</span>
                                    <div class="text-[10px] text-slate-700 font-semibold mt-0.5">By: {{ $bill->engineer_name ?: 'Engineer' }}</div>
                                @else
                                    <span class="text-amber-600 italic">Unverified</span>
                                @endif
                            </td>

                            <!-- CORRECTION OF BILL -->
                            <td class="px-3 py-3.5 text-right font-mono text-amber-700 font-bold">
                                {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                            </td>

                            <!-- RA BILL AFTER CORRECTION (NET APPROVED) -->
                            <td class="px-3 py-3.5 text-right font-mono font-black text-blue-900 bg-blue-50/30">
                                ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                            </td>

                            <!-- RA BILL DUE DATE -->
                            <td class="px-3 py-3.5 text-center font-mono text-slate-600">
                                {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                            </td>

                            <!-- RA BILL PAID DATE -->
                            <td class="px-3 py-3.5 text-center font-mono text-slate-600">
                                @if($bill->payments->count() > 0)
                                    <div class="text-emerald-700 font-bold">{{ $bill->payments->last()->payment_date->format('d/m/Y') }}</div>
                                    @if($bill->payments->count() > 1)
                                        <div class="text-[9px] text-slate-400">({{ $bill->payments->count() }} Installments)</div>
                                    @endif
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <!-- PAID AMOUNT -->
                            <td class="px-3 py-3.5 text-right font-mono font-bold text-emerald-700">
                                ₹{{ number_format((float) $bill->paid_amount, 2) }}
                            </td>

                            <!-- BALANCE AMOUNT -->
                            <td class="px-3 py-3.5 text-right font-mono font-black text-rose-700">
                                ₹{{ number_format((float) $bill->balance_amount, 2) }}
                            </td>

                            <!-- STATUS -->
                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                @if($bill->status === 'cleared')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1.5 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-[#087443]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Cleared</span>
                                    </span>
                                @elseif($bill->status === 'partially_paid')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center gap-1.5 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        <span>Partially Paid</span>
                                    </span>
                                @elseif($bill->verified_date)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 inline-flex items-center gap-1.5 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Verified</span>
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1.5 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        <span>Submitted</span>
                                    </span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Verify Button / Verified Button -->
                                    @if($bill->verified_date)
                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                class="px-3 py-1 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-full text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer border border-[#a38c29]/40"
                                                title="Verified By: {{ $bill->engineer_name }}. Click to view or update sign-off.">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Verified</span>
                                        </button>
                                    @else
                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                class="px-3 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-full text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                                title="Engineer Sign-off & Apply Correction">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Verify</span>
                                        </button>
                                    @endif

                                    <!-- Pay Installment Button -->
                                    @if((float)$bill->balance_amount > 0)
                                        <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                                title="Disburse Staggered Payment">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Pay</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                No Contractor RA Progress Bills recorded yet. Click "+  Contractor RA Bill" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── MODAL 1: LOG NEW CONTRACTOR RA BILL ── -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden border border-slate-200" @click.away="addModalOpen = false">
            <div class="bg-gradient-to-r from-[#a38c29] to-[#8a7522] p-4 text-white flex items-center justify-between">
                <h3 class="font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span> New Contractor RA Progress Bill</span>
                </h3>
                <button type="button" @click="addModalOpen = false" class="text-white/80 hover:text-white font-bold">✕</button>
            </div>

            <form action="{{ route('expenses.ra-bills.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('ra_bill_number') ? 'text-rose-600' : 'text-slate-700' }}">RA BILL NO *</label>
                        <input type="text" name="ra_bill_number" value="{{ old('ra_bill_number') }}" placeholder="e.g. 1 or RA-001" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('ra_bill_number') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('ra_bill_number')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('submit_date') ? 'text-rose-600' : 'text-slate-700' }}">CONTRACTOR SUBMIT DATE *</label>
                        <input type="date" name="submit_date" value="{{ old('submit_date', date('Y-m-d')) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('submit_date') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('submit_date')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('contractor_id') ? 'text-rose-600' : 'text-slate-700' }}">CONTRACTOR NAME *</label>
                        <select name="contractor_id" required 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('contractor_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Contractor</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                        @error('contractor_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('project_id') ? 'text-rose-600' : 'text-slate-700' }}">SITE PROJECT *</label>
                        <select name="project_id" x-model="selectedProjectId" @change="filterUnits()" required 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('project_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Project</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('unit_id') ? 'text-rose-600' : 'text-slate-700' }}">UNIT *</label>
                        <select name="unit_id" required 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('unit_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                            <option value="">Select Unit</option>
                            <template x-for="u in availableUnits" :key="u.id">
                                <option :value="u.id" x-text="u.door_no" :selected="u.id == {{ old('unit_id', 0) }}"></option>
                            </template>
                            @if(empty(old('project_id')))
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->door_no }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 {{ $errors->has('gross_amount') ? 'text-rose-600' : 'text-slate-700' }}">RA BILL GROSS AMOUNT (₹) *</label>
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

                <div class="border-t border-slate-100 pt-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">REMARKS / NOTES</label>
                    <textarea name="remarks" rows="2" placeholder="Notes regarding progress work done..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">{{ old('remarks') }}</textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md border border-[#a38c29]/40 cursor-pointer">Submit RA Bill</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 2: SITE ENGINEER VERIFICATION & CORRECTIONS ── -->
    <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-200/80" @click.away="verifyModalOpen = false">
            <!-- ── HERO BADGE HEADER ── -->
            <div class="bg-gradient-to-r from-[#9e821b] via-[#a38c29] to-[#806915] p-6 text-white flex items-center gap-5 relative overflow-hidden">
                <!-- Sparkle background accent -->
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <!-- Large Checkmark Circular Badge -->
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white flex items-center justify-center p-2 shadow-xl shrink-0 z-10">
                    <div class="w-full h-full rounded-full bg-[#a38c29] flex items-center justify-center">
                        <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Title & Subtitle Text -->
                <div class="flex-1 pr-8 z-10">
                    <h3 class="font-black text-base md:text-lg uppercase tracking-wider text-white leading-tight">
                        Site Engineer Verification & Correction Sign-Off
                    </h3>
                    <p class="text-xs text-white/90 font-medium mt-1">
                        The RA Bill has been successfully verified and corrected.
                    </p>
                </div>
                
                <!-- Close Button -->
                <button type="button" @click="verifyModalOpen = false" 
                        class="w-8 h-8 rounded-full bg-white/90 hover:bg-white text-slate-700 flex items-center justify-center font-bold text-xs shadow-sm transition-all cursor-pointer absolute top-5 right-5 z-10">
                    ✕
                </button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/verify` : '#'" method="POST" class="p-6 space-y-5">
                @csrf
                
                <!-- ── 1. RA BILL SUMMARY KPI CARD ── -->
                <div class="p-4 bg-white border border-amber-200/80 rounded-2xl shadow-2xs grid grid-cols-3 gap-4 items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">RA BILL NO.</span>
                            <span class="text-xs md:text-sm font-mono font-black text-slate-900" x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">GROSS CLAIMED</span>
                        <span class="text-xs md:text-sm font-mono font-black text-slate-900" x-text="selectedBill ? '₹' + numberFormat(selectedBill.gross_amount) : ''"></span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">STATUS</span>
                        <span x-show="selectedBill && selectedBill.status === 'cleared'" class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Cleared</span>
                        </span>
                        <span x-show="selectedBill && selectedBill.status !== 'cleared' && selectedBill.verified_date" class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Verified</span>
                        </span>
                        <span x-show="selectedBill && !selectedBill.verified_date" class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <span>Submitted</span>
                        </span>
                    </div>
                </div>

                <!-- ── 2. VERIFICATION ALREADY DONE ALERT BANNER ── -->
                <template x-if="selectedBill && selectedBill.verified_date">
                    <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/90 rounded-2xl flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-emerald-800 font-extrabold">
                            <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>VERIFICATION ALREADY DONE</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 font-semibold">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Verified By: <span class="font-bold text-slate-900" x-text="selectedBill.engineer_name || 'Engineer'"></span></span>
                        </div>
                    </div>
                </template>

                <!-- ── 3. FORM FIELDS ── -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>VERIFIED DATE *</span>
                        </label>
                        <input type="date" name="verified_date" x-model="verifyDateInput" required
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>SITE ENGINEER (FROM MASTER) *</span>
                        </label>
                        <select name="engineer_id" x-model="selectedEngineerId" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all shadow-2xs">
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
                        <label class="block text-xs font-extrabold text-amber-900 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>CORRECTION OF BILL (DEDUCTION ₹) *</span>
                        </label>
                        <input type="number" step="0.01" name="correction_amount" x-model="correctionInput" @input="recalcNet()" required
                               class="w-full px-4 py-3 bg-amber-50/60 border border-amber-200 rounded-xl text-sm font-mono font-black text-amber-950 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-blue-900 uppercase tracking-wider mb-1.5">NET RA PAYABLE AFTER CORRECTION</label>
                        <div class="w-full px-4 py-3 bg-blue-50/70 border border-blue-200 rounded-xl text-sm font-mono font-black text-blue-950 flex items-center min-h-[46px] shadow-2xs"
                             x-text="'₹ ' + numberFormat(calculatedNet)"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 002-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>VERIFICATION REMARKS</span>
                    </label>
                    <textarea name="remarks" rows="2" x-model="verifyRemarksInput" placeholder="Details of corrections/retentions applied..."
                              class="w-full px-4 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all shadow-2xs"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="verifyModalOpen = false" 
                            class="px-6 py-3 bg-slate-200/80 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="selectedBill && selectedBill.verified_date ? 'Update Verification Sign-Off' : 'Confirm Sign-Off'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 3: STAGGERED DISBURSEMENT RELEASE ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-200" @click.away="disburseModalOpen = false">
            <div class="bg-emerald-700 p-4 text-white flex items-center justify-between">
                <h3 class="font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Disburse Staggered Payment (Payment Voucher)</span>
                </h3>
                <button type="button" @click="disburseModalOpen = false" class="text-white/80 hover:text-white font-bold">✕</button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/disburse` : '#'" method="POST" class="p-5 space-y-4">
                @csrf
                
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1 text-xs">
                    <div class="flex justify-between font-bold text-emerald-950">
                        <span>RA BILL NO: <span x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span></span>
                        <span>OUTSTANDING BAL: <span x-text="selectedBill ? '₹' + numberFormat(selectedBill.balance_amount) : ''" class="text-rose-700 font-black"></span></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSEMENT DATE *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1.5">PAID AMOUNT (₹) *</label>
                        <input type="number" step="0.01" name="paid_amount" :max="selectedBill ? selectedBill.balance_amount : 0" required
                               class="w-full px-3.5 py-2.5 bg-emerald-50/70 border border-emerald-300/80 rounded-xl text-sm font-mono font-bold text-emerald-950 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISBURSE FROM BANK ACCOUNT *</label>
                        <select name="company_bank_account_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }} (Bal: ₹{{ number_format((float)$bank->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAYMENT MODE *</label>
                        <select name="payment_mode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all">
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
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all">
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="disburseModalOpen = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">Release Payment & Print Voucher</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function raBillManagement() {
    return {
        searchQuery: '',
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        verifyModalOpen: false,
        disburseModalOpen: false,
        selectedBill: null,
        correctionInput: 0,
        calculatedNet: 0,
        selectedProjectId: '{{ old('project_id') }}',
        allUnits: @json($units),
        availableUnits: [],
        allEngineers: @json($engineers),
        selectedEngineerId: '',
        verifyDateInput: '{{ date("Y-m-d") }}',
        verifyRemarksInput: '',

        init() {
            this.filterUnits();
        },

        filterUnits() {
            if (!this.selectedProjectId) {
                this.availableUnits = this.allUnits;
            } else {
                this.availableUnits = this.allUnits.filter(u => u.project_id == this.selectedProjectId);
            }
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
            const corr = parseFloat(this.correctionInput) || 0;
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
