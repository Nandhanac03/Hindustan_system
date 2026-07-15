<x-erp-layout>
    <x-slot:title>Entity Sub-Ledger</x-slot:title>
    <x-slot:headerTitle>Entity Sub-Ledger</x-slot:headerTitle>

    <div class="max-w-[1600px] mx-auto space-y-5">

        {{-- ═══ HEADER IDENTITY BAR ═══ --}}
        <div class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-violet-950 to-slate-900 rounded-2xl px-6 py-4 border border-violet-900/30 shadow-lg">
            <div>
                <p class="text-[9px] font-bold text-violet-400/70 uppercase tracking-[0.3em]">Core Accounting Engine</p>
                <h1 class="text-lg font-extrabold text-white tracking-wide">Entity Sub-Ledger</h1>
                <p class="text-[10px] text-slate-400 mt-0.5">Reusable generic ledger view — Broker, Customer, Supplier, Tax Account statements</p>
            </div>
            <div class="flex items-center gap-3">
                @if($selectedAccount)
                <div class="text-right">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Selected Entity</p>
                    <p class="text-sm font-bold text-violet-300">{{ $selectedAccount->name }}</p>
                    <p class="text-[9px] font-mono text-slate-500 mt-0.5">{{ $selectedAccount->code }} [{{ ucfirst($selectedAccount->type) }}]</p>
                </div>
                @endif
                <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ═══ ACCOUNT SEARCH BAR ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-5">
            <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Select Entity / Account Ledger</h2>
            <form method="GET" action="{{ route('vouchers.entity-ledger') }}" class="flex flex-wrap items-end gap-4">
                <div class="space-y-1.5 flex-[2] min-w-[260px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Account / Entity</label>
                    <select name="account_id" id="entitySelect"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-violet-400/20 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                        <option value="">— Select Account —</option>
                        @foreach($accounts->groupBy('type') as $type => $group)
                            <optgroup label="{{ strtoupper($type) }}">
                                @foreach($group as $acc)
                                    <option value="{{ $acc->id }}" {{ $selectedId == $acc->id ? 'selected' : '' }}>
                                        {{ $acc->name }} ({{ $acc->code }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-violet-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="space-y-1.5 flex-1 min-w-[160px]">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-violet-400/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition shadow-md shadow-[#a38c29]/25 uppercase tracking-wide">
                        View Ledger
                    </button>
                    <a href="{{ route('vouchers.entity-ledger') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition text-center uppercase tracking-wide">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if($selectedAccount)
        {{-- ═══ ENTITY PROFILE CARD ═══ --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-1 bg-gradient-to-br from-violet-900 to-slate-900 rounded-2xl border border-violet-800/30 p-5 flex flex-col justify-between">
                <div>
                    <p class="text-[9px] font-bold text-violet-400/70 uppercase tracking-widest mb-3">Entity Profile</p>
                    <h3 class="text-sm font-extrabold text-white leading-snug">{{ $selectedAccount->name }}</h3>
                    <p class="text-[10px] font-mono text-violet-400 mt-1">{{ $selectedAccount->code }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-violet-800/50">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] text-slate-500 uppercase tracking-widest">Account Type</span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-violet-500/20 text-violet-300">{{ ucfirst($selectedAccount->type) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[9px] text-slate-500 uppercase tracking-widest">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $selectedAccount->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $selectedAccount->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/60 p-5 flex flex-col justify-between">
                <p class="text-[9px] font-bold text-emerald-600/70 uppercase tracking-widest">Total Debits (DR)</p>
                <p class="text-2xl font-extrabold text-emerald-700 mt-2">₹{{ number_format($totalDebit, 2) }}</p>
                <p class="text-[9px] text-emerald-500 mt-1">{{ $entries->where('debit', '>', 0)->count() }} transactions</p>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100/50 rounded-2xl border border-red-200/60 p-5 flex flex-col justify-between">
                <p class="text-[9px] font-bold text-red-600/70 uppercase tracking-widest">Total Credits (CR)</p>
                <p class="text-2xl font-extrabold text-red-700 mt-2">₹{{ number_format($totalCredit, 2) }}</p>
                <p class="text-[9px] text-red-500 mt-1">{{ $entries->where('credit', '>', 0)->count() }} transactions</p>
            </div>

            <div class="bg-gradient-to-br from-violet-50 to-violet-100/50 rounded-2xl border border-violet-200/60 p-5 flex flex-col justify-between">
                <p class="text-[9px] font-bold text-violet-700/70 uppercase tracking-widest">Net Closing Balance</p>
                <p class="text-2xl font-extrabold {{ $balance >= 0 ? 'text-violet-700' : 'text-red-700' }} mt-2">
                    ₹{{ number_format(abs($balance), 2) }}
                </p>
                <p class="text-[9px] text-violet-500 mt-1">{{ $balance >= 0 ? 'Debit Balance' : 'Credit Balance' }}</p>
            </div>
        </div>

        {{-- ═══ TRANSACTION TABLE ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-violet-500"></div>
                <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">
                    Ledger Statement — {{ $selectedAccount->name }}
                </h2>
                <span class="ml-auto px-3 py-1 bg-violet-100 text-violet-700 text-[10px] font-bold rounded-full uppercase tracking-widest">
                    {{ $entries->count() }} Entries
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-900 text-slate-300">
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Date</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Particulars / Narration</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher Type</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-widest text-[10px]">Voucher No.</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-emerald-400">Debit (DR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-red-400">Credit (CR)</th>
                            <th class="px-4 py-3 text-right font-bold uppercase tracking-widest text-[10px] text-violet-300">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Opening Balance --}}
                        <tr class="bg-slate-50">
                            <td class="px-4 py-3 text-slate-500 font-mono text-[10px]">—</td>
                            <td class="px-4 py-3 font-bold text-slate-700 italic" colspan="3">Opening Balance</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-600">₹0.00</td>
                        </tr>

                        @forelse($entries as $entry)
                        @php
                            $typeColors = ['Receipt' => 'emerald', 'Payment' => 'red', 'Contra' => 'blue', 'Journal' => 'purple'];
                            $vType = optional($entry->voucher)->type ?? 'Journal';
                            $tc = $typeColors[$vType] ?? 'slate';
                        @endphp
                        <tr class="hover:bg-violet-50/30 transition-colors">
                            <td class="px-4 py-3 text-slate-500 font-mono text-[10px] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800 text-[11px]">
                                    {{ optional($entry->voucherLine)->line_narration ?: optional($entry->voucher)->narration ?: '—' }}
                                </p>
                                <p class="text-[9px] text-slate-400 mt-0.5">
                                    Voucher: {{ optional($entry->voucher)->voucher_number ?? '—' }}
                                </p>
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
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-sm font-semibold text-slate-400">No transactions found for this entity</p>
                                <p class="text-xs text-slate-400 mt-1">Adjust the date range or post vouchers against this account.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if($entries->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="4" class="px-4 py-4 font-extrabold uppercase tracking-widest text-[10px] text-violet-300">
                                CLOSING TOTALS
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-emerald-400 text-sm">
                                ₹{{ number_format($totalDebit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-red-400 text-sm">
                                ₹{{ number_format($totalCredit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-violet-300 text-sm">
                                ₹{{ number_format(abs($balance), 2) }}
                                <span class="text-[9px] text-violet-400">{{ $balance >= 0 ? 'DR' : 'CR' }}</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @else
        {{-- ═══ EMPTY STATE ═══ --}}
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-violet-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Select an Entity to View Its Ledger</h3>
            <p class="text-sm text-slate-400 max-w-md mx-auto">
                Use the dropdown above to select any ledger account — Customer, Broker, Supplier, Tax Account — and view the complete chronological transaction statement.
            </p>
        </div>
        @endif

    </div>
</x-erp-layout>
