@extends('layouts.erp')

@section('title', 'RA Bill Verification & Sign-off')

@section('content')
<div x-data="raBillVerification()" class="space-y-6">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>CONTRACTOR OPERATIONS</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">RA BILL VERIFICATION</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>RA Progress Bills Verification Desk</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Inward Claims & Engineer Sign-Off</span>
            </h1>
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

    <!-- Executive KPI Metrics Bar (Upgraded with Icons & Hover Effects) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total RA Claimed -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-slate-800 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">TOTAL RA CLAIMED</span>
                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-800 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight group-hover:text-slate-800 transition-colors">₹{{ number_format((float) $totalGross, 2) }}</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">{{ $raBills->count() }} Inward RA Progress Bills</div>
            </div>
        </div>

        <!-- Card 2: Engineer Deductions -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-amber-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">ENGINEER DEDUCTIONS</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-amber-700 tracking-tight group-hover:text-amber-800 transition-colors">-₹{{ number_format((float) $totalCorrections, 2) }}</div>
                <div class="text-[10px] text-amber-600 font-bold mt-1.5 pt-1.5 border-t border-amber-50">Total Corrections Applied</div>
            </div>
        </div>

        <!-- Card 3: Net Approved Liabilities -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">NET APPROVED LIABILITIES</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5 border-t border-blue-50">Verified Payable Claimed</div>
            </div>
        </div>

        <!-- Card 4: Verification Sign-offs -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">VERIFICATION SIGN-OFFS</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4m-10 12h8m-8 4h8m-16 0h.01M3 16h.01M3 12h.01M3 8h.01M3 4h.01"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors">{{ $raBills->whereNotNull('verified_date')->count() }} / {{ $raBills->count() }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5 border-t border-emerald-50">Completed Engineer Sign-Offs</div>
            </div>
        </div>
    </div>

    <!-- Excel-Matched RA Progress Bills Register Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">RA Progress Bills & Verification Sign-Off Register</span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ $raBills->count() }} Records</span>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Export Excel Button -->
                <button type="button" @click="exportExcel('classic')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-xs hover:shadow-md cursor-pointer border border-emerald-500/40">
                    <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export Excel</span>
                </button>

                <!-- New RA Progress Bill Button -->
                <button type="button" @click="addModalOpen = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-xs hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>New RA Progress Bill</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3 py-3 text-left w-[85px]">RA BILL NO</th>
                        <th class="px-3 py-3 text-left w-[180px]">CONTRACTOR / PROJECT</th>
                        <th class="px-3 py-3 text-left w-[120px]">SUBMIT / VERIFIED</th>
                        <th class="px-3 py-3 text-right w-[110px]">RA BILL AMOUNT</th>
                        <th class="px-3 py-3 text-right w-[110px]">ADDITIONAL WORK</th>
                        <th class="px-3 py-3 text-right w-[100px]">CORRECTION</th>
                        <th class="px-3 py-3 text-right bg-[#8a7522]/40 w-[110px]">AFTER CORRECTION</th>
                        <th class="px-3 py-3 text-center w-[110px]">DUE DATE</th>
                        <th class="px-3 py-3 text-center w-[90px]">STATUS</th>
                        <th class="px-3 py-3 text-right w-[110px]">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    @forelse($raBills as $bill)
                        <tr class="hover:bg-amber-50/20 transition-colors border-b border-slate-100">
                            <td class="px-3 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-[10.5px] whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                            </td>

                            <td class="px-3 py-3 align-middle">
                                <div class="font-black text-slate-900 text-[11.5px] leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                <div class="text-[10px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                <!-- @if($bill->unit_name || $bill->unit)
                                    <div class="mt-0.5">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 bg-amber-100/90 text-amber-950 border border-amber-300/70 rounded text-[9px] font-black uppercase tracking-wider whitespace-nowrap shadow-2xs">
                                            <span>Unit: {{ $bill->unit_name ?: ($bill->unit->door_no ?? '') }}</span>
                                        </span>
                                    </div>
                                @endif -->
                            </td>

                            <td class="px-3 py-3 text-left font-mono align-middle">
                                <div class="text-slate-700 font-bold text-[10.5px]">
                                    {{ $bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—' }}
                                </div>
                                @if($bill->verified_date)
                                    <div class="text-[9.5px] text-emerald-700 font-bold mt-0.5 whitespace-nowrap" title="Verified By: {{ $bill->engineer_name }}">
                                        Ver: {{ $bill->verified_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[8.5px] text-slate-500 font-semibold truncate max-w-[100px]">
                                        By: {{ $bill->engineer_name ?: 'Engineer' }}
                                    </div>
                                @else
                                    <div class="text-[9.5px] text-amber-600 italic font-medium mt-0.5">Unverified</div>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right font-mono font-bold text-slate-900 align-middle">
                                ₹{{ number_format((float) $bill->gross_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-right font-mono align-middle">
                                @if((float)$bill->additional_amount > 0)
                                    <div class="font-bold text-slate-900 text-[11px]">
                                        ₹{{ number_format((float)$bill->additional_amount, 2) }}
                                    </div>
                                    @if((float)$bill->gross_amount > 0)
                                        @php
                                            $pct = round(((float)$bill->additional_amount / (float)$bill->gross_amount) * 100, 1);
                                            $formattedPct = ($pct == (int)$pct) ? (int)$pct : $pct;
                                        @endphp
                                        <div class="text-[9.5px] font-black text-amber-700 mt-0.5 whitespace-nowrap">
                                            ({{ $formattedPct }}%)
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-bold">—</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right font-mono text-amber-700 font-bold align-middle">
                                {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                            </td>

                            <td class="px-3 py-3 text-right font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-center font-mono align-middle">
                                <div class="text-slate-700 font-bold text-[10.5px]">
                                    {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                                </div>
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap align-middle">
                                @if($bill->verified_date)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>VERIFIED</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>SUBMITTED</span>
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right whitespace-nowrap align-middle">
                                @if($bill->verified_date)
                                    <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                            class="px-3 py-1 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-[10.5px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer border border-[#a38c29]/40"
                                            title="Verified By: {{ $bill->engineer_name }}. Click to view or update sign-off.">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Edit Sign-off</span>
                                    </button>
                                @else
                                    <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                            class="px-3 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-[10.5px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                            title="Engineer Sign-off & Apply Correction">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Verify Sign-off</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                No Contractor RA Progress Bills recorded yet. Click "+ Log New RA Progress Bill" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── MODAL 1: LOG NEW CONTRACTOR RA BILL ── -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="addModalOpen = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONTRACTOR RA BILLS</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">LOG NEW CONTRACTOR RA PROGRESS BILL</h3>
                </div>
                <button type="button" @click="addModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('expenses.ra-bills.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Row 1: Bill No & Submit Date -->
                <div class="grid grid-cols-2 gap-4 items-start">
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

                <!-- Row 2: Contractor & Project -->
                <div class="grid grid-cols-2 gap-4 items-start">
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

                <!-- Row 3: Gross Amount & Due Date -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('gross_amount') ? 'text-rose-600' : '' }}">RA BILL GROSS AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="gross_amount" x-model="newGrossInput" @input="calcAdditionalFromPercent()" value="{{ old('gross_amount') }}" placeholder="5000000" required
                               class="w-full px-3.5 py-2.5 rounded-xl text-sm font-mono font-bold focus:outline-none transition-all {{ $errors->has('gross_amount') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('gross_amount')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">RA BILL DUE DATE</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Row 4: Additional Work (% and Amount) -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ADDITIONAL WORK (%)</label>
                        <input type="number" step="0.01" x-model="newAdditionalPercent" @input="calcAdditionalFromPercent()" placeholder="e.g. 12 or 20"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                        <p class="mt-1 text-[10px] font-bold text-slate-400">e.g. Type 12 for 12%</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('additional_amount') ? 'text-rose-600' : '' }}">ADDITIONAL WORK (₹)</label>
                        <input type="number" step="0.01" name="additional_amount" x-model="newAdditionalAmount" @input="calcPercentFromAdditional()" value="{{ old('additional_amount') }}" placeholder="0.00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all {{ $errors->has('additional_amount') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900' : '' }}">
                        @error('additional_amount')
                            <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                        @enderror
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

            <form :action="selectedBill ? '{{ url('expenses/ra-bills') }}/' + selectedBill.id + '/verify' : '#'" method="POST" class="p-6 space-y-4">
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
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">ADDITIONAL WORK</span>
                        <span class="text-xs font-mono font-black text-slate-700" x-text="selectedBill && parseFloat(selectedBill.additional_amount) > 0 ? '₹' + numberFormat(selectedBill.additional_amount) + (parseFloat(selectedBill.gross_amount) > 0 ? ' (' + calcPercentage(selectedBill.additional_amount, selectedBill.gross_amount) + '%)' : '') : '—'"></span>
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
                        <p class="mt-1 text-[10px] font-bold text-slate-500" x-text="selectedBill ? 'Max Correction: ₹' + numberFormat((parseFloat(selectedBill.gross_amount) || 0) + (parseFloat(selectedBill.additional_amount) || 0)) : ''"></p>
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

</div>

<script>
function raBillVerification() {
    return {
        searchQuery: '',
        addModalOpen: {{ $errors->has('ra_bill_number') || $errors->has('contractor_id') || $errors->has('gross_amount') ? 'true' : 'false' }},
        verifyModalOpen: false,
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
        newGrossInput: '{{ old('gross_amount', '') }}',
        newAdditionalPercent: '',
        newAdditionalAmount: '{{ old('additional_amount', '') }}',

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

        openVerifyModal(bill) {
            this.selectedBill = bill;
            this.correctionInput = bill.correction_amount || 0;
            this.calculatedNet = Math.max(0, (parseFloat(bill.gross_amount) || 0) + (parseFloat(bill.additional_amount) || 0) - this.correctionInput);
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

        recalcNet() {
            if (!this.selectedBill) return;
            const gross = parseFloat(this.selectedBill.gross_amount) || 0;
            const additional = parseFloat(this.selectedBill.additional_amount) || 0;
            let corr = parseFloat(this.correctionInput) || 0;
            if (corr > (gross + additional)) {
                corr = gross + additional;
                this.correctionInput = gross + additional;
            }
            this.calculatedNet = Math.max(0, gross + additional - corr);
        },

        calcAdditionalFromPercent() {
            const gross = parseFloat(this.newGrossInput) || 0;
            const pct = parseFloat(this.newAdditionalPercent) || 0;
            if (gross > 0 && pct > 0) {
                this.newAdditionalAmount = (gross * pct / 100).toFixed(2);
            }
        },

        calcPercentFromAdditional() {
            const gross = parseFloat(this.newGrossInput) || 0;
            const amt = parseFloat(this.newAdditionalAmount) || 0;
            if (gross > 0 && amt > 0) {
                this.newAdditionalPercent = ((amt / gross) * 100).toFixed(2);
            } else {
                this.newAdditionalPercent = '';
            }
        },

        calcPercentage(additional, gross) {
            const add = parseFloat(additional) || 0;
            const g = parseFloat(gross) || 0;
            if (g <= 0 || add <= 0) return '0';
            const pct = Math.round((add / g) * 1000) / 10;
            return pct % 1 === 0 ? pct.toFixed(0) : pct.toFixed(1);
        },

        numberFormat(val) {
            return (parseFloat(val) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        exportExcel(theme = 'gold') {
            const tableId = theme === 'classic' ? '#raBillsExcelTable' : '#raBillsExcelTableGold';
            const table = document.querySelector(tableId);
            if (!table) {
                alert("No data available to export.");
                return;
            }

            const filename = theme === 'classic' 
                ? 'HindustanERP_RA_Bills_Verification_Report_Classic.xlsx'
                : 'HindustanERP_RA_Bills_Verification_Executive_Gold_Report.xlsx';

            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            const sheetName = 'RA Bills Verification';
            const worksheet = workbook.addWorksheet(sheetName);

            const frozenRow = theme === 'classic' ? 3 : 7;
            worksheet.views = [{ state: 'frozen', xSplit: 2, ySplit: frozenRow, activePane: 'bottomRight' }];
            worksheet.pageSetup = {
                paperSize: 9, // A4 landscape
                orientation: 'landscape',
                fitToPage: true,
                fitToWidth: 1,
                fitToHeight: 0
            };
            worksheet.pageSetup.printTitles = theme === 'classic' ? '1:3' : '5:7';

            worksheet.columns = [
                { width: 10 }, // SL NO
                { width: 16 }, // RA BILL NO
                { width: 44 }, // CONTRACTOR NAME
                { width: 50 }, // SITE PROJECT
                { width: 16 }, // SUBMIT DATE
                { width: 24 }, // RA BILL AMOUNT (₹)
                { width: 22 }, // ADDITIONAL WORK (₹)
                { width: 26 }, // CORRECTION / DEDUCTION (₹)
                { width: 28 }, // AFTER CORRECTION (NET PAYABLE ₹)
                { width: 16 }, // DUE DATE
                { width: 16 }, // VERIFIED DATE
                { width: 36 }, // VERIFYING ENGINEER
                { width: 18 }  // STATUS
            ];

            function cssColorToHex(cssColor) {
                if (!cssColor) return null;
                cssColor = cssColor.trim();
                if (cssColor.startsWith('#')) {
                    let hex = cssColor.substring(1);
                    if (hex.length === 3) {
                        hex = hex.split('').map(c => c + c).join('');
                    }
                    return 'FF' + hex.toUpperCase();
                }
                if (cssColor.startsWith('rgb')) {
                    const parts = cssColor.match(/\d+/g);
                    if (parts && parts.length >= 3) {
                        const r = parseInt(parts[0]).toString(16).padStart(2, '0');
                        const g = parseInt(parts[1]).toString(16).padStart(2, '0');
                        const b = parseInt(parts[2]).toString(16).padStart(2, '0');
                        return 'FF' + (r + g + b).toUpperCase();
                    }
                }
                const nameMap = {
                    'white': 'FFFFFFFF',
                    'black': 'FF000000',
                    'red': 'FFFF0000',
                    'green': 'FF00FF00',
                    'blue': 'FF0000FF'
                };
                return nameMap[cssColor.toLowerCase()] || null;
            }

            const rows = table.querySelectorAll("tr");
            const mergedCells = [];

            function isMerged(r, c) {
                return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
            }

            rows.forEach((tr, rIdx) => {
                const sheetRow = worksheet.getRow(rIdx + 1);
                
                const heightAttr = tr.getAttribute("height") || tr.style.height;
                if (heightAttr) {
                    const match = heightAttr.match(/[\d\.]+/);
                    if (match) {
                        sheetRow.height = Math.max(parseFloat(match[0]), 26);
                    }
                } else {
                    sheetRow.height = 26;
                }

                const cells = tr.cells;
                let colIdx = 1;

                for (let cIdx = 0; cIdx < cells.length; cIdx++) {
                    const cell = cells[cIdx];

                    while (isMerged(rIdx + 1, colIdx)) {
                        colIdx++;
                    }

                    const colspan = parseInt(cell.getAttribute("colspan")) || 1;
                    const rowspan = parseInt(cell.getAttribute("rowspan")) || 1;

                    if (colspan > 1 || rowspan > 1) {
                        worksheet.mergeCells(rIdx + 1, colIdx, rIdx + rowspan, colIdx + colspan - 1);
                        mergedCells.push({
                            s: { r: rIdx + 1, c: colIdx },
                            e: { r: rIdx + rowspan, c: colIdx + colspan - 1 }
                        });
                    }

                    const excelCell = worksheet.getCell(rIdx + 1, colIdx);
                    const rawVal = cell.textContent ? cell.textContent.trim() : '';

                    const bgColorAttr = cell.getAttribute("bgcolor") || cell.style.backgroundColor;
                    const bgColorHex = cssColorToHex(bgColorAttr);
                    
                    const textColorAttr = cell.style.color;
                    const textColorHex = cssColorToHex(textColorAttr) || 'FF000000';

                    const isBold = cell.tagName === 'TH' || cell.style.fontWeight === 'bold' || (cell.style.fontWeight && parseInt(cell.style.fontWeight) >= 700);
                    const fontSizeMatch = (cell.style.fontSize || '').match(/[\d\.]+/);
                    const fontSize = fontSizeMatch ? parseFloat(fontSizeMatch[0]) : 10;

                    let horizAlign = cell.style.textAlign || (cell.tagName === 'TH' ? 'center' : 'left');
                    if (horizAlign === 'start') horizAlign = 'left';
                    if (horizAlign === 'end') horizAlign = 'right';

                    let vertAlign = cell.style.verticalAlign || 'middle';
                    const isCurrency = cell.getAttribute("data-format") === "currency" || (cell.style.msoNumberFormat && cell.style.msoNumberFormat.includes('#,##0'));
                    const isDate = cell.getAttribute("data-format") === "date" || (cell.style.msoNumberFormat && cell.style.msoNumberFormat.includes('dd-mmm-yyyy'));
                    
                    // Parse values and apply clean formatting
                    if (isDate || (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal))) {
                        if (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal)) {
                            const [yyyy, mm, dd] = rawVal.split('-');
                            excelCell.value = new Date(parseInt(yyyy), parseInt(mm) - 1, parseInt(dd));
                        } else {
                            excelCell.value = rawVal;
                        }
                        excelCell.numFormat = 'DD-MMM-YYYY';
                    } else if (isCurrency || (rawVal && /^[\-₹\s\d\,\.]+\.?\d*$/.test(rawVal) && cell.tagName !== 'TH')) {
                        const cleanVal = rawVal.replace(/[^\d\.\-]/g, '');
                        const parsedNum = parseFloat(cleanVal);
                        if (rawVal && !isNaN(parsedNum)) {
                            excelCell.value = parsedNum;
                        } else {
                            excelCell.value = 0;
                        }
                        excelCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                    } else {
                        if (rawVal && /^\-?\d+(\.\d+)?$/.test(rawVal) && !cell.getAttribute("colspan")) {
                            excelCell.value = parseFloat(rawVal);
                        } else {
                            excelCell.value = rawVal;
                        }
                    }

                    excelCell.font = {
                        name: 'Calibri',
                        size: fontSize,
                        bold: isBold,
                        color: { argb: textColorHex }
                    };

                    if (bgColorHex) {
                        excelCell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: { argb: bgColorHex }
                        };
                    }

                    excelCell.alignment = {
                        horizontal: horizAlign,
                        vertical: vertAlign,
                        wrapText: false
                    };

                    excelCell.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };

                    colIdx += colspan;
                }
            });

            workbook.xlsx.writeBuffer().then(function (data) {
                const blob = new Blob([data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement("a");
                anchor.href = url;
                anchor.download = filename;
                anchor.click();
                window.URL.revokeObjectURL(url);
            });
        }
    };
}
</script>

<div class="hidden" style="display: none;">
    <!-- ── EXCEL DESIGN OPTION 1: EXECUTIVE LUXURY GOLD & CHARCOAL THEME (WITH KPIS & SEPARATE VERIFIER COLS) ── -->
    <table id="raBillsExcelTableGold" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="55" style="width: 40pt;" />
            <col width="105" style="width: 80pt;" />
            <col width="230" style="width: 175pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="155" style="width: 115pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="175" style="width: 135pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="190" style="width: 145pt;" />
            <col width="115" style="width: 85pt;" />
        </colgroup>
        <thead>
            <tr height="24" style="height: 24pt;">
                <th colspan="13" bgcolor="#1e293b" style="background-color: #1e293b; color: #f59e0b; font-weight: bold; font-size: 10pt; text-align: left; padding-left: 12px; vertical-align: middle; border: 1px solid #334155; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP — EXECUTIVE SUMMARY KPI METRICS & AUDIT SIGN-OFF DESK
                </th>
            </tr>
            <tr height="36" style="height: 36pt;">
                <td colspan="3" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #0f172a; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #cbd5e1; font-family: 'Calibri', 'Aptos', sans-serif;">
                    TOTAL RA CLAIMED: ₹{{ number_format((float)$totalGross, 2) }}
                </td>
                <td colspan="3" bgcolor="#fef2f2" style="background-color: #fef2f2; color: #991b1b; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #fca5a5; font-family: 'Calibri', 'Aptos', sans-serif;">
                    ENGINEER DEDUCTIONS: -₹{{ number_format((float)$totalCorrections, 2) }}
                </td>
                <td colspan="3" bgcolor="#eff6ff" style="background-color: #eff6ff; color: #1e3a8a; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #93c5fd; font-family: 'Calibri', 'Aptos', sans-serif;">
                    NET APPROVED LIABILITIES: ₹{{ number_format((float)$totalNetApproved, 2) }}
                </td>
                <td colspan="4" bgcolor="#f0fdf4" style="background-color: #f0fdf4; color: #166534; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #86efac; font-family: 'Calibri', 'Aptos', sans-serif;">
                    VERIFICATION SIGN-OFFS: {{ $raBills->whereNotNull('verified_date')->count() }} / {{ $raBills->count() }} Completed
                </td>
            </tr>
            <tr height="14" style="height: 14pt;">
                <td colspan="13" style="border: none;"></td>
            </tr>
            <tr height="46" style="height: 46pt;">
                <th colspan="13" bgcolor="#2a2415" style="background-color: #2a2415; color: #f3e5ab; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #a38c29; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="5" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. CONTRACTOR & RA BILL IDENTIFICATION</th>
                <th colspan="4" bgcolor="#8a7522" style="background-color: #8a7522; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. INWARD FINANCIAL CLAIMS & ENGINEER DEDUCTIONS</th>
                <th colspan="4" bgcolor="#065f46" style="background-color: #065f46; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #044e39; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. SITE ENGINEER VERIFICATION & AUDIT SIGN-OFF</th>
            </tr>
            <tr height="40" style="height: 40pt;">
                <th width="55" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 40pt;">SL NO</th>
                <th width="105" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 80pt;">RA BILL NO</th>
                <th width="230" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 175pt;">CONTRACTOR NAME</th>
                <th width="200" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 150pt;">SITE PROJECT</th>
                <th width="125" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">SUBMIT DATE</th>
                <th width="155" bgcolor="#8a7522" style="background-color: #8a7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">RA BILL AMOUNT (₹)</th>
                <th width="145" bgcolor="#8a7522" style="background-color: #8a7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">ADDITIONAL WORK (₹)</th>
                <th width="145" bgcolor="#8a7522" style="background-color: #8a7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">CORRECTION / DEDUCTION (₹)</th>
                <th width="175" bgcolor="#8a7522" style="background-color: #8a7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 135pt;">AFTER CORRECTION (NET PAYABLE ₹)</th>
                <th width="125" bgcolor="#065f46" style="background-color: #065f46; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #044e39; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">DUE DATE</th>
                <th width="125" bgcolor="#065f46" style="background-color: #065f46; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #044e39; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">VERIFIED DATE</th>
                <th width="190" bgcolor="#065f46" style="background-color: #065f46; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #044e39; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 145pt;">VERIFYING ENGINEER</th>
                <th width="115" bgcolor="#065f46" style="background-color: #065f46; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #044e39; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 85pt;">SIGN-OFF STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $goldAdditionalSum = 0;
            @endphp
            @foreach($raBills as $bill)
                @php
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
                    $grossAmt = (float)$bill->gross_amount;
                    $addAmt = (float)$bill->additional_amount;
                    $goldAdditionalSum += $addAmt;
                    $corrAmt = (float)$bill->correction_amount;
                    $netAmt = (float)$bill->net_approved_amount;
                    $subDate = $bill->submit_date ? $bill->submit_date->format('Y-m-d') : '';
                    $dueDate = $bill->due_date ? $bill->due_date->format('Y-m-d') : '';
                    $verDate = $bill->verified_date ? $bill->verified_date->format('Y-m-d') : '';
                    
                    $corrStyle = $corrAmt > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    $statusStyle = $bill->verified_date ? 'background-color: #dcfce7; color: #166534; font-weight: bold;' : 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                @endphp
                <tr height="26" style="height: 26pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->ra_bill_number }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor')) }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->project->name ?? 'Site Project') }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $subDate }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $grossAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $addAmt > 0 ? $addAmt : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $corrStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $corrAmt > 0 ? ('-' . $corrAmt) : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #eff6ff; color: #1e3a8a; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $netAmt }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $dueDate }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $verDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->engineer_name ?: ($bill->verified_date ? 'Site Engineer' : '—') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $statusStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->verified_date ? 'VERIFIED' : 'SUBMITTED' }}</td>
                </tr>
            @endforeach
            <tr height="32" style="height: 32pt; font-weight: bold; color: #ffffff;">
                <td colspan="5" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL REGISTER SUMMARY</td>
                <td data-format="currency" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalGross }}</td>
                <td data-format="currency" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$goldAdditionalSum }}</td>
                <td data-format="currency" bgcolor="#1e293b" style="background-color: #fee2e2; color: #991b1b; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)-$totalCorrections }}</td>
                <td data-format="currency" bgcolor="#1e293b" style="background-color: #1e293b; color: #38bdf8; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalNetApproved }}</td>
                <td colspan="4" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            </tr>
        </tbody>
    </table>

    <!-- ── EXCEL DESIGN OPTION 2: CLASSIC SALES REPORT MULTI-COLOR THEME ── -->
    <table id="raBillsExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="55" style="width: 40pt;" />
            <col width="105" style="width: 80pt;" />
            <col width="230" style="width: 175pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="155" style="width: 115pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="175" style="width: 135pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="190" style="width: 145pt;" />
            <col width="115" style="width: 85pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="13" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="5" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. CONTRACTOR & BILL INFORMATION</th>
                <th colspan="4" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. FINANCIAL CLAIMS & ENGINEER DEDUCTIONS</th>
                <th colspan="4" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. ENGINEER VERIFICATION & AUDIT SIGN-OFF</th>
            </tr>
            <tr height="40" style="height: 40pt;">
                <th width="55" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 40pt;">SL NO</th>
                <th width="105" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 80pt;">RA BILL NO</th>
                <th width="230" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 175pt;">CONTRACTOR NAME</th>
                <th width="200" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 150pt;">SITE PROJECT</th>
                <th width="125" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">SUBMIT DATE</th>
                <th width="155" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">RA BILL AMOUNT (₹)</th>
                <th width="145" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">ADDITIONAL WORK (₹)</th>
                <th width="145" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">CORRECTION / DEDUCTION (₹)</th>
                <th width="175" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 135pt;">AFTER CORRECTION (NET PAYABLE ₹)</th>
                <th width="125" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">DUE DATE</th>
                <th width="125" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">VERIFIED DATE</th>
                <th width="190" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 145pt;">VERIFYING ENGINEER</th>
                <th width="115" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 85pt;">SIGN-OFF STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAdditionalSum = 0;
            @endphp
            @foreach($raBills as $bill)
                @php
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
                    $grossAmt = (float)$bill->gross_amount;
                    $addAmt = (float)$bill->additional_amount;
                    $totalAdditionalSum += $addAmt;
                    $corrAmt = (float)$bill->correction_amount;
                    $netAmt = (float)$bill->net_approved_amount;
                    $subDate = $bill->submit_date ? $bill->submit_date->format('Y-m-d') : '';
                    $dueDate = $bill->due_date ? $bill->due_date->format('Y-m-d') : '';
                    $verDate = $bill->verified_date ? $bill->verified_date->format('Y-m-d') : '';
                    
                    $corrStyle = $corrAmt > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    $statusStyle = $bill->verified_date ? 'background-color: #dcfce7; color: #166534; font-weight: bold;' : 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                @endphp
                <tr height="26" style="height: 26pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->ra_bill_number }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor')) }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->project->name ?? 'Site Project') }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $subDate }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $grossAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $addAmt > 0 ? $addAmt : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $corrStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $corrAmt > 0 ? ('-' . $corrAmt) : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #eff6ff; color: #1e3a8a; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $netAmt }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $dueDate }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $verDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->engineer_name ?: ($bill->verified_date ? 'Site Engineer' : '—') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $statusStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->verified_date ? 'VERIFIED' : 'SUBMITTED' }}</td>
                </tr>
            @endforeach
            <tr height="30" style="height: 30pt; font-weight: bold; color: #ffffff;">
                <td colspan="5" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                <td data-format="currency" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalGross }}</td>
                <td data-format="currency" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalAdditionalSum }}</td>
                <td data-format="currency" bgcolor="#17365D" style="background-color: #fee2e2; color: #991b1b; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)-$totalCorrections }}</td>
                <td data-format="currency" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 10pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalNetApproved }}</td>
                <td colspan="4" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
@endsection
