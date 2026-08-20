@extends('layouts.erp')

@section('title', 'RA Bill Verification & Sign-off')

@section('content')
<div x-data="raBillVerification()" class="p-6 space-y-6 bg-slate-50 min-h-screen">

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

        <div class="flex items-center gap-3">
            <button type="button" @click="addModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span> New RA Progress Bill</span>
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

    <!-- Executive KPI Metrics Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-slate-800 shadow-xs">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">TOTAL RA CLAIMED</span>
            <div class="text-xl font-mono font-black text-slate-900 mt-1">₹{{ number_format((float) $totalGross, 2) }}</div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">{{ $raBills->count() }} Inward RA Progress Bills</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-amber-500 shadow-xs">
            <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">ENGINEER DEDUCTIONS</span>
            <div class="text-xl font-mono font-black text-amber-700 mt-1">-₹{{ number_format((float) $totalCorrections, 2) }}</div>
            <div class="text-[10px] text-amber-600 font-semibold mt-1">Total Corrections Applied</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-500 shadow-xs">
            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">NET APPROVED LIABILITIES</span>
            <div class="text-xl font-mono font-black text-blue-900 mt-1">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
            <div class="text-[10px] text-blue-600 font-semibold mt-1">Verified Payable Claimed</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">VERIFICATION SIGN-OFFS</span>
            <div class="text-xl font-mono font-black text-emerald-800 mt-1">{{ $raBills->whereNotNull('verified_date')->count() }} / {{ $raBills->count() }}</div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-1">Completed Engineer Sign-Offs</div>
        </div>
    </div>

    <!-- Excel-Matched RA Progress Bills Register Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">RA Progress Bills & Verification Sign-Off Register</span>
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
                        <th class="px-3 py-3 text-left w-[85px]">RA BILL NO</th>
                        <th class="px-3 py-3 text-left w-[180px]">CONTRACTOR / PROJECT / UNIT</th>
                        <th class="px-3 py-3 text-left w-[120px]">SUBMIT / VERIFIED</th>
                        <th class="px-3 py-3 text-left w-[110px]">RA BILL AMOUNT</th>
                        <th class="px-3 py-3 text-left w-[110px]">25% ADDITIONAL</th>
                        <th class="px-3 py-3 text-left w-[100px]">CORRECTION</th>
                        <th class="px-3 py-3 text-left bg-[#8a7522]/40 w-[110px]">AFTER CORRECTION</th>
                        <th class="px-3 py-3 text-left w-[110px]">DUE DATE</th>
                        <th class="px-3 py-3 text-left w-[90px]">STATUS</th>
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
                                @if($bill->unit_name || $bill->unit)
                                    <div class="mt-0.5">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 bg-amber-100/90 text-amber-950 border border-amber-300/70 rounded text-[9px] font-black uppercase tracking-wider whitespace-nowrap shadow-2xs">
                                            <span>Unit: {{ $bill->unit_name ?: ($bill->unit->door_no ?? '') }}</span>
                                        </span>
                                    </div>
                                @endif
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

                            <td class="px-3 py-3 text-left font-mono font-bold text-slate-900 align-middle">
                                ₹{{ number_format((float) $bill->gross_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-left font-mono font-bold text-slate-700 align-middle">
                                {{ (float)$bill->additional_amount > 0 ? '₹' . number_format((float)$bill->additional_amount, 2) : '—' }}
                            </td>

                            <td class="px-3 py-3 text-left font-mono text-amber-700 font-bold align-middle">
                                {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                            </td>

                            <td class="px-3 py-3 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-left font-mono align-middle">
                                <div class="text-slate-700 font-bold text-[10.5px]">
                                    {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                                </div>
                            </td>

                            <td class="px-3 py-3 text-left whitespace-nowrap align-middle">
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
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 {{ $errors->has('additional_amount') ? 'text-rose-600' : '' }}">25% ADDITIONAL (₹)</label>
                        <input type="number" step="0.01" name="additional_amount" value="{{ old('additional_amount') }}" placeholder="0.00"
                               class="w-full px-3.5 py-2.5 rounded-xl text-sm font-mono font-bold focus:outline-none transition-all {{ $errors->has('additional_amount') ? 'bg-rose-50 border-2 border-rose-500 text-rose-900 focus:ring-2 focus:ring-rose-500 ring-2 ring-rose-200' : 'bg-slate-50 border border-slate-200 text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29]' }}">
                        @error('additional_amount')
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
                        <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">25% ADDITIONAL</span>
                        <span class="text-xs font-mono font-black text-slate-700" x-text="selectedBill ? '₹' + numberFormat(selectedBill.additional_amount) : ''"></span>
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
