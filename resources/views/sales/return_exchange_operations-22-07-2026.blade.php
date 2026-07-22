{{-- Breadcrumbs & Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
    <div>
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Home</a>
            <span>&gt;</span>
            @if(request('tab') === 'exchange')
                <span class="hover:text-primary transition-colors">Sales</span>
                <span>&gt;</span>
                <span class="text-slate-650">Exchange Management</span>
            @else
                <span class="text-slate-650">
                    @if(request('tab') === 'cancellations')
                        Sales Cancellations
                    @elseif(request('tab') === 'sale-return')
                        Sales Return / Cancellation
                    @else
                        Sales Returns
                    @endif
                </span>
            @endif
        </div>
        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight uppercase mt-1">
            @if(request('tab') === 'exchange')
                Exchange Management
            @elseif(request('tab') === 'cancellations')
                Sales Cancellations
            @elseif(request('tab') === 'sale-return')
                Sales Return / Cancellation
            @else
                Sales Returns
            @endif
        </h2>
        @if(request('tab') === 'exchange')
            <p class="text-[10px] text-slate-450 mt-0.5">Manage unit-to-unit exchanges.</p>
        @endif
    </div>
    <div>
        @if(request('tab') === 'sale-return' || request('tab') === 'returns' || request('tab') === 'cancellations')
            <button type="button" @click="openNewReturnModal = true; newReturnStep = 1; newReturnSaleId = ''; newReturnSale = null;" 
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-sm transition-all duration-200 hover:bg-primary-700 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-text="isCancellationTab ? 'New Cancellation' : 'New Return'"></span>
            </button>
        @elseif(request('tab') === 'exchange')
            <button type="button" @click="openNewExchangeModal = true; newExchangeStep = 1; newExchangeSaleId = '';" 
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span> New Exchange</span>
            </button>
        @endif
    </div>
</div>

{{-- Layout wrapper: full width, conditionally shown --}}
<div class="space-y-6">

    {{-- LEFT COLUMN: SALES RETURNS & CANCELLATIONS --}}
    @if(request('tab') === 'returns' || request('tab') === 'sale-return')
    
    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Returns -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="isCancellationTab ? 'Total Cancellations' : 'Total Returns'"></p>
                <h4 class="text-xl font-extrabold text-slate-800 mt-0.5" x-text="getReturnStats().totalReturns">0</h4>
                <p class="text-[9px] text-slate-450 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 2: Return Amount -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="isCancellationTab ? 'Cancellation Amount' : 'Return Amount'"></p>
                <h4 class="text-xl font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getReturnStats().returnAmount)">₹0.00</h4>
                <p class="text-[9px] text-slate-450 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 3: Payable to Customer -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Payable to Customer</p>
                <h4 class="text-xl font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getReturnStats().payableToCustomer)">₹0.00</h4>
                <p class="text-[9px] text-slate-450 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 4: Receivable from Customer -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Receivable from Customer</p>
                <h4 class="text-xl font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getReturnStats().receivableFromCustomer)">₹0.00</h4>
                <p class="text-[9px] text-slate-450 mt-0.5">This Month</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        
        {{-- Filter Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 flex-1 items-end">
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
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Unit Type:</label>
                <select x-model="returnFilters.type"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All</option>
                    <option value="Flat">Flat</option>
                    <option value="Shop">Shop</option>
                </select>
            </div>
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Return Type:</label>
                <select x-model="returnFilters.status"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">All</option>
                    <option value="cancelled">Cancellation</option>
                    <option value="returned">Return</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="returnFilters.search = ''; returnFilters.project_id = ''; returnFilters.type = ''; returnFilters.status = '';"
                        class="w-full px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition uppercase">
                    Reset
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="border border-slate-100 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[11px] border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-3 py-3 text-left">Return No</th>
                            <th class="px-3 py-3 text-left">Date</th>
                            <th class="px-3 py-3 text-left">Project</th>
                            <th class="px-3 py-3 text-left">Unit Details</th>
                            <th class="px-3 py-3 text-left">Customer</th>
                            <th class="px-3 py-3 text-left">Return Type</th>
                            <th class="px-3 py-3 text-right">Amount</th>
                            <th class="px-3 py-3 text-left">Payable / Receivable</th>
                            <th class="px-3 py-3 text-center">Status</th>
                            <th class="px-3 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-705">
                        <template x-for="sale in filteredReturnSales()" :key="sale.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2.5 text-primary font-bold text-left">
                                    <span x-text="'RET-' + new Date(sale.cancelled_at || sale.updated_at).getFullYear() + '-' + String(sale.id).padStart(3, '0')"></span>
                                </td>
                                <td class="px-3 py-2.5 text-slate-500 text-left" x-text="formatDate(sale.cancelled_at || sale.updated_at)"></td>
                                <td class="px-3 py-2.5 text-left" x-text="sale.project ? sale.project.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left" x-text="sale.unit ? sale.unit.door_no : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left font-bold text-slate-900" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left" x-text="sale.status === 'cancelled' ? 'Cancellation' : 'Return'"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-slate-900" x-text="fmt(sale.total_amount)"></td>
                                <td class="px-3 py-2.5 text-left">
                                    <template x-if="sale.status === 'returned'">
                                        <div>
                                            <template x-if="parseFloat(sale.refund_amount) > 0">
                                                <span class="text-orange-600 font-bold" x-text="'Payable (' + fmt(sale.refund_amount) + ')'"></span>
                                            </template>
                                            <template x-if="parseFloat(sale.refund_amount) <= 0">
                                                <span class="text-teal-600 font-bold" x-text="'Receivable (' + fmt(sale.cancellation_fee) + ')'"></span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="sale.status === 'cancelled'">
                                        <div>
                                            <template x-if="getPaidTillDate(sale) > parseFloat(sale.cancellation_fee)">
                                                <span class="text-orange-600 font-bold" x-text="'Payable (' + fmt(getPaidTillDate(sale) - parseFloat(sale.cancellation_fee)) + ')'"></span>
                                            </template>
                                            <template x-if="getPaidTillDate(sale) <= parseFloat(sale.cancellation_fee)">
                                                <span class="text-teal-600 font-bold" x-text="'Receivable (' + fmt(parseFloat(sale.cancellation_fee) - getPaidTillDate(sale)) + ')'"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <template x-if="sale.status === 'cancelled'">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-amber-50 text-amber-700 border border-amber-100">
                                            Pending
                                        </span>
                                    </template>
                                    <template x-if="sale.status === 'returned'">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Completed
                                        </span>
                                    </template>
                                    <template x-if="sale.status === 'exchanged'">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">
                                            Exchanged
                                        </span>
                                    </template>
                                </td>
                                <td class="px-3 py-2.5 text-right text-slate-500">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" @click="selectReturnSale(sale, 'returned'); isEditReturn = false;" class="text-slate-400 hover:text-slate-650 transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <template x-if="sale.status === 'cancelled'">
                                            <button type="button" @click="selectReturnSale(sale, 'returned'); isEditReturn = true;" class="text-primary hover:text-primary-700 transition-colors" title="Process Return">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredReturnSales().length === 0">
                            <td colspan="10" class="px-3 py-8 text-center text-slate-400 italic">No sales found matching return filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PROCESS RETURN / CANCELLATION DETAILS MODAL POPUP --}}
        <div x-show="selectedReturnSale" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="selectedReturnSale = null"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full border border-emerald-100 overflow-hidden animate-fade-in p-6 space-y-4 outline-none focus:outline-none">
                    
                    <button type="button" @click="selectedReturnSale = null" class="absolute top-4 right-4 text-emerald-700 hover:text-emerald-950 font-bold text-lg focus:outline-none">✕</button>

                    <div class="flex items-center justify-between border-b border-emerald-200/50 pb-2">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest block mb-0.5"
                                  :class="!isEditReturn ? 'text-slate-500' : 'text-emerald-850'"
                                  x-text="!isEditReturn ? 'View Mode' : 'Active Form'"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider"
                                x-text="!isEditReturn ? 'View Return Details' : (targetReturnStatus === 'cancelled' ? 'Process Cancellation Details' : 'Process Return Details')"></h4>
                            <p class="text-[10px] text-slate-500 font-semibold"
                               x-text="selectedReturnSale ? 'Sale No: ' + selectedReturnSale.sale_number + ' • Customer: ' + (selectedReturnSale.customer ? selectedReturnSale.customer.name : 'N/A') : ''"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Select Return/Cancel Date:</label>
                            <input type="date" x-model="returnForm.date" :disabled="!isEditReturn"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-70 disabled:bg-slate-50">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Revert Unit status to 'Unsold'?</label>
                            <label class="flex items-center gap-2 h-9 cursor-pointer">
                                <input type="checkbox" x-model="returnForm.revert_unsold" :disabled="!isEditReturn" class="rounded text-primary focus:ring-primary/20 disabled:opacity-70">
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
                                <input type="number" step="1" x-model.number="returnForm.cancellation_fee" :disabled="!isEditReturn"
                                       class="w-20 px-1 py-0.5 bg-rose-50/50 border border-rose-200 text-rose-650 font-bold font-mono rounded text-center text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 disabled:opacity-70">
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Approved Refund Amount</p>
                            <p class="text-sm font-extrabold text-emerald-700 font-mono mt-0.5" x-text="fmt(calculateApprovedRefund(selectedReturnSale))"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Reason / Narrative Notes *</label>
                        <textarea x-model="returnForm.reason" rows="2" placeholder="Explain the rationale for this action..." :disabled="!isEditReturn"
                                  class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none disabled:opacity-70 disabled:bg-slate-50"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-emerald-100">
                        <button type="button" @click="selectedReturnSale = null"
                                class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase"
                                x-text="!isEditReturn ? 'Close' : 'Cancel'">
                        </button>
                        <button type="button" @click="submitReturnRefund()" x-show="isEditReturn"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                            Confirm Return & Refund
                        </button>
                    </div>

                    {{-- RETURN PROCESS FLOW TIMELINE --}}
                    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-3 font-sans">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Return Process Flow</h4>
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-650 max-w-sm mx-auto py-2">
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border border-slate-800 shadow shadow-slate-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </div>
                                <span class="text-[9px]">Return Request</span>
                            </div>
                            <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center border border-primary shadow shadow-primary-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-[9px]">Admin Approval</span>
                            </div>
                            <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center border border-emerald-500 shadow shadow-emerald-250">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-[9px]">Customer Ledger</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- INITIATE NEW RETURN / CANCELLATION MODAL POPUP --}}
    <div x-show="openNewReturnModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openNewReturnModal = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full overflow-hidden animate-fade-in">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider"
                        x-text="newReturnStep === 1 ? (isCancellationTab ? 'Initiate Booking Cancellation' : 'Initiate Sales Return / Cancellation') : (isCancellationTab ? 'Process Cancellation Details' : 'Process Return Details')"></h3>
                    <button type="button" @click="openNewReturnModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">✕</button>
                </div>
                
                <div class="p-6">
                    {{-- STEP 1: SELECT SALE --}}
                    <div x-show="newReturnStep === 1" class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block" x-text="isCancellationTab ? 'Select Active Booking *' : 'Select Active Sale *'"></label>
                            <select x-model="newReturnSaleId" 
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-750 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="" x-text="isCancellationTab ? '— Select an Active Booking to Cancel —' : '— Select an Active Sale to Return / Cancel —'"></option>
                                <template x-for="sale in sales.filter(s => s.status === 'active')" :key="sale.id">
                                    <option :value="sale.id" 
                                            x-text="sale.sale_number + ' — ' + (sale.customer ? sale.customer.name : 'N/A') + ' (' + (sale.unit ? (sale.project ? sale.project.name + ' - ' : '') + sale.unit.door_no : 'N/A') + ')'"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="openNewReturnModal = false"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                                Cancel
                            </button>
                            <button type="button" @click="selectNewReturnSale()"
                                    class="px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                                Next
                            </button>
                        </div>
                    </div>
                    
                    {{-- STEP 2: FORM DETAILS --}}
                    <div x-show="newReturnStep === 2" class="space-y-4">
                        <div class="bg-slate-50 border border-slate-200/50 rounded-xl p-3 text-xs font-bold text-slate-600">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5" x-text="isCancellationTab ? 'Selected Booking' : 'Selected Sale'"></span>
                            <span class="text-slate-800" x-text="newReturnSale ? (isCancellationTab ? 'Booking No: ' : 'Sale No: ') + newReturnSale.sale_number + ' • Customer: ' + (newReturnSale.customer ? newReturnSale.customer.name : 'N/A') : ''"></span>
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
                        <div class="bg-slate-50/50 border border-slate-200 rounded-xl p-3 grid grid-cols-3 gap-4 divide-x divide-slate-200">
                            <div class="text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total Paid</p>
                                <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(getPaidTillDate(newReturnSale))"></p>
                            </div>
                            <div class="text-center px-2">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Cancellation Fee</p>
                                <div class="flex items-center justify-center gap-1 mt-0.5">
                                    <span class="text-slate-400 font-bold">-</span>
                                    <input type="number" step="1" x-model.number="returnForm.cancellation_fee"
                                           class="w-24 px-1.5 py-0.5 bg-rose-50/50 border border-rose-200 text-rose-650 font-bold font-mono rounded text-center text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Approved Refund Amount</p>
                                <p class="text-sm font-extrabold text-emerald-700 font-mono mt-0.5" x-text="fmt(calculateApprovedRefund(newReturnSale))"></p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Reason / Narrative Notes *</label>
                            <textarea x-model="returnForm.reason" rows="2" placeholder="Explain the rationale for this action..."
                                      class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none"></textarea>
                        </div>

                        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 font-sans">
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Return Process Flow</h4>
                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-650 max-w-sm mx-auto py-2">
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border border-slate-800 shadow shadow-slate-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    </div>
                                    <span class="text-[9px]">Return Request</span>
                                </div>
                                <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center border border-primary shadow shadow-primary-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <span class="text-[9px]">Admin Approval</span>
                                </div>
                                <div class="h-0.5 bg-slate-300 flex-1 mx-2 -mt-4"></div>
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center border border-emerald-500 shadow shadow-emerald-250">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-[9px]">Customer Ledger</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="newReturnStep = 1"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                                Back
                            </button>
                            <button type="button" @click="submitNewReturn()"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                                Confirm Cancellation
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    @endif

    {{-- INITIATE NEW EXCHANGE MODAL POPUP --}}
    <div x-show="openNewExchangeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openNewExchangeModal = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full overflow-hidden animate-fade-in">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider"
                        x-text="newExchangeStep === 1 ? 'Initiate Unit Exchange' : 'Process Exchange Details'"></h3>
                    <button type="button" @click="openNewExchangeModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">✕</button>
                </div>
                
                <div class="p-6">
                    {{-- STEP 1: SELECT SALE --}}
                    <div x-show="newExchangeStep === 1" class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Select Active/Cancelled Sale *</label>
                            <select x-model="newExchangeSaleId" 
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-750 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">— Select an Active/Cancelled Booking to Exchange —</option>
                                <template x-for="sale in sales.filter(s => s.status === 'active' || s.status === 'cancelled')" :key="sale.id">
                                    <option :value="sale.id" 
                                            x-text="sale.sale_number + ' — ' + (sale.customer ? sale.customer.name : 'N/A') + ' (' + (sale.unit ? (sale.project ? sale.project.name + ' - ' : '') + sale.unit.door_no : 'N/A') + ')'"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="openNewExchangeModal = false"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                                Cancel
                            </button>
                            <button type="button" @click="selectNewExchangeSale()"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                                Next
                            </button>
                        </div>
                    </div>
                    
                    {{-- STEP 2: FORM DETAILS --}}
                    <div x-show="newExchangeStep === 2" class="space-y-4">
                        <div class="bg-slate-50 border border-slate-200/50 rounded-xl p-3 text-xs font-bold text-slate-600">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Selected Sale</span>
                            <span class="text-slate-800" x-text="selectedExchangeSale ? 'Sale No: ' + selectedExchangeSale.sale_number + ' • Customer: ' + (selectedExchangeSale.customer ? selectedExchangeSale.customer.name : 'N/A') : ''"></span>
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
                                <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                    <button type="button" 
                                            @click="if (exchangeForm.new_project_id) { open = !open; if (open) $nextTick(() => $refs.modalTargetUnitSearchInput.focus()); }" 
                                            :disabled="!exchangeForm.new_project_id"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 text-left flex justify-between items-center h-[38px]">
                                        <span x-text="exchangeForm.new_unit_id ? (exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id) ? (exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).door_no + ' — ' + exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name) : '— Select Target Unit —') : '— Select Target Unit —'"
                                              :class="!exchangeForm.new_unit_id ? 'text-slate-400' : 'text-slate-800 font-semibold'"></span>
                                        <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                                         style="display: none;">
                                        
                                        <div class="p-2 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <input type="text" 
                                                   x-model="search" 
                                                   x-ref="modalTargetUnitSearchInput"
                                                   placeholder="Search unit door no or floor..."
                                                   class="w-full py-1 text-xs border-0 bg-transparent focus:outline-none focus:ring-0 text-slate-800 placeholder-slate-400">
                                            <button type="button" x-show="search" @click="search = ''" class="text-slate-400 hover:text-slate-600 text-xs px-1">✕</button>
                                        </div>

                                        <button type="button" 
                                                @click="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); open = false; search = ''"
                                                class="w-full px-3 py-1.5 text-left text-xs text-slate-400 hover:bg-slate-50 border-b border-slate-100 italic flex items-center justify-between">
                                            <span>— Select Target Unit —</span>
                                        </button>

                                        <div class="overflow-y-auto flex-1 divide-y divide-slate-50">
                                            <template x-for="unit in exchangeAvailableUnits.filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase())))" :key="unit.id">
                                                <button type="button"
                                                        @click="exchangeForm.new_unit_id = unit.id; onExchangeUnitSelect(); open = false; search = ''"
                                                        class="w-full px-3 py-2 text-left text-xs hover:bg-blue-50 transition-colors flex items-center justify-between gap-2"
                                                        :class="exchangeForm.new_unit_id == unit.id ? 'bg-blue-50/80 text-blue-700 font-bold' : 'text-slate-700'">
                                                    <div>
                                                        <span class="font-semibold" x-text="unit.door_no"></span>
                                                        <span class="text-[10px] text-slate-400 ml-1.5" x-text="unit.floor_name ? '(' + unit.floor_name + ')' : ''"></span>
                                                    </div>
                                                    <span class="text-[9px] text-slate-400 font-mono bg-slate-100 px-1.5 py-0.5 rounded" x-text="unit.unit_type_name"></span>
                                                </button>
                                            </template>

                                            <div x-show="exchangeAvailableUnits.filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase()))).length === 0"
                                                 class="px-4 py-4 text-center text-xs text-slate-400 italic">
                                                No matching units found
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        <div class="bg-slate-50/50 border border-slate-200 rounded-xl p-3 grid grid-cols-3 gap-4 divide-x divide-slate-200">
                            <div class="text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Equity Applied</p>
                                <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(exchangeForm.equity_applied)"></p>
                            </div>
                            <div class="text-center px-2">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">New Contract Value</p>
                                <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(exchangeForm.new_unit_value)"></p>
                            </div>
                            <div class="text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Differential Due</p>
                                <p class="text-sm font-extrabold text-blue-700 font-mono mt-0.5" x-text="fmt(calculateDifferentialDue())"></p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Exchange Reason / Notes *</label>
                            <textarea x-model="exchangeForm.reason" rows="2" placeholder="Write internal memo for the unit exchange..."
                                      class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="newExchangeStep = 1"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                                Back
                            </button>
                            <button type="button" @click="submitExchangePlan()"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition uppercase shadow-sm">
                                Finalize Exchange & New EMI
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    {{-- LEFT COLUMN: OLD SALES CANCELLATIONS (Cancel Booking tab) --}}
    @if(request('tab') === 'cancellations')
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        <div>
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                Sales Cancellations
            </h3>
            <p class="text-[10px] text-slate-450 mt-0.5">
                Manage property cancellations, fees, and documentation.
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
                                        <button type="button" @click="selectReturnSale(sale, 'returned'); isEditReturn = true;"
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
    </div>
    @endif

    {{-- RIGHT COLUMN: UNIT-TO-UNIT EXCHANGE PLAN --}}
    @if(request('tab') === 'exchange')
    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
        <!-- Card 1: Total Exchanges -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Total Exchanges</p>
                <h4 class="text-base font-extrabold text-slate-800 mt-0.5" x-text="getExchangeStats().totalExchanges">0</h4>
                <p class="text-[8px] text-slate-400 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 2: Total Difference Amount -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Total Difference Amount</p>
                <h4 class="text-base font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getExchangeStats().totalDiff)">₹0.00</h4>
                <p class="text-[8px] text-slate-400 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 3: Payable by Customer -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m5-13a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Payable by Customer</p>
                <h4 class="text-base font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getExchangeStats().payableByCustomer)">₹0.00</h4>
                <p class="text-[8px] text-slate-400 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 4: Refundable to Customer -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m5-13a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Refundable to Customer</p>
                <h4 class="text-base font-extrabold text-slate-800 mt-0.5" x-text="fmtIndian(getExchangeStats().refundableToCustomer)">₹0.00</h4>
                <p class="text-[8px] text-slate-400 mt-0.5">This Month</p>
            </div>
        </div>

        <!-- Card 5: Completed Exchanges -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Completed Exchanges</p>
                <h4 class="text-base font-extrabold text-slate-800 mt-0.5" x-text="getExchangeStats().completedExchanges">0</h4>
                <p class="text-[8px] text-slate-400 mt-0.5">This Month</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
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
                    <option value="cancelled">Cancelled</option>
                    <option value="exchanged">Exchanged</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Exchange Register</h4>
        </div>

        {{-- Table --}}
        <div class="border border-slate-100 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[11px] border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-3 py-3 text-left">Exchange No.</th>
                            <th class="px-3 py-3 text-left">Date</th>
                            <th class="px-3 py-3 text-left">Project</th>
                            <th class="px-3 py-3 text-left bg-slate-100/50" colspan="2">Old Unit (Cancelled)</th>
                            <th class="px-3 py-3 text-left bg-blue-50/30" colspan="2">New Unit (Booked)</th>
                            <th class="px-3 py-3 text-right">Difference Amount</th>
                            <th class="px-3 py-3 text-left">Payable / Refundable</th>
                            <th class="px-3 py-3 text-center">Status</th>
                            <th class="px-3 py-3 text-right">Actions</th>
                        </tr>
                        <tr class="bg-slate-50/50 border-b border-slate-150 text-[9px] text-slate-500">
                            <th class="px-3 py-1 font-normal" colspan="3"></th>
                            <th class="px-3 py-1 bg-slate-100/30 font-semibold border-r border-slate-150">Unit Details</th>
                            <th class="px-3 py-1 bg-slate-100/30 font-semibold border-r border-slate-150">Customer</th>
                            <th class="px-3 py-1 bg-blue-50/10 font-semibold border-r border-slate-150">Unit Details</th>
                            <th class="px-3 py-1 bg-blue-50/10 font-semibold border-r border-slate-150">Customer</th>
                            <th class="px-3 py-1 font-normal" colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                        <template x-for="sale in filteredExchangeSales()" :key="sale.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2.5 font-bold text-primary text-left" x-text="sale.status === 'exchanged' ? ('EXC-' + new Date(sale.updated_at).getFullYear() + '-' + String(sale.id).padStart(3, '0')) : '—'"></td>
                                <td class="px-3 py-2.5 text-slate-500 text-left" x-text="formatDate(sale.status === 'exchanged' ? sale.updated_at : sale.sale_date)"></td>
                                <td class="px-3 py-2.5 text-left" x-text="sale.project ? sale.project.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left bg-slate-100/20 border-r border-slate-100" x-text="sale.unit ? sale.unit.door_no : '—'"></td>
                                <td class="px-3 py-2.5 text-left font-bold text-slate-900 bg-slate-100/20 border-r border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left bg-blue-50/10 border-r border-slate-100 font-bold text-blue-700" x-text="getNewUnitDoorNo(sale)"></td>
                                <td class="px-3 py-2.5 text-left font-bold text-slate-900 bg-blue-50/10 border-r border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-slate-900" x-text="sale.status === 'exchanged' ? fmt(getDifferenceAmount(sale)) : '—'"></td>
                                <td class="px-3 py-2.5 text-left">
                                    <template x-if="sale.status === 'exchanged'">
                                        <span :class="getNewUnitValue(sale) > parseFloat(sale.total_amount) ? 'text-orange-600 font-bold' : 'text-teal-600 font-bold'"
                                              x-text="getNewUnitValue(sale) > parseFloat(sale.total_amount) ? 'Payable by Customer' : 'Refundable to Customer'"></span>
                                    </template>
                                    <template x-if="sale.status !== 'exchanged'">
                                        <span class="text-slate-400 font-normal">—</span>
                                    </template>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block"
                                          :class="sale.status === 'exchanged' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : (sale.status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-amber-50 text-amber-700 border border-amber-100')"
                                          x-text="sale.status === 'exchanged' ? 'Completed' : (sale.status === 'cancelled' ? 'Pending' : 'Active')"></span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <template x-if="sale.status === 'active' || sale.status === 'cancelled'">
                                            <button type="button" @click="selectExchangeSale(sale)"
                                                    class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-[9px] uppercase transition-all tracking-wide">
                                                Process Exchange
                                            </button>
                                        </template>
                                        <template x-if="sale.status === 'exchanged'">
                                            <div class="flex items-center gap-1.5 justify-end">
                                                <button type="button" @click="viewExchangeSale = sale; openViewExchangeModal = true;" class="text-slate-450 hover:text-slate-700 transition-colors" title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button type="button" @click="selectExchangeSale(sale); newExchangeStep = 2;" class="text-primary hover:text-primary-700 transition-colors" title="Edit Exchange">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <!-- <button type="button" class="text-slate-400 hover:text-slate-650 transition-colors" title="More Options">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                                </button> -->
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredExchangeSales().length === 0">
                            <td colspan="11" class="px-3 py-8 text-center text-slate-400 italic">No sales found matching exchange filters.</td>
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

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-semibold">
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
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Target Unit Type</label>
                        <select x-model="exchangeForm.new_unit_type" @change="exchangeForm.new_unit_id = ''; exchangeForm.new_unit_value = 0;" :disabled="!exchangeForm.new_project_id"
                                class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50">
                            <option value="">All Types</option>
                            <template x-for="ut in exchangeUnitTypes" :key="ut.id">
                                <option :value="ut.id" x-text="ut.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Target Available Unit *</label>
                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                            <button type="button" 
                                    @click="if (exchangeForm.new_project_id) { open = !open; if (open) $nextTick(() => $refs.panelTargetUnitSearchInput.focus()); }" 
                                    :disabled="!exchangeForm.new_project_id"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 text-left flex justify-between items-center h-[38px]">
                                <span x-text="exchangeForm.new_unit_id ? (exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id) ? (exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).door_no + ' — ' + exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name) : '— Select Target Unit —') : '— Select Target Unit —'"
                                      :class="!exchangeForm.new_unit_id ? 'text-slate-400' : 'text-slate-800 font-semibold'"></span>
                                <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                                 style="display: none;">
                                
                                <div class="p-2 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" 
                                           x-model="search" 
                                           x-ref="panelTargetUnitSearchInput"
                                           placeholder="Search unit door no or floor..."
                                           class="w-full py-1 text-xs border-0 bg-transparent focus:outline-none focus:ring-0 text-slate-800 placeholder-slate-400">
                                    <button type="button" x-show="search" @click="search = ''" class="text-slate-400 hover:text-slate-600 text-xs px-1">✕</button>
                                </div>

                                <button type="button" 
                                        @click="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); open = false; search = ''"
                                        class="w-full px-3 py-1.5 text-left text-xs text-slate-400 hover:bg-slate-50 border-b border-slate-100 italic flex items-center justify-between">
                                    <span>— Select Target Unit —</span>
                                </button>

                                <div class="overflow-y-auto flex-1 divide-y divide-slate-50">
                                    <template x-for="unit in getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase())))" :key="unit.id">
                                        <button type="button"
                                                @click="exchangeForm.new_unit_id = unit.id; onExchangeUnitSelect(); open = false; search = ''"
                                                class="w-full px-3 py-2 text-left text-xs hover:bg-blue-50 transition-colors flex items-center justify-between gap-2"
                                                :class="exchangeForm.new_unit_id == unit.id ? 'bg-blue-50/80 text-blue-700 font-bold' : 'text-slate-700'">
                                            <div>
                                                <span class="font-semibold" x-text="unit.door_no"></span>
                                                <span class="text-[10px] text-slate-400 ml-1.5" x-text="unit.floor_name ? '(' + unit.floor_name + ')' : ''"></span>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-mono bg-slate-100 px-1.5 py-0.5 rounded" x-text="unit.unit_type_name"></span>
                                        </button>
                                    </template>

                                    <div x-show="getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase()))).length === 0"
                                         class="px-4 py-4 text-center text-xs text-slate-400 italic">
                                        No matching units found
                                    </div>
                                </div>
                            </div>
                        </div>
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

    {{-- VIEW EXCHANGE DETAILS MODAL POPUP --}}
    <div x-show="openViewExchangeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openViewExchangeModal = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full overflow-hidden animate-fade-in">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Exchange Details</h3>
                    <button type="button" @click="openViewExchangeModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">✕</button>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="bg-slate-50 border border-slate-200/50 rounded-xl p-3 text-xs font-bold text-slate-650 space-y-1">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Customer Name</span>
                            <span class="text-slate-800 text-sm font-extrabold" x-text="viewExchangeSale ? (viewExchangeSale.customer ? viewExchangeSale.customer.name : 'N/A') : ''"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold">
                        <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 space-y-2">
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/60 pb-1">Old Unit (Cancelled)</h4>
                            <div class="space-y-1">
                                <p class="text-slate-500">Project: <span class="text-slate-850 font-bold" x-text="viewExchangeSale && viewExchangeSale.project ? viewExchangeSale.project.name : '—'"></span></p>
                                <p class="text-slate-500">Unit details: <span class="text-slate-850 font-bold" x-text="viewExchangeSale && viewExchangeSale.unit ? viewExchangeSale.unit.door_no : '—'"></span></p>
                                <p class="text-slate-500">Original Value: <span class="text-slate-850 font-bold font-mono" x-text="viewExchangeSale ? fmt(viewExchangeSale.total_amount) : '—'"></span></p>
                            </div>
                        </div>

                        <div class="bg-blue-50/10 border border-blue-150 rounded-2xl p-4 space-y-2">
                            <h4 class="text-[10px] font-bold text-blue-500 uppercase tracking-wider border-b border-blue-200/30 pb-1">New Unit (Booked)</h4>
                            <div class="space-y-1">
                                <p class="text-slate-500">Project: <span class="text-slate-850 font-bold" x-text="viewExchangeSale && (viewExchangeSale.replacement_sale || sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + viewExchangeSale.sale_number))) ? ((viewExchangeSale.replacement_sale || sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + viewExchangeSale.sale_number))).project ? (viewExchangeSale.replacement_sale || sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + viewExchangeSale.sale_number))).project.name : '—') : '—'"></span></p>
                                <p class="text-slate-500">Unit details: <span class="text-slate-850 font-bold" x-text="getNewUnitDoorNo(viewExchangeSale)"></span></p>
                                <p class="text-slate-500">New Value: <span class="text-slate-850 font-bold font-mono" x-text="fmt(getNewUnitValue(viewExchangeSale))"></span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Financial calculations --}}
                    <div class="bg-slate-50/50 border border-slate-200 rounded-xl p-3 grid grid-cols-3 gap-4 divide-x divide-slate-200">
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Equity Transferred</p>
                            <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="viewExchangeSale ? fmt(getPaidTillDate(viewExchangeSale)) : '₹0.00'"></p>
                        </div>
                        <div class="text-center px-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Difference Value</p>
                            <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="viewExchangeSale ? fmt(getDifferenceAmount(viewExchangeSale)) : '₹0.00'"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Payable / Refundable</p>
                            <p class="text-xs font-extrabold mt-1 uppercase" 
                               :class="viewExchangeSale && getNewUnitValue(viewExchangeSale) > parseFloat(viewExchangeSale.total_amount) ? 'text-orange-600' : 'text-teal-600'"
                               x-text="viewExchangeSale ? (getNewUnitValue(viewExchangeSale) > parseFloat(viewExchangeSale.total_amount) ? 'Payable' : 'Refundable') : '—'"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Notes / Narration Details</label>
                        <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 min-h-12"
                             x-text="viewExchangeSale ? (viewExchangeSale.cancellation_reason || 'No notes entered.') : ''"></div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="button" @click="openViewExchangeModal = false"
                                class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase">
                            Close
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</div>
