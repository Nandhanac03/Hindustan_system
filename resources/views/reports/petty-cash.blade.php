<x-erp-layout title="Petty Cash Inflow & Outflow Book" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Petty Cash Inflow & Outflow Book</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track daily cash receipts, petty cash disbursements, and physical cash register balances.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @include('reports.partials.header-badges')
                    <div class="px-4 py-2 bg-gradient-to-r from-amber-500/10 to-amber-500/5 border border-[#a38c29]/30 rounded-xl">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-[#a38c29]">Total Cash Inflow</span>
                        <span class="text-base font-black text-slate-900 font-mono">₹{{ number_format($pettyCashChartData['total_amount'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>



            {{-- 4 Stat Badges Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Collection</span>
                    <div class="text-base font-black text-slate-900 font-mono">₹{{ number_format($pettyCashChartData['total_amount'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cash Receipts Count</span>
                    <div class="text-base font-black text-emerald-600 font-mono">{{ $pettyCashChartData['total_count'] ?? 0 }} Vouchers</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Average Receipt</span>
                    <div class="text-base font-black text-indigo-600 font-mono">₹{{ number_format($pettyCashChartData['avg_amount'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Max Cash Receipt</span>
                    <div class="text-base font-black text-amber-600 font-mono">₹{{ number_format($pettyCashChartData['max_amount'] ?? 0, 2) }}</div>
                </div>
            </div>

            {{-- Dual Visualization Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            Customer Inflow Share
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="pettyCashCustomerChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>

                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Monthly Cash Timeline Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Inflows (₹)</span>
                    </div>
                    <div id="pettyCashChart" class="w-full h-56"></div>
                </div>
            </div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'pettyCashFilterForm', 'actionRoute' => route('reports.petty_cash'), 'exportLabel' => 'Export Petty Cash'])
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                            <th class="px-5 py-3.5 text-white font-extrabold">Transaction Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Voucher Ref ID</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Site / Customer Detail</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Receipt Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($pettyCashEntries as $pc)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $pc->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $pc->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">
                                <div>Cash collection from {{ $pc->customer?->name }}</div>
                                <div class="text-[10px] text-slate-400">Project: {{ $pc->sale?->project?->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($pc->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-400 italic">No cash inflow transactions posted.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $pettyCashEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
