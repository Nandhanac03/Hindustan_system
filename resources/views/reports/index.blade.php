<x-erp-layout title="Business Performance Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        {{-- TAB 1: AVAILABILITY REPORT --}}
        @if($activeTab === 'availability')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Property Availability Matrix</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time counts, carpet areas, and dynamic availability grids per floor.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="printReport()" class="px-3.5 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print PDF
                    </button>
                </div>
            </div>

            {{-- Summary stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($inventorySummary as $is)
                <div class="bg-slate-50 rounded-2xl border border-slate-150 p-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">{{ $is->unitType?->name ?? 'Other' }}</span>
                    <span class="text-xl font-extrabold text-slate-900 mt-1 block font-mono">{{ $is->available }} / {{ $is->count }} Available</span>
                    <span class="text-[10px] text-slate-450 block mt-1">Area: {{ number_format((float)$is->total_built_up, 1) }} sq.ft</span>
                </div>
                @endforeach
            </div>

            {{-- Grid Filter --}}
            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="availability">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Unit Type</label>
                    <select name="unit_type_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Types</option>
                        @foreach($unitTypes as $ut)
                            <option value="{{ $ut->id }}" {{ request('unit_type_id') == $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Search</button>
                    <a href="?report=availability" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Door No.</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Project</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Floor</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Type</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Built Area</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Carpet Area</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Availability</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650">
                        @forelse($inventoryGrid as $row)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-bold text-slate-900 font-mono">{{ $row->door_no }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-700">{{ $row->project?->name }}</td>
                            <td class="px-5 py-3 font-medium text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                            <td class="px-5 py-3 font-semibold text-indigo-750">{{ $row->unitType?->name }}</td>
                            <td class="px-5 py-3 text-right font-mono">{{ number_format($row->built_up_area, 2) }} sqft</td>
                            <td class="px-5 py-3 text-right font-mono">{{ number_format($row->carpet_area, 2) }} sqft</td>
                            <td class="px-5 py-3">
                                @php $sc = ['available'=>'bg-emerald-50 text-emerald-700','sold'=>'bg-rose-50 text-rose-700','booked'=>'bg-amber-50 text-amber-700','reserved'=>'bg-blue-50 text-blue-700']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $sc[$row->status] ?? 'bg-slate-100' }}">{{ $row->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No inventory matching filter criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $inventoryGrid->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 2: SALES REPORT --}}
        @if($activeTab === 'sales')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Real-time Sales Register</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Realized agreement sales for commercial, apartments, and parking properties.</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="sales">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Categories</option>
                        <option value="Commercial" {{ request('category') == 'Commercial' ? 'selected' : '' }}>Commercial Breakup</option>
                        <option value="Apartment" {{ request('category') == 'Apartment' ? 'selected' : '' }}>Apartment Breakup</option>
                        <option value="Parking" {{ request('category') == 'Parking' ? 'selected' : '' }}>Parking Breakup</option>
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Search</button>
                    <a href="?report=sales" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Sale Number</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Project / Unit</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Category</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Sale Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($salesList as $sale)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-bold text-indigo-700">{{ $sale->sale_number }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-slate-800">{{ $sale->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $sale->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Unit: {{ $sale->unit?->door_no }}</div>
                            </td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-550">{{ $sale->unit?->unitType?->category ?? 'Apartment' }}</td>
                            <td class="px-5 py-3 text-right text-slate-500 font-sans">{{ $sale->sale_date?->format('d M Y') }}</td>
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

        {{-- TAB 3: EMI & COLLECTIONS --}}
        @if($activeTab === 'emi_collections')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">EMI Outstanding & Collection Ledger</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time collections summary and month-to-date inflows.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-50 rounded-2xl border border-slate-150 p-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Receivable Value</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">₹{{ number_format($emiCollectionsSummary['total_receivable'], 0) }}</span>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-150 p-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Receipts Collected</span>
                    <span class="text-2xl font-extrabold text-emerald-700 mt-1 block font-mono">₹{{ number_format($emiCollectionsSummary['total_received'], 0) }}</span>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-150 p-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Net Outstanding Balance</span>
                    <span class="text-2xl font-extrabold text-rose-700 mt-1 block font-mono">₹{{ number_format($emiCollectionsSummary['outstanding'], 0) }}</span>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-150 p-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Collected This Month</span>
                    <span class="text-2xl font-extrabold text-indigo-700 mt-1 block font-mono">₹{{ number_format($emiCollectionsSummary['mtd_collections'], 0) }}</span>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher Ref</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Details</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Payment Mode</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Inflow Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($cashBookEntries as $receipt)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div class="font-semibold text-slate-900">{{ $receipt->customer?->name }}</div>
                            </td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-150 font-bold">{{ $receipt->payment_mode }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-600 font-bold">₹{{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No collections found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- TAB 4: CUSTOMER LEDGER --}}
        @if($activeTab === 'customer_ledger')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Customer Ledger Statement</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Mandatory customer filter statement — chronological listing of dues, bookings, and receipt clearings.</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="customer_ledger">
                <div class="sm:col-span-2">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Select Customer (Mandatory) *</label>
                    <select name="customer_id" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">-- Choose Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Generate Statement</button>
                    <a href="?report=customer_ledger" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Clear</a>
                </div>
            </form>

            @if($selectedCustomer)
            <div class="space-y-4">
                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-200 flex justify-between items-center text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px] block">Customer Name</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $selectedCustomer->name }}</strong>
                        <span class="text-slate-500 block">{{ $selectedCustomer->email }} &bull; {{ $selectedCustomer->phone }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px] block">Statement Outstanding</span>
                        <strong class="text-rose-600 font-mono text-lg block mt-0.5">₹{{ number_format($closingBalance, 2) }}</strong>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Posting Date</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher / Ref No.</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Narrative</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Mode</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Debit (Due)</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Credit (Receipt)</th>
                                <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                            @forelse($ledgerEntries as $row)
                            <tr>
                                <td class="px-5 py-3 text-slate-500 font-sans">{{ $row['date'] }}</td>
                                <td class="px-5 py-3 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                                <td class="px-5 py-3 font-semibold text-slate-800 font-sans">{{ $row['description'] }}</td>
                                <td class="px-5 py-3 text-slate-500 font-sans">{{ $row['payment_mode'] }}</td>
                                <td class="px-5 py-3 text-right text-rose-600 font-bold">
                                    {{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-3 text-right text-emerald-600 font-bold">
                                    {{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-3 text-right text-slate-900 font-extrabold">
                                    ₹{{ number_format(abs($row['balance']), 2) }} {{ $row['balance'] >= 0 ? 'DR' : 'CR' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400 italic">No ledger transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold font-mono">
                            <tr class="border-t border-slate-200">
                                <td colspan="4" class="px-5 py-3 font-sans font-bold">CLOSING BALANCE SUMMARY</td>
                                <td class="px-5 py-3 text-right text-rose-700">₹{{ number_format($totalDebits, 2) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($totalCredits, 2) }}</td>
                                <td class="px-5 py-3 text-right {{ $closingBalance > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                    ₹{{ number_format(abs($closingBalance), 2) }} DR
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @else
            <div class="p-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-150 italic">
                Please select a customer from the dropdown above to load the Ledger Statement.
            </div>
            @endif
        </div>
        @endif

        {{-- TAB 5: CASH BOOK --}}
        @if($activeTab === 'cash_book')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Consolidated Cash Book</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Value date listing of cash and bank transfer collections.</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="cash_book">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Mode</label>
                    <select name="payment_mode" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Modes</option>
                        <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Cheque" {{ request('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Bank Transfer" {{ request('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Online" {{ request('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Search</button>
                    <a href="?report=cash_book" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher Ref</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Details</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Narrative</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Mode</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Debit (Inflow)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($cashBookEntries as $receipt)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div class="font-bold text-slate-800">{{ $receipt->customer?->name }}</div>
                            </td>
                            <td class="px-5 py-3 font-sans text-slate-600">
                                Collection Receipt linked to {{ $receipt->sale?->project?->name }} (Unit: {{ $receipt->sale?->unit?->door_no }})
                            </td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-1.5 py-0.5 text-[10px] rounded bg-slate-100 font-bold">{{ $receipt->payment_mode }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-600 font-bold">₹{{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No transaction records found matching active filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $cashBookEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 6: BANK REPORTS --}}
        @if($activeTab === 'bank_reports')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Clearing Bank Inflows</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Consolidated lists of bank collections (cheques, bank transfers, online gateway payments).</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="bank_reports">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Clearing Bank Name</label>
                    <input type="text" name="bank_name" value="{{ request('bank_name') }}" placeholder="e.g. HDFC Bank" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Search</button>
                    <a href="?report=bank_reports" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher Ref</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Details</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Clearing Bank</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Ref No.</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Credit (Inflow)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($bankReportEntries as $receipt)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-slate-800">{{ $receipt->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-600">{{ $receipt->bank_name ?? 'Not Specified' }}</td>
                            <td class="px-5 py-3 text-slate-550 font-sans">{{ $receipt->reference_no ?? '—' }}</td>
                            <td class="px-5 py-3 text-right text-emerald-600 font-bold">₹{{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No bank collections found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $bankReportEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 7: PARTNER STATEMENTS --}}
        @if($activeTab === 'partner_statements')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Partner Shares & Allocations</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time split calculations for booking collection deposits per partner.</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="partner_statements">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Partner</label>
                    <select name="partner_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Partners</option>
                        @foreach($partners as $p)
                            <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Search</button>
                    <a href="?report=partner_statements" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Entry Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Partner</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Project Name</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Link</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Calculated Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($partnerAllocations as $alloc)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $alloc->date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-900">{{ $alloc->partner?->name }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-slate-700">{{ $alloc->project?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-550">{{ $alloc->payment?->customer?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right text-emerald-600 font-extrabold">₹{{ number_format($alloc->allocated_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No partner allocations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $partnerAllocations->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 8: SUPPLIER & CONTRACTOR STATEMENTS --}}
        @if($activeTab === 'supplier_contractor')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Supplier & Contractor Statements</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Payable commissions, site supplier liabilities, and contractor disbursement audits.</p>
                </div>
            </div>

            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-150">
                <input type="hidden" name="report" value="supplier_contractor">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mb-1">Select Broker/Supplier</label>
                    <select name="broker_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none cursor-pointer">
                        <option value="">All Suppliers</option>
                        @foreach($brokers as $b)
                            <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-700 uppercase tracking-wider">Generate Statement</button>
                    <a href="?report=supplier_contractor" class="px-4 py-2 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-350 uppercase tracking-wider">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Payee Name</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Linked Unit</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Commission Amount</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Paid to Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Balance Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($supplierContractorEntries as $row)
                        @php
                            $comm = (float)$row->commission_amount;
                            $paid = (float)$row->paid_amount;
                            $pending = max(0, $comm - $paid);
                        @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-sans font-bold text-slate-900">{{ $row->broker?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $row->sale?->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Unit: {{ $row->sale?->unit?->door_no }}</div>
                            </td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($comm, 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-605">₹{{ number_format($paid, 2) }}</td>
                            <td class="px-5 py-3 text-right text-rose-600 font-bold">₹{{ number_format($pending, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No supplier records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $supplierContractorEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 9: SALES RETURN SUMMARY --}}
        @if($activeTab === 'sales_return')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Sales Cancellations & Returns</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Summary of refunded bookings, initial values, and paid vs remaining liabilities.</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Unit No</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Name</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Project</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Initial Sale Value</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Paid to Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Refund Value</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Cancellation Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($salesReturns as $ret)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-bold text-slate-900 font-sans">Unit {{ $ret->unit?->door_no }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-slate-800">{{ $ret->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-500">{{ $ret->project?->name }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($ret->total_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-600">₹{{ number_format($ret->receipts->sum('amount'), 2) }}</td>
                            <td class="px-5 py-3 text-right text-rose-600 font-bold">₹{{ number_format($ret->receipts->sum('amount'), 2) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-400 italic text-[11px]">{{ $ret->cancellation_reason ?? 'Customer withdrew request' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 10: EXCHANGE REPORT --}}
        @if($activeTab === 'exchange_report')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Exchanged Properties Summary</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Active list of unit exchange transfers and replacement adjustments.</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher No</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Customer Details</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Project Name</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Unit Replaced</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Original Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($exchangeEntries as $exch)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-bold text-indigo-700">{{ $exch->sale_number }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-slate-800">{{ $exch->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-500">{{ $exch->project?->name }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-600">Unit {{ $exch->unit?->door_no }}</td>
                            <td class="px-5 py-3 text-right text-slate-900">₹{{ number_format($exch->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No exchanged properties records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $exchangeEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 11: PETTY CASH BOOK --}}
        @if($activeTab === 'petty_cash')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Daily Petty Cash Book</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time ledger list of cash receipts, disbursements, and running totals.</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Entry Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Voucher Ref</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Description</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Cash Debit (Inflow)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($pettyCashEntries as $petty)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $petty->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $petty->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-650">Collection Receipt — {{ $petty->customer?->name }} (Unit: {{ $petty->sale?->unit?->door_no }})</td>
                            <td class="px-5 py-3 text-right text-emerald-600 font-bold">₹{{ number_format($petty->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-400 italic">No petty cash records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $pettyCashEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- TAB 12: BANK LOAN EMI SCHEDULES --}}
        @if($activeTab === 'loan_schedules')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Bank Loan Repayment Schedules</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Amortization schedules and payment tracking for builder liabilities.</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Due Date</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Lender / Loan</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Installment No</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Principal component</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Interest component</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Total EMI Amount</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($loanSchedules as $sch)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $sch->due_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div class="font-bold text-slate-900">{{ $sch->loan?->lender_name }}</div>
                                <div class="text-[10px] text-slate-400">Project: {{ $sch->loan?->project?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-slate-500">EMI #{{ $sch->installment_no }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->principal_component, 2) }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->interest_component, 2) }}</td>
                            <td class="px-5 py-3 text-right text-indigo-750 font-bold">₹{{ number_format($sch->emi_amount, 2) }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['Paid'=>'bg-emerald-50 text-emerald-700','Due'=>'bg-amber-50 text-amber-700','Overdue'=>'bg-rose-50 text-rose-700']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $sc[$sch->status] ?? 'bg-slate-100' }}">{{ $sch->status }}</span>
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

        {{-- TAB 13: TRIAL BALANCE --}}
        @if($activeTab === 'trial_balance')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Consolidated Trial Balance</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Group statement audit of ledger debits and credits.</p>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Account Code</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Account Description Name</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest">Category Type</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Debit Balance (+)</th>
                            <th class="px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-right">Credit Balance (-)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($trialBalanceEntries as $tb)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-bold text-slate-500">{{ $tb['code'] }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800">{{ $tb['name'] }}</td>
                            <td class="px-5 py-3 font-sans font-semibold text-indigo-750">{{ $tb['type'] }}</td>
                            <td class="px-5 py-3 text-right text-rose-600">
                                {{ $tb['debit'] > 0 ? '₹' . number_format($tb['debit'], 2) : '—' }}
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-600">
                                {{ $tb['credit'] > 0 ? '₹' . number_format($tb['credit'], 2) : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No account entities found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold font-mono">
                        <tr class="border-t border-slate-200">
                            <td colspan="3" class="px-5 py-3 font-sans">TRIAL BALANCE NET TOTAL</td>
                            <td class="px-5 py-3 text-right text-rose-700">₹{{ number_format($trialBalanceEntries->sum('debit'), 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($trialBalanceEntries->sum('credit'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        {{-- TAB 14: PROFIT & LOSS --}}
        @if($activeTab === 'profit_loss')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Profit & Loss Statement</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Realized income totals vs operational expenses and net margins.</p>
                </div>
            </div>

            <div class="max-w-2xl mx-auto border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-slate-50 border-b border-slate-150 px-6 py-4">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Operational Income Statement</span>
                    <strong class="text-slate-800 text-sm font-bold block mt-0.5">Revenue & Expenditures Audit</strong>
                </div>
                <div class="p-6 space-y-4 text-xs text-slate-700 font-mono">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 font-sans">
                        <span class="font-bold text-slate-800">Gross Sales Revenue</span>
                        <strong class="text-emerald-600 font-mono font-extrabold text-sm">₹{{ number_format($profitLossEntries['revenue'], 2) }}</strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span>Less: Brokerage Commissions Paid</span>
                        <span class="text-rose-600 font-semibold">-₹{{ number_format($profitLossEntries['brokerage'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span>Less: Bank Financing Interest Paid</span>
                        <span class="text-rose-600 font-semibold">-₹{{ number_format($profitLossEntries['financing'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-50 rounded-xl px-4 mt-6 font-sans">
                        <strong class="text-slate-900 font-extrabold text-sm uppercase">Net Project Profit Margin</strong>
                        <strong class="text-primary-700 font-mono font-extrabold text-lg">₹{{ number_format($profitLossEntries['net_profit'], 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- TAB 15: BALANCE SHEET SUMMARY --}}
        @if($activeTab === 'balance_sheet')
        <div class="p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Balance Sheet Summary</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Asset accounts balanced against liabilities and capital distributions.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                {{-- Assets --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="bg-slate-50 border-b border-slate-150 px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-slate-500">Asset Accounts (+)</div>
                    <div class="p-5 space-y-4 text-xs font-mono text-slate-700 flex-1">
                        @foreach($balanceSheetEntries['assets'] as $name => $val)
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span>{{ $name }}</span>
                            <span class="text-slate-900 font-semibold">₹{{ number_format($val, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-slate-50/50 px-5 py-3 border-t border-slate-150 flex justify-between font-bold">
                        <span class="text-[9px] uppercase tracking-wider text-slate-500 font-sans">Total Assets</span>
                        <strong class="font-mono text-emerald-700">₹{{ number_format(array_sum($balanceSheetEntries['assets']), 2) }}</strong>
                    </div>
                </div>

                {{-- Liabilities --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="bg-slate-50 border-b border-slate-150 px-5 py-3 font-bold text-[9px] uppercase tracking-widest text-slate-500">Liabilities & Capital (-)</div>
                    <div class="p-5 space-y-4 text-xs font-mono text-slate-700 flex-1">
                        @foreach($balanceSheetEntries['liabilities'] as $name => $val)
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span>{{ $name }}</span>
                            <span class="text-slate-900 font-semibold">₹{{ number_format($val, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-slate-50/50 px-5 py-3 border-t border-slate-150 flex justify-between font-bold">
                        <span class="text-[9px] uppercase tracking-wider text-slate-500 font-sans">Total Liabilities & Equity</span>
                        <strong class="font-mono text-rose-700">₹{{ number_format(array_sum($balanceSheetEntries['liabilities']), 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<script>
function reportsApp() {
    return {
        printReport() {
            window.print();
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
