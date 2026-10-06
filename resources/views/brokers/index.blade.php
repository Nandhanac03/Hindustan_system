<x-erp-layout title="Agent Master Directory" headerTitle="Agent Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="brokerMasterFilterApp()">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Agent Master Directory</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage real estate brokers, track default commission percentages, and monitor general profile details.</p>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 rounded-2xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- Filter Panel (Instant Client-Side Filtering - No Refresh) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
            
            {{-- 1. Searchable Broker Filter (Golden Theme Styling) --}}
            <div class="relative" @click.outside="brokerFilterOpen = false">
                <div @click="brokerFilterOpen = !brokerFilterOpen; if(brokerFilterOpen) { projectFilterOpen = false; sortOpen = false; brokerFilterSearch = ''; $nextTick(() => $refs.brokerFilterSearchInput?.focus()); }"
                     class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                     :class="brokerFilterOpen || brokerId ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                    <div class="flex items-center gap-2 truncate">
                        <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="brokerFilterOpen || brokerId ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="truncate font-extrabold" :class="brokerId ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSelectedBrokerName()"></span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="brokerFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Search Popover (Theme Golden Style) --}}
                <div x-show="brokerFilterOpen" x-transition
                     class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                     style="display: none;">
                    
                    {{-- Search Header --}}
                    <div class="p-2 bg-[#a38c29]/10 border-b border-[#a38c29]/20 sticky top-0 z-10">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#a38c29]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text"
                                   x-model="brokerFilterSearch"
                                   x-ref="brokerFilterSearchInput"
                                   placeholder="Search broker name..."
                                   class="w-full pl-8 pr-3 py-1.5 bg-white border border-[#a38c29]/40 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/30 rounded-lg text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none transition-all">
                        </div>
                    </div>

                    {{-- Broker Options List --}}
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectBroker('')"
                             class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                             :class="!brokerId ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                            <span class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>All Brokers</span>
                            </span>
                            <span class="text-[9.5px] font-medium" :class="!brokerId ? 'text-white/80' : 'text-slate-400'">({{ count($allBrokers ?? $brokers) }} registered)</span>
                        </div>
                        
                        <template x-for="b in filteredBrokers" :key="b.id">
                            <div @click="selectBroker(String(b.id))"
                                 class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                 :class="String(brokerId) === String(b.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                <span x-text="b.name"></span>
                                <span class="px-2 py-0.5 rounded text-[9.5px] font-mono font-black shrink-0 ml-2"
                                      :class="String(brokerId) === String(b.id) ? 'bg-[#a38c29] text-white' : 'bg-[#a38c29]/10 text-[#8a7522]'"
                                      x-text="b.default_commission_pct ? Number(b.default_commission_pct).toFixed(2) + '%' : ''">
                                </span>
                            </div>
                        </template>
                        
                        <div x-show="filteredBrokers.length === 0" class="px-3.5 py-4 text-center text-slate-400 text-xs italic">
                            No brokers found matching query
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Project Filter (Custom Gold Popover - First Project Selected by Default) --}}
            <div class="relative" @click.outside="projectFilterOpen = false">
                <div @click="projectFilterOpen = !projectFilterOpen; if(projectFilterOpen) { brokerFilterOpen = false; sortOpen = false; }"
                     class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                     :class="projectFilterOpen || projectId ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                    <div class="flex items-center gap-2 truncate">
                        <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="projectFilterOpen || projectId ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="truncate font-extrabold" :class="projectId ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSelectedProjectName()"></span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="projectFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Project Popover Menu --}}
                <div x-show="projectFilterOpen" x-transition
                     class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                     style="display: none;">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectProject('')"
                             class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                             :class="!projectId ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                            <span>All Projects</span>
                        </div>
                        <template x-for="p in projectsList" :key="p.id">
                            <div @click="selectProject(String(p.id))"
                                 class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                 :class="String(projectId) === String(p.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                <span x-text="p.name"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 3. Custom Gold Sort By Dropdown --}}
            <div class="relative" @click.outside="sortOpen = false">
                <div @click="sortOpen = !sortOpen; if(sortOpen) { brokerFilterOpen = false; projectFilterOpen = false; }"
                     class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                     :class="sortOpen || sortBy ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                    <div class="flex items-center gap-2 truncate">
                        <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="sortOpen || sortBy ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <span class="truncate font-extrabold" :class="sortBy ? 'text-[#8a7522]' : 'text-slate-900'" x-text="getSortLabel()"></span>
                    </div>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="sortOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Sort Popover Menu --}}
                <div x-show="sortOpen" x-transition
                     class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                     style="display: none;">
                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                        <div @click="selectSort('')"
                             class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                             :class="!sortBy ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                            <span>Sort by Name (A-Z)</span>
                        </div>
                        <div @click="selectSort('deals_desc')"
                             class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                             :class="sortBy === 'deals_desc' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                            <span>Deals Count (High to Low)</span>
                        </div>
                        <div @click="selectSort('accrued_desc')"
                             class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                             :class="sortBy === 'accrued_desc' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                            <span>Accrued Commission (High to Low)</span>
                        </div>
                        <div @click="selectSort('payable_desc')"
                             class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                             :class="sortBy === 'payable_desc' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                            <span>Payable Commission (High to Low)</span>
                        </div>
                        <div @click="selectSort('rate_desc')"
                             class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                             :class="sortBy === 'rate_desc' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                            <span>Default Rate (High to Low)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button" @click="resetFilters()"
               class="h-[38px] inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
            <button type="button" @click="openRegister = true"
                    class="h-[38px] inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] px-5 text-xs font-black text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Broker</span>
            </button>
        </div>
    </div>

    {{-- Register Modal --}}
    <div x-show="openRegister" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none; background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;" 
         x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.away="openRegister = false">
              {{-- Header --}}
              <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                  <div>
                      <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BROKERAGE DIRECTORY</span>
                      <h3 class="font-black text-base uppercase tracking-wider text-white">REGISTER BROKER</h3>
                  </div>
                  <button type="button" @click="openRegister = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
              </div>

              <form action="{{ route('brokers.store') }}" method="POST" x-data="{ errors: {}, name: '', default_commission_pct: '2.00', submitRegister(e) { let errs = {}; if(!this.name || !String(this.name).trim()) errs.name = ['The broker name field is required.']; if(!this.default_commission_pct) errs.default_commission_pct = ['The commission % field is required.']; if(Object.keys(errs).length > 0) { e.preventDefault(); this.errors = errs; return false; } } }" @submit="submitRegister($event)" novalidate class="p-6 space-y-4 text-xs font-sans bg-white">
                  @csrf
                  <div class="space-y-1.5">
                      <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Broker / Agency Name <span class="text-rose-500">*</span></label>
                      <input type="text" name="name" x-model="name" required placeholder="e.g. Apex Realty Brokers"
                             :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                             class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs text-slate-900 font-bold transition-all shadow-xs">
                      <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                  </div>

                  <div class="space-y-1.5">
                      <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                          <span>Default Commission % <span class="text-rose-500">*</span></span>
                          <span class="text-slate-400 font-normal text-[9px]">(Typically 2% per sale)</span>
                      </label>
                      <div class="relative">
                          <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" x-model="default_commission_pct" required
                                 :class="errors.default_commission_pct ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                 class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs text-slate-900 pr-8 font-mono font-bold transition-all shadow-xs">
                          <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                      </div>
                      <template x-if="errors.default_commission_pct"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.default_commission_pct) ? errors.default_commission_pct[0] : errors.default_commission_pct"></p></template>
                      <p class="text-[9px] text-slate-400 font-medium">This percentage is applied by default to all project sales handled by this broker.</p>
                  </div>

                  <div class="p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl text-[10px] text-amber-900 space-y-1">
                      <span class="font-bold flex items-center gap-1">
                          <svg class="w-3 h-3 text-[#a38c29]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                          Automated Accounting Integration:
                      </span>
                      <p>A dedicated liability ledger account will be automatically created in the accounts master for tracking commissions payable.</p>
                  </div>

                  <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                      <button type="button" @click="openRegister = false" 
                              class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                          CANCEL
                      </button>
                      <button type="submit" 
                              class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">
                          SAVE PROFILE
                      </button>
                  </div>
              </form>
         </div>
    </div>

    {{-- Registered Brokers Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #brokers-table thead th { border-color: #8a7522 !important; }
            #brokers-tbody tr:nth-child(even) { background-color: #F6F3E9 !important; }
            #brokers-tbody tr:hover { background-color: #ebe5d0 !important; }
        </style>
        <div class="overflow-x-auto">
            <table id="brokers-table" class="w-full text-xs text-left min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Broker</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Default Rate</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Deals Closed</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Accrued (Locked)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Payable (Unlocked)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Paid Out</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="brokers-tbody" class="divide-y divide-slate-100">
                    <template x-for="broker in computedBrokers()" :key="broker.id">
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3 border text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29] flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0"
                                         x-text="getInitials(broker.name)">
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight" x-text="broker.name"></span>
                                        <span class="text-[9px] text-slate-500 font-medium" x-text="(broker.linked_account?.name || broker.linkedAccount?.name || 'Unlinked') + ' (' + (broker.linked_account?.code || broker.linkedAccount?.code || 'N/A') + ')'"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]"
                                      x-text="Number(broker.default_commission_pct || 0).toFixed(2) + '%'">
                                </span>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]"
                                      x-text="broker.total_deals + ' Deals'">
                                </span>
                                <span class="block text-[9px] text-slate-500 font-mono mt-0.5" x-text="'₹' + formatCurrency(broker.total_sale_value)"></span>
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-amber-700"
                                x-text="'₹' + formatCurrency(broker.accrued_commission)">
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-emerald-600"
                                x-text="'₹' + formatCurrency(broker.payable_commission)">
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-indigo-600"
                                x-text="'₹' + formatCurrency(broker.paid_commission)">
                            </td>
                            <td class="px-3 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(broker)" title="View Broker Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(broker)" title="Edit Broker Rate" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button x-show="broker.total_deals === 0" @click="openDeleteModal(broker)" title="Delete Broker" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <button x-show="broker.total_deals > 0" disabled class="p-2 rounded-lg bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed shadow-sm" title="Cannot delete broker with associated sales">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="computedBrokers().length === 0" style="display: none;">
                        <td colspan="7" class="px-3 py-12 border text-center text-slate-500 italic">No brokers found matching current filter selection.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Single View Modal (Bound to selectedBroker) --}}
    <div x-show="openView" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
         style="display: none; background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;" 
         x-transition.opacity>
         <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden" @click.away="openView = false">
             {{-- Header --}}
             <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                 <div>
                     <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BROKER PROFILE</span>
                     <h3 class="font-black text-base uppercase tracking-wider text-white">PROFILE & LEDGER DETAILS</h3>
                 </div>
                 <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
             </div>

             <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white" x-show="selectedBroker">
                 <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                     <div>
                         <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Broker / Agency Name</span>
                         <span class="text-sm font-extrabold text-slate-900" x-text="selectedBroker?.name"></span>
                     </div>
                     <div class="text-right">
                         <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Commission Structure</span>
                         <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20"
                               x-text="Number(selectedBroker?.default_commission_pct || 0).toFixed(2) + '% Default'">
                         </span>
                     </div>
                 </div>

                 <div class="grid grid-cols-2 gap-3">
                     <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50">
                         <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Linked Ledger Account</span>
                         <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedBroker?.linked_account?.name || selectedBroker?.linkedAccount?.name || 'Unlinked'"></span>
                         <span class="text-[9px] font-mono text-slate-500 block" x-text="'Code: ' + (selectedBroker?.linked_account?.code || selectedBroker?.linkedAccount?.code || 'N/A')"></span>
                     </div>
                     <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50">
                         <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Total Deals & Sales</span>
                         <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="(selectedBroker?.total_deals || 0) + ' Closed Deal(s)'"></span>
                         <span class="text-[9px] font-mono text-slate-500 block" x-text="'Value: ₹' + formatCurrency(selectedBroker?.total_sale_value)"></span>
                     </div>
                 </div>

                 <div class="grid grid-cols-3 gap-3">
                     <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-center">
                         <span class="text-[9px] font-bold text-amber-800 uppercase block">Accrued (Locked)</span>
                         <span class="text-xs font-bold font-mono text-amber-900 mt-1 block" x-text="'₹' + formatCurrency(selectedBroker?.accrued_commission)"></span>
                     </div>
                     <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                         <span class="text-[9px] font-bold text-emerald-800 uppercase block">Payable (Ready)</span>
                         <span class="text-xs font-bold font-mono text-emerald-900 mt-1 block" x-text="'₹' + formatCurrency(selectedBroker?.payable_commission)"></span>
                     </div>
                     <div class="p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                         <span class="text-[9px] font-bold text-indigo-800 uppercase block">Total Paid Out</span>
                         <span class="text-xs font-bold font-mono text-indigo-900 mt-1 block" x-text="'₹' + formatCurrency(selectedBroker?.paid_commission)"></span>
                     </div>
                 </div>
             </div>

             <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                 <button type="button" @click="openView = false" 
                         class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                     CLOSE
                 </button>
                 <a :href="'{{ route('brokers.payable-report') }}?broker_id=' + (selectedBroker ? selectedBroker.id : '')" 
                    class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md inline-flex items-center gap-1.5 cursor-pointer">
                     <span>Full Ledger Statement</span>
                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                 </a>
             </div>
         </div>
    </div>

    {{-- Single Edit Modal (Bound to selectedBroker) --}}
    <div x-show="openEdit" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
         style="display: none; background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;" 
         x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden" @click.away="openEdit = false">
             {{-- Header --}}
             <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                 <div>
                     <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EDIT BROKER</span>
                     <h3 class="font-black text-base uppercase tracking-wider text-white truncate max-w-[280px]" x-text="selectedBroker?.name"></h3>
                 </div>
                 <button type="button" @click="openEdit = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
             </div>

             <form :action="'{{ url('brokers') }}/' + (selectedBroker ? selectedBroker.id : '')" method="POST" @submit="submitEdit($event)" novalidate class="p-6 space-y-4 text-xs font-sans bg-white">
                 @csrf
                 @method('PUT')
                 <div class="space-y-1.5">
                     <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Broker / Agency Name <span class="text-rose-500">*</span></label>
                     <input type="text" name="name" x-model="editName" required
                            :class="editErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                            class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs text-slate-900 font-bold transition-all shadow-xs">
                     <template x-if="editErrors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.name) ? editErrors.name[0] : editErrors.name"></p></template>
                 </div>

                 <div class="space-y-1.5">
                     <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Default Commission % <span class="text-rose-500">*</span></label>
                     <div class="relative">
                         <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" x-model="editCommissionPct" required
                                :class="editErrors.default_commission_pct ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs text-slate-900 pr-8 font-mono font-bold transition-all shadow-xs">
                         <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                     </div>
                     <template x-if="editErrors.default_commission_pct"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.default_commission_pct) ? editErrors.default_commission_pct[0] : editErrors.default_commission_pct"></p></template>
                 </div>

                 <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                     <button type="button" @click="openEdit = false" 
                             class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                         CANCEL
                     </button>
                     <button type="submit" 
                             class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">
                         UPDATE CHANGES
                     </button>
                 </div>
             </form>
         </div>
    </div>

    {{-- Single Delete Modal (Bound to selectedBroker) --}}
    <div x-show="openDelete" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
         style="display: none; background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;" 
         x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openDelete = false">
             {{-- Header --}}
             <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-500/10">
                 <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                 <div class="relative z-10 flex items-center justify-between gap-4">
                     <div>
                         <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">SAFETY CHECK</span>
                         <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">DELETE BROKER</h2>
                     </div>
                     <button type="button" @click="openDelete = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer">✕</button>
                 </div>
             </div>
             
             <form method="POST" :action="'{{ url('brokers') }}/' + (selectedBroker ? selectedBroker.id : '')">
                 @csrf
                 @method('DELETE')
                 <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                     <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-2">
                         <p class="text-sm text-slate-700">
                             Are you sure you want to delete broker <span class="font-bold text-slate-900" x-text="selectedBroker?.name"></span>?
                         </p>
                         <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">THIS ACTION CANNOT BE UNDONE AND WILL REMOVE THE RECORD.</p>
                     </div>
                 </div>
                 <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                     <button type="button" @click="openDelete = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">CANCEL</button>
                     <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer">CONFIRM DELETE</button>
                 </div>
             </form>
         </div>
    </div>

