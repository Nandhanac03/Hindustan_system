<x-erp-layout title="EMI Collection Trends & Summary" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">EMI Collection Trends & Summary</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">EMI Outstanding vs Collection</h4>
                    <div id="emiOutstandingCollectionChart" class="w-full h-56"></div>
                </div>
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Collection Trend Performance</h4>
                    <div id="emiCollectionTrendChart" class="w-full h-56"></div>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Voucher Ref</th>
                            <th class="px-5 py-3">Customer Details</th>
                            <th class="px-5 py-3">Payment Mode</th>
                            <th class="px-5 py-3 text-right">Inflow Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($cashBookEntries as $receipt)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-900">{{ $receipt->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 inline-block">{{ $receipt->payment_mode }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No collections found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $cashBookEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
