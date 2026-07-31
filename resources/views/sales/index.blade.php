@php
    $isReturnExchange = request('tab') === 'returns' || request('tab') === 'cancellations' || request('tab') === 'exchange' || request('tab') === 'sale-return';
    $pageTitle = request('tab') === 'exchange' ? 'Unit Exchange Operations' : (request('tab') === 'cancellations' ? 'Sales Cancellations' : (request('tab') === 'sale-return' ? 'Sales Return / Cancellation' : ($isReturnExchange ? 'Sales Returns' : 'Sales Register')));
@endphp
<x-erp-layout :title="$pageTitle" :headerTitle="$pageTitle">
<div class="max-w-[1800px] mx-auto space-y-6" x-data="salesApp()">
    {{-- Toast --}}
    <div x-show="toast.open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-[100] p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>
    @if($isReturnExchange)
        @include('sales.return_exchange_operations')
    @else
    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 flex-1">
            {{-- Pro Search Input --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Sale No / Customer..." 
                       x-model="filters.search" @input.debounce.300ms="fetchSales()"
                       class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                    <button type="button" x-show="filters.search" @click="filters.search = ''; fetchSales()"
                            class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Project Filter --}}
            <div class="relative">
                <select x-model="filters.project_id" @change="fetchSales()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <select x-model="filters.status" @change="fetchSales()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="returned">Returned</option>
                    <option value="exchanged">Exchanged</option>
                    <option value="resale">Resale</option>
                </select>
            </div>

            {{-- Date From --}}
            <input type="date" x-model="filters.date_from" @change="fetchSales()"
                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">

            {{-- Date To --}}
            <input type="date" x-model="filters.date_to" @change="fetchSales()"
                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
            <button @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-slate-900/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider">
                <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Sale
            </button>
        </div>
    </div>
    {{-- Sales Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Sale No</th>
                        <th class="px-4 py-3.5 text-left text-white font-extrabold border-b border-[#8a7522]">Project / Unit</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Customer</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Broker</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Sale Amount</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">GST</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Total</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Sale Date</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522]">Status</th>
                        <th class="px-4 py-3.5 text-white font-extrabold border-b border-[#8a7522] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <template x-for="sale in paginatedSales()" :key="sale.id">
                        <tr class="hover:bg-slate-50/50 transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-4 py-4 font-bold border-b border-slate-100">
                                <a href="#" @click.prevent="openViewModal(sale.id)" class="text-[#09876B] hover:text-[#076852] hover:underline transition-colors cursor-pointer" x-text="sale.sale_number"></a>
                            </td>
                            <td class="px-4 py-4 text-left border-b border-slate-100">
                                <div class="font-bold text-slate-800" x-text="sale.project ? sale.project.name : 'N/A'"></div>
                                <div class="text-[10px] text-slate-400 mt-0.5" x-text="sale.sale_units && sale.sale_units.length ? sale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (sale.unit ? sale.unit.door_no : '')"></div>
                            </td>
                            <td class="px-4 py-4 text-slate-600 border-b border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                            <td class="px-4 py-4 text-slate-500 border-b border-slate-100" x-text="sale.broker ? sale.broker.name : '—'"></td>
                            <td class="px-4 py-4 font-bold text-slate-900 border-b border-slate-100" x-text="'₹' + Number(sale.sale_amount).toLocaleString()"></td>
                            <td class="px-4 py-4 border-b border-slate-100">
                                <span x-show="sale.gst_amount > 0" x-text="'₹' + Number(sale.gst_amount).toLocaleString()"></span>
                                <span x-show="!sale.gst_amount || sale.gst_amount == 0" class="text-slate-400">N/A</span>
                            </td>
                            <td class="px-4 py-4 font-bold text-emerald-700 border-b border-slate-100" x-text="'₹' + Number(sale.total_amount).toLocaleString()"></td>
                            <td class="px-4 py-4 text-slate-500 border-b border-slate-100" x-text="formatDate(sale.sale_date)"></td>
                            <td class="px-4 py-4 border-b border-slate-100">
                                <span class="badge-pill" :class="getStatusBadgeClass(sale.status)" x-text="sale.status"></span>
                            </td>
                            <td class="px-4 py-4 text-right border-b border-slate-100">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a :href="`{{ url('emi-collections/ledger') }}/${sale.id}`" class="p-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 hover:text-indigo-800 transition inline-flex items-center justify-center shadow-sm" title="EMI & Collection Ledger">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <button @click="openViewModal(sale.id)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Sale">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button x-show="(sale.status || '').toLowerCase() !== 'cancelled'" @click="openEditModal(sale.id)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Sale">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="sales.length === 0">
                        <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">No sales records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Sales Register Table Pagination --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl" x-show="sales.length > 0">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900" x-text="(currentPage - 1) * perPage + 1"></span> TO 
                <span class="text-slate-900" x-text="Math.min(currentPage * perPage, sales.length)"></span> OF 
                <span class="text-slate-900" x-text="sales.length"></span> SALES
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="if(currentPage > 1) currentPage--" 
                        :disabled="currentPage <= 1"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                    PREV
                </button>
                
                {{-- Page Numbers --}}
                <template x-for="p in getPageNumbers()" :key="p">
                    <span class="inline-flex items-center gap-1">
                        <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                        <button type="button" x-show="p !== '...'"
                                @click="currentPage = p"
                                x-text="p"
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors shadow-2xs"
                                :class="currentPage === p ? 'bg-primary text-white border border-primary' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                    </span>
                </template>
                
                <button type="button" @click="if(currentPage < getTotalPages()) currentPage++" 
                        :disabled="currentPage >= getTotalPages()"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs">
                    NEXT
                </button>
            </div>
        </div>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- ═══════════════════════════════════════════
         CUSTOM DELETE UNIT CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    {{-- ═══════════════════════════════════════════
         CUSTOM DELETE UNIT CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="confirmDeleteUnitModal.open" 
         @click.stop
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80" @click.stop>
            {{-- Dark Header matching screenshot --}}
            <div class="bg-[#1c1716] px-6 py-5 text-white flex items-center justify-between relative overflow-hidden">
                <div class="space-y-1">
                    <span class="px-2.5 py-0.5 rounded-md bg-rose-500/20 text-rose-400 text-[10px] font-extrabold uppercase tracking-widest inline-block">WARNING</span>
                </div>
                <button type="button" @click.stop="confirmDeleteUnitModal.open = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
            </div>
            
            {{-- Body matching screenshot --}}
            <div class="p-6 space-y-4 text-slate-700 text-xs">
                <p class="font-bold text-slate-900 text-sm">
                    Are you sure you want to remove unit <span class="font-black text-rose-600 font-mono text-base" x-text="confirmDeleteUnitModal.doorNo"></span>?
                </p>

                <template x-if="confirmDeleteUnitModal.hasEmis || confirmDeleteUnitModal.isEmiPlan || confirmDeleteUnitModal.hasReceipts">
                    <div class="p-3.5 bg-amber-50/80 border border-amber-200 rounded-2xl space-y-1.5 text-amber-900 shadow-2xs">
                        <p class="font-extrabold uppercase tracking-wider text-[10px] text-amber-800 flex items-center gap-1.5">
                            <span>⚠️ Impact on Active Sale Contract</span>
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-[11px] text-slate-700 font-medium leading-relaxed">
                            <template x-if="confirmDeleteUnitModal.hasEmis || confirmDeleteUnitModal.isEmiPlan">
                                <li>EMI payment schedules / installments have already been created for this sale.</li>
                            </template>
                            <template x-if="confirmDeleteUnitModal.hasReceipts">
                                <li>Payment receipts have already been generated for this sale.</li>
                            </template>
                            <li>Removing this unit will automatically recalculate total contract amounts.</li>
                        </ul>
                    </div>
                </template>
            </div>

            {{-- Footer matching screenshot --}}
            <!-- <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                <button type="button" @click.stop="confirmDeleteUnitModal.open = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl text-xs uppercase tracking-wider transition cursor-pointer">
                    CANCEL
                </button>
                <button type="button" @click.stop="confirmExecuteDeleteUnit()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition cursor-pointer">
                    CONFIRM REMOVE
                </button>
            </div> -->
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ADD SALE MODAL (Redesigned)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up modal-widthincrease" @click.away="if (!modals.quickCustomer.open && !confirmDeleteUnitModal.open) closeAddModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-primary/20 text-primary text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Sale Registry</span>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">New Agreement</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-white tracking-tight mt-1">Add New Sale <span class="text-xs font-normal text-slate-450 font-sans">(Multi-Unit Contract)</span></h2>
                    </div>
                    <button type="button" @click="closeAddModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitAddSale()">
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto font-sans text-xs bg-slate-50/50" x-ref="addModalScroll">
                    {{-- ═══ Sticky Fixed Top Calculation Summary Bar ═══ --}}
                    <div class="sticky top-0 z-30 -mt-6 -mx-6 mb-4 px-6 py-3 bg-slate-900/95 backdrop-blur-md border-b border-slate-700/80 shadow-lg mt-2">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-extrabold text-[#d9bf3b] uppercase tracking-widest flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-6 4h6M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Live Calculation Summary</span>
                            </span>
                            <span class="text-sm font-black text-amber-300 font-mono" x-text="'Contract Total: ₹' + Number(forms.add.total_contract_value || 0).toLocaleString()"></span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
                            <!-- 1. Total Sale Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate">Total Sale Amount</span>
                                <span class="font-black text-white text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.unit_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 2. Difference Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate">Difference Amount</span>
                                <span class="font-black text-base sm:text-lg lg:text-xl font-mono mt-1"
                                      :class="getTotalDifference('add') > 0 ? 'text-emerald-400' : (getTotalDifference('add') < 0 ? 'text-rose-400' : 'text-slate-400')"
                                      x-text="(getTotalDifference('add') >= 0 ? '₹' : '-₹') + Math.abs(getTotalDifference('add')).toLocaleString()"></span>
                            </div>
                            <!-- 2. GST Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-amber-400/90 uppercase tracking-wider truncate">GST Amount</span>
                                <span class="font-black text-amber-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.unit_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 3. Parking Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-sky-400/90 uppercase tracking-wider truncate">Parking Amount</span>
                                <span class="font-black text-sky-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.parking_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 4. Parking GST -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-sky-300/90 uppercase tracking-wider truncate">Parking GST</span>
                                <span class="font-black text-sky-300 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.parking_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 5. Additional Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-teal-400/90 uppercase tracking-wider truncate">Additional Amount</span>
                                <span class="font-black text-teal-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.extra_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 6. Additional GST -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-teal-300/90 uppercase tracking-wider truncate">Additional GST</span>
                                <span class="font-black text-teal-300 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.extra_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 7. Total Contract Value -->
                            <div class="col-span-2 sm:col-span-4 lg:col-span-1 bg-gradient-to-r from-amber-500/20 via-yellow-500/25 to-amber-500/20 border border-amber-400/60 rounded-xl p-2 flex flex-col justify-between shadow-xs">
                                <span class="text-[9px] font-black text-[#d9bf3b] uppercase tracking-widest truncate">Contract Value</span>
                                <span class="font-black text-[#d9bf3b] text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.add.total_contract_value || 0).toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 1 — Basics ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>📋 Basic Information</span>
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project *</label>
                                <select x-model="forms.add.project_id" @change="loadUnitsForProject('add')"
                                        :class="errors.project_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                        class="w-full h-9 px-3 py-1.5 border focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="">Select Project...</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.project_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="Array.isArray(errors.project_id) ? errors.project_id[0] : errors.project_id"></p></template>
                            </div>
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                 <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Customer *</label>
                                 <div class="flex gap-2">
                                     <div class="relative flex-1">
                                         <!-- Main Selector Button -->
                                         <button type="button" 
                                                 @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                                                 :class="errors.customer_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : (open ? 'border-primary ring-4 ring-primary/10 bg-white shadow-sm' : 'border-slate-250 bg-white hover:bg-slate-50 hover:border-slate-300')"
                                                 class="w-full h-9 px-2.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-sm">
                                             <template x-if="getSelectedCustomer()">
                                                 <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                                     <div class="w-5 h-5 rounded-full bg-primary/10 text-primary font-bold text-[10px] flex items-center justify-center shrink-0" x-text="getSelectedCustomer().name.charAt(0).toUpperCase()"></div>
                                                     <span class="font-extrabold text-slate-800 truncate" x-text="getSelectedCustomer().name"></span>
                                                     <span class="text-xs font-bold text-slate-300 font-mono shrink-0" x-show="getSelectedCustomer().phone" x-text="'(' + getSelectedCustomer().phone + ')'"></span>
                                                 </div>
                                             </template>
                                             <template x-if="!getSelectedCustomer()">
                                                 <span class="text-slate-400 font-medium">— Select Customer —</span>
                                             </template>
                                             <div class="flex items-center gap-1 shrink-0 ml-1">
                                                 <template x-if="getSelectedCustomer()">
                                                     <span @click.stop="selectCustomer(null); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear customer">
                                                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                     </span>
                                                 </template>
                                                 <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                                              class="absolute left-0 top-full mt-1.5 w-80 bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                                              style="display: none;">
                                             
                                             <!-- Search Input Header -->
                                             <div class="p-2 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                                 <div class="relative">
                                                     <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                     <input type="text"
                                                            x-model="search"
                                                            x-ref="customerSearchInput"
                                                            placeholder="Type name or phone number..."
                                                            @keydown.escape="open = false"
                                                            class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                                     <template x-if="search">
                                                         <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                                     </template>
                                                 </div>
                                             </div>

                                             <!-- Clear Option -->
                                             <button type="button" @click="selectCustomer(null); open = false; search = ''"
                                                     class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition">
                                                 <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                 <span>Clear Selected Customer</span>
                                             </button>

                                             <!-- Customer List Options -->
                                             <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                                 <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                                                     <button type="button"
                                                             @click="selectCustomer(customer); open = false; search = ''"
                                                             :class="forms.add.customer_id == customer.id ? 'bg-primary/10 border-primary/20 text-primary shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                             class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                                         <div class="flex items-center gap-2.5 min-w-0">
                                                             <div :class="forms.add.customer_id == customer.id ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary/10 group-hover:text-primary'"
                                                                  class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors"
                                                                  x-text="(customer.name || '?').charAt(0).toUpperCase()"></div>
                                                             <div class="min-w-0">
                                                                 <p class="font-bold text-xs truncate leading-snug" :class="forms.add.customer_id == customer.id ? 'text-primary' : 'text-slate-800'" x-text="customer.name"></p>
                                                                 <div class="flex items-center gap-2 text-xs font-bold text-slate-300 font-mono mt-0.5" x-show="customer.phone">
                                                                     <span class="flex items-center gap-1">
                                                                         <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                                         <span x-text="customer.phone"></span>
                                                                     </span>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                         <template x-if="forms.add.customer_id == customer.id">
                                                             <svg class="w-4 h-4 text-primary shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                         </template>
                                                     </button>
                                                 </template>
                                                 <!-- Empty State -->
                                                 <div x-show="getFilteredCustomersList(search).length === 0"
                                                      class="py-6 px-4 text-center">
                                                     <p class="text-xs text-slate-400 italic">No matching customers found</p>
                                                     <button type="button" @click="open = false; openQuickAddCustomer()" class="mt-2 text-[11px] text-primary font-bold hover:underline uppercase tracking-wider">+ Add New Customer</button>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <button type="button" @click="openQuickAddCustomer()"
                                             title="Quick Add Customer"
                                             class="flex-shrink-0 w-9 h-9 flex items-center justify-center border border-slate-300 rounded-xl text-slate-500 hover:border-primary hover:text-primary hover:bg-primary/5 transition shadow-xs">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                     </button>
                                 </div>
                                 <template x-if="errors.customer_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="Array.isArray(errors.customer_id) ? errors.customer_id[0] : errors.customer_id"></p></template>
                             </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreement Date *</label>
                                <input type="date" x-model="forms.add.agreement_date"
                                       :class="errors.agreement_date ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       class="w-full h-9 px-3 py-1.5 border focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <template x-if="errors.agreement_date"><p class="text-[10px] text-rose-600 font-semibold" x-text="Array.isArray(errors.agreement_date) ? errors.agreement_date[0] : errors.agreement_date"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Registration Date</label>
                                <input type="date" x-model="forms.add.registration_date"
                                       class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 2 — Repeatable Units / Line Items ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                                <span>🏢 Booked Inventory / Units</span>
                            </p>
                            <button type="button" @click="addUnitRow()"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Unit Row
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(row, index) in forms.add.units" :key="index">
                                <div class="p-4 bg-slate-50/50 border border-slate-200/60 rounded-xl space-y-3 relative" :x-ref="'unitRow_' + index">
                                    <button type="button" @click="removeUnitRow(index)" x-show="forms.add.units.length > 1"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-wider">✕ Remove</button>
                                    
                                    <!-- Row 1: Unit, Built Up Area, Expected Rate/Sqft, Sale Rate/Sqft (4 columns) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit *</label>
                                            <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                                 <button type="button" 
                                                         @click="open = !open; if (open) { $nextTick(() => $refs.unitSearchInput?.focus()); }"
                                                         :disabled="!forms.add.project_id"
                                                         :class="errors['units.' + index + '.unit_id'] ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : (open ? 'border-primary ring-4 ring-primary/10 bg-white shadow-sm' : 'border-slate-250 bg-white hover:bg-slate-50 hover:border-slate-300')"
                                                         class="w-full h-9 px-2.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-sm disabled:opacity-50">
                                                     <template x-if="row.unit_id && availableUnits.add.find(u => u.id == row.unit_id)">
                                                         <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                                             <span class="font-extrabold text-slate-800 truncate text-xs" x-text="availableUnits.add.find(u => u.id == row.unit_id).door_no"></span>
                                                             <span class="text-xs font-bold text-slate-300 font-mono shrink-0" x-text="'(' + availableUnits.add.find(u => u.id == row.unit_id).floor_name + ')'"></span>
                                                         </div>
                                                     </template>
                                                     <template x-if="!row.unit_id || !availableUnits.add.find(u => u.id == row.unit_id)">
                                                         <span class="text-slate-400 font-medium">— Select Unit —</span>
                                                     </template>
                                                     <div class="flex items-center gap-1 shrink-0 ml-1">
                                                         <template x-if="row.unit_id">
                                                             <span @click.stop="row.unit_id = ''; onRowUnitSelect(index, 'add'); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear unit">
                                                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                             </span>
                                                         </template>
                                                         <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                                                      class="absolute left-0 top-full mt-1.5 w-80 bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                                                      style="display: none;">
                                                     
                                                     <!-- Search Input Header -->
                                                     <div class="p-2 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                                         <div class="relative">
                                                             <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                             <input type="text"
                                                                    x-model="search"
                                                                    x-ref="unitSearchInput"
                                                                    placeholder="Type floor, door no, or unit type..."
                                                                    @keydown.escape="open = false"
                                                                    class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                                             <template x-if="search">
                                                                 <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                                             </template>
                                                         </div>
                                                     </div>

                                                     <!-- Clear Option -->
                                                     <button type="button" @click="row.unit_id = ''; onRowUnitSelect(index, 'add'); open = false; search = ''"
                                                             class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition">
                                                         <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                         <span>Clear Selected Unit</span>
                                                     </button>

                                                     <!-- Unit List Options -->
                                                     <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                                         <template x-for="unit in getFilteredUnits('add', search)" :key="unit.id">
                                                             <button type="button"
                                                                     @click="row.unit_id = unit.id; onRowUnitSelect(index, 'add'); open = false; search = ''"
                                                                     :disabled="forms.add.units.some((r, i) => i !== index && r.unit_id == unit.id)"
                                                                     :class="row.unit_id == unit.id ? 'bg-primary/10 border-primary/20 text-primary shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                                     class="w-full px-3 py-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                                                 <div class="flex items-center gap-2 min-w-0">
                                                                     <span class="font-extrabold text-xs truncate" :class="row.unit_id == unit.id ? 'text-primary' : 'text-slate-800'" x-text="unit.door_no"></span>
                                                                     <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0"
                                                                           x-text="unit.unit_type_name || 'Unit'"></span>
                                                                 </div>
                                                                 <div class="flex items-center gap-2 shrink-0">
                                                                     <span class="text-xs font-bold text-slate-300 font-mono" x-text="unit.floor_name"></span>
                                                                     <template x-if="forms.add.units.some((r, i) => i !== index && r.unit_id == unit.id)">
                                                                         <span class="text-[9px] font-bold text-rose-500 uppercase tracking-wider bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 shrink-0">Selected</span>
                                                                     </template>
                                                                     <template x-if="row.unit_id == unit.id">
                                                                         <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                     </template>
                                                                 </div>
                                                             </button>
                                                         </template>
                                                         <!-- Empty State -->
                                                         <div x-show="getFilteredUnits('add', search).length === 0"
                                                              class="py-6 px-4 text-center text-xs text-slate-400 italic">
                                                             No matching units found
                                                         </div>
                                                     </div>
                                                 </div>
                                            </div>
                                            <template x-if="errors['units.' + index + '.unit_id']">
                                                <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors['units.' + index + '.unit_id']) ? errors['units.' + index + '.unit_id'][0] : errors['units.' + index + '.unit_id']"></p>
                                            </template>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built Up Area (Sq Ft)</label>
                                            <div class="w-full px-2.5 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 font-bold h-9 flex items-center shadow-inner">
                                                <span x-text="onGetRowArea(index) + ' Sq Ft'"></span>
                                            </div>
                                        </div>
                                        <div x-show="!isRowParking(index, 'add')" class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate/Sqft *</label>
                                            <input type="number" step="0.01" x-model="row.rate_per_sqft" placeholder="Expected rate"
                                                   class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div x-show="!isRowParking(index, 'add')" class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sale Rate/Sqft *</label>
                                            <input type="number" step="0.01" x-model="row.sale_rate_per_sqft" @input="onRowSaleRateChange(index)" placeholder="Sale rate"
                                                   class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                            <div class="text-xs sm:text-sm font-extrabold font-mono flex items-center gap-1.5 pt-0.5"
                                                 :class="getRowDifference(index, 'add') > 0 ? 'text-emerald-600' : (getRowDifference(index, 'add') < 0 ? 'text-rose-600' : 'text-slate-500')">
                                                <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Diff:</span>
                                                <span x-text="(getRowDifference(index, 'add') >= 0 ? '₹' : '-₹') + Math.abs(getRowDifference(index, 'add')).toLocaleString()"></span>
                                            </div>
                                        </div>
                                        <div x-show="isRowParking(index, 'add')" class="space-y-1.5 lg:col-span-2">
                                            <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Expected Sale Amount (Parking) *</label>
                                            <div class="relative rounded-xl shadow-sm h-9">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-slate-400 font-bold text-xs">₹</span>
                                                </div>
                                                <input type="number" step="0.01" x-model="row.expected_sale_amount" @input="row.sale_amount = row.expected_sale_amount; recalculateRowGst(index, 'add')" placeholder="Enter parking expected sale amount"
                                                       class="block w-full h-full pl-7 pr-3 py-1.5 border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary bg-amber-50/30 rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <!-- Row 2: Agreed Sale Amount, GST %, GST Amount, Total Payable (4 columns) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start pt-3 border-t border-slate-200/50">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreed Sale Amount *</label>
                                            <div class="relative rounded-xl shadow-sm h-9">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-slate-400 font-bold text-xs">₹</span>
                                                </div>
                                                <input type="number" step="0.01" x-model="row.sale_amount" @input="onRowSaleAmountChange(index)" placeholder="0.00"
                                                       :class="errors['units.' + index + '.sale_amount'] ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] bg-slate-50/50 focus:bg-white'"
                                                       class="block w-full h-full pl-7 pr-3 py-1.5 border rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                            </div>
                                            <template x-if="errors['units.' + index + '.sale_amount']">
                                                <p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors['units.' + index + '.sale_amount']) ? errors['units.' + index + '.sale_amount'][0] : errors['units.' + index + '.sale_amount']"></p>
                                            </template>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Percentage (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateRowGst(index)" placeholder="e.g. 18"
                                                   class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Amount (₹)</label>
                                            <div class="relative h-9 rounded-xl shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-slate-400 font-bold text-xs">₹</span>
                                                </div>
                                                <input type="number" step="0.01" x-model="row.gst_amount" @input="recalculateRowGstFromAmount(index)" placeholder="0.00"
                                                       class="block w-full h-full pl-7 pr-3 py-1.5 border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Payable</label>
                                            <p class="font-black text-indigo-700 text-lg sm:text-xl font-mono pt-0.5 h-9 flex items-center" x-text="'₹' + Number(row.total_amount || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50" x-show="forms.add.broker_involved && row.broker_involved">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                            <select x-model="row.brokerage_type" @change="recalculateRowBrokerage(index)"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed">Fixed (₹)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                            <input type="number" step="0.01" x-model="row.brokerage_value" @input="recalculateRowBrokerage(index)" placeholder="Value"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Brokerage Amount</p>
                                            <p class="font-bold text-slate-800 mt-1 font-mono" x-text="'₹' + Number(row.brokerage_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Section 3 — Broker / Commission ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <label class="flex items-center gap-2 border-b border-slate-100 pb-2 cursor-pointer">
                            <input type="checkbox" x-model="forms.add.broker_involved" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>💼 Broker / Commission Details</span>
                            </span>
                        </label>
                        <div x-show="forms.add.broker_involved" class="space-y-4" x-transition>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker</label>
                                    <select x-model="forms.add.broker_id" @change="onBrokerSelect('add')"
                                            class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        <option value="">— Select Broker —</option>
                                        @foreach($brokers as $broker)
                                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                        @endforeach
                                    </select>
                                    <template x-if="errors.broker_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.broker_id[0]"></p></template>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                    <div class="flex items-center gap-4 h-9 px-3 bg-slate-50 border border-slate-250 rounded-xl shadow-sm">
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="percentage" x-model="forms.add.brokerage_type" @change="onBrokerageTypeChange('add')" class="text-primary focus:ring-primary/20">
                                            <span>Percentage (%)</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="fixed" x-model="forms.add.brokerage_type" @change="onBrokerageTypeChange('add')" class="text-primary focus:ring-primary/20">
                                            <span>Fixed (₹)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                    <input type="number" step="any" min="0" :max="forms.add.brokerage_type === 'percentage' ? 100 : null" x-model="forms.add.brokerage_value" @input="recalculateAllTotals('add')" :placeholder="forms.add.brokerage_type === 'fixed' ? 'Enter fixed amount (₹)' : 'e.g. 2 for 2%'"
                                           class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono shadow-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brokerage Amount</p>
                                    <div class="w-full h-9 px-3 bg-slate-100/90 border border-slate-250 rounded-xl font-bold text-slate-900 font-mono text-xs flex items-center shadow-sm" x-text="'₹' + Number(forms.add.brokerage_amount || 0).toLocaleString()"></div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Status</label>
                                    <select x-model="forms.add.brokerage_status"
                                            class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Custom Alterations / Extra Work (add) ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>🛠️ Custom Alterations / Extra Work</span>
                            </p>
                            <button type="button" @click="addExtraWorkRow('add')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Extra Work
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(row, index) in forms.add.extra_works" :key="index">
                                <div class="p-4 bg-slate-50/50 border border-slate-200/60 rounded-xl space-y-3 relative">
                                    <button type="button" @click="removeExtraWorkRow(index, 'add')"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-wider">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-start">
                                        <div class="space-y-1.5 sm:col-span-2">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Description / Work Details *</label>
                                            <input type="text" x-model="row.description" placeholder="e.g. Flooring Upgrade, Custom Fittings"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Amount (₹) *</label>
                                            <input type="number" step="0.01" x-model="row.amount" @input="recalculateExtraWorkRowGst(index, 'add')" placeholder="Enter amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono shadow-sm">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Type</label>
                                            <select x-model="row.gst_type" @change="recalculateExtraWorkRowGst(index, 'add')"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                                <option value="none">None</option>
                                                <option value="exclusive">Exclusive</option>
                                                <option value="inclusive">Inclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5 sm:col-span-1" x-show="row.gst_type !== 'none'">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateExtraWorkRowGst(index, 'add')" placeholder="18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="space-y-1.5 sm:col-span-1" x-show="row.gst_type !== 'none'">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-bold text-emerald-800">GST Amount</p>
                                            <p class="font-bold text-slate-900 leading-9 font-mono text-xs" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="space-y-1 ml-auto text-right" :class="row.gst_type !== 'none' ? 'sm:col-span-2' : 'sm:col-span-4'">
                                            <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Total Payable</p>
                                            <p class="font-black text-emerald-800 text-lg sm:text-xl font-mono pt-0.5" x-text="'₹' + Number(row.line_total || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- Aggregated Contract Summary --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 border border-slate-800 rounded-xl p-5 relative overflow-hidden shadow-md space-y-3">
                        <div class="absolute -top-12 -left-12 w-32 h-32 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-white relative z-10">
                            <div class="border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Sale Amount</span>
                                <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="'₹' + Number(forms.add.base_amount || 0).toLocaleString()"></span>
                            </div>
                            <div class="border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total GST Amount</span>
                                <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="'₹' + Number(forms.add.gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Contract Value</span>
                                <span class="font-extrabold text-[#d9bf3b] text-lg mt-1 block font-mono" x-text="'₹' + Number(forms.add.total_amount || 0).toLocaleString()"></span>
                            </div>
                        </div>
                        <template x-if="forms.add.total_amount && parseFloat(forms.add.total_amount) > 0">
                            <div class="relative z-10 pt-2.5 border-t border-slate-700/60 text-center">
                                <span class="text-xs font-bold text-slate-200 capitalize tracking-wide ml-1" x-text="numberToWords(forms.add.total_amount)"></span>
                            </div>
                        </template>
                    </div>
                    {{-- ── Section 4 — Initial Payment ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>💼 Initial Payment Details</span>
                        </p>
                        
                        <div class="flex flex-col md:flex-row gap-5 items-start bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5 flex-1 w-full">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Initial Payment Amount & %</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" min="0" x-model="forms.add.initial_payment_amount" @input="updateInitialPaymentFromAmount('add', $event)" placeholder="Amount (₹)"
                                               :class="{'border-rose-500 ring-2 ring-rose-500/20 text-rose-700 bg-rose-50/20': forms.add.total_amount > 0 && (parseFloat(forms.add.initial_payment_amount) || 0) > parseFloat(forms.add.total_amount)}"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                    <div>
                                        <input type="number" step="0.01" min="0" max="100" x-model="forms.add.initial_payment_percentage" @input="updateInitialPaymentFromPercentage('add', $event)" placeholder="Percentage (%)"
                                               :class="{'border-rose-500 ring-2 ring-rose-500/20 text-rose-700 bg-rose-50/20': (parseFloat(forms.add.initial_payment_percentage) || 0) > 100}"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                </div>
                                <p x-show="!((parseFloat(forms.add.total_amount) > 0 && (parseFloat(forms.add.initial_payment_amount) || 0) > parseFloat(forms.add.total_amount)) || (parseFloat(forms.add.initial_payment_percentage) || 0) > 100)"
                                   class="text-[9px] text-slate-400 font-medium">Enter amount or percentage (0 if none)</p>
                                <p x-show="(parseFloat(forms.add.total_amount) > 0 && (parseFloat(forms.add.initial_payment_amount) || 0) > parseFloat(forms.add.total_amount)) || (parseFloat(forms.add.initial_payment_percentage) || 0) > 100"
                                   class="text-[10px] font-semibold text-rose-600 mt-1 flex items-center gap-1" x-cloak>
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Initial payment cannot exceed Total Contract Value. Please enter a lesser amount.</span>
                                </p>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Mode</label>
                                <select x-model="forms.add.payment_mode"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Date</label>
                                <input type="date" x-model="forms.add.initial_payment_date"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div x-show="['Bank Transfer', 'Cheque', 'UPI'].includes(forms.add.payment_mode)" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 shadow-sm" x-transition>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block" x-text="forms.add.payment_mode === 'UPI' ? 'UPI Number / Transaction ID' : 'Reference / Cheque No'"></label>
                                <input type="text" x-model="forms.add.reference_no" :placeholder="forms.add.payment_mode === 'UPI' ? 'Enter UPI Number or Ref ID' : 'e.g. UTR / Cheque number'"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Bank Name</label>
                                <select x-model="forms.add.bank_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="">Select Bank Account</option>
                                    <template x-for="bank in bankAccountsList" :key="bank.id">
                                        <option :value="bank.id" x-text="bank.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 5 — Balance & Payment Plan ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>📊 Balance & Payment Plan</span>
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-slate-55/30 border border-slate-200/85 rounded-xl p-4 flex flex-col justify-center shadow-inner">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Remaining Balance</span>
                                <span class="text-xl font-extrabold text-primary mt-1 block font-mono" x-text="'₹' + Number(forms.add.remaining_balance || 0).toLocaleString()"></span>
                            </div>
                            <div class="space-y-3 md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Plan</label>
                                <div class="flex items-center gap-4 h-9">
                                    <label class="flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                        <input type="radio" value="lump_sum" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        <span>Lump Sum (Full payment)</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                        <input type="radio" value="emi" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        <span>EMI / Installment Plan</span>
                                    </label>
                                </div>
                                <div x-show="forms.add.payment_plan === 'emi'" class="mt-4 space-y-4" x-transition>
                                    {{-- Equal Installments Fields --}}
                                    <div class="grid grid-cols-3 gap-3 border border-slate-200/60 p-3 rounded-xl bg-slate-50/50">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">No. of Installments</label>
                                            <input type="number" x-model="forms.add.emi_installment_count" min="1" placeholder="e.g. 12"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">Frequency</label>
                                            <select x-model="forms.add.emi_frequency"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">First Installment Date</label>
                                            <input type="date" x-model="forms.add.first_installment_date"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                        </div>
                                    </div>
                                    {{-- Live Preview Block --}}
                                    <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-xl p-3 space-y-2">
                                        <p class="text-[9px] font-bold text-indigo-800 uppercase tracking-widest">📝 Live Schedule Preview</p>
                                        <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-[11px] font-semibold text-slate-700">
                                            <template x-for="(preview, pIdx) in getEmiPreview()" :key="pIdx">
                                                <div class="flex justify-between items-center py-1 border-b border-indigo-100/30">
                                                    <span x-text="preview.label"></span>
                                                    <div class="flex gap-4">
                                                        <span class="text-slate-400 font-mono text-[10px]" x-text="preview.due_date"></span>
                                                        <span class="font-bold text-indigo-700 font-mono" x-text="'₹' + Number(preview.amount).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="getEmiPreview().length === 0">
                                                <p class="text-[10px] text-slate-455 italic py-1 text-center">No schedule preview available. Fill EMI parameters.</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 6 — Remarks ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            <span>💬 Remarks / Notes</span>
                        </p>
                        <textarea x-model="forms.add.notes" rows="3" placeholder="Optional remarks or notes..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Create Sale</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         QUICK ADD CUSTOMER MODAL (nested)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.quickCustomer.open" @click.self.stop="modals.quickCustomer.open = false" class="fixed inset-0 z-[60] flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.stop>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Quick Add Customer</h3>
                <button @click="modals.quickCustomer.open = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitQuickCustomer()">
                <div class="p-6 space-y-3">
                    <input type="text" x-model="quickCustomer.name" placeholder="Full Name"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <input type="email" x-model="quickCustomer.email" placeholder="Email"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <input type="text" x-model="quickCustomer.phone" placeholder="Phone"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <template x-if="quickCustomerErrors.name"><p class="text-[10px] text-rose-600 font-semibold" x-text="quickCustomerErrors.name[0]"></p></template>
                    <template x-if="quickCustomerErrors.email"><p class="text-[10px] text-rose-600 font-semibold" x-text="quickCustomerErrors.email[0]"></p></template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="modals.quickCustomer.open = false" class="px-4 py-2 border border-slate-200 text-slate-650 text-xs font-bold rounded-xl uppercase">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl uppercase">Add & Select</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         EDIT SALE MODAL (legacy single-unit fields kept for backward-compatible edits)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up modal-widthincrease" @click.away="if (!modals.quickCustomer.open && !confirmDeleteUnitModal.open) closeEditModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-[#a38c29]/20">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Contract Editing</span>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Mode</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-white tracking-tight mt-1">Edit Sale — <span class="text-[#d9bf3b] font-mono" x-text="activeSale.sale_number"></span></h2>
                    </div>
                    <button type="button" @click="closeEditModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitEditSale()">
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    {{-- ═══ Sticky Fixed Top Calculation Summary Bar ═══ --}}
                    <div class="sticky top-0 z-30 -mt-6 -mx-6 mb-4 px-6 py-3 bg-slate-900/95 backdrop-blur-md border-b border-slate-700/80 shadow-lg mt-2">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-extrabold text-[#d9bf3b] uppercase tracking-widest flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-6 4h6M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Live Calculation Summary</span>
                            </span>
                            <span class="text-sm font-black text-amber-300 font-mono" x-text="'Contract Total: ₹' + Number(forms.edit.total_contract_value || 0).toLocaleString()"></span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
                            <!-- 1. Total Sale Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate">Total Sale Amount</span>
                                <span class="font-black text-white text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.unit_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 2. Difference Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate">Difference Amount</span>
                                <span class="font-black text-base sm:text-lg lg:text-xl font-mono mt-1"
                                      :class="getTotalDifference('edit') > 0 ? 'text-emerald-400' : (getTotalDifference('edit') < 0 ? 'text-rose-400' : 'text-slate-400')"
                                      x-text="(getTotalDifference('edit') >= 0 ? '₹' : '-₹') + Math.abs(getTotalDifference('edit')).toLocaleString()"></span>
                            </div>
                            <!-- 2. GST Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-amber-400/90 uppercase tracking-wider truncate">GST Amount</span>
                                <span class="font-black text-amber-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.unit_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 3. Parking Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-sky-400/90 uppercase tracking-wider truncate">Parking Amount</span>
                                <span class="font-black text-sky-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.parking_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 4. Parking GST -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-sky-300/90 uppercase tracking-wider truncate">Parking GST</span>
                                <span class="font-black text-sky-300 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.parking_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 5. Additional Amount -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-teal-400/90 uppercase tracking-wider truncate">Additional Amount</span>
                                <span class="font-black text-teal-400 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.extra_base_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 6. Additional GST -->
                            <div class="bg-slate-800/90 border border-slate-700/70 rounded-xl p-2 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-bold text-teal-300/90 uppercase tracking-wider truncate">Additional GST</span>
                                <span class="font-black text-teal-300 text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.extra_gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <!-- 7. Total Contract Value -->
                            <div class="col-span-2 sm:col-span-4 lg:col-span-1 bg-gradient-to-r from-amber-500/20 via-yellow-500/25 to-amber-500/20 border border-amber-400/60 rounded-xl p-2 flex flex-col justify-between shadow-xs">
                                <span class="text-[9px] font-black text-[#d9bf3b] uppercase tracking-widest truncate">Contract Value</span>
                                <span class="font-black text-[#d9bf3b] text-base sm:text-lg lg:text-xl font-mono mt-1" x-text="'₹' + Number(forms.edit.total_contract_value || 0).toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 1 — Basics (Read-Only) ── --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Project Card --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Project Name</span>
                                <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.project ? activeSale.project.name : '—'"></strong>
                            </div>
                        </div>
                        {{-- Unit Card --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Unit & Floor</span>
                                <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.sale_units && activeSale.sale_units.length ? activeSale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (activeSale.unit ? activeSale.unit.door_no + ' — ' + (activeSale.unit.floor ? activeSale.unit.floor.name : '') : '—')"></strong>
                            </div>
                        </div>
                        {{-- Customer Card --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Customer Details</span>
                                <strong class="text-slate-800 text-xs block mt-1 truncate" x-text="activeSale.customer ? activeSale.customer.name : '—'"></strong>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 2 — Booked Inventory / Units ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                                <span>🏢 Booked Inventory / Units</span>
                            </p>
                            <button type="button" @click="addUnitRow('edit')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Unit Row
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(row, index) in forms.edit.units" :key="index">
                                <div class="p-4 bg-slate-50/50 border border-slate-200/60 rounded-xl space-y-3 relative">
                                    <button type="button" @click="removeUnitRow(index, 'edit')" x-show="forms.edit.units.length > 1"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-wider">✕ Remove</button>
                                     <!-- Single row: 5 fields (Unit, Built Up Area, Agreed Sale Amount, Expected Rate/Sqft, Sale Rate/Sqft) -->
                                     <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                                         <div class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit *</label>
                                             <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                                 <!-- Trigger Button -->
                                                 <button type="button" 
                                                         @click="open = !open; if (open) { $nextTick(() => $refs.unitEditSearchInput?.focus()); }" 
                                                         :disabled="!forms.edit.project_id"
                                                         :class="open ? 'border-primary ring-4 ring-primary/10 bg-white shadow-sm' : 'border-slate-250 bg-white hover:bg-slate-50 hover:border-slate-300'"
                                                         class="w-full px-2.5 py-1.5 border rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 text-left flex justify-between items-center h-9 shadow-sm cursor-pointer">
                                                     <template x-if="row.unit_id && availableUnits.edit.find(u => u.id == row.unit_id)">
                                                         <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                                             <span class="font-extrabold text-slate-800 truncate text-xs" x-text="availableUnits.edit.find(u => u.id == row.unit_id).door_no"></span>
                                                             <span class="text-xs font-bold text-slate-300 font-mono shrink-0" x-text="'(' + availableUnits.edit.find(u => u.id == row.unit_id).floor_name + ')'"></span>
                                                         </div>
                                                     </template>
                                                     <template x-if="!row.unit_id || !availableUnits.edit.find(u => u.id == row.unit_id)">
                                                         <span class="text-slate-400 font-medium">— Select Unit —</span>
                                                     </template>
                                                     <div class="flex items-center gap-1 shrink-0 ml-1">
                                                         <template x-if="row.unit_id">
                                                             <span @click.stop="row.unit_id = ''; onRowUnitSelect(index, 'edit'); search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear unit">
                                                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                             </span>
                                                         </template>
                                                         <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                                                      class="absolute z-50 left-0 top-full mt-1.5 w-80 bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col"
                                                      style="display: none;">
                                                     
                                                     <!-- Search Input Header -->
                                                     <div class="p-2 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                                         <div class="relative">
                                                             <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                             <input type="text"
                                                                    x-model="search"
                                                                    x-ref="unitEditSearchInput"
                                                                    placeholder="Type floor, door no, or unit type..."
                                                                    @keydown.escape="open = false"
                                                                    class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                                             <template x-if="search">
                                                                 <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                                             </template>
                                                         </div>
                                                     </div>

                                                     <!-- Clear Option -->
                                                     <button type="button" @click="row.unit_id = ''; onRowUnitSelect(index, 'edit'); open = false; search = ''"
                                                             class="w-full px-3.5 py-1.5 text-left text-[11px] font-bold text-slate-400 hover:bg-slate-50 border-b border-slate-100 flex items-center gap-1.5 transition">
                                                         <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                         <span>Clear Selected Unit</span>
                                                     </button>

                                                     <!-- Unit List Options -->
                                                     <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                                         <template x-for="unit in getFilteredUnits('edit', search)" :key="unit.id">
                                                             <button type="button"
                                                                     @click="row.unit_id = unit.id; onRowUnitSelect(index, 'edit'); open = false; search = ''"
                                                                     :disabled="forms.edit.units.some((r, i) => i !== index && r.unit_id == unit.id)"
                                                                     :class="row.unit_id == unit.id ? 'bg-primary/10 border-primary/20 text-primary shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                                     class="w-full px-3 py-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                                                 <div class="flex items-center gap-2 min-w-0">
                                                                     <span class="font-extrabold text-xs truncate" :class="row.unit_id == unit.id ? 'text-primary' : 'text-slate-800'" x-text="unit.door_no"></span>
                                                                     <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0"
                                                                           x-text="unit.unit_type_name || 'Unit'"></span>
                                                                 </div>
                                                                 <div class="flex items-center gap-2 shrink-0">
                                                                     <span class="text-xs font-bold text-slate-300 font-mono" x-text="unit.floor_name"></span>
                                                                     <template x-if="forms.edit.units.some((r, i) => i !== index && r.unit_id == unit.id)">
                                                                         <span class="text-[9px] font-bold text-rose-500 uppercase tracking-wider bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 shrink-0">Selected</span>
                                                                     </template>
                                                                     <template x-if="row.unit_id == unit.id">
                                                                         <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                     </template>
                                                                 </div>
                                                             </button>
                                                         </template>
                                                         <!-- Empty State -->
                                                         <div x-show="getFilteredUnits('edit', search).length === 0"
                                                              class="py-6 px-4 text-center text-xs text-slate-400 italic">
                                                             No matching units found
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built Up Area (Sq Ft)</label>
                                             <div class="w-full px-2.5 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-650 font-bold h-9 flex items-center shadow-inner">
                                                 <span x-text="onGetRowArea(index, 'edit') + ' Sq Ft'"></span>
                                             </div>
                                         </div>
                                         <div x-show="!isRowParking(index, 'edit')" class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate/Sqft *</label>
                                             <input type="number" step="0.01" x-model="row.rate_per_sqft" placeholder="Expected rate"
                                                    class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                         </div>
                                          <div x-show="!isRowParking(index, 'edit')" class="space-y-1">
                                              <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sale Rate/Sqft *</label>
                                              <input type="number" step="0.01" x-model="row.sale_rate_per_sqft" @input="onRowSaleRateChange(index, 'edit')" placeholder="Sale rate"
                                                     class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                              <div class="text-xs sm:text-sm font-extrabold font-mono flex items-center gap-1.5 pt-0.5"
                                                   :class="getRowDifference(index, 'edit') > 0 ? 'text-emerald-600' : (getRowDifference(index, 'edit') < 0 ? 'text-rose-600' : 'text-slate-500')">
                                                  <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Diff:</span>
                                                  <span x-text="(getRowDifference(index, 'edit') >= 0 ? '₹' : '-₹') + Math.abs(getRowDifference(index, 'edit')).toLocaleString()"></span>
                                              </div>
                                           </div>
                                                                        <div x-show="isRowParking(index, 'edit')" class="space-y-1.5 lg:col-span-2">
                                              <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Expected Sale Amount (Parking) *</label>
                                              <div class="relative h-9 rounded-xl shadow-sm">
                                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                      <span class="text-slate-400 font-bold text-xs">₹</span>
                                                  </div>
                                                  <input type="number" step="0.01" x-model="row.expected_sale_amount" @input="row.sale_amount = row.expected_sale_amount; recalculateRowGst(index, 'edit')" placeholder="Enter parking expected sale amount"
                                                         class="block w-full h-full pl-7 pr-3 py-1.5 border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary bg-amber-50/30 rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                              </div>
                                          </div>
                                      </div>
                                     <!-- Third row: GST and Payable calculations -->
                                     <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start pt-3 border-t border-slate-200/50">
                                         <div class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreed Sale Amount *</label>
                                             <div class="relative rounded-xl shadow-sm h-9">
                                                 <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                     <span class="text-slate-400 font-bold text-xs">₹</span>
                                                 </div>
                                                 <input type="number" step="0.01" x-model="row.sale_amount" @input="onRowSaleAmountChange(index, 'edit')" placeholder="0.00"
                                                        class="block w-full h-full pl-7 pr-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] bg-slate-50/50 focus:bg-white rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                             </div>
                                         </div>
                                         <div class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Percentage (%)</label>
                                             <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateRowGst(index, 'edit')" placeholder="e.g. 18"
                                                    class="w-full h-9 px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                         </div>
                                         <div class="space-y-1.5">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Amount (₹)</label>
                                             <div class="relative h-9 rounded-xl shadow-sm">
                                                 <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                     <span class="text-slate-400 font-bold text-xs">₹</span>
                                                 </div>
                                                 <input type="number" step="0.01" x-model="row.gst_amount" @input="recalculateRowGstFromAmount(index, 'edit')" placeholder="0.00"
                                                        class="block w-full h-full pl-7 pr-3 py-1.5 border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white rounded-xl text-xs focus:outline-none transition-all font-mono font-bold text-slate-800 placeholder-slate-400">
                                             </div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Payable</label>
                                             <p class="font-black text-emerald-800 text-lg sm:text-xl font-mono pt-0.5 h-9 flex items-center" x-text="'₹' + Number(row.total_amount || 0).toLocaleString()"></p>
                                         </div>
                                     </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Custom Alterations / Extra Work (edit) ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>🛠️ Custom Alterations / Extra Work</span>
                            </p>
                            <button type="button" @click="addExtraWorkRow('edit')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Extra Work
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(row, index) in forms.edit.extra_works" :key="index">
                                <div class="p-4 bg-slate-50/50 border border-slate-200/60 rounded-xl space-y-3 relative">
                                    <button type="button" @click="removeExtraWorkRow(index, 'edit')"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-wider">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-start">
                                        <div class="space-y-1.5 sm:col-span-2">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Description / Work Details *</label>
                                            <input type="text" x-model="row.description" placeholder="e.g. Flooring Upgrade, Custom Fittings"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Amount (₹) *</label>
                                            <input type="number" step="0.01" x-model="row.amount" @input="recalculateExtraWorkRowGst(index, 'edit')" placeholder="Enter amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono shadow-sm">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Type</label>
                                            <select x-model="row.gst_type" @change="recalculateExtraWorkRowGst(index, 'edit')"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                                <option value="none">None</option>
                                                <option value="exclusive">Exclusive</option>
                                                <option value="inclusive">Inclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5 sm:col-span-1" x-show="row.gst_type !== 'none'">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateExtraWorkRowGst(index, 'edit')" placeholder="18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="space-y-1.5 sm:col-span-1" x-show="row.gst_type !== 'none'">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-bold text-emerald-800">GST Amount</p>
                                            <p class="font-bold text-slate-900 leading-9 font-mono text-xs" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="space-y-1 ml-auto text-right" :class="row.gst_type !== 'none' ? 'sm:col-span-2' : 'sm:col-span-4'">
                                            <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Total Payable</p>
                                            <p class="font-black text-emerald-800 text-lg sm:text-xl font-mono pt-0.5" x-text="'₹' + Number(row.line_total || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Section 3 — Pricing & Contract Totals Summary ── --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 border border-slate-800 rounded-xl p-5 relative overflow-hidden shadow-md space-y-3">
                        <div class="absolute -top-12 -left-12 w-32 h-32 bg-[#09876B]/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-white relative z-10">
                            <div class="border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Sale Amount</span>
                                <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="'₹' + Number(forms.edit.base_amount || 0).toLocaleString()"></span>
                            </div>
                            <div class="border-b md:border-b-0 md:border-r border-slate-700/50 pb-3 md:pb-0 md:pr-4">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total GST Amount</span>
                                <span class="font-extrabold text-white text-base mt-1 block font-mono" x-text="'₹' + Number(forms.edit.gst_amount || 0).toLocaleString()"></span>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Contract Value</span>
                                <span class="font-extrabold text-[#d9bf3b] text-lg mt-1 block font-mono" x-text="'₹' + Number(forms.edit.total_amount || 0).toLocaleString()"></span>
                            </div>
                        </div>
                        <template x-if="forms.edit.total_amount && parseFloat(forms.edit.total_amount) > 0">
                            <div class="relative z-10 pt-2.5 border-t border-slate-700/60 text-center">
                                <span class="text-xs font-bold text-slate-200 capitalize tracking-wide ml-1" x-text="numberToWords(forms.edit.total_amount)"></span>
                            </div>
                        </template>
                    </div>
                    {{-- ── Section 3 — Broker / Commission ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <label class="flex items-center gap-2 border-b border-slate-100 pb-2 cursor-pointer">
                            <input type="checkbox" x-model="forms.edit.broker_involved" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-[10px] font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>💼 Broker / Commission Details</span>
                            </span>
                        </label>
                        <div x-show="forms.edit.broker_involved" class="space-y-4" x-transition>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker</label>
                                    <select x-model="forms.edit.broker_id" @change="onBrokerSelect('edit')"
                                            class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        <option value="">— Select Broker —</option>
                                        @foreach($brokers as $broker)
                                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                    <div class="flex items-center gap-4 h-9 px-3 bg-slate-50 border border-slate-250 rounded-xl shadow-sm">
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="percentage" x-model="forms.edit.brokerage_type" @change="onBrokerageTypeChange('edit')" class="text-primary focus:ring-primary/20">
                                            <span>Percentage (%)</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="fixed" x-model="forms.edit.brokerage_type" @change="onBrokerageTypeChange('edit')" class="text-primary focus:ring-primary/20">
                                            <span>Fixed (₹)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                    <input type="number" step="any" min="0" :max="forms.edit.brokerage_type === 'percentage' ? 100 : null" x-model="forms.edit.brokerage_value" @input="recalculateBrokerage('edit')"
                                           :placeholder="forms.edit.brokerage_type === 'fixed' ? 'Enter fixed amount (₹)' : 'e.g. 2 for 2%'"
                                           class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono shadow-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brokerage Amount</p>
                                    <div class="w-full h-9 px-3 bg-slate-100/90 border border-slate-250 rounded-xl font-bold text-slate-900 font-mono text-xs flex items-center shadow-sm" x-text="'₹' + Number(forms.edit.brokerage_amount || 0).toLocaleString()"></div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Status</label>
                                    <select x-model="forms.edit.brokerage_status"
                                            class="w-full h-9 px-3 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 4 — Dates ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>📅 Agreement & Registration Dates</span>
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreement / Sale Date *</label>
                                <input type="date" x-model="forms.edit.sale_date"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Registration Date</label>
                                <input type="date" x-model="forms.edit.registration_date"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 5 — Initial Payment ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>💼 Initial Payment Details</span>
                        </p>
                        
                        <div class="flex flex-col md:flex-row gap-5 items-start bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5 flex-1 w-full">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Initial Payment Amount & %</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" min="0" x-model="forms.edit.initial_payment_amount" @input="updateInitialPaymentFromAmount('edit', $event)" placeholder="Amount (₹)"
                                               :class="{'border-rose-500 ring-2 ring-rose-500/20 text-rose-700 bg-rose-50/20': forms.edit.total_amount > 0 && (parseFloat(forms.edit.initial_payment_amount) || 0) > parseFloat(forms.edit.total_amount)}"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                    <div>
                                        <input type="number" step="0.01" min="0" max="100" x-model="forms.edit.initial_payment_percentage" @input="updateInitialPaymentFromPercentage('edit', $event)" placeholder="Percentage (%)"
                                               :class="{'border-rose-500 ring-2 ring-rose-500/20 text-rose-700 bg-rose-50/20': (parseFloat(forms.edit.initial_payment_percentage) || 0) > 100}"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                </div>
                                <p x-show="!((parseFloat(forms.edit.total_amount) > 0 && (parseFloat(forms.edit.initial_payment_amount) || 0) > parseFloat(forms.edit.total_amount)) || (parseFloat(forms.edit.initial_payment_percentage) || 0) > 100)"
                                   class="text-[9px] text-slate-400 font-medium">Enter amount or percentage (0 if none)</p>
                                <p x-show="(parseFloat(forms.edit.total_amount) > 0 && (parseFloat(forms.edit.initial_payment_amount) || 0) > parseFloat(forms.edit.total_amount)) || (parseFloat(forms.edit.initial_payment_percentage) || 0) > 100"
                                   class="text-[10px] font-semibold text-rose-600 mt-1 flex items-center gap-1" x-cloak>
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Initial payment cannot exceed Total Contract Value. Please enter a lesser amount.</span>
                                </p>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Mode</label>
                                <select x-model="forms.edit.payment_mode"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Date</label>
                                <input type="date" x-model="forms.edit.initial_payment_date"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div x-show="['Bank Transfer', 'Cheque', 'UPI'].includes(forms.edit.payment_mode)" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 shadow-sm" x-transition>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block" x-text="forms.edit.payment_mode === 'UPI' ? 'UPI Number / Transaction ID' : 'Reference / Cheque No'"></label>
                                <input type="text" x-model="forms.edit.reference_no" :placeholder="forms.edit.payment_mode === 'UPI' ? 'Enter UPI Number or Ref ID' : 'e.g. UTR / Cheque number'"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Bank Name</label>
                                <select x-model="forms.edit.bank_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="">Select Bank Account</option>
                                    <template x-for="bank in bankAccountsList" :key="bank.id">
                                        <option :value="bank.id" x-text="bank.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 6 — Balance & Payment Plan ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>📊 Balance & Payment Plan</span>
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-slate-55/30 border border-slate-200/85 rounded-xl p-4 flex flex-col justify-center shadow-inner">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Remaining Balance</span>
                                <span class="text-xl font-extrabold text-primary mt-1 block font-mono" x-text="'₹' + Number(forms.edit.remaining_balance || 0).toLocaleString()"></span>
                            </div>
                            <div class="space-y-3 md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Plan</label>
                                <div class="flex items-center gap-4 h-9">
                                    <label class="flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                        <input type="radio" value="lump_sum" x-model="forms.edit.payment_plan" class="text-primary focus:ring-primary/20">
                                        <span>Lump Sum (Full payment)</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                        <input type="radio" value="emi" x-model="forms.edit.payment_plan" class="text-primary focus:ring-primary/20">
                                        <span>EMI / Installment Plan</span>
                                    </label>
                                </div>
                                <div x-show="forms.edit.payment_plan === 'emi'" class="mt-4 space-y-4" x-transition>
                                    {{-- Equal Installments Fields --}}
                                    <div class="grid grid-cols-3 gap-3 border border-slate-200/60 p-3 rounded-xl bg-slate-50/50">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">No. of Installments</label>
                                            <input type="number" x-model="forms.edit.emi_installment_count" min="1" placeholder="e.g. 12"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">Frequency</label>
                                            <select x-model="forms.edit.emi_frequency"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">First Installment Date</label>
                                            <input type="date" x-model="forms.edit.first_installment_date"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                                        </div>
                                    </div>
                                    {{-- Live Preview Block --}}
                                    <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-xl p-3 space-y-2">
                                        <p class="text-[9px] font-bold text-indigo-800 uppercase tracking-widest">📝 Live Schedule Preview</p>
                                        <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-[11px] font-semibold text-slate-700">
                                            <template x-for="(preview, pIdx) in getEmiPreview('edit')" :key="pIdx">
                                                <div class="flex justify-between items-center py-1 border-b border-indigo-100/30">
                                                    <span x-text="preview.label"></span>
                                                    <div class="flex gap-4">
                                                        <span class="text-slate-400 font-mono text-[10px]" x-text="preview.due_date"></span>
                                                        <span class="font-bold text-indigo-700 font-mono" x-text="'₹' + Number(preview.amount).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="getEmiPreview('edit').length === 0">
                                                <p class="text-[10px] text-slate-455 italic py-1 text-center">No schedule preview available. Fill EMI parameters.</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 7 — Remarks ── --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            <span>💬 Remarks / Notes</span>
                        </p>
                        <textarea x-model="forms.edit.notes" rows="3" placeholder="Optional remarks or notes..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md shadow-[#a38c29]/20 uppercase transition tracking-wider">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         VIEW SALE MODAL (read-only)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeViewModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Agreement Details</span>
                            <span class="badge-pill text-[9px] whitespace-nowrap" :class="getStatusBadgeClass(activeSale.status)" x-text="activeSale.status"></span>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight truncate break-all" x-text="activeSale.sale_number"></h2>
                    </div>
                    <button @click="closeViewModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            {{-- Scrollable Container --}}
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                {{-- Row 1: Sale Profile --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Project Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Project Name</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.project ? activeSale.project.name : '—'"></strong>
                        </div>
                    </div>
                    {{-- Unit Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Unit & Floor</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.sale_units && activeSale.sale_units.length ? activeSale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (activeSale.unit ? activeSale.unit.door_no + ' — ' + (activeSale.unit.floor ? activeSale.unit.floor.name : '') : '—')"></strong>
                        </div>
                    </div>
                    {{-- Customer Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Customer Details</span>
                            <strong class="text-slate-800 text-xs block mt-1 truncate" x-text="activeSale.customer ? activeSale.customer.name : '—'"></strong>
                            <span class="text-slate-450 text-[10px] block mt-0.5 truncate" x-text="activeSale.customer ? activeSale.customer.phone : ''"></span>
                        </div>
                    </div>
                </div>
                {{-- Multi Unit Details Table --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3" x-show="activeSale.sale_units && activeSale.sale_units.length > 0">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">🏢 Booked Inventory / Units</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[11px] border-collapse min-w-[500px]">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="py-2 px-2">Unit</th>
                                    <th class="py-2 px-2">Floor</th>
                                    <th class="py-2 px-2">Area (Sq.Ft)</th>
                                    <th class="py-2 px-2">Rate/Sq.Ft</th>
                                    <th class="py-2 px-2">GST</th>
                                    <th class="py-2 px-2 text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                <template x-for="su in activeSale.sale_units" :key="su.id">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-2 px-2 font-bold text-indigo-700" x-text="su.unit ? su.unit.door_no : '—'"></td>
                                        <td class="py-2 px-2" x-text="su.unit && su.unit.floor ? su.unit.floor.name : '—'"></td>
                                        <td class="py-2 px-2 font-mono" x-text="Number(su.area_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 font-mono" x-text="'₹' + Number(su.rate_per_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 whitespace-nowrap" x-text="su.gst_type !== 'none' ? '₹' + Number(su.gst_amount).toLocaleString() + ' (' + su.gst_type + ')' : 'None'"></td>
                                        <td class="py-2 px-2 text-right font-mono text-slate-900" x-text="'₹' + Number(su.line_total).toLocaleString()"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 1.5: Extra Works Details --}}
                <template x-if="activeSale.extra_works && activeSale.extra_works.length > 0">
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                        <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">🛠️ Custom Alterations / Extra Work Details</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-455 uppercase tracking-wider border-b border-slate-100">
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                        <th class="px-4 py-3">GST Type</th>
                                        <th class="px-4 py-3">GST (%)</th>
                                        <th class="px-4 py-3 text-right">GST Amount</th>
                                        <th class="px-4 py-3 text-right font-bold text-emerald-800">Total Payable</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                    <template x-for="ew in activeSale.extra_works" :key="ew.id">
                                        <tr class="hover:bg-slate-55/40 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-900" x-text="ew.description"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 uppercase" x-text="ew.gst_type"></td>
                                            <td class="px-4 py-3" x-text="ew.gst_percentage + '%'"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.gst_amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 text-right font-mono text-emerald-800 font-bold" x-text="'₹' + Number(ew.line_total).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
                {{-- Row 2: Financial Summary Card --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💰 Pricing & GST Breakdown</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreed Rate / Sqft</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="activeSale.rate_per_sqft > 0 ? '₹' + Number(activeSale.rate_per_sqft).toLocaleString() : '₹0 (Flat Price)'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Base Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block font-mono" x-text="activeSale.base_amount ? '₹' + Number(activeSale.base_amount).toLocaleString() : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total GST Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block" x-text="activeSale.gst_amount > 0 ? '₹' + Number(activeSale.gst_amount || 0).toLocaleString() : 'None / Excluded'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Contract Value</span>
                            <span class="font-extrabold text-[#a38c29] text-base mt-1 block font-mono" x-text="'₹' + Number(activeSale.total_amount || 0).toLocaleString()"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100 bg-slate-50/50 -mx-5 -mb-5 p-5 rounded-b-xl">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreement Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatDate(activeSale.sale_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Registration Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatDate(activeSale.registration_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Selected Plan</span>
                            <span class="font-extrabold text-indigo-600 mt-1 block uppercase" x-text="activeSale.payment_plan === 'emi' ? 'EMI (' + (activeSale.emi_installment_count || 12) + '-Mo ' + (activeSale.emi_frequency || 'Monthly') + ')' : 'Lump Sum'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Remaining Balance</span>
                            <span class="font-extrabold text-sm mt-1 block font-mono" :class="activeSale.remaining_balance > 0 ? 'text-rose-600' : 'text-emerald-700'" x-text="'₹' + Number(activeSale.remaining_balance || 0).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
                {{-- Row 3: Receipts Ledger --}}
                <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">💳 Collection Receipts History</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-450 uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-3">Receipt No</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Payment Mode</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="r in activeSale.receipts" :key="r.id">
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-4 py-3 font-bold text-slate-900" x-text="'REC-' + String(r.id).padStart(5, '0') + (r.reference_no ? ' (' + r.reference_no + ')' : '')"></td>
                                        <td class="px-4 py-3 text-slate-500" x-text="formatDate(r.receipt_date)"></td>
                                        <td class="px-4 py-3 text-slate-500 uppercase" x-text="r.payment_mode"></td>
                                        <td class="px-4 py-3 text-right font-extrabold text-emerald-700 font-mono" x-text="'₹' + Number(r.amount).toLocaleString()"></td>
                                    </tr>
                                </template>
                                <template x-if="!activeSale.receipts || activeSale.receipts.length === 0">
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-400 italic bg-white">No receipts recorded for this sale.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 4: Broker details --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">💼 Broker & Commission Details</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="activeSale.brokerage">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Broker Name</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="activeSale.broker ? activeSale.broker.name : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Commission Structure</span>
                            <span class="font-bold text-slate-855 mt-1 block" x-text="activeSale.brokerage ? (activeSale.brokerage.commission_type === 'percentage' ? activeSale.brokerage.commission_percent + '% of Sale Price' : 'Fixed Commission') : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Payout Amount / Status</span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="font-extrabold text-slate-900 font-mono" x-text="activeSale.brokerage ? '₹' + Number(activeSale.brokerage.commission_amount).toLocaleString() : '—'"></span>
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider inline-block"
                                      :class="activeSale.brokerage && activeSale.brokerage.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                      x-text="activeSale.brokerage ? activeSale.brokerage.status : ''"></span>
                            </div>
                        </div>
                    </div>
                    <div x-show="!activeSale.brokerage" class="text-slate-400 italic text-[11px] py-1">
                        No broker was associated with this transaction (Direct Sale).
                    </div>
                </div>
                {{-- Row 5: Logs & Remarks --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Transition logs --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm space-y-2.5">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center justify-between">
                            <span>📜 Transition History Logs</span>
                            <span class="text-[9px] font-bold text-slate-400 font-mono" x-text="(activeSale.status_logs ? activeSale.status_logs.length : 0) + ' Logs'"></span>
                        </p>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            <template x-for="log in activeSale.status_logs" :key="log.id">
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 text-[10px] space-y-1.5" x-data="{ openSnap: false }">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-slate-800 uppercase tracking-wide" x-text="(log.from_status || 'created') + ' → ' + log.to_status"></span>
                                            <template x-if="log.snapshot_data">
                                                <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 text-[8px] font-extrabold uppercase">Archived Log</span>
                                            </template>
                                        </div>
                                        <span class="text-slate-400 font-mono text-[9px]" x-text="formatDate(log.created_at)"></span>
                                    </div>
                                    <p class="text-slate-600 italic font-sans" x-text="log.reason || 'No narrative provided'"></p>

                                    {{-- Archived Unit Snapshot Viewer --}}
                                    <template x-if="log.snapshot_data">
                                        <div class="mt-2 pt-2 border-t border-slate-200/80">
                                            <button type="button" @click="openSnap = !openSnap"
                                                    class="text-[9px] font-extrabold text-blue-600 hover:text-blue-800 uppercase tracking-wider flex items-center gap-1 cursor-pointer">
                                                <span x-text="openSnap ? 'Hide Archived Unit Log' : '📦 View Archived Unit Log Snapshot'"></span>
                                                <svg class="w-3 h-3 transition-transform" :class="openSnap ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>

                                            <div x-show="openSnap" class="mt-2 p-2.5 bg-white rounded-lg border border-blue-200 space-y-2 text-[10px] font-sans">
                                                <div class="grid grid-cols-2 gap-2 border-b border-slate-100 pb-1.5">
                                                    <div>
                                                        <span class="text-slate-400 font-bold block text-[8px] uppercase">Old Unit</span>
                                                        <span class="font-extrabold text-slate-800" x-text="log.snapshot_data.old_unit ? log.snapshot_data.old_unit.door_no : 'N/A'"></span>
                                                        <span class="text-slate-500 text-[9px]" x-text="log.snapshot_data.old_unit && log.snapshot_data.old_unit.floor_name ? ' (' + log.snapshot_data.old_unit.floor_name + ')' : ''"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 font-bold block text-[8px] uppercase">Old Sale No</span>
                                                        <span class="font-extrabold text-slate-800 font-mono" x-text="log.snapshot_data.old_sale ? log.snapshot_data.old_sale.sale_number : 'N/A'"></span>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-1 bg-slate-50 p-1.5 rounded-md text-center font-mono">
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Contract Value</span>
                                                        <span class="font-bold text-slate-800" x-text="log.snapshot_data.old_sale ? fmt(log.snapshot_data.old_sale.total_amount) : '₹0'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Total Paid</span>
                                                        <span class="font-bold text-emerald-700" x-text="log.snapshot_data.old_sale ? fmt(log.snapshot_data.old_sale.total_paid) : '₹0'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Balance</span>
                                                        <span class="font-bold text-slate-700" x-text="log.snapshot_data.old_sale ? fmt(log.snapshot_data.old_sale.remaining_balance) : '₹0'"></span>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between items-center text-[9px] text-slate-500 pt-1 font-semibold">
                                                    <span>Receipts Recorded: <strong class="text-slate-800 font-mono" x-text="log.snapshot_data.receipts ? log.snapshot_data.receipts.length : 0"></strong></span>
                                                    <span>EMI Schedule Count: <strong class="text-slate-800 font-mono" x-text="log.snapshot_data.installments ? log.snapshot_data.installments.length : 0"></strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!activeSale.status_logs || activeSale.status_logs.length === 0">
                                <p class="text-xs text-slate-400 italic py-2">No transition history logged for this agreement.</p>
                            </template>
                        </div>
                    </div>
                    {{-- Remarks --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💬 Agreement Notes & Remarks</p>
                        <div class="flex-1 mt-3">
                            <p class="text-slate-650 font-sans text-xs bg-slate-50 p-3 rounded-lg border border-slate-200/80 h-full min-h-[80px]" x-text="activeSale.notes || 'No remarks recorded for this agreement.'"></p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                <a :href="'{{ url('/emi-collections/ledger') }}/' + activeSale.id" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EMI & Collection Ledger</span>
                </a>
                <button @click="closeViewModal()" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Close</button>
            </div>
        </div>
    </div>
    @endif

    </div>

</div>
{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC
═══════════════════════════════════════════ --}}
<script>
function salesApp() {
    return {
        sales: [],
        currentPage: 1,
        perPage: 10,
        filters: { search: '', project_id: '{{ request('project_id') ?: ($projects->first()?->id ?? '') }}', status: '', date_from: '', date_to: '' },
        modals: { add: { open: false }, edit: { open: false }, view: { open: false }, quickCustomer: { open: false } },
        confirmDeleteUnitModal: { open: false, index: null, mode: 'add', doorNo: '', hasEmis: false, hasReceipts: false, isEmiPlan: false },
        availableUnits: { add: [], edit: [] },
        selectedUnit: { add: null, edit: null },
        customerList: {!! json_encode($customers->map(function($c) { return ['id' => $c->id, 'name' => $c->name, 'email' => $c->email, 'phone' => $c->phone]; })) !!},
        brokerList: {!! json_encode($brokers->map(function($b) { return ['id' => $b->id, 'name' => $b->name, 'default_commission_pct' => $b->default_commission_pct ?? null]; })) !!},
        bankAccountsList: {!! json_encode($bankAccounts->map(function($ba) { return ['id' => $ba->id, 'name' => $ba->bank_name]; })) !!},
        quickCustomer: { name: '', email: '', phone: '' },
        quickCustomerErrors: {},
        customerSearch: '',
        customerDropdownOpen: false,
        forms: {
            add: {
                project_id: '{{ request('project_id') ?: ($projects->first()?->id ?? '') }}', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                brokerage_type: 'percentage', brokerage_value: '',
                initial_payment_amount: 0, initial_payment_percentage: '', payment_mode: 'Cash', reference_no: '', bank_id: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', emi_type: 'equal', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(), milestones: [], remaining_balance: 0,
                notes: '',
                units: [],
                extra_works: []
            },
            edit: {
                project_id: '', sale_amount: '', sale_date: '', gst_type: 'none',
                gst_percentage: '', gst_amount: 0, base_amount: '', total_amount: '', notes: '',
                payment_plan: 'lump_sum', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(),
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                brokerage_type: 'percentage', brokerage_value: '', broker_id: '',
                units: [],
                extra_works: []
            }
        },
        activeSale: {},
        statusChange: { pending: false, targetStatus: '', reason: '' },
        errors: {},
        toast: { open: false, message: '', type: 'success' },
        // Return & Exchange State
        returnFilters: { search: '', project_id: '{{ request('project_id') ?: ($projects->first()?->id ?? '') }}', type: '', status: '{{ request('tab') === 'cancellations' ? 'cancelled' : (request('tab') === 'returns' ? 'returned' : '') }}' },
        returnCurrentPage: 1,
        returnPerPage: 10,
        exchangeFilters: { search: '', project_id: '{{ request('project_id') ?: ($projects->first()?->id ?? '') }}', type: '', status: '' },
        exchangeCurrentPage: 1,
        exchangePerPage: 50,
        selectedReturnSale: null,
        targetReturnStatus: '',
        selectedExchangeSale: null,
        returnForm: { date: new Date().toISOString().split('T')[0], cancellation_fee: 100000, reason: '', revert_unsold: true },
        exchangeForm: { new_project_id: '{{ request('project_id') ?: ($projects->first()?->id ?? '') }}', new_unit_type: '', new_unit_id: '', new_unit_value: 0, equity_applied: 0, carry_forward: true, reason: '', payment_plan: 'emi', emi_type: 'equal', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (function() { const d = new Date(); d.setMonth(d.getMonth() + 1); return d.toISOString().split('T')[0]; })() },
        exchangeAvailableUnits: [],
        exchangeUnitTypes: [],
        exchangeSelectedUnit: null,
        openNewReturnModal: false,
        newReturnStep: 1,
        newReturnSaleId: '',
        newReturnSale: null,
        isEditReturn: false,
        isCancellationTab: {{ request('tab') === 'cancellations' ? 'true' : 'false' }},
        openNewExchangeModal: false,
        newExchangeStep: 1,
        newExchangeSaleId: '',
        openViewExchangeModal: false,
        viewExchangeSale: null,
        init() {
            this.fetchSales();
            this.$watch('returnFilters', () => {
                this.returnCurrentPage = 1;
            }, { deep: true });
            this.$watch('exchangeFilters', () => {
                this.exchangeCurrentPage = 1;
            }, { deep: true });
            const urlParams = new URLSearchParams(window.location.search);
            const viewSaleId = urlParams.get('view_sale_id');
            if (viewSaleId) {
                this.openViewModal(viewSaleId);
            }
            const autoSaleId = urlParams.get('sale_id');
            if (autoSaleId && urlParams.get('tab') === 'exchange') {
                let checkExist = setInterval(() => {
                    if (this.sales && this.sales.length > 0) {
                        const foundSale = this.sales.find(s => s.id == autoSaleId);
                        if (foundSale) {
                            this.selectExchangeSale(foundSale);
                            clearInterval(checkExist);
                        }
                    }
                }, 100);
                setTimeout(() => clearInterval(checkExist), 3000);
            }
        },
        fmt(value) {
            return '₹' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        formatDate(val) {
            if (!val) return '—';
            try {
                const clean = val.replace('Z', '').split('T')[0];
                const parts = clean.split('-');
                if (parts.length === 3) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const yr = parts[0];
                    const mo = months[parseInt(parts[1], 10) - 1];
                    const dy = parts[2];
                    return `${dy} ${mo} ${yr}`;
                }
                return clean;
            } catch(e) {
                return val.split('T')[0];
            }
        },
        fetchSales() {
            let params = new URLSearchParams();
            params.append('tab', '{{ request('tab') }}');
            Object.entries(this.filters).forEach(([key, val]) => { if (val) params.append(key, val); });
            fetch('{{ route('sales.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { 
                this.sales = data.sales; 
                this.currentPage = 1;
                this.$nextTick(() => {
                    this.renderExchangeChart();
                });
            })
            .catch(err => { console.error(err); this.showToast('Failed to fetch sales.', 'error'); });
        },
        paginatedSales() {
            const perPage = this.perPage || 10;
            const currentPage = this.currentPage || 1;
            const start = (currentPage - 1) * perPage;
            return (this.sales || []).slice(start, start + perPage);
        },
        getTotalPages() {
            const perPage = this.perPage || 10;
            return Math.ceil((this.sales || []).length / perPage) || 1;
        },
        getPageNumbers() {
            const totalPages = this.getTotalPages();
            const current = this.currentPage;
            const pages = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                } else if (current >= totalPages - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = totalPages - 4; i <= totalPages; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                }
            }
            return pages;
        },
        resetFilters() {
            this.filters = { search: '', project_id: '', status: '', date_from: '', date_to: '' };
            this.fetchSales();
        },
        getStatusBadgeClass(status) {
            switch (status) {
                case 'active': return 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                case 'cancelled': return 'bg-rose-50 text-rose-700 border border-rose-100';
                case 'returned': return 'bg-amber-50 text-amber-700 border border-amber-100';
                case 'exchanged': return 'bg-blue-50 text-blue-700 border border-blue-105';
                case 'resale': return 'bg-primary-50 text-primary-700 border border-primary-100';
                default: return 'bg-slate-50 text-slate-700 border border-slate-200';
            }
        },
        showToast(message, type = 'success') {
            this.toast = { open: true, message, type };
            setTimeout(() => { this.toast.open = false; }, 3000);
        },
        getPaidTillDate(sale) {
            if (!sale) return 0;
            if (sale.status === 'exchanged') {
                const snap = this.getExchangeSnapshot(sale);
                if (snap && snap.old_sale && snap.old_sale.total_paid !== undefined && snap.old_sale.total_paid !== null) {
                    return Number(snap.old_sale.total_paid);
                }
            }
            const receiptsSum = sale.receipts ? sale.receipts.filter(r => !r.partner_id).reduce((sum, r) => sum + Number(r.amount), 0) : 0;
            return receiptsSum;
        },
        selectReturnSale(sale, targetStatus) {
            this.selectedReturnSale = sale;
            this.targetReturnStatus = targetStatus;
            this.returnForm.cancellation_fee = 100000;
            this.returnForm.reason = sale.cancellation_reason || '';
            this.returnForm.revert_unsold = true;
        },
        calculateApprovedRefund(sale) {
            const paid = this.getPaidTillDate(sale);
            return Math.max(0, paid - (Number(this.returnForm.cancellation_fee) || 0));
        },
        submitReturnRefund() {
            if (!this.returnForm.reason) {
                this.showToast('Reason is required.', 'error');
                return;
            }
            const approvedRefund = this.calculateApprovedRefund(this.selectedReturnSale);
            fetch(`{{ url('sales') }}/${this.selectedReturnSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: this.targetReturnStatus,
                    reason: this.returnForm.reason,
                    cancellation_fee: this.returnForm.cancellation_fee,
                    refund_amount: approvedRefund,
                    revert_unsold: this.returnForm.revert_unsold
                })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process.', 'error');
                } else {
                    this.showToast(this.targetReturnStatus === 'cancelled' ? 'Sale cancelled successfully.' : 'Sales return processed successfully.');
                    this.selectedReturnSale = null;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        getRefundPaid(sale) {
            if (!sale) return 0;
            return Number(sale.refund_paid || 0);
        },
        getRefundDue(sale) {
            if (!sale) return 0;
            const paidIntake = this.getPaidTillDate(sale);
            const fee = Number(sale.cancellation_fee || 0);
            return Math.max(0, paidIntake - fee);
        },
        getRemainingRefund(sale) {
            if (!sale) return 0;
            const due = this.getRefundDue(sale);
            const paid = this.getRefundPaid(sale);
            return Math.max(0, due - paid);
        },
        getRefundStatus(sale) {
            if (!sale) return 'Pending';
            const due = this.getRefundDue(sale);
            const paid = this.getRefundPaid(sale);
            if (due <= 0) return 'Completed';
            if (paid >= due - 0.01) return 'Completed';
            if (paid > 0) return 'Partially Refunded';
            return 'Pending';
        },
        getReturnStats() {
            let salesList = this.sales.filter(s => s.status === 'cancelled' || s.status === 'returned');
            if (this.returnFilters && this.returnFilters.project_id) {
                salesList = salesList.filter(s => s.project_id == this.returnFilters.project_id);
            }
            let totalReturns = salesList.length;
            let returnAmount = salesList.reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
            let payableToCustomer = 0;
            let receivableFromCustomer = 0;
            salesList.forEach(s => {
                let remaining = this.getRemainingRefund(s);
                let paidIntake = this.getPaidTillDate(s);
                let fee = parseFloat(s.cancellation_fee || 0);
                if (s.status === 'returned') {
                    payableToCustomer += remaining;
                    receivableFromCustomer += fee;
                } else {
                    if (paidIntake > fee) {
                        payableToCustomer += remaining;
                    } else {
                        receivableFromCustomer += (fee - paidIntake);
                    }
                }
            });
            return { totalReturns, returnAmount, payableToCustomer, receivableFromCustomer };
        },
        getExchangeStats() {
            let salesList = this.sales.filter(s => s.status === 'exchanged');
            if (this.exchangeFilters && this.exchangeFilters.project_id) {
                salesList = salesList.filter(s => s.project_id == this.exchangeFilters.project_id);
            }
            let totalExchanges = salesList.length;
            let totalDiff = 0;
            let payableByCustomer = 0;
            let refundableToCustomer = 0;
            let completedExchanges = salesList.filter(s => s.status === 'exchanged').length;
            salesList.forEach(sale => {
                const netDue = this.getExchangeNetDue(sale);
                totalDiff += Math.abs(netDue);
                if (netDue > 0) {
                    payableByCustomer += netDue;
                } else if (netDue < 0) {
                    refundableToCustomer += Math.abs(netDue);
                }
            });
            return { totalExchanges, totalDiff, payableByCustomer, refundableToCustomer, completedExchanges };
        },
        getNewUnitDoorNo(sale) {
            if (sale.status !== 'exchanged') return '—';
            const newSale = this.sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + sale.sale_number));
            return newSale && newSale.unit ? newSale.unit.door_no : '—';
        },
        getNewUnitValue(sale) {
            if (sale.status !== 'exchanged') return 0;
            const newSale = this.sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + sale.sale_number));
            return newSale ? parseFloat(newSale.total_amount) : 0;
        },
        getExchangeNetDue(sale) {
            if (!sale || sale.status !== 'exchanged') return 0;
            const newVal = this.getNewUnitValue(sale);
            const paid = this.getPaidTillDate(sale);
            return Math.round((newVal - paid) * 100) / 100;
        },
        getExchangeStatusText(sale) {
            if (!sale || sale.status !== 'exchanged') return '—';
            const netDue = this.getExchangeNetDue(sale);
            if (netDue > 0) return 'Payable by Customer';
            if (netDue < 0) return 'Refundable to Customer';
            return 'Fully Settled';
        },
        getDifferenceAmount(sale) {
            if (!sale || sale.status !== 'exchanged') return 0;
            return Math.abs(this.getExchangeNetDue(sale));
        },
        fmtIndian(value) {
            let num = Number(value || 0);
            if (num >= 10000000) {
                return '₹' + (num / 10000000).toFixed(2) + ' Cr';
            } else if (num >= 100000) {
                return '₹' + (num / 100000).toFixed(2) + ' L';
            }
            return '₹' + num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        selectNewReturnSale() {
            let sale = this.sales.find(s => s.id == this.newReturnSaleId);
            if (!sale) {
                this.showToast('Please select a sale first.', 'error');
                return;
            }
            this.newReturnSale = sale;
            this.newReturnStep = 2;
            this.returnForm.date = new Date().toISOString().split('T')[0];
            this.returnForm.cancellation_fee = 100000;
            this.returnForm.reason = '';
            this.returnForm.revert_unsold = true;
        },
        selectNewExchangeSale() {
            let sale = this.sales.find(s => s.id == this.newExchangeSaleId);
            if (!sale) {
                this.showToast('Please select a sale first.', 'error');
                return;
            }
            this.selectExchangeSale(sale);
            this.newExchangeStep = 2;
        },
        submitNewReturn() {
            if (!this.returnForm.reason) {
                this.showToast('Reason is required.', 'error');
                return;
            }
            const approvedRefund = this.getPaidTillDate(this.newReturnSale) - (Number(this.returnForm.cancellation_fee) || 0);
            fetch(`{{ url('sales') }}/${this.newReturnSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'cancelled',
                    reason: this.returnForm.reason,
                    cancellation_fee: this.returnForm.cancellation_fee,
                    refund_amount: Math.max(0, approvedRefund),
                    revert_unsold: this.returnForm.revert_unsold
                })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process.', 'error');
                } else {
                    this.showToast('Sales return/cancellation processed successfully.');
                    this.openNewReturnModal = false;
                    this.newReturnSaleId = '';
                    this.newReturnSale = null;
                    this.newReturnStep = 1;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        selectExchangeSale(sale) {
            this.selectedExchangeSale = sale;
            this.exchangeForm.new_project_id = sale.project_id || '';
            this.exchangeForm.new_unit_type = '';
            this.exchangeForm.new_unit_id = '';
            this.exchangeForm.new_unit_value = 0;
            this.exchangeForm.equity_applied = this.getPaidTillDate(sale);
            this.exchangeForm.carry_forward = true;
            this.exchangeForm.reason = '';
            this.exchangeForm.payment_plan = sale.payment_plan || 'emi';
            this.exchangeForm.emi_type = sale.emi_type || 'equal';
            this.exchangeForm.emi_installment_count = sale.emi_installment_count || 12;
            this.exchangeForm.emi_frequency = sale.emi_frequency || 'monthly';
            const defaultDate = new Date();
            defaultDate.setMonth(defaultDate.getMonth() + 1);
            this.exchangeForm.first_installment_date = sale.first_installment_date ? (sale.first_installment_date.split('T')[0]) : defaultDate.toISOString().split('T')[0];
            this.exchangeAvailableUnits = [];
            this.exchangeSelectedUnit = null;
            if (sale.project_id) {
                this.loadExchangeUnits();
            }
        },
        getExchangeSnapshot(sale) {
            if (!sale || !sale.status_logs) return null;
            const log = sale.status_logs.find(l => l.snapshot_data && (l.event_type === 'exchanged' || l.event_type === 'created'));
            return log ? log.snapshot_data : null;
        },
        getExchangeEmiPreview() {
            if (this.exchangeForm.payment_plan !== 'emi') return [];
            const newVal = parseFloat(this.exchangeForm.new_unit_value || 0);
            const equity = this.exchangeForm.carry_forward ? parseFloat(this.exchangeForm.equity_applied || 0) : 0;
            const netDue = Math.max(0, Math.round((newVal - equity) * 100) / 100);
            const count = parseInt(this.exchangeForm.emi_installment_count) || 1;
            if (count <= 0 || netDue <= 0) return [];
            const emiAmt = Math.floor((netDue / count) * 100) / 100;
            const lastEmiAmt = Math.round((netDue - (emiAmt * (count - 1))) * 100) / 100;
            
            const list = [];
            let baseDateStr = this.exchangeForm.first_installment_date || new Date().toISOString().split('T')[0];
            let baseDate = new Date(baseDateStr + 'T00:00:00');
            if (isNaN(baseDate.getTime())) baseDate = new Date();

            for (let i = 1; i <= count; i++) {
                let dueDate = new Date(baseDate);
                if (i > 1) {
                    if (this.exchangeForm.emi_frequency === 'quarterly') {
                        dueDate.setMonth(dueDate.getMonth() + (i - 1) * 3);
                    } else {
                        dueDate.setMonth(dueDate.getMonth() + (i - 1));
                    }
                }
                const year = dueDate.getFullYear();
                const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                const day = String(dueDate.getDate()).padStart(2, '0');
                list.push({
                    installment_no: i,
                    label: 'EMI ' + i,
                    due_date: `${year}-${month}-${day}`,
                    amount: i === count ? lastEmiAmt : emiAmt
                });
            }
            return list;
        },
        getFilteredExchangeAvailableUnits() {
            if (!this.exchangeForm.new_project_id) return [];
            let units = this.exchangeAvailableUnits;
            if (this.exchangeForm.new_unit_type) {
                const typeId = this.exchangeForm.new_unit_type;
                units = units.filter(unit => unit.unit_type_id == typeId);
            }
            return units;
        },
        loadExchangeUnits() {
            const projId = this.exchangeForm.new_project_id;
            this.exchangeAvailableUnits = [];
            this.exchangeUnitTypes = [];
            this.exchangeForm.new_unit_type = '';
            this.exchangeForm.new_unit_id = '';
            this.exchangeForm.new_unit_value = 0;
            if (!projId) return;
            fetch(`{{ url('sales/available-units') }}/${projId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { 
                this.exchangeAvailableUnits = data.units || [];
                this.exchangeUnitTypes = data.unitTypes || [];
            })
            .catch(err => console.error(err));
        },
        onExchangeUnitSelect() {
            const unit = this.exchangeAvailableUnits.find(u => u.id == this.exchangeForm.new_unit_id);
            this.exchangeSelectedUnit = unit;
            if (unit) {
                let base = parseFloat(unit.expected_sale_amount) || 0;
                let gstType = this.selectedExchangeSale.gst_type || 'none';
                let total = base;
                if (gstType === 'exclusive') {
                    total = Math.round(base * 1.18 * 100) / 100;
                }
                this.exchangeForm.new_unit_value = total;
            } else {
                this.exchangeForm.new_unit_value = 0;
            }
        },
        calculateDifferentialDue() {
            return Math.round((parseFloat(this.exchangeForm.new_unit_value || 0) - parseFloat(this.exchangeForm.equity_applied || 0)) * 100) / 100;
        },
        submitExchangePlan() {
            this.errors = {};
            let hasError = false;

            if (!this.exchangeForm.new_project_id) {
                this.errors.new_project_id = ['Please select a target project.'];
                hasError = true;
            }
            if (!this.exchangeForm.new_unit_id) {
                this.errors.new_unit_id = ['Please select a target available unit.'];
                hasError = true;
            }
            if (!this.exchangeForm.reason) {
                this.errors.reason = ['Please enter exchange reason / notes.'];
                hasError = true;
            }

            if (hasError) {
                this.showToast('Please fill all required fields highlighted below.', 'error');
                this.$nextTick(() => {
                    if (this.$refs.exchangeModalScroll) {
                        this.$refs.exchangeModalScroll.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                    const firstErrInput = document.querySelector('[x-ref="exchangeModalScroll"] .border-rose-500');
                    if (firstErrInput) {
                        firstErrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (typeof firstErrInput.focus === 'function') firstErrInput.focus();
                    }
                });
                return;
            }

            fetch(`{{ url('sales') }}/${this.selectedExchangeSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'exchanged',
                    new_unit_id: this.exchangeForm.new_unit_id,
                    carry_forward: this.exchangeForm.carry_forward,
                    reason: this.exchangeForm.reason,
                    payment_plan: this.exchangeForm.payment_plan,
                    emi_type: this.exchangeForm.emi_type,
                    emi_installment_count: this.exchangeForm.emi_installment_count,
                    emi_frequency: this.exchangeForm.emi_frequency,
                    first_installment_date: this.exchangeForm.first_installment_date,
                })
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                    this.showToast('Please resolve validation errors.', 'error');
                    this.$nextTick(() => {
                        if (this.$refs.exchangeModalScroll) {
                            this.$refs.exchangeModalScroll.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                        const firstErrInput = document.querySelector('[x-ref="exchangeModalScroll"] .border-rose-500');
                        if (firstErrInput) {
                            firstErrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            if (typeof firstErrInput.focus === 'function') firstErrInput.focus();
                        }
                    });
                }
                else if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process exchange.', 'error');
                } else {
                    this.showToast('Unit exchange processed successfully.');
                    this.selectedExchangeSale = null;
                    this.openNewExchangeModal = false;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        filteredReturnSales() {
            return this.sales.filter(sale => {
                if (sale.status === 'exchanged') return false;
                if (this.returnFilters.search) {
                    const q = this.returnFilters.search.toLowerCase();
                    const cust = sale.customer ? sale.customer.name.toLowerCase() : '';
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const num = sale.sale_number ? sale.sale_number.toLowerCase() : '';
                    if (!cust.includes(q) && !door.includes(q) && !num.includes(q)) return false;
                }
                if (this.returnFilters.project_id && sale.project_id != this.returnFilters.project_id) return false;
                if (this.returnFilters.type) {
                    const filterVal = String(this.returnFilters.type);
                    const unitTypeId = sale.unit ? (sale.unit.unit_type_id || (sale.unit.unit_type ? sale.unit.unit_type.id : null)) : null;
                    const unitTypeName = sale.unit && sale.unit.unit_type ? (sale.unit.unit_type.name || '').toLowerCase() : '';
                    
                    let isMatch = (unitTypeId && String(unitTypeId) === filterVal) ||
                                  (unitTypeName && unitTypeName === filterVal.toLowerCase());

                    if (!isMatch && sale.sale_units && sale.sale_units.length) {
                        isMatch = sale.sale_units.some(su => {
                            const suTypeId = su.unit ? (su.unit.unit_type_id || (su.unit.unit_type ? su.unit.unit_type.id : null)) : null;
                            const suTypeName = su.unit && su.unit.unit_type ? (su.unit.unit_type.name || '').toLowerCase() : '';
                            return (suTypeId && String(suTypeId) === filterVal) ||
                                   (suTypeName && suTypeName === filterVal.toLowerCase());
                        });
                    }

                    if (!isMatch) {
                        const door = sale.unit ? (sale.unit.door_no || '').toLowerCase() : '';
                        const fLower = filterVal.toLowerCase();
                        if (fLower === 'flat' && !door.includes('shop') && !door.includes('office') && !door.includes('comm')) isMatch = true;
                        if (fLower === 'shop' && (door.includes('shop') || door.includes('office') || door.includes('comm'))) isMatch = true;
                    }

                    if (!isMatch) return false;
                }
                if (this.returnFilters.status) {
                    if (sale.status !== this.returnFilters.status) return false;
                } else {
                    const allowed = ('{{ request('tab') }}' === 'sale-return' || '{{ request('tab') }}' === 'returns') ? ['cancelled', 'returned'] : ['active', 'cancelled', 'returned'];
                    if (!allowed.includes(sale.status)) return false;
                }
                return true;
            });
        },
        paginatedReturnSales() {
            const filtered = this.filteredReturnSales();
            const start = (this.returnCurrentPage - 1) * this.returnPerPage;
            return filtered.slice(start, start + this.returnPerPage);
        },
        getReturnTotalPages() {
            return Math.ceil(this.filteredReturnSales().length / this.returnPerPage) || 1;
        },
        getReturnPageNumbers() {
            const totalPages = this.getReturnTotalPages();
            const current = this.returnCurrentPage;
            const pages = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                } else if (current >= totalPages - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = totalPages - 4; i <= totalPages; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                }
            }
            return pages;
        },
        filteredExchangeSales() {
            return this.sales.filter(sale => {
                // Only single-unit sales are eligible for exchange
                if (sale.sale_units && sale.sale_units.length > 1) return false;

                if (this.exchangeFilters.search) {
                    const q = this.exchangeFilters.search.toLowerCase();
                    const cust = sale.customer ? sale.customer.name.toLowerCase() : '';
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const num = sale.sale_number.toLowerCase();
                    if (!cust.includes(q) && !door.includes(q) && !num.includes(q)) return false;
                }
                if (this.exchangeFilters.project_id && sale.project_id != this.exchangeFilters.project_id) return false;
                if (this.exchangeFilters.type) {
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const type = this.exchangeFilters.type.toLowerCase();
                    if (type === 'flat' && (door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                    if (type === 'shop' && !(door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                }
                if (this.exchangeFilters.status) {
                    if (sale.status !== this.exchangeFilters.status) return false;
                } else {
                    if (sale.status !== 'exchanged') return false;
                }
                return true;
            });
        },
        paginatedExchangeSales() {
            const filtered = this.filteredExchangeSales();
            const start = (this.exchangeCurrentPage - 1) * this.exchangePerPage;
            return filtered.slice(start, start + this.exchangePerPage);
        },
        getExchangeTotalPages() {
            return Math.ceil(this.filteredExchangeSales().length / this.exchangePerPage) || 1;
        },
        getExchangePageNumbers() {
            const totalPages = this.getExchangeTotalPages();
            const current = this.exchangeCurrentPage;
            const pages = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                } else if (current >= totalPages - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = totalPages - 4; i <= totalPages; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(totalPages);
                }
            }
            return pages;
        },
        renderExchangeChart() {
            const chartEl = document.querySelector("#returnsExchangesChart");
            if (!chartEl) return;
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const returnsData = Array(12).fill(0);
            const exchangesData = Array(12).fill(0);
            this.sales.forEach(sale => {
                if (!sale.sale_date) return;
                const date = new Date(sale.sale_date);
                const m = date.getMonth();
                if (sale.status === 'returned' || sale.status === 'cancelled') {
                    returnsData[m]++;
                } else if (sale.status === 'exchanged') {
                    exchangesData[m]++;
                }
            });
            const sumReturns = returnsData.reduce((a,b)=>a+b, 0);
            const sumExchanges = exchangesData.reduce((a,b)=>a+b, 0);
            if (sumReturns === 0 && sumExchanges === 0) {
                returnsData[5] = 2; returnsData[6] = 5; returnsData[7] = 4; returnsData[8] = 3;
                exchangesData[5] = 4; exchangesData[6] = 8; exchangesData[7] = 6; exchangesData[8] = 5;
            }
            const options = {
                series: [
                    { name: 'Returns', data: returnsData },
                    { name: 'Exchanges', data: exchangesData }
                ],
                chart: { type: 'bar', height: 180, toolbar: { show: false } },
                colors: ['#3b82f6', '#f97316'],
                plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 3 } },
                dataLabels: { enabled: false },
                xaxis: { categories: months },
                yaxis: { title: { text: 'Count' } },
                fill: { opacity: 0.95 },
                legend: { position: 'top', horizontalAlign: 'right' }
            };
            chartEl.innerHTML = '';
            const chart = new ApexCharts(chartEl, options);
            chart.render();
        },
        getEmiPreview(mode = 'add') {
            const preview = [];
            const remaining = parseFloat(this.forms[mode].remaining_balance) || 0;
            if (remaining <= 0 || this.forms[mode].payment_plan !== 'emi') {
                return preview;
            }
            const count = parseInt(this.forms[mode].emi_installment_count) || 0;
            if (count <= 0) return preview;
            const emiAmt = Math.round((remaining / count) * 100) / 100;
            const freq = this.forms[mode].emi_frequency || 'monthly';
            const firstDate = this.forms[mode].first_installment_date ? new Date(this.forms[mode].first_installment_date) : new Date();
            for (let i = 1; i <= count; i++) {
                const d = new Date(firstDate);
                if (i > 1) {
                    if (freq === 'quarterly') {
                        d.setMonth(d.getMonth() + (i - 1) * 3);
                    } else {
                        d.setMonth(d.getMonth() + (i - 1));
                    }
                }
                const amt = (i === count) ? (Math.round((remaining - (emiAmt * (count - 1))) * 100) / 100) : emiAmt;
                preview.push({
                    label: `EMI ${i}`,
                    due_date: d.toISOString().split('T')[0],
                    amount: amt
                });
            }
            return preview;
        },
        addUnitRow(mode = 'add') {
            this.forms[mode].units.push({
                id: null,
                unit_id: '', wing: '', rate_per_sqft: '', sale_rate_per_sqft: '', sale_amount: '', gst_type: 'exclusive', gst_percentage: '',
                gst_amount: 0, base_amount: 0, total_amount: 0
            });
            this.recalculateAllTotals(mode);
        },
        removeUnitRow(index, mode = 'add') {
            if (this.forms[mode].units.length <= 1) {
                this.showToast('At least one unit row is required in the sale contract.', 'error');
                return;
            }

            const row = this.forms[mode].units[index];
            const isExistingSavedUnit = mode === 'edit' && row && row.id && (this.activeSale?.sale_units || []).some(su => su.id == row.id);

            // If it's a newly added unsaved row (e.g. user clicked + ADD UNIT ROW), remove directly without popup
            if (!isExistingSavedUnit) {
                this.forms[mode].units.splice(index, 1);
                this.recalculateAllTotals(mode);
                return;
            }

            // For existing saved units in edit mode, show the custom warning confirmation modal
            let doorNo = 'Unit #' + (index + 1);
            if (row && row.unit_id) {
                const u = (this.availableUnits[mode] || []).find(unit => unit.id == row.unit_id);
                if (u && u.door_no) {
                    doorNo = u.door_no;
                } else if (mode === 'edit' && this.activeSale) {
                    const activeSU = (this.activeSale.sale_units || []).find(su => su.unit_id == row.unit_id);
                    doorNo = activeSU?.unit?.door_no || this.activeSale.unit?.door_no || doorNo;
                }
            }

            this.confirmDeleteUnitModal = {
                open: true,
                index: index,
                mode: mode,
                doorNo: doorNo,
                hasEmis: (mode === 'edit' && this.activeSale && this.activeSale.emis && this.activeSale.emis.length > 0),
                hasReceipts: (mode === 'edit' && this.activeSale && this.activeSale.receipts && this.activeSale.receipts.length > 0),
                isEmiPlan: (mode === 'edit' && this.forms.edit.payment_plan === 'emi')
            };
        },
        confirmExecuteDeleteUnit() {
            const { index, mode } = this.confirmDeleteUnitModal;
            if (index !== null && index !== undefined && this.forms[mode]?.units) {
                this.forms[mode].units.splice(index, 1);
                this.recalculateAllTotals(mode);
                if (mode === 'edit') {
                    this.showToast('Unit row removed. Please review contract totals & EMI schedule.', 'warning');
                } else {
                    this.showToast('Unit row removed.', 'info');
                }
            }
            this.confirmDeleteUnitModal.open = false;
        },
        isRowParking(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            if (!row || !row.unit_id) return false;
            const unit = (this.availableUnits[mode] || []).find(u => u.id == row.unit_id);
            if (!unit) {
                if (mode === 'edit' && this.activeSale) {
                    const activeSU = (this.activeSale.sale_units || []).find(su => su.unit_id == row.unit_id);
                    const uObj = activeSU?.unit || (this.activeSale.unit_id == row.unit_id ? this.activeSale.unit : null);
                    if (uObj) {
                        return (uObj.unit_type?.name || '').toLowerCase() === 'parking'
                            || (uObj.unit_type?.category || '').toLowerCase() === 'parking';
                    }
                }
                return false;
            }
            return (unit.unit_type_name || '').toLowerCase() === 'parking'
                || (unit.unit_type_category || '').toLowerCase() === 'parking';
        },
        onRowUnitSelect(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            if (unit) {
                const isParking = this.isRowParking(index, mode);
                if (isParking) {
                    row.rate_per_sqft = 0;
                    row.sale_rate_per_sqft = 0;
                    row.expected_sale_amount = unit.expected_sale_amount || '';
                    row.sale_amount = unit.expected_sale_amount || '';
                    this.recalculateRowGst(index, mode);
                } else {
                    row.rate_per_sqft = unit.expected_rate_per_sqft || '';
                    row.sale_rate_per_sqft = unit.expected_rate_per_sqft || '';
                    row.expected_sale_amount = unit.expected_sale_amount || '';
                    
                    const area = parseFloat(unit.built_up_area) || 0;
                    const rate = parseFloat(row.sale_rate_per_sqft) || 0;
                    if (rate > 0 && area > 0) {
                        row.sale_amount = Math.round(rate * area * 100) / 100;
                    } else if (unit.expected_sale_amount) {
                        row.sale_amount = parseFloat(unit.expected_sale_amount) || '';
                        if (area > 0 && row.sale_amount) {
                            row.sale_rate_per_sqft = Math.round((row.sale_amount / area) * 100) / 100;
                        }
                    }
                    this.recalculateRowGst(index, mode);
                }
            } else {
                // Cleared selection — reset row fields
                row.rate_per_sqft = '';
                row.sale_rate_per_sqft = '';
                row.expected_sale_amount = '';
                row.sale_amount   = '';
                row.gst_amount    = 0;
                row.base_amount   = 0;
                row.total_amount  = 0;
                this.recalculateAllTotals(mode);
            }
        },
        onGetRowArea(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            if (!row || !row.unit_id) return '—';
            const unit = (this.availableUnits[mode] || []).find(u => u.id == row.unit_id);
            if (!unit) {
                if (mode === 'edit' && this.activeSale) {
                    const activeSU = (this.activeSale.sale_units || []).find(su => su.unit_id == row.unit_id);
                    const uObj = activeSU?.unit || (this.activeSale.unit_id == row.unit_id ? this.activeSale.unit : null);
                    if (uObj) {
                        return uObj.built_up_area || '—';
                    }
                }
                return '—';
            }
            return unit.built_up_area || '—';
        },
        getFilteredUnits(mode, search = '') {
            const s = (search || '').toLowerCase().trim();
            const unitsList = this.availableUnits[mode] || [];
            if (!s) return unitsList;

            const sClean = s.replace(/[\s\-_]+/g, '');
            const searchWords = s.split(/\s+/).filter(Boolean);

            return unitsList.filter(u => {
                const doorNo = (u.door_no || '').toLowerCase();
                const doorNoClean = doorNo.replace(/[\s\-_]+/g, '');
                const floorName = (u.floor_name || '').toLowerCase();
                const floorNameClean = floorName.replace(/[\s\-_]+/g, '');
                const typeName = (u.unit_type_name || '').toLowerCase();
                const category = (u.unit_type_category || '').toLowerCase();

                // 1. Direct clean match on door number (e.g., 'g 2' matches 'G 2', 'G-2', 'G2')
                if (sClean && doorNoClean.includes(sClean)) return true;
                if (doorNo.includes(s)) return true;

                // 2. Direct clean match on floor name
                if (sClean && floorNameClean.includes(sClean)) return true;
                if (floorName.includes(s)) return true;

                // 3. Multi-word search: each search word must match
                return searchWords.every(word => {
                    const wClean = word.replace(/[\s\-_]+/g, '');
                    if (!wClean) return true;

                    // Short words (<= 2 chars like 'g' or '2') match only against door_no or floor_name to prevent false matches inside 'parking'
                    if (word.length <= 2) {
                        return doorNoClean.includes(wClean) || doorNo.includes(word) || floorNameClean.includes(wClean) || floorName.includes(word);
                    }

                    const combined = `${doorNo} ${floorName} ${typeName} ${category}`;
                    return combined.includes(word);
                });
            });
        },
        getFloorGroups(mode, search = '') {
            const filtered = this.getFilteredUnits(mode, search);
            const groups = [];
            const map = {};
            filtered.forEach(u => {
                const key = u.floor_name || 'Other';
                if (!map[key]) {
                    map[key] = { floor: key, units: [] };
                    groups.push(map[key]);
                }
                map[key].units.push(u);
            });
            return groups;
        },
        onRowRateChange(index, mode = 'add') {
            // Expected Rate change handler (if user explicitly modifies Expected Rate)
            const row = this.forms[mode].units[index];
            if (!row.sale_rate_per_sqft) {
                row.sale_rate_per_sqft = row.rate_per_sqft;
                this.onRowSaleRateChange(index, mode);
            }
        },
        onRowSaleRateChange(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            const rate = parseFloat(row.sale_rate_per_sqft) || 0;
            const area = unit ? parseFloat(unit.built_up_area) || 0 : 0;
            row.sale_amount = Math.round(rate * area * 100) / 100;
            this.recalculateRowGst(index, mode);
        },
        onRowSaleAmountChange(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            const amount = parseFloat(row.sale_amount) || 0;
            const area = unit ? parseFloat(unit.built_up_area) || 0 : 0;
            if (area > 0) {
                row.sale_rate_per_sqft = Math.round((amount / area) * 100) / 100;
            }
            this.recalculateRowGst(index, mode);
        },
        recalculateRowGst(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const entered = parseFloat(row.sale_amount) || 0;
            const pctStr = (row.gst_percentage !== null && row.gst_percentage !== undefined) ? String(row.gst_percentage).trim() : '';
            const pct = parseFloat(row.gst_percentage) || 0;

            if (pctStr !== '' && pct > 0 && entered > 0) {
                const gst = Math.round(entered * (pct / 100) * 100) / 100;
                row.base_amount = entered;
                row.gst_amount = gst;
                row.total_amount = Math.round((entered + gst) * 100) / 100;
                row.gst_type = 'exclusive';
            } else {
                row.base_amount = entered;
                row.gst_percentage = (pctStr === '' || pct === 0) ? '' : row.gst_percentage;
                row.gst_amount = (pctStr === '' || pct === 0) ? '' : (row.gst_amount || '');
                row.total_amount = Math.round((entered + (parseFloat(row.gst_amount) || 0)) * 100) / 100;
                row.gst_type = (row.gst_amount && parseFloat(row.gst_amount) > 0) ? 'exclusive' : 'none';
            }
            this.recalculateAllTotals(mode);
        },
        recalculateRowGstFromAmount(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const entered = parseFloat(row.sale_amount) || 0;
            const gstAmtStr = (row.gst_amount !== null && row.gst_amount !== undefined) ? String(row.gst_amount).trim() : '';
            const gstAmt = parseFloat(row.gst_amount) || 0;

            if (gstAmtStr !== '' && gstAmt > 0 && entered > 0) {
                row.gst_percentage = Math.round((gstAmt / entered * 100) * 100) / 100;
                row.base_amount = entered;
                row.total_amount = Math.round((entered + gstAmt) * 100) / 100;
                row.gst_type = 'exclusive';
            } else {
                row.gst_percentage = '';
                row.base_amount = entered;
                row.gst_amount = (gstAmtStr === '' || gstAmt === 0) ? '' : row.gst_amount;
                row.total_amount = entered;
                row.gst_type = 'none';
            }
            this.recalculateAllTotals(mode);
        },
        recalculateRowBrokerage(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const total = parseFloat(row.total_amount) || parseFloat(row.sale_amount) || 0;
            const value = parseFloat(row.brokerage_value) || 0;
            if (!this.forms[mode].broker_involved || !row.broker_involved || !value) {
                row.brokerage_amount = 0;
                return;
            }
            row.brokerage_amount = row.brokerage_type === 'percentage'
                ? Math.round(total * (value / 100) * 100) / 100
                : Math.round(value * 100) / 100;
        },
        addExtraWorkRow(mode = 'add') {
            if (!this.forms[mode].extra_works) {
                this.forms[mode].extra_works = [];
            }
            this.forms[mode].extra_works.push({
                description: '', amount: '', gst_type: 'none', gst_percentage: '', gst_amount: 0, line_total: 0
            });
            this.recalculateAllTotals(mode);
        },
        removeExtraWorkRow(index, mode = 'add') {
            this.forms[mode].extra_works.splice(index, 1);
            this.recalculateAllTotals(mode);
        },
        recalculateExtraWorkRowGst(index, mode = 'add') {
            const row = this.forms[mode].extra_works[index];
            const entered = parseFloat(row.amount) || 0;
            let pct = parseFloat(row.gst_percentage) || 0;
            const type = row.gst_type || 'none';
            let gst = 0;
            let total = 0;

            if (type === 'none') {
                pct = 0;
                row.gst_percentage = 0;
            }

            if (type === 'exclusive') {
                gst = Math.round(entered * (pct / 100) * 100) / 100;
                total = Math.round((entered + gst) * 100) / 100;
            } else if (type === 'inclusive') {
                const base = entered / (1 + (pct / 100));
                gst = Math.round((entered - base) * 100) / 100;
                total = entered;
            } else {
                gst = 0;
                total = entered;
            }
            row.gst_amount = gst;
            row.line_total = total;
            this.recalculateAllTotals(mode);
        },
        recalculateAllTotals(mode = 'add') {
            let unitBase = 0;
            let unitGst = 0;
            let parkingBase = 0;
            let parkingGst = 0;
            let totalBase = 0;
            let totalGst = 0;
            let totalVal = 0;

            if (this.forms[mode].units) {
                this.forms[mode].units.forEach((row, index) => {
                    const base = parseFloat(row.base_amount || row.sale_amount) || 0;
                    const gst = parseFloat(row.gst_amount) || 0;
                    const total = parseFloat(row.total_amount) || (base + gst);

                    if (this.isRowParking(index, mode)) {
                        parkingBase += base;
                        parkingGst += gst;
                    } else {
                        unitBase += base;
                        unitGst += gst;
                    }
                    totalBase += base;
                    totalGst += gst;
                    totalVal += total;
                });
            }

            let extraBase = 0;
            let extraGst = 0;
            let extraVal = 0;
            if (this.forms[mode].extra_works) {
                this.forms[mode].extra_works.forEach((row) => {
                    const line_total = parseFloat(row.line_total) || 0;
                    const gst = parseFloat(row.gst_amount) || 0;
                    const base = line_total > 0 ? (line_total - gst) : (parseFloat(row.amount) || 0);
                    extraBase += base;
                    extraGst += gst;
                    extraVal += (base + gst);
                });
            }

            let totalBrokerage = 0;
            if (this.forms[mode].broker_involved) {
                const bVal = parseFloat(this.forms[mode].brokerage_value) || 0;
                const bType = this.forms[mode].brokerage_type || 'percentage';
                if (bType === 'percentage') {
                    totalBrokerage = totalBase * (bVal / 100);
                } else {
                    totalBrokerage = bVal;
                }
            }

            this.forms[mode].unit_base_amount = Math.round(unitBase * 100) / 100;
            this.forms[mode].unit_gst_amount = Math.round(unitGst * 100) / 100;
            this.forms[mode].parking_base_amount = Math.round(parkingBase * 100) / 100;
            this.forms[mode].parking_gst_amount = Math.round(parkingGst * 100) / 100;
            this.forms[mode].extra_base_amount = Math.round(extraBase * 100) / 100;
            this.forms[mode].extra_gst_amount = Math.round(extraGst * 100) / 100;

            this.forms[mode].base_amount = Math.round((totalBase + extraBase) * 100) / 100;
            this.forms[mode].gst_amount = Math.round((totalGst + extraGst) * 100) / 100;
            this.forms[mode].total_amount = Math.round((totalVal + extraVal) * 100) / 100;
            this.forms[mode].total_contract_value = Math.round((totalVal + extraVal) * 100) / 100;
            this.forms[mode].brokerage_amount = Math.round(totalBrokerage * 100) / 100;
            this.forms[mode].sale_amount = Math.round(totalBase * 100) / 100;

            const paid = parseFloat(this.forms[mode].initial_payment_amount) || 0;
            const totalContract = this.forms[mode].total_amount || 0;
            this.forms[mode].remaining_balance = Math.max(0, Math.round((totalContract - paid) * 100) / 100);
            if (totalContract > 0) {
                this.forms[mode].initial_payment_percentage = Math.round((paid / totalContract * 100) * 100) / 100;
            }
        },
        loadUnitsForProject(mode) {
            const projectId = this.forms[mode].project_id;
            this.availableUnits[mode] = [];
            this.selectedUnit[mode] = null;
            this.forms[mode].unit_id = '';
            if (!projectId) return;
            fetch(`{{ url('sales/available-units') }}/${projectId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { this.availableUnits[mode] = data.units; })
            .catch(err => console.error(err));
        },
        onUnitSelect(mode) {
            const unit = this.availableUnits[mode].find(u => u.id == this.forms[mode].unit_id);
            this.selectedUnit[mode] = unit || null;
            if (unit) {
                const isParking = (unit.unit_type_name || '').toLowerCase() === 'parking' || (unit.unit_type_category || '').toLowerCase() === 'parking';
                if (isParking) {
                    this.forms[mode].rate_per_sqft = 0;
                    this.forms[mode].sale_amount = unit.expected_sale_amount || '';
                    this.recalculateGst(mode);
                    this.recalculateBrokerage(mode);
                } else {
                    this.forms[mode].rate_per_sqft = unit.expected_rate_per_sqft || '';
                    this.onRateChange(mode);
                }
            }
        },
        onRateChange(mode) {
            const unit = this.selectedUnit[mode];
            const rate = parseFloat(this.forms[mode].rate_per_sqft) || 0;
            const area = unit ? parseFloat(unit.built_up_area) || 0 : 0;
            this.forms[mode].sale_amount = Math.round(rate * area * 100) / 100;
            this.recalculateGst(mode);
            this.recalculateBrokerage(mode);
        },
        saleDifference(mode) {
            const unit = this.selectedUnit[mode];
            const expected = unit ? parseFloat(unit.expected_sale_amount) || 0 : 0;
            const agreed = parseFloat(this.forms[mode].sale_amount) || 0;
            return Math.round((agreed - expected) * 100) / 100;
        },
        getRowDifference(index, mode = 'add') {
            const row = (this.forms[mode].units || [])[index];
            if (!row) return 0;
            
            let expectedAmt = 0;
            if (this.isRowParking(index, mode)) {
                expectedAmt = parseFloat(row.expected_sale_amount) || 0;
            } else {
                const unit = (this.availableUnits[mode] || []).find(u => u.id == row.unit_id);
                const area = unit ? (parseFloat(unit.built_up_area) || 0) : (parseFloat(row.built_up_area) || 0);
                const expectedRate = parseFloat(row.rate_per_sqft) || 0;
                expectedAmt = Math.round(expectedRate * area * 100) / 100;
            }
            
            const agreedAmt = parseFloat(row.sale_amount) || 0;
            return Math.round((agreedAmt - expectedAmt) * 100) / 100;
        },
        getTotalDifference(mode = 'add') {
            let totalDiff = 0;
            (this.forms[mode].units || []).forEach((row, index) => {
                totalDiff += this.getRowDifference(index, mode);
            });
            return Math.round(totalDiff * 100) / 100;
        },
        recalculateBrokerage(mode) {
            this.recalculateAllTotals(mode);
        },
        onBrokerSelect(mode) {
            const form = this.forms[mode];
            const broker = this.brokerList.find(b => b.id == form.broker_id);
            if (broker && form.brokerage_type === 'percentage' && broker.default_commission_pct !== null) {
                form.brokerage_value = broker.default_commission_pct;
            }
            this.recalculateBrokerage(mode);
        },
        onBrokerageTypeChange(mode) {
            const form = this.forms[mode];
            if (form.brokerage_type === 'percentage') {
                const broker = this.brokerList.find(b => b.id == form.broker_id);
                form.brokerage_value = (broker && broker.default_commission_pct !== null) ? broker.default_commission_pct : '';
            } else {
                form.brokerage_value = '';
            }
            this.recalculateBrokerage(mode);
        },
        recalculateBalance(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            if (form.initial_payment_percentage !== '' && form.initial_payment_percentage !== undefined) {
                const pct = parseFloat(form.initial_payment_percentage) || 0;
                form.initial_payment_amount = Math.round((total * pct / 100) * 100) / 100;
            }
            const paid = parseFloat(form.initial_payment_amount) || 0;
            form.remaining_balance = Math.max(0, Math.round((total - paid) * 100) / 100);
        },
        updateInitialPaymentFromPercentage(mode, event = null) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const pct = parseFloat(form.initial_payment_percentage) || 0;

            form.initial_payment_amount = Math.round((total * pct / 100) * 100) / 100;
            const paid = parseFloat(form.initial_payment_amount) || 0;
            form.remaining_balance = Math.max(0, Math.round((total - paid) * 100) / 100);
        },
        updateInitialPaymentFromAmount(mode, event = null) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const paid = parseFloat(form.initial_payment_amount) || 0;

            if (total > 0) {
                form.initial_payment_percentage = Math.round((paid / total * 100) * 100) / 100;
            } else {
                form.initial_payment_percentage = '';
            }
            form.remaining_balance = Math.max(0, Math.round((total - paid) * 100) / 100);
        },
        openQuickAddCustomer() {
            this.quickCustomer = { name: '', email: '', phone: '' };
            this.quickCustomerErrors = {};
            this.modals.quickCustomer.open = true;
        },
        submitQuickCustomer() {
            fetch('{{ route('customers.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.quickCustomer)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.quickCustomerErrors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Failed to add customer.', 'error');
                } else {
                    this.customerList.push({ id: data.customer.id, name: data.customer.name, email: data.customer.email, phone: data.customer.phone });
                    this.forms.add.customer_id = data.customer.id;
                    this.modals.quickCustomer.open = false;
                    this.showToast('Customer added and selected.');
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        getFilteredCustomersList(search = '') {
            const q = (search || '').toLowerCase().trim();
            if (!q) return this.customerList;
            return this.customerList.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.phone && c.phone.toLowerCase().includes(q))
            );
        },
        getSelectedCustomer() {
            if (!this.forms.add || !this.forms.add.customer_id) return null;
            return this.customerList.find(c => c.id == this.forms.add.customer_id) || null;
        },
        selectCustomer(customer) {
            if (this.forms.add) {
                this.forms.add.customer_id = customer ? customer.id : '';
            }
            this.customerDropdownOpen = false;
            this.customerSearch = '';
        },
        recalculateGst(mode) {
            const form = this.forms[mode];
            const entered = parseFloat(form.sale_amount) || 0;
            if (form.gst_type === 'exclusive') {
                const gst = Math.round(entered * 0.18 * 100) / 100;
                form.base_amount = entered;
                form.gst_amount = gst;
                form.total_amount = Math.round((entered + gst) * 100) / 100;
            } else if (form.gst_type === 'inclusive') {
                const gst = Math.round(entered * 18 / 118 * 100) / 100;
                form.base_amount = Math.round((entered - gst) * 100) / 100;
                form.gst_amount = gst;
                form.total_amount = entered;
            } else {
                form.base_amount = entered;
                form.gst_amount = 0;
                form.total_amount = entered;
            }
            this.recalculateBrokerage(mode);
            this.recalculateBalance(mode);
        },
        openAddModal() {
            this.errors = {};
            this.availableUnits.add = [];
            this.selectedUnit.add = null;
            this.forms.add = {
                project_id: '{{ request('project_id') }}', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                initial_payment_amount: 0, initial_payment_percentage: '', payment_mode: 'Cash', reference_no: '', bank_id: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', emi_type: 'equal', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(), milestones: [], remaining_balance: 0,
                notes: '',
                units: []
            };
            this.addUnitRow();
            this.modals.add.open = true;
            if (this.forms.add.project_id) {
                this.loadUnitsForProject('add');
            }
        },
        closeAddModal() { this.modals.add.open = false; },
        submitAddSale() {
            this.errors = {};
            let hasError = false;

            const paidAdd = parseFloat(this.forms.add.initial_payment_amount) || 0;
            const totalAdd = parseFloat(this.forms.add.total_amount) || parseFloat(this.forms.add.sale_amount) || 0;
            if (totalAdd > 0 && paidAdd > totalAdd) {
                this.showToast('Initial payment cannot exceed Total Contract Value. Please enter a lesser amount.', 'error');
                hasError = true;
            }

            if (!this.forms.add.project_id) {
                this.errors.project_id = ['The project field is required.'];
                hasError = true;
            }
            if (!this.forms.add.customer_id) {
                this.errors.customer_id = ['Please select a customer.'];
                hasError = true;
            }
            if (!this.forms.add.agreement_date) {
                this.errors.agreement_date = ['Please enter agreement date.'];
                hasError = true;
            }
            if (!this.forms.add.units || this.forms.add.units.length === 0) {
                this.showToast('Please add at least one unit row.', 'error');
                hasError = true;
            } else {
                this.forms.add.units.forEach((u, idx) => {
                    if (!u.unit_id) {
                        this.errors['units.' + idx + '.unit_id'] = ['Please select a unit.'];
                        hasError = true;
                    }
                    if (!u.sale_amount || parseFloat(u.sale_amount) <= 0) {
                        this.errors['units.' + idx + '.sale_amount'] = ['Please enter agreed sale amount.'];
                        hasError = true;
                    }
                });
            }

            if (hasError) {
                this.showToast('Please fill all required fields highlighted below.', 'error');
                this.$nextTick(() => {
                    if (this.$refs.addModalScroll) {
                        this.$refs.addModalScroll.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                    const firstErrInput = document.querySelector('[x-ref="addModalScroll"] .border-rose-500');
                    if (firstErrInput) {
                        firstErrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (typeof firstErrInput.focus === 'function') firstErrInput.focus();
                    }
                });
                return;
            }

            fetch('{{ route('sales.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.add)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) { 
                    this.errors = data.errors || {}; 
                    this.showToast('Please resolve validation errors.', 'error');
                    this.$nextTick(() => {
                        if (this.$refs.addModalScroll) {
                            this.$refs.addModalScroll.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                        const firstErrInput = document.querySelector('[x-ref="addModalScroll"] .border-rose-500');
                        if (firstErrInput) {
                            firstErrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            if (typeof firstErrInput.focus === 'function') firstErrInput.focus();
                        }
                    });
                }
                else if (!res.ok) { this.showToast(data.error || 'Server error.', 'error'); }
                else { this.showToast('Sale recorded successfully.'); this.closeAddModal(); this.fetchSales(); }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        openEditModal(saleId) {
            this.errors = {};
            fetch(`{{ url('sales') }}/${saleId}/json`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.activeSale = data.sale;
                this.selectedUnit.edit = this.activeSale.unit ? {
                    id: this.activeSale.unit.id,
                    door_no: this.activeSale.unit.door_no,
                    floor_name: this.activeSale.unit.floor ? this.activeSale.unit.floor.name : '',
                    built_up_area: this.activeSale.unit.built_up_area,
                    expected_rate_per_sqft: this.activeSale.unit.expected_rate_per_sqft,
                    expected_sale_amount: this.activeSale.unit.expected_sale_amount,
                    unit_type_name: this.activeSale.unit.unit_type ? this.activeSale.unit.unit_type.name : '',
                    unit_type_category: this.activeSale.unit.unit_type ? this.activeSale.unit.unit_type.category : ''
                } : null;
                const initialReceipt = this.activeSale.receipts ? this.activeSale.receipts.find(r => r.remarks === 'Initial payment at sale creation') : null;
                this.forms.edit = {
                    project_id: this.activeSale.project_id,
                    unit_id: this.activeSale.unit_id,
                    customer_id: this.activeSale.customer_id,
                    sale_date: this.activeSale.sale_date ? this.activeSale.sale_date.split('T')[0] : '',
                    agreement_date: this.activeSale.sale_date ? this.activeSale.sale_date.split('T')[0] : '',
                    registration_date: this.activeSale.registration_date ? this.activeSale.registration_date.split('T')[0] : '',
                    rate_per_sqft: this.activeSale.rate_per_sqft || '',
                    sale_amount: this.activeSale.sale_amount,
                    gst_type: this.activeSale.gst_type || 'none',
                    gst_percentage: this.activeSale.gst_percentage || '',
                    gst_amount: this.activeSale.gst_amount,
                    base_amount: this.activeSale.base_amount,
                    total_amount: this.activeSale.total_amount,
                    broker_involved: this.activeSale.brokerage ? true : false,
                    broker_id: this.activeSale.brokerage ? this.activeSale.brokerage.broker_id : '',
                    brokerage_type: this.activeSale.brokerage ? this.activeSale.brokerage.commission_type : 'percentage',
                    brokerage_value: this.activeSale.brokerage ? (this.activeSale.brokerage.commission_type === 'percentage' ? this.activeSale.brokerage.commission_percent : this.activeSale.brokerage.commission_amount) : '',
                    brokerage_amount: this.activeSale.brokerage ? this.activeSale.brokerage.commission_amount : 0,
                    brokerage_status: this.activeSale.brokerage ? this.activeSale.brokerage.status : 'pending',
                    initial_payment_amount: initialReceipt ? initialReceipt.amount : 0,
                    initial_payment_percentage: (initialReceipt && this.activeSale.total_amount > 0) ? Math.round((parseFloat(initialReceipt.amount) / parseFloat(this.activeSale.total_amount) * 100) * 100) / 100 : '',
                    payment_mode: initialReceipt ? initialReceipt.payment_mode : 'Cash',
                    reference_no: initialReceipt ? initialReceipt.reference_no || '' : '',
                    bank_id: initialReceipt ? initialReceipt.bank_id || '' : '',
                    initial_payment_date: initialReceipt ? (initialReceipt.receipt_date ? initialReceipt.receipt_date.split('T')[0] : '') : '',
                    payment_plan: this.activeSale.payment_plan || 'lump_sum',
                    emi_installment_count: this.activeSale.emi_installment_count || 12,
                    emi_frequency: this.activeSale.emi_frequency || 'monthly',
                    first_installment_date: this.activeSale.first_installment_date ? this.activeSale.first_installment_date.split('T')[0] : (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(),
                    remaining_balance: this.activeSale.remaining_balance || 0,
                    notes: this.activeSale.notes,
                    units: []
                };
                // Populate existing extra works
                if (this.activeSale.extra_works && this.activeSale.extra_works.length > 0) {
                    this.forms.edit.extra_works = this.activeSale.extra_works.map(ew => {
                        const isInclusive = ew.gst_type === 'inclusive';
                        const displayAmt = isInclusive ? parseFloat(ew.line_total) : parseFloat(ew.amount);
                        return {
                            description: ew.description,
                            amount: displayAmt,
                            gst_type: ew.gst_type || 'none',
                            gst_percentage: ew.gst_percentage,
                            gst_amount: ew.gst_amount,
                            line_total: ew.line_total
                        };
                    });
                } else {
                    this.forms.edit.extra_works = [];
                }
                // Populate existing units
                if (this.activeSale.sale_units && this.activeSale.sale_units.length > 0) {
                    this.forms.edit.units = this.activeSale.sale_units.map(su => {
                        return {
                            id: su.id,
                            unit_id: su.unit_id,
                            wing: su.wing || '',
                            rate_per_sqft: su.unit ? su.unit.expected_rate_per_sqft : su.rate_per_sqft,
                            sale_rate_per_sqft: su.rate_per_sqft,
                            expected_sale_amount: su.unit ? su.unit.expected_sale_amount : su.base_amount,
                            sale_amount: su.base_amount,
                            gst_type: su.gst_type,
                            gst_percentage: su.gst_percentage,
                            gst_amount: su.gst_amount,
                            base_amount: su.base_amount,
                            total_amount: su.line_total,
                            broker_involved: parseFloat(su.brokerage_amount) > 0,
                            brokerage_type: su.brokerage_type || 'percentage',
                            brokerage_value: su.brokerage_value || '',
                            brokerage_amount: su.brokerage_amount || 0
                        };
                    });
                } else if (this.activeSale.unit_id) {
                    this.forms.edit.units.push({
                        id: null,
                        unit_id: this.activeSale.unit_id,
                        wing: '',
                        rate_per_sqft: this.activeSale.unit ? this.activeSale.unit.expected_rate_per_sqft : this.activeSale.rate_per_sqft,
                        sale_rate_per_sqft: this.activeSale.rate_per_sqft,
                        expected_sale_amount: this.activeSale.unit ? this.activeSale.unit.expected_sale_amount : this.activeSale.sale_amount,
                        sale_amount: this.activeSale.sale_amount,
                        gst_type: this.activeSale.gst_applicable ? 'exclusive' : 'none',
                        gst_percentage: this.activeSale.gst_applicable ? (this.activeSale.gst_percentage || '') : 0,
                        gst_amount: this.activeSale.gst_amount || 0,
                        base_amount: this.activeSale.base_amount || this.activeSale.sale_amount,
                        total_amount: this.activeSale.total_amount || this.activeSale.sale_amount,
                        broker_involved: this.activeSale.brokerage ? true : false,
                        brokerage_type: this.activeSale.brokerage ? this.activeSale.brokerage.commission_type : 'percentage',
                        brokerage_value: this.activeSale.brokerage ? (this.activeSale.brokerage.commission_type === 'percentage' ? this.activeSale.brokerage.commission_percent : this.activeSale.brokerage.commission_amount) : '',
                        brokerage_amount: this.activeSale.brokerage ? this.activeSale.brokerage.commission_amount : 0
                    });
                }
                // Calculate live summary totals immediately upon opening edit modal
                this.recalculateAllTotals('edit');

                // Load available units for project
                if (this.forms.edit.project_id) {
                    const projectId = this.forms.edit.project_id;
                    this.availableUnits.edit = [];
                    fetch(`{{ url('sales/available-units') }}/${projectId}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => { 
                        this.availableUnits.edit = data.units;
                        // Include already booked units for this sale in available units options
                        this.forms.edit.units.forEach(u => {
                            if (u.unit_id && !this.availableUnits.edit.some(au => au.id == u.unit_id)) {
                                const activeSU = this.activeSale.sale_units?.find(su => su.unit_id == u.unit_id);
                                const unitObj = activeSU?.unit || this.activeSale.unit;
                                
                                const door_no = unitObj?.door_no || 'Current Unit';
                                const floor_name = unitObj?.floor?.name || '';
                                const built_up_area = unitObj?.built_up_area || 0;
                                const expected_rate_per_sqft = unitObj?.expected_rate_per_sqft || 0;
                                const expected_sale_amount = unitObj?.expected_sale_amount || 0;
                                const unit_type_name = unitObj?.unit_type?.name || '';
                                const unit_type_category = unitObj?.unit_type?.category || '';
                                
                                this.availableUnits.edit.push({
                                    id: u.unit_id,
                                    door_no: door_no,
                                    floor_name: floor_name,
                                    built_up_area: built_up_area,
                                    expected_rate_per_sqft: expected_rate_per_sqft,
                                    expected_sale_amount: expected_sale_amount,
                                    unit_type_name: unit_type_name,
                                    unit_type_category: unit_type_category
                                });
                            }
                        });
                        this.recalculateAllTotals('edit');
                    })
                    .catch(err => console.error(err));
                }
                this.modals.edit.open = true;
            })
            .catch(err => { console.error(err); this.showToast('Failed to load sale.', 'error'); });
        },
        closeEditModal() { this.modals.edit.open = false; this.statusChange.pending = false; },
        submitEditSale() {
            const paidEdit = parseFloat(this.forms.edit.initial_payment_amount) || 0;
            const totalEdit = parseFloat(this.forms.edit.total_amount) || parseFloat(this.forms.edit.sale_amount) || 0;
            if (totalEdit > 0 && paidEdit > totalEdit) {
                this.showToast('Initial payment cannot exceed Total Contract Value. Please enter a lesser amount.', 'error');
                return;
            }
            fetch(`{{ url('sales') }}/${this.activeSale.id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.edit)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) { this.errors = data.errors || {}; }
                else if (!res.ok) { this.showToast(data.error || 'Server error.', 'error'); }
                else { this.showToast('Sale updated successfully.'); this.fetchSales(); this.openEditModal(this.activeSale.id); }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        promptStatusChange(targetStatus) {
            this.statusChange = { pending: true, targetStatus, reason: '' };
        },
        confirmStatusChange() {
            if (!this.statusChange.reason) {
                this.showToast('A reason is required.', 'error');
                return;
            }
            fetch(`{{ url('sales') }}/${this.activeSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: this.statusChange.targetStatus, reason: this.statusChange.reason })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) { this.showToast(data.error || 'Failed to update status.', 'error'); }
                else {
                    this.showToast(`Sale marked as ${this.statusChange.targetStatus}.`);
                    this.statusChange.pending = false;
                    this.fetchSales();
                    this.openEditModal(this.activeSale.id);
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        openViewModal(saleId) {
            fetch(`{{ url('sales') }}/${saleId}/json`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { this.activeSale = data.sale; this.modals.view.open = true; })
            .catch(err => { console.error(err); this.showToast('Failed to load sale.', 'error'); });
        },
        closeViewModal() { this.modals.view.open = false; },
        numberToWords(num) {
            if (!num || isNaN(num) || parseFloat(num) <= 0) return '';
            if (typeof window.convertNumberToWords === 'function') {
                return window.convertNumberToWords(num);
            }
            return '';
        }
    };
}
</script>
</x-erp-layout>
