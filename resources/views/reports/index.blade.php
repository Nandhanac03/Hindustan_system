<x-erp-layout title="Executive Reports" headerTitle="Executive Reports Dashboard">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Header Options --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Business Analytics & Metrics Reports</h2>
            <p class="text-xs text-slate-400 mt-0.5">Generate print-ready summaries of property bookings, region ledger totals, and collections.</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-650 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Executive Summary
        </button>
    </div>

    {{-- Main Financial Table by Projects --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Project Portfolio Financials Summary</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Project Name</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Unit Inventory</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Total Sales</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Collected</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Outstanding Balance</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $proj)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 text-sm">{{ $proj->name }}</div>
                                <div class="text-[10px] text-indigo-650 font-bold uppercase tracking-wider mt-0.5">{{ $proj->code }} &bull; {{ $proj->city }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $proj->units_count }} Total</div>
                                <div class="text-[10px] space-x-2 mt-1">
                                    <span class="text-emerald-600 font-semibold">{{ $proj->available_count }} Avail</span>
                                    <span class="text-indigo-500 font-semibold">{{ $proj->sold_count }} Sold</span>
                                    <span class="text-amber-500 font-semibold">{{ $proj->reserved_count }} Resv</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-950">₹{{ number_format($proj->sales_sum, 2) }}</td>
                            <td class="px-6 py-4 font-bold text-emerald-600">₹{{ number_format($proj->collection_sum, 2) }}</td>
                            <td class="px-6 py-4 font-bold text-rose-650">₹{{ number_format($proj->outstanding, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="badge-pill badge-{{ $proj->status }}">
                                    {{ ucfirst($proj->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-405 italic">No project data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Section: Top customer ledgers + print info --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Customer Ledgers --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Top Customer Ledgers</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Rank</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Name</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Bookings</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Paid Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($customers as $index => $customer)
                            <tr>
                                <td class="px-6 py-3 font-bold text-slate-400">#{{ $index + 1 }}</td>
                                <td class="px-6 py-3 font-semibold text-slate-900">{{ $customer->name }}</td>
                                <td class="px-6 py-3 font-bold text-slate-500">{{ $customer->bookings_count }}</td>
                                <td class="px-6 py-3 font-bold text-emerald-600">₹{{ number_format($customer->payments_sum_amount ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-405 italic">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Export/Print Guidance --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Ledger Reporting Tool</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                The financial reporting ledger aggregates operational data across your operating regions. Report calculations auto-update as installments are registered inside the system.
            </p>
            <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wide block mb-1">Export as CSV / Excel</span>
                <span class="text-[10px] text-slate-500">Integrate report views directly into corporate ledger sheets.</span>
            </div>
            <button class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs uppercase tracking-wide transition">
                Export Ledger Sheet (.csv)
            </button>
        </div>

    </div>

</div>

</x-erp-layout>
