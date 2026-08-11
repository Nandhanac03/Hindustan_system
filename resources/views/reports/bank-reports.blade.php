<x-erp-layout title="Bank Transaction Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Bank Transaction Statement</h3>
                @include('reports.partials.header-badges')
            </div>



            <div id="bankTransactionsChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'bankReportsFilterForm', 'actionRoute' => route('reports.bank_reports'), 'exportLabel' => 'Export Bank Statement'])
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Clearance Date</th>
                            <th class="px-5 py-3">Voucher Ref</th>
                            <th class="px-5 py-3">Associated Customer</th>
                            <th class="px-5 py-3">Bank / Ref Number</th>
                            <th class="px-5 py-3 text-right">Cleared Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($bankReportEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $row->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $row->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $row->bank_name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Ref: {{ $row->reference_no ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($row->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No bank ledger clearance logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $bankReportEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
