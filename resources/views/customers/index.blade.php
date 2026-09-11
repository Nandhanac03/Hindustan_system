<x-erp-layout title="Customers Directory" headerTitle="Customers Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="customersApp()">

    {{-- Notification Toast --}}
    <div x-show="toast.open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- Top Action & Customer Filter Bar (Above Filter Panel) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all">
        {{-- Customer Search & Select Dropdown Filter (Like Image) --}}
        <div class="flex-1 min-w-[260px] relative" 
             x-data="{ 
                 open: false, 
                 search: '',
                 get selectedCustomer() {
                     return (allCustomerList || []).find(c => c.id == filters.customer_id);
                 },
                 getFilteredList() {
                     const q = (this.search || '').toLowerCase().trim();
                     if (!q) return allCustomerList;
                     return (allCustomerList || []).filter(c => 
                         (c.name && c.name.toLowerCase().includes(q)) || 
                         (c.phone && c.phone.includes(q)) || 
                         (c.email && c.email.toLowerCase().includes(q))
                     );
                 },
                 selectCustomer(id) {
                     filters.customer_id = id;
                     this.open = false;
                     this.search = '';
                     fetchCustomers();
                 },
                 clearCustomer() {
                     filters.customer_id = '';
                     this.open = false;
                     this.search = '';
                     fetchCustomers();
                 }
             }" 
             @click.outside="open = false">

            <div class="relative w-full">
                <button type="button"
                        @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                        :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                        class="w-full min-h-[42px] px-3 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs text-slate-700">
                    
                    <template x-if="selectedCustomer">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1.5 pl-2 pr-1 py-1 rounded-lg bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-xs font-bold">
                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span x-text="selectedCustomer.name" class="whitespace-nowrap max-w-[220px] truncate"></span>
                                <button type="button" @click.stop="clearCustomer()" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        </div>
                    </template>

                    <template x-if="!selectedCustomer">
                        <div class="flex items-center gap-2 text-slate-500 font-bold px-1">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-xs">Filter by Customers</span>
                        </div>
                    </template>

                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                        <template x-if="selectedCustomer">
                            <span @click.stop="clearCustomer()" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </template>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                     class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-[100]"
                     style="display: none;">
                    
                    <div class="p-2.5 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text"
                                   x-model="search"
                                   x-ref="customerSearchInput"
                                   placeholder="Type name or phone number..."
                                   @keydown.escape="open = false"
                                   class="w-full pl-8 pr-7 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                            <template x-if="search">
                                <button type="button" @click="search = ''; $refs.customerSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                            </template>
                        </div>
                    </div>

                    <button type="button" @click="clearCustomer()"
                            class="w-full px-3.5 py-2.5 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>— Clear Selection (All Customers) —</span>
                    </button>

                    <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                        <template x-for="c in getFilteredList()" :key="c.id">
                            <button type="button"
                                    @click="selectCustomer(c.id)"
                                    :class="filters.customer_id == c.id ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                    class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div :class="filters.customer_id == c.id ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                         class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors"
                                         x-text="(c.name || '?').charAt(0).toUpperCase()">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-xs truncate leading-snug" :class="filters.customer_id == c.id ? 'text-[#8a7522]' : 'text-slate-800'" x-text="c.name"></p>
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 font-mono mt-0.5" x-show="c.phone">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span x-text="c.phone"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Export Excel Button (Aligned on the right of this top bar) --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <button type="button" @click="exportCustomersExcel()"
                    class="h-[42px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-xs font-bold rounded-xl transition shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Customers</span>
            </button>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel (Below Top Bar) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
            {{-- Search: Name / Email / Phone --}}
            <div class="relative sm:col-span-2 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search by name, email or phone..."
                       x-model="filters.search" @input.debounce.300ms="fetchCustomers()"
                       class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                    <button type="button" x-show="filters.search" @click="filters.search = ''; fetchCustomers()"
                            class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <select x-model="filters.status" @change="fetchCustomers()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                    <option value="">All Statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
            <button @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-slate-900/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Customer</span>
            </button>
        </div>
    </div>

    {{-- Customers Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #customers-table thead th {
                border-color: #8a7522 !important;
            }
            #customers-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #customers-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="customers-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Customer</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Contact Info</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Units Purchased</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Total Sale Value</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Total Paid</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Outstanding Balance</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">Status</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="customers-tbody">
                    <template x-for="customer in customers" :key="customer.id">
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3 border text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0"
                                         x-text="(customer.avatar_url || customer.name.substring(0,2)).toUpperCase()"></div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight" x-text="customer.name"></span>
                                        <span class="text-[9px] text-slate-500 font-medium" x-text="customer.address ? (customer.address.length > 25 ? customer.address.substring(0,25)+'...' : customer.address) : 'No address'"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-left">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-slate-700 font-medium flex items-center gap-1.5 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span x-text="customer.email"></span>
                                    </span>
                                    <template x-if="customer.phone">
                                        <span class="text-slate-500 text-[10px] flex items-center gap-1.5 mt-0.5">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span x-text="customer.phone"></span>
                                        </span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]" x-text="(customer.sales_count || 0) + ' Units'"></span>
                            </td>
                            <td class="px-3 py-3 border text-right font-bold text-slate-800" x-text="'₹' + Number(customer.total_purchase || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-right font-bold text-emerald-600" x-text="'₹' + Number(customer.total_paid || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-right font-bold" :class="Number(customer.total_purchase || 0) - Number(customer.total_paid || 0) > 0 ? 'text-rose-600' : 'text-slate-500'" x-text="'₹' + Number(Math.max(0, (customer.total_purchase || 0) - (customer.total_paid || 0))).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-center">
                                <span class="badge-pill" :class="customer.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200'" x-text="customer.is_active ? 'Active' : 'Inactive'"></span>
                            </td>
                            <td class="px-3 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(customer)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Customer Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(customer.id)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Customer">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <template x-if="!customer.sales_count || customer.sales_count == 0">
                                        <button @click="openDeleteModal(customer)" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Customer">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
                                    <template x-if="customer.sales_count > 0">
                                        <button disabled class="p-2 rounded-lg bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed shadow-sm" title="Cannot delete customer with associated properties">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="customers.length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No customers match the query filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>    {{-- ═══════════════════════════════════════════
         ADD CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Customer Directory</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Add New Customer</h2>
                    </div>
                    <button type="button" @click="closeAddModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitAddCustomer()">
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name *</label>
                            <input type="text" x-model="forms.add.name"
                                   @input="if (errors.name) delete errors.name"
                                   :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   placeholder="Enter name"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                                <input type="email" x-model="forms.add.email"
                                       @input="if (errors.email) delete errors.email"
                                       :class="errors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       placeholder="Enter email"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="errors.email"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.email) ? errors.email[0] : errors.email"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                                <input type="text" x-model="forms.add.phone"
                                       @input="if (errors.phone) delete errors.phone"
                                       :class="errors.phone ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       placeholder="Enter phone number"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="errors.phone"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.phone) ? errors.phone[0] : errors.phone"></p></template>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                            <textarea x-model="forms.add.address" rows="2"
                                      @input="if (errors.address) delete errors.address"
                                      :class="errors.address ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                      placeholder="Enter address..."
                                      class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm font-semibold"></textarea>
                            <template x-if="errors.address"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.address) ? errors.address[0] : errors.address"></p></template>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Add Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         EDIT CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Profile</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Edit Customer Details</h2>
                    </div>
                    <button type="button" @click="closeEditModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitEditCustomer()">
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name *</label>
                            <input type="text" x-model="forms.edit.name" placeholder="Enter name"
                                   @input="if (editErrors.name) delete editErrors.name"
                                   :class="editErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="editErrors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.name) ? editErrors.name[0] : editErrors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                                <input type="email" x-model="forms.edit.email" placeholder="Enter email"
                                       @input="if (editErrors.email) delete editErrors.email"
                                       :class="editErrors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="editErrors.email"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.email) ? editErrors.email[0] : editErrors.email"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                                <input type="text" x-model="forms.edit.phone" placeholder="Enter phone number"
                                       @input="if (editErrors.phone) delete editErrors.phone"
                                       :class="editErrors.phone ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="editErrors.phone"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.phone) ? editErrors.phone[0] : editErrors.phone"></p></template>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                            <textarea x-model="forms.edit.address" rows="2" placeholder="Enter address..."
                                      @input="if (editErrors.address) delete editErrors.address"
                                      :class="editErrors.address ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                      class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm font-semibold"></textarea>
                            <template x-if="editErrors.address"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.address) ? editErrors.address[0] : editErrors.address"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</label>
                            <select x-model="forms.edit.is_active"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold text-slate-700 cursor-pointer">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <template x-if="editErrors.is_active"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.is_active[0]"></p></template>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Update Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         DELETE CUSTOMER CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-500/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Safety Check</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Delete Customer</h2>
                    </div>
                    <button type="button" @click="closeDeleteModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-2">
                    <p class="text-sm text-slate-700">
                        Are you sure you want to delete customer <span class="font-bold text-slate-900" x-text="deleteTarget?.name"></span>?
                    </p>
                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">This action cannot be undone and will remove the record.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                <button type="button" @click="confirmDeleteCustomer()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Confirm Delete</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="modals.view.open = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Customer Profile</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Profile Overview</h2>
                    </div>
                    <button type="button" @click="modals.view.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-4 bg-slate-50/50 text-xs font-sans">
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Customer Name</span>
                        <span class="text-sm font-extrabold text-slate-900" x-text="viewTarget?.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5"
                              :class="viewTarget?.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200'"
                              x-text="viewTarget?.is_active ? 'Active' : 'Inactive'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="viewTarget?.email || 'N/A'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.phone || 'N/A'"></span>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Address Details</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.address || 'No address provided'"></span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                <a :href="'{{ route('dms.index', ['category' => 'customer']) }}&search=' + encodeURIComponent(viewTarget?.name || '')" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Manage Documents
                </a>
                <button type="button" @click="modals.view.open = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC CODE & EXCELJS
