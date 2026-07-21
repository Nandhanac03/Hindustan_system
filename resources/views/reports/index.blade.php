<x-erp-layout title="Business Performance Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    {{-- Header Options --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-white via-slate-50/50 to-white border border-slate-200/90 rounded-2xl p-5 shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-black text-slate-900 tracking-tight uppercase">Reporting & MIS Analytics</h2>
                <span class="px-3 py-1 bg-amber-500/10 text-amber-800 text-[10px] font-black rounded-full uppercase border border-amber-500/20 shadow-2xs">Executive Suite</span>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Real-time financial checkpoint, Trial Balance, P&L Statement, and Balance Sheet net worth analytics.</p>
        </div>
        <div class="flex flex-wrap gap-2.5 items-center">
            <button @click="printReport()" 
                    class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
            <button @click="exportCurrentTable()" 
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </button>
        </div>
    </div>

    {{-- Global Filters --}}
    <form method="GET" action="" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-4">
        <input type="hidden" name="report" value="{{ $activeTab }}">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Project</label>
                <select name="project_id" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Floor</label>
                <select name="floor_id" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Floors</option>
                    @foreach($floors ?? [] as $fl)
                        <option value="{{ $fl->id }}" {{ request('floor_id') == $fl->id ? 'selected' : '' }}>{{ $fl->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Unit Type</label>
                <select name="unit_type_id" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Types</option>
                    @foreach($unitTypes as $ut)
                        <option value="{{ $ut->id }}" {{ request('unit_type_id') == $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Customer</label>
                <select name="customer_id" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Customers</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Broker</label>
                <select name="broker_id" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Payment Mode</label>
                <select name="payment_mode" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
                    <option value="">All Modes</option>
                    <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ request('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ request('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="Online" {{ request('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                </select>
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-1.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
            </div>
            <div>
                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-1.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition">
            </div>
        </div>
        <div class="flex justify-end gap-2.5 pt-1 border-t border-slate-100">
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black rounded-xl transition shadow hover:shadow-md uppercase tracking-wider flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Apply Filters
            </button>
            <a href="?report={{ $activeTab }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold rounded-xl transition uppercase tracking-wider flex items-center gap-1.5">
                Reset Filters
            </a>
        </div>
    </form>

    {{-- Main Report Output Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">

        {{-- ACTIVITY STATEMENTS — Customer & Supplier History --}}
        @if($activeTab === 'activity_statements')
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Customer & Supplier Activity Statements</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Passbook-style transaction history for customers (receivables) and suppliers (payables).</p>
                </div>
                <span class="px-3 py-1 bg-primary/10 text-primary-700 border border-primary/20 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">Step 5: Audit Trail</span>
            </div>

            {{-- Two column layout: Customer | Supplier --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- CUSTOMER STATEMENT --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3">
                        <h4 class="text-[10px] font-extrabold text-white uppercase tracking-widest">Customer Account Statement</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Receivables ledger — advance paid vs outstanding</p>
                    </div>
                    <div class="p-4 bg-slate-50 border-b border-slate-200">
                        <form method="GET" class="flex items-end gap-3">
                            <input type="hidden" name="report" value="activity_statements">
                            <div class="flex-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Select Customer</label>
                                <select name="customer_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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
                                <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3 text-right">Debit (₹)</th>
                                    <th class="px-4 py-3 text-right">Credit (₹)</th>
                                    <th class="px-4 py-3 text-right">Balance (₹)</th>
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
                                    <td class="px-4 py-3 text-right font-mono">₹{{ number_format($totalDebits, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-emerald-700">₹{{ number_format($totalCredits, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($closingBalance, 2) }}</td>
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
                        <form method="GET" class="flex items-end gap-3">
                            <input type="hidden" name="report" value="activity_statements">
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
                                <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Bill No.</th>
                                    <th class="px-4 py-3 text-right">Billed (₹)</th>
                                    <th class="px-4 py-3 text-right">Paid (₹)</th>
                                    <th class="px-4 py-3 text-right">Balance (₹)</th>
                                    <th class="px-4 py-3 text-center">Status</th>
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
                    {{-- KPI Summary --}}
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
        @endif

        {{-- 16. DASHBOARD & MIS --}}
        @if($activeTab === 'dashboard')
        <div class="space-y-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-150 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-slate-900 text-white rounded-xl shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight uppercase">Dashboard Analytics & Profitability</h3>
                        <p class="text-xs text-slate-400">High-level financial KPIs, property metrics, and profitability breakdown</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-[10px] font-bold uppercase tracking-wider border border-slate-200 shadow-2xs">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Live Data
                </span>
            </div>
            
            {{-- Executive KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Card 1 --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Projects</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['total_projects'] }}</span>
                </div>

                {{-- Card 2 --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Units (Sold / Total)</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['sold_units'] }} <span class="text-slate-400 text-lg">/ {{ $dashboardData['total_units'] }}</span></span>
                </div>

                {{-- Card 3 --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Collections</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block">₹{{ number_format($dashboardData['collections'], 0) }}</span>
                </div>

                {{-- Card 4 --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Outstanding Receivable</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">₹{{ number_format($dashboardData['outstanding'], 0) }}</span>
                </div>

                {{-- Card 5 --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-400"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Net Calculated Profit</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">₹{{ number_format($dashboardData['profit'], 0) }}</span>
                </div>
            </div>

            {{-- Dashboard Charts (2 Columns: Sold vs Unsold, Collections vs Expected) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Sold vs Unsold Units</h4>
                            <p class="text-[11px] text-slate-400">Inventory allocation ratio</p>
                        </div>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase">Property Units</span>
                    </div>
                    <div id="soldUnsoldChart" class="w-full h-60"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Collections vs Expected</h4>
                            <p class="text-[11px] text-slate-400">Received vs pending receivables</p>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg uppercase">Financials</span>
                    </div>
                    <div id="collectionsExpectedChart" class="w-full h-60"></div>
                </div>
            </div>

            {{-- Bank Loan EMI alerts --}}
            @if($dashboardData['loan_emi_alerts']->isNotEmpty())
            <div class="bg-gradient-to-r from-rose-50 to-rose-100/50 border border-rose-200 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-extrabold text-rose-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="p-1 bg-rose-200 rounded-lg text-rose-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        Bank Loan EMI Alerts (Upcoming 30 Days)
                    </h4>
                    <span class="px-2.5 py-0.5 bg-rose-200 text-rose-900 text-[10px] font-extrabold rounded-full uppercase">Action Required</span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-rose-200/80 bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-rose-50/80 text-[10px] uppercase tracking-wider text-rose-800 border-b border-rose-150 font-bold">
                                <th class="px-4 py-2.5">Project</th>
                                <th class="px-4 py-2.5">Lender</th>
                                <th class="px-4 py-2.5">Due Date</th>
                                <th class="px-4 py-2.5 text-right">EMI Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100 font-semibold font-mono text-slate-800">
                            @foreach($dashboardData['loan_emi_alerts'] as $alert)
                            <tr class="hover:bg-rose-50/50 transition-colors">
                                <td class="px-4 py-2.5 font-sans font-bold text-slate-900">{{ $alert->loan?->project?->name }}</td>
                                <td class="px-4 py-2.5 font-sans text-slate-700">{{ $alert->loan?->lender_name }}</td>
                                <td class="px-4 py-2.5 text-rose-700">{{ $alert->due_date?->format('d M Y') }}</td>
                                <td class="px-4 py-2.5 text-right text-rose-700 font-bold">₹{{ number_format($alert->emi_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Project Profitability Grid --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Project Profitability Analysis</h4>
                        <p class="text-[11px] text-slate-400">Detailed breakdown of expected vs actual revenue, costs, and profit margin per project</p>
                    </div>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[10px]">
                                <th class="px-4 py-3 rounded-tl-2xl">Project</th>
                                <th class="px-4 py-3 text-right">Expected Revenue</th>
                                <th class="px-4 py-3 text-right">Actual Revenue</th>
                                <th class="px-4 py-3 text-right">Partner Payouts</th>
                                <th class="px-4 py-3 text-right">Brokerage</th>
                                <th class="px-4 py-3 text-right">Material Costs</th>
                                <th class="px-4 py-3 text-right">Contractor Payments</th>
                                <th class="px-4 py-3 text-right">Total Cost</th>
                                <th class="px-4 py-3 text-right">Net Profit</th>
                                <th class="px-4 py-3 text-right rounded-tr-2xl">Margin %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                            @foreach($dashboardData['project_profitability'] as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-sans font-extrabold text-slate-900">{{ $row['project']->name }}</td>
                                <td class="px-4 py-3 text-right">₹{{ number_format($row['expected_revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-700">₹{{ number_format($row['actual_revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['partner_payouts'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['brokerage_costs'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['material_costs'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['contractor_payments'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-rose-700">₹{{ number_format($row['total_cost'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">₹{{ number_format($row['profit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded font-sans font-extrabold text-[10px] uppercase {{ $row['margin'] > 15 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ number_format($row['margin'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- 1. AVAILABILITY REPORT --}}
        @if($activeTab === 'availability')
        <div class="space-y-6" x-data="{ currentSubTab: 'summary' }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Property Availability Matrix</h3>
                
                {{-- Sub-tab Navigation --}}
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl">
                    <button type="button" @click="currentSubTab = 'summary'"
                            :class="currentSubTab === 'summary' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Summary
                    </button>
                    <button type="button" @click="currentSubTab = 'shop'"
                            :class="currentSubTab === 'shop' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Shop
                    </button>
                    <button type="button" @click="currentSubTab = 'flat'"
                            :class="currentSubTab === 'flat' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Flat
                    </button>
                    <button type="button" @click="currentSubTab = 'parking'"
                            :class="currentSubTab === 'parking' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Parking
                    </button>
                    @if($others->isNotEmpty())
                    <button type="button" @click="currentSubTab = 'other'"
                            :class="currentSubTab === 'other' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Other
                    </button>
                    @endif
                </div>
            </div>

            {{-- SUMMARY SUB-TAB --}}
            <div x-show="currentSubTab === 'summary'" class="space-y-6" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-1">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Availability Distribution</h4>
                        <div id="availabilityDistributionChart" class="w-full h-52"></div>
                    </div>
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-2">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Unit Type Distribution</h4>
                        <div id="unitTypeDistributionChart" class="w-full h-52"></div>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3 text-center">Nos</th>
                                <th class="px-5 py-3 text-right">Built Up Area (In Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (In Sq Ft)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @php
                                $totalNos = 0;
                                $totalBuilt = 0;
                                $totalCarpet = 0;
                            @endphp
                            @foreach($groupedSummary as $row)
                                @php
                                    $totalNos += $row->nos;
                                    $totalBuilt += $row->built_up_area;
                                    $totalCarpet += $row->carpet_area;
                                @endphp
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 font-sans font-bold text-slate-900">{{ $row->type }}</td>
                                    <td class="px-5 py-3.5 text-center text-slate-650">{{ $row->nos }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->built_up_area > 0 ? number_format($row->built_up_area, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->carpet_area > 0 ? number_format($row->carpet_area, 2) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50/80 font-bold text-slate-900">
                                <td class="px-5 py-4 font-sans uppercase">Total</td>
                                <td class="px-5 py-4 text-center">{{ $totalNos }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalBuilt, 2) }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalCarpet, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SHOP SUB-TAB --}}
            <div x-show="currentSubTab === 'shop'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($shops as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No shops matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FLAT SUB-TAB --}}
            <div x-show="currentSubTab === 'flat'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($flats as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No flats matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PARKING SUB-TAB --}}
            <div x-show="currentSubTab === 'parking'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Parking No</th>
                                <th class="px-5 py-3">Sold/Booked To</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($parkings as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-800">
                                        @if($row->sale)
                                            <div class="font-bold text-[11px]">{{ $row->sale->customer?->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-mono">Sale: {{ $row->sale->sale_number }}</div>
                                        @else
                                            <span class="text-slate-350 italic font-normal text-[10px]">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-slate-400 italic">No parking bays matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- OTHER SUB-TAB --}}
            <div x-show="currentSubTab === 'other'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($others as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No other properties matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @endif

        {{-- 2. SALES REPORT --}}
        @if($activeTab === 'sales')
        <div class="space-y-6">
            <!-- <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Real-time Sales Register</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Monthly Sales Trend</h4>
                    <div id="monthlySalesTrendChart" class="w-full h-56"></div>
                </div>
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Sales by Project</h4>
                    <div id="salesByProjectChart" class="w-full h-56"></div>
                </div>
            </div> -->

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Sale Number</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Project / Unit</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3 text-right">Sale Date</th>
                            <th class="px-5 py-3 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($salesList as $sale)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 font-bold text-indigo-700">{{ $sale->sale_number }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $sale->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $sale->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Unit: {{ $sale->unit?->door_no }}</div>
                            </td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-500">{{ $sale->unit?->unitType?->category ?? 'Apartment' }}</td>
                            <td class="px-5 py-3 text-right font-sans">{{ $sale->sale_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-right font-extrabold text-slate-900">₹{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No sales logs matching filter criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $salesList->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 3. EMI & COLLECTIONS --}}
        @if($activeTab === 'emi_collections')
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
        @endif

        {{-- 4. CUSTOMER LEDGER --}}
        @if($activeTab === 'customer_ledger')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Customer Ledger Statement</h3>

            @if($selectedCustomer)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 border border-slate-200 rounded-2xl p-5 bg-slate-50/50 space-y-4">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Statement Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block font-bold">Customer Name</span>
                            <strong class="text-slate-900 text-sm block mt-0.5">{{ $selectedCustomer->name }}</strong>
                            <span class="text-slate-500 block text-[10px] mt-0.5">{{ $selectedCustomer->email }} &bull; {{ $selectedCustomer->phone }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block font-bold">Net Outstanding Due</span>
                            <strong class="text-rose-600 font-mono text-lg block mt-0.5">₹{{ number_format($closingBalance, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1 border border-slate-200 rounded-2xl p-4 bg-slate-50/50 flex flex-col justify-center">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">History & Ledger Mix</h4>
                    <div id="customerPaymentHistoryChart" class="w-full h-36"></div>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Posting Date</th>
                            <th class="px-5 py-3">Voucher / Ref No.</th>
                            <th class="px-5 py-3">Narrative</th>
                            <th class="px-5 py-3">Mode</th>
                            <th class="px-5 py-3 text-right">Debit (Due)</th>
                            <th class="px-5 py-3 text-right">Credit (Receipt)</th>
                            <th class="px-5 py-3 text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($ledgerEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row['date'] }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $row['description'] }}</td>
                            <td class="px-5 py-3 font-sans text-slate-450">{{ $row['payment_mode'] }}</td>
                            <td class="px-5 py-3 text-right text-rose-600">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700">{{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}</td>
                            <td class="px-5 py-3 text-right text-slate-900 font-extrabold">₹{{ number_format($row['balance'], 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No chronological allocations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center text-slate-400 italic">Please select a customer above to generate the ledger statement.</div>
            @endif
        </div>
        @endif

        {{-- 5. CASH BOOK --}}
        @if($activeTab === 'cash_book')
        <div class="space-y-6" id="cashBookDashboard">

            {{-- Section Header with partner context --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Partner Cash Book Analytics</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Real-time collection register with partner-wise breakdown and trend analytics.</p>
                </div>
                {{-- Partner Quick-filter pill tabs --}}
                <div class="flex flex-wrap gap-2">
                    <a href="?{{ http_build_query(array_merge(request()->query(), ['report'=>'cash_book', 'partner_id'=>''])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ !request('partner_id') ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'border-slate-200 text-slate-500 hover:border-slate-400 hover:text-slate-800' }}">
                        All Partners
                    </a>
                    @foreach($partners as $pt)
                    <a href="?{{ http_build_query(array_merge(request()->query(), ['report'=>'cash_book', 'partner_id'=>$pt->id])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ request('partner_id') == $pt->id ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-md' : 'border-slate-200 text-slate-500 hover:border-[#a38c29] hover:text-[#a38c29]' }}">
                        {{ $pt->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Total Received</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['total_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">All payment modes</div>
                    </div>
                </div>

                {{-- Cash Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Cash in Hand</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['cash_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Cash mode only</div>
                    </div>
                </div>

                {{-- Bank / Digital Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Bank / Digital</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['bank_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Bank · Cheque · UPI · Online</div>
                    </div>
                </div>

                {{-- Pending Collections --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Pending Balance</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['pending_balance'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Outstanding receivables</div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 1: Monthly Bar + Daily Line --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Monthly Cash Collections</h4>
                            <p class="text-[9px] text-slate-400">Last 12 months · bar chart</p>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">INFLOW</span>
                    </div>
                    <div id="cbMonthlyChart" class="w-full" style="height:220px;"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Payment Mode Mix</h4>
                            <p class="text-[9px] text-slate-400">Cash · Bank · UPI · Cheque</p>
                        </div>
                    </div>
                    <div id="cbPaymentModeChart" class="w-full" style="height:220px;"></div>
                </div>
            </div>

            {{-- Charts Row 2: Daily Line + Partner Donut + Partner Bar --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Daily Collection Trend</h4>
                            <p class="text-[9px] text-slate-400">Last 30 days · line chart</p>
                        </div>
                        <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">TREND</span>
                    </div>
                    <div id="cbDailyTrendChart" class="w-full" style="height:200px;"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Partner-wise Collections</h4>
                            <p class="text-[9px] text-slate-400">Basheer vs Pavoor · donut</p>
                        </div>
                    </div>
                    <div id="cbPartnerDonutChart" class="w-full flex-1" style="height:200px;"></div>
                </div>
            </div>

            {{-- Partner comparison bar chart --}}
            @if($cashBookChartData['partner_wise']->count() > 1)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Partner Collection Comparison</h4>
                        <p class="text-[9px] text-slate-400">Total amount received per partner</p>
                    </div>
                </div>
                <div id="cbPartnerBarChart" class="w-full" style="height:180px;"></div>
            </div>
            @endif

            {{-- Transaction Table --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Recent Cash Book Entries</h4>
                    <span class="text-[9px] text-slate-400 font-mono">{{ $cashBookEntries->total() }} records</span>
                </div>
                <div class="overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Voucher #</th>
                                <th class="px-5 py-3">Customer / Unit</th>
                                <th class="px-5 py-3">Partner</th>
                                <th class="px-5 py-3">Mode</th>
                                <th class="px-5 py-3">Bank Ref</th>
                                <th class="px-5 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($cashBookEntries as $cash)
                            @php
                                $modeColors = [
                                    'Cash'          => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'Bank Transfer' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Cheque'        => 'bg-violet-50 text-violet-700 border-violet-100',
                                    'Online'        => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'UPI'           => 'bg-amber-50 text-amber-700 border-amber-100',
                                ];
                                $mc = $modeColors[$cash->payment_mode] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors font-semibold">
                                <td class="px-5 py-3.5 text-slate-500 font-sans whitespace-nowrap">{{ $cash->receipt_date?->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-indigo-700 font-mono">REC-{{ sprintf("%05d", $cash->id) }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    <div class="font-bold text-slate-900">{{ $cash->customer?->name ?? '—' }}</div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $cash->sale?->project?->name }} · Unit {{ $cash->sale?->unit?->door_no ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    @if($cash->partner)
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100 inline-block">{{ $cash->partner->name }}</span>
                                    @else
                                        <span class="text-slate-300 font-mono text-[10px]">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase border inline-block {{ $mc }}">{{ $cash->payment_mode }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-400 text-[10px]">{{ $cash->reference_no ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-right font-black font-mono text-emerald-700 text-sm">₹{{ number_format($cash->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-slate-400 italic text-xs">No cash entries found for the selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $cashBookEntries->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif

        {{-- 6. BANK REPORTS --}}
        @if($activeTab === 'bank_reports')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Bank Transaction Statement</h3>

            <div id="bankTransactionsChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

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
        @endif

        {{-- 7. PARTNER STATEMENTS --}}
        @if($activeTab === 'partner_statements')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Partner Statement Ledger</h3>

            <div id="partnerStatementsChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Allocation Date</th>
                            <th class="px-5 py-3">Partner Entity</th>
                            <th class="px-5 py-3">Associated Project</th>
                            <th class="px-5 py-3">Description Memo</th>
                            <th class="px-5 py-3 text-right">Allocated Outflow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($partnerAllocations as $alloc)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $alloc->date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800">{{ $alloc->partner?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-500">{{ $alloc->project?->name }}</td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-500">Capital Profit Allocation via receipts mapping</td>
                            <td class="px-5 py-3 text-right text-rose-600">₹{{ number_format($alloc->allocated_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No allocations posted for partners.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $partnerAllocations->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 8. SUPPLIER & CONTRACTOR --}}
        @if($activeTab === 'supplier_contractor')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Supplier, Contractor & Broker Payables</h3>

            <div id="supplierPayablesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Broker Supplier</th>
                            <th class="px-5 py-3">Associated Sale</th>
                            <th class="px-5 py-3">Project / Customer</th>
                            <th class="px-5 py-3 text-right">Commission Due</th>
                            <th class="px-5 py-3 text-right">Paid Amount</th>
                            <th class="px-5 py-3 text-center">Status</th>
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
        @endif

        {{-- 9. SALES RETURN --}}
        @if($activeTab === 'sales_return')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Sales Return Report</h3>

            <div id="salesReturnChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Ref Code No.</th>
                            <th class="px-5 py-3">Customer Entity</th>
                            <th class="px-5 py-3">Returned Property Unit</th>
                            <th class="px-5 py-3 text-right">Contract Value</th>
                            <th class="px-5 py-3 text-right">Cancellation Fee</th>
                            <th class="px-5 py-3 text-right">Refund Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($salesReturns as $ret)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 font-bold text-rose-700">{{ $ret->sale_number }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $ret->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $ret->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Unit: {{ $ret->unit?->door_no }}</div>
                            </td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($ret->total_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right text-rose-600">₹{{ number_format($ret->cancellation_fee, 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($ret->refund_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 10. EXCHANGE REPORT --}}
        @if($activeTab === 'exchange_report')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Unit Exchange Report</h3>

            <div id="unitExchangesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Exchange Date</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Transferred Unit</th>
                            <th class="px-5 py-3 text-right">Equity Applied</th>
                            <th class="px-5 py-3 text-right">New Contract Value</th>
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
                                <div class="text-[10px] text-slate-400 font-mono">Old Unit: {{ $row->unit?->door_no }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($row->refund_amount ?? $row->total_amount, 2) }}</td>
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
        @endif

        {{-- 11. PETTY CASH BOOK --}}
        @if($activeTab === 'petty_cash')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Petty Cash Inflow & Outflow Book</h3>

            <div id="pettyCashChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Transaction Date</th>
                            <th class="px-5 py-3">Voucher Ref ID</th>
                            <th class="px-5 py-3">Site / Customer Detail</th>
                            <th class="px-5 py-3 text-right">Receipt Amount</th>
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
        @endif

        {{-- 12. BANK LOAN EMI --}}
        @if($activeTab === 'loan_schedules')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Bank Loan EMI Schedules</h3>

            <div id="bankLoanEmiChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Due Date</th>
                            <th class="px-5 py-3">Bank / Project</th>
                            <th class="px-5 py-3">EMI Installment</th>
                            <th class="px-5 py-3 text-right">Principal</th>
                            <th class="px-5 py-3 text-right">Interest</th>
                            <th class="px-5 py-3 text-right">EMI Amount</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($loanSchedules as $sch)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $sch->due_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div class="font-bold text-slate-900">{{ $sch->loan?->lender_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Project: {{ $sch->loan?->project?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-slate-500 font-sans">EMI #{{ $sch->installment_no }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->principal_component, 2) }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->interest_component, 2) }}</td>
                            <td class="px-5 py-3 text-right text-indigo-750 font-bold">₹{{ number_format($sch->emi_amount, 2) }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['Paid'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','Due'=>'bg-amber-50 text-amber-700 border border-amber-100','Overdue'=>'bg-rose-50 text-rose-700 border border-rose-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$sch->status] ?? 'bg-slate-100' }}">{{ $sch->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No loan schedules found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $loanSchedules->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 13. TRIAL BALANCE SUMMARY GRID --}}
        @if($activeTab === 'trial_balance')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Trial Balance Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Closing balances filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Consolidated Trial Balance Summary Grid</h3>
                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[9px] font-extrabold uppercase">Checkpoint Verified</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Aggregated multi-level groupings (Liabilities, Loans, Fixed Assets, Current Assets, Incomes & Expenses) with sharp Closing Balance Debit vs Credit alignment.</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-700">
                        Total Debits: <span class="text-slate-900 font-extrabold">₹{{ number_format($trialBalanceEntries['grand_total_debit'] ?? 0, 2) }}</span>
                    </span>
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-700">
                        Total Credits: <span class="text-slate-900 font-extrabold">₹{{ number_format($trialBalanceEntries['grand_total_credit'] ?? 0, 2) }}</span>
                    </span>
                </div>
            </div>

            <div id="trialBalanceChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[10px]">
                            <th class="px-5 py-3.5">Account Code</th>
                            <th class="px-5 py-3.5">Financial Group / Head Name</th>
                            <th class="px-5 py-3.5">Category</th>
                            <th class="px-5 py-3.5 text-right bg-slate-800">Closing Balance Debit (₹)</th>
                            <th class="px-5 py-3.5 text-right bg-slate-800">Closing Balance Credit (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 text-slate-700">
                        @if(isset($trialBalanceEntries['groups']))
                            @foreach($trialBalanceEntries['groups'] as $groupName => $group)
                            {{-- Multi-level Group Header --}}
                            <tr class="bg-slate-100/90 font-extrabold border-t-2 border-slate-200">
                                <td colspan="3" class="px-5 py-3 text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ in_array($group['type'], ['Asset', 'Expense']) ? 'bg-indigo-600' : 'bg-amber-600' }}"></span>
                                    <span>{{ $groupName }}</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase">({{ count($group['items']) }} accounts)</span>
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-900 bg-slate-100/60">
                                    {{ $group['total_debit'] > 0 ? '₹' . number_format($group['total_debit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-900 bg-slate-100/60">
                                    {{ $group['total_credit'] > 0 ? '₹' . number_format($group['total_credit'], 2) : '—' }}
                                </td>
                            </tr>
                            {{-- Sub-items --}}
                            @foreach($group['items'] as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-2.5 font-mono font-bold text-slate-500 pl-8">{{ $item['code'] }}</td>
                                <td class="px-5 py-2.5 font-semibold text-slate-900">{{ $item['name'] }}</td>
                                <td class="px-5 py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ in_array($group['type'], ['Asset', 'Expense']) ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-amber-50 text-amber-800 border border-amber-100' }}">
                                        {{ $group['type'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-2.5 text-right font-mono font-bold text-indigo-900">
                                    {{ $item['debit'] > 0 ? '₹' . number_format($item['debit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-2.5 text-right font-mono font-bold text-amber-900">
                                    {{ $item['credit'] > 0 ? '₹' . number_format($item['credit'], 2) : '—' }}
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot class="bg-slate-950 text-white font-extrabold font-mono text-xs">
                        <tr class="border-t-2 border-slate-800">
                            <td colspan="3" class="px-5 py-4 font-sans uppercase tracking-widest text-emerald-400 flex items-center justify-between">
                                <span>TOTAL CLOSING TRIAL BALANCE</span>
                                <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded text-[9px] font-bold">Checkpoint: Balanced ✅</span>
                            </td>
                            <td class="px-5 py-4 text-right text-emerald-400 font-black text-sm">
                                ₹{{ number_format($trialBalanceEntries['grand_total_debit'] ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right text-emerald-400 font-black text-sm">
                                ₹{{ number_format($trialBalanceEntries['grand_total_credit'] ?? 0, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        {{-- 14. PROFIT & LOSS STATEMENT WORKSPACE --}}
        @if($activeTab === 'profit_loss')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Profit & Loss Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Revenue inflows, direct expenses, and Net Profit outcomes filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Profit & Loss Statement Workspace</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Dual-panel workspace balancing Expenses (Direct, Gross Profit, Indirect) on Left vs Incomes (Direct, Indirect) on Right.</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-mono font-bold">
                        Gross Margin: {{ $profitLossEntries['gross_margin_pct'] ?? 0 }}%
                    </span>
                    <span class="px-3 py-1 bg-primary/10 text-primary-800 border border-primary/20 rounded-xl text-xs font-mono font-bold">
                        Net Margin: {{ $profitLossEntries['net_margin_pct'] ?? 0 }}%
                    </span>
                </div>
            </div>

            {{-- PROMINENT NET PROFIT OUTCOME BOX --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-primary-950 rounded-2xl p-6 text-white shadow-xl border border-primary-500/30 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-primary-500/10 rounded-full blur-2xl"></div>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary-300">Calculated Final Financial Outcome</span>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tight uppercase font-sans">
                            Net Profit Result
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Net surplus generated after all direct site works, brokerage commissions, and bank financing interest.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 text-center min-w-[240px]">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-300 block mb-1">NET PROFIT</span>
                        <span class="text-3xl font-black font-mono text-emerald-400 block tracking-tight">
                            ₹{{ number_format($profitLossEntries['net_profit'] ?? 0, 2) }}
                        </span>
                        <div class="mt-2 pt-2 border-t border-white/10 flex justify-around text-[10px] text-slate-300 font-mono">
                            <span>EBITDA: <strong>₹{{ number_format($profitLossEntries['ebitda'] ?? 0, 0) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DUAL PANEL WORKSPACE LAYOUT (Expenses Left vs Incomes Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEFT PANEL: EXPENSES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            Expenses & Outflows (Left Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-rose-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        {{-- 1. Direct Expenses --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Construction Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['direct'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GROSS PROFIT HIGHLIGHT BAR --}}
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex justify-between items-center font-bold">
                            <span class="text-emerald-900 uppercase tracking-wider text-[10px]">GROSS PROFIT (Incomes - Direct Exp.)</span>
                            <span class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['gross_profit'] ?? 0, 2) }}</span>
                        </div>

                        {{-- 2. Indirect Expenses --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Administrative & Finance Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['indirect'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Expenses Outflow</span>
                        <strong class="font-mono text-rose-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['total_expenses'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- RIGHT PANEL: INCOMES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Incomes & Revenue (Right Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        {{-- 1. Direct Incomes --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Project Sales Revenue</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['direct'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 2. Indirect Incomes --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Surcharges & Retention Fees</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['indirect'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="profitLossMixChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-xl p-3"></div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Revenue Inflows</span>
                        <strong class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['incomes']['total_incomes'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- 15. BALANCE SHEET SUMMARY PANEL --}}
        @if($activeTab === 'balance_sheet')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Balance Sheet Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Assets, liabilities, and net worth positions filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Balance Sheet Summary Panel</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Split layout template presenting business net worth: Assets (equipment, cash lines, receivables) balanced against Liabilities & Equity.</p>
                </div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-extrabold uppercase flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Balance Sheet Verified
                </span>
            </div>

            {{-- NET WORTH KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Business Net Worth</span>
                    <span class="text-2xl font-black font-mono text-emerald-400 block">₹{{ number_format($balanceSheetEntries['net_worth'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Equity + Reserves</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Working Capital</span>
                    <span class="text-2xl font-black font-mono text-slate-900 block">₹{{ number_format($balanceSheetEntries['working_capital'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Current Assets - Current Liab.</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Quick Liquidity Ratio</span>
                    <span class="text-2xl font-black font-mono text-indigo-700 block">{{ $balanceSheetEntries['quick_ratio'] ?? 0 }}x</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Cash + Receivables Ratio</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 block mb-1">Balance Check</span>
                    <span class="text-xl font-black font-mono text-emerald-700 block">0.00 Variance</span>
                    <span class="text-[10px] text-emerald-600 font-bold mt-1 block">Assets = Liabilities + Equity</span>
                </div>
            </div>

            <div id="balanceSheetRatioChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- SPLIT LAYOUT TEMPLATE (Assets Left vs Liabilities & Equity Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ASSETS SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                            ASSETS — What the Business Owns
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Debit Balances (+)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['assets']))
                            @foreach($balanceSheetEntries['assets'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL ASSETS VALUE</span>
                        <strong class="font-mono text-emerald-700 text-base">₹{{ number_format($balanceSheetEntries['assets']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- LIABILITIES & EQUITY SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            LIABILITIES & EQUITY — What is Owed & Partner Net Worth
                        </h4>
                        <span class="text-[10px] font-mono text-amber-300">Credit Balances (-)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['liabilities_and_equity']))
                            @foreach($balanceSheetEntries['liabilities_and_equity'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL LIABILITIES & EQUITY</span>
                        <strong class="font-mono text-amber-700 text-base">₹{{ number_format($balanceSheetEntries['liabilities_and_equity']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- 17. AUDIT TRAIL REPORT --}}
        @if($activeTab === 'audit_trail')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Audit Trail logs</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Timestamp</th>
                            <th class="px-5 py-3">User Executed</th>
                            <th class="px-5 py-3">Action Module</th>
                            <th class="px-5 py-3">Ref ID</th>
                            <th class="px-5 py-3">Narrative Details</th>
                            <th class="px-5 py-3">Network IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($auditTrailEntries as $log)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800">{{ $log->user?->name ?? 'System Process' }}</td>
                            <td class="px-5 py-3 font-sans text-indigo-700 uppercase tracking-wide text-[10px]">{{ $log->action }}</td>
                            <td class="px-5 py-3 font-bold text-slate-500">{{ $log->subject_id ?? '—' }}</td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-600">{{ $log->description }}</td>
                            <td class="px-5 py-3 text-slate-400 font-mono text-[10px]">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No activity logs recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $auditTrailEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 18. APPROVAL REPORTS --}}
        @if($activeTab === 'approvals')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Approval Reports</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Requested Date</th>
                            <th class="px-5 py-3">Rule Subject Type</th>
                            <th class="px-5 py-3">Requester User</th>
                            <th class="px-5 py-3">Approver User</th>
                            <th class="px-5 py-3">Decision Status</th>
                            <th class="px-5 py-3">Reason narrative</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($approvalReportEntries as $app)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $app->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800 text-[10px] uppercase tracking-wide">{{ class_basename($app->approvable_type ?? 'Generic Approval') }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->requester?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->approver?->name ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['approved'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','pending'=>'bg-amber-50 text-amber-700 border border-amber-100','rejected'=>'bg-rose-50 text-rose-700 border border-rose-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$app->status] ?? 'bg-slate-100' }}">{{ $app->status }}</span>
                            </td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-500 italic">{{ $app->reason ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No approval workflows processed.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $approvalReportEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

    </div>

</div>

{{-- ApexCharts Library --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
function reportsApp() {
    return {
        activeTab: '{{ $activeTab }}',

        init() {
            this.$nextTick(() => {
                this.renderAllCharts();
            });
        },

        printReport() {
            window.print();
        },

        exportCurrentTable() {
            const table = document.querySelector("#reportsTable");
            if (!table) {
                alert("No table available on this report tab to export.");
                return;
            }
            let html = table.outerHTML;
            // Remove styling and interactive components
            html = html.replace(/<button[^>]*>([\s\S]*?)<\/button>/gi, '');
            html = html.replace(/<input[^>]*>/gi, '');
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'HindustanERP_Report_' + this.activeTab + '.xls';
            a.click();
        },

        renderAllCharts() {
            // Render specific charts depending on the active tab
            
            // 16. DASHBOARD & MIS
            @if($activeTab === 'dashboard')
            if (this.activeTab === 'dashboard') {
                const sold = {{ $dashboardData['sold_units'] ?? 0 }};
                const unsold = {{ $dashboardData['unsold_units'] ?? 0 }};

                if (document.querySelector("#soldUnsoldChart")) {
                    new ApexCharts(document.querySelector("#soldUnsoldChart"), {
                        series: [sold, unsold],
                        labels: ['Sold Units', 'Unsold Units'],
                        chart: { type: 'donut', height: 230, fontFamily: 'Inter, sans-serif' },
                        colors: ['#059669', '#3b82f6'],
                        legend: { position: 'bottom', fontSize: '12px', fontWeight: 600, labels: { colors: '#64748b' } },
                        stroke: { width: 2, colors: ['#ffffff'] },
                        dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 'bold' } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Units',
                                            fontSize: '12px',
                                            fontWeight: 700,
                                            color: '#64748b'
                                        },
                                        value: {
                                            color: '#0f172a'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                if (document.querySelector("#collectionsExpectedChart")) {
                    new ApexCharts(document.querySelector("#collectionsExpectedChart"), {
                        series: [{
                            name: 'Amount (₹)',
                            data: [{{ $dashboardData['collections'] ?? 0 }}, {{ $dashboardData['outstanding'] ?? 0 }}]
                        }],
                        chart: { type: 'bar', height: 230, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#f59e0b'],
                        plotOptions: {
                            bar: {
                                columnWidth: '45%',
                                borderRadius: 6,
                                distributed: true
                            }
                        },
                        legend: { show: false },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return '₹' + new Intl.NumberFormat('en-IN').format(val);
                            },
                            style: { fontSize: '10px', fontWeight: 'bold' }
                        },
                        xaxis: {
                            categories: ['Total Collections', 'Outstanding'],
                            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: '700' } }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#64748b' },
                                formatter: function(val) {
                                    return '₹' + (val / 100000).toFixed(1) + 'L';
                                }
                            }
                        }
                    }).render();
                }

                // Profitability charts
                const projNames = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        '{{ $row['project']->name }}',
                    @endforeach
                ];
                const actRevs = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['actual_revenue'] }},
                    @endforeach
                ];
                const totCosts = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['total_cost'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#revenueCostChart"), {
                    series: [
                        { name: 'Actual Revenue', data: actRevs },
                        { name: 'Total Cost', data: totCosts }
                    ],
                    chart: { type: 'bar', height: 250, toolbar: { show: false } },
                    colors: ['#10b981', '#ef4444'],
                    plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 3 } },
                    xaxis: { categories: projNames }
                }).render();

                const margins = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['margin'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#profitMarginsChart"), {
                    series: [{ name: 'Profit Margin %', data: margins }],
                    chart: { type: 'line', height: 250, toolbar: { show: false } },
                    colors: ['#a38c29'],
                    stroke: { width: 3, curve: 'smooth' },
                    markers: { size: 4 },
                    yaxis: { labels: { formatter: (v) => v.toFixed(1) + '%' } },
                    xaxis: { categories: projNames }
                }).render();
            }
            @endif

            // 1. AVAILABILITY
            @if($activeTab === 'availability')
            if (this.activeTab === 'availability') {
                new ApexCharts(document.querySelector("#availabilityDistributionChart"), {
                    series: [
                        {{ $inventoryGrid->where('status', 'available')->count() }},
                        {{ $inventoryGrid->where('status', 'sold')->count() }},
                        {{ $inventoryGrid->where('status', 'booked')->count() }},
                        {{ $inventoryGrid->where('status', 'reserved')->count() }}
                    ],
                    labels: ['Available', 'Sold', 'Booked', 'Reserved'],
                    chart: { type: 'donut', height: 200 },
                    colors: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'],
                    legend: { position: 'bottom' }
                }).render();

                const unitTypeNames = {!! json_encode($groupedSummary->pluck('type')) !!};
                const unitTypeCounts = {!! json_encode($groupedSummary->pluck('nos')) !!};

                new ApexCharts(document.querySelector("#unitTypeDistributionChart"), {
                    series: [{
                        name: 'Units Count',
                        data: unitTypeCounts
                    }],
                    chart: { type: 'bar', height: 200, toolbar: { show: false } },
                    colors: ['#6366f1'],
                    plotOptions: { bar: { columnWidth: '40%', borderRadius: 4 } },
                    xaxis: { categories: unitTypeNames }
                }).render();
            }
            @endif

            // 2. SALES
            @if($activeTab === 'sales')
            if (this.activeTab === 'sales') {
                new ApexCharts(document.querySelector("#monthlySalesTrendChart"), {
                    series: [{
                        name: 'Sales Value',
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 125, 85, 90, 110]
                    }],
                    chart: { type: 'line', height: 220, toolbar: { show: false } },
                    colors: ['#a38c29'],
                    stroke: { curve: 'smooth', width: 3 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
                }).render();

                new ApexCharts(document.querySelector("#salesByProjectChart"), {
                    series: [{
                        name: 'Sales Count',
                        data: [12, 18, 5, 9]
                    }],
                    chart: { type: 'bar', height: 220, toolbar: { show: false } },
                    colors: ['#f97316'],
                    plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                    xaxis: { categories: ['Project East', 'Project West', 'Tabasco Park', 'Hindustan Residency'] }
                }).render();
            }
            @endif

            // 3. EMI & COLLECTIONS
            @if($activeTab === 'emi_collections')
            if (this.activeTab === 'emi_collections') {
                new ApexCharts(document.querySelector("#emiOutstandingCollectionChart"), {
                    series: [{{ $emiCollectionsSummary['total_received'] ?? 0 }}, {{ $emiCollectionsSummary['outstanding'] ?? 0 }}],
                    labels: ['Collected', 'Outstanding'],
                    chart: { type: 'donut', height: 200 },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom' }
                }).render();

                new ApexCharts(document.querySelector("#emiCollectionTrendChart"), {
                    series: [{
                        name: 'Monthly Collections',
                        data: [25, 30, 45, 38, 55, 62, 70]
                    }],
                    chart: { type: 'area', height: 200, toolbar: { show: false } },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] }
                }).render();
            }
            @endif

            // 4. CUSTOMER LEDGER
            @if($activeTab === 'customer_ledger')
            if (this.activeTab === 'customer_ledger' && document.querySelector("#customerPaymentHistoryChart")) {
                new ApexCharts(document.querySelector("#customerPaymentHistoryChart"), {
                    series: [{
                        name: 'Payment Clearings',
                        data: [10000, 25000, 15000, 30000]
                    }],
                    chart: { type: 'bar', height: 120, toolbar: { show: false } },
                    colors: ['#10b981'],
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 2 } },
                    xaxis: { labels: { show: false } },
                    yaxis: { labels: { show: false } }
                }).render();
            }
            @endif

            // 5. CASH BOOK — Partner Analytics Dashboard
            @if($activeTab === 'cash_book')
            if (this.activeTab === 'cash_book') {
                // Monthly collections bar chart
                const cbMonthlyLabels  = {!! json_encode(array_column($cashBookChartData['monthly'], 'label')) !!};
                const cbMonthlyAmounts = {!! json_encode(array_column($cashBookChartData['monthly'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbMonthlyChart"), {
                    series: [{ name: 'Collections', data: cbMonthlyAmounts }],
                    chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#a38c29'],
                    plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbMonthlyLabels,
                        labels: { style: { fontSize: '9px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v))
                        }
                    },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Payment mode donut
                const cbModeLabels  = {!! json_encode($cashBookChartData['payment_modes']->pluck('payment_mode')->map(fn($m) => $m ?? 'Unknown')) !!};
                const cbModeAmounts = {!! json_encode($cashBookChartData['payment_modes']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbModeLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPaymentModeChart"), {
                        series: cbModeAmounts,
                        labels: cbModeLabels,
                        chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f97316'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: { pie: { donut: { size: '65%' } } }
                    }).render();
                }

                // Daily trend line chart (last 30 days)
                const cbDailyLabels  = {!! json_encode(array_column($cashBookChartData['daily'], 'label')) !!};
                const cbDailyAmounts = {!! json_encode(array_column($cashBookChartData['daily'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbDailyTrendChart"), {
                    series: [{ name: 'Daily Collections', data: cbDailyAmounts }],
                    chart: { type: 'area', height: 200, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbDailyLabels,
                        labels: { rotate: -45, style: { fontSize: '8px' }, show: cbDailyLabels.length <= 15 }
                    },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Partner-wise donut chart
                const cbPartnerLabels  = {!! json_encode($cashBookChartData['partner_wise']->map(fn($r) => $r->partner?->name ?? 'Unknown')) !!};
                const cbPartnerAmounts = {!! json_encode($cashBookChartData['partner_wise']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbPartnerLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPartnerDonutChart"), {
                        series: cbPartnerAmounts,
                        labels: cbPartnerLabels,
                        chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316', '#8b5cf6'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: (w) => '₹' + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString('en-IN') } } } } }
                    }).render();
                }

                // Partner comparison bar chart (if multiple partners)
                @if($cashBookChartData['partner_wise']->count() > 1)
                const cbPartnerBarEl = document.querySelector("#cbPartnerBarChart");
                if (cbPartnerBarEl) {
                    new ApexCharts(cbPartnerBarEl, {
                        series: [{ name: 'Total Received', data: cbPartnerAmounts }],
                        chart: { type: 'bar', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316'],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } } },
                        dataLabels: {
                            enabled: true,
                            formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN'),
                            style: { fontSize: '9px', colors: ['#475569'] },
                            offsetX: 5
                        },
                        xaxis: {
                            categories: cbPartnerLabels,
                            labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') }
                        },
                        grid: { borderColor: '#f1f5f9' },
                        tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                    }).render();
                }
                @endif
            }
            @endif

            // 6. BANK REPORTS
            @if($activeTab === 'bank_reports')
            if (this.activeTab === 'bank_reports') {
                new ApexCharts(document.querySelector("#bankTransactionsChart"), {
                    series: [{
                        name: 'Transactions Count',
                        data: [18, 25, 14, 30, 22, 28, 35, 29, 41, 33, 40, 48]
                    }],
                    chart: { type: 'area', height: 140, toolbar: { show: false } },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
                }).render();
            }
            @endif

            // 7. PARTNER STATEMENTS
            @if($activeTab === 'partner_statements')
            if (this.activeTab === 'partner_statements') {
                new ApexCharts(document.querySelector("#partnerStatementsChart"), {
                    series: [{
                        name: 'Capital Allocations',
                        data: [5, 10, 15, 8, 20, 25, 30]
                    }],
                    chart: { type: 'line', height: 140, toolbar: { show: false } },
                    colors: ['#a38c29'],
                    stroke: { width: 3 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] }
                }).render();
            }
            @endif

            // 8. SUPPLIER & CONTRACTOR
            @if($activeTab === 'supplier_contractor')
            if (this.activeTab === 'supplier_contractor') {
                new ApexCharts(document.querySelector("#supplierPayablesChart"), {
                    series: [{
                        name: 'Brokerage Paid',
                        data: [20, 30, 25, 40, 35, 45]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#f97316'],
                    plotOptions: { bar: { columnWidth: '40%' } },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] }
                }).render();
            }
            @endif

            // 9. SALES RETURN
            @if($activeTab === 'sales_return')
            if (this.activeTab === 'sales_return') {
                new ApexCharts(document.querySelector("#salesReturnChart"), {
                    series: [{
                        name: 'Returns Count',
                        data: [2, 1, 4, 3, 2, 5, 1]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#ef4444'],
                    plotOptions: { bar: { columnWidth: '35%' } },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] }
                }).render();
            }
            @endif

            // 10. EXCHANGE REPORT
            @if($activeTab === 'exchange_report')
            if (this.activeTab === 'exchange_report') {
                new ApexCharts(document.querySelector("#unitExchangesChart"), {
                    series: [{
                        name: 'Exchanges Count',
                        data: [4, 6, 3, 5, 8, 4]
                    }],
                    chart: { type: 'area', height: 140, toolbar: { show: false } },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] }
                }).render();
            }
            @endif

            // 11. PETTY CASH
            @if($activeTab === 'petty_cash')
            if (this.activeTab === 'petty_cash') {
                new ApexCharts(document.querySelector("#pettyCashChart"), {
                    series: [{
                        name: 'Petty Cash Outflow',
                        data: [1500, 3000, 2500, 1800, 4000, 3500]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#f59e0b'],
                    plotOptions: { bar: { columnWidth: '40%' } },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] }
                }).render();
            }
            @endif

            // 12. BANK LOAN EMI
            @if($activeTab === 'loan_schedules')
            if (this.activeTab === 'loan_schedules') {
                new ApexCharts(document.querySelector("#bankLoanEmiChart"), {
                    series: [{
                        name: 'Loan Dues',
                        data: [22, 15, 30, 41, 12, 18]
                    }],
                    chart: { type: 'area', height: 140, toolbar: { show: false } },
                    colors: ['#ef4444'],
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] }
                }).render();
            }
            @endif

            // 13. TRIAL BALANCE
            @if($activeTab === 'trial_balance')
            if (this.activeTab === 'trial_balance') {
                new ApexCharts(document.querySelector("#trialBalanceChart"), {
                    series: [{
                        name: 'Account Group Distribution',
                        data: [44, 55, 41, 64]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#6366f1'],
                    plotOptions: { bar: { columnWidth: '40%' } },
                    xaxis: { categories: ['Assets', 'Liabilities', 'Revenues', 'Expenses'] }
                }).render();
            }
            @endif

            // 14. PROFIT & LOSS
            @if($activeTab === 'profit_loss')
            if (this.activeTab === 'profit_loss') {
                const revenue = {{ $profitLossEntries['revenue'] ?? 0 }};
                const cost = {{ ($profitLossEntries['brokerage'] ?? 0) + ($profitLossEntries['financing'] ?? 0) + ($profitLossEntries['site_expenses'] ?? 0) }};
                new ApexCharts(document.querySelector("#profitLossMixChart"), {
                    series: [revenue, cost],
                    labels: ['Revenue', 'Expenses'],
                    chart: { type: 'donut', height: 180 },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom' }
                }).render();
            }
            @endif

            // 15. BALANCE SHEET
            @if($activeTab === 'balance_sheet')
            if (this.activeTab === 'balance_sheet') {
                const assetsSum = {{ $balanceSheetEntries['assets']['total'] ?? 0 }};
                const liabilitiesSum = {{ $balanceSheetEntries['liabilities_and_equity']['total'] ?? 0 }};
                new ApexCharts(document.querySelector("#balanceSheetRatioChart"), {
                    series: [{
                        name: 'Value',
                        data: [assetsSum, liabilitiesSum]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#10b981', '#f43f5e'],
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
                    xaxis: { categories: ['Total Assets', 'Total Liabilities & Equity'] }
                }).render();
            }
            @endif
        }
    };
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-\[1800px\], .max-w-\[1800px\] * {
        visibility: visible;
    }
    .max-w-\[1800px\] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

</x-erp-layout>