</div>

<script>
function brokerMasterFilterApp() {
    return {
        brokerId: @json(request('broker_id', '')),
        projectId: @json($selectedProjectId ?? request('project_id', '')),
        sortBy: @json(request('sort_by', '')),
        brokerFilterOpen: false,
        brokerFilterSearch: '',
        projectFilterOpen: false,
        sortOpen: false,
        openRegister: false,
        openView: false,
        openEdit: false,
        openDelete: false,
        selectedBroker: null,
        editName: '',
        editCommissionPct: '2.00',
        editErrors: {},
        rawBrokers: @json($brokers ?? []),
        allBrokersList: @json($allBrokers ?? $brokers ?? []),
        projectsList: @json($projects ?? []),

        get filteredBrokers() {
            const list = this.allBrokersList || [];
            if (!this.brokerFilterSearch || !this.brokerFilterSearch.trim()) return list;
            const q = this.brokerFilterSearch.toLowerCase().trim();
            return list.filter(function(b) {
                return b.name && b.name.toLowerCase().indexOf(q) !== -1;
            });
        },

        getSelectedBrokerName() {
            if (!this.brokerId) return 'All Brokers';
            const currentId = String(this.brokerId);
            const b = (this.allBrokersList || []).find(function(x) {
                return String(x.id) === currentId;
            });
            return b ? b.name : 'All Brokers';
        },

        selectBroker(id) {
            this.brokerId = id;
            this.brokerFilterOpen = false;
        },

        getSelectedProjectName() {
            if (!this.projectId) return 'All Projects';
            const currentId = String(this.projectId);
            const p = (this.projectsList || []).find(function(x) {
                return String(x.id) === currentId;
            });
            return p ? p.name : 'All Projects';
        },

        selectProject(id) {
            this.projectId = id;
            this.projectFilterOpen = false;
        },

        getSortLabel() {
            if (this.sortBy === 'deals_desc') return 'Deals Count (High to Low)';
            if (this.sortBy === 'accrued_desc') return 'Accrued Commission (High to Low)';
            if (this.sortBy === 'payable_desc') return 'Payable Commission (High to Low)';
            if (this.sortBy === 'rate_desc') return 'Default Rate (High to Low)';
            return 'Sort by Name (A-Z)';
        },

        selectSort(val) {
            this.sortBy = val;
            this.sortOpen = false;
        },

        resetFilters() {
            this.brokerId = '';
            this.brokerFilterSearch = '';
            this.projectId = (this.projectsList && this.projectsList.length > 0) ? String(this.projectsList[0].id) : '';
            this.sortBy = '';
        },

        computedBrokers() {
            const list = this.rawBrokers || [];
            const projId = this.projectId ? String(this.projectId) : '';
            const brkId = this.brokerId ? String(this.brokerId) : '';
            const sortBy = this.sortBy;

            // Compute dynamic stats per broker based on selected project
            let result = list.map(b => {
                const matchingBrokerages = (b.brokerages || []).filter(entry => {
                    if (!projId) return true;
                    const saleProjId = entry.sale?.project_id || entry.sale?.unit?.project_id;
                    return String(saleProjId) === projId;
                });

                const total_deals = matchingBrokerages.length;
                let total_sale_value = 0;
                let accrued_commission = 0;
                let payable_commission = 0;
                let paid_commission = 0;

                matchingBrokerages.forEach(entry => {
                    const saleAmt = parseFloat(entry.sale?.total_amount || 0) || 0;
                    total_sale_value += saleAmt;

                    const commAmt = parseFloat(entry.commission_amount || 0) || 0;
                    const paidAmt = parseFloat(entry.paid_amount || 0) || 0;

                    paid_commission += paidAmt;
                    const remaining = Math.max(0, commAmt - paidAmt);

                    if (entry.status === 'pending' && paidAmt <= 0) {
                        accrued_commission += remaining;
                    } else if (remaining > 0) {
                        payable_commission += remaining;
                    }
                });

                return {
                    ...b,
                    total_deals: total_deals,
                    total_sale_value: total_sale_value,
                    accrued_commission: accrued_commission,
                    payable_commission: payable_commission,
                    paid_commission: paid_commission,
                    total_commission: accrued_commission + payable_commission + paid_commission
                };
            });

            // Filter by Broker if selected
            if (brkId) {
                result = result.filter(b => String(b.id) === brkId);
            }

            // Sort results
            if (sortBy === 'deals_desc') {
                result.sort((a, b) => (b.total_deals || 0) - (a.total_deals || 0));
            } else if (sortBy === 'accrued_desc') {
                result.sort((a, b) => (b.accrued_commission || 0) - (a.accrued_commission || 0));
            } else if (sortBy === 'payable_desc') {
                result.sort((a, b) => (b.payable_commission || 0) - (a.payable_commission || 0));
            } else if (sortBy === 'rate_desc') {
                result.sort((a, b) => (parseFloat(b.default_commission_pct) || 0) - (parseFloat(a.default_commission_pct) || 0));
            } else {
                result.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
            }

            return result;
        },

        openViewModal(b) {
            this.selectedBroker = b;
            this.openView = true;
        },

        openEditModal(b) {
            this.selectedBroker = b;
            this.editName = b.name || '';
            this.editCommissionPct = b.default_commission_pct || '2.00';
            this.editErrors = {};
            this.openEdit = true;
        },

        openDeleteModal(b) {
            this.selectedBroker = b;
            this.openDelete = true;
        },

        submitEdit(e) {
            let errs = {};
            if (!this.editName || !String(this.editName).trim()) errs.name = ['The broker name field is required.'];
            if (!this.editCommissionPct) errs.default_commission_pct = ['The commission % field is required.'];
            if (Object.keys(errs).length > 0) {
                e.preventDefault();
                this.editErrors = errs;
                return false;
            }
        },

        formatCurrency(num) {
            if (num === null || num === undefined || isNaN(num)) return '0.00';
            return Number(num).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        getInitials(name) {
            if (!name) return 'BR';
            return String(name).substring(0, 2).toUpperCase();
        }
    };
}
</script>

</x-erp-layout>
