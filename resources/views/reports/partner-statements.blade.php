<x-erp-layout title="Partner Statement Ledger & Capital Outflows" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Partner Statement Ledger & Capital Outflows</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track capital allocations, profit shares, and mapping of receipt distributions across project partners.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @include('reports.partials.header-badges')
                    <div class="px-4 py-2 bg-gradient-to-r from-amber-500/10 to-amber-500/5 border border-[#a38c29]/30 rounded-xl">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-[#a38c29]">Total Allocated Outflow</span>
                        <span class="text-base font-black text-slate-900 font-mono">₹{{ number_format($partnerChartData['total_allocated'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>



            {{-- Dual Interactive Charts Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29]"></span>
                            Monthly Capital Outflow Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Allocated Amounts (₹)</span>
                    </div>
                    <div id="partnerStatementsChart" class="w-full h-56"></div>
                </div>

                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Partner Outflow Share
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="partnerDistributionChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>
            </div>



                <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                            <th class="px-5 py-3.5 text-white font-extrabold">Allocation Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Partner Entity</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Associated Project</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Description Memo</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Allocated Outflow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($partnerAllocations as $alloc)
                        <tr class="hover:bg-slate-50/70 transition-colors font-semibold">
                            <td class="px-5 py-3.5 text-slate-500 font-sans whitespace-nowrap">{{ $alloc->date?->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 font-sans font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                {{ $alloc->partner?->name }}
                            </td>
                            <td class="px-5 py-3.5 font-sans text-slate-600">{{ $alloc->project?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 font-sans font-medium text-slate-500">Capital Profit Allocation via receipts mapping</td>
                            <td class="px-5 py-3.5 text-right text-rose-600 font-black font-mono">₹{{ number_format($alloc->allocated_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No allocations posted for partners.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $partnerAllocations->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
