{{-- Breadcrumbs & Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
    <div>
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Home</a>
            <span>&gt;</span>
            <span class="text-slate-650">
                @if(request('tab') === 'exchange')
                    Unit Exchange Operations
                @elseif(request('tab') === 'cancellations')
                    Sales Cancellations
                @else
                    Sales Returns
                @endif
            </span>
        </div>
        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight uppercase mt-1">
            @if(request('tab') === 'exchange')
                Unit Exchange Operations
            @elseif(request('tab') === 'cancellations')
                Sales Cancellations
            @else
                Sales Returns
            @endif
        </h2>
    </div>
</div>

{{-- Layout wrapper: full width, conditionally shown --}}
<div class="space-y-6">

    {{-- LEFT COLUMN: SALES RETURNS & CANCELLATIONS --}}
    @if(request('tab') === 'returns' || request('tab') === 'cancellations')
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        <div>
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                @if(request('tab') === 'cancellations')
                    Sales Cancellations
                @else
                    Sales Returns
                @endif
            </h3>
            <p class="text-[10px] text-slate-450 mt-0.5">
                @if(request('tab') === 'cancellations')
                    Manage property cancellations, fees, and documentation.
                @else
                    Manage property returns, refund calculations, and ledger settlements.
                @endif
            </p>
        </div>

        {{-- Filter Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Search Customer/Unit:</label>
                <input type="text" placeholder="Search..." x-model="returnFilters.search"
                       class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Filter by Project:</label>
                <select x-model="returnFilters.project_id"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Projects</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Type:</label>
                <select x-model="returnFilters.type"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Types</option>
                    <option value="Flat">Flat</option>
                    <option value="Shop">Shop</option>
                </select>
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status:</label>
                <select x-model="returnFilters.status"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="returned">Returned</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="border border-slate-100 rounded-xl overflow-hidden">
            <div class="overflow-x-auto max-h-60">
                <table class="w-full text-left text-[11px]">
                    <thead class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-2.5">Customer</th>
                            <th class="px-3 py-2.5">Unit</th>
                            <th class="px-3 py-2.5 text-right">Contract Value</th>
                            <th class="px-3 py-2.5 text-right">Paid Till Date</th>
                            <th class="px-3 py-2.5 text-center">Return Status</th>
                            <th class="px-3 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        <template x-for="sale in filteredReturnSales()" :key="sale.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2.5 font-bold text-slate-900" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5" x-text="sale.unit ? (sale.project ? sale.project.name + ' - ' : '') + sale.unit.door_no : '—'"></td>
                                <td class="px-3 py-2.5 text-right font-mono" x-text="fmt(sale.total_amount)"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-emerald-700" x-text="fmt(getPaidTillDate(sale))"></td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block"
                                          :class="getStatusBadgeClass(sale.status)" x-text="sale.status"></span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <template x-if="sale.status === 'active'">
                                        <button type="button" @click="selectReturnSale(sale, 'cancelled')"
                                                class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-[9px] uppercase transition-all tracking-wide">
                                            Cancel Sale
                                        </button>
                                    </template>
                                    <template x-if="sale.status === 'cancelled'">
                                        <button type="button" @click="selectReturnSale(sale, 'returned')"
                                                class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-[9px] uppercase transition-all tracking-wide">
                                            Process Return
                                        </button>
                                    </template>
                                    <template x-if="sale.status === 'returned'">
                                        <span class="text-slate-400 italic">Settled</span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredReturnSales().length === 0">
                            <td colspan="6" class="px-3 py-8 text-center text-slate-400 italic">No sales found matching return filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PROCESS RETURN / CANCELLATION DETAILS PANEL --}}
        <template x-if="selectedReturnSale">
            <div class="bg-emerald-50/60 border border-emerald-150 rounded-2xl p-4 space-y-4 animate-fade-in">
                <div class="flex items-center justify-between border-b border-emerald-200/50 pb-2">
                    <div>
                        <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-widest">Active Form</span>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider"
                            x-text="targetReturnStatus === 'cancelled' ? 'Process Cancellation Details' : 'Process Return Details'"></h4>
                        <p class="text-[10px] text-slate-500 font-semibold"
                           x-text="'Sale No: ' + selectedReturnSale.sale_number + ' • Customer: ' + (selectedReturnSale.customer ? selectedReturnSale.customer.name : 'N/A')"></p>
                    </div>
                    <button type="button" @click="selectedReturnSale = null" class="text-emerald-700 hover:text-emerald-900 font-bold">✕</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Select Return/Cancel Date:</label>
                        <input type="date" x-model="returnForm.date"
                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Revert Unit status to 'Unsold'?</label>
                        <label class="flex items-center gap-2 h-9 cursor-pointer">
                            <input type="checkbox" x-model="returnForm.revert_unsold" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-xs text-slate-650">Mark Unit as Available</span>
                        </label>
                    </div>
                </div>

                {{-- Refund Calculations Grid --}}
                <div class="bg-white border border-emerald-100 rounded-xl p-3 grid grid-cols-3 gap-4 divide-x divide-slate-100">
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total Paid</p>
                        <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(getPaidTillDate(selectedReturnSale))"></p>
                    </div>
                    <div class="text-center px-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Cancellation Fee</p>
                        <div class="flex items-center justify-center gap-1 mt-0.5">
                            <span class="text-slate-400 font-bold">-</span>
                            <input type="number" step="1" x-model.number="returnForm.cancellation_fee"
                                   class="w-20 px-1 py-0.5 bg-rose-50/50 border border-rose-200 text-rose-650 font-bold font-mono rounded text-center text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Approved Refund Amount</p>
                        <p class="text-sm font-extrabold text-emerald-700 font-mono mt-0.5" x-text="fmt(calculateApprovedRefund(selectedReturnSale))"></p>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Reason / Narrative Notes *</label>
                    <textarea x-model="returnForm.reason" rows="2" placeholder="Explain the rationale for this action..."
                              class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-emerald-100">
                    <button type="button" @click="selectedReturnSale = null"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                        Cancel
                    </button>
                    <button type="button" @click="submitReturnRefund()"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                        Confirm Return & Refund
                    </button>
                </div>
            </div>
        </template>

        {{-- RETURN PROCESS FLOW TIMELINE --}}
        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 font-sans">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Return Process Flow</h4>
            <div class="flex items-center justify-between text-[11px] font-bold text-slate-650 max-w-sm mx-auto py-2">
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border border-slate-800 shadow shadow-slate-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    </div>
                    <span>Return Request</span>
                </div>
                <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center border border-primary shadow shadow-primary-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span>Admin Approval</span>
                </div>
                <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center border border-emerald-500 shadow shadow-emerald-250">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span>Customer Ledger Update</span>
                </div>
            </div>
    </div>
    @endif

    {{-- RIGHT COLUMN: UNIT-TO-UNIT EXCHANGE PLAN --}}
    @if(request('tab') === 'exchange')
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        <div>
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Unit-to-Unit Exchange Plan</h3>
            <p class="text-[10px] text-slate-450 mt-0.5">Transfer equity from an active sale into a replacement property unit.</p>
        </div>

        {{-- Filter Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Search Customer/Unit:</label>
                <input type="text" placeholder="Search..." x-model="exchangeFilters.search"
                       class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Filter by Project:</label>
                <select x-model="exchangeFilters.project_id"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Projects</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Type:</label>
                <select x-model="exchangeFilters.type"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Types</option>
                    <option value="Flat">Flat</option>
                    <option value="Shop">Shop</option>
                </select>
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status:</label>
                <select x-model="exchangeFilters.status"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="exchanged">Exchanged</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="border border-slate-100 rounded-xl overflow-hidden">
            <div class="overflow-x-auto max-h-60">
                <table class="w-full text-left text-[11px]">
                    <thead class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-2.5">Customer</th>
                            <th class="px-3 py-2.5">Old Unit</th>
                            <th class="px-3 py-2.5 text-right">Old Value</th>
                            <th class="px-3 py-2.5">New Unit</th>
                            <th class="px-3 py-2.5 text-right">New Value</th>
                            <th class="px-3 py-2.5 text-right">Equity Transferred</th>
                            <th class="px-3 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        <template x-for="sale in filteredExchangeSales()" :key="sale.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2.5 font-bold text-slate-900" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5" x-text="sale.unit ? sale.unit.door_no : '—'"></td>
                                <td class="px-3 py-2.5 text-right font-mono" x-text="fmt(sale.total_amount)"></td>
                                <td class="px-3 py-2.5">
                                    <span x-show="sale.status === 'exchanged'" class="text-slate-450 italic">See New Unit</span>
                                    <span x-show="sale.status !== 'exchanged'" class="text-slate-400 font-normal">—</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span x-show="sale.status === 'exchanged'" class="font-mono text-slate-450 italic">—</span>
                                    <span x-show="sale.status !== 'exchanged'" class="text-slate-400 font-normal">—</span>
                                </td>
                                <td class="px-3 py-2.5 text-right font-mono text-emerald-700" x-text="fmt(getPaidTillDate(sale))"></td>
                                <td class="px-3 py-2.5 text-right">
                                    <template x-if="sale.status === 'active'">
                                        <button type="button" @click="selectExchangeSale(sale)"
                                                class="px-2.5 py-1 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg text-[9px] uppercase transition-all tracking-wide">
                                            Process Exchange
                                        </button>
                                    </template>
                                    <template x-if="sale.status === 'exchanged'">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase inline-block">Exchanged</span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredExchangeSales().length === 0">
                            <td colspan="7" class="px-3 py-8 text-center text-slate-400 italic">No sales found matching exchange filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- EXECUTE EXCHANGE PLAN PANEL --}}
        <template x-if="selectedExchangeSale">
            <div class="bg-blue-50/60 border border-blue-150 rounded-2xl p-4 space-y-4 animate-fade-in">
                <div class="flex items-center justify-between border-b border-blue-200/50 pb-2">
                    <div>
                        <span class="text-[9px] font-bold text-blue-800 uppercase tracking-widest">Active Plan</span>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Execute Exchange Plan</h4>
                        <p class="text-[10px] text-slate-500 font-semibold"
                           x-text="'Old Unit: ' + (selectedExchangeSale.unit ? selectedExchangeSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedExchangeSale.customer ? selectedExchangeSale.customer.name : 'N/A')"></p>
                    </div>
                    <button type="button" @click="selectedExchangeSale = null" class="text-blue-700 hover:text-blue-900 font-bold">✕</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Target Project *</label>
                        <select x-model="exchangeForm.new_project_id" @change="loadExchangeUnits()"
                                class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <option value="">Select Target Project...</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Target Available Unit *</label>
                        <select x-model="exchangeForm.new_unit_id" @change="onExchangeUnitSelect()" :disabled="!exchangeForm.new_project_id"
                                class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50">
                            <option value="">— Select Project First —</option>
                            <template x-for="unit in exchangeAvailableUnits" :key="unit.id">
                                <option :value="unit.id" x-text="unit.door_no + ' — ' + unit.floor_name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">New Contract Value:</label>
                        <input type="text" :value="fmt(exchangeForm.new_unit_value)" disabled
                               class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-500 font-bold font-mono">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Options:</label>
                        <label class="flex items-center gap-2 h-9 cursor-pointer">
                            <input type="checkbox" x-model="exchangeForm.carry_forward" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-xs text-slate-650">Carry forward payments to New Unit sale?</span>
                        </label>
                    </div>
                </div>

                {{-- Financial Balance Grid --}}
                <div class="bg-white border border-blue-100 rounded-xl p-3 grid grid-cols-3 gap-4 divide-x divide-slate-100">
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Equity Applied</p>
                        <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(exchangeForm.equity_applied)"></p>
                    </div>
                    <div class="text-center px-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">New Contract Value</p>
                        <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(exchangeForm.new_unit_value)"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Differential Due (Receivable)</p>
                        <p class="text-sm font-extrabold text-blue-700 font-mono mt-0.5" x-text="fmt(calculateDifferentialDue())"></p>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Exchange Reason / Notes *</label>
                    <textarea x-model="exchangeForm.reason" rows="2" placeholder="Write internal memo for the unit exchange..."
                              class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-blue-100">
                    <button type="button" @click="selectedExchangeSale = null"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                        Cancel
                    </button>
                    <button type="button" @click="submitExchangePlan()"
                            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                        Finalize Exchange & New EMI
                    </button>
                </div>
            </div>
        </template>

        {{-- ACTIVE RETURNS vs. EXCHANGES (Monthly) CHART --}}
        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 font-sans">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Returns vs. Exchanges (Monthly)</h4>
            <div id="returnsExchangesChart" class="w-full" style="height: 180px;"></div>
        </div>
    </div>
    @endif

</div>
