<x-erp-layout title="Customer Ledger" headerTitle="Customer Running Ledger">

<div class="max-w-[1800px] mx-auto space-y-3" x-data="ledgerApp()">

    {{-- Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-[11px] text-slate-400 font-semibold">
        <div class="flex items-center gap-2">
            <a href="{{ route('sales.index') }}" class="hover:text-[#a38c29] transition-colors">Sales</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('emi-collections.outstanding') }}" class="hover:text-[#a38c29] transition-colors">Outstanding</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700 font-bold">Ledger — {{ $sale->sale_number }}</span>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Select Customer / Unit:</span>
            <select onchange="window.location.href='/emi-collections/ledger/' + this.value" 
                    class="px-3.5 py-1.5 bg-white border border-slate-200/90 focus:ring-4 focus:ring-[#a38c29]/15 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all shadow-xs cursor-pointer">
                @foreach($allSales as $s)
                    <option value="{{ $s->id }}" {{ $s->id == $sale->id ? 'selected' : '' }}>
                        {{ $s->customer?->name ?? '—' }} — Unit: {{ $s->unit?->door_no ?? '—' }} ({{ $s->sale_number }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Hero Sale Summary Banner --}}
    <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 text-slate-900">
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-[#a38c29]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
            {{-- Customer Info --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 border border-[#a38c29]/20 text-[#a38c29] flex items-center justify-center font-extrabold text-sm shrink-0 shadow-xs">
                    {{ strtoupper(substr($sale->customer?->name ?? 'C', 0, 2)) }}
                </div>
                <div>
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Customer</span>
                    <h3 class="text-sm font-extrabold text-slate-900 mt-0.5 tracking-tight">{{ $sale->customer?->name ?? '—' }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold inline-flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $sale->customer?->phone ?? 'N/A' }}
                    </span>
                </div>
            </div>

            {{-- Sale Number & Project --}}
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Sale Number / Project</span>
                <span class="text-xs font-extrabold text-[#a38c29] font-mono mt-0.5 block tracking-wide">{{ $sale->sale_number }}</span>
                <span class="text-[10px] text-slate-600 font-semibold mt-0.5 block truncate" title="{{ $sale->project?->name ?? '—' }} — Unit: {{ $sale->unit?->door_no ?? '—' }}">
                    {{ $sale->project?->name ?? '—' }} — <span class="text-slate-900 font-bold">Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
                </span>
            </div>

            {{-- Sale Amount --}}
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Contract Value (incl. GST)</span>
                <span class="text-sm font-black text-slate-900 font-mono mt-0.5 block tracking-tight">₹{{ number_format($sale->total_amount, 2) }}</span>
                <span class="text-[10px] text-slate-500 font-semibold mt-0.5 block">Agreement: {{ $sale->agreement_date?->format('d M Y') ?? '—' }}</span>
            </div>

            {{-- Remaining Balance Status --}}
            <div class="lg:text-right">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Remaining Balance</span>
                <div class="mt-0.5 inline-flex items-center gap-2">
                    <span class="text-base font-black font-mono {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        ₹{{ number_format(abs($closingBalance), 2) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[8.5px] font-extrabold uppercase tracking-wider border {{ $closingBalance > 0 ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                        {{ $closingBalance > 0 ? 'Outstanding' : 'Fully Paid' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Executive Metric Cards (4 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Card 1: Sale Value --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 border-t-4 border-t-[#a38c29] shadow-sm p-3.5 sm:p-4 hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Sale Value</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block font-mono">₹{{ number_format($sale->total_amount, 2) }}</span>
                <span class="text-[9px] text-slate-400 font-medium mt-0.5 block">Agreed sale price + GST</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M13 12h.01M13 16h.01M17 12h.01M17 16h.01"/></svg>
            </div>
        </div>

        {{-- Card 2: Total Instalment Dues --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 border-t-4 border-t-rose-500 shadow-sm p-3.5 sm:p-4 hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Instalment Dues</span>
                <span class="text-lg font-black text-rose-600 mt-0.5 block font-mono">₹{{ number_format($totalDebits, 2) }}</span>
                <span class="text-[9px] text-slate-400 font-medium mt-0.5 block">Scheduled installments</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Card 3: Total Receipts Paid --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 border-t-4 border-t-emerald-500 shadow-sm p-3.5 sm:p-4 hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Receipts Paid</span>
                <span class="text-lg font-black text-emerald-600 mt-0.5 block font-mono">₹{{ number_format($totalCredits, 2) }}</span>
                <span class="text-[9px] text-slate-400 font-medium mt-0.5 block">Payments received</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Card 4: Net Outstanding --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 border-t-4 {{ $closingBalance > 0 ? 'border-t-rose-500 bg-rose-50/20' : 'border-t-emerald-500 bg-emerald-50/20' }} shadow-sm p-3.5 sm:p-4 hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Net Outstanding</span>
                <span class="text-lg font-black {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-0.5 block font-mono">
                    ₹{{ number_format(abs($closingBalance), 2) }}
                </span>
                <span class="text-[9px] font-bold {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-0.5 block">
                    {{ $closingBalance > 0 ? 'Balance Due' : 'Fully Settled' }}
                </span>
            </div>
            <div class="w-9 h-9 rounded-xl {{ $closingBalance > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 18h12l3-18H3zm12 11H9v-2h6v2zm1-4H8V9h9v4z"/></svg>
            </div>
        </div>
    </div>

    {{-- Running Ledger Table Container --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-10 bg-[#a38c29] rounded-full shrink-0"></div>
                <div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        Running Ledger Statement
                        <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 text-[9px] font-extrabold uppercase tracking-normal">Live Log</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Chronological history of installment dues and receipt credits with running balance.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                <button type="button" @click="openPayModal({{ $closingBalance }}, 'Outstanding Balance')" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white border border-[#a38c29] text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-sm hover:shadow inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Receipt
                </button>
                <a href="{{ route('sales.index') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/90 hover:border-slate-300 text-[10px] font-extrabold uppercase tracking-wider rounded-xl transition-all shadow-xs inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Sales Register
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white font-extrabold text-[9.5px] uppercase tracking-widest border-b border-[#8a7522]">
                        <th class="px-4 py-2.5">Date</th>
                        <th class="px-4 py-2.5">Description</th>
                        <th class="px-4 py-2.5 text-right">Debit (Due)</th>
                        <th class="px-4 py-2.5 text-right">Credit (Paid)</th>
                        <th class="px-4 py-2.5 text-right">Running Balance</th>
                        <th class="px-4 py-2.5 text-center">Type & Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                    @forelse($ledger as $row)
                    @if($row['type'] === 'receipt')
                        @continue
                    @endif
                    @php
                        $status = $row['status'] ?? '';
                        $rowStyle = match($status) {
                            'paid'    => 'bg-emerald-50/60 hover:bg-emerald-100/70',
                            'partial' => 'bg-amber-50/50 hover:bg-amber-100/60',
                            'overdue' => 'bg-rose-50/50 hover:bg-rose-100/60',
                            default   => 'bg-slate-50/50 hover:bg-slate-100/60',
                        };

                        $cfg = [
                            'row'   => $rowStyle,
                            'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ];
                    @endphp
                    <tr class="transition-colors border-b border-slate-100/80 {{ $cfg['row'] }}">
                        <td class="px-4 py-2.5 text-slate-500 text-[10px] font-mono whitespace-nowrap">{{ $row['date'] }}</td>
                        <td class="px-4 py-2.5">
                            <div class="font-extrabold text-slate-900 text-xs">{{ $row['description'] }}</div>
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs {{ ($row['status'] ?? '') === 'paid' ? 'text-emerald-600 font-extrabold' : ($row['debit'] > 0 ? 'text-rose-600 font-extrabold' : 'text-slate-300') }}">
                            {{ $row['debit'] > 0 ? '₹' . number_format($row['debit'], 2) : '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs {{ $row['credit'] > 0 ? 'text-emerald-600 font-extrabold' : 'text-slate-300' }}">
                            {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs font-black">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg border {{ ($row['status'] ?? '') === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($row['running_balance'] > 0 ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100') }}">
                                ₹{{ number_format(abs($row['running_balance']), 2) }}
                                <span class="text-[8px] font-extrabold uppercase">{{ $row['running_balance'] > 0 ? 'DR' : 'CR' }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 align-middle text-center">
                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                {{-- Type Badge --}}
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md border {{ $cfg['badge'] }}">
                                    {{ strtoupper($row['type']) }}
                                </span>

                                {{-- Installment Status / Button --}}
                                @if(isset($row['status']) && $row['type'] === 'installment')
                                    @if($row['status'] === 'paid' || round($row['debit'] ?? 0, 2) <= 0.01)
                                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            Paid
                                        </span>
                                        @if(isset($row['receipt_ids']) && count($row['receipt_ids']) > 0)
                                            <button type="button" @click.stop="openReceiptModal('{{ collect($row['receipt_ids'])->last() }}')" title="View Receipt"
                                                    class="inline-flex items-center justify-center p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition-all shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        @elseif($sale->receipts->count() > 0)
                                            <button type="button" @click.stop="openReceiptModal('{{ $sale->receipts->last()->id }}')" title="View Receipt"
                                                    class="inline-flex items-center justify-center p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition-all shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        @endif
                                    @elseif($row['status'] === 'partial')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-amber-100 text-amber-700 border border-amber-200">
                                            Partial
                                        </span>
                                        @if(isset($row['receipt_ids']) && count($row['receipt_ids']) > 0)
                                            <button type="button" @click.stop="openReceiptModal('{{ collect($row['receipt_ids'])->last() }}')"
                                                    class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 text-[9px] font-bold uppercase rounded-lg transition-all whitespace-nowrap shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Receipt
                                            </button>
                                        @endif
                                        <button
                                            @click.stop="openPayModal('{{ $row['balance'] ?? (($row['debit'] ?? 0) - ($row['credit'] ?? 0)) }}', '{{ addslashes($row['description']) }}')"
                                            type="button"
                                            class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap">
                                            Pay Remaining
                                        </button>
                                    @elseif($row['status'] === 'overdue')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-rose-100 text-rose-700 border border-rose-200">
                                            Overdue
                                        </span>
                                        <button
                                            @click.stop="openPayModal('{{ $row['balance'] ?? (($row['debit'] ?? 0) - ($row['credit'] ?? 0)) }}', '{{ addslashes($row['description']) }}')"
                                            type="button"
                                            class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap">
                                            Pay Installment
                                        </button>
                                    @else
                                        <button
                                            @click.stop="openPayModal('{{ $row['balance'] ?? (($row['debit'] ?? 0) - ($row['credit'] ?? 0)) }}', '{{ addslashes($row['description']) }}')"
                                            type="button"
                                            class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap">
                                            Pay Installment
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No transactions yet for this sale. Use "Add Receipt" to record the first payment.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-900 text-white font-extrabold text-xs border-t-2 border-[#a38c29]">
                        <td colspan="2" class="px-5 py-4 text-[10px] text-slate-300 uppercase tracking-widest">Closing Totals</td>
                        <td class="px-5 py-4 text-right font-mono text-rose-400">₹{{ number_format($totalDebits, 2) }}</td>
                        <td class="px-5 py-4 text-right font-mono text-emerald-400">₹{{ number_format($totalCredits, 2) }}</td>
                        <td class="px-5 py-4 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                            ₹{{ number_format(abs($closingBalance), 2) }} <span class="text-[9px] font-extrabold uppercase text-slate-400">{{ $closingBalance > 0 ? 'DR' : 'CR' }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Back Links --}}
    <div class="flex gap-4 text-xs">
        <a href="{{ route('emi-collections.outstanding') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Outstanding Summary</a>
        <a href="{{ route('sales.index') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Sales Register</a>
    </div>

    {{-- Direct Pay Installment Modal --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="modalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Installment Payment</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Payment for <span x-text="form.label"></span></h2>
                      </div>
                      <button type="button" @click="modalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <div x-show="error" class="p-4 mx-6 mt-4 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide" x-text="error"></div>

              <form @submit.prevent="submitPayment()" novalidate>
                  <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                      <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount to Collect (₹) <span class="text-rose-500">*</span></label>
                              <input type="number" step="0.01" x-model.number="form.amount"
                                     @input="if(errors.amount) delete errors.amount;"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-sm"
                                     :class="errors.amount ? 'border-rose-500 bg-rose-50/20' : ''">
                              <template x-if="errors.amount">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.amount) ? errors.amount[0] : errors.amount"></span>
                              </template>
                              <template x-if="form.amount && amountInWords(form.amount)">
                                  <span class="text-[10px] text-[#a38c29] font-extrabold block mt-1 uppercase tracking-wide" x-text="amountInWords(form.amount)"></span>
                              </template>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date <span class="text-rose-500">*</span></label>
                              <input type="date" x-model="form.receipt_date"
                                     @input="if(errors.receipt_date) delete errors.receipt_date;"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all shadow-sm"
                                     :class="errors.receipt_date ? 'border-rose-500 bg-rose-50/20' : ''">
                              <template x-if="errors.receipt_date">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.receipt_date) ? errors.receipt_date[0] : errors.receipt_date"></span>
                              </template>
                          </div>

                          <div class="space-y-2">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode <span class="text-rose-500">*</span></label>
                              <div class="grid grid-cols-2 gap-2">
                                  <template x-for="mode in ['Cash', 'Cheque', 'Bank Transfer', 'Online']" :key="mode">
                                      <button type="button" @click="form.payment_mode = mode; if(errors.payment_mode) delete errors.payment_mode;"
                                              :class="form.payment_mode === mode ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-sm shadow-[#a38c29]/20' : 'bg-slate-50 text-slate-600 border-slate-250 hover:border-[#a38c29]/40'"
                                              class="px-3 py-2 border rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all"
                                              x-text="mode">
                                      </button>
                                  </template>
                              </div>
                              <template x-if="errors.payment_mode">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.payment_mode) ? errors.payment_mode[0] : errors.payment_mode"></span>
                              </template>
                          </div>
                      </div>

                      <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                          <div class="grid grid-cols-2 gap-4">
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Ref / Cheque No.</label>
                                  <input type="text" x-model="form.reference_no" placeholder="Optional"
                                         class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all shadow-sm">
                              </div>
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Bank Name</label>
                                  <select x-model="form.bank_name"
                                          class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm">
                                      <option value="">-- Optional --</option>
                                      @foreach($banks as $bank)
                                      <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Remarks</label>
                              <textarea x-model="form.remarks" rows="2"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all resize-none shadow-sm"></textarea>
                          </div>
                      </div>
                  </div>

                  <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                      <button type="button" @click="modalOpen = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                          Cancel
                      </button>
                      <button type="submit" x-bind:disabled="submitting"
                              class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition-all uppercase tracking-wider shadow-md flex items-center justify-center gap-2">
                          <span x-text="submitting ? 'Recording...' : 'Collect Payment'"></span>
                      </button>
                  </div>
              </form>
         </div>
    </div>

    {{-- Manage EMI Schedule Modal --}}
    <div x-show="emiModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="emiModalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Schedule Editor</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Custom EMI Schedule Breakdown</h2>
                      </div>
                      <button type="button" @click="emiModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <div x-show="emiError" class="p-4 mx-6 mt-4 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide" x-text="emiError"></div>

              <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                  {{-- Dynamic Summary Stats --}}
                  <div class="grid grid-cols-3 gap-3 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-4 rounded-xl text-xs text-white border border-[#a38c29]/20">
                      <div>
                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Sale Amount</span>
                          <strong class="text-white text-sm font-mono mt-0.5 block">₹<span x-text="totalSaleAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                      </div>
                      <div>
                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Allocated Schedule</span>
                          <strong class="text-[#d9bf3b] text-sm font-mono mt-0.5 block">₹<span x-text="calculateTotalAllocated().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                      </div>
                      <div>
                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Unallocated Balance</span>
                          <div class="flex items-center gap-1.5 mt-0.5">
                              <strong class="text-sm font-mono" :class="calculateUnallocated() === 0 ? 'text-emerald-400' : 'text-rose-400'">
                                  ₹<span x-text="calculateUnallocated().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                              </strong>
                              <template x-if="calculateUnallocated() !== 0">
                                  <button type="button" @click="distributeRemaining()" class="px-1.5 py-0.5 bg-[#a38c29]/20 hover:bg-[#a38c29]/40 text-[#d9bf3b] text-[8px] font-bold uppercase rounded transition-colors border border-[#a38c29]/30">
                                      Auto-Distribute
                                  </button>
                              </template>
                          </div>
                      </div>
                  </div>

                  {{-- Scrollable List of Rows --}}
                  <div class="space-y-2.5 pr-1 max-h-[300px] overflow-y-auto">
                      <template x-for="(inst, index) in editInstallments" :key="index">
                          <div class="flex items-center gap-3 p-3 rounded-xl border transition-all bg-white border-slate-200/85 shadow-sm"
                               :class="inst.status === 'paid' ? 'bg-emerald-50/20 border-emerald-100 shadow-none' : ''">
                              
                              {{-- Label Input --}}
                              <div class="w-1/4 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Label</span>
                                  <input type="text" x-model="inst.label" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Due Date Input --}}
                              <div class="w-1/4 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Due Date</span>
                                  <input type="date" x-model="inst.due_date" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Amount Input --}}
                              <div class="w-1/3 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Amount (₹)</span>
                                  <input type="number" step="0.01" min="0" x-model.number="inst.amount" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Status & Remove --}}
                              <div class="w-1/6 flex items-center justify-end gap-2 pt-3">
                                  <template x-if="inst.status === 'paid'">
                                      <span class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold uppercase rounded">Paid</span>
                                  </template>
                                  <template x-if="inst.status !== 'paid'">
                                      <button type="button" @click="removeInstallment(index)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Row">
                                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                          </svg>
                                      </button>
                                  </template>
                              </div>
                          </div>
                      </template>
                  </div>
              </div>

              {{-- Footer Actions --}}
              <div class="px-6 py-4 border-t border-slate-200 flex justify-between items-center bg-slate-50">
                  <button type="button" @click="addInstallment()"
                          class="px-4 py-2 bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider flex items-center gap-1.5 border border-slate-200">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                      Add Installment
                  </button>
                  <div class="flex gap-2">
                      <button type="button" @click="emiModalOpen = false" 
                              class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                          Cancel
                      </button>
                      <button type="button" @click="submitEmiSchedule()" x-bind:disabled="emiSubmitting"
                              class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md flex items-center gap-1.5">
                          <span x-text="emiSubmitting ? 'Saving...' : 'Save Schedule'"></span>
                      </button>
                  </div>
              </div>
         </div>
    </div>

    {{-- View Receipt Modal --}}
    <div x-show="receiptModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="receiptModalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Receipt Log</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Receipt Details</h2>
                      </div>
                      <button type="button" @click="receiptModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <template x-if="viewReceiptData">
                  <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                      <div class="grid grid-cols-2 gap-4 bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Date</span>
                              <strong class="text-slate-800 text-xs font-bold block mt-1" x-text="formatDate(viewReceiptData.receipt_date)"></strong>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Amount Paid</span>
                              <strong class="text-emerald-700 text-sm font-mono font-extrabold block mt-1">₹<span x-text="Number(viewReceiptData.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                          </div>
                      </div>

                      <div class="grid grid-cols-2 gap-4 bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</span>
                              <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-1 bg-slate-100 text-slate-600 border border-slate-200" x-text="viewReceiptData.payment_mode"></span>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Reference / Transaction ID</span>
                              <span class="text-slate-800 text-xs font-mono font-bold mt-1 block" x-text="viewReceiptData.reference_no || 'N/A'"></span>
                          </div>
                      </div>
                      
                      <template x-if="viewReceiptData.remarks">
                          <div class="p-5 rounded-xl border border-slate-200/80 bg-[#a38c29]/5 shadow-sm">
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Remarks</span>
                              <p class="text-slate-600 text-xs bg-amber-50/50 p-3 rounded-lg border border-amber-100/50 italic font-medium" x-text="viewReceiptData.remarks"></p>
                          </div>
                      </template>
                  </div>
              </template>

              <template x-if="!viewReceiptData">
                  <div class="p-6 text-center text-xs text-slate-500 italic">
                      No receipt details found for this transaction.
                  </div>
              </template>
              
              <div class="px-6 py-4 border-t border-slate-200 flex justify-end bg-slate-50">
                  <button type="button" @click="receiptModalOpen = false" 
                          class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                      Close
                  </button>
              </div>
         </div>
    </div>

 </div>

<script>
function ledgerApp() {
    return {
        modalOpen: false,
        submitting: false,
        error: '',
        errors: {},
        form: {
            sale_id: '{{ $sale->id }}',
            amount: 0,
            receipt_date: new Date().toISOString().split('T')[0],
            payment_mode: 'Cash',
            reference_no: '',
            bank_name: '',
            partner_id: '',
            remarks: '',
            label: ''
        },
        openPayModal(amount, label) {
            this.error = '';
            this.errors = {};
            this.form.amount = amount;
            this.form.label = label;
            this.form.receipt_date = new Date().toISOString().split('T')[0];
            this.form.payment_mode = 'Cash';
            this.form.reference_no = '';
            this.form.bank_name = '';
            this.form.partner_id = '';
            this.form.remarks = '';
            this.modalOpen = true;
        },
        async submitPayment() {
            this.error = '';
            this.errors = {};
            let hasError = false;

            if (this.form.amount === '' || this.form.amount === null || this.form.amount === undefined || parseFloat(this.form.amount) <= 0 || isNaN(parseFloat(this.form.amount))) {
                this.errors.amount = ['please enter amount'];
                hasError = true;
            }
            if (!this.form.receipt_date) {
                this.errors.receipt_date = ['please select receipt date'];
                hasError = true;
            }
            if (!this.form.payment_mode) {
                this.errors.payment_mode = ['please select payment mode'];
                hasError = true;
            }

            if (hasError) {
                return;
            }

            this.submitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.form, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.modalOpen = false;
                    window.location.reload();
                } else if (json.errors) {
                    this.errors = json.errors;
                } else {
                    this.error = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.submitting = false;
            }
        },

        // Custom EMI schedule editor state
        originalInstallments: @json($installments),
        editInstallments: [],
        emiModalOpen: false,
        totalSaleAmount: {{ $sale->total_amount }},
        emiSubmitting: false,
        emiError: '',
        
        // View Receipt Modal State
        receiptsData: @json($sale->receipts),
        receiptModalOpen: false,
        viewReceiptId: null,
        get viewReceiptData() {
            if (!this.viewReceiptId) return null;
            return this.receiptsData.find(r => r.id == this.viewReceiptId) || null;
        },
        formatDate(dateStr) {
            if (!dateStr) return '—';
            try {
                const isoStr = String(dateStr).replace(' ', 'T');
                const d = new Date(isoStr);
                if (isNaN(d.getTime())) return String(dateStr);
                return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            } catch(e) {
                return String(dateStr);
            }
        },
        openReceiptModal(id) {
            console.log("Opening receipt modal for ID:", id);
            this.viewReceiptId = id;
            if ((!this.viewReceiptData || !id) && this.receiptsData && this.receiptsData.length > 0) {
                this.viewReceiptId = this.receiptsData[this.receiptsData.length - 1].id;
            }
            this.receiptModalOpen = true;
        },

        openEmiModal() {
            this.emiError = '';
            this.editInstallments = this.originalInstallments.map(inst => ({
                id: inst.id,
                installment_no: inst.installment_no,
                label: inst.label,
                due_date: inst.due_date ? inst.due_date.split('T')[0] : '',
                amount: Number(inst.amount),
                status: inst.status
            }));
            this.emiModalOpen = true;
        },
        addInstallment() {
            const nextNo = this.editInstallments.length > 0 
                ? Math.max(...this.editInstallments.map(i => i.installment_no)) + 1 
                : 1;
            
            let lastDate = new Date();
            if (this.editInstallments.length > 0) {
                const dates = this.editInstallments.map(i => i.due_date).filter(Boolean);
                if (dates.length > 0) {
                    lastDate = new Date(dates[dates.length - 1]);
                    lastDate.setMonth(lastDate.getMonth() + 1);
                }
            }
            
            this.editInstallments.push({
                installment_no: nextNo,
                label: 'EMI ' + nextNo,
                due_date: lastDate.toISOString().split('T')[0],
                amount: 0,
                status: 'pending'
            });
        },
        removeInstallment(index) {
            if (this.editInstallments[index].status === 'paid') return;
            this.editInstallments.splice(index, 1);
            this.editInstallments.forEach((inst, idx) => {
                if (inst.installment_no > 0) {
                    inst.installment_no = idx;
                }
            });
        },
        calculateTotalAllocated() {
            return this.editInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0);
        },
        calculateUnallocated() {
            return Math.round((this.totalSaleAmount - this.calculateTotalAllocated()) * 100) / 100;
        },
        distributeRemaining() {
            const unallocated = this.calculateUnallocated();
            const pendingInsts = this.editInstallments.filter(inst => inst.status === 'pending');
            if (pendingInsts.length === 0) {
                this.emiError = 'No pending installments to distribute balance to.';
                return;
            }
            
            const perInstallment = Math.round((unallocated / pendingInsts.length) * 100) / 100;
            pendingInsts.forEach((inst, idx) => {
                if (idx === pendingInsts.length - 1) {
                    const allocatedSoFar = perInstallment * (pendingInsts.length - 1);
                    inst.amount = Math.round((Number(inst.amount) + (unallocated - allocatedSoFar)) * 100) / 100;
                } else {
                    inst.amount = Math.round((Number(inst.amount) + perInstallment) * 100) / 100;
                }
            });
        },
        async submitEmiSchedule() {
            this.emiError = '';
            const unallocated = this.calculateUnallocated();
            if (Math.abs(unallocated) > 0.01) {
                this.emiError = `Unallocated balance must be 0 (current: ₹${unallocated.toLocaleString('en-IN')}).`;
                return;
            }
            this.emiSubmitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.schedules.bulk-update', $sale->id) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ installments: this.editInstallments, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.emiModalOpen = false;
                    window.location.reload();
                } else {
                    this.emiError = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.emiError = 'Request failed: ' + e.message;
            } finally {
                this.emiSubmitting = false;
            }
        },

        amountInWords(amount) {
            if (!amount || isNaN(amount) || parseFloat(amount) <= 0) return '';
            const num = Math.floor(parseFloat(amount));
            const paise = Math.round((parseFloat(amount) - num) * 100);

            const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                           'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            function convert(n) {
                if (n < 20) return units[n];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + units[n % 10] : '');
                if (n < 1000) return units[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + convert(n % 100) : '');
                if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + convert(n % 1000) : '');
                if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + convert(n % 100000) : '');
                return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + convert(n % 10000000) : '');
            }

            let words = convert(num);
            if (!words) return '';
            let result = 'IN WORDS: ' + words.toUpperCase() + ' RUPEES';
            if (paise > 0) {
                result += ' AND ' + convert(paise).toUpperCase() + ' PAISE';
            }
            result += ' ONLY';
            return result;
        }
    };
}
</script>

</x-erp-layout>
