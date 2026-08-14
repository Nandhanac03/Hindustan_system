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
                                    <span class="text-emerald-700 font-bold">{{ $bill->verified_date->format('d/m/Y') }}</span>
                                    <div class="text-[9px] text-slate-400 font-normal">By: {{ $bill->engineer_name ?: 'Engineer' }}</div>
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
                            <td class="px-3 py-3.5 text-center">
                                @if($bill->status === 'cleared')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300 inline-block">
                                        ✅ CLEARED
                                    </span>
                                @elseif($bill->status === 'partially_paid')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-300 inline-block">
                                        🔵 PARTIALLY PAID
                                    </span>
                                @elseif($bill->verified_date)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300 inline-block">
                                        ⏳ VERIFIED PAYABLE
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-300 inline-block">
                                        📝 SUBMITTED
                                    </span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-3 py-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Verify Button -->
                                    <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                            class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[10px] font-bold uppercase transition flex items-center gap-1 shadow-2xs"
                                            title="Engineer Sign-off & Apply Correction">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Verify</span>
                                    </button>

                                    <!-- Pay Installment Button -->
                                    @if((float)$bill->balance_amount > 0)
                                        <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold uppercase transition flex items-center gap-1 shadow-2xs"
                                                title="Disburse Staggered Payment">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('ra_bill_number') ? 'text-rose-600' : 'text-slate-600' }}">RA BILL NO *</label>
                        <input type="text" name="ra_bill_number" value="{{ old('ra_bill_number') }}" placeholder="e.g. 1 or RA-001" required
                               class="w-full px-3 py-2 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('ra_bill_number') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
                        @error('ra_bill_number')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('submit_date') ? 'text-rose-600' : 'text-slate-600' }}">CONTRACTOR SUBMIT DATE *</label>
                        <input type="date" name="submit_date" value="{{ old('submit_date', date('Y-m-d')) }}" required
                               class="w-full px-3 py-2 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('submit_date') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
                        @error('submit_date')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('contractor_id') ? 'text-rose-600' : 'text-slate-600' }}">CONTRACTOR NAME *</label>
                        <select name="contractor_id" required 
                                class="w-full px-3 py-2 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('contractor_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
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
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('project_id') ? 'text-rose-600' : 'text-slate-600' }}">SITE PROJECT *</label>
                        <select name="project_id" x-model="selectedProjectId" @change="filterUnits()" required 
                                class="w-full px-3 py-2 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('project_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
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
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('unit_id') ? 'text-rose-600' : 'text-slate-600' }}">UNIT *</label>
                        <select name="unit_id" required 
                                class="w-full px-3 py-2 rounded-xl text-xs font-bold focus:outline-none transition-all {{ $errors->has('unit_id') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
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
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('gross_amount') ? 'text-rose-600' : 'text-slate-600' }}">RA BILL GROSS AMOUNT (₹) *</label>
                        <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount') }}" placeholder="5000000" required
                               class="w-full px-3 py-2 rounded-xl text-xs font-mono font-black focus:outline-none transition-all {{ $errors->has('gross_amount') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29]' }}">
                        @error('gross_amount')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">RA BILL DUE DATE</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">REMARKS / NOTES</label>
                    <textarea name="remarks" rows="2" placeholder="Notes regarding progress work done..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">{{ old('remarks') }}</textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md">Submit RA Bill</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 2: SITE ENGINEER VERIFICATION & CORRECTIONS ── -->
    <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-200" @click.away="verifyModalOpen = false">
            <div class="bg-amber-600 p-4 text-white flex items-center justify-between">
                <h3 class="font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Site Engineer Verification & Correction Sign-Off</span>
                </h3>
                <button type="button" @click="verifyModalOpen = false" class="text-white/80 hover:text-white font-bold">✕</button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/verify` : '#'" method="POST" class="p-5 space-y-4">
                @csrf
                
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl space-y-1 text-xs">
                    <div class="flex justify-between font-bold text-amber-900">
                        <span>RA BILL NO: <span x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span></span>
                        <span>GROSS CLAIMED: <span x-text="selectedBill ? '₹' + numberFormat(selectedBill.gross_amount) : ''"></span></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">VERIFIED DATE *</label>
                        <input type="date" name="verified_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">SITE ENGINEER (FROM MASTER) *</label>
                        <select name="engineer_id" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <option value="">Select Verifying Engineer</option>
                            @foreach($engineers as $eng)
                                <option value="{{ $eng->id }}">
                                    {{ $eng->name }} {{ $eng->designation ? '('.$eng->designation.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-amber-800 uppercase tracking-wider mb-1">CORRECTION OF BILL (DEDUCTION ₹) *</label>
                        <input type="number" step="0.01" name="correction_amount" x-model="correctionInput" @input="recalcNet()" required
                               class="w-full px-3 py-2 bg-amber-50 border border-amber-300 rounded-xl text-xs font-mono font-black text-amber-950 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-1">NET RA PAYABLE AFTER CORRECTION</label>
                        <div class="px-3 py-2 bg-blue-50 border border-blue-300 rounded-xl text-xs font-mono font-black text-blue-950"
                             x-text="'₹' + numberFormat(calculatedNet)"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">VERIFICATION REMARKS</label>
                    <textarea name="remarks" rows="2" placeholder="Details of corrections/retentions applied..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="verifyModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md">Confirm Sign-Off</button>
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
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSEMENT DATE *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1">PAID AMOUNT (₹) *</label>
                        <input type="number" step="0.01" name="paid_amount" :max="selectedBill ? selectedBill.balance_amount : 0" required
                               class="w-full px-3 py-2 bg-emerald-50 border border-emerald-300 rounded-xl text-xs font-mono font-black text-emerald-950 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DISBURSE FROM BANK ACCOUNT *</label>
                        <select name="company_bank_account_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($companyBankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->bank_name }} — A/C: {{ $bank->account_number }} (Bal: ₹{{ number_format((float)$bank->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAYMENT MODE *</label>
                        <select name="payment_mode" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="NEFT">NEFT Transfer</option>
                            <option value="RTGS">RTGS Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI / Net Banking</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">REFERENCE NO (CHEQUE # / UTR #)</label>
                    <input type="text" name="reference_no" placeholder="e.g. UTR123456789 or Chq #000123"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="disburseModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md">Release Payment & Print Voucher</button>
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
