<x-erp-layout title="Broker Payout Release" headerTitle="Broker Payout Release">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="brokerPayoutApp()">

    {{-- Header & Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('brokers.index') }}" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Broker Payout Release</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Disburse commission payments from bank accounts to brokers. Commissions become payable only after full payment or EMI completion.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('brokers.index') }}" 
               class="inline-flex items-center gap-2 rounded-xl border border-slate-250 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-slate-700 shadow-2xs transition-all hover:bg-slate-50">
                ← Back to Brokerage Dashboard
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all hover:bg-slate-800">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
        </div>
    </div>

    {{-- Feedback Messages --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 rounded-2xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-250 rounded-2xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- KPI Highlights Banner --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative flex items-center justify-between group overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-400 rounded-l-2xl group-hover:w-2 transition-all"></div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Accrued Commission (Locked)</span>
                <span class="text-2xl font-black text-slate-800 font-mono mt-1 block group-hover:text-amber-600 transition-colors">₹{{ number_format($totalAccrued, 2) }}</span>
                <span class="text-[9px] text-slate-500 mt-1 block font-semibold">Pending customer full payment / EMI</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center font-bold shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative flex items-center justify-between group overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500 rounded-l-2xl group-hover:w-2 transition-all"></div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Payable Commission (Unlocked)</span>
                <span class="text-2xl font-black text-slate-800 font-mono mt-1 block group-hover:text-emerald-600 transition-colors">₹{{ number_format($totalPayable, 2) }}</span>
                <span class="text-[9px] text-slate-500 mt-1 block font-bold">Ready for immediate payment</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative flex items-center justify-between group overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 rounded-l-2xl group-hover:w-2 transition-all"></div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Settled & Paid</span>
                <span class="text-2xl font-black text-slate-800 font-mono mt-1 block group-hover:text-indigo-600 transition-colors">₹{{ number_format($totalPaid, 2) }}</span>
                <span class="text-[9px] text-slate-500 mt-1 block font-semibold">Historical commission payments</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
    </div>

    {{-- Master Broker Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Broker Payout Settlement Dashboard</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Summary of pending commissions per broker. Expand a broker to release payments selectively or disburse ready balances in bulk.</p>
            </div>
            <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-2xs">Showing {{ count($brokerReports) }} Registered Broker(s)</span>
        </div>

        <style>
            .broker-table thead th { border-color: #8a7522 !important; }
        </style>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1000px] broker-table border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-2 py-3 border w-12"></th>
                        <th class="px-3 py-3 border text-left">Broker Name & Ledger Account</th>
                        <th class="px-3 py-3 border">Default Rate</th>
                        <th class="px-3 py-3 border">Accrued (Locked)</th>
                        <th class="px-3 py-3 border">Payable (Unlocked)</th>
                        <th class="px-3 py-3 border">Total Pending</th>
                        <th class="px-3 py-3 border">Total Settled</th>
                        <th class="px-3 py-3 border text-right">Settlement Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 broker-tbody">
                    @forelse($brokerReports as $report)
                        @php
                            $rowBg = $loop->even ? 'bg-[#F6F3E9]/60' : 'bg-white';
                        @endphp
                        <tr class="master-row transition-colors text-center text-xs font-semibold text-slate-700 {{ $rowBg }} hover:bg-[#ebe5d0]/80">
                            <td class="px-2 py-4 border text-center">
                                <button @click="expanded = (expanded === {{ $report->broker->id }} ? null : {{ $report->broker->id }})" 
                                        class="p-1.5 rounded-lg hover:bg-slate-200/80 text-slate-600 transition-all focus:outline-none cursor-pointer"
                                        title="View itemized deals">
                                    <svg class="w-4 h-4 transform transition-transform duration-300" 
                                         :class="expanded === {{ $report->broker->id }} ? 'rotate-180 text-[#a38c29]' : ''" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-3 py-4 border text-left">
                                <div class="font-bold text-slate-900 text-sm">{{ $report->broker->name }}</div>
                                <div class="text-[9px] text-slate-500 font-mono mt-0.5 flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-slate-600 font-bold shadow-sm">A/C: {{ $report->broker->linkedAccount->code ?? 'N/A' }}</span>
                                    <span>{{ $report->broker->linkedAccount->name ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-bold text-slate-700">
                                {{ number_format($report->broker->default_commission_pct, 2) }}%
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-semibold text-amber-700">
                                ₹{{ number_format($report->accrued, 2) }}
                                @if($report->accrued > 0)
                                    <span class="text-[9px] text-amber-600/80 block font-sans font-normal mt-0.5">Awaiting EMI completion</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-bold text-emerald-700 text-sm">
                                ₹{{ number_format($report->payable, 2) }}
                                @if($report->payable > 0)
                                    <span class="text-[9px] bg-white text-emerald-700 px-1.5 py-0.5 rounded font-sans font-bold border border-emerald-200 block mt-1 w-max mx-auto shadow-sm">Ready to Pay</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-black text-slate-900">
                                ₹{{ number_format($report->total_pending, 2) }}
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-semibold text-slate-600">
                                ₹{{ number_format($report->paid, 2) }}
                            </td>
                            <td class="px-3 py-4 border text-right">
                                @if($report->payable > 0)
                                    <button type="button" 
                                            @click="openBulkPayout({{ $report->broker->id }}, '{{ addslashes($report->broker->name) }}', {{ (float)$report->payable }})"
                                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold rounded-xl text-xs transition-all shadow-md uppercase tracking-wide cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Record Payment ₹{{ number_format($report->payable, 0) }}</span>
                                    </button>
                                @else
                                    <span class="text-slate-550 italic text-[10px]">No payable balance</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Expanded Detail Row --}}
                        <tr x-show="expanded === {{ $report->broker->id }}" style="display: none;" class="bg-slate-50/70">
                            <td colspan="8" class="px-6 py-4 border">
                                <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden p-4 space-y-4 text-left">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wide">Itemized Commission Deals — {{ $report->broker->name }}</span>
                                            <p class="text-[10px] text-slate-450 mt-0.5">Locked commissions automatically unlock to "Payable" when the customer booking outstanding balance reaches zero.</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#a38c29] bg-[#a38c29]/10 px-2.5 py-1 rounded-lg">{{ $report->broker->brokerages->count() }} total deal(s)</span>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs text-left border-collapse">
                                            <thead>
                                                <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                                                    <th class="px-3 py-3 border text-left">Booking & Date</th>
                                                    <th class="px-3 py-3 border">Property & Unit</th>
                                                    <th class="px-3 py-3 border">Customer</th>
                                                    <th class="px-3 py-3 border text-right">Sale Value</th>
                                                    <th class="px-3 py-3 border text-right">Commission Amount</th>
                                                    <th class="px-3 py-3 border">EMI / Collection Progress</th>
                                                    <th class="px-3 py-3 border">Status</th>
                                                    <th class="px-3 py-3 border text-right">Disbursement Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @forelse($report->broker->brokerages as $entry)
                                                    @php
                                                        $sale = $entry->sale;
                                                        if (!$sale) continue;

                                                        $status = $entry->status ?? 'pending';
                                                        $commAmount = (float)($entry->commission_amount ?? 0);
                                                        
                                                        $badgeClass = match($status) {
                                                            'payable', 'partial' => 'bg-emerald-50 text-emerald-700 border-emerald-250 font-bold',
                                                            'paid' => 'bg-indigo-50 text-indigo-700 border-indigo-250 font-bold',
                                                            default => 'bg-amber-50 text-amber-700 border-amber-250 font-semibold'
                                                        };

                                                        $statusLabel = match($status) {
                                                            'payable', 'partial' => 'Payable (Unlocked)',
                                                            'paid' => 'Paid Out',
                                                            default => 'Accrued (Locked)'
                                                        };
                                                    @endphp
                                                    <tr class="hover:bg-slate-50/50 transition-colors text-center text-xs">
                                                        <td class="px-3 py-3 border text-left">
                                                            <div class="font-bold text-[#a38c29] font-mono">{{ $sale->sale_number ?? 'N/A' }}</div>
                                                            <div class="text-[9px] text-slate-500 mt-0.5">{{ $sale->sale_date ? $sale->sale_date->format('d M Y') : 'N/A' }}</div>
                                                        </td>
                                                        <td class="px-3 py-3 border text-center">
                                                            <div class="font-bold text-slate-900">{{ $sale->project->name ?? 'N/A' }}</div>
                                                            <div class="text-[10px] text-slate-550 mt-0.5">Unit: <span class="font-bold text-slate-800 font-mono">{{ $sale->unit->door_no ?? 'N/A' }}</span></div>
                                                        </td>
                                                        <td class="px-3 py-3 border text-center font-semibold text-slate-700">
                                                            {{ $sale->customer->name ?? 'Customer' }}
                                                        </td>
                                                        <td class="px-3 py-3 border text-right font-mono font-bold text-slate-900">
                                                            ₹{{ number_format($sale->total_amount ?? 0, 2) }}
                                                        </td>
                                                        <td class="px-3 py-3 border text-right">
                                                            <div class="font-mono font-black text-slate-900">₹{{ number_format($commAmount, 2) }}</div>
                                                            @if($entry->commission_percent)
                                                                <div class="text-[9px] text-slate-450 mt-0.5">@ {{ number_format($entry->commission_percent, 2) }}%</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-3 border text-center">
                                                            @if($sale->remaining_balance <= 0)
                                                                <span class="inline-flex items-center justify-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded border border-emerald-300 shadow-2xs">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                    100% Paid / EMI Complete
                                                                </span>
                                                            @else
                                                                <div class="space-y-1 mx-auto max-w-[120px]">
                                                                    <div class="flex justify-between text-[10px]">
                                                                        <span class="text-slate-600 font-semibold">Bal.</span>
                                                                        <span class="font-mono font-bold text-rose-700">₹{{ number_format($sale->remaining_balance, 2) }}</span>
                                                                    </div>
                                                                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden border border-slate-350">
                                                                        @php
                                                                            $pctPaid = $sale->total_amount > 0 ? (($sale->total_amount - $sale->remaining_balance) / $sale->total_amount) * 100 : 0;
                                                                        @endphp
                                                                        <div class="bg-[#a38c29] h-full rounded-full" style="width: {{ min(100, max(0, $pctPaid)) }}%;"></div>
                                                                    </div>
                                                                    <span class="text-[9px] text-slate-500 block text-center font-bold">{{ number_format($pctPaid, 0) }}% collected</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-3 border text-center">
                                                            <span class="badge-pill border px-2.5 py-1 rounded-xl font-bold text-[9px] uppercase {{ $badgeClass }} inline-block shadow-sm">
                                                                {{ $statusLabel }}
                                                            </span>
                                                        </td>
                                                        <td class="px-3 py-3 border text-right">
                                                            @if($status === 'payable' || $status === 'partial')
                                                                <button type="button" 
                                                                        @click="openDealPayout({{ $entry->id }}, '{{ addslashes($report->broker->name) }}', '{{ addslashes($sale->sale_number ?? '') }}', {{ $commAmount }}, {{ (float)$report->payable }})"
                                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] transition uppercase tracking-wide shadow-2xs cursor-pointer">
                                                                    Pay ₹{{ number_format($commAmount, 0) }}
                                                                </button>
                                                            @elseif($status === 'pending')
                                                                <span class="text-[10px] text-slate-400 italic">Locked (Pending Bal.)</span>
                                                            @else
                                                                <span class="text-[10px] text-indigo-650 font-bold">Settled</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="px-3 py-6 text-center text-slate-500 italic">No commission deals linked to this broker.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 border text-center text-slate-500 italic">No broker records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Unified Record Broker Payout Modal --}}
    <div x-show="payoutModal.open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left"
         style="display: none;" 
         x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.away="payoutModal.open = false">
            {{-- Header --}}
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BROKER PAYOUT SETUP</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">Record Broker Payout</h3>
                </div>
                <button type="button" @click="payoutModal.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('brokers.payout') }}" method="POST" class="p-6 space-y-4 text-xs font-sans bg-white">
                @csrf
                <template x-if="payoutModal.isBulk">
                    <input type="hidden" name="broker_id" :value="payoutModal.brokerId">
                </template>
                <template x-if="!payoutModal.isBulk">
                    <input type="hidden" name="commission_entry_id" :value="payoutModal.commissionEntryId">
                </template>

                {{-- Summary Prompt Note --}}
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <template x-if="payoutModal.isBulk">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Are you sure you want to settle the total payable commission of <span class="font-mono font-black text-emerald-700 text-sm" x-text="formatCurrency(payoutModal.payoutAmount)"></span> to <span class="font-extrabold text-slate-900" x-text="payoutModal.brokerName"></span>?
                        </p>
                    </template>
                    <template x-if="!payoutModal.isBulk">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            You are about to record a commission payout of <span class="font-mono font-black text-emerald-700 text-sm" x-text="formatCurrency(payoutModal.payoutAmount)"></span> for Sale <span class="font-bold text-[#a38c29]" x-text="'#' + payoutModal.saleNumber"></span> to <span class="font-extrabold text-slate-900" x-text="payoutModal.brokerName"></span>.
                        </p>
                    </template>
                </div>

                {{-- Pay From Account Select --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                        PAY FROM ACCOUNT / SOURCE BANK ACCOUNT <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="company_bank_account_id" 
                                x-model="payoutModal.selectedBankId" 
                                required 
                                class="w-full pl-3 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">-- Choose Bank Account --</option>
                            @foreach($companyBankAccounts as $cBank)
                                <option value="{{ $cBank->id }}">{{ $cBank->bank_name }} Account ({{ $cBank->account_number ? 'BANK-'.substr($cBank->account_number, 0, 8).'...' : $cBank->account_name }}) — Avail: Rs. {{ number_format((float)($cBank->current_balance ?? $cBank->opening_balance ?? 0), 2) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Live Insufficient Funds Error Banner --}}
                <template x-if="modalErrorMessage">
                    <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-700 text-xs font-bold shadow-2xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="modalErrorMessage"></span>
                    </div>
                </template>

                {{-- Live Dynamic Balance Summary Box --}}
                <div class="bg-slate-50/90 border border-slate-200/90 rounded-2xl p-4 space-y-2 shadow-2xs text-xs">
                    <template x-if="selectedBankAccount">
                        <div class="space-y-1.5 pb-2 border-b border-slate-200/80">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-bold text-slate-600">Selected Bank Account Balance (<span x-text="selectedBankAccount?.bank_name"></span>)</span>
                                <span class="font-mono font-extrabold text-blue-600 text-sm shrink-0" x-text="formatCurrency(selectedBankBalance)">Rs. 0</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-bold text-slate-600">Bank Balance After Payout</span>
                                <span class="font-mono font-bold text-sm shrink-0" :class="bankBalanceAfterPayout < 0 ? 'text-rose-600 font-extrabold' : 'text-slate-800'" x-text="formatCurrency(bankBalanceAfterPayout)">Rs. 0</span>
                            </div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between gap-3 pt-1">
                        <span class="font-bold text-slate-600">Available Broker Balance</span>
                        <span class="font-mono font-extrabold text-emerald-600 text-sm shrink-0" x-text="formatCurrency(payoutModal.availablePayable)">Rs. 0</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-slate-600">Payout Amount</span>
                        <span class="font-mono font-extrabold text-rose-500 text-sm shrink-0" x-text="formatCurrency(payoutModal.payoutAmount)">Rs. 0</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between gap-3">
                        <span class="font-extrabold text-slate-900 uppercase tracking-wider pr-2">Broker Balance After Payout</span>
                        <span class="font-mono font-black text-slate-900 text-base shrink-0" x-text="formatCurrency(brokerBalanceAfterPayout)">Rs. 0</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="payoutModal.open = false" 
                            class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" 
                            :disabled="Boolean(modalErrorMessage) || !payoutModal.selectedBankId"
                            :class="Boolean(modalErrorMessage) || !payoutModal.selectedBankId ? 'opacity-50 cursor-not-allowed bg-slate-400 hover:bg-slate-400' : 'bg-[#a38c29] hover:bg-[#8a7522] cursor-pointer shadow-md'"
                            class="px-5 py-2.5 text-white text-xs font-black uppercase tracking-wider rounded-xl transition inline-flex items-center gap-2">
                        <span>CONFIRM & POST PAYOUT</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function brokerPayoutApp() {
    return {
        expanded: null,
        companyBankAccounts: @json($companyBankAccounts) || [],
        payoutModal: {
            open: false,
            brokerId: null,
            commissionEntryId: null,
            brokerName: '',
            saleNumber: '',
            isBulk: false,
            availablePayable: 0,
            payoutAmount: 0,
            selectedBankId: '',
        },
        openBulkPayout(brokerId, brokerName, payableAmount) {
            const firstBankId = (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? String(this.companyBankAccounts[0].id) : '';
            this.payoutModal = {
                open: true,
                brokerId: brokerId,
                commissionEntryId: null,
                brokerName: brokerName,
                saleNumber: '',
                isBulk: true,
                availablePayable: Number(payableAmount || 0),
                payoutAmount: Number(payableAmount || 0),
                selectedBankId: firstBankId,
            };
        },
        openDealPayout(entryId, brokerName, saleNumber, amount, brokerPayable) {
            const firstBankId = (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? String(this.companyBankAccounts[0].id) : '';
            this.payoutModal = {
                open: true,
                brokerId: null,
                commissionEntryId: entryId,
                brokerName: brokerName,
                saleNumber: saleNumber,
                isBulk: false,
                availablePayable: Number(brokerPayable || amount || 0),
                payoutAmount: Number(amount || 0),
                selectedBankId: firstBankId,
            };
        },
        get selectedBankAccount() {
            if (!this.payoutModal.selectedBankId) return null;
            return this.companyBankAccounts.find(b => String(b.id) === String(this.payoutModal.selectedBankId)) || null;
        },
        get selectedBankBalance() {
            const b = this.selectedBankAccount;
            if (!b) return 0;
            return Number(b.current_balance !== null && b.current_balance !== undefined ? b.current_balance : (b.opening_balance || 0));
        },
        get bankBalanceAfterPayout() {
            return this.selectedBankBalance - this.payoutModal.payoutAmount;
        },
        get brokerBalanceAfterPayout() {
            return this.payoutModal.availablePayable - this.payoutModal.payoutAmount;
        },
        get isBankInsufficient() {
            if (!this.selectedBankAccount) return false;
            return this.payoutModal.payoutAmount > 0 && this.payoutModal.payoutAmount > this.selectedBankBalance;
        },
        get modalErrorMessage() {
            if (this.isBankInsufficient) {
                const bankName = this.selectedBankAccount ? this.selectedBankAccount.bank_name : 'selected bank';
                return `Insufficient Bank Funds! Payout amount (${this.formatCurrency(this.payoutModal.payoutAmount)}) exceeds available balance in ${bankName} (${this.formatCurrency(this.selectedBankBalance)}).`;
            }
            return '';
        },
        formatCurrency(val) {
            const n = Number(val || 0);
            return 'Rs. ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    };
}
</script>

</x-erp-layout>
