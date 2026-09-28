<x-erp-layout title="Broker Payout Release Report" headerTitle="Broker Payout Release">

<style>
@media print {
    @page {
        size: landscape;
        margin: 0;
    }
    *, *::before, *::after {
        box-sizing: border-box !important;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 8pt !important;
        padding: 6mm 8mm !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print\:hidden, header, nav, aside, footer, button, select, input, .custom-scrollbar::-webkit-scrollbar {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
    .print\:grid {
        display: grid !important;
    }
    .print\:flex {
        display: flex !important;
    }
    .print\:table {
        display: table !important;
    }
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    thead {
        display: table-header-group !important;
    }
    tr {
        page-break-inside: avoid !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl, .shadow-2xs {
        box-shadow: none !important;
    }
    .border-slate-200, .border-slate-100 {
        border-color: #cbd5e1 !important;
    }
}
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="brokerPayoutApp()">

    <!-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Brokerage &amp; Payout Intelligence</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">BROKER PAYOUT RELEASE &amp; COMMISSION SETTLEMENT REPORT</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ date('d-M-Y') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Scope:</span> <span class="font-bold text-[#a38c29]">All Registered Brokers</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Brokers:</span> <span class="font-mono font-bold text-slate-800">{{ count($brokerReports) }} Brokers</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Payable:</span> <span class="font-mono font-bold text-emerald-700">₹{{ number_format($totalPayable, 2) }}</span></div>
            </div>
        </div>
    </div>

    <!-- ── EXECUTIVE PRINT KPI CARDS (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:grid grid-cols-3 gap-3 mb-5">
        <div class="border border-amber-300 rounded-xl p-3 bg-amber-50/40">
            <span class="text-[8.5px] font-black uppercase text-amber-600 block">Accrued Commission (Locked)</span>
            <strong class="text-sm font-black text-slate-900 font-mono block mt-0.5">₹{{ number_format($totalAccrued, 2) }}</strong>
            <span class="text-[8px] text-slate-500 font-bold">Pending customer full payment / EMI</span>
        </div>
        <div class="border border-emerald-300 rounded-xl p-3 bg-emerald-50/40">
            <span class="text-[8.5px] font-black uppercase text-emerald-600 block">Payable Commission (Unlocked)</span>
            <strong class="text-sm font-black text-emerald-700 font-mono block mt-0.5">₹{{ number_format($totalPayable, 2) }}</strong>
            <span class="text-[8px] text-emerald-600 font-bold">Ready for immediate disbursement</span>
        </div>
        <div class="border border-indigo-300 rounded-xl p-3 bg-indigo-50/40">
            <span class="text-[8.5px] font-black uppercase text-indigo-600 block">Total Settled &amp; Paid</span>
            <strong class="text-sm font-black text-slate-900 font-mono block mt-0.5">₹{{ number_format($totalPaid, 2) }}</strong>
            <span class="text-[8px] text-indigo-600 font-bold">Historical commission payouts</span>
        </div>
    </div>

    {{-- Header & Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
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
            
            <button type="button" onclick="printCleanPDF()" 
                    class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>EXPORT PDF</span>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 print:hidden">
        {{-- Card 1: Locked Commission --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all duration-300 relative group overflow-hidden border-l-4 border-l-[#a38c29]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <span class="text-xs font-black text-slate-700 uppercase tracking-wider">LOCKED COMMISSION</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                    LOCKED
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">₹{{ number_format($totalAccrued, 2) }}</span>
                <span class="text-[11px] text-slate-400 font-medium mt-1 block">Commissions awaiting customer payment completion</span>
            </div>
        </div>

        {{-- Card 2: Ready to Pay --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all duration-300 relative group overflow-hidden border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="text-xs font-black text-slate-700 uppercase tracking-wider">READY TO PAY</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                    READY
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">₹{{ number_format($totalPayable, 2) }}</span>
                <span class="text-[11px] text-slate-400 font-medium mt-1 block">Unlocked commission ready for immediate payment</span>
            </div>
        </div>

        {{-- Card 3: Total Paid Commission --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all duration-300 relative group overflow-hidden border-l-4 border-l-rose-500">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <span class="text-xs font-black text-slate-700 uppercase tracking-wider">TOTAL PAID COMMISSION</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">
                    SETTLED
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">₹{{ number_format($totalPaid, 2) }}</span>
                <span class="text-[11px] text-slate-400 font-medium mt-1 block">Total commission payments disbursed to date</span>
            </div>
        </div>
    </div>

    {{-- Master Broker Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden print:hidden">
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
                        <th class="px-3 py-3 border">Locked Commission</th>
                        <th class="px-3 py-3 border">Ready to Pay</th>
                        <th class="px-3 py-3 border">Total Pending</th>
                        <th class="px-3 py-3 border">Total Paid</th>
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
                                ₹{{ number_format($report->paid_out, 2) }}
                            </td>
                            <td class="px-3 py-4 border text-right">
                                @if($report->payable > 0 || $report->total_pending > 0)
                                    <button type="button" 
                                            @click="openBulkPayout({{ $report->broker->id }}, '{{ addslashes($report->broker->name) }}', {{ (float)$report->payable }}, {{ (float)$report->total_pending }})"
                                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold rounded-xl text-xs transition-all shadow-md uppercase tracking-wide cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 00-2 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Record Payment {{ $report->payable > 0 ? '₹'.number_format($report->payable, 0) : '' }}</span>
                                    </button>
                                @else
                                    <span class="text-emerald-700 font-bold text-xs flex items-center justify-end gap-1">
                                        <svg class="w-4 h-4 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Fully Settled
                                    </span>
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
                                                        $paidAmount = (float)($entry->paid_amount ?? 0);
                                                        $remainingPayable = max(0.0, $commAmount - $paidAmount);
                                                        
                                                        if ($remainingPayable <= 0.01 || $status === 'paid') {
                                                            $statusLabel = 'Fully Paid';
                                                            $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300 font-extrabold';
                                                        } elseif ($paidAmount > 0 || $status === 'partial') {
                                                            $statusLabel = 'Partially Paid';
                                                            $badgeClass = 'bg-amber-100 text-amber-800 border-amber-300 font-extrabold';
                                                        } else {
                                                            if ($status === 'payable' || $sale->remaining_balance <= 0) {
                                                                $statusLabel = 'Pending Payment';
                                                                $badgeClass = 'bg-blue-100 text-blue-800 border-blue-300 font-extrabold';
                                                            } else {
                                                                $statusLabel = 'Pending (Locked)';
                                                                $badgeClass = 'bg-slate-100 text-slate-700 border-slate-300 font-extrabold';
                                                            }
                                                        }
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
                                                            <span class="border px-2.5 py-1 rounded-xl font-bold text-[9px] uppercase {{ $badgeClass }} inline-block shadow-2xs">
                                                                {{ $statusLabel }}
                                                            </span>
                                                            @if($paidAmount > 0 && $remainingPayable > 0.01)
                                                                <div class="text-[9px] text-slate-500 font-mono mt-1 font-bold">Paid: ₹{{ number_format($paidAmount, 2) }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-3 border text-right">
                                                            @if($remainingPayable > 0.01)
                                                                <button type="button" 
                                                                        @click="openDealPayout({{ $entry->id }}, '{{ addslashes($report->broker->name) }}', '{{ addslashes($sale->sale_number ?? '') }}', {{ $commAmount }}, {{ $paidAmount }}, {{ $remainingPayable }})"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] transition uppercase tracking-wide shadow-2xs cursor-pointer">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                                    <span>Pay Now</span>
                                                                </button>
                                                            @else
                                                                <span class="text-[10px] text-emerald-700 font-bold inline-flex items-center gap-1">
                                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                    Fully Settled
                                                                </span>
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

    <!-- ── EXECUTIVE PRINTABLE BROKER PAYABLE TABLE (ONLY VISIBLE IN PRINT/PDF) ── -->
    <div class="hidden print:block mb-8">
        <div class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1">Broker Payout Settlement Summary</div>
        <table class="w-full text-xs text-left border-collapse border border-slate-300">
            <thead>
                <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[9px]">
                    <th class="px-2 py-2 border border-slate-300 w-8">#</th>
                    <th class="px-3 py-2 border border-slate-300 text-left">Broker Name</th>
                    <th class="px-3 py-2 border border-slate-300 text-left">Ledger Account</th>
                    <th class="px-3 py-2 border border-slate-300">Default Rate</th>
                    <th class="px-3 py-2 border border-slate-300 text-right">Accrued (Locked)</th>
                    <th class="px-3 py-2 border border-slate-300 text-right">Payable (Unlocked)</th>
                    <th class="px-3 py-2 border border-slate-300 text-right">Total Pending</th>
                    <th class="px-3 py-2 border border-slate-300 text-right">Total Settled</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($brokerReports as $index => $report)
                    @php
                        $rowBg = $loop->even ? 'bg-[#F6F3E9]/40' : 'bg-white';
                    @endphp
                    <tr class="text-center text-[10px] font-semibold text-slate-800 {{ $rowBg }}">
                        <td class="px-2 py-2 border border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 border border-slate-300 text-left font-bold text-slate-900">{{ $report->broker->name }}</td>
                        <td class="px-3 py-2 border border-slate-300 text-left font-mono text-slate-600">
                            {{ $report->broker->linkedAccount->code ?? 'N/A' }} - {{ $report->broker->linkedAccount->name ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-300 text-center font-mono font-bold">{{ number_format($report->broker->default_commission_pct, 2) }}%</td>
                        <td class="px-3 py-2 border border-slate-300 text-right font-mono text-amber-800">₹{{ number_format($report->accrued, 2) }}</td>
                        <td class="px-3 py-2 border border-slate-300 text-right font-mono font-bold text-emerald-800">₹{{ number_format($report->payable, 2) }}</td>
                        <td class="px-3 py-2 border border-slate-300 text-right font-mono font-black text-slate-900">₹{{ number_format($report->total_pending, 2) }}</td>
                        <td class="px-3 py-2 border border-slate-300 text-right font-mono text-slate-700">₹{{ number_format($report->paid, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 border border-slate-300 text-center text-slate-500 italic">No broker records found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold text-slate-900 text-[10px] border-t-2 border-slate-400">
                    <td colspan="4" class="px-3 py-2.5 border border-slate-300 text-right uppercase tracking-wider">Grand Total Summary:</td>
                    <td class="px-3 py-2.5 border border-slate-300 text-right font-mono text-amber-900">₹{{ number_format($totalAccrued, 2) }}</td>
                    <td class="px-3 py-2.5 border border-slate-300 text-right font-mono text-emerald-900 font-black">₹{{ number_format($totalPayable, 2) }}</td>
                    <td class="px-3 py-2.5 border border-slate-300 text-right font-mono text-slate-900 font-black">₹{{ number_format($totalAccrued + $totalPayable, 2) }}</td>
                    <td class="px-3 py-2.5 border border-slate-300 text-right font-mono text-slate-800">₹{{ number_format($totalPaid, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Unified Record Broker Payout Modal --}}
    <div x-show="payoutModal.open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left"
         style="display: none;" 
         x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.away="payoutModal.open = false">
            {{-- Header --}}
            <div class="bg-[#2a2415] px-5 py-3.5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-0.5">BROKER PAYOUT SETUP</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Record Broker Payout</h3>
                </div>
                <button type="button" @click="payoutModal.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('brokers.payout') }}" method="POST" class="p-5 space-y-3.5 text-xs font-sans bg-white">
                @csrf
                <template x-if="payoutModal.isBulk">
                    <input type="hidden" name="broker_id" :value="payoutModal.brokerId">
                </template>
                <template x-if="!payoutModal.isBulk">
                    <input type="hidden" name="commission_entry_id" :value="payoutModal.commissionEntryId">
                </template>

                {{-- Summary Prompt Note --}}
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <template x-if="payoutModal.isBulk">
                        <p class="text-xs text-slate-700 leading-snug">
                            You are recording a bulk commission payout to <span class="font-extrabold text-slate-900" x-text="payoutModal.brokerName"></span>. Total pending: <span class="font-mono font-black text-emerald-700" x-text="formatCurrency(payoutModal.availablePayable)"></span>.
                        </p>
                    </template>
                    <template x-if="!payoutModal.isBulk">
                        <p class="text-xs text-slate-700 leading-snug">
                            You are recording a commission payout for Sale <span class="font-bold text-[#a38c29]" x-text="'#' + payoutModal.saleNumber"></span> to <span class="font-extrabold text-slate-900" x-text="payoutModal.brokerName"></span>. Total commission: <span class="font-mono font-black text-emerald-700" x-text="formatCurrency(payoutModal.commAmount)"></span>.
                        </p>
                    </template>
                </div>

                {{-- Inputs Grid (2 Columns) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    {{-- Customizable Payout Amount Input --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                                PAYOUT AMOUNT (₹) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-slate-500 font-bold">
                                Max: <span class="font-mono text-emerald-700" x-text="formatCurrency(payoutModal.availablePayable)"></span>
                            </span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-black text-slate-400">₹</span>
                            <input type="number" step="0.01" min="0.01" :max="payoutModal.availablePayable"
                                   name="amount"
                                   x-model.number="payoutModal.payoutAmount"
                                   data-no-words="true"
                                   required
                                   class="w-full h-10 pl-7 pr-3 bg-slate-50 focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 font-mono transition shadow-2xs"
                                   placeholder="Enter custom payout amount...">
                        </div>
                        {{-- Payout Amount in Words Badge (Theme Golden Color) --}}
                        <div x-show="payoutAmountInWords" 
                             class="mt-1 px-2.5 py-1 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#8a7522] font-extrabold text-[10px] capitalize tracking-wide shadow-2xs">
                            <span x-text="payoutAmountInWords"></span>
                        </div>
                    </div>

                    {{-- Pay From Account Select (Custom Searchable Dropdown) --}}
                    <div class="space-y-1.5 relative" @click.outside="modalBankOpen = false">
                        <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                            PAY FROM ACCOUNT / SOURCE BANK ACCOUNT <span class="text-rose-500">*</span>
                        </label>
                        
                        {{-- Hidden Required Input for Form Submission --}}
                        <input type="hidden" name="company_bank_account_id" :value="payoutModal.selectedBankId" required>

                        {{-- Dropdown Trigger Button --}}
                        <div @click="modalBankOpen = !modalBankOpen; if(modalBankOpen) { modalBankSearch = ''; $nextTick(() => $refs.modalBankSearchInput?.focus()); }"
                             class="w-full h-10 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs">
                            <template x-if="selectedBankAccount">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="px-1.5 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[9px]" x-text="selectedBankAccount.bank_name"></span>
                                    <span class="font-bold text-slate-800 truncate text-[11px]" x-text="selectedBankAccount.account_name || selectedBankAccount.bank_name"></span>
                                    <span class="text-slate-500 text-[9px] font-mono shrink-0" x-text="'(A/C: ' + (selectedBankAccount.account_number || '—') + ')'"></span>
                                </div>
                            </template>
                            <template x-if="!selectedBankAccount">
                                <span class="text-slate-400 font-medium">Select Bank Account...</span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0 ml-1" :class="modalBankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Selected Bank Balance in Words Badge (Theme Golden Color - Direct Words Only) --}}
                        <div x-show="selectedBankBalanceInWords" 
                             class="mt-1 px-2.5 py-1 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#8a7522] font-extrabold text-[10px] capitalize tracking-wide shadow-2xs">
                            <span x-text="selectedBankBalanceInWords"></span>
                        </div>

                        {{-- Dropdown Popover List --}}
                        <div x-show="modalBankOpen" 
                             x-transition
                             class="absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-56 flex flex-col"
                             style="display: none;">
                            
                            {{-- Search Input inside Popover --}}
                            <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                                <div class="relative">
                                    <input type="text" 
                                           x-model="modalBankSearch" 
                                           x-ref="modalBankSearchInput"
                                           placeholder="Search bank name, account no..." 
                                           class="w-full pl-7 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                    <svg class="w-3 h-3 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>

                            {{-- Results List --}}
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                <template x-for="acc in filteredModalBankAccounts" :key="acc.id">
                                    <div @click="payoutModal.selectedBankId = String(acc.id); modalBankOpen = false; modalBankSearch = ''"
                                         class="px-3 py-2 hover:bg-[#a38c29]/5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="String(payoutModal.selectedBankId) === String(acc.id) ? 'bg-[#a38c29]/10 font-bold' : ''">
                                        <div class="flex flex-col min-w-0 pr-2">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                            </div>
                                            <div class="text-[9px] text-slate-400 font-mono mt-0.5 truncate" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                        </div>
                                        <div class="text-right font-mono shrink-0">
                                            <div class="text-[8px] text-slate-400 uppercase font-sans font-bold tracking-wider">Current Balance</div>
                                            <div class="font-bold text-slate-800 text-[11px]" x-text="formatCurrency(acc.current_balance !== null && acc.current_balance !== undefined ? acc.current_balance : (acc.opening_balance || 0))"></div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="filteredModalBankAccounts.length === 0">
                                    <div class="p-3 text-center text-xs text-slate-400 italic">No matching bank accounts found.</div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Live Insufficient Funds Error Banner --}}
                <template x-if="modalErrorMessage">
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2.5 text-rose-700 text-xs font-bold shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="modalErrorMessage"></span>
                    </div>
                </template>

                {{-- Compact 2-Column Live Dynamic Balance Summary Box --}}
                <div class="bg-slate-50/90 border border-slate-200/90 rounded-xl p-3 shadow-2xs text-xs">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                        {{-- Left Column: Bank Account Details --}}
                        <div class="space-y-1.5 md:border-r md:border-slate-200/80 md:pr-4">
                            <template x-if="selectedBankAccount">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-slate-600 text-[11px]">Bank Balance (<span x-text="selectedBankAccount?.bank_name"></span>)</span>
                                        <span class="font-mono font-black text-blue-600 text-xs shrink-0" x-text="formatCurrency(selectedBankBalance)">Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-slate-600 text-[11px]">Bank Balance After Payout</span>
                                        <span class="font-mono font-black text-xs shrink-0" :class="bankBalanceAfterPayout < 0 ? 'text-rose-600 font-black' : 'text-slate-800'" x-text="formatCurrency(bankBalanceAfterPayout)">Rs. 0</span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!selectedBankAccount">
                                <div class="text-slate-400 italic text-[11px]">Select a source bank account to calculate bank balance.</div>
                            </template>
                        </div>

                        {{-- Right Column: Broker Payable & Payout Details --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-slate-600 text-[11px]">Available Broker Balance</span>
                                <span class="font-mono font-extrabold text-emerald-600 text-xs shrink-0" x-text="formatCurrency(payoutModal.availablePayable)">Rs. 0</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-slate-600 text-[11px]">Payout Amount</span>
                                <span class="font-mono font-extrabold text-rose-500 text-xs shrink-0" x-text="formatCurrency(payoutModal.payoutAmount)">Rs. 0</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200/80 flex items-center justify-between gap-2">
                                <span class="font-black text-slate-900 uppercase tracking-wider text-[11px]">Broker Bal After Payout</span>
                                <span class="font-mono font-black text-slate-900 text-sm shrink-0" x-text="formatCurrency(brokerBalanceAfterPayout)">Rs. 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="payoutModal.open = false" 
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" 
                            :disabled="Boolean(modalErrorMessage) || !payoutModal.selectedBankId"
                            :class="Boolean(modalErrorMessage) || !payoutModal.selectedBankId ? 'opacity-50 cursor-not-allowed bg-slate-400 hover:bg-slate-400' : 'bg-[#a38c29] hover:bg-[#8a7522] cursor-pointer shadow-md'"
                            class="px-5 py-2 text-white text-xs font-black uppercase tracking-wider rounded-xl transition inline-flex items-center gap-2">
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
        modalBankOpen: false,
        modalBankSearch: '',

        get filteredModalBankAccounts() {
            const accounts = this.companyBankAccounts || [];
            if (!this.modalBankSearch || !this.modalBankSearch.trim()) {
                return accounts;
            }
            const q = this.modalBankSearch.toLowerCase().trim();
            return accounts.filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
        },

        payoutModal: {
            open: false,
            brokerId: null,
            commissionEntryId: null,
            brokerName: '',
            saleNumber: '',
            isBulk: false,
            commAmount: 0,
            paidAmount: 0,
            availablePayable: 0,
            payoutAmount: 0,
            selectedBankId: '',
        },
        openBulkPayout(brokerId, brokerName, payableAmount, totalPending) {
            const firstBankId = (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? String(this.companyBankAccounts[0].id) : '';
            const avail = Number(payableAmount > 0 ? payableAmount : (totalPending || 0));
            this.modalBankOpen = false;
            this.modalBankSearch = '';
            this.payoutModal = {
                open: true,
                brokerId: brokerId,
                commissionEntryId: null,
                brokerName: brokerName,
                saleNumber: '',
                isBulk: true,
                commAmount: avail,
                paidAmount: 0,
                availablePayable: avail,
                payoutAmount: avail,
                selectedBankId: firstBankId,
            };
        },
        openDealPayout(entryId, brokerName, saleNumber, commAmount, paidAmount, remainingPayable) {
            const firstBankId = (this.companyBankAccounts && this.companyBankAccounts.length > 0) ? String(this.companyBankAccounts[0].id) : '';
            const avail = Number(remainingPayable !== undefined ? remainingPayable : (commAmount - paidAmount));
            this.modalBankOpen = false;
            this.modalBankSearch = '';
            this.payoutModal = {
                open: true,
                brokerId: null,
                commissionEntryId: entryId,
                brokerName: brokerName,
                saleNumber: saleNumber,
                isBulk: false,
                commAmount: Number(commAmount || 0),
                paidAmount: Number(paidAmount || 0),
                availablePayable: avail,
                payoutAmount: avail,
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
            return this.selectedBankBalance - (this.payoutModal.payoutAmount || 0);
        },
        get brokerBalanceAfterPayout() {
            return this.payoutModal.availablePayable - (this.payoutModal.payoutAmount || 0);
        },
        get isBankInsufficient() {
            if (!this.selectedBankAccount) return false;
            return (this.payoutModal.payoutAmount || 0) > 0 && (this.payoutModal.payoutAmount || 0) > this.selectedBankBalance;
        },
        get modalErrorMessage() {
            if (!this.payoutModal.payoutAmount || this.payoutModal.payoutAmount <= 0) {
                return 'Please enter a valid payout amount greater than ₹0.00';
            }
            if (this.payoutModal.payoutAmount > this.payoutModal.availablePayable + 0.01) {
                return `Payout amount (${this.formatCurrency(this.payoutModal.payoutAmount)}) cannot exceed available remaining commission (${this.formatCurrency(this.payoutModal.availablePayable)}).`;
            }
            if (this.isBankInsufficient) {
                const bankName = this.selectedBankAccount ? this.selectedBankAccount.bank_name : 'selected bank';
                return `Insufficient Bank Funds! Payout amount (${this.formatCurrency(this.payoutModal.payoutAmount)}) exceeds available balance in ${bankName} (${this.formatCurrency(this.selectedBankBalance)}).`;
            }
            return '';
        },
        formatCurrency(val) {
            const n = Number(val || 0);
            return 'Rs. ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        numberToWords(val) {
            let num = Math.floor(parseFloat(val) || 0);
            if (!num || num <= 0) return '';
            const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            function toWords(n) {
                if (n < 20) return a[n];
                let digit = n % 10;
                return b[Math.floor(n / 10)] + (digit ? ' ' + a[digit] : '');
            }
            let str = '';
            let crore = Math.floor(num / 10000000);
            num %= 10000000;
            let lakh = Math.floor(num / 100000);
            num %= 100000;
            let thousand = Math.floor(num / 1000);
            num %= 1000;
            let hundred = Math.floor(num / 100);
            let rest = num % 100;
            if (crore > 0) str += toWords(crore) + ' Crore ';
            if (lakh > 0) str += toWords(lakh) + ' Lakh ';
            if (thousand > 0) str += toWords(thousand) + ' Thousand ';
            if (hundred > 0) str += toWords(hundred) + ' Hundred ';
            if (rest > 0) str += (str !== '' ? 'and ' : '') + toWords(rest) + ' ';
            return str.trim() + ' Rupees Only';
        },
        get selectedBankBalanceInWords() {
            return this.numberToWords(this.selectedBankBalance);
        },
        get payoutAmountInWords() {
            return this.numberToWords(this.payoutModal.payoutAmount);
        }
    };
}

function printCleanPDF() {
    const origTitle = document.title;
    document.title = '';
    window.print();
    setTimeout(() => {
        document.title = origTitle;
    }, 1000);
}
</script>

</x-erp-layout>
