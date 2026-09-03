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
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-purple-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(168,85,247,0.2)] hover:border-r-purple-500/20 hover:border-y-purple-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span x-text="isCancellationTab ? 'Total Cancellations' : 'Total Returns'">Total Returns</span>
                <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 border border-purple-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getReturnStats().totalReturns">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 2: Return Amount -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span x-text="isCancellationTab ? 'Cancellation Amount' : 'Return Amount'">Return Amount</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().returnAmount)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 3: Payable to Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.2)] hover:border-r-amber-500/20 hover:border-y-amber-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Payable to Customer</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().payableToCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 4: Receivable from Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-teal-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(20,184,166,0.2)] hover:border-r-teal-500/20 hover:border-y-teal-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Receivable from Customer</span>
                <div class="w-6 h-6 rounded-md bg-teal-50 text-teal-600 border border-teal-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-teal-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getReturnStats().receivableFromCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>
    </div></div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        
        {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-primary group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Customer/Unit..." 
                           x-model="returnFilters.search"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-primary/60 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="returnFilters.search" @click="returnFilters.search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                    </div>
                    <select x-model="returnFilters.project_id"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-primary/60 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Unit Type Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <select x-model="returnFilters.type"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-primary/60 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Unit Types</option>
                        @if(isset($unitTypes))
                            @foreach($unitTypes as $ut)
                                <option value="{{ $ut->id }}">{{ $ut->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Return Type Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="returnFilters.status"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-primary/60 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Return Types</option>
                        <option value="cancelled">Cancellation</option>
                        <option value="returned">Return</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            {{-- Reset Filters Button --}}
            <button type="button" @click="returnFilters.search = ''; returnFilters.project_id = ''; returnFilters.type = ''; returnFilters.status = '';"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-6 py-2.5 text-xs font-extrabold text-slate-700 shadow-sm shadow-slate-200/50 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-slate-500 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
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
                                <td class="px-3 py-2.5 text-left" x-text="sale.unit ? formatUnitDisplay(sale.unit) : 'N/A'"></td>
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

        <div x-show="selectedReturnSale" class="fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <!-- VIEW MODE MODAL (New Design) -->
            <template x-if="!isEditReturn">
                <div class="w-full max-w-4xl max-h-[95vh] bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up flex flex-col" @click.away="selectedReturnSale = null">
                    <!-- Header -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-primary-500/10 shrink-0">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29]/20 text-[#d9bf3b] flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    Cancel Sale Details
                                </h2>
                                <p class="text-[10px] text-slate-400 font-semibold mt-1 ml-10">View cancellation information and process status</p>
                            </div>
                            <button type="button" @click="selectedReturnSale = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body container -->
                    <div class="p-6 overflow-y-auto bg-[#f8f9fa] space-y-4 flex-1">
                        
                        <!-- Badges & Header Info -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex gap-2">
                                <span class="px-3 py-1 bg-[#a38c29]/10 border border-[#a38c29]/20 text-[#a38c29] text-[10px] font-extrabold rounded-md uppercase tracking-widest shadow-sm">VIEW MODE</span>
                                <span class="px-3 py-1 bg-rose-50 border border-rose-100 text-rose-600 text-[10px] font-extrabold rounded-md uppercase tracking-widest shadow-sm" x-text="selectedReturnSale && selectedReturnSale.status === 'cancelled' ? 'CANCELLED' : 'RETURNED'"></span>
                            </div>
                            <div>
                                <span class="px-3 py-1 border border-rose-100 text-rose-600 bg-rose-50 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm" x-text="selectedReturnSale && selectedReturnSale.status === 'cancelled' ? '⊗ Cancelled' : '⊗ Returned'"></span>
                            </div>
                        </div>

                        <!-- Sale/Booking Banner -->
                        <div class="p-3 border border-slate-200 bg-white rounded-lg flex items-center gap-3 shadow-sm">
                            <div class="w-8 h-8 rounded bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">SALE / BOOKING</div>
                                <div class="text-xs font-bold text-slate-700 mt-0.5" x-text="selectedReturnSale ? (selectedReturnSale.project ? (selectedReturnSale.project.code || selectedReturnSale.project.name) : 'N/A') + ' - ' + (selectedReturnSale.unit ? selectedReturnSale.unit.door_no : 'N/A') + ' • Customer: ' + (selectedReturnSale.customer ? selectedReturnSale.customer.name : 'N/A') + ' • ' + (selectedReturnSale.status === 'cancelled' ? 'Booking No: ' : 'Sale No: ') + selectedReturnSale.sale_number : ''"></div>
                            </div>
                        </div>

                        <!-- Grid sections -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Cancellation Summary -->
                            <div class="md:col-span-2 border border-[#a38c29]/20 rounded-lg p-5 bg-white relative overflow-hidden shadow-sm">
                                <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-4 uppercase tracking-wide">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Cancellation Summary
                                </h3>
                                <div class="grid grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-500 mb-1 uppercase tracking-wider">Total Paid</div>
                                        <div class="text-sm font-extrabold text-slate-800 font-mono" x-text="selectedReturnSale ? fmt(getPaidTillDate(selectedReturnSale)) : '—'"></div>
                                    </div>
                                    <div class="border-l border-slate-100 pl-4">
                                        <div class="text-[10px] font-bold text-slate-500 mb-1 uppercase tracking-wider">Cancellation Fee</div>
                                        <div class="text-sm font-extrabold text-red-600 font-mono" x-text="selectedReturnSale && selectedReturnSale.cancellation_fee ? '- ' + fmt(selectedReturnSale.cancellation_fee) : '- ₹0.00'"></div>
                                    </div>
                                    <div class="border-l border-slate-100 pl-4">
                                        <div class="text-[10px] font-bold text-slate-500 mb-1 uppercase tracking-wider">Approved Refund Amount</div>
                                        <div class="text-sm font-extrabold text-[#a38c29] font-mono" x-text="selectedReturnSale ? fmt(calculateApprovedRefund(selectedReturnSale)) : '—'"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancellation Date -->
                            <div class="border border-[#a38c29]/10 rounded-lg p-5 bg-[#a38c29]/[0.03] shadow-sm">
                                <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-4 uppercase tracking-wide">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Cancellation Date
                                </h3>
                                <div class="text-sm font-extrabold text-slate-800 pt-1" x-text="selectedReturnSale && selectedReturnSale.cancelled_at ? formatDate(selectedReturnSale.cancelled_at) : (selectedReturnSale ? formatDate(selectedReturnSale.updated_at) : '')"></div>
                            </div>
                        </div>

                        <!-- Sale Information -->
                        <div class="border border-slate-200 rounded-lg p-5 bg-white shadow-sm">
                            <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-5 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sale Information
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-5 gap-x-4">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Project</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.project ? selectedReturnSale.project.name : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Unit / Plot</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.unit ? selectedReturnSale.unit.door_no : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Sale Amount</div>
                                    <div class="text-xs font-bold text-slate-800 font-mono" x-text="selectedReturnSale ? fmt(selectedReturnSale.total_amount) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Booked On</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale ? formatDate(selectedReturnSale.agreement_date || selectedReturnSale.created_at) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Customer</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.customer ? selectedReturnSale.customer.name : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Customer Mobile</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.customer ? selectedReturnSale.customer.phone : '—'"></div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Customer Email</div>
                                    <div class="text-xs font-bold text-slate-800 truncate" x-text="selectedReturnSale && selectedReturnSale.customer && selectedReturnSale.customer.email ? selectedReturnSale.customer.email : '—'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Cancellation Information -->
                        <div class="border border-slate-200 rounded-lg p-5 bg-white shadow-sm">
                            <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-5 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Cancellation Information
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-5 gap-x-4">
                                <div class="md:col-span-1">
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Cancellation Reason</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.cancellation_reason ? selectedReturnSale.cancellation_reason : '—'"></div>
                                </div>
                                <div class="md:col-span-1">
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Detailed Reason / Remarks</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale ? (selectedReturnSale.detailed_reason || selectedReturnSale.cancellation_remarks || '—') : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Cancellation Date</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale ? (selectedReturnSale.cancelled_at ? formatDate(selectedReturnSale.cancelled_at) : formatDate(selectedReturnSale.updated_at)) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Cancelled By</div>
                                    <div class="text-xs font-bold text-slate-800">Admin User</div>
                                </div>
                            </div>
                        </div>

                        <!-- Cancellation Fee Details -->
                        <div class="border border-slate-200 rounded-lg p-5 bg-white shadow-sm">
                            <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-5 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                Cancellation Fee Details
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-5 gap-x-4">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Fee Type</div>
                                    <div class="text-xs font-bold text-slate-800">Fixed Amount</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Cancellation Fee Amount</div>
                                    <div class="text-xs font-bold text-slate-800 font-mono" x-text="selectedReturnSale && selectedReturnSale.cancellation_fee ? fmt(selectedReturnSale.cancellation_fee) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Deducted From Refund</div>
                                    <div class="text-xs font-bold text-emerald-600 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Yes</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Description</div>
                                    <div class="text-xs font-bold text-slate-800">This amount has been deducted as cancellation fee.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Refund & Payment Details -->
                        <div class="border border-slate-200 rounded-lg p-5 bg-white shadow-sm">
                            <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-5 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Refund & Payment Details
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Refund Mode</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.refund_mode ? selectedReturnSale.refund_mode : 'Bank Transfer'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Refund Amount</div>
                                    <div class="text-xs font-extrabold text-[#a38c29] font-mono" x-text="selectedReturnSale ? fmt(calculateApprovedRefund(selectedReturnSale)) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Bank Name</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.refund_bank ? selectedReturnSale.refund_bank : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Reference No.</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.refund_reference ? selectedReturnSale.refund_reference : '—'"></div>
                                </div>
                                <div class="md:col-span-1">
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Refund Remarks</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.refund_remarks ? selectedReturnSale.refund_remarks : '—'"></div>
                                </div>
                                <div class="md:col-span-1">
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Refund Status</div>
                                    <div class="mt-1">
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-extrabold uppercase tracking-widest shadow-sm" x-text="getRefundStatus(selectedReturnSale)"></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Refund Initiated On</div>
                                    <div class="text-xs font-bold text-slate-800" x-text="selectedReturnSale && selectedReturnSale.refund_initiated_at ? formatDate(selectedReturnSale.refund_initiated_at) : '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Expected Refund Date</div>
                                    <div class="text-xs font-bold text-slate-800">Within 7 working days</div>
                                </div>
                            </div>
                        </div>

                        <!-- Cancellation Process Flow -->
                        <div class="border border-slate-200 rounded-lg p-5 bg-white shadow-sm">
                            <h3 class="text-xs font-extrabold text-[#a38c29] flex items-center gap-1.5 mb-8 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Cancellation Process Flow
                            </h3>
                            <div class="flex items-start justify-between text-[11px] font-bold text-slate-650 max-w-4xl mx-auto py-2 relative">
                                <!-- Process steps dynamically driven by status -->
                                <div class="flex flex-col items-center gap-3 w-1/4 relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    </div>
                                    <div class="flex flex-col text-center items-center">
                                        <span class="text-xs font-bold text-slate-800">Cancellation Requested</span>
                                        <span class="text-[10px] text-slate-400 font-normal mt-0.5">Admin User</span>
                                        <span class="text-[10px] text-slate-400 font-normal" x-text="selectedReturnSale && selectedReturnSale.cancelled_at ? formatDate(selectedReturnSale.cancelled_at) : '—'"></span>
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-extrabold uppercase mt-1.5 shadow-sm">Completed</span>
                                    </div>
                                </div>
                                <div class="h-0.5 bg-slate-200 absolute top-4 left-[12.5%] right-[12.5%] -z-0"></div>
                                
                                <div class="flex flex-col items-center gap-3 w-1/4 relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div class="flex flex-col text-center items-center">
                                        <span class="text-xs font-bold text-slate-800">Approved</span>
                                        <span class="text-[10px] text-slate-400 font-normal mt-0.5">Manager User</span>
                                        <span class="text-[10px] text-slate-400 font-normal" x-text="selectedReturnSale && selectedReturnSale.cancelled_at ? formatDate(selectedReturnSale.cancelled_at) : '—'"></span>
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-extrabold uppercase mt-1.5 shadow-sm">Completed</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-center gap-3 w-1/4 relative z-10">
                                    <div class="w-8 h-8 rounded-full text-white flex items-center justify-center shadow-md transition-colors" :class="getRefundStatus(selectedReturnSale) !== 'Pending' ? 'bg-[#a38c29]' : 'bg-slate-300'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex flex-col text-center items-center">
                                        <span class="text-xs font-bold text-slate-800">Refund Initiated</span>
                                        <span class="text-[10px] text-slate-400 font-normal mt-0.5">Accountant User</span>
                                        <span class="text-[10px] text-slate-400 font-normal" x-text="selectedReturnSale && selectedReturnSale.refund_initiated_at ? formatDate(selectedReturnSale.refund_initiated_at) : '—'"></span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase mt-1.5 shadow-sm" :class="getRefundStatus(selectedReturnSale) !== 'Pending' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'" x-text="getRefundStatus(selectedReturnSale) !== 'Pending' ? 'Completed' : 'Pending'"></span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-center gap-3 w-1/4 relative z-10">
                                    <div class="w-8 h-8 rounded-full text-white flex items-center justify-center shadow-md transition-colors" :class="getRefundStatus(selectedReturnSale) === 'Completed' ? 'bg-blue-500' : 'bg-slate-300'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="flex flex-col text-center items-center">
                                        <span class="text-xs font-bold text-slate-800">Refund Completed</span>
                                        <span class="text-[10px] text-slate-400 font-normal mt-0.5">System</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase mt-1.5 shadow-sm" :class="getRefundStatus(selectedReturnSale) === 'Completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600'" x-text="getRefundStatus(selectedReturnSale) === 'Completed' ? 'Completed' : 'Pending'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white shrink-0">
                        <button type="button" @click="selectedReturnSale = null" class="px-5 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg transition flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Close
                        </button>
                        <button type="button" class="px-5 py-2 border border-[#a38c29] text-[#a38c29] hover:bg-[#a38c29] hover:text-white text-xs font-bold rounded-lg transition flex items-center gap-2 bg-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print Details
                        </button>
                    </div>
                </div>
            </template>

            <!-- ACTIVE FORM MODAL (Original Edit Design) -->
            <template x-if="isEditReturn">
<div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="selectedReturnSale = null">
                {{-- Header (Image 2 style) --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0 rounded-t-2xl">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 flex items-center justify-center text-[#d9bf3b] shadow-inner shadow-[#a38c29]/30 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap" x-text="selectedReturnSale ? (selectedReturnSale.status === 'cancelled' ? 'Cancellation' : 'Return') : 'Audit Trail'">Audit Trail</span>
                                <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5" x-text="!isEditReturn ? 'View Return Details' : (targetReturnStatus === 'cancelled' ? 'Process Cancellation Details' : 'Process Cancel Details')">Process Cancellation Details</h2>
                            </div>
                        </div>
                        <button type="button" @click="selectedReturnSale = null" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
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
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Return/Cancel Date</label>
                                <input type="date" x-model="returnForm.date" :disabled="!isEditReturn"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm disabled:opacity-75 disabled:cursor-not-allowed">
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
            </template>
        </div>
        </template>

    {{-- INITIATE NEW RETURN / CANCELLATION MODAL POPUP (Redesigned matching Image 2 style) --}}
    <template x-teleport="body">
    <div x-show="openNewReturnModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-cloak x-transition.opacity>
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up" @click.away="openNewReturnModal = false">
            {{-- Header (Image 2 style) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 flex items-center justify-center text-[#d9bf3b] shadow-inner shadow-[#a38c29]/30 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider" x-text="isCancellationTab ? 'Sale Cancellation' : 'Return Sale'">Return Sale</h2>
                            <p class="text-[11px] text-slate-300 mt-0.5 font-normal">Search and select the sale to be cancelled and provide cancellation details.</p>
                        </div>
                    </div>
                    <button type="button" @click="openNewReturnModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            {{-- Stepper --}}
            <div class="bg-slate-50/80 px-6 py-3 border-b border-slate-200/80 flex justify-center flex-shrink-0">
                <div class="flex items-center gap-4 sm:gap-8 text-xs font-semibold">
                    <div class="flex items-center gap-2.5" :class="newReturnStep >= 1 ? 'text-[#a38c29]' : 'text-slate-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition shadow-2xs" :class="newReturnStep >= 1 ? 'bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white shadow-[#a38c29]/30' : 'bg-white text-slate-400 border border-slate-200'">1</div>
                        <div class="flex flex-col">
                            <span class="font-bold text-xs" :class="newReturnStep >= 1 ? 'text-slate-900' : 'text-slate-400'">Select Sale</span>
                            <span class="text-[10px] font-normal text-slate-400 hidden sm:inline">Search and choose sale</span>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <div class="flex items-center gap-2.5" :class="newReturnStep >= 2 ? 'text-[#a38c29]' : 'text-slate-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition shadow-2xs" :class="newReturnStep >= 2 ? 'bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white shadow-[#a38c29]/30' : 'bg-white text-slate-400 border border-slate-200'">2</div>
                        <div class="flex flex-col">
                            <span class="font-bold text-xs" :class="newReturnStep >= 2 ? 'text-slate-900' : 'text-slate-400'">Cancellation Details</span>
                            <span class="text-[10px] font-normal text-slate-400 hidden sm:inline">Provide cancellation info</span>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <div class="flex items-center gap-2.5" :class="newReturnStep >= 3 ? 'text-[#a38c29]' : 'text-slate-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition shadow-2xs" :class="newReturnStep >= 3 ? 'bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white shadow-[#a38c29]/30' : 'bg-white text-slate-400 border border-slate-200'">3</div>
                        <div class="flex flex-col">
                            <span class="font-bold text-xs" :class="newReturnStep >= 3 ? 'text-slate-900' : 'text-slate-400'">Review & Confirm</span>
                            <span class="text-[10px] font-normal text-slate-400 hidden sm:inline">Confirm and submit</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Area (Scrollable Body) --}}
            <div class="p-6 overflow-y-auto flex-grow space-y-5 bg-white">

                {{-- STEP 1: SELECT SALE --}}
                <div x-show="newReturnStep === 1" class="space-y-4">
                    {{-- Search Sale --}}
                    <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Search Sale</label>
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="search" @focus="open = true" placeholder="Search by Sale ID, Customer Name, Unit / Plot, Project..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                        </div>
                        
                        {{-- Dropdown for search --}}
                        <div x-show="open && search.length > 0" class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100">
                            <template x-for="sale in sales.filter(s => s.status === 'active' && ((s.sale_number||'').toLowerCase().includes(search.toLowerCase()) || (s.customer?.name||'').toLowerCase().includes(search.toLowerCase()) || (s.unit?.door_no||'').toLowerCase().includes(search.toLowerCase())))" :key="sale.id">
                                <div @click="newReturnSaleId = sale.id; open = false; search = ''; selectNewReturnSale();" class="px-4 py-2.5 hover:bg-amber-50/50 cursor-pointer text-xs flex justify-between items-center transition">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                        <div>
                                            <span class="font-extrabold text-slate-800" x-text="sale.sale_number"></span>
                                            <span class="text-slate-500 ml-1 font-medium" x-text="'• ' + (sale.customer ? sale.customer.name : 'N/A')"></span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold" x-text="getUnitsDisplay(sale)"></span>
                                </div>
                            </template>
                            <div x-show="!sales.some(s => s.status === 'active' && ((s.sale_number||'').toLowerCase().includes(search.toLowerCase()) || (s.customer?.name||'').toLowerCase().includes(search.toLowerCase())))" class="px-4 py-3 text-xs text-slate-400 text-center font-medium">No matching active sales found.</div>
                        </div>
                    </div>

                    {{-- Table of Recent Sales --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Recent Sales</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-[10px] text-slate-600 font-semibold">Active Bookings</span>
                            </h4>
                            <span class="text-[10px] font-bold text-[#a38c29]">Click row to select</span>
                        </div>

                        <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-2xs">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="px-3.5 py-3 w-10 text-center"></th>
                                        <th class="px-4 py-3">Sale Details</th>
                                        <th class="px-4 py-3">Customer</th>
                                        <th class="px-4 py-3">Unit / Plot</th>
                                        <th class="px-4 py-3 text-right">Sale Amount</th>
                                        <th class="px-4 py-3 text-right">Booked On</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <template x-for="sale in sales.filter(s => s.status === 'active').slice(0, 6)" :key="sale.id">
                                        <tr class="hover:bg-amber-50/40 cursor-pointer transition-colors" :class="newReturnSaleId == sale.id ? 'bg-amber-50/60' : ''" @click="newReturnSaleId = sale.id; selectNewReturnSale();">
                                            <td class="px-3.5 py-3 text-center">
                                                <div class="w-4 h-4 rounded-full border flex items-center justify-center mx-auto transition" :class="newReturnSaleId == sale.id ? 'border-[#a38c29] bg-[#a38c29]' : 'border-slate-300 bg-white'">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="newReturnSaleId == sale.id"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                                    <span x-text="sale.sale_number"></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-medium ml-3" x-text="(sale.project ? sale.project.code || sale.project.name : '')"></div>
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-slate-700 whitespace-nowrap" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 text-xs font-semibold text-slate-800" x-text="getUnitsDisplay(sale)"></span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap" x-text="fmtIndian(sale.total_amount)"></td>
                                            <td class="px-4 py-3 text-right text-slate-500 font-medium whitespace-nowrap" x-text="formatDate(sale.agreement_date || sale.created_at)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: CANCELLATION DETAILS --}}
                <div x-show="newReturnStep === 2" class="space-y-5">
                    {{-- 2-Column Summary Cards (Sale Information & Cancellation Summary) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        {{-- Left Box: Sale Information --}}
                        <div class="bg-slate-50/70 rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3.5">
                            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-200/70">
                                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider">Sale Information</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-y-3 gap-x-3 text-xs">
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Sale ID</span>
                                    <span class="font-bold text-slate-800 block mt-0.5" x-text="newReturnSale ? newReturnSale.sale_number : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Customer</span>
                                    <span class="font-bold text-slate-800 block mt-0.5 truncate" x-text="newReturnSale && newReturnSale.customer ? newReturnSale.customer.name : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Project</span>
                                    <span class="font-bold text-slate-800 block mt-0.5 truncate" x-text="newReturnSale?.project ? (newReturnSale.project.name || newReturnSale.project.code) : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Unit / Plot</span>
                                    <span class="font-bold text-slate-800 block mt-0.5" x-text="getUnitsDisplay(newReturnSale)"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Sale Amount</span>
                                    <span class="font-bold font-mono text-slate-800 block mt-0.5" x-text="newReturnSale ? fmtIndian(newReturnSale.total_amount) : '₹0.00'"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block">Total Paid</span>
                                    <span class="font-bold font-mono text-emerald-600 block mt-0.5" x-text="newReturnSale ? fmtIndian(getPaidTillDate(newReturnSale)) : '₹0.00'"></span>
                                </div>
                                <div class="col-span-3 pt-0.5">
                                    <span class="text-[11px] font-medium text-slate-400 block">Booked On</span>
                                    <span class="font-bold text-slate-800 block mt-0.5" x-text="newReturnSale ? formatDate(newReturnSale.agreement_date || newReturnSale.created_at) : 'N/A'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Box: Cancellation Summary --}}
                        <div class="bg-amber-50/20 rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 pb-2.5 border-b border-slate-200/70">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <h4 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider">Cancellation Summary</h4>
                                </div>
                                <div class="space-y-2.5 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500 font-medium">Total Paid</span>
                                        <span class="font-bold font-mono text-slate-800" x-text="newReturnSale ? fmtIndian(getPaidTillDate(newReturnSale)) : '₹0.00'"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500 font-medium">Cancellation Fee</span>
                                        <span class="font-bold font-mono text-rose-600" x-text="'- ' + fmtIndian(Number(returnForm.cancellation_fee) || 0)"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3.5 border-t border-slate-200/80 flex items-center justify-between">
                                <span class="text-xs font-bold text-[#a38c29] uppercase tracking-wide">Amount to be Refunded</span>
                                <span class="text-base md:text-lg font-black font-mono text-[#a38c29]" x-text="fmtIndian(calculateApprovedRefund(newReturnSale))"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Cards Grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        {{-- Cancellation Information Card --}}
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Cancellation Information</h4>
                            </div>
                            <div class="grid grid-cols-1 gap-3.5 text-xs">
                                <div>
                                    <label class="block text-slate-600 mb-1 font-semibold">Cancellation Date <span class="text-red-500">*</span></label>
                                    <input type="date" x-model="returnForm.date" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                                    <p x-show="returnFormErrors.date" x-text="returnFormErrors.date" class="text-red-500 text-[10px] mt-1 font-semibold"></p>
                                </div>
                                <div>
                                    <label class="block text-slate-600 mb-1 font-semibold">Cancellation Reason <span class="text-red-500">*</span></label>
                                    <select x-model="returnForm.reason" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                                        <option value="Customer Request">Customer Request</option>
                                        <option value="Financial Issue">Financial Issue</option>
                                        <option value="Legal Issue">Legal Issue</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <p x-show="returnFormErrors.reason" x-text="returnFormErrors.reason" class="text-red-500 text-[10px] mt-1 font-semibold"></p>
                                </div>
                                <div>
                                    <label class="block text-slate-600 mb-1 font-semibold">Detailed Reason / Remarks <span class="text-red-500">*</span></label>
                                    <textarea x-model="returnForm.detailed_reason" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs resize-none" placeholder="Provide detailed reason..."></textarea>
                                    <p x-show="returnFormErrors.detailed_reason" x-text="returnFormErrors.detailed_reason" class="text-red-500 text-[10px] mt-1 font-semibold"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Cancellation Fee & Refund Info Card --}}
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 mb-3.5">
                                    <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Cancellation Fee Deduction</h4>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-slate-600 mb-1 font-semibold text-xs">
                                            <span>Cancellation Fee Amount (₹)</span>
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="1" x-model.number="returnForm.cancellation_fee" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs" placeholder="Enter amount">
                                        <p x-show="returnFormErrors.cancellation_fee" x-text="returnFormErrors.cancellation_fee" class="text-red-500 text-[10px] mt-1 font-semibold"></p>
                                    </div>
                                    <div class="bg-amber-50/80 border border-amber-200/60 text-amber-800 p-3 rounded-xl text-xs flex items-start gap-2.5 shadow-2xs">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[11px] leading-relaxed">This amount will be deducted as cancellation charge.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600">Net Refund Payable:</span>
                                <span class="text-base font-extrabold font-mono text-[#a38c29]" x-text="fmtIndian(calculateApprovedRefund(newReturnSale))"></span>
                            </div>
                        </div>

                        {{-- Refund & Payment Details (Full Width Card) --}}
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs lg:col-span-2 space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Refund & Settlement Details</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                                <div>
                                    <label class="block text-slate-600 mb-1 font-semibold">Refund Amount (₹)</label>
                                    <input type="text" :value="calculateApprovedRefund(newReturnSale).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})" disabled class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl bg-slate-100 text-slate-600 font-mono font-bold shadow-2xs cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-slate-600 mb-1 font-semibold">Refund Mode <span class="text-red-500">*</span></label>
                                    <select x-model="returnForm.refund_mode" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                                        @if(isset($paymentModes) && count($paymentModes) > 0)
                                            @foreach($paymentModes as $pm)
                                                <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Cash">Cash</option>
                                        @endif
                                    </select>
                                    <p x-show="returnFormErrors.refund_mode" x-text="returnFormErrors.refund_mode" class="text-red-500 text-[10px] mt-1 font-semibold"></p>
                                </div>
                                <div x-show="(returnForm.refund_mode || '').toLowerCase() === 'cheque'" x-transition>
                                    <label class="block text-slate-600 mb-1 font-semibold">Reference / Cheque No.</label>
                                    <input type="text" x-model="returnForm.cheque_number" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs" placeholder="Enter Reference / Cheque No.">
                                </div>
                                <div :class="(returnForm.refund_mode || '').toLowerCase() === 'cheque' ? 'md:col-span-3' : 'md:col-span-1'">
                                    <label class="block text-slate-600 mb-1 font-semibold">Refund Remarks</label>
                                    <textarea x-model="returnForm.refund_remarks" rows="1" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- STEP 3: REVIEW & CONFIRM --}}
                <div x-show="newReturnStep === 3" class="space-y-5">
                    {{-- Summary Theme Card --}}
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50/70 via-white to-amber-50/40 border border-[#a38c29]/30 p-5 shadow-md text-slate-900">
                        <div class="absolute -top-14 -right-14 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="flex items-center gap-2 pb-3 border-b border-[#a38c29]/15">
                            <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#a38c29] text-[9px] font-extrabold uppercase tracking-widest border border-[#a38c29]/30">Step 3</span>
                            <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Final Verification & Cancellation Summary</h4>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 pt-4">
                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale ID</span>
                                <span class="text-xs font-extrabold text-slate-800 mt-1 block" x-text="newReturnSale ? newReturnSale.sale_number : ''"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Customer</span>
                                <span class="text-xs font-extrabold text-slate-800 mt-1 block truncate" x-text="newReturnSale && newReturnSale.customer ? newReturnSale.customer.name : ''"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Amount</span>
                                <span class="text-xs font-extrabold font-mono text-slate-800 mt-1 block" x-text="newReturnSale ? fmtIndian(newReturnSale.total_amount) : ''"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Paid</span>
                                <span class="text-xs font-extrabold font-mono text-emerald-600 mt-1 block" x-text="newReturnSale ? fmtIndian(getPaidTillDate(newReturnSale)) : ''"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200/60 shadow-2xs">
                                <span class="text-[9px] font-bold text-rose-600 uppercase tracking-widest block">Cancellation Fee</span>
                                <span class="text-xs font-extrabold font-mono text-rose-700 mt-1 block" x-text="'- ' + fmtIndian(Number(returnForm.cancellation_fee) || 0)"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-[#a38c29]/15 border border-[#a38c29]/40 shadow-2xs">
                                <span class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-widest block">Approved Refund</span>
                                <span class="text-xs font-black font-mono text-slate-900 mt-1 block" x-text="fmtIndian(calculateApprovedRefund(newReturnSale))"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Details Box --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider pb-2 border-b border-slate-100">Settlement Info</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs" :class="(returnForm.refund_mode || '').toLowerCase() === 'cheque' ? 'md:grid-cols-4' : 'md:grid-cols-3'">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Reason</span>
                                <span class="font-bold text-slate-800 mt-0.5 block" x-text="returnForm.reason"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Refund Mode</span>
                                <span class="font-bold text-slate-800 mt-0.5 block" x-text="returnForm.refund_mode"></span>
                            </div>
                            <div x-show="(returnForm.refund_mode || '').toLowerCase() === 'cheque'" class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Reference / Cheque No.</span>
                                <span class="font-bold text-slate-800 mt-0.5 block font-mono" x-text="returnForm.cheque_number || '—'"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Detailed Remarks</span>
                                <span class="font-semibold text-slate-700 mt-0.5 block" x-text="returnForm.detailed_reason"></span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Confirmation --}}
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200/80">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" id="confirmCancelCheck" class="w-4 h-4 text-[#a38c29] rounded border-slate-300 focus:ring-[#a38c29]/50">
                            <span class="text-xs text-slate-700 font-semibold">I confirm that the above cancellation information is verified and correct.</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Modal Footer (Docked Bottom Bar matching Image 2 style) --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50 flex-shrink-0">
                <template x-if="newReturnStep === 1">
                    <button type="button" @click="openNewReturnModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
                </template>
                <template x-if="newReturnStep > 1">
                    <button type="button" @click="newReturnStep--" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Back</button>
                </template>

                <div class="ml-auto">
                    <template x-if="newReturnStep === 1">
                        <button type="button" @click="if(newReturnSale) newReturnStep = 2; else showToast('Please select a sale first', 'error');" class="px-6 py-2.5 bg-gradient-to-br from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73621b] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md shadow-[#a38c29]/25 flex items-center gap-1.5">
                            <span>Next Step</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </template>
                    <template x-if="newReturnStep === 2">
                        <button type="button" @click="
                            returnFormErrors = {};
                            if (!returnForm.date) returnFormErrors.date = 'Date is required.';
                            if (!returnForm.reason) returnFormErrors.reason = 'Reason is required.';
                            if (!returnForm.detailed_reason) returnFormErrors.detailed_reason = 'Detailed reason is required.';
                            if (returnForm.cancellation_fee === '' || returnForm.cancellation_fee === null) returnFormErrors.cancellation_fee = 'Cancellation fee is required.';
                            if (!returnForm.refund_mode) returnFormErrors.refund_mode = 'Refund mode is required.';
                            if (Object.keys(returnFormErrors).length === 0) newReturnStep = 3;
                        " class="px-6 py-2.5 bg-gradient-to-br from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73621b] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md shadow-[#a38c29]/25 flex items-center gap-1.5">
                            <span>Next: Review</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </template>
                    <template x-if="newReturnStep === 3">
                        <button type="button" @click="if(!document.getElementById('confirmCancelCheck').checked) showToast('Please check the confirmation box.', 'error'); else submitNewReturn();" class="px-6 py-2.5 bg-gradient-to-br from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73621b] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md shadow-[#a38c29]/25 flex items-center gap-1.5">
                            <span>Confirm Cancellation</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
    </template>
    @endif


    {{-- RIGHT COLUMN: UNIT-TO-UNIT EXCHANGE PLAN --}}
    @if(request('tab') === 'exchange')
    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-5">
        <!-- Card 1: Total Exchanges -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-purple-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(168,85,247,0.2)] hover:border-r-purple-500/20 hover:border-y-purple-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Exchanges</span>
                <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 border border-purple-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getExchangeStats().totalExchanges">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 2: Total Difference Amount -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Difference Amount</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().totalDiff)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 3: Payable by Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.2)] hover:border-r-amber-500/20 hover:border-y-amber-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Payable by Customer</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().payableByCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 4: Refundable to Customer -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-teal-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(20,184,166,0.2)] hover:border-r-teal-500/20 hover:border-y-teal-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Refundable to Customer</span>
                <div class="w-6 h-6 rounded-md bg-teal-50 text-teal-600 border border-teal-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-teal-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="fmtIndian(getExchangeStats().refundableToCustomer)">
                ₹0.00
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>

        <!-- Card 5: Completed Exchanges -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)] hover:border-r-blue-500/20 hover:border-y-blue-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Completed Exchanges</span>
                <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900" x-text="getExchangeStats().completedExchanges">
                0
            </div>
            <div class="text-[10px] font-medium text-slate-400">This Month</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-5">
        {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Customer/Unit..." 
                           x-model="exchangeFilters.search"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="exchangeFilters.search" @click="exchangeFilters.search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                    </div>
                    <select x-model="exchangeFilters.project_id"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Type Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <select x-model="exchangeFilters.type"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Types</option>
                        <option value="Flat">Flat</option>
                        <option value="Shop">Shop</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="exchangeFilters.status"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="exchanged">Exchanged</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            {{-- Reset Filters Button --}}
            <button @click="exchangeFilters.search = ''; exchangeFilters.project_id = ''; exchangeFilters.type = ''; exchangeFilters.status = '';"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
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
                                <td class="px-3 py-2.5 text-left bg-slate-100/20 border-r border-slate-100" x-text="sale.unit ? formatUnitDisplay(sale.unit) : '—'"></td>
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

    {{-- INITIATE NEW EXCHANGE MODAL POPUP (Matching Return Sale style & Image) --}}
    <template x-teleport="body">
    <div x-show="openNewExchangeModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-cloak x-transition.opacity>
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up" @click.away="openNewExchangeModal = false">
            {{-- Header (Matching Image) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 flex items-center justify-center text-[#d9bf3b] shadow-inner shadow-[#a38c29]/30 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">Unit Exchange</h2>
                            <p class="text-[11px] text-slate-300 mt-0.5 font-normal">Search and select the sale to be exchanged and configure new unit details.</p>
                        </div>
                    </div>
                    <button type="button" @click="openNewExchangeModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            {{-- Stepper --}}
            <div class="bg-slate-50/80 px-6 py-3 border-b border-slate-200/80 flex justify-center flex-shrink-0">
                <div class="flex items-center gap-4 sm:gap-8 text-xs font-semibold">
                    <div class="flex items-center gap-2.5" :class="newExchangeStep >= 1 ? 'text-[#a38c29]' : 'text-slate-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition shadow-2xs" :class="newExchangeStep >= 1 ? 'bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white shadow-[#a38c29]/30' : 'bg-white text-slate-400 border border-slate-200'">1</div>
                        <div class="flex flex-col">
                            <span class="font-bold text-xs" :class="newExchangeStep >= 1 ? 'text-slate-900' : 'text-slate-400'">Select Sale</span>
                            <span class="text-[10px] font-normal text-slate-400 hidden sm:inline">Search and choose sale</span>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <div class="flex items-center gap-2.5" :class="newExchangeStep >= 2 ? 'text-[#a38c29]' : 'text-slate-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition shadow-2xs" :class="newExchangeStep >= 2 ? 'bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white shadow-[#a38c29]/30' : 'bg-white text-slate-400 border border-slate-200'">2</div>
                        <div class="flex flex-col">
                            <span class="font-bold text-xs" :class="newExchangeStep >= 2 ? 'text-slate-900' : 'text-slate-400'">Exchange Details</span>
                            <span class="text-[10px] font-normal text-slate-400 hidden sm:inline">Configure target unit & payment</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="p-6 overflow-y-auto flex-1 min-h-0 space-y-5 bg-white" x-ref="newExchangeModalScroll">
                
                {{-- STEP 1: SELECT SALE --}}
                <div x-show="newExchangeStep === 1" class="space-y-4">
                    {{-- Search Sale --}}
                    <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }" x-init="$watch('openNewExchangeModal', val => { if(!val) { search = ''; open = false; newExchangeStep = 1; newExchangeSaleId = ''; selectedExchangeSale = null; } else { $nextTick(() => { if ($refs.newExchangeModalScroll) $refs.newExchangeModalScroll.scrollTop = 0; }); } }); $watch('newExchangeStep', val => { $nextTick(() => { if ($refs.newExchangeModalScroll) $refs.newExchangeModalScroll.scrollTop = 0; }); });" @click.outside="open = false">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Search Sale</label>
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="search"
                                   :value="selectedExchangeSale ? (selectedExchangeSale.sale_number + ' - ' + (selectedExchangeSale.customer ? selectedExchangeSale.customer.name : '')) : search"
                                   @focus="open = true" @input="if(newExchangeSaleId && $event.isTrusted) { newExchangeSaleId = ''; selectedExchangeSale = null; newExchangeStep = 1; }" placeholder="Search by Sale ID, Customer Name, Unit / Plot, Project..." class="w-full pl-10 pr-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                            
                            {{-- Clear Selection Button --}}
                            <button type="button" x-show="newExchangeSaleId" @click="newExchangeSaleId = ''; selectedExchangeSale = null; newExchangeStep = 1; search = ''; $el.previousElementSibling.focus();" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        {{-- Dropdown for search --}}
                        <div x-show="open && search.length > 0 && !newExchangeSaleId" class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100">
                            <template x-for="sale in sales.filter(s => (s.status === 'active' || s.status === 'cancelled') && isSaleExchangeEligible(s) && ((s.sale_number||'').toLowerCase().includes(search.toLowerCase()) || (s.customer?.name||'').toLowerCase().includes(search.toLowerCase()) || (s.unit?.door_no||'').toLowerCase().includes(search.toLowerCase())))" :key="sale.id">
                                <div @click="newExchangeSaleId = sale.id; search = sale.sale_number + ' - ' + (sale.customer ? sale.customer.name : ''); open = false; selectExchangeSale(sale); newExchangeStep = 2;" class="px-4 py-2.5 hover:bg-amber-50/50 cursor-pointer text-xs flex justify-between items-center transition">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                        <div>
                                            <span class="font-extrabold text-slate-800" x-text="sale.sale_number"></span>
                                            <span class="text-slate-500 ml-1 font-medium" x-text="'• ' + (sale.customer ? sale.customer.name : 'N/A')"></span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold" x-text="getUnitsDisplay(sale)"></span>
                                </div>
                            </template>
                            <div x-show="!sales.some(s => (s.status === 'active' || s.status === 'cancelled') && isSaleExchangeEligible(s) && ((s.sale_number||'').toLowerCase().includes(search.toLowerCase()) || (s.customer?.name||'').toLowerCase().includes(search.toLowerCase())))" class="px-4 py-3 text-xs text-slate-400 text-center font-medium">No matching exchangeable sales found.</div>
                        </div>
                    </div>

                    {{-- Recent Sales Table --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Recent Sales</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-[10px] text-slate-600 font-semibold">Active Bookings</span>
                            </h4>
                            <span class="text-[10px] font-bold text-[#a38c29]">Click row to select</span>
                        </div>

                        <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-2xs">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="px-3.5 py-3 w-10 text-center"></th>
                                        <th class="px-4 py-3">Sale Details</th>
                                        <th class="px-4 py-3">Customer</th>
                                        <th class="px-4 py-3">Unit / Plot</th>
                                        <th class="px-4 py-3 text-right">Sale Amount</th>
                                        <th class="px-4 py-3 text-right">Booked On</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <template x-for="sale in sales.filter(s => (s.status === 'active' || s.status === 'cancelled') && isSaleExchangeEligible(s)).slice(0, 10)" :key="sale.id">
                                        <tr class="hover:bg-amber-50/40 cursor-pointer transition-colors"
                                            :class="newExchangeSaleId == sale.id ? 'bg-amber-50/60' : ''"
                                            @click="newExchangeSaleId = sale.id; search = sale.sale_number + ' - ' + (sale.customer ? sale.customer.name : ''); selectExchangeSale(sale); newExchangeStep = 2;">
                                            <td class="px-3.5 py-3 text-center">
                                                <div class="w-4 h-4 rounded-full border flex items-center justify-center mx-auto transition" :class="newExchangeSaleId == sale.id ? 'border-[#a38c29] bg-[#a38c29]' : 'border-slate-300 bg-white'">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="newExchangeSaleId == sale.id"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                                    <span x-text="sale.sale_number"></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-medium ml-3" x-text="(sale.project ? sale.project.code || sale.project.name : '')"></div>
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-slate-700 whitespace-nowrap" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 text-xs font-semibold text-slate-800" x-text="getUnitsDisplay(sale)"></span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap" x-text="fmtIndian(sale.total_amount)"></td>
                                            <td class="px-4 py-3 text-right text-slate-500 font-mono text-xs whitespace-nowrap" x-text="formatDate(sale.agreement_date || sale.created_at)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: EXCHANGE CONFIGURATION DETAILS --}}
                <div x-show="newExchangeStep === 2" class="space-y-5">
                <template x-if="selectedExchangeSale">
                    <div class="space-y-6 animate-fade-in-up">
                        {{-- Old Unit Details --}}
                        <div class="border border-slate-200 rounded-xl p-5 space-y-3">
                            <h3 class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                                Unit Exchange Details
                            </h3>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <div>
                                    <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        Old Unit(s):
                                    </div>
                                    <div class="text-xs font-bold text-slate-800 space-y-1 pl-5">
                                        <template x-if="selectedExchangeSale && selectedExchangeSale.sale_units && selectedExchangeSale.sale_units.length > 0">
                                            <template x-for="su in selectedExchangeSale.sale_units" :key="su.id">
                                                <div>
                                                    <span x-text="su.unit ? su.unit.door_no : ''"></span> 
                                                    <span class="text-slate-500 font-normal" x-text="su.unit && su.unit.unit_type ? '(' + su.unit.unit_type.name + ')' : ''"></span> — 
                                                    <span class="text-slate-500 font-normal" x-text="su.unit && su.unit.floor ? su.unit.floor.name : ''"></span>
                                                </div>
                                            </template>
                                        </template>
                                        <template x-if="selectedExchangeSale && (!selectedExchangeSale.sale_units || selectedExchangeSale.sale_units.length === 0)">
                                            <div>
                                                <span x-text="selectedExchangeSale.unit ? selectedExchangeSale.unit.door_no : ''"></span> 
                                                <span class="text-slate-500 font-normal" x-text="selectedExchangeSale.unit && selectedExchangeSale.unit.unit_type ? '(' + selectedExchangeSale.unit.unit_type.name + ')' : ''"></span> — 
                                                <span class="text-slate-500 font-normal" x-text="selectedExchangeSale.unit && selectedExchangeSale.unit.floor ? selectedExchangeSale.unit.floor.name : ''"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-200/80">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Old Contract Value</span>
                                        <div class="text-base font-extrabold text-slate-800 font-mono" x-text="selectedExchangeSale ? fmtIndian(selectedExchangeSale.total_amount || selectedExchangeSale.sale_amount) : '₹0.00'"></div>
                                    </div>
                                    <div class="space-y-1 sm:border-l sm:border-slate-200/80 sm:pl-5">
                                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Paid Amount</span>
                                        <div class="text-base font-extrabold text-emerald-600 font-mono" x-text="selectedExchangeSale ? fmtIndian(getPaidTillDate(selectedExchangeSale)) : '₹0.00'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target Unit Selections --}}
                        <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                            <h3 class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                                Target Unit Selections
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    <div class="space-y-1 sm:col-span-4 lg:col-span-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Target Project *</label>
                                        <div class="relative">
                                            <select x-model="exchangeForm.new_project_id" @change="loadExchangeUnits()"
                                                    :class="errors.new_project_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                                    class="w-full pl-3 pr-8 py-2.5 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                                <option value="">Select Project...</option>
                                                @foreach($projects as $proj)
                                                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                        <template x-if="errors.new_project_id">
                                            <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.new_project_id) ? errors.new_project_id[0] : errors.new_project_id"></p>
                                        </template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Unit *</label>
                                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                            <div @click="if (exchangeForm.new_project_id) { open = !open; if (open) $nextTick(() => $refs.targetUnitSearchInput?.focus()); }" 
                                                 :class="!exchangeForm.new_project_id ? 'opacity-50 cursor-not-allowed bg-slate-100' : (errors.new_unit_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'cursor-pointer bg-slate-50 hover:bg-white border-slate-250')"
                                                 class="w-full pl-3 pr-8 py-2 border rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs flex items-center min-h-[42px]">
                                                
                                                <template x-if="exchangeForm.new_unit_id && exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id)">
                                                    <div class="flex items-center gap-1.5 w-full">
                                                        <span class="font-extrabold text-slate-800 truncate" x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).door_no"></span>
                                                        <span class="text-[10px] text-slate-400 font-mono" x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name ? '(' + exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).floor_name + ')' : ''"></span>
                                                        <span class="text-[10px] text-slate-500 font-medium truncate ml-auto" x-text="exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id).unit_type_name || 'Unit'"></span>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="!exchangeForm.new_unit_id || !exchangeAvailableUnits.find(u => u.id == exchangeForm.new_unit_id)">
                                                    <span class="text-slate-400 font-medium truncate">— Select Unit —</span>
                                                </template>
                                            </div>

                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1">
                                                <template x-if="exchangeForm.new_unit_id">
                                                    <button type="button" @click.stop="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition z-10" title="Clear target unit">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </template>
                                                <svg class="w-4 h-4 text-slate-400 pointer-events-none" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>

                                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                                 class="absolute z-[100] w-[120%] lg:w-full min-w-[280px] mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden flex flex-col max-h-64">
                                                <div class="p-2 border-b border-slate-100 bg-slate-50/50 sticky top-0 z-10 shrink-0">
                                                    <div class="relative">
                                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                        <input type="text" x-model="search" x-ref="targetUnitSearchInput" placeholder="Search door, floor..." class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs shadow-sm font-medium">
                                                    </div>
                                                </div>
                                                <div class="overflow-y-auto flex-1 p-1 space-y-1">
                                                    <!-- Clear Option -->
                                                    <button type="button" @click="exchangeForm.new_unit_id = ''; onExchangeUnitSelect(); open = false; search = ''"
                                                            class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition">
                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Clear Selected Unit</span>
                                                    </button>

                                                    <template x-for="unit in exchangeAvailableUnits.filter(u => search === '' || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase())))" :key="unit.id">
                                                        <button type="button"
                                                                @click="exchangeForm.new_unit_id = unit.id; onExchangeUnitSelect(); open = false; search = ''"
                                                                :class="exchangeForm.new_unit_id == unit.id ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#a38c29] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                                class="w-full px-3 py-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                                            <div class="flex items-center gap-2 min-w-0">
                                                                <span class="font-extrabold text-xs truncate" :class="exchangeForm.new_unit_id == unit.id ? 'text-[#a38c29]' : 'text-slate-800'" x-text="unit.door_no || unit.unit_no || unit.name || '—'"></span>
                                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0"
                                                                      x-text="unit.unit_type_name || 'Unit'"></span>
                                                            </div>
                                                            <div class="flex items-center gap-2 shrink-0">
                                                                <span class="text-xs font-bold text-slate-300 font-mono" x-text="unit.floor_name"></span>
                                                                <template x-if="exchangeForm.new_unit_id == unit.id">
                                                                    <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                </template>
                                                            </div>
                                                        </button>
                                                    </template>
                                                    <template x-if="exchangeAvailableUnits.filter(u => search === '' || (u.door_no && u.door_no.toLowerCase().includes(search.toLowerCase())) || (u.floor_name && u.floor_name.toLowerCase().includes(search.toLowerCase())) || (u.unit_type_name && u.unit_type_name.toLowerCase().includes(search.toLowerCase()))).length === 0">
                                                        <div class="px-3 py-4 text-center text-xs text-slate-400 italic">No matching units found</div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <template x-if="errors.new_unit_id">
                                            <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.new_unit_id) ? errors.new_unit_id[0] : errors.new_unit_id"></p>
                                        </template>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Built Up Area (Sq Ft)</label>
                                        <input type="text" :value="exchangeForm.built_up_area ? exchangeForm.built_up_area + ' Sq Ft' : '— Sq Ft'" disabled class="w-full px-3 py-2.5 bg-[#eae9e6] border border-slate-300 rounded-xl text-xs text-slate-500 font-bold font-mono">
                                    </div>
                                    <div x-show="!isTargetUnitParking()" class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Expected Rate/SqFt *</label>
                                        <input type="text" inputmode="decimal" name="expected_rate_per_sqft" x-model="exchangeForm.expected_rate_per_sqft" @input="calculateExchangeAmounts('rate')" placeholder="Expected rate" class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                    </div>
                                    <div x-show="!isTargetUnitParking()" class="space-y-1 relative">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Sale Rate/SqFt *</label>
                                        <input type="text" inputmode="decimal" name="sale_rate_per_sqft" x-model="exchangeForm.sale_rate_per_sqft" @input="calculateExchangeAmounts('rate')" placeholder="Sale rate"
                                               :class="errors.sale_rate ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white'"
                                               class="w-full px-3 py-2.5 border rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                        <template x-if="errors.sale_rate">
                                            <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.sale_rate) ? errors.sale_rate[0] : errors.sale_rate"></p>
                                        </template>
                                        <div class="text-xs sm:text-sm font-extrabold font-mono flex items-center gap-1.5 pt-0.5"
                                             :class="getExchangeDifference() > 0 ? 'text-emerald-600' : (getExchangeDifference() < 0 ? 'text-rose-600' : 'text-slate-500')">
                                            <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Diff:</span>
                                            <span x-text="(getExchangeDifference() >= 0 ? '₹' : '-₹') + Math.abs(getExchangeDifference()).toLocaleString()"></span>
                                        </div>
                                    </div>
                                    <div x-show="isTargetUnitParking()" class="space-y-1 sm:col-span-2">
                                        <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Expected Sale Amount (Parking) *</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold font-mono">₹</span>
                                            <input type="number" step="0.01" x-model.number="exchangeForm.expected_sale_amount" @input="exchangeForm.agreed_sale_amount = exchangeForm.expected_sale_amount; calculateExchangeAmounts('agreed_amount')" placeholder="Enter parking expected sale amount"
                                                   class="w-full pl-6 pr-3 py-2.5 bg-amber-50/30 border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 pt-4 border-t border-slate-100">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Agreed Sale Amount *</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold font-mono">₹</span>
                                            <input type="text" inputmode="decimal" x-model="exchangeForm.agreed_sale_amount" @input="calculateExchangeAmounts('agreed_amount')"
                                                   :class="errors.agreed_sale_amount ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white'"
                                                   class="w-full pl-6 pr-3 py-2.5 border rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                        </div>
                                        <template x-if="errors.agreed_sale_amount">
                                            <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.agreed_sale_amount) ? errors.agreed_sale_amount[0] : errors.agreed_sale_amount"></p>
                                        </template>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">GST Type</label>
                                        <select x-model="exchangeForm.gst_type" @change="calculateExchangeAmounts('gst_type')"
                                                class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold shadow-sm">
                                            <option value="none">None</option>
                                            <option value="exclusive">Exclusive</option>
                                            <option value="inclusive">Inclusive</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">GST Percentage (%)</label>
                                        <input type="text" inputmode="decimal" x-model="exchangeForm.gst_percentage" @input="calculateExchangeAmounts('gst_percentage')" placeholder="e.g. 18" class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">GST Amount (₹)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold font-mono">₹</span>
                                            <input type="text" inputmode="decimal" x-model="exchangeForm.gst_amount" @input="calculateExchangeAmounts('gst_amount')" class="w-full pl-6 pr-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold font-mono shadow-sm">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Total Payable</label>
                                        <div class="text-lg sm:text-xl font-black text-indigo-700 font-mono py-1.5" x-text="'₹' + Number(exchangeForm.new_unit_value || 0).toLocaleString()"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contract Values --}}
                        <div class="border border-slate-200 rounded-xl p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Old Contract Value</label>
                                    <input type="text" :value="fmt(selectedExchangeSale.total_amount)" disabled
                                           class="w-full px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-xl text-xs text-slate-600 font-bold font-mono">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">New Contract Value</label>
                                    <input type="text" :value="fmt(exchangeForm.new_unit_value)" disabled
                                           class="w-full px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-xl text-xs text-slate-600 font-bold font-mono">
                                </div>
                            </div>
                        </div>


                        {{-- Initial Payment Details --}}
                        <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                            <h3 class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-full bg-[#a38c29]/20 text-[#a38c29] flex items-center justify-center font-bold font-mono text-[9px] border border-[#a38c29]/40">₹</div>
                                Initial Payment Details
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Initial Payment Amount & %</label>
                                    <div class="flex gap-2">
                                        <input type="number" x-model.number="exchangeForm.initial_payment_amount" @input="updateExchangeInitialPaymentFromAmount()"
                                               :class="errors.initial_payment_amount ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white'"
                                               class="w-1/2 px-3 py-2.5 border rounded-xl text-xs font-bold text-slate-800 transition-all">
                                        <input type="number" x-model.number="exchangeForm.initial_payment_percentage" @input="updateExchangeInitialPaymentFromPercentage()" placeholder="Percentage (%)"
                                               class="w-1/2 px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 transition-all">
                                    </div>
                                    <p class="text-[9px] text-slate-400 mt-1">Enter amount or percentage (0 if none)</p>
                                    <template x-if="errors.initial_payment_amount">
                                        <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.initial_payment_amount) ? errors.initial_payment_amount[0] : errors.initial_payment_amount"></p>
                                    </template>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Mode</label>
                                    <div class="relative">
                                        <select x-model="exchangeForm.payment_mode"
                                                class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                            @if(isset($paymentModes) && count($paymentModes) > 0)
                                                @foreach($paymentModes as $pm)
                                                    <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="Cash">Cash</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Demand Draft">Demand Draft</option>
                                            @endif
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Date</label>
                                    <input type="date" x-model="exchangeForm.initial_payment_date"
                                           class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 transition-all">
                                </div>
                                <div x-show="exchangeForm.payment_mode && exchangeForm.payment_mode !== 'Cash'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100 sm:col-span-3" x-transition>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block" x-text="exchangeForm.payment_mode.toLowerCase().includes('upi') ? 'UPI Number / Transaction ID' : 'Reference / Cheque No'"></label>
                                        <input type="text" x-model="exchangeForm.reference_no" :placeholder="exchangeForm.payment_mode.toLowerCase().includes('upi') ? 'Enter UPI Number or Ref ID' : 'e.g. UTR / Cheque number'"
                                               class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 shadow-sm">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Bank Name</label>
                                        <div class="relative">
                                            <select x-model="exchangeForm.bank_id"
                                                    class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                                <option value="">Select Bank Account</option>
                                                @if(isset($bankAccounts))
                                                    @foreach($bankAccounts as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Plan Configuration --}}
                        <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Payment Plan & Schedule Configuration
                                </h3>
                                <span class="px-2 py-0.5 rounded bg-[#f5ecd8] text-[#a38c29] text-[9px] font-extrabold uppercase tracking-widest" x-text="exchangeForm.payment_plan === 'emi' ? 'EMI Schedule Active' : 'Lump-sum Active'"></span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Structure *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center justify-center py-2.5 rounded-xl border cursor-pointer transition text-xs font-bold text-center"
                                               :class="exchangeForm.payment_plan === 'lump_sum' ? 'bg-[#fdfcf6] border-[#a38c29] text-[#a38c29]' : 'border-slate-300 text-slate-600 hover:bg-slate-50'">
                                            <input type="radio" value="lump_sum" x-model="exchangeForm.payment_plan" class="sr-only">
                                            Full / Lump-sum
                                        </label>
                                        <label class="flex items-center justify-center py-2.5 rounded-xl border cursor-pointer transition text-xs font-bold text-center"
                                               :class="exchangeForm.payment_plan === 'emi' ? 'bg-[#fdfcf6] border-[#a38c29] text-[#a38c29]' : 'border-slate-300 text-slate-600 hover:bg-slate-50'">
                                            <input type="radio" value="emi" x-model="exchangeForm.payment_plan" class="sr-only">
                                            EMI / Installments
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1" x-show="exchangeForm.payment_plan === 'emi'">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">First Installment Date *</label>
                                    <input type="date" x-model="exchangeForm.first_installment_date"
                                           :class="errors.first_installment_date ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white'"
                                           class="w-full px-3 py-2.5 border rounded-xl text-xs font-bold text-slate-800 transition-all">
                                    <template x-if="errors.first_installment_date">
                                        <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.first_installment_date) ? errors.first_installment_date[0] : errors.first_installment_date"></p>
                                    </template>
                                </div>
                                <div class="space-y-1" x-show="exchangeForm.payment_plan === 'emi'">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">No. of Installments *</label>
                                    <input type="number" x-model.number="exchangeForm.emi_installment_count" min="1"
                                           class="w-full px-3 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 transition-all">
                                </div>
                                <div class="space-y-1" x-show="exchangeForm.payment_plan === 'emi'">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Frequency *</label>
                                    <div class="relative">
                                        <select x-model="exchangeForm.emi_frequency"
                                                class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Live Schedule Preview --}}
                                <div x-show="exchangeForm.payment_plan === 'emi' && calculateDifferentialDue() > 0" class="bg-indigo-50/30 border border-indigo-100/50 rounded-xl p-3 space-y-2 sm:col-span-2 mt-2">
                                    <p class="text-[9px] font-bold text-indigo-800 uppercase tracking-widest flex items-center gap-1.5">
                                        <span>📝</span>
                                        <span>Live Schedule Preview</span>
                                    </p>
                                    <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-[11px] font-semibold text-slate-700">
                                        <template x-for="(preview, pIdx) in getExchangeEmiPreview()" :key="pIdx">
                                            <div class="flex justify-between items-center py-1 border-b border-indigo-100/30">
                                                <span x-text="preview.label"></span>
                                                <div class="flex gap-4">
                                                    <span class="text-slate-400 font-mono text-[10px]" x-text="preview.due_date"></span>
                                                    <span class="font-bold text-indigo-700 font-mono" x-text="'₹' + Number(preview.amount).toLocaleString()"></span>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="calculateDifferentialDue() <= 0">
                                            <div class="bg-emerald-50 border border-emerald-200/80 rounded-lg p-2.5 text-center">
                                                <p class="text-[10px] text-emerald-700 font-extrabold flex items-center justify-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Customer paid equity (₹<span x-text="Number(exchangeForm.equity_applied || 0).toLocaleString()"></span>) fully covers contract value. No EMI balance due (₹0.00).</span>
                                                </p>
                                            </div>
                                        </template>
                                        <template x-if="calculateDifferentialDue() > 0 && getExchangeEmiPreview().length === 0">
                                            <p class="text-[10px] text-slate-400 italic py-1 text-center">No schedule preview available. Fill EMI parameters.</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Exchange Reason --}}
                        <div class="border border-slate-200 rounded-xl p-5 space-y-2">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Exchange Reason / Notes *
                            </label>
                            <textarea x-model="exchangeForm.reason" rows="2" placeholder="Write internal memo for the unit exchange..."
                                      :class="errors.reason ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-400 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white'"
                                      class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-800 transition-all resize-none"></textarea>
                            <template x-if="errors.reason">
                                <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.reason) ? errors.reason[0] : errors.reason"></p>
                            </template>
                        </div>
                    </div>
                </template>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50 flex-shrink-0">
                <button type="button" x-show="newExchangeStep === 1" @click="openNewExchangeModal = false"
                        class="px-5 py-2 border border-slate-250 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                    Close
                </button>
                <button type="button" x-show="newExchangeStep === 2" @click="newExchangeStep = 1"
                        class="px-5 py-2 border border-slate-250 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                    Back
                </button>
                <div x-show="newExchangeStep === 2" class="flex items-center gap-2">
                    <button type="button" @click="openNewExchangeModal = false"
                            class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="button" @click="submitExchangePlan()" :disabled="!selectedExchangeSale"
                            class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                        Finalize Exchange & New EMI
                    </button>
                </div>
            </div>
        </div>
    </div>
    </template>

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
                                <p class="text-slate-500">Unit details: <span class="text-slate-850 font-bold" x-text="viewExchangeSale && viewExchangeSale.unit ? formatUnitDisplay(viewExchangeSale.unit) : '—'"></span></p>
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
                                    <span x-text="openTables ? 'Hide Detailed Receipts & EMI Schedule History' : 'ðŸ“œ Expand Full Receipts & EMI Schedule Audit Log'"></span>
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

