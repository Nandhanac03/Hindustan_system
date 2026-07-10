<x-erp-layout title="Customer Ledger" headerTitle="Customer Running Ledger">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-semibold">
        <a href="{{ route('sales.index') }}" class="hover:text-primary transition-colors">Sales</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('emi-collections.outstanding') }}" class="hover:text-primary transition-colors">Outstanding</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-700">Ledger — {{ $sale->sale_number }}</span>
    </div>

    {{-- Sale Summary Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Customer</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block">{{ $sale->customer?->name ?? '—' }}</span>
                <span class="text-[10px] text-slate-400">{{ $sale->customer?->phone ?? '' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Number / Project</span>
                <span class="text-sm font-bold text-indigo-600 mt-1 block font-mono">{{ $sale->sale_number }}</span>
                <span class="text-[10px] text-slate-500">{{ $sale->project?->name ?? '—' }} — Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Amount (incl. GST)</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block font-mono">₹{{ number_format($sale->total_amount, 2) }}</span>
                <span class="text-[10px] text-slate-400">Agreement: {{ $sale->agreement_date?->format('d M Y') ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Remaining Balance</span>
                <span class="text-sm font-extrabold {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1 block font-mono">
                    ₹{{ number_format(abs($closingBalance), 2) }}
                    <span class="text-[9px] font-semibold">{{ $closingBalance > 0 ? '(Outstanding)' : '(Fully Paid)' }}</span>
                </span>
            </div>
        </div>
    </div>

    {{-- Ledger KPIs --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Value</span>
            <span class="text-xl font-extrabold text-slate-900 mt-1 block font-mono">₹{{ number_format($sale->total_amount, 2) }}</span>
            <span class="text-[9px] text-slate-400">Agreed sale price + GST</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Instalment Dues</span>
            <span class="text-xl font-extrabold text-rose-600 mt-1 block font-mono">₹{{ number_format($totalDebits, 2) }}</span>
            <span class="text-[9px] text-slate-400">Scheduled installments</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Receipts</span>
            <span class="text-xl font-extrabold text-emerald-600 mt-1 block font-mono">₹{{ number_format($totalCredits, 2) }}</span>
            <span class="text-[9px] text-slate-400">Payments received</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Net Outstanding</span>
            <span class="text-xl font-extrabold {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1 block font-mono">
                ₹{{ number_format(abs($closingBalance), 2) }}
            </span>
            <span class="text-[9px] text-slate-400">{{ $closingBalance > 0 ? 'Balance Due' : 'Settled' }}</span>
        </div>
    </div>

    {{-- Running Ledger Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Running Ledger Statement</h2>
                <p class="text-xs text-slate-400 mt-0.5">Chronological history of installment dues and receipt credits with running balance.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('emi-collections.receipts') }}?sale_id={{ $sale->id }}" class="text-[10px] font-bold text-primary hover:underline">↗ Add Receipt</a>
                <a href="{{ route('sales.index') }}" class="text-[10px] font-bold text-indigo-600 hover:underline">↗ Sales Register</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left">
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Date</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Description</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Debit (Due)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Credit (Paid)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Running Balance</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ledger as $row)
                    @php
                        $typeColors = [
                            'installment' => ['row' => ($row['status'] ?? '') === 'paid' ? 'bg-emerald-50/20' : (($row['status'] ?? '') === 'overdue' ? 'bg-rose-50/30' : ''), 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                            'receipt'     => ['row' => 'bg-emerald-50/20', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        ];
                        $cfg = $typeColors[$row['type']] ?? ['row' => '', 'badge' => 'bg-slate-100 text-slate-600 border-slate-200'];
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors {{ $cfg['row'] }}">
                        <td class="px-5 py-3 text-slate-500 text-[10px] font-mono">{{ $row['date'] }}</td>
                        <td class="px-5 py-3">
                            <div class="font-semibold text-slate-800">{{ $row['description'] }}</div>
                            @if(isset($row['status']) && $row['type'] === 'installment')
                                @php $sc = ['paid'=>'text-emerald-600','overdue'=>'text-rose-600','pending'=>'text-amber-600','partial'=>'text-blue-600']; @endphp
                                <span class="text-[9px] font-bold {{ $sc[$row['status']] ?? '' }} uppercase">{{ $row['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-mono {{ $row['debit'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-300' }}">
                            {{ $row['debit'] > 0 ? '₹' . number_format($row['debit'], 2) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-mono {{ $row['credit'] > 0 ? 'text-emerald-600 font-bold' : 'text-slate-300' }}">
                            {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold font-mono {{ $row['running_balance'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            ₹{{ number_format(abs($row['running_balance']), 2) }}
                            <span class="text-[8px] font-semibold">{{ $row['running_balance'] > 0 ? 'DR' : 'CR' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-[9px] font-bold border px-1.5 py-0.5 rounded {{ $cfg['badge'] }}">
                                {{ strtoupper($row['type']) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No transactions yet for this sale. Use "Add Receipt" to record the first payment.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200 font-bold">
                        <td colspan="2" class="px-5 py-3 text-[10px] text-slate-600 uppercase">Closing Totals</td>
                        <td class="px-5 py-3 text-right font-mono text-rose-700">₹{{ number_format($totalDebits, 2) }}</td>
                        <td class="px-5 py-3 text-right font-mono text-emerald-700">₹{{ number_format($totalCredits, 2) }}</td>
                        <td class="px-5 py-3 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            ₹{{ number_format(abs($closingBalance), 2) }} {{ $closingBalance > 0 ? 'DR' : 'CR' }}
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

</div>

</x-erp-layout>
