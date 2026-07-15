<x-erp-layout>
    <x-slot:title>Cash Book Register</x-slot:title>
    <x-slot:headerTitle>Cash Book</x-slot:headerTitle>

    <div class="max-w-[1600px] mx-auto space-y-5">

        {{-- ═══ HEADER IDENTITY BAR ═══ --}}
        <div class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-2xl px-6 py-4 border border-slate-700/50 shadow-lg">
            <div>
                <p class="text-[9px] font-bold text-amber-400/70 uppercase tracking-[0.3em]">Core Accounting Engine</p>
                <h1 class="text-lg font-extrabold text-white tracking-wide">Cash Book Register</h1>
                <p class="text-[10px] text-slate-400 mt-0.5">Cash-in-Hand Account &mdash; Office Safe Chronological Statement</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Account Code</p>
                    <p class="text-sm font-bold text-amber-400 font-mono">CASH-HAND</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ═══ FILTER BAR ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-5">
            <form method="GET" action="{{ route('vouchers.cash-book') }}" class="flex flex-wrap items-end gap-4">
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition shadow-md shadow-[#a38c29]/25 uppercase tracking-wide">
                        Filter
                    </button>
                    <a href="{{ route('vouchers.cash-book') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition text-center uppercase tracking-wide">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ═══ SUMMARY STAT CARDS ═══ --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/60 p-5">
                <p class="text-[9px] font-bold text-emerald-600/70 uppercase tracking-widest mb-1">Total Cash In (DR)</p>
                <p class="text-2xl font-extrabold text-emerald-700">₹{{ number_format($totalDebit, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100/50 rounded-2xl border border-red-200/60 p-5">
                <p class="text-[9px] font-bold text-red-600/70 uppercase tracking-widest mb-1">Total Cash Out (CR)</p>
                <p class="text-2xl font-extrabold text-red-700">₹{{ number_format($totalCredit, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl border border-amber-300/60 p-5">
                <p class="text-[9px] font-bold text-amber-700/70 uppercase tracking-widest mb-1">Closing Balance</p>
                <p class="text-2xl font-extrabold {{ $balance >= 0 ? 'text-amber-700' : 'text-red-700' }}">
                    ₹{{ number_format(abs($balance), 2) }} {{ $balance < 0 ? 'CR' : 'DR' }}
                </p>
            </div>
        </div>

        {{-- ═══ LEDGER TABLE ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Transaction History — Cash-in-Hand</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-900 text-slate-300">
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Date</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Particulars</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher Type</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher No.</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-emerald-400">Debit (DR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-red-400">Credit (CR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-amber-400">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Opening Balance Row --}}
                        <tr class="bg-slate-50">
                            <td class="px-4 py-3 text-slate-500 font-mono text-[10px]">—</td>
                            <td class="px-4 py-3 font-bold text-slate-700 italic" colspan="3">Opening Balance</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-600">₹0.00</td>
                        </tr>

                        @forelse($entries as $entry)
                        <tr class="hover:bg-amber-50/40 transition-colors group">
                            <td class="px-4 py-3 text-slate-500 font-mono text-[10px] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800 text-[11px]">
                                    {{ optional($entry->voucherLine)->line_narration ?: optional($entry->voucher)->narration ?: '—' }}
                                </p>
                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $entry->account->code ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $typeColors = ['Receipt' => 'emerald', 'Payment' => 'red', 'Contra' => 'blue', 'Journal' => 'purple'];
                                    $vType = optional($entry->voucher)->type ?? 'Journal';
                                    $tc = $typeColors[$vType] ?? 'slate';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-{{ $tc }}-100 text-{{ $tc }}-700">
                                    {{ $vType }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-[10px] text-slate-600">
                                {{ optional($entry->voucher)->voucher_number ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-700">
                                {{ $entry->debit > 0 ? '₹'.number_format($entry->debit, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600">
                                {{ $entry->credit > 0 ? '₹'.number_format($entry->credit, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold {{ $entry->running_balance >= 0 ? 'text-slate-800' : 'text-red-600' }}">
                                ₹{{ number_format(abs($entry->running_balance), 2) }}
                                <span class="text-[9px] font-normal text-slate-400">{{ $entry->running_balance >= 0 ? 'DR' : 'CR' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">No cash transactions found</p>
                                    <p class="text-xs text-slate-400">Post a receipt or payment voucher against Cash-in-Hand to see entries here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    {{-- ═══ TOTALS FOOTER ═══ --}}
                    @if($entries->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="4" class="px-4 py-4 font-extrabold uppercase tracking-widest text-[10px] text-amber-400">
                                CLOSING TOTALS
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-emerald-400 text-sm">
                                ₹{{ number_format($totalDebit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-red-400 text-sm">
                                ₹{{ number_format($totalCredit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-amber-400 text-sm">
                                ₹{{ number_format(abs($balance), 2) }}
                                <span class="text-[9px] text-amber-300">{{ $balance >= 0 ? 'DR' : 'CR' }}</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>
</x-erp-layout>
