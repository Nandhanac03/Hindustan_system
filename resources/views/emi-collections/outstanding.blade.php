<x-erp-layout title="Outstanding & Due Tracking" headerTitle="Customer Outstanding Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ activeBracket: 'all', searchQuery: '', toast: { open: false, message: '' } }">

    {{-- Aging Bracket KPI Cards (real data from Sale.remaining_balance) --}}
    <div class="space-y-3">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Select Aging Bracket to Filter</h3>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div @click="activeBracket = activeBracket === 'current' ? 'all' : 'current'"
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === 'current' ? 'border-primary shadow-lg bg-primary-50/10' : 'border-slate-200/80 shadow-sm hover:border-primary/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Current / 0–30 Days</span>
                    <span class="text-[9px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-bold">Active</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-emerald-700 block font-mono">₹{{ number_format($totals['current'], 0) }}</span>
                    <span class="text-[9px] text-slate-400 mt-1 block font-semibold">{{ count($brackets['current']) }} Accounts</span>
                </div>
            </div>

            <div @click="activeBracket = activeBracket === '1-30' ? 'all' : '1-30'"
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '1-30' ? 'border-amber-500 shadow-lg bg-amber-50/10' : 'border-slate-200/80 shadow-sm hover:border-amber-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">31–60 Days</span>
                    <span class="text-[9px] bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded font-bold">Mild</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-amber-700 block font-mono">₹{{ number_format($totals['1-30'], 0) }}</span>
                    <span class="text-[9px] text-amber-600 mt-1 block font-semibold">{{ count($brackets['1-30']) }} Accounts</span>
                </div>
            </div>

            <div @click="activeBracket = activeBracket === '31-60' ? 'all' : '31-60'"
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '31-60' ? 'border-orange-500 shadow-lg bg-orange-50/10' : 'border-slate-200/80 shadow-sm hover:border-orange-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">61–90 Days</span>
                    <span class="text-[9px] bg-orange-50 text-orange-700 px-1.5 py-0.5 rounded font-bold">Moderate</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-orange-700 block font-mono">₹{{ number_format($totals['31-60'], 0) }}</span>
                    <span class="text-[9px] text-orange-600 mt-1 block font-semibold">{{ count($brackets['31-60']) }} Accounts</span>
                </div>
            </div>

            <div @click="activeBracket = activeBracket === '61+' ? 'all' : '61+'"
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '61+' ? 'border-rose-500 shadow-lg bg-rose-50/10' : 'border-slate-200/80 shadow-sm hover:border-rose-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">90+ Days</span>
                    <span class="text-[9px] bg-rose-50 text-rose-800 px-1.5 py-0.5 rounded font-bold">Severe</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-rose-700 block font-mono">₹{{ number_format($totals['61+'], 0) }}</span>
                    <span class="text-[9px] text-rose-600 mt-1 block font-semibold">{{ count($brackets['61+']) }} Accounts</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Totals Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Outstanding</span>
            <span class="text-2xl font-extrabold text-rose-600 mt-1 block font-mono">₹{{ number_format(array_sum($totals), 0) }}</span>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Across all active Sales</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Receipts (All Time)</span>
            @php $totalReceived = \App\Models\Receipt::sum('amount'); @endphp
            <span class="text-2xl font-extrabold text-emerald-600 mt-1 block font-mono">₹{{ number_format($totalReceived, 0) }}</span>
            <span class="text-[10px] text-slate-400 mt-0.5 block">From Receipts register</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Active Accounts</span>
            <span class="text-2xl font-extrabold text-primary mt-1 block font-mono">
                {{ collect($brackets)->flatten(1)->count() }}
            </span>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Sales with balance due</span>
        </div>
    </div>

    {{-- Receivables Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    Receivable Statements
                    <template x-if="activeBracket !== 'all'">
                        <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded font-mono uppercase font-bold" x-text="'Bracket: ' + activeBracket"></span>
                    </template>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Real outstanding balance from Sales module — Sale → Receipts → Remaining Balance.</p>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('emi-collections.outstanding') }}" class="flex gap-2">
                    <select name="project_id" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none cursor-pointer">
                        <option value="">All Projects</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-2 bg-primary text-white text-[10px] font-bold rounded-xl uppercase tracking-wide">Filter</button>
                    <a href="{{ route('emi-collections.outstanding') }}" class="px-3 py-2 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-xl uppercase tracking-wide">Clear</a>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Customer</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Project / Unit</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Sale No.</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Sale Total</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Receipts Paid</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Outstanding</th>
                        <!-- <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Aging</th> -->
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $agingColors = ['current'=>'bg-emerald-50 text-emerald-700','1-30'=>'bg-amber-50 text-amber-700','31-60'=>'bg-orange-50 text-orange-700','61+'=>'bg-rose-50 text-rose-700']; @endphp
                    @foreach(['current', '1-30', '31-60', '61+'] as $bracketKey)
                        @foreach($brackets[$bracketKey] as $row)
                        @php $sale = $row['sale']; @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors"
                            x-show="activeBracket === 'all' || activeBracket === '{{ $bracketKey }}'">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $sale->customer?->name ?? '—' }}</div>
                                <div class="text-[9px] text-slate-400">{{ $sale->customer?->phone ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-700">{{ $sale->project?->name ?? '—' }}</div>
                                <div class="text-[9px] text-slate-400">Unit: {{ $sale->unit?->door_no ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-[10px] text-primary font-bold">{{ $sale->sale_number }}</td>
                            <td class="px-6 py-4 text-right font-mono font-semibold text-slate-800">₹{{ number_format($sale->total_amount, 0) }}</td>
                            <td class="px-6 py-4 text-right font-mono font-semibold text-emerald-600">₹{{ number_format($row['total_paid'], 0) }}</td>
                            <td class="px-6 py-4 text-right font-mono font-extrabold text-rose-600">₹{{ number_format($row['outstanding'], 0) }}</td>
                            <!-- <td class="px-6 py-4">
                                <span class="text-[9px] font-bold px-2 py-1 rounded-lg {{ $agingColors[$bracketKey] }}">
                                    {{ $row['days_aged'] }}d
                                </span>
                            </td> -->
                            <td class="px-6 py-4">
                                <a href="{{ route('emi-collections.ledger', $sale) }}"
                                   class="text-[10px] font-bold text-primary hover:underline">View Ledger</a>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if(array_sum(array_map('count', $brackets)) === 0)
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                            No outstanding balances found.
                            <a href="{{ route('sales.index') }}" class="text-primary font-bold hover:underline ml-1">Go to Sales Register →</a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Footer Summary --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-wrap items-center justify-between text-xs gap-3">
            <span class="text-slate-500 font-semibold">
                Total Outstanding: <strong class="text-rose-600 font-mono">₹{{ number_format(array_sum($totals), 0) }}</strong>
            </span>
            <span class="text-slate-500 font-semibold">
                Total Receipts Applied: <strong class="text-emerald-600 font-mono">₹{{ number_format($totalReceived, 0) }}</strong>
            </span>
            <span class="text-slate-500 font-semibold">
                Net Closing Outstanding: <strong class="text-primary font-mono">₹{{ number_format(array_sum($totals), 0) }}</strong>
            </span>
        </div>
    </div>

</div>

</x-erp-layout>
