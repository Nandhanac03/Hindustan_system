<x-erp-layout title="Sales Cancellation Report" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-rose-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-rose-200/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-rose-500/15 rounded-xl border border-rose-200 text-rose-700 shadow-2xs">
                            <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Sales Cancellation Report</h3>
                            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest bg-rose-500/15 px-2.5 py-0.5 rounded border border-rose-200">Cancellation & Refund Audit Trail</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Audit cancelled bookings, cancellation fees retained by the business, and refund liabilities.</p>
                </div>
                
                {{-- Action Buttons & Total Stats --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0 relative z-10">
                    <div class="px-3.5 py-2 bg-rose-50 border border-rose-200 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-rose-700">Total Cancellation Fee</span>
                        <span class="text-xs font-black text-rose-900 font-mono">₹{{ number_format($salesReturnChartData['total_fee'] ?? 0, 2) }}</span>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-250/20 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Refund Payable</span>
                        <span class="text-xs font-black text-emerald-900 font-mono">₹{{ number_format($salesReturnChartData['total_refund'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Dual Interactive Charts Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            Cancellation Fees & Refund Outflow Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Monthly Timeline (₹)</span>
                    </div>
                    <div id="salesReturnChart" class="w-full h-56"></div>
                </div>

                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            Cancellation Fee vs Refund Breakdown
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="salesReturnDonutChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>
            </div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'salesReturnFilterForm', 'actionRoute' => route('reports.sales_return'), 'exportLabel' => 'Export Cancellations'])
            </div>

            {{-- Sales Cancellation Table Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-rose-50/20 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                            Chronological Sales Cancellation & Refund Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Verified audit trail of cancelled or returned units, cancellation fees, and refund liabilities.</p>
                    </div>
                    @include('reports.partials.header-badges')
                </div>

                <div class="w-full overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3 text-white font-extrabold whitespace-nowrap">Ref Code No.</th>
                                <th class="px-5 py-3 text-white font-extrabold">Customer Entity</th>
                                <th class="px-5 py-3 text-white font-extrabold">Returned Property Unit</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Contract Value</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Paid Amount</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Cancellation Fee</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-655 font-mono">
                            @forelse($salesReturns as $ret)
                            <tr class="hover:bg-rose-50/10 transition-colors duration-150 font-medium text-xs">
                                <td class="px-5 py-3 font-bold text-rose-700 whitespace-nowrap">{{ $ret->sale_number }}</td>
                                <td class="px-5 py-3 font-sans text-slate-800 whitespace-nowrap">{{ $ret->customer?->name }}</td>
                                <td class="px-5 py-3 font-sans">
                                    <div class="font-bold text-slate-800">{{ $ret->project?->name }}</div>
                                    <div class="text-[10px] text-slate-400">Unit: {{ $ret->unit?->door_no }}</div>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">₹{{ number_format($ret->total_amount, 2) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-600 font-bold whitespace-nowrap">₹{{ number_format($ret->total_paid ?? 0.00, 2) }}</td>
                                <td class="px-5 py-3 text-right text-rose-600 whitespace-nowrap">₹{{ number_format($ret->cancellation_fee, 2) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700 font-bold whitespace-nowrap">₹{{ number_format($ret->refund_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
