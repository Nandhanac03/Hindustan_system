<x-erp-layout title="Loan Repayment Schedule" headerTitle="Loan Repayment Schedule Manager">

<style>
    /* Suppress automatically injected amount-in-words elements in modals */
    [data-no-words="true"] .amount-in-words-label,
    .pay-modal-box .amount-in-words-label {
        display: none !important;
    }
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="scheduleApp()">
    {{-- Loan summary card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6 p-6">
        <div class="space-y-1.5">
            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <a href="{{ route('loans.index') }}" class="hover:text-primary transition">Bank Loans</a>
                <span>/</span>
                <span class="text-primary">Repayment Schedule</span>
            </div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase" x-text="'Loan A/C: ' + loan.loan_account_no"></h1>
            <p class="text-xs text-slate-500 font-medium">Lending Bank: <span class="font-bold text-slate-700" x-text="loan.lender_name"></span> | Project: <span class="font-bold text-slate-700">{{ $loan->project->name ?? '—' }}</span></p>
        </div>

        <div class="flex flex-wrap gap-3">
            @if($loan->status === 'Active')
                <button @click="openPrepayModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Prepayment & Reschedule
                </button>
            @endif
            <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200 uppercase tracking-wide">
                &larr; Back Master
            </a>
        </div>
    </div>

    {{-- Loan metrics cards (matching loans index cards design) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Principal Amount --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/40 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Principal Amount</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/40 group-hover:text-[#8a7522] group-hover:bg-[#a38c29]/5">Disbursed</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">₹{{ number_format((float)$loan->principal_amount, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total loan amount sanctioned.</p>
            </div>
        </div>

        {{-- Card 2: Outstanding Balance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Outstanding Balance</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:text-rose-700 group-hover:bg-rose-50/50">To Pay</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight block group-hover:text-rose-600 transition-colors duration-300">₹{{ number_format((float)$loan->outstanding_balance, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Remaining principal balance.</p>
            </div>
        </div>

        {{-- Card 3: Interest Rate --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Interest Rate</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:text-emerald-700 group-hover:bg-emerald-50/50">P.A</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">{{ number_format((float)$loan->interest_rate, 2) }}%</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Per annum interest rate.</p>
            </div>
        </div>

        {{-- Card 4: Tenure / Type --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Tenure / Type</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:text-amber-700 group-hover:bg-amber-50/50 whitespace-nowrap">{{ strtoupper(str_replace('_', ' ', $loan->schedule_type)) }}</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">{{ $loan->tenure_months }} <span class="text-base font-bold font-sans">Mo</span></span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total loan duration.</p>
            </div>
        </div>
    </div>

    {{-- Repayment schedule table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Repayment Installment Ledger</h2>
            <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Paid</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Partial</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Due</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-center border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white text-center font-bold uppercase tracking-wider text-[10px] border-b border-[#8a7522]">
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">INST NO</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">DUE DATE</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">PRINCIPAL COMPONENT</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">INTEREST COMPONENT</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">EMI AMOUNT</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">AMOUNT PAID</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">PAID DATE</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">STATUS</th>
                        <th class="px-4 py-3 border border-[#8a7522]/50 font-black text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    @forelse($loan->emiSchedules as $inst)
                        @php
                            $amtPaid   = (float)$inst->amount_paid;
                            $emiAmount = (float)$inst->emi_amount;
                            $balance   = max(0, $emiAmount - $amtPaid);
                            $isPartial = $inst->status !== 'Paid' && $amtPaid > 0;
                            $dueDate   = $inst->due_date ? \Carbon\Carbon::parse($inst->due_date) : null;
                            $isOverdue = $dueDate && $inst->status !== 'Paid' && $dueDate->lt(now()->startOfDay());
                            $isDueThisMonth = $dueDate && $inst->status !== 'Paid' && $dueDate->between(now()->startOfMonth(), now()->endOfMonth());
                            $isUrgent  = ($isOverdue || $isDueThisMonth) && $loan->status === 'Active';
                        @endphp
                        @php $firstDueMarked = $firstDueMarked ?? false; @endphp
                        <tr id="{{ (!$firstDueMarked && $isUrgent) ? 'first-due-installment' : '' }}"
                            class="hover:bg-slate-50/50 transition-colors text-xs font-semibold text-slate-700 {{ $inst->status === 'Paid' ? 'bg-emerald-50/30' : ($isUrgent ? 'bg-rose-50/60' : ($isPartial ? 'bg-amber-50/30' : '')) }}"
                            style="{{ (!$firstDueMarked && $isUrgent) ? 'scroll-margin-top: 100px;' : '' }}">
                        @php if (!$firstDueMarked && $isUrgent) { $firstDueMarked = true; } @endphp
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $inst->installment_no }}</td>
                            <td class="px-4 py-3.5 border {{ $isUrgent ? 'text-rose-700 font-extrabold' : 'text-slate-650' }}">{{ $inst->due_date ? \Carbon\Carbon::parse($inst->due_date)->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-600">₹{{ number_format((float)$inst->principal_component, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-600">₹{{ number_format((float)$inst->interest_component, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono {{ $isUrgent ? 'text-rose-600 font-extrabold text-[13px]' : 'text-slate-900 font-bold' }}">₹{{ number_format($emiAmount, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono font-bold">
                                @if($isPartial)
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-emerald-700">₹{{ number_format($amtPaid, 2) }}</span>
                                        <span class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">Paid</span>
                                        <span class="text-rose-600">₹{{ number_format($balance, 2) }}</span>
                                        <span class="text-[9px] font-bold text-rose-400 uppercase tracking-wider">Balance Due</span>
                                    </div>
                                @else
                                    <span class="{{ $inst->status === 'Paid' ? 'text-emerald-800' : 'text-slate-400' }}">₹{{ number_format($amtPaid, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-slate-500">{{ $inst->paid_date ? \Carbon\Carbon::parse($inst->paid_date)->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-3.5 border">
                                @if($inst->status === 'Paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border bg-emerald-50 border-emerald-100 text-emerald-700">Paid</span>
                                @elseif($isOverdue)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border bg-rose-200 border-rose-300 text-rose-800 animate-pulse">⚠️ Overdue Urgent</span>
                                @elseif($isDueThisMonth)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border bg-rose-100 border-rose-200 text-rose-700">🔥 Due This Month</span>
                                @elseif($isPartial)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border bg-amber-50 border-amber-200 text-amber-700">Partial</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border bg-slate-50 border-slate-200 text-slate-500">Due</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                @if($inst->status !== 'Paid' && $loan->status === 'Active')
                                    <button @click="openPayModal({{ $inst }})" class="px-3 py-1 {{ $isPartial ? 'bg-amber-500 hover:bg-amber-600' : 'bg-[#a38c29] hover:bg-[#8e7a23]' }} text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition shadow-sm shadow-[#a38c29]/10">
                                        {{ $isPartial ? 'Pay Balance' : 'Pay Installment' }}
                                    </button>
                                    @if($isPartial)
                                        <div class="text-[9px] text-amber-600 font-bold mt-1">₹{{ number_format($balance, 2) }} pending</div>
                                    @endif
                                @else
                                    <span class="text-emerald-650 text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Cleared</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-slate-400 italic">No schedules generated.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Prepayment Logs list --}}
    @if($loan->prepayments->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Prepayment & Rescheduling History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[10px] font-bold uppercase tracking-wider border-b border-[#8a7522] text-center">
                            <th class="px-4 py-2.5 border border-[#8a7522]/50 font-black">DATE</th>
                            <th class="px-4 py-2.5 border border-[#8a7522]/50 font-black">PREPAYMENT AMOUNT</th>
                            <th class="px-4 py-2.5 border border-[#8a7522]/50 font-black">PREVIOUS OUTSTANDING</th>
                            <th class="px-4 py-2.5 border border-[#8a7522]/50 font-black">NEW OUTSTANDING</th>
                            <th class="px-4 py-2.5 border border-[#8a7522]/50 font-black">RESCHEDULE MODE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-center font-semibold text-slate-700">
                        @foreach($loan->prepayments as $log)
                            <tr>
                                <td class="px-4 py-3 border font-mono text-slate-600">{{ $log->prepayment_date ? \Carbon\Carbon::parse($log->prepayment_date)->format('d M Y') : '—' }}</td>
                                <td class="px-4 py-3 border font-mono text-emerald-800">₹{{ number_format((float)$log->prepayment_amount, 2) }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-550">₹{{ number_format((float)$log->previous_outstanding, 2) }}</td>
                                <td class="px-4 py-3 border font-mono text-rose-700 font-bold">₹{{ number_format((float)$log->new_outstanding, 2) }}</td>
                                <td class="px-4 py-3 border text-slate-600 uppercase text-[10px]">{{ $log->reschedule_option === 'reduce_emi' ? 'Reduce EMI' : 'Reduce Tenure' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Pay EMI Modal --}}
    <div x-show="payModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="payModalOpen = false"></div>
        <div class="relative w-full max-w-4xl bg-slate-950 rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up border border-slate-800/80 my-auto max-h-[96vh] flex flex-col" @click.away="payModalOpen = false">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-6 py-3.5 text-white flex items-center justify-between relative overflow-hidden border-b border-slate-800/80 shrink-0">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/20 text-[#f3e5ab] flex items-center justify-center text-sm font-black shadow-inner border border-[#a38c29]/30">
                        ₹
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[8px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40">LOAN REPAYMENT</span>
                            <span class="text-[9px] text-slate-400 font-semibold" x-text="'Installment #' + (activeInst?.installment_no || '')"></span>
                        </div>
                        <h3 class="font-black text-sm uppercase tracking-wider text-white mt-0.5" x-text="'Record Payment: Installment #' + (activeInst?.installment_no || '')"></h3>
                    </div>
                </div>
                <button type="button" @click="payModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer relative z-10" title="Close Modal">✕</button>
            </div>

            {{-- Modal Body --}}
            <div class="bg-white rounded-b-2xl sm:rounded-b-3xl p-4 sm:p-5 space-y-3 overflow-y-auto" x-data="{
                bankOpen: false,
                bankSearch: '',
                get filteredAccounts() {
                    const list = companyBankAccounts || [];
                    if (!this.bankSearch) return list;
                    const q = this.bankSearch.toLowerCase();
                    return list.filter(a => 
                        (a.bank_name && a.bank_name.toLowerCase().includes(q)) ||
                        (a.account_name && a.account_name.toLowerCase().includes(q)) ||
                        (a.account_number && a.account_number.toLowerCase().includes(q)) ||
                        (a.branch_name && a.branch_name.toLowerCase().includes(q))
                    );
                },
                get selectedAccount() {
                    return (companyBankAccounts || []).find(a => a.id == payForm.bank_account_id) || null;
                },
                get totalOutflow() {
                    return Number(payForm.amount || 0) + Number(payForm.other_charges || 0);
                }
            }">
                <form @submit.prevent="submitPayForm" novalidate data-no-words="true">
                    {{-- 1. Top Installment & Loan Overview Strip --}}
                    <div class="bg-slate-50/90 rounded-xl p-3 border border-slate-200/80 shadow-2xs mb-3">
                        <div class="flex items-center justify-between pb-1.5 mb-2 border-b border-slate-200/70">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-600">Installment & Loan Breakdown</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#a38c29] font-mono text-[10px] font-extrabold border border-[#a38c29]/30 shadow-2xs" x-text="activeInst?.due_date ? ('Due Date: ' + new Date(activeInst.due_date).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'})) : ''"></span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Principal Amount</span>
                                <span class="text-xs font-mono font-black text-slate-800 block" x-text="'₹' + Number(payForm.principal_amount || activeInst?.principal_component || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Interest Component</span>
                                <span class="text-xs font-mono font-black text-slate-800 block" x-text="'₹' + Number(payForm.interest_amount || activeInst?.interest_component || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Scheduled EMI</span>
                                <span class="text-xs font-mono font-black text-slate-900 block" x-text="'₹' + Number(payForm.amount || activeInst?.emi_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payable Balance</span>
                                <span class="text-sm font-black font-mono text-[#a38c29] block" x-text="'₹' + Number(payForm.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Payment Details & Actions Form Grid --}}
                    <div>
                        <div class="flex items-center gap-1.5 mb-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-wider">
                                Payment Details & Bank Selection
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-xs items-start">
                            {{-- ROW 1 --}}
                            {{-- Principal Amount --}}
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.principal_amount ? 'text-rose-600' : 'text-slate-700'">
                                    Principal Amount (₹) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="0.01" x-model="payForm.principal_amount" @input="onPrincipalAmountChange()" data-no-words="true"
                                       :class="payErrors.principal_amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                       class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs">
                                <span x-show="payErrors.principal_amount" x-text="payErrors.principal_amount" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                            </div>

                            {{-- Interest Rate & Interest Amount Grid --}}
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.interest_rate ? 'text-rose-600' : 'text-slate-700'">
                                        Interest Rate (% P.A.) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" step="0.01" x-model="payForm.interest_rate" @input="onInterestRateChange()" data-no-words="true"
                                           :class="payErrors.interest_rate ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="payErrors.interest_rate" x-text="payErrors.interest_rate" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>

                                <div>
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.interest_amount ? 'text-rose-600' : 'text-slate-700'">
                                        Interest Amount (₹) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" step="0.01" x-model="payForm.interest_amount" @input="onInterestAmountChange()" data-no-words="true"
                                           :class="payErrors.interest_amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="payErrors.interest_amount" x-text="payErrors.interest_amount" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>
                            </div>

                            {{-- ROW 2 --}}
                            {{-- Payment Amount --}}
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.amount ? 'text-rose-600' : 'text-slate-700'">
                                    Payment Amount (₹) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="0.01" x-model="payForm.amount" readonly data-no-words="true"
                                       :class="payErrors.amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : 'border-slate-200/80 bg-slate-100/90'"
                                       class="w-full h-9 px-3 border rounded-xl text-xs font-extrabold text-slate-800 cursor-not-allowed">
                                <span x-show="payErrors.amount" x-text="payErrors.amount" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>

                                {{-- Amount in Words (No background, Theme Golden Font) --}}
                                <div class="mt-1 text-[10.5px] font-semibold text-[#8a7522] italic leading-tight" 
                                     x-show="payForm.amount > 0" 
                                     x-text="numberToWords(payForm.amount)"></div>
                            </div>

                            {{-- Transaction / Cheque / UTR No. --}}
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.reference_no ? 'text-rose-600' : 'text-slate-700'">
                                    Transaction / Cheque / UTR No. <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="payForm.reference_no"
                                       @input="delete payErrors.reference_no"
                                       placeholder="e.g. UTR1087349137 or Cheque Ref"
                                       :class="payErrors.reference_no ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                       class="w-full h-9 px-3 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                                <span x-show="payErrors.reference_no" x-text="payErrors.reference_no" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                            </div>

                            {{-- ROW 3 (Payment Date & Payment Mode aligned) --}}
                            {{-- Payment Date --}}
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.paid_date ? 'text-rose-600' : 'text-slate-700'">
                                    Payment Date <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" x-model="payForm.paid_date"
                                       @input="delete payErrors.paid_date"
                                       :class="payErrors.paid_date ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                       class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs">
                                <span x-show="payErrors.paid_date" x-text="payErrors.paid_date" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                            </div>

                            {{-- Payment Mode --}}
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.payment_mode ? 'text-rose-600' : 'text-slate-700'">
                                    Payment Mode <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select x-model="payForm.payment_mode"
                                            @change="delete payErrors.payment_mode"
                                            :class="payErrors.payment_mode ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white'"
                                            class="w-full h-9 pl-3 pr-8 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition shadow-2xs appearance-none">
                                        <option value="Bank Transfer">Bank Transfer / NEFT / RTGS / IMPS</option>
                                        <option value="Cheque">Cheque Payout</option>
                                        <option value="Direct Debit">Direct Bank Debit (ECS / Auto-debit)</option>
                                        <option value="Cash">Cash Payout</option>
                                        <option value="Online">Online Gateway Payment</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <span x-show="payErrors.payment_mode" x-text="payErrors.payment_mode" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                            </div>

                            {{-- ROW 4 (Paid From & Remarks aligned) --}}
                            {{-- Paid From Company Bank Account (Search & Select) --}}
                            <div class="relative" @click.outside="bankOpen = false">
                                <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="payErrors.bank_account_id ? 'text-rose-600' : 'text-slate-700'">
                                    Paid From (Company Bank Account) <span class="text-rose-500">*</span>
                                </label>
                                
                                <div @click="bankOpen = !bankOpen; if(bankOpen) $nextTick(() => $refs.payBankSearch?.focus())"
                                     :class="payErrors.bank_account_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : 'border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white focus:bg-white'"
                                     class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs">
                                     <template x-if="selectedAccount">
                                         <div class="flex items-center gap-2 truncate">
                                             <span class="px-1.5 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[9px]" x-text="selectedAccount.bank_name"></span>
                                             <span class="font-bold text-slate-800 truncate" x-text="selectedAccount.account_name || selectedAccount.bank_name"></span>
                                             <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (selectedAccount.account_number || '—') + ')'"></span>
                                         </div>
                                     </template>
                                     <template x-if="!selectedAccount">
                                         <span class="text-slate-400 font-normal">Select Company Bank Account...</span>
                                     </template>
                                     <svg class="w-3.5 h-3.5 text-slate-400 transition-transform shrink-0" :class="bankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <span x-show="payErrors.bank_account_id" x-text="payErrors.bank_account_id" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>

                                {{-- Selected Bank Balance in Words Only --}}
                                <div class="mt-1.5 flex items-baseline justify-between gap-2 text-[11px]" x-show="selectedAccount">
                                    <span class="text-slate-500 font-medium shrink-0">Selected Bank Balance:</span>
                                    <span class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                          x-text="numberToWords(selectedAccount?.current_balance || 0)"></span>
                                </div>

                                <!-- Dropdown Search Menu -->
                                <div x-show="bankOpen" x-transition class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-56 flex flex-col" style="display: none;">
                                    <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0">
                                        <div class="relative">
                                            <input type="text" x-ref="payBankSearch" x-model="bankSearch" placeholder="Search bank name, account no, branch..." class="w-full pl-7 pr-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                            <svg class="w-3 h-3 text-slate-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                    </div>
                                    <div class="overflow-y-auto divide-y divide-slate-100">
                                        <template x-for="acc in filteredAccounts" :key="acc.id">
                                            <div @click="payForm.bank_account_id = acc.id; delete payErrors.bank_account_id; bankOpen = false; bankSearch = ''"
                                                 class="px-3 py-2 hover:bg-[#a38c29]/5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                                 :class="payForm.bank_account_id == acc.id ? 'bg-[#a38c29]/10 font-bold' : ''">
                                                <div class="flex flex-col">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                        <span class="text-slate-500 font-medium" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                                    </div>
                                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                                </div>
                                                <div class="text-right font-mono">
                                                    <div class="text-[8px] text-slate-400 uppercase font-sans">Current Balance</div>
                                                    <div class="font-bold text-slate-800 text-[11px]" x-text="'₹ ' + Number(acc.current_balance || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="filteredAccounts.length === 0">
                                            <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Remarks / Internal Notes --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wide text-[9px]">
                                    Remarks / Internal Notes
                                </label>
                                <input type="text" x-model="payForm.remarks" placeholder="Optional internal payout notes..." class="w-full h-9 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-medium text-slate-800 focus:outline-none transition shadow-2xs">
                            </div>
                        </div>

                        {{-- 3. Bank Details & Balance Summary Row (2 Horizontal Boxes in 1 Line) --}}
                        <div x-show="selectedAccount" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3" style="display: none;" x-transition>
                            {{-- Left Box: Bank Details Card --}}
                            <div class="rounded-xl p-3 bg-slate-50 border border-slate-200/80 shadow-2xs flex flex-col justify-center">
                                <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[10px]">
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Bank</span>
                                        <span class="font-bold text-slate-800 truncate block" x-text="selectedAccount?.bank_name || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Account No</span>
                                        <span class="font-mono font-bold text-slate-800 block" x-text="selectedAccount?.account_number || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Account Name</span>
                                        <span class="font-semibold text-slate-700 truncate block" x-text="selectedAccount?.account_name || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Branch</span>
                                        <span class="font-semibold text-slate-700 truncate block" x-text="selectedAccount?.branch_name || '—'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Box: Dynamic Bank Balance Summary Card --}}
                            <div class="bg-slate-50/90 border border-slate-200/90 rounded-xl p-3 space-y-1 shadow-2xs text-[11px] flex flex-col justify-center">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-bold text-slate-600">
                                        Selected Bank Account Balance (<span x-text="selectedAccount?.bank_name || 'Bank'"></span>)
                                    </span>
                                    <span class="font-mono font-extrabold text-blue-600 text-xs shrink-0" x-text="'₹' + Number(selectedAccount?.current_balance || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹0.00</span>
                                </div>
                                
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-bold text-slate-600">EMI Payment Amount</span>
                                    <span class="font-mono font-extrabold text-rose-600 text-xs shrink-0" x-text="'- ₹' + Number(totalOutflow).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">- ₹0.00</span>
                                </div>

                                <div class="pt-1.5 border-t border-slate-200/80 flex items-center justify-between gap-3">
                                    <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[10px] pr-2">Bank Balance After Payment</span>
                                    <span class="font-mono font-black text-sm shrink-0" 
                                          :class="(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)) < 0 ? 'text-rose-600' : 'text-slate-900'"
                                          x-text="'₹' + Number(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹0.00</span>
                                </div>
                                <template x-if="(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)) < 0">
                                    <div class="text-[9px] text-rose-600 font-bold flex items-center gap-1 mt-0.5 bg-rose-100/60 p-1 rounded-md">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Warning: EMI amount exceeds available bank balance.</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Footer Action Bar --}}
                    <div class="mt-4 pt-3 flex items-center justify-between border-t border-slate-100">
                        <button type="button" @click="payModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs uppercase tracking-wider transition cursor-pointer">← Back</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Prepayment & Reschedule Modal --}}
    <div x-show="prepayModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="prepayModalOpen = false"></div>
        <div class="relative w-full max-w-4xl bg-slate-950 rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up border border-slate-800/80 my-auto max-h-[96vh] flex flex-col" @click.away="prepayModalOpen = false">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-6 py-3.5 text-white flex items-center justify-between relative overflow-hidden border-b border-slate-800/80 shrink-0">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/20 text-[#f3e5ab] flex items-center justify-center text-sm font-black shadow-inner border border-[#a38c29]/30">
                        ₹
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[8px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40">PRINCIPAL PREPAYMENT</span>
                            <span class="text-[9px] text-slate-400 font-semibold">Rescheduling Console</span>
                        </div>
                        <h3 class="font-black text-sm uppercase tracking-wider text-white mt-0.5">Lump Sum Prepayment & Rescheduling</h3>
                    </div>
                </div>
                <button type="button" @click="prepayModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer relative z-10" title="Close Modal">✕</button>
            </div>

            {{-- Modal Body --}}
            <div class="bg-white rounded-b-2xl sm:rounded-b-3xl p-4 sm:p-5 space-y-3 overflow-y-auto" x-data="{
                prepayBankOpen: false,
                prepayBankSearch: '',
                get filteredAccounts() {
                    const list = companyBankAccounts || [];
                    if (!this.prepayBankSearch) return list;
                    const q = this.prepayBankSearch.toLowerCase();
                    return list.filter(a => 
                        (a.bank_name && a.bank_name.toLowerCase().includes(q)) ||
                        (a.account_name && a.account_name.toLowerCase().includes(q)) ||
                        (a.account_number && a.account_number.toLowerCase().includes(q)) ||
                        (a.branch_name && a.branch_name.toLowerCase().includes(q))
                    );
                },
                get selectedAccount() {
                    return (companyBankAccounts || []).find(a => a.id == prepayForm.bank_account_id) || null;
                },
                get totalOutflow() {
                    return Number(prepayForm.amount || 0) + Number(prepayForm.prepayment_charges || 0);
                }
            }">
                <form @submit.prevent="submitPrepayForm" novalidate>
                    {{-- 1. Top Loan Overview Strip --}}
                    <div class="bg-slate-50/90 rounded-xl p-3 border border-slate-200/80 shadow-2xs mb-3">
                        <div class="flex items-center justify-between pb-1.5 mb-2 border-b border-slate-200/70">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-600">Active Loan Outstanding Summary</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#a38c29] font-mono text-[10px] font-extrabold border border-[#a38c29]/30 shadow-2xs" x-text="loan ? ('A/C: ' + loan.loan_account_no) : ''"></span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Sanctioned Principal</span>
                                <span class="text-xs font-mono font-black text-slate-800 block" x-text="loan ? '₹' + Number(loan.principal_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Interest Rate</span>
                                <span class="text-xs font-mono font-black text-slate-800 block" x-text="loan ? Number(loan.interest_rate).toFixed(2) + '% p.a.' : '—'"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Current Outstanding</span>
                                <span class="text-sm font-black font-mono text-rose-700 block" x-text="loan ? '₹' + Number(loan.outstanding_balance).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '₹0.00'"></span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Net Payout</span>
                                <span class="text-sm font-black font-mono text-[#a38c29] block" x-text="'₹' + Number(totalOutflow).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Prepayment Details & Bank Selection --}}
                    <div>
                        <div class="flex items-center gap-1.5 mb-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-wider">
                                Prepayment Parameters & Reference
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2.5 text-xs">
                            {{-- Left Column --}}
                            <div class="space-y-2.5">
                                {{-- Prepayment Amount --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block font-bold uppercase tracking-wide text-[9px]" :class="prepayErrors.amount ? 'text-rose-600' : 'text-slate-700'">
                                            Prepayment Amount (₹) <span class="text-rose-500">*</span>
                                        </label>
                                        <button type="button" 
                                                @click="prepayForm.amount = loan?.outstanding_balance || 0; delete prepayErrors.amount" 
                                                class="text-[9px] font-bold text-[#8a7522] hover:text-[#a38c29] bg-[#a38c29]/10 hover:bg-[#a38c29]/20 px-1.5 py-0.5 rounded transition cursor-pointer"
                                                title="Click to fill full outstanding amount">
                                            Max: ₹<span x-text="loan ? Number(loan.outstanding_balance).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '0.00'"></span>
                                        </button>
                                    </div>
                                    <input type="number" step="any" min="0.01" :max="loan?.outstanding_balance || 0" x-model="prepayForm.amount"
                                           @input="delete prepayErrors.amount; const val = parseFloat(prepayForm.amount); const maxVal = parseFloat(loan?.outstanding_balance || 0); if (!isNaN(val) && val > maxVal) { prepayErrors.amount = 'Amount cannot exceed outstanding balance (₹' + Number(maxVal).toLocaleString('en-IN', {minimumFractionDigits: 2}) + ')'; }"
                                           placeholder="0"
                                           :class="prepayErrors.amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 rounded-xl text-xs font-extrabold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="prepayErrors.amount" x-text="prepayErrors.amount" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>

                                {{-- Date of Prepayment --}}
                                <div>
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="prepayErrors.prepayment_date ? 'text-rose-600' : 'text-slate-700'">
                                        Date of Prepayment <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" x-model="prepayForm.prepayment_date"
                                           @input="delete prepayErrors.prepayment_date"
                                           :class="prepayErrors.prepayment_date ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="prepayErrors.prepayment_date" x-text="prepayErrors.prepayment_date" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>

                                {{-- Paid From Company Bank Account (Search & Select) --}}
                                <div class="relative" @click.outside="prepayBankOpen = false">
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="prepayErrors.bank_account_id ? 'text-rose-600' : 'text-slate-700'">
                                        Paid From (Company Bank Account) <span class="text-rose-500">*</span>
                                    </label>
                                    
                                    <div @click="prepayBankOpen = !prepayBankOpen; if(prepayBankOpen) $nextTick(() => $refs.prepayBankSearch?.focus())"
                                         :class="prepayErrors.bank_account_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : 'border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white focus:bg-white'"
                                         class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs">
                                        <template x-if="selectedPrepayAccount">
                                            <div class="flex items-center gap-2 truncate">
                                                <span class="px-1.5 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[9px]" x-text="selectedPrepayAccount.bank_name"></span>
                                                <span class="font-bold text-slate-800 truncate" x-text="selectedPrepayAccount.account_name || selectedPrepayAccount.bank_name"></span>
                                                <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (selectedPrepayAccount.account_number || '—') + ')'"></span>
                                            </div>
                                        </template>
                                        <template x-if="!selectedPrepayAccount">
                                            <span class="text-slate-400 font-normal">Select Company Bank Account...</span>
                                        </template>
                                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform shrink-0" :class="prepayBankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <span x-show="prepayErrors.bank_account_id" x-text="prepayErrors.bank_account_id" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>

                                    {{-- Selected Bank Balance in Words Only --}}
                                    <div class="mt-1.5 flex items-baseline justify-between gap-2 text-[11px]" x-show="selectedPrepayAccount">
                                        <span class="text-slate-500 font-medium shrink-0">Selected Bank Balance:</span>
                                        <span class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                              x-text="numberToWords(selectedPrepayAccount?.current_balance || 0)"></span>
                                    </div>

                                    <!-- Dropdown Search Menu -->
                                    <div x-show="prepayBankOpen" x-transition class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-56 flex flex-col" style="display: none;">
                                        <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0">
                                            <div class="relative">
                                                <input type="text" x-ref="prepayBankSearch" x-model="prepayBankSearch" placeholder="Search bank name, account no, branch..." class="w-full pl-7 pr-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                                <svg class="w-3 h-3 text-slate-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            </div>
                                        </div>
                                        <div class="overflow-y-auto divide-y divide-slate-100">
                                            <template x-for="acc in filteredPrepayAccounts" :key="acc.id">
                                                <div @click="prepayForm.bank_account_id = acc.id; delete prepayErrors.bank_account_id; prepayBankOpen = false; prepayBankSearch = ''"
                                                     class="px-3 py-2 hover:bg-[#a38c29]/5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                                     :class="prepayForm.bank_account_id == acc.id ? 'bg-[#a38c29]/10 font-bold' : ''">
                                                    <div class="flex flex-col">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                            <span class="text-slate-500 font-medium" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                                        </div>
                                                        <div class="text-[9px] text-slate-400 font-mono mt-0.5" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                                    </div>
                                                    <div class="text-right font-mono">
                                                        <div class="text-[8px] text-slate-400 uppercase font-sans">Current Balance</div>
                                                        <div class="font-bold text-slate-800 text-[11px]" x-text="'₹ ' + Number(acc.current_balance || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="filteredPrepayAccounts.length === 0">
                                                <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column --}}
                            <div class="space-y-2.5">
                                {{-- Transaction / UTR No. --}}
                                <div>
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="prepayErrors.reference_no ? 'text-rose-600' : 'text-slate-700'">
                                        Transaction / Cheque / UTR No. <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" x-model="prepayForm.reference_no"
                                           @input="delete prepayErrors.reference_no"
                                           placeholder="e.g. UTR1087349137"
                                           :class="prepayErrors.reference_no ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="prepayErrors.reference_no" x-text="prepayErrors.reference_no" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>

                                {{-- Reschedule Option --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wide text-[9px]">
                                        Rescheduling Mode <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select x-model="prepayForm.reschedule_option" class="w-full h-9 pl-3 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition shadow-2xs appearance-none">
                                            <option value="reduce_emi">Reduce monthly installment (EMI), keep tenure same</option>
                                            <option value="reduce_tenure">Reduce remaining tenure (months), keep EMI same</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Prepayment Charges --}}
                                <div>
                                    <label class="block font-bold mb-1 uppercase tracking-wide text-[9px]" :class="prepayErrors.prepayment_charges ? 'text-rose-600' : 'text-slate-700'">
                                        Prepayment Penalty / Charges (₹)
                                    </label>
                                    <input type="number" step="any" min="0" x-model="prepayForm.prepayment_charges"
                                           @input="delete prepayErrors.prepayment_charges"
                                           placeholder="0"
                                           :class="prepayErrors.prepayment_charges ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                           class="w-full h-9 px-3 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                                    <span x-show="prepayErrors.prepayment_charges" x-text="prepayErrors.prepayment_charges" class="text-[10px] font-bold text-rose-600 mt-1 block" style="display: none;"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Remarks / Notes (Full Width) --}}
                        <div class="mt-2.5">
                            <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wide text-[9px]">
                                Remarks / Reason
                            </label>
                            <input type="text" x-model="prepayForm.remarks" placeholder="Optional notes or reason for prepayment..." class="w-full h-9 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-medium text-slate-800 focus:outline-none transition shadow-2xs">
                        </div>

                        {{-- 3. Bank Details & Balance Summary Row (2 Horizontal Boxes in 1 Line) --}}
                        <div x-show="selectedAccount" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3" style="display: none;" x-transition>
                            {{-- Left Box: Bank Details Card --}}
                            <div class="rounded-xl p-3 bg-slate-50 border border-slate-200/80 shadow-2xs flex flex-col justify-center">
                                <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[10px]">
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Bank</span>
                                        <span class="font-bold text-slate-800 truncate block" x-text="selectedAccount?.bank_name || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Account No</span>
                                        <span class="font-mono font-bold text-slate-800 block" x-text="selectedAccount?.account_number || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Account Name</span>
                                        <span class="font-semibold text-slate-700 truncate block" x-text="selectedAccount?.account_name || '—'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-bold text-[8px] uppercase tracking-wider block">Branch</span>
                                        <span class="font-semibold text-slate-700 truncate block" x-text="selectedAccount?.branch_name || '—'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Box: Dynamic Bank Balance Summary Card --}}
                            <div class="bg-slate-50/90 border border-slate-200/90 rounded-xl p-3 space-y-1 shadow-2xs text-[11px] flex flex-col justify-center">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-bold text-slate-600">
                                        Selected Bank Account Balance (<span x-text="selectedAccount?.bank_name || 'Bank'"></span>)
                                    </span>
                                    <span class="font-mono font-extrabold text-blue-600 text-xs shrink-0" x-text="'₹' + Number(selectedAccount?.current_balance || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹0.00</span>
                                </div>
                                
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-bold text-slate-600">Total Prepayment Outflow</span>
                                    <span class="font-mono font-extrabold text-rose-600 text-xs shrink-0" x-text="'- ₹' + Number(totalOutflow).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">- ₹0.00</span>
                                </div>

                                <div class="pt-1.5 border-t border-slate-200/80 flex items-center justify-between gap-3">
                                    <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[10px] pr-2">Bank Balance After Payment</span>
                                    <span class="font-mono font-black text-sm shrink-0" 
                                          :class="(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)) < 0 ? 'text-rose-600' : 'text-slate-900'"
                                          x-text="'₹' + Number(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹0.00</span>
                                </div>
                                <template x-if="(Number(selectedAccount?.current_balance || 0) - Number(totalOutflow)) < 0">
                                    <div class="text-[9px] text-rose-600 font-bold flex items-center gap-1 mt-0.5 bg-rose-100/60 p-1 rounded-md">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Warning: Prepayment payout exceeds available bank balance.</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Footer Action Bar --}}
                    <div class="mt-4 pt-3 flex items-center justify-between border-t border-slate-100">
                        <button type="button" @click="prepayModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs uppercase tracking-wider transition cursor-pointer">← Back</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Apply & Reschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alert Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>
</div>

<script>
function scheduleApp() {
    return {
        loan: {!! json_encode($loan) !!},
        companyBankAccounts: {!! json_encode($companyBankAccounts ?? []) !!},
        payModalOpen: false,
        prepayModalOpen: false,
        bankOpen: false,
        bankSearch: '',
        prepayBankOpen: false,
        prepayBankSearch: '',
        activeInst: {},
        payErrors: {},
        prepayErrors: {},
        get selectedPayAccount() {
            return (this.companyBankAccounts || []).find(b => b.id == this.payForm.bank_account_id) || null;
        },
        get selectedPrepayAccount() {
            return (this.companyBankAccounts || []).find(b => b.id == this.prepayForm.bank_account_id) || null;
        },
        get filteredPayAccounts() {
            if (!this.bankSearch || !this.bankSearch.trim()) return this.companyBankAccounts || [];
            const q = this.bankSearch.toLowerCase().trim();
            return (this.companyBankAccounts || []).filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
        },
        get filteredPrepayAccounts() {
            if (!this.prepayBankSearch || !this.prepayBankSearch.trim()) return this.companyBankAccounts || [];
            const q = this.prepayBankSearch.toLowerCase().trim();
            return (this.companyBankAccounts || []).filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
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
        originalInterest: 0,
        originalRate: 0,
        payForm: {
            principal_amount: '',
            interest_rate: '',
            interest_amount: '',
            amount: '',
            paid_date: new Date().toISOString().split('T')[0],
            bank_account_id: ({!! json_encode($companyBankAccounts ?? []) !!}[0]?.id) || '',
            payment_mode: 'Bank Transfer',
            reference_no: '',
            remarks: '',
            other_charges: 0
        },
        prepayForm: {
            action_type: 'prepayment',
            amount: '',
            prepayment_date: new Date().toISOString().split('T')[0],
            prepayment_charges: '',
            bank_account_id: ({!! json_encode($companyBankAccounts ?? []) !!}[0]?.id) || '',
            reference_no: '',
            reschedule_option: 'reduce_emi',
            remarks: ''
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 4000);
        },
        openPayModal(inst) {
            this.activeInst = inst;
            this.payErrors = {};
            const principal = Number(inst.principal_component || 0);
            const interest = Number(inst.interest_component || 0);
            const defaultRate = Number(this.loan.interest_rate || 0);

            this.originalPrincipal = principal > 0 ? principal : 1;
            this.originalInterest = interest;
            this.originalRate = defaultRate > 0 ? defaultRate : 1;

            this.payForm.principal_amount = principal.toFixed(2);
            this.payForm.interest_rate = defaultRate.toFixed(2);
            this.payForm.interest_amount = interest.toFixed(2);
            this.payForm.amount = (principal + interest).toFixed(2);
            this.payForm.paid_date = new Date().toISOString().split('T')[0];
            this.payForm.bank_account_id = (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? this.companyBankAccounts[0].id : '';
            this.payForm.reference_no = '';
            this.payForm.remarks = '';
            this.payForm.other_charges = 0;
            this.payModalOpen = true;
        },
        onPrincipalAmountChange() {
            delete this.payErrors.principal_amount;
            delete this.payErrors.amount;

            let principal = parseFloat(this.payForm.principal_amount);
            if (isNaN(principal) || principal < 0) principal = 0;

            let interest = parseFloat(this.payForm.interest_amount);
            if (isNaN(interest) || interest < 0) interest = 0;

            // Interest component is NOT affected by change in principal amount
            this.payForm.amount = (principal + interest).toFixed(2);
        },
        onInterestRateChange() {
            delete this.payErrors.interest_rate;
            delete this.payErrors.interest_amount;
            delete this.payErrors.amount;

            let rate = parseFloat(this.payForm.interest_rate);
            if (isNaN(rate) || rate < 0) rate = 0;

            let newInterest = 0;
            if (this.originalRate > 0 && this.originalInterest > 0) {
                newInterest = (rate / this.originalRate) * this.originalInterest;
            } else if (Number(this.loan.principal_amount) > 0) {
                newInterest = (Number(this.loan.principal_amount) * rate) / 1200;
            } else {
                newInterest = (parseFloat(this.payForm.principal_amount) || 0) * rate / 1200;
            }

            this.payForm.interest_amount = Number(newInterest).toFixed(2);
            let principal = parseFloat(this.payForm.principal_amount) || 0;
            this.payForm.amount = (principal + newInterest).toFixed(2);
        },
        onInterestAmountChange() {
            delete this.payErrors.interest_rate;
            delete this.payErrors.interest_amount;
            delete this.payErrors.amount;

            let interest = parseFloat(this.payForm.interest_amount);
            if (isNaN(interest) || interest < 0) interest = 0;

            let newRate = 0;
            if (this.originalInterest > 0 && this.originalRate > 0) {
                newRate = (interest / this.originalInterest) * this.originalRate;
            } else if (Number(this.loan.principal_amount) > 0) {
                newRate = (interest * 1200) / Number(this.loan.principal_amount);
            } else {
                newRate = (interest * 1200) / (parseFloat(this.payForm.principal_amount) || 1);
            }

            this.payForm.interest_rate = Number(newRate).toFixed(2);
            let principal = parseFloat(this.payForm.principal_amount) || 0;
            this.payForm.amount = (principal + interest).toFixed(2);
        },
        openPrepayModal() {
            this.prepayErrors = {};
            this.prepayForm = {
                action_type: 'prepayment',
                amount: '',
                prepayment_date: new Date().toISOString().split('T')[0],
                prepayment_charges: '',
                bank_account_id: (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? this.companyBankAccounts[0].id : '',
                reference_no: '',
                reschedule_option: 'reduce_emi',
                remarks: ''
            };
            this.prepayModalOpen = true;
        },
        submitPayForm() {
            this.payErrors = {};
            let hasError = false;

            if (this.payForm.principal_amount === '' || isNaN(parseFloat(this.payForm.principal_amount)) || parseFloat(this.payForm.principal_amount) <= 0) {
                this.payErrors.principal_amount = 'Valid principal amount is required';
                hasError = true;
            }
            if (!this.payForm.amount || parseFloat(this.payForm.amount) <= 0) {
                this.payErrors.amount = 'Valid payment amount is required';
                hasError = true;
            }
            if (this.payForm.interest_rate === '' || isNaN(parseFloat(this.payForm.interest_rate)) || parseFloat(this.payForm.interest_rate) < 0) {
                this.payErrors.interest_rate = 'Valid interest rate is required';
                hasError = true;
            }
            if (this.payForm.interest_amount === '' || isNaN(parseFloat(this.payForm.interest_amount)) || parseFloat(this.payForm.interest_amount) < 0) {
                this.payErrors.interest_amount = 'Valid interest amount is required';
                hasError = true;
            }
            if (!this.payForm.paid_date) {
                this.payErrors.paid_date = 'Payment date is required';
                hasError = true;
            }
            if (!this.payForm.bank_account_id) {
                this.payErrors.bank_account_id = 'Please select a company bank account';
                hasError = true;
            }
            if (!this.payForm.reference_no || !this.payForm.reference_no.trim()) {
                this.payErrors.reference_no = 'Transaction / Cheque / UTR number is required';
                hasError = true;
            }

            if (hasError) {
                this.showToast('Please correct the highlighted form errors.', 'error');
                return;
            }

            fetch(`{{ url('loans') }}/${this.loan.id}/pay-emi/${this.activeInst.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.payForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    if (data.errors && typeof data.errors === 'object') {
                        for (const [k, v] of Object.entries(data.errors)) {
                            this.payErrors[k] = Array.isArray(v) ? v[0] : v;
                        }
                    }
                    let errMsg = data.error || data.message;
                    this.showToast(errMsg || 'Failed to submit payment.', 'error');
                } else {
                    this.showToast('Payment submitted successfully.');
                    this.payModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1200);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },
        submitPrepayForm() {
            this.prepayErrors = {};
            let hasError = false;

            const amount = parseFloat(this.prepayForm.amount);
            const outstanding = parseFloat(this.loan?.outstanding_balance || 0);

            if (!this.prepayForm.amount || isNaN(amount) || amount <= 0) {
                this.prepayErrors.amount = 'Please enter a valid prepayment amount greater than ₹0.00';
                hasError = true;
            } else if (amount > outstanding + 0.01) {
                this.prepayErrors.amount = `Prepayment amount cannot exceed current outstanding balance (₹${outstanding.toLocaleString('en-IN', {minimumFractionDigits: 2})})`;
                hasError = true;
            }

            if (!this.prepayForm.prepayment_date) {
                this.prepayErrors.prepayment_date = 'Please select the date of prepayment';
                hasError = true;
            }

            if (!this.prepayForm.bank_account_id) {
                this.prepayErrors.bank_account_id = 'Please select a company bank account';
                hasError = true;
            }

            if (!this.prepayForm.reference_no || !this.prepayForm.reference_no.trim()) {
                this.prepayErrors.reference_no = 'Transaction / Cheque / UTR number is required';
                hasError = true;
            }

            if (this.prepayForm.prepayment_charges !== '' && this.prepayForm.prepayment_charges !== null && parseFloat(this.prepayForm.prepayment_charges) < 0) {
                this.prepayErrors.prepayment_charges = 'Prepayment charges cannot be negative';
                hasError = true;
            }

            if (hasError) {
                this.showToast('Please correct the highlighted form errors.', 'error');
                return;
            }

            fetch(`{{ url('loans') }}/${this.loan.id}/prepay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.prepayForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    if (data.errors && typeof data.errors === 'object') {
                        for (const [k, v] of Object.entries(data.errors)) {
                            this.prepayErrors[k] = Array.isArray(v) ? v[0] : v;
                        }
                    }
                    let errMsg = data.error || data.message;
                    this.showToast(errMsg || 'Failed to apply prepayment.', 'error');
                } else {
                    this.showToast('Prepayment applied and schedule rescheduled successfully.');
                    this.prepayModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1200);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        }
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dueRow = document.getElementById('first-due-installment');
    if (dueRow) {
        // Scroll to the first due/overdue installment row
        setTimeout(function () {
            dueRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Add a brief highlight pulse to draw attention
            dueRow.style.transition = 'outline 0.2s, box-shadow 0.2s';
            dueRow.style.outline = '2px solid #f87171';
            dueRow.style.boxShadow = '0 0 0 4px rgba(248,113,113,0.25)';
            setTimeout(function () {
                dueRow.style.outline = '';
                dueRow.style.boxShadow = '';
            }, 2500);
        }, 400);
    }
});
</script>
</x-erp-layout>
