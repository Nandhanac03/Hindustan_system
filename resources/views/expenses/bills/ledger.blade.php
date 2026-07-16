<x-erp-layout>
    <x-slot:title>Expense Ledger - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Site Expenses > Expense Ledger</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6">
        <!-- Section Header -->
        <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Site Expenses & Payments Ledger</h1>
                <p class="text-xs text-slate-450 mt-1">Real-time tracking of allocations made from customer receipt splits routing to supplier accounts.</p>
            </div>
        </div>

        <!-- Ledger Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <th class="px-6 py-4">Payment Date</th>
                            <th class="px-6 py-4">Bill/Invoice Number</th>
                            <th class="px-6 py-4">Supplier Owed</th>
                            <th class="px-6 py-4 text-right">Payment Amount (₹)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($payments as $pmt)
                            <tr class="hover:bg-slate-55 hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-500">{{ \Carbon\Carbon::parse($pmt->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $pmt->bill_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $pmt->supplier_name }}</td>
                                <td class="px-6 py-4 text-right font-mono font-extrabold text-slate-900">₹{{ number_format((float)$pmt->amount, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[9px] font-extrabold uppercase">Processed & Cleared</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-450 font-bold">
                                    No ledger payment transactions found. Payouts processed via "Accounting & Cash Flow" split allocations will compile here automatically.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-erp-layout>
