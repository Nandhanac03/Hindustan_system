<x-erp-layout title="Outstanding & Due Tracking" headerTitle="Customer Outstanding Directory">

<div class="max-w-[1800px] mx-auto space-y-2.5" x-data="{ activeBracket: 'all', searchQuery: '', toast: { open: false, message: '' } }">

    {{-- Aging Bracket KPI Cards (real data from Sale.remaining_balance) --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                Select Aging Bracket to Filter
                <span class="text-[9px] bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full font-bold uppercase">Click Card to Filter Table</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            {{-- Bracket 1: Current --}}
            <div @click="activeBracket = activeBracket === 'current' ? 'all' : 'current'"
                 class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 cursor-pointer transition-all duration-300 select-none flex flex-col justify-between group relative overflow-hidden hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)]"
                 :class="activeBracket === 'current' ? 'shadow-lg bg-emerald-50/40 ring-2 ring-emerald-500/20 border-y-emerald-300 border-r-emerald-300' : 'shadow-sm hover:border-y-emerald-200 hover:border-r-emerald-200'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Current / 0–30 Days</span>
                    <span class="text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-200/80 px-2 py-0.5 rounded-full font-extrabold shadow-2xs">Active</span>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <span class="text-2xl font-black text-emerald-700 block font-mono tracking-tight">₹{{ number_format($totals['current'], 0) }}</span>
                        <span class="text-[10px] text-slate-400 mt-1 block font-semibold">{{ count($brackets['current']) }} Accounts</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Bracket 2: 31–60 Days --}}
            <div @click="activeBracket = activeBracket === '1-30' ? 'all' : '1-30'"
                 class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 cursor-pointer transition-all duration-300 select-none flex flex-col justify-between group relative overflow-hidden hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)]"
                 :class="activeBracket === '1-30' ? 'shadow-lg bg-amber-50/40 ring-2 ring-amber-500/20 border-y-amber-300 border-r-amber-300' : 'shadow-sm hover:border-y-amber-200 hover:border-r-amber-200'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-extrabold text-slate-400 tracking-wider uppercase">31–60 Days</span>
                    <span class="text-[9px] bg-amber-50 text-amber-700 border border-amber-200/80 px-2 py-0.5 rounded-full font-extrabold shadow-2xs">Mild</span>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <span class="text-2xl font-black text-amber-700 block font-mono tracking-tight">₹{{ number_format($totals['1-30'], 0) }}</span>
                        <span class="text-[10px] text-amber-600 mt-1 block font-semibold">{{ count($brackets['1-30']) }} Accounts</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Bracket 3: 61–90 Days --}}
            <div @click="activeBracket = activeBracket === '31-60' ? 'all' : '31-60'"
                 class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-orange-500 p-5 cursor-pointer transition-all duration-300 select-none flex flex-col justify-between group relative overflow-hidden hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(249,115,22,0.15)]"
                 :class="activeBracket === '31-60' ? 'shadow-lg bg-orange-50/40 ring-2 ring-orange-500/20 border-y-orange-300 border-r-orange-300' : 'shadow-sm hover:border-y-orange-200 hover:border-r-orange-200'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-extrabold text-slate-400 tracking-wider uppercase">61–90 Days</span>
                    <span class="text-[9px] bg-orange-50 text-orange-700 border border-orange-200/80 px-2 py-0.5 rounded-full font-extrabold shadow-2xs">Moderate</span>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <span class="text-2xl font-black text-orange-700 block font-mono tracking-tight">₹{{ number_format($totals['31-60'], 0) }}</span>
                        <span class="text-[10px] text-orange-600 mt-1 block font-semibold">{{ count($brackets['31-60']) }} Accounts</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Bracket 4: 90+ Days --}}
            <div @click="activeBracket = activeBracket === '61+' ? 'all' : '61+'"
                 class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 cursor-pointer transition-all duration-300 select-none flex flex-col justify-between group relative overflow-hidden hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)]"
                 :class="activeBracket === '61+' ? 'shadow-lg bg-rose-50/40 ring-2 ring-rose-500/20 border-y-rose-300 border-r-rose-300' : 'shadow-sm hover:border-y-rose-200 hover:border-r-rose-200'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-extrabold text-slate-400 tracking-wider uppercase">90+ Days</span>
                    <span class="text-[9px] bg-rose-50 text-rose-800 border border-rose-200/80 px-2 py-0.5 rounded-full font-extrabold shadow-2xs">Severe</span>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <span class="text-2xl font-black text-rose-700 block font-mono tracking-tight">₹{{ number_format($totals['61+'], 0) }}</span>
                        <span class="text-[10px] text-rose-600 mt-1 block font-semibold">{{ count($brackets['61+']) }} Accounts</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Totals Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 shadow-sm hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] group relative overflow-hidden flex items-center justify-between cursor-pointer">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Total Outstanding</span>
                <span class="text-2xl font-black text-rose-600 mt-1 block font-mono tracking-tight">₹{{ number_format(array_sum($totals), 0) }}</span>
                <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">Across all active Sales</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 scale-100 group-hover:scale-110 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 shadow-sm hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] group relative overflow-hidden flex items-center justify-between cursor-pointer">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Total Receipts (All Time)</span>
                @php $totalReceived = \App\Models\Receipt::sum('amount'); @endphp
                <span class="text-2xl font-black text-emerald-600 mt-1 block font-mono tracking-tight">₹{{ number_format($totalReceived, 0) }}</span>
                <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">From Receipts register</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 scale-100 group-hover:scale-110 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 shadow-sm hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] group relative overflow-hidden flex items-center justify-between cursor-pointer">
            <div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Active Accounts</span>
                <span class="text-2xl font-black text-[#a38c29] mt-1 block font-mono tracking-tight">
                    {{ collect($brackets)->flatten(1)->count() }}
                </span>
                <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">Sales with balance due</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 text-[#a38c29] group-hover:bg-[#a38c29] group-hover:text-white transition-all duration-300 scale-100 group-hover:scale-110 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Receivables Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-[#a38c29] rounded-full shrink-0"></div>
                <div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        Receivable Statements
                        <template x-if="activeBracket !== 'all'">
                            <span class="text-[9px] bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 px-2 py-0.5 rounded-full font-mono uppercase font-extrabold" x-text="'Bracket: ' + activeBracket"></span>
                        </template>
                    </h2>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Real outstanding balance from Sales module — Sale → Receipts → Remaining Balance.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('emi-collections.outstanding') }}" class="flex gap-2">
                    <select name="project_id" onchange="this.form.submit()"
                            class="px-3.5 py-1.5 bg-white border border-slate-200/90 rounded-xl text-xs font-bold text-slate-800 focus:outline-none cursor-pointer shadow-xs focus:ring-4 focus:ring-[#a38c29]/15 focus:border-[#a38c29]">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ (string)$selectedProjectId === (string)$proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                        <option value="" {{ $selectedProjectId === '' ? 'selected' : '' }}>All Projects</option>
                    </select>
                    <button type="submit" class="px-3.5 py-1.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[10px] font-black rounded-xl uppercase tracking-wider transition-all shadow-xs">Filter</button>
                    <a href="{{ route('emi-collections.outstanding', ['project_id' => '']) }}" class="px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/90 text-[10px] font-extrabold rounded-xl uppercase tracking-wider transition-all shadow-xs">Clear</a>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white font-extrabold text-[9.5px] uppercase tracking-widest border-b border-[#8a7522]">
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Project / Unit</th>
                        <th class="px-6 py-3.5">Sale No.</th>
                        <th class="px-6 py-3.5 text-right">Sale Total</th>
                        <th class="px-6 py-3.5 text-right">Receipts Paid</th>
                        <th class="px-6 py-3.5 text-right">Outstanding</th>
                        <th class="px-6 py-3.5 text-center">Actions</th>
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
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('emi-collections.ledger', $sale) }}"
                                   class="px-3 py-1.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[10px] font-extrabold rounded-xl transition-all shadow-2xs hover:shadow uppercase tracking-wider inline-flex items-center gap-1">
                                    View Ledger &rarr;
                                </a>
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
        <div class="px-6 py-4 border-t-2 border-[#a38c29] bg-slate-900 text-white flex flex-wrap items-center justify-between text-xs gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span class="text-slate-300 font-bold uppercase tracking-wider text-[10px]">Total Outstanding:</span>
                <strong class="text-rose-400 font-mono text-sm font-extrabold">₹{{ number_format(array_sum($totals), 0) }}</strong>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-slate-300 font-bold uppercase tracking-wider text-[10px]">Total Receipts Applied:</span>
                <strong class="text-emerald-400 font-mono text-sm font-extrabold">₹{{ number_format($totalReceived, 0) }}</strong>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                <span class="text-slate-300 font-bold uppercase tracking-wider text-[10px]">Net Closing Outstanding:</span>
                <strong class="text-[#a38c29] font-mono text-sm font-extrabold">₹{{ number_format(array_sum($totals), 0) }}</strong>
            </div>
        </div>
    </div>

</div>

</x-erp-layout>
