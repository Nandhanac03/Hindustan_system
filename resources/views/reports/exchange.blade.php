<x-erp-layout title="Unit Exchange Report" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Unit Exchange Report</h3>
                @include('reports.partials.header-badges')
            </div>



            <div id="unitExchangesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'exchangeFilterForm', 'actionRoute' => route('reports.exchange_report'), 'exportLabel' => 'Export Exchanges'])
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Exchange Date</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Transferred Unit</th>
                            <th class="px-5 py-3 text-right">Equity Applied(Paid Amount)</th>
                            <th class="px-5 py-3 text-right">Contract Value</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($exchangeEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row->sale_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans text-slate-900">{{ $row->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $row->project?->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Old Unit: {{ $row->unit?->formatted_name ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($row->transferred_equity, 2) }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($row->total_amount, 2) }}</td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">Exchanged</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No exchanges registered.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $exchangeEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
