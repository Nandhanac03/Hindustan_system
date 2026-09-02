<x-erp-layout title="Supplier, Contractor & Broker Payables" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    {{-- Under Construction Notice --}}
    <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-5 md:p-6 shadow-sm relative overflow-hidden backdrop-blur-sm">
        <div class="flex items-start md:items-center gap-4">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30 text-2xl md:text-3xl">
                🚧
            </div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Work In Progress
                    </span>
                </div>
                <h2 class="text-lg md:text-2xl font-black text-red-950 tracking-tight leading-snug">
                    We're working on this module. It is not yet ready for use and will be released shortly.
                </h2>
                <p class="text-xs md:text-sm font-medium text-red-800">
                    This module is currently being finalized. Please check back soon for full availability.
                </p>
            </div>
        </div>
    </div>

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Supplier, Contractor & Broker Payables</h3>
                @include('reports.partials.header-badges')
            </div>



            <div id="supplierPayablesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'supplierContractorFilterForm', 'actionRoute' => route('reports.supplier_contractor'), 'exportLabel' => 'Export Payables'])
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                            <th class="px-5 py-3.5 text-white font-extrabold">Broker / Supplier</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Associated Sale</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Project / Customer</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Commission Due</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Paid Amount</th>
                            <th class="px-5 py-3.5 text-center text-white font-extrabold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($supplierContractorEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 font-sans font-bold text-slate-900">{{ $row->broker?->name }}</td>
                            <td class="px-5 py-3 text-indigo-750 font-bold">{{ $row->sale?->sale_number }}</td>
                            <td class="px-5 py-3 font-sans text-slate-500">
                                <div>{{ $row->sale?->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Cust: {{ $row->sale?->customer?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-rose-600">₹{{ number_format($row->commission_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($row->paid_amount, 2) }}</td>
                            <td class="px-5 py-3 text-center">
                                @php $sc = ['paid'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','pending'=>'bg-amber-50 text-amber-700 border border-amber-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$row->status] ?? 'bg-slate-105' }}">{{ $row->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No suppliers/broker accounts payable found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $supplierContractorEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
