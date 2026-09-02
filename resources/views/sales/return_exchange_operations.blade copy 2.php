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
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition-all duration-200 hover:bg-primary-700 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-text="isCancellationTab ? 'New Cancellation' : '{{ request('tab') === 'sale-return' ? 'Cancellation' : 'New Return' }}'"></span>
            </button>
        @elseif(request('tab') === 'exchange')
            <button type="button" @click="openNewExchangeModal = true; newExchangeStep = 1; newExchangeSaleId = '';" 
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition-all duration-200 hover:bg-primary-700 hover:shadow-md">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Card 1: Total Returns -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-purple-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span x-text="isCancellationTab ? 'Total Cancellations' : 'Total Returns'">Total Returns</span>
                <span class="w-2 h-2 rounded-full bg-purple-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getReturnStats().totalReturns">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 2: Return Amount -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span x-text="isCancellationTab ? 'Cancellation Amount' : 'Return Amount'">Return Amount</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().returnAmount)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 3: Payable to Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Payable to Customer</span>
                <span class="w-2 h-2 rounded-full bg-amber-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().payableToCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 4: Receivable from Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-teal-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Receivable from Customer</span>
                <span class="w-2 h-2 rounded-full bg-teal-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().receivableFromCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>
    </div></div>

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
                    @if(isset($unitTypes))
                        @foreach($unitTypes as $ut)
                            <option value="{{ $ut->id }}">{{ $ut->name }}</option>
                        @endforeach
                    @endif
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
                    <thead class="bg-[#a38c29] border-b border-[#8a7522] font-bold text-white uppercase tracking-wider text-[10px]">
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
                        <template x-for="sale in paginatedReturnSales()" :key="sale.id">
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
                                    <template x-if="sale.status === 'returned' || sale.status === 'cancelled'">
                                        <div>
                                            <template x-if="getRefundDue(sale) > 0">
                                                <div>
                                                    <span class="text-orange-600 font-bold block" x-text="'Payable (' + fmt(getRemainingRefund(sale)) + ')'"></span>
                                                    <template x-if="getRefundPaid(sale) > 0">
                                                        <span class="text-[9px] text-slate-400 font-semibold block" x-text="'Paid: ' + fmt(getRefundPaid(sale)) + ' / Total: ' + fmt(getRefundDue(sale))"></span>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="getRefundDue(sale) <= 0">
                                                <span class="text-teal-600 font-bold" x-text="'Receivable (' + fmt(Math.max(0, parseFloat(sale.cancellation_fee) - getPaidTillDate(sale))) + ')'"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <template x-if="sale.status === 'cancelled' || sale.status === 'returned'">
                                        <div>
                                            <template x-if="getRefundStatus(sale) === 'Pending'">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-amber-50 text-amber-700 border border-amber-100">
                                                    Pending
                                                </span>
                                            </template>
                                            <template x-if="getRefundStatus(sale) === 'Partially Refunded'">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">
                                                    Partially Paid
                                                </span>
                                            </template>
                                            <template x-if="getRefundStatus(sale) === 'Completed'">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    Completed
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="sale.status === 'exchanged'">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">
                                            Exchanged
                                        </span>
                                    </template>
                                </td>
                                <td class="px-3 py-2.5 text-right text-slate-500">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" @click="selectReturnSale(sale, 'returned'); isEditReturn = false;" 
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" 
                                                title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <template x-if="sale.status === 'cancelled'">
                                            <button type="button" @click="selectReturnSale(sale, 'returned'); isEditReturn = true;" 
                                                    class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" 
                                                    title="Process Return">
                                                <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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

            {{-- Return Table Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl" x-show="filteredReturnSales().length > 0">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900" x-text="(returnCurrentPage - 1) * returnPerPage + 1"></span> TO 
                    <span class="text-slate-900" x-text="Math.min(returnCurrentPage * returnPerPage, filteredReturnSales().length)"></span> OF 
                    <span class="text-slate-900" x-text="filteredReturnSales().length"></span> RETURNS
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="if(returnCurrentPage > 1) returnCurrentPage--" 
                            :disabled="returnCurrentPage <= 1"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        PREV
                    </button>
                    
                    {{-- Page Numbers --}}
                    <template x-for="p in getReturnPageNumbers()" :key="p">
                        <span class="inline-flex items-center gap-1">
                            <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                            <button type="button" x-show="p !== '...'"
                                    @click="returnCurrentPage = p"
                                    x-text="p"
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs"
                                    :class="returnCurrentPage === p ? 'bg-primary text-white border border-primary' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                        </span>
                    </template>
                    
                    <button type="button" @click="if(returnCurrentPage < getReturnTotalPages()) returnCurrentPage++" 
                            :disabled="returnCurrentPage >= getReturnTotalPages()"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

        {{-- PROCESS RETURN / CANCELLATION DETAILS MODAL POPUP --}}
        <div x-show="selectedReturnSale" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="selectedReturnSale = null">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap" x-text="!isEditReturn ? 'View Mode' : 'Active Form'"></span>
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap" x-text="selectedReturnSale ? (selectedReturnSale.status === 'cancelled' ? 'Cancellation' : 'Return') : ''"></span>
                            </div>
                            <h2 class="text-lg font-extrabold text-white tracking-tight mt-1" x-text="!isEditReturn ? 'View Return Details' : (targetReturnStatus === 'cancelled' ? 'Process Cancellation Details' : 'Process Cancel Details')"></h2>
                            <p class="text-[10px] text-slate-400 font-semibold mt-1" x-show="selectedReturnSale"
                               x-text="selectedReturnSale ? (selectedReturnSale.project ? (selectedReturnSale.project.code || selectedReturnSale.project.name) : 'N/A') + ' - ' + (selectedReturnSale.unit ? selectedReturnSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedReturnSale.customer ? selectedReturnSale.customer.name : 'N/A') + ' • Sale No: ' + selectedReturnSale.sale_number : ''"></p>
                        </div>
                        <button type="button" @click="selectedReturnSale = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                    </div>
                </div>
                
                <div class="p-6 space-y-6 max-h-[78vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    {{-- Selected Info Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider" x-text="selectedReturnSale && selectedReturnSale.status === 'cancelled' ? 'Selected Booking' : 'Selected Sale'"></span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="selectedReturnSale ? (selectedReturnSale.project ? (selectedReturnSale.project.code || selectedReturnSale.project.name) : 'N/A') + ' - ' + (selectedReturnSale.unit ? selectedReturnSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedReturnSale.customer ? selectedReturnSale.customer.name : 'N/A') + ' • ' + (selectedReturnSale.status === 'cancelled' ? 'Booking No: ' : 'Sale No: ') + selectedReturnSale.sale_number : ''"></strong>
                        </div>
                    </div>
                    
                    {{-- Inputs Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Return/Cancel Date</label>
                                <input type="date" x-model="returnForm.date" :disabled="!isEditReturn"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm disabled:opacity-75 disabled:cursor-not-allowed">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Revert Unit status to 'Unsold'?</label>
                                <label class="flex items-center gap-2 h-9 cursor-pointer">
                                    <input type="checkbox" x-model="returnForm.revert_unsold" :disabled="!isEditReturn" class="rounded text-primary focus:ring-primary/20 disabled:opacity-75">
                                    <span class="text-xs font-semibold text-slate-650">Mark Unit as Available</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Refund Calculations Grid --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 border border-slate-800 rounded-xl p-5 grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-white relative overflow-hidden shadow-md">
                        <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Paid</span>
                            <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="fmt(getPaidTillDate(selectedReturnSale))"></span>
                        </div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4 flex flex-col justify-center items-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Cancellation Fee</span>
                            <div class="flex items-center justify-center gap-1 mt-1">
                                <span class="text-slate-400 font-bold text-xs">- ₹</span>
                                <input type="number" step="1" x-model.number="returnForm.cancellation_fee" :disabled="!isEditReturn"
                                       class="w-28 px-2 py-0.5 bg-rose-500/10 border border-rose-500/30 text-rose-305 font-bold font-mono rounded-lg text-center text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 disabled:opacity-75">
                            </div>
                        </div>
                        <div class="relative z-10 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Approved Refund Amount</span>
                            <span class="font-extrabold text-[#d9bf3b] text-base mt-1 block font-mono" x-text="fmt(calculateApprovedRefund(selectedReturnSale))"></span>
                        </div>
                    </div>

                    {{-- Reason Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            <span>💬 Reason & Narrative Notes *</span>
                        </p>
                        <textarea x-model="returnForm.reason" rows="2" placeholder="Explain the rationale for this action..." :disabled="!isEditReturn"
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm disabled:opacity-75 disabled:cursor-not-allowed"></textarea>
                    </div>

                    {{-- Process Flow Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>🔄 Return Process Flow</span>
                        </p>
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-650 max-w-sm mx-auto py-2">
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border border-slate-800 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Request</span>
                            </div>
                            <div class="h-0.5 bg-slate-200 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center border border-primary shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Approval</span>
                            </div>
                            <div class="h-0.5 bg-slate-200 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center border border-emerald-500 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Ledger</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="selectedReturnSale = null"
                            class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider"
                            x-text="!isEditReturn ? 'Close' : 'Cancel'"></button>
                    <button type="button" @click="submitReturnRefund()" x-show="isEditReturn"
                            class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                        Confirm Return & Refund
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- INITIATE NEW RETURN / CANCELLATION MODAL POPUP --}}
    <div x-show="openNewReturnModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up modal-widthcancel" @click.away="openNewReturnModal = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap" x-text="isCancellationTab ? 'Cancellation' : 'Return'"></span>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Step <span x-text="newReturnStep"></span> of 2</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-white tracking-tight mt-1" x-text="newReturnStep === 1 ? (isCancellationTab ? 'Initiate Booking Cancellation' : 'Initiate Sales Return / Cancellation') : (isCancellationTab ? 'Process Cancellation Details' : 'Process Cancel Details')"></h2>
                    </div>
                    <button type="button" @click="openNewReturnModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            
            <div class="p-6 space-y-6 max-h-[78vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                {{-- STEP 1: SELECT SALE --}}
                <div x-show="newReturnStep === 1" class="space-y-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block" x-text="isCancellationTab ? 'Select Active Booking *' : 'Select Active Sale *'"></label>
                        <select x-model="newReturnSaleId" 
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm cursor-pointer font-semibold text-slate-800">
                            <option value="" x-text="isCancellationTab ? '— Select an Active Booking to Cancel —' : '— Select an Active Sale to Return / Cancel —'"></option>
                            <template x-for="sale in sales.filter(s => s.status === 'active')" :key="sale.id">
                                <option :value="sale.id" 
                                        x-text="(sale.project ? (sale.project.code || sale.project.name) : 'N/A') + ' - ' + (sale.unit ? sale.unit.door_no : (sale.sale_units && sale.sale_units.length ? sale.sale_units.map(su => su.unit ? su.unit.door_no : '').filter(Boolean).join(', ') : 'N/A')) + ' — ' + (sale.customer ? sale.customer.name : 'N/A') + ' (' + sale.sale_number + ')'"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                        <button type="button" @click="openNewReturnModal = false"
                                class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="button" @click="selectNewReturnSale()"
                                class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                            Next
                        </button>
                    </div>
                </div>
                
                {{-- STEP 2: FORM DETAILS --}}
                <div x-show="newReturnStep === 2" class="space-y-6">
                    {{-- Selected Info Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider" x-text="isCancellationTab ? 'Selected Booking' : 'Selected Sale' "></span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="newReturnSale ? (newReturnSale.project ? (newReturnSale.project.code || newReturnSale.project.name) : 'N/A') + ' - ' + (newReturnSale.unit ? newReturnSale.unit.door_no : 'N/A') + ' • Customer: ' + (newReturnSale.customer ? newReturnSale.customer.name : 'N/A') + ' • ' + (isCancellationTab ? 'Booking No: ' : 'Sale No: ') + newReturnSale.sale_number : ''"></strong>
                        </div>
                    </div>
                    
                    {{-- Inputs Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Select Return/Cancel Date *</label>
                                <input type="date" x-model="returnForm.date"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Revert Unit status to 'Unsold'?</label>
                                <label class="flex items-center gap-2 h-9 cursor-pointer">
                                    <input type="checkbox" x-model="returnForm.revert_unsold" class="rounded text-primary focus:ring-primary/20">
                                    <span class="text-xs font-semibold text-slate-650">Mark Unit as Available</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Refund Calculations Grid --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 border border-slate-800 rounded-xl p-5 grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-white relative overflow-hidden shadow-md">
                        <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Paid</span>
                            <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="fmt(getPaidTillDate(newReturnSale))"></span>
                        </div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4 flex flex-col justify-center items-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Cancellation Fee</span>
                            <div class="flex items-center justify-center gap-1 mt-1">
                                <span class="text-slate-400 font-bold text-xs">- ₹</span>
                                <input type="number" step="1" x-model.number="returnForm.cancellation_fee"
                                       class="w-28 px-2 py-0.5 bg-rose-500/10 border border-rose-500/30 text-rose-305 font-bold font-mono rounded-lg text-center text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                        </div>
                        <div class="relative z-10 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Approved Refund Amount</span>
                            <span class="font-extrabold text-[#d9bf3b] text-base mt-1 block font-mono" x-text="fmt(calculateApprovedRefund(newReturnSale))"></span>
                        </div>
                    </div>

                    {{-- Reason Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            <span>💬 Reason & Narrative Notes *</span>
                        </p>
                        <textarea x-model="returnForm.reason" rows="2" placeholder="Explain the rationale for this action..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm"></textarea>
                    </div>

                    {{-- Process Flow Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>🔄 Return Process Flow</span>
                        </p>
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-650 max-w-sm mx-auto py-2">
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border border-slate-800 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Request</span>
                            </div>
                            <div class="h-0.5 bg-slate-200 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center border border-primary shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Approval</span>
                            </div>
                            <div class="h-0.5 bg-slate-200 flex-1 mx-2 -mt-4"></div>
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center border border-emerald-500 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Ledger</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                        <button type="button" @click="newReturnStep = 1"
                                class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Back
                        </button>
                        <button type="button" @click="submitNewReturn()"
                                class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                            Confirm Cancellation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- INITIATE NEW EXCHANGE MODAL POPUP --}}
    <div x-show="openNewExchangeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up modal-widthexchange" @click.away="openNewExchangeModal = false; selectedExchangeSale = null;">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap" x-show="newExchangeStep === 2">Active Plan</span>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Step <span x-text="newExchangeStep"></span> of 2</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-white tracking-tight mt-1" x-text="newExchangeStep === 1 ? 'Initiate Unit Exchange' : 'Execute Exchange Plan'"></h2>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1" x-show="newExchangeStep === 2 && selectedExchangeSale"
                           x-text="selectedExchangeSale ? (selectedExchangeSale.project ? (selectedExchangeSale.project.code || selectedExchangeSale.project.name) : 'N/A') + ' - Door ' + (selectedExchangeSale.unit ? selectedExchangeSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedExchangeSale.customer ? selectedExchangeSale.customer.name : 'N/A') : ''"></p>
                    </div>
                    <button type="button" @click="openNewExchangeModal = false; selectedExchangeSale = null;" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            
            <div class="p-6 space-y-6 max-h-[78vh] overflow-y-auto font-sans text-xs bg-slate-50/50" x-ref="exchangeModalScroll">
                {{-- STEP 1: SELECT SALE --}}
                <div x-show="newExchangeStep === 1" class="space-y-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Select Active/Cancelled Sale *</label>
                        <select x-model="newExchangeSaleId" 
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm cursor-pointer font-semibold text-slate-800">
                            <option value="">— Select an Active/Cancelled Booking to Exchange —</option>
                            <template x-for="sale in sales.filter(s => (s.status === 'active' || s.status === 'cancelled') && (!s.sale_units || s.sale_units.length <= 1))" :key="sale.id">
                                <option :value="sale.id" 
                                        x-text="(sale.project ? (sale.project.code || sale.project.name) : 'N/A') + ' - ' + (sale.unit ? sale.unit.door_no : (sale.sale_units && sale.sale_units.length ? sale.sale_units.map(su => su.unit ? su.unit.door_no : '').filter(Boolean).join(', ') : 'N/A')) + ' — ' + (sale.customer ? sale.customer.name : 'N/A') + ' (' + sale.sale_number + ')'"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                        <button type="button" @click="openNewExchangeModal = false; selectedExchangeSale = null;"
                                class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="button" @click="selectNewExchangeSale()"
                                class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                            Next
                        </button>
                    </div>
                </div>
                
                {{-- STEP 2: FORM DETAILS --}}
                <div x-show="newExchangeStep === 2" class="space-y-6">
                    {{-- Target selectors card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                            <span>🎯 Target Unit Selections</span>
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Project *</label>
                                <select x-model="exchangeForm.new_project_id" @change="loadExchangeUnits()"
                                        :class="errors.new_project_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-white'"
                                        class="w-full px-3 py-2 border rounded-xl text-xs focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] focus:outline-none transition-all shadow-sm cursor-pointer font-semibold text-slate-700">
                                    <option value="">Select Target Project...</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.new_project_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.new_project_id) ? errors.new_project_id[0] : errors.new_project_id"></p></template>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Unit Type</label>
                                <select x-model="exchangeForm.new_unit_type" @change="exchangeForm.new_unit_id = ''; exchangeForm.new_unit_value = 0;" :disabled="!exchangeForm.new_project_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-250 focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 shadow-sm cursor-pointer font-semibold text-slate-700">
                                    <option value="">All Types</option>
                                    <template x-for="ut in exchangeUnitTypes" :key="ut.id">
                                        <option :value="ut.id" x-text="ut.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Available Unit *</label>
                                <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                    <button type="button" 
                                            @click="if (exchangeForm.new_project_id) { open = !open; if (open) $nextTick(() => $refs.modalTargetUnitSearchInput?.focus()); }" 
                                            :disabled="!exchangeForm.new_project_id"
                                            :class="errors.new_unit_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : (open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-250 bg-white hover:bg-slate-50 hover:border-slate-300')"
                                            class="w-full h-10 px-3 py-2 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs disabled:opacity-50">
                                        <template x-if="exchangeForm.new_unit_id && getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id)">
                                            <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                                <span class="font-extrabold text-slate-800 truncate text-xs" x-text="getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id).door_no"></span>
                                                <span class="text-[10px] text-slate-400 font-mono shrink-0" x-text="getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id).floor_name ? '(' + getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id).floor_name + ')' : ''"></span>
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 shrink-0"
                                                      x-text="getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id).unit_type_name || 'Unit'"></span>
                                            </div>
                                        </template>
                                        <template x-if="!exchangeForm.new_unit_id || !getFilteredExchangeAvailableUnits().find(u => u.id == exchangeForm.new_unit_id)">
                                            <span class="text-slate-400 font-medium">— Select Target Unit —</span>
                                        </template>
                                        <div class="flex items-center gap-1 shrink-0 ml-1">
                                            <template x-if="exchangeForm.new_unit_id">
                                                <span @click.stop="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear target unit">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </span>
                                            </template>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                         class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50 min-w-[280px]"
                                         style="display: none;">
                                        
                                        <!-- Search Header -->
                                        <div class="p-2 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                            <div class="relative flex items-center">
                                                <svg class="w-3.5 h-3.5 absolute left-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                <input type="text" 
                                                       x-model="search" 
                                                       x-ref="modalTargetUnitSearchInput"
                                                       placeholder="Type door no, floor, or unit type..."
                                                       @keydown.escape="open = false"
                                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/15 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                                <template x-if="search">
                                                    <button type="button" @click="search = ''" class="absolute right-2.5 text-slate-400 hover:text-slate-600">✕</button>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Clear Selection Item -->
                                        <button type="button" 
                                                @click="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); open = false; search = ''"
                                                class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition cursor-pointer">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Clear Target Unit</span>
                                        </button>

                                        <!-- Unit Options -->
                                        <div class="overflow-y-auto flex-1 p-1.5 space-y-1 max-h-60">
                                            <template x-for="unit in getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase())))" :key="unit.id">
                                                <button type="button"
                                                        @click="exchangeForm.new_unit_id = unit.id; onExchangeUnitSelect(); open = false; search = ''"
                                                        :class="exchangeForm.new_unit_id == unit.id ? 'bg-[#a38c29]/10 border-[#a38c29]/30 text-[#a38c29] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                        class="w-full px-3 py-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="font-extrabold text-xs truncate" :class="exchangeForm.new_unit_id == unit.id ? 'text-[#a38c29]' : 'text-slate-800'" x-text="unit.door_no"></span>
                                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0"
                                                              x-text="unit.unit_type_name || 'Unit'"></span>
                                                    </div>
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        <span class="text-[10px] text-slate-400 font-mono" x-text="unit.floor_name ? '(' + unit.floor_name + ')' : ''"></span>
                                                        <template x-if="exchangeForm.new_unit_id == unit.id">
                                                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </template>
                                                    </div>
                                                </button>
                                            </template>

                                            <div x-show="getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase()))).length === 0"
                                                 class="px-4 py-6 text-center text-xs text-slate-400 italic flex flex-col items-center gap-1.5">
                                                <svg class="w-6 h-6 text-slate-300 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                <span>No matching target units found</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="errors.new_unit_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.new_unit_id) ? errors.new_unit_id[0] : errors.new_unit_id"></p></template>
                            </div>
                        </div>
                    </div>

                    {{-- Contract values & Options Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Old Contract Value</label>
                                <input type="text" :value="selectedExchangeSale ? fmt(selectedExchangeSale.total_amount) : '₹0.00'" disabled
                                       class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-700 font-bold font-mono h-[38px] flex items-center shadow-inner">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">New Contract Value</label>
                                <input type="text" :value="fmt(exchangeForm.new_unit_value)" disabled
                                       class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold font-mono h-[38px] flex items-center shadow-inner">
                            </div>

                        </div>                    </div>

                    {{-- Financial Balance Grid --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 border border-slate-800 rounded-xl p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center text-white relative overflow-hidden shadow-md">
                        <div class="absolute -top-12 -left-12 w-32 h-32 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-3 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Old Contract Value</span>
                            <span class="font-extrabold text-slate-200 text-sm mt-1 block font-mono" x-text="selectedExchangeSale ? fmt(selectedExchangeSale.total_amount) : '₹0.00'"></span>
                        </div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-3 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Paid Amount</span>
                            <span class="font-extrabold text-emerald-400 text-sm mt-1 block font-mono" x-text="fmt(exchangeForm.equity_applied)"></span>
                        </div>
                        <div class="relative z-10 border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-3 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">New Contract Value</span>
                            <span class="font-extrabold text-white text-sm mt-1 block font-mono" x-text="fmt(exchangeForm.new_unit_value)"></span>
                        </div>
                        <div class="relative z-10 flex flex-col justify-center">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Differential Due (Receivable)</span>
                            <span class="font-extrabold text-[#d9bf3b] text-sm mt-1 block font-mono" x-text="fmt(calculateDifferentialDue())"></span>
                        </div>
                    </div>

                    {{-- PAYMENT PLAN SECTION --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Payment Plan & Schedule Configuration</span>
                            </label>
                            <span class="text-[9px] font-bold text-[#a38c29] bg-[#a38c29]/10 px-2 py-0.5 rounded-full uppercase" x-text="exchangeForm.payment_plan === 'emi' ? 'EMI Schedule Active' : 'Full / Lump-sum'"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Structure *</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center justify-center gap-2 p-2 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                           :class="exchangeForm.payment_plan === 'lump_sum' ? 'bg-[#a38c29]/10 border-[#a38c29] text-[#a38c29] font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" value="lump_sum" x-model="exchangeForm.payment_plan" class="sr-only">
                                        <span>Full / Lump-sum</span>
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-2 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                           :class="exchangeForm.payment_plan === 'emi' ? 'bg-[#a38c29]/10 border-[#a38c29] text-[#a38c29] font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" value="emi" x-model="exchangeForm.payment_plan" class="sr-only">
                                        <span>EMI / Installments</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="exchangeForm.payment_plan === 'emi'" class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">First Installment Date *</label>
                                <input type="date" x-model="exchangeForm.first_installment_date"
                                       class="w-full px-3 py-2 bg-white border border-slate-250 focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-medium text-slate-800">
                            </div>
                        </div>

                        <div x-show="exchangeForm.payment_plan === 'emi'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No. of Installments *</label>
                                <input type="number" min="1" max="120" x-model.number="exchangeForm.emi_installment_count"
                                       placeholder="e.g. 12"
                                       class="w-full px-3 py-2 bg-white border border-slate-250 focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-semibold text-slate-800">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Frequency *</label>
                                <select x-model="exchangeForm.emi_frequency"
                                        class="w-full px-3 py-2 bg-white border border-slate-250 focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-semibold text-slate-800 cursor-pointer">
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                        </div>

                        {{-- Live Schedule Preview --}}
                        <div x-show="exchangeForm.payment_plan === 'emi' && getExchangeEmiPreview().length > 0" class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Live Installment Schedule Preview</span>
                                <span class="text-[10px] font-bold text-[#a38c29] font-mono" x-text="getExchangeEmiPreview().length + ' Installments @ ' + fmt(getExchangeEmiPreview()[0]?.amount || 0) + ' / ' + (exchangeForm.emi_frequency === 'quarterly' ? 'qtr' : 'mo')"></span>
                            </div>

                            <div class="max-h-40 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50 divide-y divide-slate-100">
                                <template x-for="(inst, idx) in getExchangeEmiPreview()" :key="idx">
                                    <div class="px-3 py-1.5 text-xs flex items-center justify-between hover:bg-white transition">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center font-mono" x-text="inst.installment_no"></span>
                                            <span class="font-semibold text-slate-700 text-[11px]" x-text="inst.label"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] text-slate-400 font-mono" x-text="inst.due_date"></span>
                                            <span class="font-bold text-slate-800 font-mono text-[11px]" x-text="fmt(inst.amount)"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Reason Card --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            <span>💬 Exchange Reason / Notes *</span>
                        </p>
                        <textarea x-model="exchangeForm.reason" rows="2" placeholder="Write internal memo for the unit exchange..."
                                  :class="errors.reason ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-white'"
                                  class="w-full px-3 py-2 border rounded-xl text-xs focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] outline-none transition resize-none shadow-sm"></textarea>
                        <template x-if="errors.reason"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.reason) ? errors.reason[0] : errors.reason"></p></template>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                        <button type="button" @click="newExchangeStep = 1"
                                class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Back
                        </button>
                        <button type="button" @click="submitExchangePlan()"
                                class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                            Finalize Exchange & New EMI
                        </button>
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
                    @if(isset($unitTypes))
                        @foreach($unitTypes as $ut)
                            <option value="{{ $ut->id }}">{{ $ut->name }}</option>
                        @endforeach
                    @endif
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
                    <thead class="bg-[#a38c29] border-b border-[#8a7522] font-bold text-white uppercase tracking-wider">
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
                            x-text="targetReturnStatus === 'cancelled' ? 'Process Cancellation Details' : 'Process Cancel Details'"></h4>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-5">
        <!-- Card 1: Total Exchanges -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-purple-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Exchanges</span>
                <span class="w-2 h-2 rounded-full bg-purple-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getExchangeStats().totalExchanges">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 2: Total Difference Amount -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Difference Amount</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().totalDiff)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 3: Payable by Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Payable by Customer</span>
                <span class="w-2 h-2 rounded-full bg-amber-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().payableByCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 4: Refundable to Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-teal-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Refundable to Customer</span>
                <span class="w-2 h-2 rounded-full bg-teal-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().refundableToCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 5: Completed Exchanges -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Completed Exchanges</span>
                <span class="w-2 h-2 rounded-full bg-blue-500 shadow-xs"></span>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getExchangeStats().completedExchanges">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
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
                    <thead class="bg-[#a38c29] border-b border-[#8a7522] font-bold text-white uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-3 py-3 text-left">Exchange No.</th>
                            <th class="px-3 py-3 text-left">Date</th>
                            <th class="px-3 py-3 text-left">Project</th>
                            <th class="px-3 py-3 text-left bg-[#8f7a23]/60" colspan="2">Old Unit (Cancelled)</th>
                            <th class="px-3 py-3 text-left bg-[#8f7a23]/80" colspan="2">New Unit (Booked)</th>
                            <th class="px-3 py-3 text-right">Difference Amount</th>
                            <th class="px-3 py-3 text-left">Payable / Refundable</th>
                            <th class="px-3 py-3 text-center">Status</th>
                            <th class="px-3 py-3 text-right">Actions</th>
                        </tr>
                        <tr class="bg-[#8f7a23]/40 border-b border-[#8a7522] text-[9px] text-amber-100">
                            <th class="px-3 py-1 font-normal" colspan="3"></th>
                            <th class="px-3 py-1 bg-[#8f7a23]/50 font-semibold border-r border-[#8a7522]">Unit Details</th>
                            <th class="px-3 py-1 bg-[#8f7a23]/50 font-semibold border-r border-[#8a7522]">Customer</th>
                            <th class="px-3 py-1 bg-[#8f7a23]/70 font-semibold border-r border-[#8a7522]">Unit Details</th>
                            <th class="px-3 py-1 bg-[#8f7a23]/70 font-semibold border-r border-[#8a7522]">Customer</th>
                            <th class="px-3 py-1 font-normal" colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                        <template x-for="sale in paginatedExchangeSales()" :key="sale.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2.5 font-bold text-primary text-left" x-text="sale.status === 'exchanged' ? ('EXC-' + new Date(sale.updated_at).getFullYear() + '-' + String(sale.id).padStart(3, '0')) : '—'"></td>
                                <td class="px-3 py-2.5 text-slate-500 text-left" x-text="formatDate(sale.status === 'exchanged' ? sale.updated_at : sale.sale_date)"></td>
                                <td class="px-3 py-2.5 text-left" x-text="sale.project ? sale.project.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left bg-slate-100/20 border-r border-slate-100" x-text="sale.unit ? sale.unit.door_no : '—'"></td>
                                <td class="px-3 py-2.5 text-left font-bold text-slate-900 bg-slate-100/20 border-r border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-left bg-primary/5 border-r border-slate-100 font-bold text-primary" x-text="getNewUnitDoorNo(sale)"></td>
                                <td class="px-3 py-2.5 text-left font-bold text-slate-900 bg-primary/5 border-r border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                <td class="px-3 py-2.5 text-right font-mono text-slate-900" x-text="sale.status === 'exchanged' ? fmt(getDifferenceAmount(sale)) : '—'"></td>
                                <td class="px-3 py-2.5 text-left">
                                    <template x-if="sale.status === 'exchanged'">
                                        <span :class="getExchangeNetDue(sale) > 0 ? 'text-orange-600 font-bold' : (getExchangeNetDue(sale) < 0 ? 'text-teal-600 font-bold' : 'text-slate-600 font-bold')"
                                              x-text="getExchangeStatusText(sale)"></span>
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
                                            <button type="button" @click="selectExchangeSale(sale); newExchangeStep = 2; openNewExchangeModal = true;"
                                                    class="px-3 py-1.5 bg-primary hover:bg-primary-700 text-white font-extrabold rounded-lg text-[11px] uppercase transition-all tracking-wide shadow-sm">
                                                Process Exchange
                                            </button>
                                        </template>
                                        <template x-if="sale.status === 'exchanged'">
                                            <div class="flex items-center gap-1.5 justify-end">
                                                <button type="button" @click="viewExchangeSale = sale; openViewExchangeModal = true;" class="w-7 h-7 rounded-xl bg-[#f5f2df] text-[#8a7620] hover:bg-[#eae5cb] flex items-center justify-center transition-all" title="View Details">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <!-- <button type="button" @click="selectExchangeSale(sale); newExchangeStep = 2; openNewExchangeModal = true;" class="w-7 h-7 rounded-xl bg-[#e6f4ee] text-[#107b6e] hover:bg-[#d5ebe2] flex items-center justify-center transition-all" title="Edit Exchange">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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

            {{-- Exchange Table Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl" x-show="filteredExchangeSales().length > 0">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900" x-text="(exchangeCurrentPage - 1) * exchangePerPage + 1"></span> TO 
                    <span class="text-slate-900" x-text="Math.min(exchangeCurrentPage * exchangePerPage, filteredExchangeSales().length)"></span> OF 
                    <span class="text-slate-900" x-text="filteredExchangeSales().length"></span> EXCHANGES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="if(exchangeCurrentPage > 1) exchangeCurrentPage--" 
                            :disabled="exchangeCurrentPage <= 1"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        PREV
                    </button>
                    
                    {{-- Page Numbers --}}
                    <template x-for="p in getExchangePageNumbers()" :key="p">
                        <span class="inline-flex items-center gap-1">
                            <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                            <button type="button" x-show="p !== '...'"
                                    @click="exchangeCurrentPage = p"
                                    x-text="p"
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs"
                                    :class="exchangeCurrentPage === p ? 'bg-primary text-white border border-primary' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                        </span>
                    </template>
                    
                    <button type="button" @click="if(exchangeCurrentPage < getExchangeTotalPages()) exchangeCurrentPage++" 
                            :disabled="exchangeCurrentPage >= getExchangeTotalPages()"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

        {{-- EXECUTE EXCHANGE PLAN PANEL --}}
        <template x-if="selectedExchangeSale && !openNewExchangeModal">
            <div class="bg-blue-50/60 border border-blue-150 rounded-2xl p-4 space-y-4 animate-fade-in">
                <div class="flex items-center justify-between border-b border-blue-200/50 pb-2">
                    <div>
                        <span class="text-[9px] font-bold text-blue-800 uppercase tracking-widest">Active Plan</span>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Execute Exchange Plan</h4>
                        <p class="text-[10px] text-slate-500 font-semibold"
                           x-text="selectedExchangeSale ? (selectedExchangeSale.project ? (selectedExchangeSale.project.code || selectedExchangeSale.project.name) : 'N/A') + ' - Door ' + (selectedExchangeSale.unit ? selectedExchangeSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedExchangeSale.customer ? selectedExchangeSale.customer.name : 'N/A') : ''"></p>
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
                                    @click="if (exchangeForm.new_project_id) { open = !open; if (open) $nextTick(() => $refs.panelTargetUnitSearchInput?.focus()); }" 
                                    :disabled="!exchangeForm.new_project_id"
                                    :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300'"
                                    class="w-full h-10 px-3 py-2 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs disabled:opacity-50">
                                <template x-if="exchangeForm.new_unit_id && exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id)">
                                    <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                        <span class="font-extrabold text-slate-800 truncate text-xs" x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).door_no"></span>
                                        <span class="text-[10px] text-slate-400 font-mono shrink-0" x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name ? '(' + exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name + ')' : ''"></span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 shrink-0"
                                              x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).unit_type_name || 'Unit'"></span>
                                    </div>
                                </template>
                                <template x-if="!exchangeForm.new_unit_id || !exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id)">
                                    <span class="text-slate-400 font-medium">— Select Target Unit —</span>
                                </template>
                                <div class="flex items-center gap-1 shrink-0 ml-1">
                                    <template x-if="exchangeForm.new_unit_id">
                                        <span @click.stop="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear target unit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50 min-w-[280px]"
                                 style="display: none;">
                                
                                <!-- Search Header -->
                                <div class="p-2 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                    <div class="relative flex items-center">
                                        <svg class="w-3.5 h-3.5 absolute left-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-model="search" 
                                               x-ref="panelTargetUnitSearchInput"
                                               placeholder="Type door no, floor, or unit type..."
                                               @keydown.escape="open = false"
                                               class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/15 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                        <template x-if="search">
                                            <button type="button" @click="search = ''" class="absolute right-2.5 text-slate-400 hover:text-slate-600">✕</button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Clear Selection Item -->
                                <button type="button" 
                                        @click="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); open = false; search = ''"
                                        class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition cursor-pointer">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Clear Target Unit</span>
                                </button>

                                <!-- Unit Options -->
                                <div class="overflow-y-auto flex-1 p-1.5 space-y-1 max-h-60">
                                    <template x-for="unit in getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase())))" :key="unit.id">
                                        <button type="button"
                                                @click="exchangeForm.new_unit_id = unit.id; onExchangeUnitSelect(); open = false; search = ''"
                                                :class="exchangeForm.new_unit_id == unit.id ? 'bg-[#a38c29]/10 border-[#a38c29]/30 text-[#a38c29] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                class="w-full px-3 py-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="font-extrabold text-xs truncate" :class="exchangeForm.new_unit_id == unit.id ? 'text-[#a38c29]' : 'text-slate-800'" x-text="unit.door_no"></span>
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0"
                                                      x-text="unit.unit_type_name || 'Unit'"></span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="text-[10px] text-slate-400 font-mono" x-text="unit.floor_name ? '(' + unit.floor_name + ')' : ''"></span>
                                                <template x-if="exchangeForm.new_unit_id == unit.id">
                                                    <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </template>
                                            </div>
                                        </button>
                                    </template>

                                    <div x-show="getFilteredExchangeAvailableUnits().filter(u => !search || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase()))).length === 0"
                                         class="px-4 py-6 text-center text-xs text-slate-400 italic flex flex-col items-center gap-1.5">
                                        <svg class="w-6 h-6 text-slate-300 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <span>No matching target units found</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-semibold">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Old Contract Value:</label>
                        <input type="text" :value="selectedExchangeSale ? fmt(selectedExchangeSale.total_amount) : '₹0.00'" disabled
                               class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-500 font-bold font-mono">
                    </div>

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
                <div class="bg-white border border-blue-100 rounded-xl p-3 grid grid-cols-2 sm:grid-cols-4 gap-3 divide-x divide-slate-100">
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Old Contract Value</p>
                        <p class="text-xs font-extrabold text-slate-700 font-mono mt-0.5" x-text="selectedExchangeSale ? fmt(selectedExchangeSale.total_amount) : '₹0.00'"></p>
                    </div>
                    <div class="text-center px-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Paid Amount</p>
                        <p class="text-xs font-extrabold text-emerald-700 font-mono mt-0.5" x-text="fmt(exchangeForm.equity_applied)"></p>
                    </div>
                    <div class="text-center px-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">New Contract Value</p>
                        <p class="text-xs font-extrabold text-slate-800 font-mono mt-0.5" x-text="fmt(exchangeForm.new_unit_value)"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Differential Due</p>
                        <p class="text-xs font-extrabold text-blue-700 font-mono mt-0.5" x-text="fmt(calculateDifferentialDue())"></p>
                    </div>
                </div>

                {{-- PAYMENT PLAN SECTION --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Payment Plan Configuration</span>
                        </label>
                        <span class="text-[9px] font-bold text-[#a38c29] bg-[#a38c29]/10 px-2 py-0.5 rounded-full uppercase" x-text="exchangeForm.payment_plan === 'emi' ? 'EMI Schedule Active' : 'Full / Lump-sum'"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Structure</label>
                            <div class="grid grid-cols-2 gap-1.5">
                                <label class="flex items-center justify-center p-1.5 rounded-lg border cursor-pointer transition text-[11px] font-semibold"
                                       :class="exchangeForm.payment_plan === 'lump_sum' ? 'bg-[#a38c29]/10 border-[#a38c29] text-[#a38c29] font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <input type="radio" value="lump_sum" x-model="exchangeForm.payment_plan" class="sr-only">
                                    <span>Lump-sum</span>
                                </label>
                                <label class="flex items-center justify-center p-1.5 rounded-lg border cursor-pointer transition text-[11px] font-semibold"
                                       :class="exchangeForm.payment_plan === 'emi' ? 'bg-[#a38c29]/10 border-[#a38c29] text-[#a38c29] font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <input type="radio" value="emi" x-model="exchangeForm.payment_plan" class="sr-only">
                                    <span>EMI</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="exchangeForm.payment_plan === 'emi'" class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">First Installment Date</label>
                            <input type="date" x-model="exchangeForm.first_installment_date"
                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-medium text-slate-800">
                        </div>
                    </div>

                    <div x-show="exchangeForm.payment_plan === 'emi'" class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No. of Installments</label>
                            <input type="number" min="1" max="120" x-model.number="exchangeForm.emi_installment_count"
                                   placeholder="12"
                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-semibold text-slate-800">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Frequency</label>
                            <select x-model="exchangeForm.emi_frequency"
                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all font-semibold text-slate-800 cursor-pointer">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                            </select>
                        </div>
                    </div>

                    {{-- Live Schedule Preview --}}
                    <div x-show="exchangeForm.payment_plan === 'emi' && getExchangeEmiPreview().length > 0" class="mt-2 pt-2 border-t border-slate-100 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Installment Preview</span>
                            <span class="text-[10px] font-bold text-[#a38c29] font-mono" x-text="getExchangeEmiPreview().length + ' Inst. @ ' + fmt(getExchangeEmiPreview()[0]?.amount || 0)"></span>
                        </div>

                        <div class="max-h-36 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50 divide-y divide-slate-100">
                            <template x-for="(inst, idx) in getExchangeEmiPreview()" :key="idx">
                                <div class="px-2.5 py-1 text-[11px] flex items-center justify-between hover:bg-white transition">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-700 text-[9px] font-bold flex items-center justify-center font-mono" x-text="inst.installment_no"></span>
                                        <span class="font-semibold text-slate-700" x-text="inst.label"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] text-slate-400 font-mono" x-text="inst.due_date"></span>
                                        <span class="font-bold text-slate-800 font-mono" x-text="fmt(inst.amount)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
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
        <!-- <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 font-sans">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Returns vs. Exchanges (Monthly)</h4>
            <div id="returnsExchangesChart" class="w-full" style="height: 180px;"></div>
        </div> -->
    </div>
    @endif

    {{-- VIEW EXCHANGE DETAILS MODAL POPUP --}}
    <div x-show="openViewExchangeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openViewExchangeModal = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full overflow-hidden animate-fade-in">
                
                {{-- Header Combo --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-[#a38c29]/10">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Exchanged</span>
                                <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Exchange Details</span>
                            </div>
                            <h2 class="text-lg font-extrabold text-white tracking-tight mt-1">Exchange Details</h2>
                            <p class="text-[10px] text-slate-400 font-semibold mt-1" x-show="viewExchangeSale"
                               x-text="viewExchangeSale ? (viewExchangeSale.project ? (viewExchangeSale.project.code || viewExchangeSale.project.name) : 'N/A') + ' - Door ' + (viewExchangeSale.unit ? viewExchangeSale.unit.door_no : 'N/A') + ' • Customer: ' + (viewExchangeSale.customer ? viewExchangeSale.customer.name : 'N/A') + ' • Sale No: ' + viewExchangeSale.sale_number : ''"></p>
                        </div>
                        <button type="button" @click="openViewExchangeModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                    </div>
                </div>
                
                <div class="p-6 space-y-4 max-h-[78vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
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
                                <p class="text-slate-500">Paid Amount: <span class="text-emerald-700 font-bold font-mono" x-text="viewExchangeSale ? fmt(getPaidTillDate(viewExchangeSale)) : '—'"></span></p>
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
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Paid Amount</p>
                            <p class="text-sm font-extrabold text-emerald-700 font-mono mt-0.5" x-text="viewExchangeSale ? fmt(getPaidTillDate(viewExchangeSale)) : '₹0.00'"></p>
                        </div>
                        <div class="text-center px-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Net Balance Due</p>
                            <p class="text-sm font-extrabold text-slate-800 font-mono mt-0.5" x-text="viewExchangeSale ? fmt(getDifferenceAmount(viewExchangeSale)) : '₹0.00'"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Payable / Refundable</p>
                            <p class="text-xs font-extrabold mt-1 uppercase" 
                               :class="viewExchangeSale && getExchangeNetDue(viewExchangeSale) > 0 ? 'text-orange-600' : (viewExchangeSale && getExchangeNetDue(viewExchangeSale) < 0 ? 'text-teal-600' : 'text-slate-600')"
                               x-text="viewExchangeSale ? getExchangeStatusText(viewExchangeSale) : '—'"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Notes / Narration Details</label>
                        <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 min-h-12"
                             x-text="viewExchangeSale ? (viewExchangeSale.cancellation_reason || 'No notes entered.') : ''"></div>
                    </div>

                    {{-- ARCHIVED LOG SNAPSHOT CARD --}}
                    <template x-if="getExchangeSnapshot(viewExchangeSale)">
                        <div class="bg-white border border-blue-200 rounded-2xl p-4 space-y-3 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <h4 class="text-[10px] font-extrabold text-blue-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>Archived Unit Exchange Log & Sourcable Snapshot</span>
                                </h4>
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[8px] font-bold uppercase font-mono"
                                      x-text="'Archived ' + (getExchangeSnapshot(viewExchangeSale).exchange_meta ? getExchangeSnapshot(viewExchangeSale).exchange_meta.exchanged_at : '')"></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[11px]">
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 space-y-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Old Unit & Agreement Info</span>
                                    <p class="text-slate-600">Sale No: <span class="font-extrabold text-slate-800 font-mono" x-text="getExchangeSnapshot(viewExchangeSale).old_sale ? getExchangeSnapshot(viewExchangeSale).old_sale.sale_number : '—'"></span></p>
                                    <p class="text-slate-600">Door / Unit: <span class="font-extrabold text-slate-800" x-text="getExchangeSnapshot(viewExchangeSale).old_unit ? getExchangeSnapshot(viewExchangeSale).old_unit.door_no : '—'"></span></p>
                                    <p class="text-slate-600">Unit Type & Floor: <span class="font-bold text-slate-700" x-text="(getExchangeSnapshot(viewExchangeSale).old_unit ? getExchangeSnapshot(viewExchangeSale).old_unit.unit_type_name || '' : '') + ' • ' + (getExchangeSnapshot(viewExchangeSale).old_unit ? getExchangeSnapshot(viewExchangeSale).old_unit.floor_name || '' : '')"></span></p>
                                    <p class="text-slate-600">Old Contract Value: <span class="font-extrabold text-slate-800 font-mono" x-text="fmt(getExchangeSnapshot(viewExchangeSale).old_sale ? getExchangeSnapshot(viewExchangeSale).old_sale.total_amount : 0)"></span></p>
                                </div>

                                <div class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-200/80 space-y-1">
                                    <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-widest block">Transferred Financials & Receipts</span>
                                    <p class="text-slate-600">Total Paid Amount: <span class="font-extrabold text-emerald-700 font-mono text-xs" x-text="fmt(getExchangeSnapshot(viewExchangeSale).old_sale ? getExchangeSnapshot(viewExchangeSale).old_sale.total_paid : 0)"></span></p>
                                    <p class="text-slate-600">Receipts History Count: <span class="font-extrabold text-slate-800 font-mono" x-text="getExchangeSnapshot(viewExchangeSale).receipts ? getExchangeSnapshot(viewExchangeSale).receipts.length : 0"></span></p>
                                    <p class="text-slate-600">Carried Forward Status: <span class="font-bold text-emerald-700 uppercase text-[10px]" x-text="getExchangeSnapshot(viewExchangeSale).exchange_meta && getExchangeSnapshot(viewExchangeSale).exchange_meta.carry_forward ? 'Yes (Equity Transferred)' : 'No'"></span></p>
                                    <p class="text-slate-600">Processed By User: <span class="font-bold text-slate-700" x-text="getExchangeSnapshot(viewExchangeSale).exchange_meta ? getExchangeSnapshot(viewExchangeSale).exchange_meta.exchanged_by_user : 'System'"></span></p>
                                </div>
                            </div>

                            {{-- Collapsible Archived Receipts & EMI Schedule details --}}
                            <div x-data="{ openTables: false }" class="pt-1">
                                <button type="button" @click="openTables = !openTables"
                                        class="text-[9px] font-extrabold text-blue-700 hover:text-blue-900 uppercase tracking-wider flex items-center gap-1 cursor-pointer">
                                    <span x-text="openTables ? 'Hide Detailed Receipts & EMI Schedule History' : '📜 Expand Full Receipts & EMI Schedule Audit Log'"></span>
                                    <svg class="w-3 h-3 transition-transform" :class="openTables ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="openTables" class="mt-2 space-y-3">
                                    {{-- Archived Receipts Table --}}
                                    <div class="space-y-1">
                                        <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Archived Receipts History</span>
                                        <div class="max-h-36 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50 divide-y divide-slate-100">
                                            <template x-for="r in getExchangeSnapshot(viewExchangeSale).receipts" :key="r.id">
                                                <div class="px-3 py-1.5 text-[10px] flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-slate-800 font-mono" x-text="r.receipt_number"></span>
                                                        <span class="text-slate-400 font-mono" x-text="r.receipt_date"></span>
                                                        <span class="px-1.5 py-0.2 rounded bg-slate-200 text-slate-700 text-[8px] font-bold uppercase" x-text="r.payment_mode"></span>
                                                    </div>
                                                    <span class="font-bold text-emerald-700 font-mono" x-text="fmt(r.amount)"></span>
                                                </div>
                                            </template>
                                            <template x-if="!getExchangeSnapshot(viewExchangeSale).receipts || getExchangeSnapshot(viewExchangeSale).receipts.length === 0">
                                                <div class="px-3 py-2 text-[10px] text-slate-400 italic text-center">No receipts recorded prior to exchange</div>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Archived Installments Schedule Table --}}
                                    <div class="space-y-1">
                                        <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Archived EMI Installments Schedule</span>
                                        <div class="max-h-36 overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50 divide-y divide-slate-100">
                                            <template x-for="inst in getExchangeSnapshot(viewExchangeSale).installments" :key="inst.id">
                                                <div class="px-3 py-1.5 text-[10px] flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-700 text-[8px] font-bold flex items-center justify-center font-mono" x-text="inst.installment_no"></span>
                                                        <span class="font-semibold text-slate-700" x-text="inst.label"></span>
                                                        <span class="text-slate-400 font-mono" x-text="inst.due_date"></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-1.5 py-0.2 rounded text-[8px] font-bold uppercase"
                                                              :class="inst.status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'"
                                                              x-text="inst.status"></span>
                                                        <span class="font-bold text-slate-800 font-mono" x-text="fmt(inst.amount)"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!getExchangeSnapshot(viewExchangeSale).installments || getExchangeSnapshot(viewExchangeSale).installments.length === 0">
                                                <div class="px-3 py-2 text-[10px] text-slate-400 italic text-center">No EMI schedule configured prior to exchange</div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex items-center justify-end pt-4 border-t border-slate-100">
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
