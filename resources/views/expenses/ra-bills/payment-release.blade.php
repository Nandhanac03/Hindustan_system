@extends('layouts.erp')

@section('title', 'Contractor Payment Release Desk')

@section('content')
<div x-data="raBillPaymentRelease()" class="p-6 space-y-6 bg-slate-50 min-h-screen">

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

    <!-- Executive Treasury KPI Metrics Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-500 shadow-xs">
            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">VERIFIED PAYABLE CLAIMS</span>
            <div class="text-xl font-mono font-black text-blue-900 mt-1">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
            <div class="text-[10px] text-blue-600 font-semibold mt-1">Total Net Approved Liability</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">TOTAL DISBURSED (PAID)</span>
            <div class="text-xl font-mono font-black text-emerald-800 mt-1">₹{{ number_format((float) $totalPaid, 2) }}</div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-1">Corporate Bank Account Outflows</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-rose-500 shadow-xs">
            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider block">PENDING DISBURSEMENT BALANCES</span>
            <div class="text-xl font-mono font-black text-rose-800 mt-1">₹{{ number_format((float) $totalBalance, 2) }}</div>
            <div class="text-[10px] text-rose-600 font-semibold mt-1">Outstanding Balance Remaining</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-slate-800 shadow-xs">
            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">READY FOR PAYMENT</span>
            <div class="text-xl font-mono font-black text-slate-900 mt-1">{{ $raBills->whereNotNull('verified_date')->where('balance_amount', '>', 0)->count() }} Bills</div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Verified & Unpaid RA Bills</div>
        </div>
    </div>

    <!-- Payment Disbursal Desk Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Contractor Payment Release Register</span>
                <span class="text-[11px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">{{ $raBills->count() }} Records</span>
            </div>

            <div class="flex items-center gap-3">
                <input type="text" x-model="searchQuery" placeholder="Search RA Bill #, Contractor..."
                       class="px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none w-72 shadow-2xs">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3 py-3 text-left w-[130px]">RA BILL NO</th>
                        <th class="px-3 py-3 text-left w-[180px]">CONTRACTOR / PROJECT</th>
                        <th class="px-3 py-3 text-left w-[110px]">VERIFIED DATE</th>
                        <th class="px-3 py-3 text-left w-[110px]">NET APPROVED (₹)</th>
                        <th class="px-3 py-3 text-left text-emerald-100 w-[110px]">PAID AMOUNT (₹)</th>
                        <th class="px-3 py-3 text-left text-rose-100 w-[110px]">BALANCE DUE (₹)</th>
                        <th class="px-3 py-3 text-left w-[95px]">STATUS</th>
                        <th class="px-3 py-3 text-right w-[120px]">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    @forelse($raBills as $bill)
                        @php
                            $isCleared = ((float)$bill->balance_amount <= 0.001);
                            $isVerified = !empty($bill->verified_date);
                            $paymentCount = $bill->payments->count();
                        @endphp
                        <tbody x-data="{ showHistory: false }" class="border-b border-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-[10.5px] whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                                        @if($paymentCount > 0)
                                            <button type="button" @click="showHistory = !showHistory"
                                                    class="px-1.5 py-0.5 bg-[#a38c29]/15 hover:bg-[#a38c29]/30 text-[#7a681d] rounded font-black text-[9px] cursor-pointer inline-flex items-center gap-1 transition shadow-2xs border border-[#a38c29]/40"
                                                    title="Toggle Part-by-Part Payment History">
                                                <span x-text="showHistory ? '▲ Hide History' : '▼ ' + {{ $paymentCount }} + ' Part Paid'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-middle">
                                    <div class="font-black text-slate-900 text-[11.5px] leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                    <div class="text-[10px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                </td>

                                <td class="px-3 py-3 text-left font-mono align-middle">
                                    @if($bill->verified_date)
                                        <div class="text-[10.5px] text-emerald-700 font-bold">
                                            {{ $bill->verified_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[8.5px] text-slate-500 truncate max-w-[100px]">By: {{ $bill->engineer_name ?: 'Engineer' }}</div>
                                    @else
                                        <span class="text-amber-600 text-[9.5px] italic font-semibold">Verification Pending</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                    ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-bold text-emerald-700 align-middle">
                                    <div>₹{{ number_format((float) $bill->paid_amount, 2) }}</div>
                                    @if($paymentCount > 0)
                                        <div class="text-[8.5px] text-[#7a681d] font-bold">{{ $paymentCount }} Installment(s)</div>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-left font-mono font-black align-middle {{ $isCleared ? 'text-slate-400' : 'text-rose-700' }}">
                                    ₹{{ number_format((float) $bill->balance_amount, 2) }}
                                </td>

                                <td class="px-3 py-3 text-left whitespace-nowrap align-middle">
                                    @if($isCleared)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <svg class="w-2.5 h-2.5 text-[#087443]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>CLEARED</span>
                                        </span>
                                    @elseif($isVerified)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            <span>PENDING RELEASE</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">UNVERIFIED</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-right whitespace-nowrap align-middle">
                                    @if($isVerified && !$isCleared)
                                        <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10.5px] font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                                                title="Disburse Staggered Payment Release">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Disburse Payment</span>
                                        </button>
                                    @elseif($isCleared)
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Fully Paid</span>
                                    @else
                                        <span class="text-[9.5px] text-amber-600 font-semibold italic">Requires Verification</span>
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
                                                                        <a href="/vouchers/{{ $pay->voucher_id }}/payment-voucher-print" target="_blank"
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
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                No Contractor RA Progress Bills pending for payment release.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── MODAL: STAGGERED DISBURSEMENT RELEASE ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="disburseModalOpen = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 text-[9px] font-black uppercase tracking-wider rounded border border-emerald-500/40 mb-1">PAYMENT DISBURSEMENT</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DISBURSE STAGGERED CONTRACTOR PAYMENT</h3>
                </div>
                <button type="button" @click="disburseModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form :action="selectedBill ? `/expenses/ra-bills/${selectedBill.id}/disburse` : '#'" method="POST" target="_blank" class="p-6 space-y-4" @submit="setTimeout(() => { disburseModalOpen = false; window.location.reload(); }, 800)">
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
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAYMENT MODE  <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_mode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
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

</div>

<script>
function raBillPaymentRelease() {
    return {
        searchQuery: '',
        disburseModalOpen: false,
        selectedBill: null,

        openDisburseModal(bill) {
            this.selectedBill = bill;
            this.disburseModalOpen = true;
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
