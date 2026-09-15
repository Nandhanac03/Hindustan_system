@extends('layouts.erp')

@section('title', 'Daily Site Expenses - Hindustan Real Estate ERP')

@section('content')
<div class="p-6 h-full overflow-y-auto" x-data="dailySiteExpenses()">
    <div class="w-full space-y-6">
        
        {{-- Under Construction Notice --}}
        <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-5 md:p-6 shadow-sm relative overflow-hidden backdrop-blur-sm">
            <div class="flex items-start md:items-center gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30 text-2xl md:text-3xl">
                    🚧
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                        <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                            Work In Progress
                        </span>
                    </div>
                    <h2 class="text-lg md:text-2xl font-black text-red-950 tracking-tight leading-snug">
                        We're working on this module. It is not yet ready for use and will be released shortly.
                    </h2>
                    <p class="text-xs md:text-sm font-medium text-red-800">
                        This module is currently being finalized. Please check back soon for full availability.
                    </p>
                </div>
            </div>
        </div> 

        <!-- Header Card (Breadcrumb & Title) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                <span class="hover:text-gray-600 cursor-pointer">Home</span>
                <span class="mx-2">></span>
                <span class="hover:text-gray-600 cursor-pointer">Petty Cash & Site Expense</span>
                <span class="mx-2">></span>
                <span class="text-[#a38c29]">Daily Site Expenses</span>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-[#a38c29]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-800 m-0">Daily Site Expenses</h3>
                    <span class="bg-[#e6f4ea] text-[#1e8e3e] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ml-2">REAL-TIME TRACKING</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="exportExcel()" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-xs font-bold rounded-xl transition-all shadow-2xs hover:shadow-md uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        EXCEL REPORT
                    </button>
                    <button type="button" @click="showExpenseModal = true" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#a38c29] to-[#8f7a22] text-white text-xs font-bold rounded-xl hover:shadow-lg transition-all uppercase tracking-wider shadow-sm border border-[#8f7a22] cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        NEW EXPENSE
                    </button>
                </div>
            </div>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel (Matching Units Design) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3 flex-1">
                {{-- Pro Search Input --}}
                <div class="relative group col-span-1 sm:col-span-2 xl:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Voucher, Particulars..." 
                           x-model="filters.search" @input.debounce.300ms="fetchExpenses(1)"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="filters.search" @click="filters.search = ''; fetchExpenses(1)"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Site / Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                    </div>
                    <select x-model="filters.project_id" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- From Date --}}
                <div class="relative">
                    <input type="date" x-model="filters.from_date" @change="fetchExpenses(1)"
                           class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- To Date --}}
                <div class="relative">
                    <input type="date" x-model="filters.to_date" @change="fetchExpenses(1)"
                           class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- Category Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <select x-model="filters.category" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="All">All Categories</option>
                        <option value="Refreshments">Refreshments</option>
                        <option value="Transport">Transport</option>
                        <option value="Minor Tools">Minor Tools</option>
                        <option value="Stationery">Stationery</option>
                        <option value="Labour Welfare">Labour Welfare</option>
                        <option value="Electrical Material">Electrical Material</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Payment Mode Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <select x-model="filters.payment_mode" @change="fetchExpenses(1)"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="All">All Modes</option>
                        @if(isset($paymentModes))
                            @foreach($paymentModes as $mode)
                                <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                            @endforeach
                        @else
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <button @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8f7a22]">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Voucher No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Particulars</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Payment Mode</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Bill No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-right">Amount (₹)</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="expense in expensesList" :key="expense.id">
                            <tr class="hover:bg-[#f8f9fa] transition-colors group">
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600" x-text="expense.formatted_date"></td>
                                <td class="px-6 py-4 text-[12px] font-bold text-[#a38c29]" x-text="expense.voucher_number"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-700" x-text="expense.category"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600 truncate max-w-[200px]" :title="expense.narration" x-text="expense.narration"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-600" x-text="expense.payment_mode"></td>
                                <td class="px-6 py-4 text-[12px] font-medium text-gray-500" x-text="expense.bill_no || '-'"></td>
                                <td class="px-6 py-4 text-[12px] font-bold text-gray-900 text-right" x-text="expense.amount.toFixed(2)"></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openViewModal(expense)" class="w-8 h-8 rounded-xl bg-[#f0e8d5] hover:bg-[#e4dac0] text-[#917d23] transition-all inline-flex items-center justify-center cursor-pointer shadow-2xs active:scale-95" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button type="button" @click="openEditModal(expense)" class="w-8 h-8 rounded-xl bg-[#dce6d5] hover:bg-[#cfdcc3] text-[#0d7a54] transition-all inline-flex items-center justify-center cursor-pointer shadow-2xs active:scale-95" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="expensesList.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-[13px] font-bold text-gray-500">No expenses found</p>
                                        <p class="text-[11px] text-gray-400 mt-1">Adjust your search query or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot x-show="expensesList.length > 0">
                        <tr class="bg-gray-50 border-t border-gray-200">
                            <td colspan="6" class="px-6 py-3 text-[11px] font-bold text-gray-700">Total</td>
                            <td class="px-6 py-3 text-[13px] font-extrabold text-[#a38c29] text-right" x-text="formattedTotalAmount"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-white">
                <p class="text-[11px] text-gray-500 font-medium">
                    Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of <span x-text="pagination.total || 0"></span> entries
                </p>
                <div class="flex items-center gap-1" x-show="pagination.last_page > 1">
                    <button type="button" @click="fetchExpenses(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-600 disabled:opacity-40 hover:bg-gray-50 cursor-pointer">Prev</button>
                    <span class="px-2 text-xs font-bold text-gray-700" x-text="`Page ${pagination.current_page} of ${pagination.last_page}`"></span>
                    <button type="button" @click="fetchExpenses(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-600 disabled:opacity-40 hover:bg-gray-50 cursor-pointer">Next</button>
                </div>
            </div>
            
        </div>
    </div>

    <!-- New Expense Modal -->
    <div x-show="showExpenseModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showExpenseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showExpenseModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showExpenseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">
                
                <form action="{{ route('petty-cash.store-expense') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Header -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/20">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[#d9bf3b] text-[10px] font-bold uppercase tracking-widest mb-1">Petty Cash Expense Entry</p>
                                <h3 class="text-lg font-extrabold text-white">Add New Expense</h3>
                            </div>
                            <button type="button" @click="showExpenseModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row h-full">
                        <!-- Left Form Column -->
                        <div class="flex-1 p-6 space-y-5">
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Voucher No.</label>
                                    <input type="text" name="voucher_number" required value="EXP-{{ rand(1000, 9999) }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}" class="w-full bg-white border @error('transaction_date') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                    @error('transaction_date') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Site <span class="text-red-500">*</span></label>
                                    <select name="project_id" required class="w-full bg-white border @error('project_id') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                                    <select name="category" required class="w-full bg-white border @error('category') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        <option value="Refreshments" {{ old('category') == 'Refreshments' ? 'selected' : '' }}>Refreshments</option>
                                        <option value="Transport" {{ old('category') == 'Transport' ? 'selected' : '' }}>Transport</option>
                                        <option value="Minor Tools" {{ old('category') == 'Minor Tools' ? 'selected' : '' }}>Minor Tools</option>
                                        <option value="Stationery" {{ old('category') == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                                        <option value="Labour Welfare" {{ old('category') == 'Labour Welfare' ? 'selected' : '' }}>Labour Welfare</option>
                                        <option value="Electrical Material" {{ old('category') == 'Electrical Material' ? 'selected' : '' }}>Electrical Material</option>
                                        <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('category') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Bill No.</label>
                                    <input type="text" name="bill_no" placeholder="e.g. BILL-88" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Bill Date</label>
                                    <input type="date" name="bill_date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Amount (₹) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="amount" required value="{{ old('amount') }}" placeholder="0.00" class="w-full bg-white border @error('amount') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-bold text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                    @error('amount') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Payment Mode <span class="text-red-500">*</span></label>
                                    <select name="payment_mode" required class="w-full bg-white border @error('payment_mode') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->name }}" {{ old('payment_mode') == $mode->name ? 'selected' : '' }}>{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_mode') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Attach Bill / Receipt</label>
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-1">
                                            <input type="file" name="attachment" id="attachment" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">
                                            <div class="w-full bg-gray-50 border border-dashed border-gray-300 rounded-lg px-3 h-9 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span id="file-name" class="text-[11px] font-medium text-gray-500 truncate">Select file...</span>
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-gray-400 mt-1 whitespace-nowrap">JPG, PNG, PDF up to 2MB</p>
                                    </div>
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Particulars <span class="text-red-500">*</span></label>
                                    <textarea name="particulars" required rows="2" placeholder="Enter expense details (e.g. Tea & Snacks for Site Team)" class="w-full bg-white border @error('particulars') border-red-500 @else border-gray-200 @enderror rounded-lg p-3 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all resize-none">{{ old('particulars') }}</textarea>
                                    @error('particulars') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Sidebar Column -->
                        <div class="w-full md:w-[320px] bg-slate-50 border-l border-gray-200 p-6 flex flex-col gap-6">
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                                <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                                <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 relative z-10">Available Petty Cash Balance</h4>
                                <div class="text-2xl font-black text-green-600 relative z-10 mb-1">
                                    ₹ {{ number_format($availableBalance, 2) }}
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium relative z-10">
                                    (As on {{ date('d-M-Y H:i A') }})
                                </p>
                            </div>

                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex-1">
                                <h4 class="text-[11px] font-bold text-gray-800 mb-3">Expense Category Summary (This Month)</h4>
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="text-left text-[10px] font-bold text-gray-500 pb-2">Category</th>
                                            <th class="text-right text-[10px] font-bold text-gray-500 pb-2">Amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @php $catTotal = 0; @endphp
                                        @foreach($categorySummary as $catName => $catAmount)
                                            @php $catTotal += $catAmount; @endphp
                                            <tr>
                                                <td class="text-[11px] font-medium text-gray-700 py-2">{{ $catName }}</td>
                                                <td class="text-[11px] font-bold text-gray-900 text-right py-2">{{ number_format($catAmount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-200 bg-gray-50/50">
                                            <td class="text-[11px] font-bold text-gray-900 py-2">Total</td>
                                            <td class="text-[11px] font-bold text-[#a38c29] text-right py-2">{{ number_format($catTotal, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <button type="button" @click="showExpenseModal = false" class="px-5 py-2 text-[12px] font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <div class="flex gap-3">
                            <button type="submit" name="submit_action" value="save_new" class="px-5 py-2 text-[12px] font-bold text-[#a38c29] bg-white border border-[#a38c29] rounded-lg hover:bg-[#fbfaf5] transition-colors">
                                Save & New
                            </button>
                            <button type="submit" name="submit_action" value="save_post" class="px-5 py-2 text-[12px] font-bold text-white bg-gradient-to-r from-[#a38c29] to-[#8f7a22] border border-[#8f7a22] rounded-lg hover:shadow-lg transition-all">
                                Save & Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div x-show="showViewModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showViewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showViewModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showViewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <!-- Header -->
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[#d9bf3b] text-[10px] font-bold uppercase tracking-widest mb-1 block" x-text="'Voucher: ' + selectedExp.voucher_number"></span>
                            <h3 class="text-lg font-extrabold text-white">Daily Site Expense Details</h3>
                        </div>
                        <button type="button" @click="showViewModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                    </div>
                </div>
                <!-- Body -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Voucher No</span>
                            <span class="text-xs font-bold text-[#a38c29]" x-text="selectedExp.voucher_number"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Date</span>
                            <span class="text-xs font-bold text-slate-800" x-text="selectedExp.formatted_date"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Amount</span>
                            <span class="text-xs font-black text-emerald-700" x-text="selectedExp.formatted_amount"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Site / Project</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.project_name"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Category</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.category"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.payment_mode"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bill No.</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.bill_no || '-'"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bill Date</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.formatted_bill_date || '-'"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Created By</span>
                            <span class="text-xs font-semibold text-slate-800" x-text="selectedExp.created_by"></span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Particulars / Narration</span>
                        <p class="text-xs font-medium text-slate-800 leading-relaxed" x-text="selectedExp.particulars || selectedExp.narration"></p>
                    </div>

                    <template x-if="selectedExp.attachment_url">
                        <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span class="text-xs font-bold text-slate-700" x-text="selectedExp.attachment_name"></span>
                            </div>
                            <a :href="selectedExp.attachment_url" target="_blank" class="px-3 py-1 bg-[#a38c29] text-white rounded-lg text-xs font-bold hover:bg-[#8f7a22] transition">View File</a>
                        </div>
                    </template>
                </div>
                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                    <button type="button" @click="showViewModal = false" class="px-6 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition uppercase tracking-wider">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <form :action="updateUrl" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Header -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/20">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[#d9bf3b] text-[10px] font-bold uppercase tracking-widest mb-1 block" x-text="'Voucher: ' + selectedExp.voucher_number"></span>
                                <h3 class="text-lg font-extrabold text-white">Edit Site Expense</h3>
                            </div>
                            <button type="button" @click="showEditModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                        </div>
                    </div>
                    <!-- Form Body -->
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Voucher No.</label>
                                <input type="text" :value="selectedExp.voucher_number" readonly class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-700 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="transaction_date" required x-model="selectedExp.transaction_date" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                                <select name="category" required x-model="selectedExp.category" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29]">
                                    <option value="Refreshments">Refreshments</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Minor Tools">Minor Tools</option>
                                    <option value="Stationery">Stationery</option>
                                    <option value="Labour Welfare">Labour Welfare</option>
                                    <option value="Electrical Material">Electrical Material</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Payment Mode <span class="text-red-500">*</span></label>
                                <select name="payment_mode" required x-model="selectedExp.payment_mode" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29]">
                                    @foreach($paymentModes as $mode)
                                        <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Bill No.</label>
                                <input type="text" name="bill_no" x-model="selectedExp.bill_no" placeholder="e.g. BILL-88" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Bill Date</label>
                                <input type="date" name="bill_date" x-model="selectedExp.bill_date" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Amount (₹) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="amount" required x-model="selectedExp.amount" placeholder="0.00" class="w-full bg-white border border-slate-200 rounded-lg px-3 h-9 text-xs font-bold text-slate-800 outline-none focus:border-[#a38c29]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Particulars <span class="text-red-500">*</span></label>
                            <textarea name="particulars" required rows="2" x-model="selectedExp.particulars" class="w-full bg-white border border-slate-200 rounded-lg p-3 text-xs font-medium text-slate-800 outline-none focus:border-[#a38c29] resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Replace Attachment (Optional)</label>
                            <input type="file" name="attachment" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#FAF0D7] file:text-[#8a7522] hover:file:bg-[#f3e6c0]">
                            <template x-if="selectedExp.attachment_name">
                                <span class="text-[10px] text-slate-500 block mt-1">Current file: <strong x-text="selectedExp.attachment_name"></strong></span>
                            </template>
                        </div>
                    </div>
                    <!-- Footer Actions -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#a38c29] to-[#8f7a22] rounded-xl hover:shadow-lg transition-all">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function dailySiteExpenses() {
    return {
        showExpenseModal: {{ session('show_expense_modal') || $errors->any() ? 'true' : 'false' }},
        showViewModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedExp: {},
        updateUrl: '',
        deleteUrl: '',
        filters: {
            search: '',
            project_id: '{{ $selectedProject }}',
            from_date: '{{ $fromDate }}',
            to_date: '{{ $toDate }}',
            category: '{{ $category }}',
            payment_mode: '{{ $paymentMode }}'
        },
        expensesList: [],
        formattedTotalAmount: '{{ number_format($totalAmount, 2) }}',
        pagination: {
            current_page: {{ $expenses->currentPage() }},
            last_page: {{ $expenses->lastPage() }},
            from: {{ $expenses->firstItem() ?? 0 }},
            to: {{ $expenses->lastItem() ?? 0 }},
            total: {{ $expenses->total() }}
        },

        init() {
            this.expensesList = {!! json_encode(collect($expenses->items())->map(function($e) use ($category, $selectedProject, $siteName) {
                $catName = ($category && $category !== 'All') ? $category : (explode('-', $e->narration)[0] ?? 'General');
                $parts = explode('-', $e->narration);
                $particularsText = count($parts) > 1 ? trim(implode('-', array_slice($parts, 1))) : $e->narration;
                return [
                    'id' => $e->id,
                    'voucher_number' => $e->voucher_number,
                    'transaction_date' => \Carbon\Carbon::parse($e->transaction_date)->format('Y-m-d'),
                    'formatted_date' => \Carbon\Carbon::parse($e->transaction_date)->format('d-M-Y'),
                    'project_id' => $e->pettyCashBox?->project_id ?? $selectedProject,
                    'project_name' => $e->pettyCashBox?->project?->name ?? $siteName ?? 'Site',
                    'category' => trim($catName),
                    'particulars' => trim($particularsText),
                    'narration' => $e->narration,
                    'payment_mode' => $e->payment_mode ?? 'Cash',
                    'bill_no' => $e->reference_no ?? '',
                    'bill_date' => $e->bill_date ? \Carbon\Carbon::parse($e->bill_date)->format('Y-m-d') : '',
                    'formatted_bill_date' => $e->bill_date ? \Carbon\Carbon::parse($e->bill_date)->format('d-M-Y') : '',
                    'amount' => (float)$e->cash_out,
                    'formatted_amount' => '₹ ' . number_format($e->cash_out, 2),
                    'attachment_url' => $e->attachment_path ? asset($e->attachment_path) : '',
                    'attachment_name' => $e->attachment_path ? basename($e->attachment_path) : '',
                    'created_by' => $e->creator?->name ?? 'System Admin',
                ];
            })) !!};
        },

        fetchExpenses(page = 1) {
            let params = new URLSearchParams();
            params.append('page', page);
            if (this.filters.project_id) params.append('project_id', this.filters.project_id);
            if (this.filters.from_date) params.append('from_date', this.filters.from_date);
            if (this.filters.to_date) params.append('to_date', this.filters.to_date);
            if (this.filters.category) params.append('category', this.filters.category);
            if (this.filters.payment_mode) params.append('payment_mode', this.filters.payment_mode);
            if (this.filters.search) params.append('search', this.filters.search);

            fetch('{{ route('petty-cash.daily-site-expenses') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.expensesList = data.expenses;
                this.formattedTotalAmount = data.totalAmount;
                this.pagination = data.pagination;
            })
            .catch(err => {
                console.error('Error fetching expenses:', err);
            });
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.project_id = '{{ $projects->first()->id ?? '' }}';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.filters.category = 'All';
            this.filters.payment_mode = 'All';
            this.fetchExpenses(1);
        },

        openViewModal(exp) {
            this.selectedExp = exp;
            this.showViewModal = true;
        },

        openEditModal(exp) {
            this.selectedExp = Object.assign({}, exp);
            this.updateUrl = '{{ url('petty-cash/daily-site-expenses') }}/' + exp.id;
            this.showEditModal = true;
        },

        openDeleteModal(exp) {
            this.selectedExp = exp;
            this.deleteUrl = '{{ url('petty-cash/daily-site-expenses') }}/' + exp.id;
            this.showDeleteModal = true;
        },

        exportExcel() {
            if (!this.expensesList || this.expensesList.length === 0) {
                alert('No expenses available to export.');
                return;
            }
            let csv = "DATE,VOUCHER NO.,CATEGORY,PARTICULARS,PAYMENT MODE,BILL NO.,AMOUNT (INR)\n";
            this.expensesList.forEach(exp => {
                let date = `"${exp.formatted_date || ''}"`;
                let voucher = `"${exp.voucher_number || ''}"`;
                let category = `"${(exp.category || '').replace(/"/g, '""')}"`;
                let particulars = `"${(exp.particulars || '').replace(/"/g, '""')}"`;
                let mode = `"${exp.payment_mode || ''}"`;
                let billNo = `"${exp.bill_no || ''}"`;
                let amount = `"${exp.amount || 0}"`;
                csv += `${date},${voucher},${category},${particulars},${mode},${billNo},${amount}\n`;
            });

            const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `Daily_Site_Expenses_Report_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
}
</script>
@endsection
