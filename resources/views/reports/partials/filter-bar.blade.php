@props([
    'formId' => 'reportFilterForm',
    'actionRoute' => '',
    'exportLabel' => 'Export Excel',
    'showCustomer' => true
])

<form id="{{ $formId }}" method="GET" action="{{ $actionRoute }}" class="flex flex-wrap items-center gap-2.5 shrink-0 w-full">
    @if(request('project_id'))
        <input type="hidden" name="project_id" value="{{ request('project_id') }}">
    @endif
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    @if(request('section'))
        <input type="hidden" name="section" value="{{ request('section') }}">
    @endif
    @if(request('date_from'))
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
    @endif
    @if(request('date_to'))
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
    @endif
    @if(request('payment_mode'))
        <input type="hidden" name="payment_mode" value="{{ request('payment_mode') }}">
    @endif
    @if(request('partner_id'))
        <input type="hidden" name="partner_id" value="{{ request('partner_id') }}">
    @endif
    @if(request('broker_id'))
        <input type="hidden" name="broker_id" value="{{ request('broker_id') }}">
    @endif

    @if($showCustomer)
    {{-- Customer Multi-Select Dropdown Filter --}}
    <div class="flex-1 min-w-[250px] relative" 
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
                 this.submitForm();
             },
             
             submitForm() {
                 this.$nextTick(() => {
                     document.getElementById('{{ $formId }}').submit();
                 });
             },
             
             get selectedCustomers() {
                 return (this.customerList || []).filter(c => this.localSelectedIds.includes(c.id.toString()));
             }
         }" 
         @click.outside="open = false">
         
        <template x-for="id in localSelectedIds">
            <input type="hidden" name="customer_id[]" :value="id">
        </template>

        <div class="relative w-full">
            <div role="button" tabindex="0"
                 @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                 :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                 class="w-full min-h-[42px] px-2.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs">
                <template x-if="selectedCustomers.length > 0">
                    <div class="flex flex-wrap items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                        <template x-for="c in selectedCustomers" :key="c.id">
                            <span class="inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded-lg bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-[10px] font-bold">
                                <span x-text="c.name" class="whitespace-nowrap max-w-[150px] truncate"></span>
                                <button type="button" @click.stop="toggleCustomer(c.id)" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </template>
                <template x-if="selectedCustomers.length === 0">
                    <div class="flex items-center gap-1.5 text-slate-500 font-bold px-1">
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Filter by Customers</span>
                    </div>
                </template>
                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                    <template x-if="selectedCustomers.length > 0">
                        <span @click.stop="localSelectedIds = []; search = ''; submitForm();" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </span>
                    </template>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                 class="absolute right-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-[100]"
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

                <button type="button" @click="localSelectedIds = []; submitForm();"
                        class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>— Clear Selection (All Customers) —</span>
                </button>

                <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                    <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                        <button type="button"
                                @click="toggleCustomer(customer.id)"
                                :class="localSelectedIds.includes(customer.id.toString()) ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div :class="localSelectedIds.includes(customer.id.toString()) ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                     class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors">
                                     <template x-if="localSelectedIds.includes(customer.id.toString())">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                     </template>
                                     <template x-if="!localSelectedIds.includes(customer.id.toString())">
                                        <span x-text="(customer.name || '?').charAt(0).toUpperCase()"></span>
                                     </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs truncate leading-snug" :class="localSelectedIds.includes(customer.id.toString()) ? 'text-[#8a7522]' : 'text-slate-800'" x-text="customer.name"></p>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 font-mono mt-0.5" x-show="customer.phone">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span x-text="customer.phone"></span>
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
    @endif

    {{-- Print & Export Action Buttons --}}
    <button type="button" @click="printReport()" 
            class="h-[42px] px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print
    </button>
    <button type="button" @click="exportCurrentTable()" 
            class="h-[42px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        {{ $exportLabel ?? 'Export Excel' }}
    </button>
</form>
