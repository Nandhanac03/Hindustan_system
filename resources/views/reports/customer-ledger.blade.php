<x-erp-layout title="Customer Ledger Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" 
     x-data="{
         ...reportsApp(),
         isLoading: false,
         
         fetchCustomerLedger() {
             this.isLoading = true;
             const form = document.getElementById('customerLedgerForm');
             const formData = new FormData(form);
             const params = new URLSearchParams();
             for (const [k, v] of formData.entries()) {
                 if (v) params.append(k, v);
             }
             const url = '{{ route('reports.customer_ledger') }}?' + params.toString();
             window.history.pushState({}, '', url);

             fetch(url, {
                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
             })
             .then(r => r.text())
             .then(html => {
                 const parser = new DOMParser();
                 const doc = parser.parseFromString(html, 'text/html');
                 const newContent = doc.getElementById('ledger-results-wrapper');
                 const currentContent = document.getElementById('ledger-results-wrapper');
                 if (newContent && currentContent) {
                     currentContent.innerHTML = newContent.innerHTML;
                 }
                 this.$nextTick(() => {
                     if (window.renderCustomerLedgerChart) {
                         window.renderCustomerLedgerChart();
                     }
                 });
             })
             .catch(err => console.error('AJAX Ledger load error:', err))
             .finally(() => {
                 this.isLoading = false;
             });
         }
     }">

    @include('reports.partials.nav')

    {{-- Main Header & Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center text-xl shrink-0 shadow-2xs border border-[#a38c29]/30">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    Customer Ledger & Accounts Statement
                </h1>
                <p class="text-xs text-slate-500 font-medium">View comprehensive customer posting ledger, payment allocations, receipts and outstanding balances.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Customer Selection & Filters</h3>

            {{-- Customer Selection & Action Filter Bar --}}
            <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
                <form id="customerLedgerForm" @submit.prevent="fetchCustomerLedger()" method="GET" action="{{ route('reports.customer_ledger') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    @if(request('project_id'))
                        <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                    @endif
                    
                    <div class="flex-1 w-full relative" 
                         x-data="{ 
                             open: false, 
                             search: '',
                             localSelectedIds: @js(is_array(request('customer_id')) ? request('customer_id') : (request('customer_id') ? [request('customer_id')] : [])),
                             toggleCustomer(id) {
                                 const strId = id.toString();
                                 const idx = this.localSelectedIds.indexOf(strId);
                                 if (idx > -1) {
                                     this.localSelectedIds.splice(idx, 1);
                                 } else {
                                     this.localSelectedIds.push(strId);
                                 }
                                 this.$nextTick(() => fetchCustomerLedger());
                             },
                             clearAll() {
                                 this.localSelectedIds = [];
                                 this.search = '';
                                 this.$nextTick(() => fetchCustomerLedger());
                             },
                             isSelected(id) {
                                 return this.localSelectedIds.includes(id.toString());
                             },
                             get selectedCustomers() {
                                 return (customerList || []).filter(c => this.isSelected(c.id));
                             }
                          }" 
                         @click.outside="open = false">
                        
                        <template x-for="id in localSelectedIds" :key="id">
                            <input type="hidden" name="customer_id[]" :value="id">
                        </template>

                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Select Customer(s) for Ledger & Account Statement
                        </label>
                        
                        <div class="relative flex-1">
                            <button type="button" 
                                    @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                                    :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                                    class="w-full min-h-[42px] px-3.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs">
                                <template x-if="selectedCustomers.length > 0">
                                    <div class="flex flex-wrap items-center gap-1.5 overflow-hidden min-w-0 flex-1 py-0.5">
                                        <template x-for="c in selectedCustomers" :key="c.id">
                                            <span class="inline-flex items-center gap-1.5 pl-2 pr-1.5 py-0.5 rounded-lg bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 text-xs font-bold">
                                                <span class="w-4 h-4 rounded-full bg-[#a38c29] text-white font-black text-[9px] flex items-center justify-center shrink-0" x-text="c.name.charAt(0).toUpperCase()"></span>
                                                <span x-text="c.name" class="truncate max-w-[160px]"></span>
                                                <button type="button" @click.stop="toggleCustomer(c.id)" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors" title="Remove customer">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="selectedCustomers.length === 0">
                                    <span class="text-slate-500 font-bold">— All Customers —</span>
                                </template>
                                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                    <template x-if="selectedCustomers.length > 0">
                                        <span @click.stop="clearAll()" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selected customers">
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
                                 class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
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
                                            <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                        </template>
                                    </div>
                                </div>

                                <button type="button" @click="clearAll()"
                                        class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>— Clear Selection (All Customers) —</span>
                                </button>

                                <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                    <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                                        <button type="button"
                                                @click="toggleCustomer(customer.id)"
                                                :class="isSelected(customer.id) ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div :class="isSelected(customer.id) ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                                     class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors">
                                                    <template x-if="isSelected(customer.id)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                    <template x-if="!isSelected(customer.id)">
                                                        <span x-text="(customer.name || '?').charAt(0).toUpperCase()"></span>
                                                    </template>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs truncate leading-snug" :class="isSelected(customer.id) ? 'text-[#8a7522]' : 'text-slate-800'" x-text="customer.name"></p>
                                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 font-mono mt-0.5" x-show="customer.phone">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                            <span x-text="customer.phone"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <template x-if="isSelected(customer.id)">
                                                <svg class="w-4 h-4 text-[#a38c29] shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </button>
                                    </template>
                                    <div x-show="getFilteredCustomersList(search).length === 0"
                                         class="py-6 px-4 text-center">
                                        <p class="text-xs text-slate-400 italic">No matching customers found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons: Generate Statement (AJAX), Print, Export Excel --}}
                    <div class="flex items-center gap-2 self-stretch sm:self-auto shrink-0">
                        <button type="submit" 
                                :disabled="isLoading"
                                class="h-[42px] px-5 bg-[#a38c29] hover:bg-[#8a7522] disabled:opacity-50 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow flex items-center justify-center gap-2 cursor-pointer">
                            <template x-if="isLoading">
                                <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <template x-if="!isLoading">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </template>
                            <span>Generate Statement</span>
                        </button>
                        <button type="button" @click="printReport()" 
                                class="h-[42px] px-4 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold rounded-xl transition shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print
                        </button>
                        <button type="button" @click="exportCurrentTable()" 
                                class="h-[42px] px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export Excel
                        </button>
                    </div>
                </form>
            </div>

            {{-- Results Container (Refreshed dynamically via AJAX) --}}
            <div id="ledger-results-wrapper" class="space-y-6 relative min-h-[200px]">
                <div x-show="isLoading" class="absolute inset-0 bg-white/70 backdrop-blur-xs z-30 flex items-center justify-center rounded-2xl transition-opacity duration-200" style="display: none;">
                    <div class="flex items-center gap-3 px-5 py-3 bg-white border border-slate-200 shadow-xl rounded-2xl">
                        <svg class="w-5 h-5 animate-spin text-[#a38c29]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-xs font-bold text-slate-800">Updating Customer Ledger Statement...</span>
                    </div>
                </div>

                @if($selectedCustomers && $selectedCustomers->isNotEmpty())
                <div id="ledger-results"></div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    {{-- Card 1: Customer Statement & Financial Overview --}}
                    <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-[#a38c29]/30 shadow-2xs flex flex-col justify-between space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 border border-[#a38c29]/30 flex items-center justify-center text-[#8a7522] font-bold shrink-0">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-black text-[#8a7522] uppercase tracking-widest block">Statement Target</span>
                                    <h4 class="text-sm font-extrabold text-slate-900 truncate max-w-md">
                                        {{ $selectedCustomers->pluck('name')->implode(', ') }}
                                    </h4>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200/80 text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider shrink-0 ml-2">
                                {{ $selectedCustomers->count() > 1 ? $selectedCustomers->count() . ' Customers Selected' : ($selectedCustomers->first()->phone ?? 'Single Account') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 pt-1">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Total Billed / Sale</span>
                                <span class="text-sm font-mono font-black text-slate-900 block">₹{{ number_format($totalDebits, 2) }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Total Paid Receipts</span>
                                <span class="text-sm font-mono font-black text-emerald-700 block">₹{{ number_format($totalCredits, 2) }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-rose-700 uppercase tracking-wider block">Net Outstanding</span>
                                <span class="text-sm font-mono font-black text-rose-700 block">₹{{ number_format($closingBalance, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Financial Ledger Mix & Recovery Donut Visualizer --}}
                    <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">Financial Ledger Mix</h4>
                                <p class="text-[10px] text-slate-400 font-medium">Collections vs Pending Dues Balance</p>
                            </div>
                            @php
                                $pct = $totalDebits > 0 ? min(100, round(($totalCredits / $totalDebits) * 100, 1)) : 0;
                            @endphp
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black">
                                {{ $pct }}% Collected
                            </span>
                        </div>

                        <div class="grid grid-cols-12 items-center gap-2">
                            <div class="col-span-6 flex justify-center">
                                <div id="customerLedgerDonutChart" class="w-full h-32" 
                                     data-credits="{{ $totalCredits }}" 
                                     data-dues="{{ $closingBalance }}"></div>
                            </div>
                            <div class="col-span-6 space-y-2 text-[11px] font-medium pl-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Collected</span>
                                        <span class="font-mono font-bold text-slate-800 truncate block">₹{{ number_format($totalCredits, 0) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Outstanding</span>
                                        <span class="font-mono font-bold text-slate-800 truncate block">₹{{ number_format($closingBalance, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1 pt-1">
                            <div class="w-full h-2 rounded-full bg-rose-100 overflow-hidden flex">
                                <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $pct }}%"></div>
                                <div class="h-full bg-rose-500 transition-all duration-500" style="width: {{ 100 - $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl" id="ledger-table">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 text-white font-extrabold">Posting Date</th>
                                @if($selectedCustomers->count() > 1)
                                    <th class="px-5 py-3.5 text-white font-extrabold">Customer Name</th>
                                @endif
                                <th class="px-5 py-3.5 text-white font-extrabold">Voucher / Ref No.</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Narrative</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Mode</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Debit (Due)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Credit (Receipt)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                            @forelse($ledgerEntries as $row)
                            <tr class="hover:bg-slate-50/60 font-semibold">
                                <td class="px-5 py-3 text-slate-500 font-sans">{{ $row['date'] }}</td>
                                @if($selectedCustomers->count() > 1)
                                    <td class="px-5 py-3 font-bold font-sans text-slate-900">{{ $row['customer_name'] ?? '-' }}</td>
                                @endif
                                <td class="px-5 py-3 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                                <td class="px-5 py-3 font-sans text-slate-800">{{ $row['description'] }}</td>
                                <td class="px-5 py-3 font-sans text-slate-450">{{ $row['payment_mode'] }}</td>
                                <td class="px-5 py-3 text-right text-rose-600">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700">{{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}</td>
                                <td class="px-5 py-3 text-right text-slate-900 font-extrabold">₹{{ number_format($row['balance'] ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $selectedCustomers->count() > 1 ? 8 : 7 }}" class="px-5 py-12 text-center text-slate-400 italic">No chronological allocations found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator && $ledgerEntries->hasPages())
                <div class="px-5 py-3 border-t border-slate-200 bg-slate-50">
                    {{ $ledgerEntries->appends(request()->query())->links() }}
                </div>
                @endif
                @else
                {{-- DEFAULT FULL DISPLAY — ALL CUSTOMERS LEDGER SUMMARY --}}
                <div class="space-y-6">
                    {{-- Top 4 KPI Metric Cards (Top-Aligned Small Icons with Background Reflections) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Card 1: Total Sales Agreements --}}
                        <div class="bg-gradient-to-br from-white via-white to-blue-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-blue-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Sales Agreements</span>
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono">₹{{ number_format($totalDebits, 2) }}</div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Combined Sales Value</span>
                            </div>
                        </div>

                        {{-- Card 2: Total Collections --}}
                        <div class="bg-gradient-to-br from-white via-white to-emerald-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-emerald-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Collections</span>
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-emerald-700 truncate font-mono">₹{{ number_format($totalCredits, 2) }}</div>
                                <span class="text-[10px] text-emerald-600/80 font-semibold block">Total Receipts Received</span>
                            </div>
                        </div>

                        {{-- Card 3: Net Outstanding Due --}}
                        <div class="bg-gradient-to-br from-white via-white to-rose-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-rose-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Net Outstanding Due</span>
                                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs border border-rose-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-rose-700 truncate font-mono">₹{{ number_format($closingBalance, 2) }}</div>
                                <span class="text-[10px] text-rose-600/80 font-semibold block">Overall Pending Receivables</span>
                            </div>
                        </div>

                        {{-- Card 4: Active Customer Accounts --}}
                        <div class="bg-gradient-to-br from-white via-white to-amber-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Active Customer Accounts</span>
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs border border-amber-200/60 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono">{{ count($customerSummaryList) }}</div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Customers with Active Sales</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-white flex items-center justify-between border-b border-slate-200/80">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">All Customers Account Balances Directory</h4>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Overview of customer agreements, total payments received, and current outstanding dues.</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-50 text-[#8a7522] border border-amber-200/80 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                {{ $customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customerSummaryList->total() : count($customerSummaryList) }} Customers
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-5 py-3.5 text-white font-extrabold">SL NO</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Customer Name & Contact</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Project / Unit</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Sale (₹)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Paid (₹)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Outstanding (₹)</th>
                                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Last Payment</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @forelse($customerSummaryList as $idx => $cs)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="px-5 py-4 font-mono font-bold text-slate-400">{{ ($customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($customerSummaryList->currentPage() - 1) * $customerSummaryList->perPage() : 0) + $idx + 1 }}</td>
                                        <td class="px-5 py-4">
                                            <div class="font-extrabold text-slate-900 text-sm">{{ $cs['customer_name'] }}</div>
                                            <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                                {{ $cs['phone'] ?? 'No phone' }}
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-bold text-slate-800">{{ $cs['project'] }}</div>
                                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 text-slate-600 inline-block mt-0.5">
                                                Unit: {{ $cs['unit'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-bold text-slate-900">
                                            ₹{{ number_format($cs['total_amount'], 2) }}
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-bold text-emerald-700">
                                            ₹{{ number_format($cs['paid_amount'], 2) }}
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-black text-rose-600">
                                            ₹{{ number_format($cs['outstanding'], 2) }}
                                        </td>
                                        <td class="px-5 py-4 text-center font-mono text-[11px] text-slate-500">
                                            {{ $cs['last_payment'] }}
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('reports.customer_ledger', ['customer_id' => $cs['customer_id'], 'project_id' => request('project_id')]) }}"
                                               class="px-3.5 py-1.5 bg-[#a38c29]/15 hover:bg-[#a38c29] text-[#8a7522] hover:text-white border border-[#a38c29]/30 rounded-xl text-[10px] font-black uppercase tracking-wider transition inline-flex items-center gap-1">
                                                <span>View Ledger</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">No active customer accounts found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator && $customerSummaryList->hasPages())
                        <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50">
                            {{ $customerSummaryList->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-white flex items-center justify-between border-b border-slate-200/80">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">System-Wide Customer Ledger Transaction Log</h4>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Chronological transaction history combining sale agreements and receipts across all customers.</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-50 text-[#8a7522] border border-amber-200/80 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                {{ $ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ledgerEntries->total() : count($ledgerEntries) }} Transactions
                            </span>
                        </div>

                        <div class="overflow-x-auto max-h-[500px]">
                            <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-5 py-3.5 text-white font-extrabold">Posting Date</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Customer Name</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Voucher / Ref No.</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Narrative Description</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Mode</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Debit (Agreement)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Credit (Receipt)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                                    @forelse($ledgerEntries as $row)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="px-5 py-3.5 text-slate-500 font-sans text-[11px]">{{ $row['date'] }}</td>
                                        <td class="px-5 py-3.5 font-bold font-sans text-slate-900">{{ $row['customer_name'] ?? '-' }}</td>
                                        <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                                        <td class="px-5 py-3.5 font-sans text-slate-800">{{ $row['description'] }}</td>
                                        <td class="px-5 py-3.5 font-sans">
                                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 text-slate-600 inline-block">{{ $row['payment_mode'] }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right text-rose-600 font-bold">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                                        <td class="px-5 py-3.5 text-right text-emerald-700 font-bold">{{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic font-sans">No customer ledger transactions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator && $ledgerEntries->hasPages())
                        <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50">
                            {{ $ledgerEntries->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
