<x-erp-layout title="Broker Payout Release" headerTitle="Broker Payout Release">

<div class="max-w-[1800px] mx-auto space-y-6">

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
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" x-data="{ expanded: null }">
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
                                        class="p-1.5 rounded-lg hover:bg-slate-200/80 text-slate-600 transition-all focus:outline-none"
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
                                    <form action="{{ route('brokers.payout') }}" method="POST" x-data="{ openConfirm: false }" @submit.prevent="openConfirm = true">
                                        @csrf
                                        <input type="hidden" name="broker_id" value="{{ $report->broker->id }}">
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold rounded-xl text-xs transition-all shadow-md uppercase tracking-wide">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Record Payment ₹{{ number_format($report->payable, 0) }}
                                        </button>

                                        {{-- Confirmation Modal --}}
                                        <div x-show="openConfirm" 
                                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity text-left whitespace-normal font-normal"
                                             style="display: none;" x-transition.opacity>
                                            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-emerald-100 transform transition-all" @click.away="openConfirm = false">
                                                <div class="p-6 text-center">
                                                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-4 shadow-sm border border-emerald-100">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Confirm Payment</h3>
                                                    <p class="text-xs text-slate-500 font-medium">Are you sure you want to settle the total payable commission of <span class="font-bold text-slate-800 font-mono">₹{{ number_format($report->payable, 2) }}</span> to <span class="font-bold text-emerald-700">{{ $report->broker->name }}</span>?</p>
                                                    
                                                    @if($companyBankAccounts->isEmpty())
                                                        <div class="mt-4 p-3 bg-rose-50 border border-rose-250 text-rose-800 rounded-xl text-[10px] font-bold text-center">
                                                            ⚠️ No active bank accounts found. Please add a bank account in Treasury first.
                                                        </div>
                                                    @else
                                                        <div class="mt-4 text-left">
                                                            <label class="text-[10px] font-bold text-slate-555 uppercase tracking-wide block mb-1.5">Source Bank Account <span class="text-rose-500">*</span></label>
                                                            <select name="company_bank_account_id" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                                                                @foreach($companyBankAccounts as $acc)
                                                                    <option value="{{ $acc->id }}">{{ $acc->bank_name }} ({{ $acc->account_number }}) — Bal: ₹{{ number_format($acc->current_balance, 2) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="mt-6 flex items-center justify-center gap-3">
                                                        <button type="button" @click="openConfirm = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-wide">Cancel</button>
                                                        @if(!$companyBankAccounts->isEmpty())
                                                            <button type="button" @click="$el.closest('form').submit()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all uppercase tracking-wide inline-flex items-center gap-2">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                Yes, Settle Now
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                                        $commAmount = $entry->commission_amount ?? 0;
                                                        
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
                                                                <form action="{{ route('brokers.payout') }}" method="POST" x-data="{ openConfirm: false }" @submit.prevent="openConfirm = true">
                                                                    @csrf
                                                                    <input type="hidden" name="commission_entry_id" value="{{ $entry->id }}">
                                                                    <button type="submit" 
                                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] transition uppercase tracking-wide shadow-2xs">
                                                                        Pay ₹{{ number_format($commAmount, 0) }}
                                                                    </button>

                                                                    {{-- Confirmation Modal --}}
                                                                    <div x-show="openConfirm" 
                                                                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity text-left whitespace-normal font-normal"
                                                                         style="display: none;" x-transition.opacity>
                                                                        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-emerald-100 transform transition-all" @click.away="openConfirm = false">
                                                                            <div class="p-6 text-center">
                                                                                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-4 shadow-sm border border-emerald-100">
                                                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                                </div>
                                                                                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Settle Transaction</h3>
                                                                                <p class="text-xs text-slate-500 font-medium leading-relaxed">You are about to record a commission payment of <span class="font-bold text-slate-800 font-mono">₹{{ number_format($commAmount, 2) }}</span> for Sale <span class="font-bold text-emerald-700 font-mono">#{{ $sale->sale_number ?? '' }}</span>.</p>
                                                                                
                                                                                @if($companyBankAccounts->isEmpty())
                                                                                    <div class="mt-4 p-3 bg-rose-50 border border-rose-250 text-rose-800 rounded-xl text-[10px] font-bold text-center">
                                                                                        ⚠️ No active bank accounts found. Please add a bank account in Treasury first.
                                                                                    </div>
                                                                                @else
                                                                                    <div class="mt-4 text-left">
                                                                                        <label class="text-[10px] font-bold text-slate-555 uppercase tracking-wide block mb-1.5">Source Bank Account <span class="text-rose-500">*</span></label>
                                                                                        <select name="company_bank_account_id" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                                                                                            @foreach($companyBankAccounts as $acc)
                                                                                                <option value="{{ $acc->id }}">{{ $acc->bank_name }} ({{ $acc->account_number }}) — Bal: ₹{{ number_format($acc->current_balance, 2) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="mt-6 flex items-center justify-center gap-3">
                                                                                    <button type="button" @click="openConfirm = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-wide">Cancel</button>
                                                                                    @if(!$companyBankAccounts->isEmpty())
                                                                                        <button type="button" @click="$el.closest('form').submit()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all uppercase tracking-wide inline-flex items-center gap-2">
                                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                                            Yes, Settle Now
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
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

</div>

</x-erp-layout>
