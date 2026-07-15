<x-erp-layout>
    <x-slot:title>General Ledger Directory</x-slot:title>
    <x-slot:headerTitle>Ledger Directory</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6">
        
        <!-- Filters Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-5">
            <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Filter Ledger Entries</h2>
            <form method="GET" action="{{ route('vouchers.ledger.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Account Filter -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Ledger Account</label>
                    <select name="account_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                        <option value="">-- All Accounts --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $selectedAccount == $acc->id ? 'selected' : '' }}>
                                {{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>

                <!-- End Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                        Filter
                    </button>
                    <a href="{{ route('vouchers.ledger.index') }}" class="px-4 py-2.5 border border-slate-250 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition text-center uppercase tracking-wide">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <!-- Ledger Ledger Listings Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <th class="px-5 py-4">Date</th>
                            <th class="px-5 py-4">Voucher No</th>
                            <th class="px-5 py-4">Type</th>
                            <th class="px-5 py-4">Particulars Ledger</th>
                            <th class="px-5 py-4 text-right">Debit (DR)</th>
                            <th class="px-5 py-4 text-right">Credit (CR)</th>
                            <th class="px-5 py-4 text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($entries as $entry)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3.5 font-sans font-medium text-slate-550">
                                    {{ $entry->date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 font-mono font-bold text-primary-750">
                                    {{ $entry->voucher->voucher_number }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider 
                                        @if($entry->voucher->type === 'Receipt') bg-emerald-50 text-emerald-800 border border-emerald-200
                                        @elseif($entry->voucher->type === 'Payment') bg-rose-50 text-rose-800 border border-rose-200
                                        @elseif($entry->voucher->type === 'Contra') bg-blue-50 text-blue-800 border border-blue-200
                                        @elseif($entry->voucher->type === 'Journal') bg-amber-50 text-amber-800 border border-amber-200
                                        @else bg-slate-50 text-slate-800 border border-slate-200 @endif">
                                        {{ $entry->voucher->type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800">{{ $entry->account->name }}</div>
                                    <div class="text-[9px] text-slate-400 uppercase font-mono tracking-wider">{{ $entry->account->code }} [{{ $entry->account->type }}]</div>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-semibold @if($entry->debit > 0) text-slate-900 @else text-slate-300 @endif">
                                    {{ $entry->debit > 0 ? '₹' . number_format((float)$entry->debit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-semibold @if($entry->credit > 0) text-slate-900 @else text-slate-300 @endif">
                                    {{ $entry->credit > 0 ? '₹' . number_format((float)$entry->credit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800">
                                    ₹{{ number_format((float)$entry->running_balance, 2, '.', ',') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400 font-semibold uppercase tracking-wider">
                                    No ledger transactions logged for the select filters.
                                </td>
                            </tr>
                        @endforelse

                        @if($entries->isNotEmpty())
                            @php
                                $totalDebit = $entries->sum('debit');
                                $totalCredit = $entries->sum('credit');
                            @endphp
                            <!-- TOTALS Row -->
                            <tr class="bg-slate-100/90 font-extrabold text-slate-900 border-t border-slate-350">
                                <td class="px-5 py-4 text-[10px] uppercase tracking-wider" colspan="4">TOTALS</td>
                                <td class="px-5 py-4 text-right font-mono">
                                    {{ $totalDebit > 0 ? '₹' . number_format((float)$totalDebit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right font-mono">
                                    {{ $totalCredit > 0 ? '₹' . number_format((float)$totalCredit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right font-mono text-slate-400">
                                    -
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-erp-layout>
