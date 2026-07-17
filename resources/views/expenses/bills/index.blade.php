<x-erp-layout>
    <x-slot:title>Bills List - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Site Expenses > Bills List</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6">
        <!-- Section Header -->
        <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Registered Supplier Bills Directory</h1>
                <p class="text-xs text-slate-450 mt-1">Manage and track your supplier liabilities and progressive allocations.</p>
            </div>
            <a href="{{ route('expenses.bills.create') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider bg-primary text-white rounded-xl shadow-sm hover:bg-primary-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Bill</span>
            </a>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold rounded-2xl shadow-2xs">
                {{ session('status') }}
            </div>
        @endif

        <!-- Bills List Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-55 bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <th class="px-6 py-4">Register Date</th>
                            <th class="px-6 py-4">Bill Number</th>
                            <th class="px-6 py-4">Supplier / Contractor</th>
                            <th class="px-6 py-4">Project Allocation</th>
                            <th class="px-6 py-4 text-right">Base Amount (₹)</th>
                            <th class="px-6 py-4 text-right">Final Amount (₹)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($bills as $bill)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-500">{{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $bill->bill_number }}</span>
                                        @if($bill->bill_file)
                                            <a href="{{ asset('storage/' . $bill->bill_file) }}" target="_blank" title="View Bill Document"
                                               class="text-slate-400 hover:text-primary transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $bill->supplier_name }}</td>
                                <td class="px-6 py-4 text-slate-600 font-semibold">{{ $bill->project_name }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold">₹{{ number_format((float)$bill->bill_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-slate-900">₹{{ number_format((float)$bill->final_amount, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($bill->status === 'paid')
                                        <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[9px] font-extrabold uppercase">Fully Paid</span>
                                    @elseif($bill->status === 'partially_paid')
                                        <span class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[9px] font-extrabold uppercase">Partially Paid</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[9px] font-extrabold uppercase">Approved Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-450 font-bold">
                                    No registered bills found. Click "+ Add New Bill" to register a supplier liability.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-erp-layout>
