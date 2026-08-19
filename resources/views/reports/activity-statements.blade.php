<x-erp-layout title="Customer & Supplier Activity Statements" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Customer & Supplier Activity Statements</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Passbook-style transaction history for customers (receivables) and suppliers (payables).</p>
                </div>
                <div class="flex items-center gap-2">
                    @include('reports.partials.header-badges')
                    <span class="px-3 py-1 bg-primary/10 text-primary-700 border border-primary/20 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">Audit Trail</span>
                </div>
            </div>



            {{-- Filter, Print & Export Bar directly above Activity Tables --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'activityStatementsFilterForm', 'actionRoute' => route('reports.activity_statements'), 'exportLabel' => 'Export Activity'])
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- CUSTOMER STATEMENT --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3">
                        <h4 class="text-[10px] font-extrabold text-white uppercase tracking-widest">Customer Account Statement</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Receivables ledger — advance paid vs outstanding</p>
                    </div>
                    <div class="p-4 bg-slate-50 border-b border-slate-200">
                        <form method="GET" action="{{ route('reports.activity_statements') }}" class="flex items-end gap-3">
                            <div class="flex-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Select Customer</label>
                                <select name="customer_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider">Load →</button>
                        </form>
                    </div>

                    @if(request('customer_id') && isset($ledgerEntries) && $ledgerEntries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                    <th class="px-4 py-3 text-white font-extrabold">Date</th>
                                    <th class="px-4 py-3 text-white font-extrabold">Description</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Debit (₹)</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Credit (₹)</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Balance (₹)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($ledgerEntries as $entry)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2.5 text-slate-500 font-semibold">{{ $entry['date'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-700 font-medium">{{ $entry['description'] }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['debit'] > 0 ? 'text-slate-900' : 'text-slate-300' }}">{{ $entry['debit'] > 0 ? '₹' . number_format($entry['debit'], 2) : '—' }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['credit'] > 0 ? 'text-emerald-700' : 'text-slate-300' }}">{{ $entry['credit'] > 0 ? '₹' . number_format($entry['credit'], 2) : '—' }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['balance'] > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($entry['balance'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold">
                                    <td colspan="2" class="px-4 py-3 text-slate-700">Closing Balance</td>
                                    <td class="px-4 py-3 text-right font-mono">₹{{ number_format($totalDebits ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-emerald-700">₹{{ number_format($totalCredits ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono {{ ($closingBalance ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($closingBalance ?? 0, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @elseif(request('customer_id'))
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">No active sale found for this customer.</div>
                    @else
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">Select a customer above to view their account statement.</div>
                    @endif
                </div>

                {{-- SUPPLIER STATEMENT --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3">
                        <h4 class="text-[10px] font-extrabold text-white uppercase tracking-widest">Supplier / Contractor Statement</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Payables ledger — billed vs paid vs outstanding balance</p>
                    </div>
                    <div class="p-4 bg-slate-50 border-b border-slate-200">
                        <form method="GET" action="{{ route('reports.activity_statements') }}" class="flex items-end gap-3">
                            <div class="flex-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Select Supplier</label>
                                <select name="supplier_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider">Load →</button>
                        </form>
                    </div>

                    @php
                        $supplierStatement = [];
                        $supplierBilled = 0;
                        $supplierPaid = 0;
                        $supplierBalance = 0;
                        if(request('supplier_id')) {
                            $supplierBills = \Illuminate\Support\Facades\DB::table('bills')
                                ->where('payee_id', request('supplier_id'))
                                ->orderBy('created_at')
                                ->get();
                            foreach($supplierBills as $sb) {
                                $paid = \Illuminate\Support\Facades\DB::table('bill_payments')->where('bill_id', $sb->id)->sum('amount');
                                $supplierBilled += (float)$sb->final_amount;
                                $supplierPaid += (float)$paid;
                                $supplierBalance += ((float)$sb->final_amount - (float)$paid);
                                $supplierStatement[] = [
                                    'date'       => \Carbon\Carbon::parse($sb->created_at)->format('d M Y'),
                                    'bill_no'    => $sb->bill_number,
                                    'billed'     => (float)$sb->final_amount,
                                    'paid'       => (float)$paid,
                                    'balance'    => (float)$sb->final_amount - (float)$paid,
                                    'status'     => $sb->status,
                                ];
                            }
                        }
                    @endphp

                    @if(request('supplier_id') && count($supplierStatement) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                    <th class="px-4 py-3 text-white font-extrabold">Date</th>
                                    <th class="px-4 py-3 text-white font-extrabold">Bill No.</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Billed (₹)</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Paid (₹)</th>
                                    <th class="px-4 py-3 text-right text-white font-extrabold">Balance (₹)</th>
                                    <th class="px-4 py-3 text-center text-white font-extrabold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($supplierStatement as $row)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2.5 text-slate-500 font-semibold">{{ $row['date'] }}</td>
                                    <td class="px-4 py-2.5 font-mono font-bold text-slate-900">{{ $row['bill_no'] }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold text-slate-900">₹{{ number_format($row['billed'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold text-emerald-700">₹{{ number_format($row['paid'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $row['balance'] > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($row['balance'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-center">
                                        @if($row['status'] === 'paid')
                                            <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[8px] font-extrabold uppercase">Paid</span>
                                        @elseif($row['status'] === 'partially_paid')
                                            <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[8px] font-extrabold uppercase">Partial</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[8px] font-extrabold uppercase">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold">
                                    <td colspan="2" class="px-4 py-3 text-slate-700">Total</td>
                                    <td class="px-4 py-3 text-right font-mono text-slate-900">₹{{ number_format($supplierBilled, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-emerald-700">₹{{ number_format($supplierPaid, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono {{ $supplierBalance > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($supplierBalance, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="grid grid-cols-3 gap-3 p-4 border-t border-slate-100">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Billed</div>
                            <div class="text-sm font-extrabold font-mono text-slate-900 mt-1">₹{{ number_format($supplierBilled, 0) }}</div>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest">Paid / Cleared</div>
                            <div class="text-sm font-extrabold font-mono text-emerald-700 mt-1">₹{{ number_format($supplierPaid, 0) }}</div>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-rose-600 uppercase tracking-widest">Outstanding</div>
                            <div class="text-sm font-extrabold font-mono text-rose-700 mt-1">₹{{ number_format($supplierBalance, 0) }}</div>
                        </div>
                    </div>
                    @elseif(request('supplier_id'))
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">No bills found for this supplier.</div>
                    @else
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">Select a supplier above to view their statement.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
