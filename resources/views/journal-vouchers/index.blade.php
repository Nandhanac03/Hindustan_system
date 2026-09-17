<x-erp-layout>
    <x-slot:title>Journal Vouchers Master - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Journal Vouchers Master</x-slot:headerTitle>

    <style>
        /* Hide automatically injected amount-in-words labels inside table cells */
        td .amount-in-words-label,
        #journal-entries-table .amount-in-words-label {
            display: none !important;
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            border: 0 !important;
            overflow: hidden !important;
        }
    </style>

    <div x-data="journalVouchersList()" class="space-y-6 p-6">

        <!-- Header Section matching Chart of Accounts Master -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <circle cx="12" cy="12" r="5" stroke-width="2"/>
                            <circle cx="12" cy="12" r="1" stroke-width="2" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Journal Vouchers Master</h1>
                        <p class="text-xs text-slate-500 font-medium">Manage multi-line double-entry ledger vouchers, adjustments & non-cash postings</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" @click="openCreateModal()" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>New Journal Voucher</span>
                </button>
            </div>
        </div>

        <!-- Flash Success & Error Messages -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-xl flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- KPI Summary Cards (Real-time Instant Updating) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Vouchers -->
            <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider mb-1">Total Vouchers</p>
                <h4 class="text-[22px] font-bold text-[#8a7522] m-0" x-text="stats().total_vouchers"></h4>
                <p class="text-[10px] text-slate-500 mt-1">All Filtered Journal Entries</p>
            </div>

            <!-- Posted Vouchers -->
            <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-emerald-500 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-1">Posted Vouchers</p>
                <h4 class="text-[22px] font-bold text-emerald-700 m-0" x-text="stats().posted_count"></h4>
                <p class="text-[10px] text-slate-500 mt-1">Active Ledger Postings</p>
            </div>

            <!-- Draft Vouchers -->
            <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-amber-500 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider mb-1">Draft Vouchers</p>
                <h4 class="text-[22px] font-bold text-amber-700 m-0" x-text="stats().draft_count"></h4>
                <p class="text-[10px] text-slate-500 mt-1">Pending Confirmation</p>
            </div>

            <!-- Total Turnover -->
            <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-blue-600 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-1">Total Turnover</p>
                <h4 class="text-[20px] font-mono font-bold text-blue-800 m-0" x-text="'₹ ' + stats().total_turnover"></h4>
                <p class="text-[10px] text-slate-500 mt-1">Total Debit Volume</p>
            </div>
        </div>

        <!-- Ultra-Clean Modern Search & Filter Panel (No URL Reload / Instant In-Memory Filter) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm transition-all">
            <div @submit.prevent="" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 flex-1">
                    
                    <!-- Search Input with Icon -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 11-14 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" placeholder="Search Voucher No, Ref, Narration..." x-model="filters.search" @input="currentPage = 1"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    </div>

                    <!-- Voucher Type Filter with Icon -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                        </div>
                        <select x-model="filters.voucher_type_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Voucher Types</option>
                            @foreach($voucherTypes as $vt)
                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- From Date -->
                    <div>
                        <input type="date" x-model="filters.from_date" @change="currentPage = 1"
                               class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                    </div>

                    <!-- To Date -->
                    <div>
                        <input type="date" x-model="filters.to_date" @change="currentPage = 1"
                               class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                    </div>

                </div>

                <!-- Reset Filters Button (No Page Reload) -->
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reset Filters</span>
                </button>
            </div>
        </div>

        <!-- Data Table Container (Embedded directly inside index page) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider">
                            <th class="px-4 py-3.5 text-center w-12">#</th>
                            <th class="px-4 py-3.5">VOUCHER NO</th>
                            <th class="px-4 py-3.5">DATE</th>
                            <th class="px-4 py-3.5">VOUCHER TYPE</th>
                            <th class="px-4 py-3.5">REFERENCE</th>
                            <th class="px-4 py-3.5">NARRATION</th>
                            <th class="px-4 py-3.5 text-right">TOTAL DEBIT</th>
                            <th class="px-4 py-3.5 text-right">TOTAL CREDIT</th>
                            <th class="px-4 py-3.5 text-center">STATUS</th>
                            <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white text-slate-800 font-medium">
                        <template x-for="(jv, index) in paginatedVouchers()" :key="jv.id">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3.5 text-center text-slate-500 font-semibold" x-text="(currentPage - 1) * pageSize + index + 1"></td>
                                <td class="px-4 py-3.5">
                                    <button type="button" @click="showVoucherDetails(jv.id)" class="font-bold text-[#a38c29] hover:text-[#8a7522] hover:underline font-mono" x-text="jv.voucher_no"></button>
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 font-semibold" x-text="jv.voucher_date_formatted"></td>
                                <td class="px-4 py-3.5 font-bold text-slate-900" x-text="jv.voucher_type_name"></td>
                                <td class="px-4 py-3.5 text-slate-500 font-semibold" x-text="jv.reference_no"></td>
                                <td class="px-4 py-3.5 text-slate-600 max-w-xs truncate" :title="jv.narration" x-text="jv.narration"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900" x-text="formatCurrency(jv.total_debit)"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900" x-text="formatCurrency(jv.total_credit)"></td>
                                <td class="px-4 py-3.5 text-center">
                                    <template x-if="jv.status === 'Posted'">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">Posted</span>
                                    </template>
                                    <template x-if="jv.status !== 'Posted'">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200">Draft</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <button type="button" @click="showVoucherDetails(jv.id)" class="p-2 rounded-xl bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Voucher">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button type="button" @click="editVoucher(jv.id)" class="p-2 rounded-xl bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Voucher">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template x-if="filteredVouchers().length === 0">
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-slate-400 font-medium">
                                    No Journal Vouchers found matching the selected filter criteria.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination Footer (Pure Client-Side Alpine - Zero URL Reload) -->
            <div class="p-4 border-t border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-semibold text-slate-600">
                <div>
                    Showing <span x-text="filteredVouchers().length === 0 ? 0 : (currentPage - 1) * pageSize + 1"></span> 
                    to <span x-text="Math.min(currentPage * pageSize, filteredVouchers().length)"></span> 
                    of <span x-text="filteredVouchers().length"></span> entries
                </div>
                
                <div class="flex items-center gap-1" x-show="totalPages() > 1">
                    <button type="button" @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed bg-slate-100' : 'hover:bg-slate-100 bg-white cursor-pointer'"
                            class="px-3 py-1.5 border border-slate-250 text-slate-700 rounded-lg text-xs font-bold transition">Previous</button>

                    <template x-for="p in totalPages()" :key="p">
                        <button type="button" @click="currentPage = p"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-white border border-slate-250 text-slate-700 hover:bg-slate-100'"
                                class="px-3 py-1.5 rounded-lg text-xs font-black transition cursor-pointer" x-text="p"></button>
                    </template>

                    <button type="button" @click="if(currentPage < totalPages()) currentPage++" :disabled="currentPage === totalPages()"
                            :class="currentPage === totalPages() ? 'opacity-50 cursor-not-allowed bg-slate-100' : 'hover:bg-slate-100 bg-white cursor-pointer'"
                            class="px-3 py-1.5 border border-slate-250 text-slate-700 rounded-lg text-xs font-bold transition">Next</button>
                </div>
            </div>
        </div>

        <!-- CREATE / EDIT JOURNAL VOUCHER MODAL -->
        <div x-show="formModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="formModalOpen = false"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white text-slate-900 rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden">
                    
                    <!-- Dark Themed Modal Header -->
                    <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">JOURNAL VOUCHERS</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white" x-text="isEditMode ? 'EDIT JOURNAL VOUCHER' : 'ADD JOURNAL VOUCHER'"></h3>
                        </div>
                        <button type="button" @click="formModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                    </div>

                    <!-- Form -->
                    <form :action="isEditMode ? '/journal-vouchers/' + editVoucherId : '{{ route('journal-vouchers.store') }}'" method="POST" @submit.prevent="validateAndSubmit($event)" class="p-6 sm:p-8 space-y-6" novalidate>
                        @csrf
                        <template x-if="isEditMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <!-- Row 1: Voucher No, Date, Type, Reference -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Voucher No</label>
                                <input type="text" name="voucher_no" x-model="form.voucher_no" readonly
                                       class="w-full bg-slate-100 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date <span class="text-rose-500 font-bold">*</span></label>
                                <input type="date" name="voucher_date" required x-model="form.voucher_date"
                                       class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Voucher Type</label>
                                <select name="voucher_type_id" x-model="form.voucher_type_id"
                                        class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition cursor-pointer">
                                    <option value="">All</option>
                                    @foreach($voucherTypes as $vt)
                                        <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reference No</label>
                                <input type="text" name="reference_no" placeholder="Ref/Doc No..." x-model="form.reference_no" @input="errors.reference_no = ''"
                                       :class="errors.reference_no ? 'border-rose-500 focus:ring-rose-500 bg-rose-50/40 text-rose-900' : 'border-slate-200 focus:ring-[#a38c29] bg-slate-50 text-slate-900'"
                                       class="w-full hover:bg-white focus:bg-white border rounded-xl px-3.5 py-2.5 text-xs font-bold focus:ring-2 focus:outline-none transition">
                                <template x-if="errors.reference_no">
                                    <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="errors.reference_no"></span>
                                    </p>
                                </template>
                                @error('reference_no')
                                    <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Entry Lines Table -->
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Journal Entries</span>
                                <button type="button" @click="addEntryRow()"
                                        class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>Add Row</span>
                                </button>
                            </div>

                            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                                <table id="journal-entries-table" class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-100 border-b border-slate-200 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                                            <th class="px-3.5 py-3 w-4/12">Account Head *</th>
                                            <th class="px-3.5 py-3 text-right w-2/12">Debit (DR)</th>
                                            <th class="px-3.5 py-3 text-right w-2/12">Credit (CR)</th>
                                            <th class="px-3.5 py-3 w-3/12">Line Narration</th>
                                            <th class="px-2 py-3 text-center w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-150 text-xs bg-white">
                                        <template x-for="(line, index) in form.entries" :key="index">
                                            <tr class="align-middle hover:bg-slate-50/50 transition-colors">
                                                <td class="p-2.5">
                                                    <select :name="'entries['+index+'][account_id]'" x-model="line.account_id" @change="if(errors.rows && errors.rows[index]) delete errors.rows[index]"
                                                            :class="errors.rows && errors.rows[index] ? 'border-rose-500 ring-1 ring-rose-500 bg-rose-50/40 text-rose-900' : 'border-slate-300 hover:border-slate-400 bg-slate-50 hover:bg-white text-slate-900'"
                                                            class="w-full border border-slate-300 px-3 py-2 font-bold rounded-xl text-xs focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition cursor-pointer">
                                                        <option value="">Select Chart of Account...</option>
                                                        @foreach($accounts as $acc)
                                                            <option value="{{ $acc->account_code }}">{{ $acc->account_name }} ({{ $acc->account_code }})</option>
                                                        @endforeach
                                                    </select>
                                                    <template x-if="errors.rows && errors.rows[index]">
                                                        <p class="text-rose-600 text-[10px] font-bold mt-1" x-text="errors.rows[index]"></p>
                                                    </template>
                                                </td>

                                                <td class="p-2.5">
                                                    <input type="number" step="0.01" min="0" placeholder="0.00" :name="'entries['+index+'][debit_amount]'"
                                                           x-model.number="line.debit_amount" @input="clearOpposite(line, 'debit'); errors.entries = '';" data-no-words
                                                           class="w-full px-3 py-2 bg-white border border-slate-300 text-slate-900 font-mono font-bold rounded-xl text-right text-xs focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                                                </td>

                                                <td class="p-2.5">
                                                    <input type="number" step="0.01" min="0" placeholder="0.00" :name="'entries['+index+'][credit_amount]'"
                                                           x-model.number="line.credit_amount" @input="clearOpposite(line, 'credit'); errors.entries = '';" data-no-words
                                                           class="w-full px-3 py-2 bg-white border border-slate-300 text-slate-900 font-mono font-bold rounded-xl text-right text-xs focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                                                </td>

                                                <td class="p-2.5">
                                                    <input type="text" placeholder="Line detail..." :name="'entries['+index+'][line_narration]'"
                                                           x-model="line.line_narration"
                                                           class="w-full px-3 py-2 bg-white border border-slate-300 text-slate-800 font-medium rounded-xl text-xs focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                                                </td>

                                                <td class="p-2.5 text-center">
                                                    <button type="button" @click="removeEntryRow(index)" x-show="form.entries.length > 2" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors cursor-pointer" title="Remove row">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>

                                    <tfoot>
                                        <tr class="bg-slate-900 text-white font-bold text-xs">
                                            <td class="px-3.5 py-3 uppercase tracking-wider">TOTALS</td>
                                            <td class="px-3.5 py-3 text-right font-mono" x-text="formatCurrency(calcTotalDebit())"></td>
                                            <td class="px-3.5 py-3 text-right font-mono" x-text="formatCurrency(calcTotalCredit())"></td>
                                            <td colspan="2" class="px-3.5 py-3 text-center">
                                                <template x-if="isBalanced()">
                                                    <span class="text-emerald-400 font-bold uppercase text-[10px] tracking-wider">BALANCED (DR = CR)</span>
                                                </template>
                                                <template x-if="!isBalanced()">
                                                    <span class="text-rose-400 font-bold uppercase text-[10px] tracking-wider">UNBALANCED (Diff: <span x-text="formatCurrency(calcDifference())"></span>)</span>
                                                </template>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <template x-if="errors.entries">
                                <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl flex items-center gap-2 shadow-2xs">
                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="errors.entries"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Overall Narration -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Header Narration / Remarks <span class="text-rose-500 font-bold">*</span></label>
                            <textarea name="narration" rows="2" placeholder="Journal Voucher description..." x-model="form.narration" @input="errors.narration = ''"
                                      :class="errors.narration ? 'border-rose-500 focus:ring-rose-500 bg-rose-50/40 text-rose-900' : 'border-slate-200 focus:ring-[#a38c29] bg-slate-50 text-slate-900'"
                                      class="w-full hover:bg-white focus:bg-white border font-medium rounded-xl p-3.5 text-xs focus:ring-2 focus:outline-none transition resize-none"></textarea>
                            <template x-if="errors.narration">
                                <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="errors.narration"></span>
                                </p>
                            </template>
                            @error('narration')
                                <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-3">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status:</label>
                                <select name="status" x-model="form.status" class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                                    <option value="Posted">Posted</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" @click="formModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase tracking-wider rounded-xl transition cursor-pointer">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">
                                    <span x-text="isEditMode ? 'Update Journal Voucher' : 'Post Journal Voucher'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- VIEW JOURNAL VOUCHER MODAL -->
        <div x-show="viewModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="viewModalOpen = false"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white text-slate-900 rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden">
                    
                    <!-- Dark Themed Modal Header -->
                    <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1" x-text="activeVoucher.voucher_type || 'JOURNAL VOUCHER'"></span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white font-mono" x-text="activeVoucher.voucher_no"></h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <span :class="activeVoucher.status === 'Posted' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-amber-500/20 text-amber-300 border-amber-500/40'"
                                  class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase border tracking-wider" x-text="activeVoucher.status"></span>
                            <button type="button" @click="viewModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-3 gap-4 text-xs">
                            <div>
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase">Voucher Date</span>
                                <span class="font-bold text-slate-800" x-text="activeVoucher.voucher_date"></span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase">Reference No</span>
                                <span class="font-bold text-slate-800" x-text="activeVoucher.reference_no"></span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase">Total Amount</span>
                                <span class="font-mono font-bold text-[#a38c29]" x-text="'₹ ' + activeVoucher.total_debit"></span>
                            </div>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-[#a38c29] text-white font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="px-4 py-2.5">Account Head</th>
                                        <th class="px-4 py-2.5 text-right">Debit (₹)</th>
                                        <th class="px-4 py-2.5 text-right">Credit (₹)</th>
                                        <th class="px-4 py-2.5">Line Narration</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150 font-medium bg-white">
                                    <template x-for="entry in activeVoucher.entries" :key="entry.account_code">
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold text-slate-900" x-text="entry.account_name + ' (' + entry.account_code + ')'"></td>
                                            <td class="px-4 py-2.5 text-right font-mono" x-text="entry.debit_amount"></td>
                                            <td class="px-4 py-2.5 text-right font-mono" x-text="entry.credit_amount"></td>
                                            <td class="px-4 py-2.5 text-slate-500" x-text="entry.line_narration"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-slate-900 text-white font-bold">
                                    <tr>
                                        <td class="px-4 py-2.5 uppercase">TOTAL</td>
                                        <td class="px-4 py-2.5 text-right font-mono" x-text="activeVoucher.total_debit"></td>
                                        <td class="px-4 py-2.5 text-right font-mono" x-text="activeVoucher.total_credit"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase mb-1">Header Narration</span>
                            <p class="text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 font-medium" x-text="activeVoucher.narration"></p>
                        </div>

                        <div class="flex justify-end pt-2 border-t border-slate-100">
                            <button type="button" @click="viewModalOpen = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">
                                CLOSE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function journalVouchersList() {
            return {
                formModalOpen: false,
                viewModalOpen: false,
                isEditMode: false,
                editVoucherId: null,
                nextVoucherNo: '{{ $nextVoucherNo }}',
                currentPage: 1,
                pageSize: 15,
                allVouchers: @json($vouchers),
                errors: {
                    reference_no: '',
                    narration: '',
                    entries: '',
                    rows: {}
                },
                filters: {
                    search: '',
                    voucher_type_id: '',
                    from_date: '',
                    to_date: ''
                },
                activeVoucher: {
                    voucher_no: '',
                    voucher_date: '',
                    voucher_type: '',
                    reference_no: '',
                    narration: '',
                    status: 'Posted',
                    total_debit: '0.00',
                    total_credit: '0.00',
                    entries: []
                },
                form: {
                    voucher_no: '{{ $nextVoucherNo }}',
                    voucher_date: new Date().toISOString().substring(0, 10),
                    voucher_type_id: '',
                    reference_no: '',
                    narration: '',
                    status: 'Posted',
                    entries: [
                        { account_id: '', debit_amount: 0.00, credit_amount: 0.00, line_narration: '' },
                        { account_id: '', debit_amount: 0.00, credit_amount: 0.00, line_narration: '' }
                    ]
                },

                validateAndSubmit(e) {
                    this.errors = { reference_no: '', narration: '', entries: '', rows: {} };
                    let hasError = false;

                    // 1. Check Reference No uniqueness if provided
                    if (this.form.reference_no && this.form.reference_no.trim()) {
                        const ref = this.form.reference_no.trim().toLowerCase();
                        const isDup = this.allVouchers.some(v => {
                            if (this.isEditMode && v.id === this.editVoucherId) return false;
                            return v.reference_no && v.reference_no !== '-' && v.reference_no.trim().toLowerCase() === ref;
                        });
                        if (isDup) {
                            this.errors.reference_no = 'Reference No already exists for another Journal Voucher.';
                            hasError = true;
                        }
                    }

                    // 2. Check Header Narration / Remarks
                    if (!this.form.narration || !this.form.narration.trim()) {
                        this.errors.narration = 'Header narration / remarks is required.';
                        hasError = true;
                    }

                    // 3. Check Entries Balance & Amount validity
                    const totalD = this.calcTotalDebit();
                    const totalC = this.calcTotalCredit();

                    if (totalD === 0 && totalC === 0) {
                        this.errors.entries = 'Please enter valid Debit or Credit amounts for the journal entries.';
                        hasError = true;
                    } else if (Math.abs(totalD - totalC) >= 0.01) {
                        this.errors.entries = 'Journal Voucher must be balanced! Total Debit (₹ ' + this.formatCurrency(totalD) + ') does not equal Total Credit (₹ ' + this.formatCurrency(totalC) + ').';
                        hasError = true;
                    }

                    // 4. Check Account Head selection for non-zero lines
                    let validCount = 0;
                    this.form.entries.forEach((line, idx) => {
                        const d = parseFloat(line.debit_amount) || 0;
                        const c = parseFloat(line.credit_amount) || 0;
                        if (d > 0 || c > 0) {
                            validCount++;
                            if (!line.account_id) {
                                this.errors.rows[idx] = 'Please select an Account Head.';
                                hasError = true;
                            }
                        }
                    });

                    if (validCount < 2) {
                        this.errors.entries = 'A valid Journal Voucher must contain at least two non-zero entry lines.';
                        hasError = true;
                    }

                    if (hasError) {
                        return false;
                    }

                    e.target.submit();
                },

                // Pure In-Memory Alpine Filtering (NO URL Change & NO Page Reload)
                filteredVouchers() {
                    return this.allVouchers.filter(v => {
                        // Search text filter
                        if (this.filters.search) {
                            const q = this.filters.search.toLowerCase().trim();
                            const matchNo = v.voucher_no.toLowerCase().includes(q);
                            const matchRef = v.reference_no.toLowerCase().includes(q);
                            const matchNarr = v.narration.toLowerCase().includes(q);
                            const matchEntry = v.entries.some(e => e.line_narration.toLowerCase().includes(q) || e.account_name.toLowerCase().includes(q) || e.account_code.toLowerCase().includes(q));
                            if (!matchNo && !matchRef && !matchNarr && !matchEntry) return false;
                        }

                        // Voucher Type filter
                        if (this.filters.voucher_type_id) {
                            if (String(v.voucher_type_id) !== String(this.filters.voucher_type_id)) return false;
                        }

                        // From Date filter
                        if (this.filters.from_date) {
                            if (v.voucher_date < this.filters.from_date) return false;
                        }

                        // To Date filter
                        if (this.filters.to_date) {
                            if (v.voucher_date > this.filters.to_date) return false;
                        }

                        return true;
                    });
                },

                paginatedVouchers() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    return this.filteredVouchers().slice(start, start + this.pageSize);
                },

                totalPages() {
                    return Math.ceil(this.filteredVouchers().length / this.pageSize) || 1;
                },

                stats() {
                    const list = this.filteredVouchers();
                    const total_vouchers = list.length;
                    const posted_count = list.filter(v => v.status === 'Posted').length;
                    const draft_count = list.filter(v => v.status === 'Draft').length;
                    const total_turnover_num = list.reduce((sum, v) => sum + (parseFloat(v.total_debit) || 0), 0);
                    
                    return {
                        total_vouchers,
                        posted_count,
                        draft_count,
                        total_turnover: this.formatCurrency(total_turnover_num)
                    };
                },

                resetFilters() {
                    this.filters = {
                        search: '',
                        voucher_type_id: '',
                        from_date: '',
                        to_date: ''
                    };
                    this.currentPage = 1;
                },

                openCreateModal() {
                    this.isEditMode = false;
                    this.editVoucherId = null;
                    this.errors = { reference_no: '', entries: '', rows: {} };
                    this.form = {
                        voucher_no: this.nextVoucherNo,
                        voucher_date: new Date().toISOString().substring(0, 10),
                        voucher_type_id: '',
                        reference_no: '',
                        narration: '',
                        status: 'Posted',
                        entries: [
                            { account_id: '', debit_amount: 0.00, credit_amount: 0.00, line_narration: '' },
                            { account_id: '', debit_amount: 0.00, credit_amount: 0.00, line_narration: '' }
                        ]
                    };
                    this.formModalOpen = true;
                },
                addEntryRow() {
                    this.form.entries.push({ account_id: '', debit_amount: 0.00, credit_amount: 0.00, line_narration: '' });
                },
                removeEntryRow(index) {
                    if (this.form.entries.length > 2) {
                        this.form.entries.splice(index, 1);
                    }
                },
                clearOpposite(line, field) {
                    if (field === 'debit' && line.debit_amount > 0) {
                        line.credit_amount = 0.00;
                    } else if (field === 'credit' && line.credit_amount > 0) {
                        line.debit_amount = 0.00;
                    }
                },
                calcTotalDebit() {
                    return this.form.entries.reduce((sum, line) => sum + (parseFloat(line.debit_amount) || 0), 0);
                },
                calcTotalCredit() {
                    return this.form.entries.reduce((sum, line) => sum + (parseFloat(line.credit_amount) || 0), 0);
                },
                calcDifference() {
                    return Math.abs(this.calcTotalDebit() - this.calcTotalCredit());
                },
                isBalanced() {
                    const totalD = this.calcTotalDebit();
                    const totalC = this.calcTotalCredit();
                    return totalD > 0 && Math.abs(totalD - totalC) < 0.01;
                },
                formatCurrency(val) {
                    return Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
                showVoucherDetails(id) {
                    const found = this.allVouchers.find(v => v.id === id);
                    if (found) {
                        this.activeVoucher = {
                            voucher_no: found.voucher_no,
                            voucher_date: found.voucher_date_formatted,
                            voucher_type: found.voucher_type_name,
                            reference_no: found.reference_no,
                            narration: found.narration,
                            status: found.status,
                            total_debit: this.formatCurrency(found.total_debit),
                            total_credit: this.formatCurrency(found.total_credit),
                            entries: found.entries.map(e => ({
                                account_code: e.account_code,
                                account_name: e.account_name,
                                debit_amount: this.formatCurrency(e.debit_amount),
                                credit_amount: this.formatCurrency(e.credit_amount),
                                line_narration: e.line_narration
                            }))
                        };
                        this.viewModalOpen = true;
                    }
                },
                editVoucher(id) {
                    const found = this.allVouchers.find(v => v.id === id);
                    if (found) {
                        this.isEditMode = true;
                        this.editVoucherId = id;
                        this.errors = { reference_no: '', entries: '', rows: {} };
                        this.form = {
                            voucher_no: found.voucher_no,
                            voucher_date: found.voucher_date,
                            voucher_type_id: found.voucher_type_id || '',
                            reference_no: found.reference_no === '-' ? '' : found.reference_no,
                            narration: found.narration === '-' ? '' : found.narration,
                            status: found.status,
                            entries: found.entries.map(e => ({
                                account_id: e.account_code,
                                debit_amount: e.debit_amount,
                                credit_amount: e.credit_amount,
                                line_narration: e.line_narration === '-' ? '' : e.line_narration
                            }))
                        };
                        this.formModalOpen = true;
                    }
                },
                deleteVoucher(id) {
                    if (confirm('Are you sure you want to delete this Journal Voucher?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/journal-vouchers/' + id;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';

                        form.appendChild(csrfInput);
                        form.appendChild(methodInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            };
        }
    </script>
</x-erp-layout>