═══════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
function customersApp() {
    return {
        allCustomerList: {{ Js::from($allCustomers ?? []) }},
        customers: [],
        filters: {
            search: '',
            customer_id: '',
            status: ''
        },
        modals: {
            add: { open: false },
            edit: { open: false },
            delete: { open: false },
            view: { open: false }
        },
        deleteTarget: null,
        viewTarget: null,
        forms: {
            add: {
                name: '',
                email: '',
                phone: '',
                address: '',
            },
            edit: {
                id: null,
                name: '',
                email: '',
                phone: '',
                address: '',
                is_active: '1'
            }
        },
        errors: {},
        editErrors: {},
        toast: {
            open: false,
            message: '',
            type: 'success'
        },

        init() {
            this.fetchCustomers();
        },

        fetchCustomers() {
            let params = new URLSearchParams();
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.customer_id) params.append('customer_id', this.filters.customer_id);
            if (this.filters.status !== '') params.append('status', this.filters.status);

            fetch('{{ route('customers.index') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.customers = data.customers || [];
            })
            .catch(err => {
                console.error('Error fetching customers:', err);
                this.showToast('Failed to fetch customers list.', 'error');
            });
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.customer_id = '';
            this.filters.status = '';
            this.fetchCustomers();
        },

        async exportSingleCustomerStatement(customerId) {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            try {
                this.showToast('Generating Statement of Account Excel...', 'success');
                const res = await fetch(`{{ url('customers') }}/${customerId}/statement-data`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    this.showToast('Failed to load customer statement transactions.', 'error');
                    return;
                }

                const data = await res.json();
                await this.buildAndDownloadStatementWorkbook(data);
            } catch (err) {
                console.error('Error generating statement:', err);
                this.showToast('Error generating statement: ' + err.message, 'error');
            }
        },

        async exportCustomersExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            if (this.filters.customer_id) {
                await this.exportSingleCustomerStatement(this.filters.customer_id);
                return;
            }

            if (this.customers && this.customers.length === 1) {
                await this.exportSingleCustomerStatement(this.customers[0].id);
                return;
            }

            if (!this.customers || this.customers.length === 0) {
                alert('No customers available to export.');
                return;
            }

            // If multiple customers and no specific one selected, export the first customer's statement or prompt
            const firstCustomer = this.customers[0];
            await this.exportSingleCustomerStatement(firstCustomer.id);
        },

        async buildAndDownloadStatementWorkbook(statementData) {
            const customer = statementData.customer || {};
            const projectTitle = statementData.project_title || 'Tabasco Hindustan Infra Developers Pvt. Ltd';
            const properties = statementData.properties || [];
            const installments = statementData.installments || [];
            const allTransactions = statementData.transactions || [];

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'TABASCO Human Capital';
            workbook.lastModifiedBy = 'TABASCO ERP';
            workbook.created = new Date();
            workbook.modified = new Date();

            const goldBorder = {
                top: { style: 'thin', color: { argb: 'FF8A7522' } },
                bottom: { style: 'thin', color: { argb: 'FF8A7522' } },
                left: { style: 'thin', color: { argb: 'FF8A7522' } },
                right: { style: 'thin', color: { argb: 'FF8A7522' } }
            };

            const doubleGoldBorder = {
                top: { style: 'thin', color: { argb: 'FF8A7522' } },
                bottom: { style: 'double', color: { argb: 'FF8A7522' } },
                left: { style: 'thin', color: { argb: 'FF8A7522' } },
                right: { style: 'thin', color: { argb: 'FF8A7522' } }
            };

            const thinGrayBorder = {
                top: { style: 'thin', color: { argb: 'FFD1D5DB' } },
                bottom: { style: 'thin', color: { argb: 'FFD1D5DB' } },
                left: { style: 'thin', color: { argb: 'FFD1D5DB' } },
                right: { style: 'thin', color: { argb: 'FFD1D5DB' } }
            };

            // Helper to build statement ledger worksheet
            const buildLedgerSheet = (sheetName, statementType, txList) => {
                const ws = workbook.addWorksheet(sheetName, {
                    views: [{ showGridLines: true }]
                });

                ws.columns = [
                    { key: 'date', width: 15 },
                    { key: 'v_no', width: 18 },
                    { key: 'description', width: 34 },
                    { key: 'payment_type', width: 16 },
                    { key: 'debit', width: 20 },
                    { key: 'credit', width: 20 },
                    { key: 'balance', width: 22 }
                ];

                // Row 1: Blank Spacer
                ws.addRow([]);
                ws.getRow(1).height = 15;

                // Row 2: Brand Dark Emerald Banner (#0B3B2E)
                const r2 = ws.addRow([projectTitle + '   |   STATEMENT OF ACCOUNT']);
                ws.mergeCells('A2:G2');
                r2.height = 30;
                const c2 = ws.getCell('A2');
                c2.font = { name: 'Calibri', size: 11.5, bold: true, color: { argb: 'FFFFFFFF' } };
                c2.alignment = { vertical: 'middle', horizontal: 'center' };
                c2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                for (let c = 1; c <= 7; c++) { 
                    ws.getRow(2).getCell(c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                    ws.getRow(2).getCell(c).border = goldBorder; 
                }

                // Row 3: Client Details Sub-Banner (#0B3B2E)
                const r3 = ws.addRow(['Client Details', '', '', '', customer.name || 'N/A', '', '']);
                ws.mergeCells('A3:D3');
                ws.mergeCells('E3:G3');
                r3.height = 24;
                for (let c = 1; c <= 7; c++) {
                    const cell = ws.getRow(3).getCell(c);
                    cell.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                    cell.border = goldBorder;
                }
                ws.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };
                ws.getCell('E3').alignment = { vertical: 'middle', horizontal: 'center' };

                // Row 4: STATEMENT OF ACCOUNT Header
                const r4 = ws.addRow(['STATEMENT OF ACCOUNT']);
                ws.mergeCells('A4:G4');
                r4.height = 24;
                const c4 = ws.getCell('A4');
                c4.font = { name: 'Calibri', size: 11, bold: true, underline: true, color: { argb: 'FF0B3B2E' } };
                c4.alignment = { vertical: 'middle', horizontal: 'center' };

                // Row 5: Dates & Statement Type
                let startDateStr = '01-04-2024';
                let endDateStr = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\//g, '-');
                if (txList.length > 0) {
                    if (txList[0].date_dmy && txList[0].date_dmy !== 'N/A') startDateStr = txList[0].date_dmy.replace(/\//g, '-');
                    const lastTx = txList[txList.length - 1];
                    if (lastTx.date_dmy && lastTx.date_dmy !== 'N/A') endDateStr = lastTx.date_dmy.replace(/\//g, '-');
                }

                const r5 = ws.addRow([`From ${startDateStr} To ${endDateStr}`, '', '', '', `Statement Type: ${statementType}`, '', '']);
                ws.mergeCells('A5:D5');
                ws.mergeCells('E5:G5');
                r5.height = 22;
                const c5L = ws.getCell('A5');
                c5L.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF0B3B2E' } };
                c5L.alignment = { vertical: 'middle', horizontal: 'left' };

                const c5R = ws.getCell('E5');
                c5R.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FFC00000' } };
                c5R.alignment = { vertical: 'middle', horizontal: 'right' };

                // Row 6: Table Header (Brand Gold)
                const headers = ['Date', 'V.No', 'Description', 'Payment Type', 'Debit', 'Credit', 'Balance'];
                const r6 = ws.addRow(headers);
                r6.height = 26;
                r6.eachCell((cell) => {
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                    cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    cell.border = goldBorder;
                });

                // Rows 7+: Data Rows (Clean White with thin gray borders)
                let runningBalance = 0;
                let totalDebit = 0;
                let totalCredit = 0;

                txList.forEach(tx => {
                    const debitVal = Number(tx.debit || 0);
                    const creditVal = Number(tx.credit || 0);
                    totalDebit += debitVal;
                    totalCredit += creditVal;
                    runningBalance = runningBalance + debitVal - creditVal;

                    const row = ws.addRow([
                        tx.date || 'N/A',
                        tx.v_no || '',
                        tx.description || '',
                        tx.payment_type || 'Cheque',
                        debitVal > 0 ? debitVal : '',
                        creditVal > 0 ? creditVal : '',
                        runningBalance
                    ]);
                    row.height = 21;

                    row.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                        cell.border = thinGrayBorder;
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                        cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };

                        if (colNumber === 1 || colNumber === 2 || colNumber === 4) {
                            cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        } else if (colNumber === 3) {
                            cell.alignment = { vertical: 'middle', horizontal: 'left' };
                        } else if (colNumber >= 5 && colNumber <= 7) {
                            cell.alignment = { vertical: 'middle', horizontal: 'right' };
                            cell.numFmt = '0.00';
                        }
                    });
                });

                if (txList.length === 0) {
                    const emptyRow = ws.addRow(['-', '-', 'No transactions recorded', statementType, '', '', '0.00']);
                    emptyRow.height = 22;
                    emptyRow.eachCell(c => {
                        c.border = thinGrayBorder;
                        c.font = { name: 'Calibri', size: 10, color: { argb: 'FF64748B' } };
                        c.alignment = { vertical: 'middle', horizontal: 'center' };
                    });
                }

                // Sub Total Row
                const subTotalRow = ws.addRow(['Sub Total', '', '', '', totalDebit, totalCredit, runningBalance]);
                const subRowNum = ws.rowCount;
                ws.mergeCells(`A${subRowNum}:D${subRowNum}`);
                subTotalRow.height = 24;
                subTotalRow.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                    cell.border = thinGrayBorder;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'right' };
                    else if (colNumber >= 5 && colNumber <= 7) {
                        cell.alignment = { vertical: 'middle', horizontal: 'right' };
                        cell.numFmt = '0.00';
                    }
                });

                // Grand Total Row
                const grandTotalRow = ws.addRow(['Grand Total', '', '', '', totalDebit, totalCredit, runningBalance]);
                const grandRowNum = ws.rowCount;
                ws.mergeCells(`A${grandRowNum}:D${grandRowNum}`);
                grandTotalRow.height = 26;
                grandTotalRow.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                    cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                    cell.border = doubleGoldBorder;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'right' };
                    else if (colNumber >= 5 && colNumber <= 7) {
                        cell.alignment = { vertical: 'middle', horizontal: 'right' };
                        cell.numFmt = '0.00';
                    }
                });
            };

            // 1. SHEET: Client Master (Exact match with user image)
            const wsMaster = workbook.addWorksheet('Client Master', { views: [{ showGridLines: true }] });
            wsMaster.columns = [
                { key: 'colA', width: 6 },   // #
                { key: 'colB', width: 22 },  // Sale / Booking No.
                { key: 'colC', width: 34 },  // Project Name / Client Name
                { key: 'colD', width: 28 },  // Block / Door No. / Milestone
                { key: 'colE', width: 18 },  // Floor / Due Date
                { key: 'colF', width: 16 },  // Unit Type / Total Installment (₹)
                { key: 'colG', width: 18 },  // Agreement Date / Paid Amount (₹)
                { key: 'colH', width: 24 },  // Total Consideration (₹) / Balance Due (₹)
                { key: 'colI', width: 16 }   // Status
            ];

            // Row 1: Blank Spacer
            wsMaster.addRow([]);
            wsMaster.getRow(1).height = 15;

            // Row 2: Top Title Banner (#0B3B2E)
            const mr2 = wsMaster.addRow([projectTitle + '   |   CLIENT MASTER']);
            wsMaster.mergeCells('A2:I2');
            mr2.height = 30;
            const mc2 = wsMaster.getCell('A2');
            mc2.font = { name: 'Calibri', size: 11.5, bold: true, color: { argb: 'FFFFFFFF' } };
            mc2.alignment = { vertical: 'middle', horizontal: 'center' };
            mc2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            for (let c = 1; c <= 9; c++) { 
                wsMaster.getRow(2).getCell(c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                wsMaster.getRow(2).getCell(c).border = goldBorder; 
            }

            // Row 3: Client Details Sub-Banner (#0B3B2E)
            const mr3 = wsMaster.addRow(['Client Details', '', '', '', '', customer.name || 'N/A', '', '', '']);
            wsMaster.mergeCells('A3:E3');
            wsMaster.mergeCells('F3:I3');
            mr3.height = 24;
            for (let c = 1; c <= 9; c++) {
                const cell = wsMaster.getRow(3).getCell(c);
                cell.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                cell.border = goldBorder;
            }
            wsMaster.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };
            wsMaster.getCell('F3').alignment = { vertical: 'middle', horizontal: 'center' };

            // Rows 4, 5, 6: Customer Info Card
            const mr4 = wsMaster.addRow(['Customer ID', ':', customer.id || '-', '', 'Email Address', ':', customer.email || 'N/A', '', '']);
            wsMaster.mergeCells('C4:D4');
            wsMaster.mergeCells('G4:I4');

            const mr5 = wsMaster.addRow(['Full Name', ':', customer.name || 'N/A', '', 'Phone Number', ':', customer.phone || 'N/A', '', '']);
            wsMaster.mergeCells('C5:D5');
            wsMaster.mergeCells('G5:I5');

            const mr6 = wsMaster.addRow(['Status', ':', customer.is_active ? 'ACTIVE' : 'INACTIVE', '', 'Address', ':', customer.address || 'N/A', '', '']);
            wsMaster.mergeCells('C6:D6');
            wsMaster.mergeCells('G6:I6');

            [mr4, mr5, mr6].forEach(r => {
                r.height = 21;
                r.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                    cell.border = thinGrayBorder;
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    if (colNumber === 1 || colNumber === 5) {
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                        cell.alignment = { vertical: 'middle', horizontal: 'left' };
                    } else if (colNumber === 2 || colNumber === 6) {
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    } else {
                        cell.alignment = { vertical: 'middle', horizontal: 'left' };
                    }
                });
            });

            // Row 7: Section 1 Title: ASSOCIATED PROPERTIES & SALES DIRECTORY
            const ptRow = wsMaster.addRow(['ASSOCIATED PROPERTIES & SALES DIRECTORY']);
            const ptNum = wsMaster.rowCount;
            wsMaster.mergeCells(`A${ptNum}:I${ptNum}`);
            ptRow.height = 24;
            const ptCell = wsMaster.getCell(`A${ptNum}`);
            ptCell.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FFFFFFFF' } };
            ptCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            ptCell.alignment = { vertical: 'middle', horizontal: 'center' };
            for (let c = 1; c <= 9; c++) { 
                wsMaster.getRow(ptNum).getCell(c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                wsMaster.getRow(ptNum).getCell(c).border = goldBorder; 
            }

            // Row 8: Table Header: Block / Door No.
            const propHeaders = ['#', 'Sale / Booking No.', 'Project Name', 'Block / Door No.', 'Floor', 'Unit Type', 'Agreement Date', 'Total Consideration (₹)', 'Status'];
            const phr = wsMaster.addRow(propHeaders);
            phr.height = 26;
            phr.eachCell(c => {
                c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                c.alignment = { vertical: 'middle', horizontal: 'center' };
                c.border = goldBorder;
            });

            // Rows 9+: Properties Data Rows
            let totalPropsVal = 0;
            properties.forEach((prop, idx) => {
                const val = Number(prop.total_amount || 0);
                totalPropsVal += val;
                const row = wsMaster.addRow([
                    idx + 1,
                    prop.sale_number || 'N/A',
                    prop.project_name || 'Tabasco Hindustan Infra Developers',
                    prop.unit_name || 'N/A',
                    prop.floor || 'N/A',
                    prop.unit_type || 'N/A',
                    prop.agreement_date || 'N/A',
                    val,
                    prop.status || 'ACTIVE'
                ]);
                row.height = 21;
                row.eachCell((cell, colNumber) => {
                    cell.border = thinGrayBorder;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                    if (colNumber === 1 || colNumber === 2 || colNumber === 5 || colNumber === 6 || colNumber === 7 || colNumber === 9) {
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    } else if (colNumber === 3 || colNumber === 4) {
                        cell.alignment = { vertical: 'middle', horizontal: 'left' };
                    } else if (colNumber === 8) {
                        cell.alignment = { vertical: 'middle', horizontal: 'right' };
                        cell.numFmt = '0.00';
                    }
                });
            });

            if (properties.length === 0) {
                const row = wsMaster.addRow(['-', '-', 'No property sales recorded', '-', '-', '-', '-', '0.00', '-']);
                row.height = 21;
                row.eachCell(c => { c.border = thinGrayBorder; c.alignment = { vertical: 'middle', horizontal: 'center' }; });
            }

            // Total Consideration Row
            const ptotRow = wsMaster.addRow(['Total Consideration Value', '', '', '', '', '', '', totalPropsVal, '']);
            const ptotNum = wsMaster.rowCount;
            wsMaster.mergeCells(`A${ptotNum}:G${ptotNum}`);
            ptotRow.height = 24;
            ptotRow.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                cell.border = doubleGoldBorder;
                if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'right' };
                if (colNumber === 8) { cell.alignment = { vertical: 'middle', horizontal: 'right' }; cell.numFmt = '0.00'; }
            });

            // Blank spacer
            const spRow = wsMaster.addRow([]);
            spRow.height = 16;

            // Section 2 Title: CUSTOMER EMI & INSTALLMENT PAYMENT SCHEDULE
            const emiRow = wsMaster.addRow(['CUSTOMER EMI & INSTALLMENT PAYMENT SCHEDULE']);
            const emiNum = wsMaster.rowCount;
            wsMaster.mergeCells(`A${emiNum}:I${emiNum}`);
            emiRow.height = 24;
            const emiCell = wsMaster.getCell(`A${emiNum}`);
            emiCell.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FFFFFFFF' } };
            emiCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            emiCell.alignment = { vertical: 'middle', horizontal: 'center' };
            for (let c = 1; c <= 9; c++) { 
                wsMaster.getRow(emiNum).getCell(c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                wsMaster.getRow(emiNum).getCell(c).border = goldBorder; 
            }

            // Installments Table Header (Col C is Client Name / Unit)
            const emiHeaders = ['#', 'Sale / Booking No.', 'Client Name', 'Installment / Milestone', 'Due Date', 'Total Installment (₹)', 'Paid Amount (₹)', 'Balance Due (₹)', 'Status'];
            const ehr = wsMaster.addRow(emiHeaders);
            ehr.height = 26;
            ehr.eachCell(c => {
                c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                c.alignment = { vertical: 'middle', horizontal: 'center' };
                c.border = goldBorder;
            });

            // Filter to only paid installments (no unpaid pending)
            const paidInstallments = (installments || []).filter(inst => {
                return Number(inst.paid_amount || 0) > 0 || (inst.status && inst.status.toUpperCase() === 'PAID');
            });

            // Installment Data Rows
            let totalEmiScheduled = 0;
            let totalEmiPaid = 0;
            let totalEmiBalance = 0;

            paidInstallments.forEach((inst, idx) => {
                const amt = Number(inst.amount || 0);
                const paid = Number(inst.paid_amount || 0);
                const bal = Number(inst.balance_amount || 0);
                totalEmiScheduled += amt;
                totalEmiPaid += paid;
                totalEmiBalance += bal;

                const row = wsMaster.addRow([
                    idx + 1,
                    inst.sale_number || 'N/A',
                    inst.unit_name || 'N/A',
                    inst.label || (inst.installment_no === 0 ? 'Down Payment' : `EMI - ${inst.installment_no}`),
                    inst.due_date || 'N/A',
                    amt,
                    paid,
                    bal,
                    inst.status || 'PAID'
                ]);
                row.height = 21;
                row.eachCell((cell, colNumber) => {
                    cell.border = thinGrayBorder;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                    if (colNumber === 1 || colNumber === 2 || colNumber === 4 || colNumber === 5 || colNumber === 9) {
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    } else if (colNumber === 3) {
                        cell.alignment = { vertical: 'middle', horizontal: 'left' };
                    } else if (colNumber >= 6 && colNumber <= 8) {
                        cell.alignment = { vertical: 'middle', horizontal: 'right' };
                        cell.numFmt = '0.00';
                    }
                });
            });

            if (paidInstallments.length === 0) {
                const row = wsMaster.addRow(['-', '-', 'No paid installment records found', '-', '-', '0.00', '0.00', '0.00', '-']);
                row.height = 21;
                row.eachCell(c => { c.border = thinGrayBorder; c.alignment = { vertical: 'middle', horizontal: 'center' }; });
            }

            // Total Installments Row
            const etotRow = wsMaster.addRow(['Total Installments Summary', '', '', '', '', totalEmiScheduled, totalEmiPaid, totalEmiBalance, '']);
            const etotNum = wsMaster.rowCount;
            wsMaster.mergeCells(`A${etotNum}:E${etotNum}`);
            etotRow.height = 24;
            etotRow.eachCell({ includeEmpty: true }, (cell, colNumber) => {
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF000000' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                cell.border = doubleGoldBorder;
                if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'right' };
                if (colNumber >= 6 && colNumber <= 8) { cell.alignment = { vertical: 'middle', horizontal: 'right' }; cell.numFmt = '0.00'; }
            });

            // 2. SHEET: Statement-Loan (Cheque / Bank / Loan transactions)
            const loanTx = allTransactions.filter(t => !t.is_cash);
            buildLedgerSheet('Statement-Loan', 'Loan', loanTx);

            // 3. SHEET: Statement-Group (Cash / Group transactions)
            const groupTx = allTransactions.filter(t => t.is_cash);
            buildLedgerSheet('Statement-Group', 'Group', groupTx);

            // 4. SHEET: Statement-All (All transactions)
            buildLedgerSheet('Statement-All', 'All', allTransactions);

            // 5. SHEET: Summary
            const wsSum = workbook.addWorksheet('Summary', { views: [{ showGridLines: true }] });
            wsSum.columns = [
                { key: 'idx', width: 8 },
                { key: 'metric', width: 42 },
                { key: 'amount', width: 25 },
                { key: 'notes', width: 32 }
            ];

            // Row 1: Blank Spacer
            wsSum.addRow([]);
            wsSum.getRow(1).height = 15;

            // Row 2: Title Banner
            const sr2 = wsSum.addRow([projectTitle + '   |   ACCOUNT STATEMENT SUMMARY']);
            wsSum.mergeCells('A2:D2');
            sr2.height = 30;
            const sc2 = wsSum.getCell('A2');
            sc2.font = { name: 'Calibri', size: 11.5, bold: true, color: { argb: 'FFFFFFFF' } };
            sc2.alignment = { vertical: 'middle', horizontal: 'center' };
            sc2.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            for (let c = 1; c <= 4; c++) { 
                wsSum.getRow(2).getCell(c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                wsSum.getRow(2).getCell(c).border = goldBorder; 
            }

            // Row 3: Client Details Sub-Banner
            const sr3 = wsSum.addRow(['Client Details', '', customer.name || 'N/A', '']);
            wsSum.mergeCells('A3:B3');
            wsSum.mergeCells('C3:D3');
            sr3.height = 24;
            for (let c = 1; c <= 4; c++) {
                const cell = wsSum.getRow(3).getCell(c);
                cell.font = { name: 'Calibri', size: 10.5, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
                cell.border = goldBorder;
            }
            wsSum.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };
            wsSum.getCell('C3').alignment = { vertical: 'middle', horizontal: 'center' };

            wsSum.addRow([]); // Blank spacer

            let totalSales = 0;
            let totalExtraWork = 0;
            let totalChequePaid = 0;
            let totalCashPaid = 0;
            let totalReturns = 0;

            allTransactions.forEach(t => {
                if (t.debit > 0) {
                    if (t.description && t.description.toLowerCase().includes('additional')) {
                        totalExtraWork += Number(t.debit);
                    } else {
                        totalSales += Number(t.debit);
                    }
                }
                if (t.credit > 0) {
                    if (t.description && t.description.toLowerCase().includes('return')) {
                        totalReturns += Number(t.credit);
                    } else if (t.is_cash) {
                        totalCashPaid += Number(t.credit);
                    } else {
                        totalChequePaid += Number(t.credit);
                    }
                }
            });

            const netOutstanding = (totalSales + totalExtraWork) - (totalChequePaid + totalCashPaid + totalReturns);

            const sthRow = wsSum.addRow(['#', 'Financial Metric', 'Amount (₹)', 'Remarks / Channel']);
            sthRow.height = 26;
            sthRow.eachCell(c => {
                c.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                c.alignment = { vertical: 'middle', horizontal: 'center' };
                c.border = goldBorder;
            });

            const summaryItems = [
                { idx: 1, metric: 'Total Sales Consideration', amount: totalSales, notes: 'Total Booked Sales Value' },
                { idx: 2, metric: 'Total Additional Work Value', amount: totalExtraWork, notes: 'Approved Extra Works' },
                { idx: 3, metric: 'Total Paid via Cheque / Bank / Online', amount: totalChequePaid, notes: 'Realized Cheque / RTGS / NEFT' },
                { idx: 4, metric: 'Total Paid via Cash', amount: totalCashPaid, notes: 'Realized Cash Payments' },
                { idx: 5, metric: 'Total Sales Returns / Cancellation Refunds', amount: totalReturns, notes: 'Cancelled Units / Adjustments' }
            ];

            summaryItems.forEach(item => {
                const row = wsSum.addRow([item.idx, item.metric, item.amount, item.notes]);
                row.height = 21;
                row.eachCell((cell, colNumber) => {
                    cell.border = thinGrayBorder;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF000000' } };
                    if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    if (colNumber === 2) cell.alignment = { vertical: 'middle', horizontal: 'left' };
                    if (colNumber === 3) { cell.alignment = { vertical: 'middle', horizontal: 'right' }; cell.numFmt = '0.00'; }
                    if (colNumber === 4) cell.alignment = { vertical: 'middle', horizontal: 'left' };
                });
            });

            const outRow = wsSum.addRow(['', 'NET OUTSTANDING BALANCE PAYABLE', netOutstanding, 'Current Due Balance']);
            outRow.height = 26;
            outRow.eachCell((cell, colNumber) => {
                cell.border = doubleGoldBorder;
                cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF000000' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                if (colNumber === 2) cell.alignment = { vertical: 'middle', horizontal: 'left' };
                if (colNumber === 3) {
                    cell.alignment = { vertical: 'middle', horizontal: 'right' };
                    cell.numFmt = '0.00';
                    cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: netOutstanding > 0 ? 'FFC00000' : 'FF059669' } };
                }
                if (colNumber === 4) cell.alignment = { vertical: 'middle', horizontal: 'left' };
            });

            // Download file
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            const safeName = (customer.name || 'Customer').replace(/[^a-zA-Z0-9_\-\s]/g, '').trim();
            a.download = `${customer.id}.Customer-${safeName}.xlsx`;
            a.click();
            window.URL.revokeObjectURL(url);
            this.showToast('Statement of Account exported successfully.');
        },

        openAddModal() {
            this.errors = {};
            this.forms.add = {
                name: '',
                email: '',
                phone: '',
                address: '',
            };
            this.modals.add.open = true;
        },
        closeAddModal() {
            this.modals.add.open = false;
        },

        submitAddCustomer() {
            this.errors = {};
            let hasError = false;

            if (!this.forms.add.name || !this.forms.add.name.trim()) {
                this.errors.name = ['Please enter customer name.'];
                hasError = true;
            } else if (this.forms.add.name.trim().length < 2) {
                this.errors.name = ['Full Name must be at least 2 characters.'];
                hasError = true;
            }

            if (this.forms.add.email && this.forms.add.email.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.forms.add.email.trim())) {
                    this.errors.email = ['Please enter a valid email address.'];
                    hasError = true;
                }
            }

            if (this.forms.add.phone && this.forms.add.phone.trim()) {
                const phoneRegex = /^[+]*[0-9\s\-()]{7,20}$/;
                if (!phoneRegex.test(this.forms.add.phone.trim())) {
                    this.errors.phone = ['Please enter a valid phone number format.'];
                    hasError = true;
                }
            }

            if (hasError) return;

            fetch('{{ route('customers.store') }}', {
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
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Customer added successfully.');
                    this.closeAddModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        openViewModal(customer) {
            this.viewTarget = customer;
            this.modals.view.open = true;
        },

        openEditModal(customerId) {
            this.editErrors = {};

            // Try to use already-loaded row data first for instant display
            let existing = this.customers.find(c => c.id === customerId);
            if (existing) {
                this.forms.edit = {
                    id: existing.id,
                    name: existing.name || '',
                    email: existing.email || '',
                    phone: existing.phone || '',
                    address: existing.address || '',
                    is_active: existing.is_active ? '1' : '0'
                };
                this.modals.edit.open = true;
            }

            // Fetch the latest data from the server to make sure it's fresh
            fetch(`{{ url('customers') }}/${customerId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                let c = data.customer || data;
                this.forms.edit = {
                    id: c.id,
                    name: c.name || '',
                    email: c.email || '',
                    phone: c.phone || '',
                    address: c.address || '',
                    is_active: c.is_active ? '1' : '0'
                };
                this.modals.edit.open = true;
            })
            .catch(err => {
                console.error('Error fetching customer:', err);
                if (!existing) {
                    this.showToast('Failed to load customer details.', 'error');
                }
            });
        },
        closeEditModal() {
            this.modals.edit.open = false;
        },

        submitEditCustomer() {
            this.editErrors = {};
            let hasError = false;

            if (!this.forms.edit.name || !this.forms.edit.name.trim()) {
                this.editErrors.name = ['Please enter full name.'];
                hasError = true;
            } else if (this.forms.edit.name.trim().length < 2) {
                this.editErrors.name = ['Full Name must be at least 2 characters.'];
                hasError = true;
            }

            if (this.forms.edit.email && this.forms.edit.email.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.forms.edit.email.trim())) {
                    this.editErrors.email = ['Please enter a valid email address.'];
                    hasError = true;
                }
            }

            if (this.forms.edit.phone && this.forms.edit.phone.trim()) {
                const phoneRegex = /^[+]*[0-9\s\-()]{7,20}$/;
                if (!phoneRegex.test(this.forms.edit.phone.trim())) {
                    this.editErrors.phone = ['Please enter a valid phone number format.'];
                    hasError = true;
                }
            }

            if (hasError) return;

            let customerId = this.forms.edit.id;
            let payload = { ...this.forms.edit, _method: 'PUT' };
            fetch(`{{ url('customers') }}/${customerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Server returned non-JSON:', text);
                    this.showToast('Server returned an invalid response. Check console.', 'error');
                    return;
                }
                
                if (res.status === 422) {
                    this.editErrors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || data.message || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Customer updated successfully.');
                    this.closeEditModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        openDeleteModal(customer) {
            this.deleteTarget = customer;
            this.modals.delete.open = true;
        },
        closeDeleteModal() {
            this.modals.delete.open = false;
            this.deleteTarget = null;
        },

        confirmDeleteCustomer() {
            if (!this.deleteTarget) return;
            let customerId = this.deleteTarget.id;

            fetch(`{{ url('customers') }}/${customerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Server returned non-JSON:', text);
                    this.showToast('Server returned an invalid response. Check console.', 'error');
                    return;
                }
                
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to delete customer.', 'error');
                } else {
                    this.showToast('Customer deleted successfully.');
                    this.closeDeleteModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        }
    };
}
</script>

</x-erp-layout>
