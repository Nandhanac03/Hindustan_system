<x-erp-layout>
    <x-slot:title>General Ledger Directory - Categorized by Type</x-slot:title>
    <x-slot:headerTitle>Ledger Directory</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6">

        {{-- Page Title / Breadcrumb --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                    <span>›</span>
                    <a href="#" class="hover:text-slate-600 transition">Vouchers</a>
                    <span>›</span>
                    <span class="text-slate-800 font-bold">Ledger Directory</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-sans">General Ledger Directory</h1>
                <p class="text-xs text-slate-400 mt-0.5">Categorized double-entry accounting ledger table — filtered by voucher & payment type.</p>
            </div>
        </div>

        @php
            $totals = $categoryTotals ?? [
                'Receipt'  => 0,
                'Payment'  => 0,
                'Contra'   => 0,
                'Journal'  => 0,
                'Sales'    => 0,
                'Purchase' => 0,
            ];
            $activeType = $selectedVoucherType ?? '';

            $tabs = [
                'Receipt'  => ['label' => 'Receipt Vouchers', 'sub' => 'Customer Collections', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-500', 'ring' => 'ring-emerald-500/20'],
                'Payment'  => ['label' => 'Vendor Payouts',   'sub' => 'Expense & Supplier',   'dot' => 'bg-rose-500',    'text' => 'text-rose-700',    'bg' => 'bg-rose-50',    'border' => 'border-rose-500',    'ring' => 'ring-rose-500/20'],
                'Contra'   => ['label' => 'Contra Transfers', 'sub' => 'Bank / Cash Moves',    'dot' => 'bg-blue-500',    'text' => 'text-blue-700',    'bg' => 'bg-blue-50',    'border' => 'border-blue-500',    'ring' => 'ring-blue-500/20'],
                'Journal'  => ['label' => 'Journal Entries',  'sub' => 'Book Adjustments',     'dot' => 'bg-amber-500',   'text' => 'text-amber-700',   'bg' => 'bg-amber-50',   'border' => 'border-amber-500',   'ring' => 'ring-amber-500/20'],
                'Sales'    => ['label' => 'Sales Invoices',   'sub' => 'Customer Billing',     'dot' => 'bg-purple-500',  'text' => 'text-purple-700',  'bg' => 'bg-purple-50',  'border' => 'border-purple-500',  'ring' => 'ring-purple-500/20'],
                'Purchase' => ['label' => 'Purchase Bills',   'sub' => 'Vendor Invoices',      'dot' => 'bg-indigo-500',  'text' => 'text-indigo-700',  'bg' => 'bg-indigo-50',  'border' => 'border-indigo-500',  'ring' => 'ring-indigo-500/20'],
            ];
        @endphp

        <!-- Filters Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-5">
            <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Filter & Categorize Ledger Entries</h2>
            <form id="filterForm" method="GET" action="{{ route('vouchers.ledger.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                <!-- Voucher Type Filter -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payment / Voucher Type</label>
                    <select name="voucher_type" id="voucher_type"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-extrabold cursor-pointer focus:outline-none transition-all">
                        <option value="">-- All Voucher Types --</option>
                        <option value="Receipt"  {{ $activeType === 'Receipt'  ? 'selected' : '' }}>Receipt Vouchers (Collections)</option>
                        <option value="Payment"  {{ $activeType === 'Payment'  ? 'selected' : '' }}>Payment Vouchers (Vendor Payouts)</option>
                        <option value="Contra"   {{ $activeType === 'Contra'   ? 'selected' : '' }}>Contra Transfers (Bank/Cash)</option>
                        <option value="Journal"  {{ $activeType === 'Journal'  ? 'selected' : '' }}>Journal Entries (Adjustments)</option>
                        <option value="Sales"    {{ $activeType === 'Sales'    ? 'selected' : '' }}>Sales Invoices (Revenue)</option>
                        <option value="Purchase" {{ $activeType === 'Purchase' ? 'selected' : '' }}>Purchase Bills (Vendor Invoices)</option>
                    </select>
                </div>

                <!-- Account Filter -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Ledger Account</label>
                    <select name="account_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                        <option value="">-- All Accounts --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ ($selectedAccount ?? '') == $acc->id ? 'selected' : '' }}>
                                {{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
                </div>

                <!-- End Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer">
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

        {{-- ── CATEGORY TAB BOXES ────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">

            {{-- All Tab Box --}}
            @php $isAll = !$activeType; @endphp
            <button type="button" onclick="document.getElementById('voucher_type').value = ''; document.getElementById('filterForm').submit();"
               class="text-left p-3.5 rounded-2xl border transition-all duration-200 block space-y-1 group cursor-pointer
                      {{ $isAll
                         ? 'bg-slate-900 border-slate-900 text-white shadow-md ring-2 ring-slate-900/20'
                         : 'bg-white border-slate-200/80 hover:border-slate-400 hover:shadow-xs' }}">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest {{ $isAll ? 'text-white' : 'text-slate-500' }}">
                    <span>All Entries</span>
                    <span class="w-2 h-2 rounded-full {{ $isAll ? 'bg-white' : 'bg-slate-400' }}"></span>
                </div>
                <div class="text-base font-black font-mono {{ $isAll ? 'text-white' : 'text-slate-900' }}">
                    {{ count($entries) }} <span class="text-[10px] font-normal opacity-70">rows</span>
                </div>
                <div class="text-[10px] font-medium {{ $isAll ? 'text-slate-300' : 'text-slate-400' }}">All Categories</div>
            </button>

            {{-- Category Tab Boxes --}}
            @foreach($tabs as $type => $cfg)
                @php $isAct = $activeType === $type; @endphp
                <button type="button" onclick="document.getElementById('voucher_type').value = '{{ $type }}'; document.getElementById('filterForm').submit();"
                   class="text-left p-3.5 rounded-2xl border transition-all duration-200 block space-y-1 group cursor-pointer
                          {{ $isAct
                             ? $cfg['bg'].' '.$cfg['border'].' '.$cfg['text'].' shadow-md ring-2 '.$cfg['ring']
                             : 'bg-white border-slate-200/80 hover:border-slate-300 hover:shadow-xs' }}">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest {{ $isAct ? $cfg['text'] : 'text-slate-600' }}">
                        <span>{{ $type }}</span>
                        <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}"></span>
                    </div>
                    <div class="text-base font-black font-mono {{ $isAct ? $cfg['text'] : 'text-slate-900' }}">
                        @if(($totals[$type] ?? 0) >= 100000)
                            ₹{{ number_format(($totals[$type] ?? 0)/100000, 1) }}L
                        @else
                            ₹{{ number_format(($totals[$type] ?? 0)/1000, 1) }}K
                        @endif
                    </div>
                    <div class="text-[10px] font-medium {{ $isAct ? $cfg['text'] : 'text-slate-400' }} truncate">{{ $cfg['sub'] }}</div>
                </button>
            @endforeach

        </div>

        {{-- ── LEDGER LISTINGS TABLE ────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[10px] font-extrabold uppercase tracking-widest">
                            <th class="px-5 py-4">TRANSACTION DATE</th>
                            <th class="px-5 py-4">VOUCHER NO.</th>
                            <th class="px-5 py-4">TYPE / CATEGORY</th>
                            <th class="px-5 py-4">PARTICULARS LEDGER</th>
                            <th class="px-5 py-4 text-right">DEBIT (DR)</th>
                            <th class="px-5 py-4 text-right">CREDIT (CR)</th>
                            <th class="px-5 py-4 text-right">RUNNING BALANCE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 text-xs text-slate-700 font-medium">
                        @forelse($entries as $entry)
                            @php
                                $typeGroup = $entry->voucher->type ?? 'Other';
                                $typeUpper = strtoupper($typeGroup);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5 font-sans font-medium text-slate-500 whitespace-nowrap">
                                    {{ $entry->date ? $entry->date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">
                                    {{ $entry->voucher->voucher_number ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider 
                                        @if($typeUpper === 'RECEIPT') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @elseif($typeUpper === 'PAYMENT') bg-rose-100 text-rose-800 border border-rose-200
                                        @elseif($typeUpper === 'CONTRA') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($typeUpper === 'JOURNAL') bg-amber-100 text-amber-800 border border-amber-200
                                        @elseif($typeUpper === 'SALES') bg-purple-100 text-purple-800 border border-purple-200
                                        @elseif($typeUpper === 'PURCHASE') bg-indigo-100 text-indigo-800 border border-indigo-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        {{ $typeUpper }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900">{{ $entry->account->name ?? 'General Ledger' }}</div>
                                    <div class="text-[9px] text-slate-400 font-mono uppercase tracking-wider">
                                        {{ $entry->account->code ?? '-' }} 
                                        <span class="ml-1 px-1 py-0.5 rounded bg-slate-100 text-slate-600">{{ $entry->account->type ?? 'Asset' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-extrabold @if($entry->debit > 0) text-slate-900 @else text-slate-300 @endif">
                                    {{ $entry->debit > 0 ? '₹' . number_format((float)$entry->debit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-extrabold @if($entry->credit > 0) text-slate-900 @else text-slate-300 @endif">
                                    {{ $entry->credit > 0 ? '₹' . number_format((float)$entry->credit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-extrabold text-slate-900">
                                    ₹{{ number_format((float)$entry->running_balance, 2, '.', ',') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 font-bold uppercase tracking-wider">
                                    No ledger transactions found for the selected filters.
                                </td>
                            </tr>
                        @endforelse

                        @if($entries->isNotEmpty())
                            @php
                                $totalDebit = $entries->sum('debit');
                                $totalCredit = $entries->sum('credit');
                            @endphp
                            <!-- GRAND TOTALS Row -->
                            <tr class="bg-slate-900 text-white font-extrabold border-t-2 border-slate-900">
                                <td class="px-5 py-4 text-[10px] uppercase tracking-widest text-slate-300" colspan="4">GRAND TOTALS ACROSS ALL CATEGORIES</td>
                                <td class="px-5 py-4 text-right font-mono text-white font-black text-sm">
                                    {{ $totalDebit > 0 ? '₹' . number_format((float)$totalDebit, 2, '.', ',') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right font-mono text-white font-black text-sm">
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
