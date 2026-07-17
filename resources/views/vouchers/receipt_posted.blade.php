<x-erp-layout>
    <x-slot:title>Receipt Posted — {{ $voucher->voucher_number }} | HindustanERP</x-slot:title>
    <x-slot:headerTitle>Receipt Voucher Posted</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Success Banner --}}
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl p-6 shadow-md text-white flex items-center gap-6">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold uppercase tracking-wider">Receipt Processed Successfully!</h1>
                <p class="text-emerald-100 text-xs mt-1 font-medium">All allocations have been journalised and ledger entries created.</p>
            </div>
            <div class="ml-auto text-right">
                <div class="text-[10px] font-bold text-emerald-200 uppercase">Voucher No.</div>
                <div class="text-xl font-mono font-extrabold">{{ $voucher->voucher_number }}</div>
                <div class="text-[10px] text-emerald-200 font-medium">{{ $voucher->date ? \Carbon\Carbon::parse($voucher->date)->format('d/m/Y') : '' }}</div>
            </div>
        </div>

        {{-- Voucher Metadata Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-800 to-slate-900 flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-extrabold text-xs">4</div>
                <h2 class="text-xs font-extrabold text-white uppercase tracking-wider">Step 4: Ledger Logging — System Transaction Summary</h2>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6 border-b border-slate-100">
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</div>
                    <div class="mt-1 inline-flex px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-extrabold uppercase">{{ $voucher->status }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</div>
                    <div class="mt-1 text-sm font-bold text-slate-900">{{ $voucher->date ? \Carbon\Carbon::parse($voucher->date)->format('d M Y') : '—' }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payment Mode</div>
                    <div class="mt-1 text-sm font-bold text-slate-900">{{ $meta['payment_mode'] ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Split Active</div>
                    <div class="mt-1 text-sm font-bold text-slate-900">{{ !empty($meta['split_active']) ? 'Yes' : 'No' }}</div>
                </div>
            </div>

            @if($voucher->narration)
            <div class="px-6 py-3 border-b border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Narration</div>
                <div class="text-xs text-slate-700 font-medium">{{ $voucher->narration }}</div>
            </div>
            @endif

            {{-- Double-Entry Journal Lines --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <th class="px-6 py-3.5">#</th>
                            <th class="px-6 py-3.5">Ledger Account</th>
                            <th class="px-6 py-3.5">Narration</th>
                            <th class="px-6 py-3.5 text-right">Debit (DR)</th>
                            <th class="px-6 py-3.5 text-right">Credit (CR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                        @foreach($splitRows as $i => $row)
                        <tr class="{{ $row['debit'] > 0 ? 'bg-primary/5' : '' }} hover:bg-slate-50 transition">
                            <td class="px-6 py-3.5 text-slate-400 font-mono">{{ $i + 1 }}</td>
                            <td class="px-6 py-3.5 font-bold text-slate-900">{{ $row['account'] }}</td>
                            <td class="px-6 py-3.5 text-slate-500 font-medium">{{ $row['narration'] }}</td>
                            <td class="px-6 py-3.5 text-right font-mono font-bold {{ $row['debit'] > 0 ? 'text-rose-600' : 'text-slate-300' }}">
                                {{ $row['debit'] > 0 ? '₹' . number_format($row['debit'], 2) : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono font-bold {{ $row['credit'] > 0 ? 'text-emerald-700' : 'text-slate-300' }}">
                                {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold">
                            <td colspan="3" class="px-6 py-3.5 text-slate-700 uppercase">Total</td>
                            <td class="px-6 py-3.5 text-right font-mono text-rose-600">₹{{ number_format($totalIn, 2) }}</td>
                            <td class="px-6 py-3.5 text-right font-mono text-emerald-700">₹{{ number_format($totalOut, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Next Steps Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-4">What's Next</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('vouchers.receipt.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider bg-primary hover:bg-primary-700 text-white rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Receipt
                </a>
                <a href="{{ route('vouchers.ledger.index') }}"
                   class="flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider bg-slate-800 hover:bg-slate-900 text-white rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    View Ledger Directory
                </a>
                <a href="{{ route('vouchers.cash-book') }}"
                   class="flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 6h18M3 18h18"/></svg>
                    Cash Book / Passbook
                </a>
                <a href="{{ route('expenses.bills.index') }}"
                   class="flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Supplier Bills
                </a>
                <a href="{{ route('reports.index', ['report' => 'activity_statements']) }}"
                   class="flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    View Statements
                </a>
            </div>
        </div>

    </div>
</x-erp-layout>
