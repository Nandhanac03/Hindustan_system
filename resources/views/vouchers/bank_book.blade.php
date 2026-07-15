<x-erp-layout>
    <x-slot:title>Bank Book Register</x-slot:title>
    <x-slot:headerTitle>Bank Book</x-slot:headerTitle>

    <div class="max-w-[1600px] mx-auto space-y-5">

        {{-- ═══ HEADER IDENTITY BAR ═══ --}}
        <div class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-2xl px-6 py-4 border border-blue-900/30 shadow-lg">
            <div>
                <p class="text-[9px] font-bold text-blue-400/70 uppercase tracking-[0.3em]">Core Accounting Engine</p>
                <h1 class="text-lg font-extrabold text-white tracking-wide">Bank Book Register</h1>
                <p class="text-[10px] text-slate-400 mt-0.5">Corporate Bank Account — Statement View with GST Sub-Breakdown</p>
            </div>
            <div class="flex items-center gap-3">
                @if($selectedBank)
                <div class="text-right">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Active Account</p>
                    <p class="text-sm font-bold text-blue-300">{{ $selectedBank->name }}</p>
                    <p class="text-[9px] font-mono text-slate-500 mt-0.5">{{ $selectedBank->code }}</p>
                </div>
                @endif
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ═══ FILTER BAR ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-5">
            <form method="GET" action="{{ route('vouchers.bank-book') }}" class="flex flex-wrap items-end gap-4">
                <div class="space-y-1.5 flex-1 min-w-[200px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Bank Account</label>
                    <select name="bank_account_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-400/20 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}" {{ $selectedBankId == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition shadow-md shadow-[#a38c29]/25 uppercase tracking-wide">
                        Filter
                    </button>
                    <a href="{{ route('vouchers.bank-book') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition text-center uppercase tracking-wide">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ═══ SUMMARY STAT CARDS ═══ --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/60 p-5">
                <p class="text-[9px] font-bold text-emerald-600/70 uppercase tracking-widest mb-1">Total Deposits (DR)</p>
                <p class="text-2xl font-extrabold text-emerald-700">₹{{ number_format($totalDebit, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100/50 rounded-2xl border border-red-200/60 p-5">
                <p class="text-[9px] font-bold text-red-600/70 uppercase tracking-widest mb-1">Total Withdrawals (CR)</p>
                <p class="text-2xl font-extrabold text-red-700">₹{{ number_format($totalCredit, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl border border-blue-300/60 p-5">
                <p class="text-[9px] font-bold text-blue-700/70 uppercase tracking-widest mb-1">Closing Balance</p>
                <p class="text-2xl font-extrabold {{ $balance >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                    ₹{{ number_format(abs($balance), 2) }} {{ $balance < 0 ? 'CR' : 'DR' }}
                </p>
            </div>
        </div>

        {{-- ═══ BANK STATEMENT TABLE ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">
                        Statement — {{ $selectedBank->name ?? 'No Account Selected' }}
                    </h2>
                </div>
                <p class="text-[10px] text-slate-400 italic">Click any row to expand GST sub-breakdown</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-900 text-slate-300">
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px] w-8"></th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Date</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Particulars</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher Type</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher No.</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-emerald-400">Debit (DR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-red-400">Credit (CR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-blue-300">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Opening Balance Row --}}
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-[10px]">—</td>
                            <td class="px-4 py-3 font-bold text-slate-700 italic" colspan="2">Opening Balance</td>
                            <td class="px-4 py-3 text-slate-400">—</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-600">₹0.00</td>
                        </tr>

                        @forelse($entries as $i => $entry)
                        @php
                            $hasBreakdown = $entry->siblingLines && $entry->siblingLines->count() > 1;
                            $typeColors = ['Receipt' => 'emerald', 'Payment' => 'red', 'Contra' => 'blue', 'Journal' => 'purple'];
                            $vType = optional($entry->voucher)->type ?? 'Journal';
                            $tc = $typeColors[$vType] ?? 'slate';
                        @endphp
                        {{-- Main Entry Row --}}
                        <tr class="hover:bg-blue-50/30 transition-colors border-b border-slate-100 {{ $hasBreakdown ? 'cursor-pointer' : '' }}"
                            @if($hasBreakdown) onclick="toggleBreakdown('breakdown-{{ $i }}')" @endif>
                            <td class="px-4 py-3 text-center">
                                @if($hasBreakdown)
                                <svg id="chevron-{{ $i }}" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                @endif
                            </td>
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

                        {{-- ── GST Sub-Breakdown Rows (expandable) ── --}}
                        @if($hasBreakdown)
                        <tr id="breakdown-{{ $i }}" class="hidden">
                            <td colspan="8" class="p-0">
                                <div class="bg-slate-50/80 border-l-4 border-blue-400 mx-4 mb-3 mt-1 rounded-xl overflow-hidden">
                                    <div class="px-4 py-2 border-b border-slate-200 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Double-Entry Breakdown — {{ optional($entry->voucher)->voucher_number }}</p>
                                    </div>
                                    <table class="w-full text-[10px]">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-500">
                                                <th class="px-4 py-2 text-left font-bold uppercase tracking-widest text-[9px]">Account Head</th>
                                                <th class="px-4 py-2 text-left font-bold uppercase tracking-widest text-[9px]">Code</th>
                                                <th class="px-4 py-2 text-left font-bold uppercase tracking-widest text-[9px]">Type</th>
                                                <th class="px-4 py-2 text-right font-bold uppercase tracking-widest text-[9px] text-emerald-600">Debit</th>
                                                <th class="px-4 py-2 text-right font-bold uppercase tracking-widest text-[9px] text-red-500">Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($entry->siblingLines as $sLine)
                                            <tr class="hover:bg-blue-50/30">
                                                <td class="px-4 py-2 font-semibold text-slate-700">{{ optional($sLine->account)->name ?? '—' }}</td>
                                                <td class="px-4 py-2 font-mono text-slate-400 text-[9px]">{{ optional($sLine->account)->code ?? '—' }}</td>
                                                <td class="px-4 py-2 text-slate-500">{{ optional($sLine->account)->type ?? '—' }}</td>
                                                <td class="px-4 py-2 text-right font-semibold text-emerald-700">{{ $sLine->debit > 0 ? '₹'.number_format($sLine->debit, 2) : '—' }}</td>
                                                <td class="px-4 py-2 text-right font-semibold text-red-600">{{ $sLine->credit > 0 ? '₹'.number_format($sLine->credit, 2) : '—' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">No bank transactions found</p>
                                    <p class="text-xs text-slate-400">Select a bank account and date range to view entries.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    {{-- TOTALS FOOTER --}}
                    @if($entries->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="5" class="px-4 py-4 font-extrabold uppercase tracking-widest text-[10px] text-blue-300">
                                CLOSING TOTALS
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-emerald-400 text-sm">
                                ₹{{ number_format($totalDebit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-red-400 text-sm">
                                ₹{{ number_format($totalCredit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-blue-300 text-sm">
                                ₹{{ number_format(abs($balance), 2) }}
                                <span class="text-[9px] text-blue-400">{{ $balance >= 0 ? 'DR' : 'CR' }}</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>

    <script>
        function toggleBreakdown(id) {
            const row = document.getElementById(id);
            const idx = id.replace('breakdown-', '');
            const chevron = document.getElementById('chevron-' + idx);
            if (row) {
                row.classList.toggle('hidden');
                if (chevron) chevron.style.transform = row.classList.contains('hidden') ? '' : 'rotate(180deg)';
            }
        }
    </script>
</x-erp-layout>
