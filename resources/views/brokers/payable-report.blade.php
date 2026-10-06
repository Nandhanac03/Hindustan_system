<x-erp-layout title="Broker Statement & Payout Ledger" headerTitle="Brokerage Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="brokerPayoutApp()" x-init="init()">

    {{-- Top Header & Reports Export Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Broker Statement & Payout Ledger</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Track broker commission share, payouts released and current net balance owed.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Record Broker Payout Button --}}
                <button @click="openPayoutModal()" 
                        class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Record Broker Payout</span>
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-[#a38c29]/10 border border-[#a38c29]/30 rounded-2xl flex items-center justify-between text-[#7c691c] text-xs font-bold shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between text-rose-800 text-xs font-bold shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
            </div>
        @endif

        {{-- 4 Metric KPI Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Registered Brokers / Commission Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">REGISTERED BROKERS</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        {{ count($brokers) }} Brokers
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Active Broker Network</p>
                </div>
            </div>

            {{-- Card 2: Earned Commission Share --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">EARNED COMMISSION SHARE</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300" x-text="formatCurrency(totalCredit)">
                        Rs. {{ number_format($totalAccrued + $totalPayable + $totalPaid, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total broker commission allocated to date</p>
                </div>
            </div>

            {{-- Card 3: Total Payouts Released --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL PAYOUTS RELEASED</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300" x-text="formatCurrency(totalDebit)">
                        Rs. {{ number_format($totalPaid, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total commission payouts released to date</p>
                </div>
            </div>

            {{-- Card 4: Current Net Balance Owed --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">CURRENT NET PAYABLE BALANCE</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-[#a38c29] font-mono tracking-tight block group-hover:text-[#8e7a23] transition-colors duration-300" x-text="formatCurrency(totalRunningBalance)">
                        Rs. {{ number_format($totalAccrued + $totalPayable, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Earned Commission Share - Payouts Released</p>
                </div>
            </div>

        </div>

        {{-- Search & Filter Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 flex-1">
                    
                    {{-- 1. Searchable Broker Filter (Golden Theme Styling) --}}
                    <div class="relative" @click.outside="brokerFilterOpen = false">
                        <div @click="brokerFilterOpen = !brokerFilterOpen; if(brokerFilterOpen) { projectFilterOpen = false; statusFilterOpen = false; brokerFilterSearch = ''; $nextTick(() => $refs.brokerFilterSearchInput?.focus()); }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="brokerFilterOpen || filters.broker_id ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="brokerFilterOpen || filters.broker_id ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="filters.broker_id ? 'text-[#8a7522]' : 'text-slate-900'" x-text="selectedFilterBrokerName"></span>
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
                                <div @click="filters.broker_id = ''; brokerFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!filters.broker_id ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>All Brokers</span>
                                    </span>
                                    <span class="text-[9.5px] font-medium" :class="!filters.broker_id ? 'text-white/80' : 'text-slate-400'">({{ count($brokers) }} registered)</span>
                                </div>
                                
                                <template x-for="b in filteredSearchableBrokers" :key="b.id">
                                    <div @click="filters.broker_id = String(b.id); brokerFilterOpen = false; currentPage = 1"
                                         class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                         :class="String(filters.broker_id) === String(b.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                        <span x-text="b.name"></span>
                                        <span class="px-2 py-0.5 rounded text-[9.5px] font-mono font-black shrink-0 ml-2"
                                              :class="String(filters.broker_id) === String(b.id) ? 'bg-[#a38c29] text-white' : 'bg-[#a38c29]/10 text-[#8a7522]'"
                                              x-text="formatCurrency(b.payable_commission || 0)">
                                        </span>
                                    </div>
                                </template>
                                
                                <div x-show="filteredSearchableBrokers.length === 0" class="px-3.5 py-4 text-center text-slate-400 text-xs italic">
                                    No brokers found matching query
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Project Filter (Custom Gold Popover - First Project Selected by Default) --}}
                    <div class="relative" @click.outside="projectFilterOpen = false">
                        <div @click="projectFilterOpen = !projectFilterOpen; if(projectFilterOpen) { brokerFilterOpen = false; statusFilterOpen = false; }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="projectFilterOpen || filters.project_id ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="projectFilterOpen || filters.project_id ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="filters.project_id ? 'text-[#8a7522]' : 'text-slate-900'" x-text="selectedFilterProjectName"></span>
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
                                <div @click="filters.project_id = ''; projectFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!filters.project_id ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span>All Projects</span>
                                </div>
                                @foreach($projects as $proj)
                                    <div @click="filters.project_id = '{{ $proj->id }}'; projectFilterOpen = false; currentPage = 1"
                                         class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                         :class="String(filters.project_id) === '{{ $proj->id }}' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                        <span>{{ $proj->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 3. Status Filter (Custom Gold Popover - Theme Color without blue browser dropdown) --}}
                    <div class="relative" @click.outside="statusFilterOpen = false">
                        <div @click="statusFilterOpen = !statusFilterOpen; if(statusFilterOpen) { brokerFilterOpen = false; projectFilterOpen = false; }"
                             class="w-full h-[38px] px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition-all duration-200 shadow-2xs"
                             :class="statusFilterOpen || filters.status ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-slate-50 hover:bg-white border-slate-250 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 shrink-0 transition-colors duration-200" :class="statusFilterOpen || filters.status ? 'text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate font-extrabold" :class="filters.status ? 'text-[#8a7522]' : 'text-slate-900'" x-text="selectedFilterStatusName"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="statusFilterOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Status Popover Menu (Golden theme) --}}
                        <div x-show="statusFilterOpen" x-transition
                             class="absolute left-0 right-0 z-50 mt-1.5 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-64 flex flex-col min-w-[240px]"
                             style="display: none;">
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="filters.status = ''; statusFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer text-xs font-extrabold transition-all flex items-center justify-between"
                                     :class="!filters.status ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c]'">
                                    <span>All Statuses (Pending, Partial, Fully Paid)</span>
                                </div>
                                <div @click="filters.status = 'pending'; statusFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="filters.status === 'pending' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Pending (Unpaid / Payable Share)</span>
                                </div>
                                <div @click="filters.status = 'partial'; statusFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="filters.status === 'partial' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Partially Paid</span>
                                </div>
                                <div @click="filters.status = 'paid'; statusFilterOpen = false; currentPage = 1"
                                     class="px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-all"
                                     :class="filters.status === 'paid' ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 hover:bg-[#a38c29]/10 hover:text-[#7c691c] font-bold'">
                                    <span>Fully Paid / Disbursed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Reset Filters Button --}}
                <div class="shrink-0 flex items-center">
                    <button type="button" @click="resetFilters()"
                            class="h-[38px] px-5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer whitespace-nowrap group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Section A: Individual Broker Statement of Account (Running Ledger Table) --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">A. Individual Broker Statement of Account (Running Ledger)</h2>
                </div>
                <span class="text-xs font-bold text-slate-400" x-text="filteredLedger.length + ' records found'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Transaction Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Broker Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Reference / Voucher No.</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Description / Transaction Type</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Commission Share Allocated<br><span class="text-[9px] font-normal text-white/80">(Credit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Payout Released<br><span class="text-[9px] font-normal text-white/80">(Debit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Running Payable Balance<br><span class="text-[9px] font-normal text-white/80">(Rs.)</span></th>
                            <th class="px-4 py-3.5 text-center text-white font-extrabold w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(entry, index) in pagedLedger" :key="index">
                            <tr class="hover:bg-slate-50 transition-colors font-medium">
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100" x-text="formatDate(entry.date)"></td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100 whitespace-nowrap" x-text="entry.broker_name"></td>
                                <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100" x-text="entry.ref_no"></td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span x-text="entry.description"></span>
                                        <template x-if="entry.payment_mode">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold tracking-wider uppercase border shadow-2xs"
                                                  :class="{
                                                      'bg-emerald-50 text-emerald-700 border-emerald-200': entry.payment_mode === 'Bank Transfer',
                                                      'bg-blue-50 text-blue-700 border-blue-200': entry.payment_mode === 'Cheque',
                                                      'bg-amber-50 text-amber-700 border-amber-200': entry.payment_mode === 'Cash',
                                                      'bg-purple-50 text-purple-700 border-purple-200': entry.payment_mode === 'UPI / Online' || entry.payment_mode === 'Online'
                                                  }"
                                                  x-text="entry.payment_mode">
                                            </span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(entry.credit)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(entry.debit)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(entry.running_balance)"></td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap" @click.stop>
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        {{-- PDF / Print Receipt Icon Button (Matching Partner Statements Receipt Column) --}}
                                        <button type="button" 
                                                @click="downloadReceiptPdf(entry)" 
                                                title="Download Receipt"
                                                class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] border border-[#09876B]/20 hover:border-[#09876B]/40 transition inline-flex items-center justify-center shadow-sm cursor-pointer"
                                                aria-label="Download Receipt">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredLedger.length === 0">
                            <td colspan="8" class="px-5 py-8 text-center text-slate-400 font-semibold text-xs">
                                No transactions found matching the selected filter criteria.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#a38c29]/10 font-black text-slate-900 border-t-2 border-[#a38c29]/30">
                            <td colspan="4" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">TOTALS</td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalCredit)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalDebit)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-slate-900 font-black text-sm border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalRunningBalance)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    SHOWING <span class="text-slate-900 font-black" x-text="filteredLedger.length > 0 ? ((currentPage - 1) * pageSize + 1) : 0"></span> TO <span class="text-slate-900 font-black" x-text="Math.min(currentPage * pageSize, filteredLedger.length)"></span> OF <span class="text-slate-900 font-black" x-text="filteredLedger.length"></span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" 
                            @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage <= 1" 
                            class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors shadow-2xs">
                        PREV
                    </button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p" 
                                :class="currentPage === p ? 'bg-[#a38c29] text-white font-black' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold'"
                                class="w-8 h-8 rounded-lg text-xs transition-colors cursor-pointer"
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="if(currentPage < totalPages) currentPage++" 
                            :disabled="currentPage >= totalPages" 
                            class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Record Broker Payout Modal --}}
    <div x-show="payoutModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
         style="display: none; background-color: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;" 
         x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.away="payoutModalOpen = false">
            {{-- Header --}}
            <div class="bg-[#2a2415] px-6 py-4 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-0.5">BROKER DISBURSEMENT SETUP</span>
                    <h3 class="font-black text-sm sm:text-base uppercase tracking-wider text-white">Record Broker Payout</h3>
                </div>
                <button type="button" @click="payoutModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-sm transition cursor-pointer">✕</button>
            </div>

            <form action="{{ route('brokers.payout') }}" method="POST" class="p-6 space-y-4 text-xs font-sans bg-white" @submit="validatePayoutForm($event)" novalidate>
                @csrf
                <input type="hidden" name="broker_id" :value="modalData.broker_id">
                <input type="hidden" name="commission_entry_id" :value="modalData.commission_entry_id">

                {{-- Row 1: Select Broker & Associated Sale (Custom Golden Search & Select Dropdowns) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 1. Select Broker (Custom Golden Search & Select) --}}
                    <div class="space-y-1.5 relative" @click.outside="modalBrokerOpen = false">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">
                            SELECT BROKER <span class="text-rose-500">*</span>
                        </label>
                        
                        <div @click="modalBrokerOpen = !modalBrokerOpen; if(modalBrokerOpen) { modalBrokerSearch = ''; $nextTick(() => $refs.modalBrokerSearchInput?.focus()); }"
                             class="w-full h-9 px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition shadow-2xs"
                             :class="modalErrors.broker_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : (modalBrokerOpen ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-white border-slate-300 hover:border-[#a38c29]/60 text-slate-800')">
                            <div class="flex items-center gap-2 truncate">
                                <template x-if="modalSelectedBroker">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="font-bold text-slate-900 truncate" x-text="modalSelectedBroker.name"></span>
                                        <span class="px-1.5 py-0.5 rounded bg-[#a38c29]/10 text-[#8a7522] font-mono font-bold text-[9.5px] shrink-0" x-text="'Avail: ' + formatCurrency(modalSelectedBroker.payable_commission || 0)"></span>
                                    </div>
                                </template>
                                <template x-if="!modalSelectedBroker">
                                    <span class="text-slate-400 font-medium">Select Broker...</span>
                                </template>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="modalBrokerOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <span x-show="modalErrors.broker_id" x-text="modalErrors.broker_id" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>

                        {{-- Broker Search Dropdown Popover --}}
                        <div x-show="modalBrokerOpen" 
                             x-transition
                             class="absolute left-0 right-0 z-50 mt-1 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-56 flex flex-col"
                             style="display: none;">
                            <div class="p-2 border-b border-[#a38c29]/20 bg-[#a38c29]/10 sticky top-0 z-10">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#a38c29]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" 
                                           x-model="modalBrokerSearch" 
                                           x-ref="modalBrokerSearchInput"
                                           placeholder="Search broker name..." 
                                           class="w-full pl-8 pr-3 py-1 bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-lg text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none">
                                </div>
                            </div>

                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                <template x-for="b in filteredModalBrokers" :key="b.id">
                                    <div @click="selectModalBroker(b.id)"
                                         class="px-3.5 py-2 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="String(modalData.broker_id) === String(b.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800'">
                                        <span class="font-bold text-slate-900 truncate" x-text="b.name"></span>
                                        <span class="px-2 py-0.5 rounded font-mono font-black text-[9.5px] shrink-0 ml-2"
                                              :class="String(modalData.broker_id) === String(b.id) ? 'bg-[#a38c29] text-white' : 'bg-[#a38c29]/10 text-[#8a7522]'"
                                              x-text="formatCurrency(b.payable_commission || 0)">
                                        </span>
                                    </div>
                                </template>
                                <div x-show="filteredModalBrokers.length === 0" class="px-3 py-4 text-center text-slate-400 text-xs italic">
                                    No brokers found matching query
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Associated Sale (Custom Golden Search & Select) --}}
                    <div class="space-y-1.5 relative" @click.outside="modalSaleOpen = false">
                        <div class="flex items-center justify-between">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                ASSOCIATED SALE
                            </label>
                            <span class="text-[9.5px] text-slate-400 font-bold" x-show="modalBrokerSales.length > 0" x-text="modalBrokerSales.length + ' deal(s) available'"></span>
                        </div>

                        <div @click="modalSaleOpen = !modalSaleOpen; if(modalSaleOpen) { modalSaleSearch = ''; $nextTick(() => $refs.modalSaleSearchInput?.focus()); }"
                             class="w-full h-9 px-3 border rounded-xl text-xs font-bold cursor-pointer flex items-center justify-between transition shadow-2xs"
                             :class="modalSaleOpen ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-white border-slate-300 hover:border-[#a38c29]/60 text-slate-800'">
                            <div class="flex items-center gap-2 truncate">
                                <template x-if="modalSelectedSale">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="font-bold text-slate-900 truncate" x-text="modalSelectedSale.sale_number"></span>
                                        <span class="px-1.5 py-0.5 rounded bg-[#a38c29]/10 text-[#8a7522] font-mono font-bold text-[9.5px] shrink-0" x-text="'Unpaid: ' + formatCurrency(modalSelectedSale.remaining)"></span>
                                    </div>
                                </template>
                                <template x-if="!modalSelectedSale && modalData.broker_id">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="text-slate-800 font-bold truncate">All Deals (Auto-Distribute)</span>
                                        <span class="px-1.5 py-0.5 rounded bg-[#a38c29]/10 text-[#8a7522] font-mono font-bold text-[9.5px] shrink-0" x-text="'Avail: ' + formatCurrency(modalSelectedBrokerBalance)"></span>
                                    </div>
                                </template>
                                <template x-if="!modalSelectedSale && !modalData.broker_id">
                                    <span class="text-slate-400 font-medium truncate">Select Associated Sale (or All Deals)...</span>
                                </template>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="modalSaleOpen ? 'rotate-180 text-[#a38c29]' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Sale Search Dropdown Popover --}}
                        <div x-show="modalSaleOpen" 
                             x-transition
                             class="absolute left-0 right-0 z-50 mt-1 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-56 flex flex-col"
                             style="display: none;">
                            <div class="p-2 border-b border-[#a38c29]/20 bg-[#a38c29]/10 sticky top-0 z-10">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#a38c29]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" 
                                           x-model="modalSaleSearch" 
                                           x-ref="modalSaleSearchInput"
                                           placeholder="Search sale #, broker, unit, customer, project..." 
                                           class="w-full pl-8 pr-3 py-1 bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-lg text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none">
                                </div>
                            </div>

                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                {{-- Option 1: All Associated Deals (Visible when broker is selected) --}}
                                <template x-if="modalData.broker_id">
                                    <div @click="selectModalSale('')"
                                         class="px-3.5 py-2.5 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="!modalData.commission_entry_id ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800 font-bold'">
                                        <div>
                                            <div class="font-extrabold text-slate-900">All Associated Deals</div>
                                            <div class="text-[9.5px] text-slate-400 font-medium">Auto-distribute payout across pending deals</div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded font-mono font-black text-[9.5px] shrink-0 ml-2"
                                              :class="!modalData.commission_entry_id ? 'bg-[#a38c29] text-white' : 'bg-[#a38c29]/10 text-[#8a7522]'"
                                              x-text="formatCurrency(modalSelectedBrokerBalance)">
                                        </span>
                                    </div>
                                </template>

                                {{-- Specific Deals --}}
                                <template x-for="sale in filteredModalSales" :key="sale.id">
                                    <div @click="selectModalSale(sale.id)"
                                         class="px-3.5 py-2 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="String(modalData.commission_entry_id) === String(sale.id) ? 'bg-[#a38c29]/15 border-l-4 border-[#a38c29] font-black text-[#7c691c]' : 'text-slate-800'">
                                        <div class="min-w-0 pr-2">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="font-bold text-slate-900 truncate" x-text="sale.sale_number"></span>
                                                <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 font-semibold text-[9px] shrink-0" x-text="sale.broker_name"></span>
                                            </div>
                                            <div class="text-[9.5px] text-slate-500 font-medium truncate mt-0.5" x-text="sale.subDetails || 'Deal'"></div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="px-2 py-0.5 rounded font-mono font-black text-[9.5px] block"
                                                  :class="String(modalData.commission_entry_id) === String(sale.id) ? 'bg-[#a38c29] text-white' : (sale.remaining > 0 ? 'bg-[#a38c29]/10 text-[#8a7522]' : 'bg-slate-100 text-slate-400')"
                                                  x-text="'Unpaid: ' + formatCurrency(sale.remaining)">
                                            </span>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="modalBrokerSales.length > 0 && filteredModalSales.length === 0" class="px-3 py-4 text-center text-slate-400 text-xs italic">
                                    No sales found matching search
                                </div>
                                <div x-show="modalBrokerSales.length === 0" class="px-3 py-4 text-center text-slate-400 text-xs italic">
                                    No commission sales available
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Payout Amount & Pay From Account --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Payout Amount --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">PAYOUT AMOUNT (₹) <span class="text-rose-500">*</span></label>
                            <span class="text-[10px] text-slate-500 font-bold">
                                Max: <span class="font-mono text-emerald-700" x-text="formatCurrency(modalMaxPayable)"></span>
                            </span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-xs font-black text-slate-400">₹</span>
                            <input type="number" step="0.01" min="0.01" :max="modalMaxPayable"
                                   name="amount"
                                   x-model.number="modalData.amount"
                                   @input="delete modalErrors.amount"
                                   data-no-words="true"
                                   :class="modalErrors.amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-300 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white focus:bg-white'"
                                   class="w-full h-9 pl-7 pr-3 rounded-xl text-xs font-bold text-slate-900 font-mono focus:outline-none transition shadow-2xs border"
                                   placeholder="Enter payout amount...">
                        </div>
                        <span x-show="modalErrors.amount" x-text="modalErrors.amount" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                        
                        {{-- Amount in Words Badge (Theme Golden Color) --}}
                        <div x-show="modalPayoutAmountInWords" 
                             class="mt-1 px-2 py-0.5 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#8a7522] font-extrabold text-[9.5px] capitalize tracking-wide shadow-2xs">
                            <span x-text="modalPayoutAmountInWords"></span>
                        </div>
                    </div>

                    {{-- Pay From Account --}}
                    <div class="space-y-1.5 relative" @click.outside="modalBankOpen = false">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">
                            PAY FROM ACCOUNT <span class="text-rose-500">*</span>
                        </label>
                        
                        <input type="hidden" name="company_bank_account_id" :value="modalData.company_bank_account_id">

                        <div @click="modalBankOpen = !modalBankOpen; if(modalBankOpen) { modalBankSearch = ''; $nextTick(() => $refs.modalBankSearchInput?.focus()); }"
                             class="w-full h-9 px-3 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs"
                             :class="modalErrors.company_bank_account_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : (modalBankOpen ? 'bg-white border-[#a38c29] ring-2 ring-[#a38c29]/20 text-slate-900' : 'bg-white border-slate-300 hover:border-[#a38c29]/60 text-slate-800')">
                            <template x-if="modalSelectedBankAccount">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="px-1.5 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[9px]" x-text="modalSelectedBankAccount.bank_name"></span>
                                    <span class="font-bold text-slate-800 truncate text-[11px]" x-text="modalSelectedBankAccount.account_name || modalSelectedBankAccount.bank_name"></span>
                                    <span class="text-slate-500 text-[9px] font-mono shrink-0" x-text="'(A/C: ' + (modalSelectedBankAccount.account_number || '—') + ')'"></span>
                                </div>
                            </template>
                            <template x-if="!modalSelectedBankAccount">
                                <span class="text-slate-400 font-medium">Select Company Bank Account...</span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="modalBankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <span x-show="modalErrors.company_bank_account_id" x-text="modalErrors.company_bank_account_id" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>

                        {{-- Bank Balance in Words --}}
                        <div x-show="modalSelectedBankBalanceInWords" 
                             class="mt-1 px-2 py-0.5 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#8a7522] font-extrabold text-[9.5px] capitalize tracking-wide shadow-2xs">
                            <span x-text="modalSelectedBankBalanceInWords"></span>
                        </div>

                        {{-- Dropdown List --}}
                        <div x-show="modalBankOpen" 
                             x-transition
                             class="absolute left-0 right-0 z-50 mt-1 bg-white border-2 border-[#a38c29]/40 rounded-xl shadow-[0_12px_36px_-6px_rgba(163,140,41,0.25)] overflow-hidden max-h-56 flex flex-col"
                             style="display: none;">
                            <div class="p-2 border-b border-[#a38c29]/20 bg-[#a38c29]/10 sticky top-0 z-10">
                                <input type="text" 
                                       x-model="modalBankSearch" 
                                       x-ref="modalBankSearchInput"
                                       placeholder="Search bank name, account no..." 
                                       class="w-full pl-3 pr-3 py-1 bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-lg text-xs font-medium focus:outline-none">
                            </div>

                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                <template x-for="acc in filteredModalBankAccounts" :key="acc.id">
                                    <div @click="modalData.company_bank_account_id = String(acc.id); modalBankOpen = false; modalBankSearch = ''"
                                         class="px-3 py-2 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="String(modalData.company_bank_account_id) === String(acc.id) ? 'bg-[#a38c29]/15 font-bold border-l-4 border-[#a38c29]' : ''">
                                        <div class="flex flex-col min-w-0 pr-2">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                            </div>
                                            <div class="text-[9px] text-slate-400 font-mono mt-0.5 truncate" x-text="'A/C: ' + (acc.account_number || '—')"></div>
                                        </div>
                                        <div class="text-right font-mono shrink-0">
                                            <div class="text-[8px] text-slate-400 uppercase font-sans font-bold">Balance</div>
                                            <div class="font-bold text-slate-800 text-[11px]" x-text="formatCurrency(acc.current_balance !== null && acc.current_balance !== undefined ? acc.current_balance : (acc.opening_balance || 0))"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Payment Mode & Payment Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Payment Mode --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">PAYMENT MODE <span class="text-rose-500">*</span></label>
                        <select name="payment_mode" x-model="modalData.payment_mode" @change="delete modalErrors.payment_mode"
                                class="w-full pl-3.5 pr-8 py-2 bg-white border border-slate-300 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                            @if(isset($paymentModes) && count($paymentModes) > 0)
                                @foreach($paymentModes as $pm)
                                    <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                @endforeach
                            @else
                                <option value="Bank Transfer (NEFT / RTGS / IMPS)">Bank Transfer (NEFT / RTGS / IMPS)</option>
                                <option value="Cheque">Cheque</option>
                                <option value="UPI / Online Payment">UPI / Online Payment</option>
                                <option value="Cash">Cash</option>
                            @endif
                        </select>
                    </div>

                    {{-- Payment Date --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">PAYMENT DATE <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" x-model="modalData.date"
                               @input="delete modalErrors.date"
                               :class="modalErrors.date ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-300 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white focus:bg-white'"
                               class="w-full px-3 py-2 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs border">
                        <span x-show="modalErrors.date" x-text="modalErrors.date" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                    </div>
                </div>

                {{-- Row 4: Transaction Ref & Remarks --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">TRANSACTION / CHEQUE / UTR NO. <span class="text-rose-500">*</span></label>
                        <input type="text" name="reference_no" x-model="modalData.reference_no"
                               @input="delete modalErrors.reference_no"
                               placeholder="e.g. UTR1087349137 or Cheque Ref"
                               :class="modalErrors.reference_no ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500' : 'border-slate-300 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white focus:bg-white'"
                               class="w-full px-3 py-2 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs border">
                        <span x-show="modalErrors.reference_no" x-text="modalErrors.reference_no" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">REMARKS / NOTES (OPTIONAL)</label>
                        <input type="text" name="remarks" x-model="modalData.remarks"
                               placeholder="e.g. Commission clearance for sale"
                               class="w-full px-3 py-2 border border-slate-300 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 bg-white focus:bg-white rounded-xl text-xs font-medium text-slate-800 focus:outline-none transition shadow-2xs">
                    </div>
                </div>

                {{-- Live Error Banner --}}
                <template x-if="modalErrorMessage">
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2.5 text-rose-700 text-xs font-bold shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="modalErrorMessage"></span>
                    </div>
                </template>

                {{-- Live Dynamic Financial Impact Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    
                    {{-- Left Card: Bank Treasury Outflow Impact --}}
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-2.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <div class="w-5 h-5 rounded-md bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-800 truncate">
                                    Bank Treasury Source
                                </span>
                            </div>
                            <template x-if="modalSelectedBankAccount">
                                <span class="px-2 py-0.5 rounded text-[9.5px] font-bold font-mono bg-blue-50 text-blue-700 border border-blue-200/80 shrink-0"
                                      x-text="modalSelectedBankAccount.bank_name">
                                </span>
                            </template>
                        </div>

                        <template x-if="modalSelectedBankAccount">
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10.5px] font-bold text-slate-500">Current Ledger Balance:</span>
                                    <span class="font-mono font-black text-slate-900 text-xs shrink-0" x-text="formatCurrency(modalSelectedBankBalance)"></span>
                                </div>
                                
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10.5px] font-bold text-slate-500">Post-Payout Projected:</span>
                                    <span class="font-mono font-black text-xs shrink-0" 
                                          :class="modalBankBalanceAfterPayout < 0 ? 'text-rose-600 font-black' : 'text-emerald-700'" 
                                          x-text="formatCurrency(modalBankBalanceAfterPayout)"></span>
                                </div>

                                <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[9.5px] font-bold uppercase tracking-wide text-slate-400">Funds Status</span>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider"
                                          :class="modalBankBalanceAfterPayout < 0 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'"
                                          x-text="modalBankBalanceAfterPayout < 0 ? 'Insufficient Balance' : 'Sufficient Funds'">
                                    </span>
                                </div>
                            </div>
                        </template>

                        <template x-if="!modalSelectedBankAccount">
                            <div class="py-4 text-center text-slate-400 italic text-[11px]">
                                Select a company bank account to preview treasury impact.
                            </div>
                        </template>
                    </div>

                    {{-- Right Card: Broker Settlement & Balance Impact --}}
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs space-y-2.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <div class="w-5 h-5 rounded-md bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-800 truncate"
                                      x-text="modalSelectedSale ? 'Deal Settlement Balance' : 'Broker Payable Balance'">
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9.5px] font-bold uppercase tracking-wider bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 shrink-0"
                                  x-text="modalSelectedSale ? 'Specific Deal' : 'All Deals'">
                            </span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10.5px] font-bold text-slate-500" x-text="modalSelectedSale ? 'Available Sale Liability:' : 'Available Payable Bal:'"></span>
                                <span class="font-mono font-bold text-emerald-700 text-xs shrink-0" x-text="formatCurrency(modalMaxPayable)"></span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10.5px] font-bold text-slate-500">Payout Outflow Amount:</span>
                                <span class="font-mono font-bold text-rose-600 text-xs shrink-0" x-text="'- ' + formatCurrency(modalPayoutAmount)"></span>
                            </div>

                            <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                <span class="text-[10.5px] font-black text-slate-900 uppercase tracking-tight" x-text="modalSelectedSale ? 'Sale Bal After Payout:' : 'Broker Bal After Payout:'"></span>
                                <span class="font-mono font-black text-slate-900 text-xs shrink-0 px-2 py-0.5 rounded bg-slate-100 border border-slate-200/90" 
                                      x-text="formatCurrency(modalBalanceAfterPayout)"></span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="payoutModalOpen = false" 
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" 
                            :disabled="Boolean(modalErrorMessage) || !modalData.broker_id || !modalData.company_bank_account_id"
                            :class="Boolean(modalErrorMessage) || !modalData.broker_id || !modalData.company_bank_account_id ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-[#a38c29] hover:bg-[#8a7522] cursor-pointer shadow-md'"
                            class="px-5 py-2 text-white text-xs font-black uppercase tracking-wider rounded-xl transition inline-flex items-center gap-2">
                        <span>CONFIRM & POST PAYOUT</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function numberToWords(val) {
    let num = Math.floor(parseFloat(val) || 0);
    if (!num || num <= 0) return '';
    const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    function toWords(n) {
        if (n < 20) return a[n];
        let digit = n % 10;
        return b[Math.floor(n / 10)] + (digit ? ' ' + a[digit] : '');
    }
    let str = '';
    let crore = Math.floor(num / 10000000);
    num %= 10000000;
    let lakh = Math.floor(num / 100000);
    num %= 100000;
    let thousand = Math.floor(num / 1000);
    num %= 1000;
    let hundred = Math.floor(num / 100);
    let rest = num % 100;
    if (crore > 0) str += toWords(crore) + ' Crore ';
    if (lakh > 0) str += toWords(lakh) + ' Lakh ';
    if (thousand > 0) str += toWords(thousand) + ' Thousand ';
    if (hundred > 0) str += toWords(hundred) + ' Hundred ';
    if (rest > 0) str += (str !== '' ? 'and ' : '') + toWords(rest) + ' ';
    return str.trim() + ' Rupees Only';
}

function brokerPayoutApp() {
    return {
        rawLedger: @json($runningLedger) || [],
        brokers: @json($brokers) || [],
        projects: @json($projects) || [],
        companyBankAccounts: @json($companyBankAccounts) || [],
        paymentModes: @json($paymentModes) || [],

        brokerFilterOpen: false,
        brokerFilterSearch: '',
        projectFilterOpen: false,
        statusFilterOpen: false,

        filters: {
            broker_id: '',
            project_id: '',
            status: '',
            date_range: 'inception',
            from_date: '',
            to_date: ''
        },

        currentPage: 1,
        pageSize: 15,

        payoutModalOpen: false,
        modalBankOpen: false,
        modalBankSearch: '',
        modalBrokerOpen: false,
        modalBrokerSearch: '',
        modalSaleOpen: false,
        modalSaleSearch: '',
        modalErrors: {},
        modalData: {
            broker_id: '',
            commission_entry_id: '',
            company_bank_account_id: '',
            amount: 0,
            payment_mode: 'Bank Transfer (NEFT / RTGS / IMPS)',
            reference_no: '',
            date: new Date().toISOString().split('T')[0],
            remarks: ''
        },

        getDefaultPaymentMode() {
            const list = this.paymentModes || [];
            const found = list.find(pm => 
                (pm.code && (pm.code.toUpperCase() === 'BANK_TRANSFER' || pm.code.toUpperCase() === 'BANK')) ||
                (pm.name && pm.name.toLowerCase().includes('bank transfer'))
            );
            return found ? found.name : (list.length > 0 ? list[0].name : 'Bank Transfer (NEFT / RTGS / IMPS)');
        },

        init() {
            if (this.companyBankAccounts.length > 0) {
                this.modalData.company_bank_account_id = String(this.companyBankAccounts[0].id);
            }
            if (this.projects.length > 0) {
                this.filters.project_id = String(this.projects[0].id);
            }
            this.modalData.payment_mode = this.getDefaultPaymentMode();
        },

        formatBrokeragesList(brokerages, broker) {
            if (!brokerages || !Array.isArray(brokerages)) return [];
            return brokerages.map(entry => {
                const commAmt = Number(entry.commission_amount || 0);
                const paidAmt = Number(entry.paid_amount || 0);
                const remaining = Math.max(0, commAmt - paidAmt);
                const saleNo = entry.sale?.sale_number || ('Deal #' + entry.id);
                const unitDoor = entry.sale?.unit?.door_no ? `Unit ${entry.sale.unit.door_no}` : '';
                const customerName = entry.sale?.customer?.name || '';
                const projectName = entry.sale?.project?.name || '';
                
                let details = [];
                if (broker && broker.name) details.push(`Broker: ${broker.name}`);
                if (projectName) details.push(projectName);
                if (unitDoor) details.push(unitDoor);
                if (customerName) details.push(customerName);
                const subDetails = details.join(' • ');
                
                let label = `Sale #${saleNo}`;
                if (details.length > 0) {
                    label += ` (${details.join(' - ')})`;
                }
                label += ` — Unpaid: ${this.formatCurrency(remaining)}`;

                return {
                    id: String(entry.id),
                    broker_id: broker ? String(broker.id) : '',
                    broker_name: broker ? broker.name : 'Broker',
                    sale_id: entry.sale_id,
                    sale_number: saleNo,
                    subDetails: subDetails,
                    label: label,
                    commission_amount: commAmt,
                    paid_amount: paidAmt,
                    remaining: remaining,
                    status: entry.status || 'pending'
                };
            });
        },

        get modalBrokerSales() {
            if (this.modalData.broker_id) {
                const b = this.brokers.find(m => String(m.id) === String(this.modalData.broker_id));
                if (!b || !b.brokerages) return [];
                return this.formatBrokeragesList(b.brokerages, b);
            }
            let all = [];
            (this.brokers || []).forEach(b => {
                if (b && b.brokerages && b.brokerages.length > 0) {
                    all = all.concat(this.formatBrokeragesList(b.brokerages, b));
                }
            });
            return all;
        },

        get modalSelectedSale() {
            if (!this.modalData.commission_entry_id) return null;
            return this.modalBrokerSales.find(s => String(s.id) === String(this.modalData.commission_entry_id)) || null;
        },

        get modalSelectedBroker() {
            if (!this.modalData.broker_id) return null;
            return this.brokers.find(m => String(m.id) === String(this.modalData.broker_id)) || null;
        },

        get filteredModalBrokers() {
            const list = this.brokers || [];
            if (!this.modalBrokerSearch || !this.modalBrokerSearch.trim()) return list;
            const q = this.modalBrokerSearch.toLowerCase().trim();
            return list.filter(b => b.name && b.name.toLowerCase().includes(q));
        },

        get filteredModalSales() {
            const list = this.modalBrokerSales || [];
            if (!this.modalSaleSearch || !this.modalSaleSearch.trim()) return list;
            const q = this.modalSaleSearch.toLowerCase().trim();
            return list.filter(s => 
                (s.sale_number && s.sale_number.toLowerCase().includes(q)) ||
                (s.broker_name && s.broker_name.toLowerCase().includes(q)) ||
                (s.label && s.label.toLowerCase().includes(q)) ||
                (s.subDetails && s.subDetails.toLowerCase().includes(q))
            );
        },

        get modalMaxPayable() {
            if (this.modalSelectedSale) {
                return this.modalSelectedSale.remaining;
            }
            return this.modalSelectedBrokerBalance;
        },

        openPayoutModal(brokerId = null, commissionEntryId = null) {
            this.modalErrors = {};
            this.modalBankOpen = false;
            this.modalBankSearch = '';
            this.modalBrokerOpen = false;
            this.modalBrokerSearch = '';
            this.modalSaleOpen = false;
            this.modalSaleSearch = '';
            const firstBankId = (this.companyBankAccounts.length > 0) ? String(this.companyBankAccounts[0].id) : '';
            
            let selectedBroker = null;
            if (brokerId) {
                selectedBroker = this.brokers.find(b => String(b.id) === String(brokerId)) || null;
            }
            if (!selectedBroker && commissionEntryId) {
                for (const b of this.brokers) {
                    if (b.brokerages && b.brokerages.some(entry => String(entry.id) === String(commissionEntryId))) {
                        selectedBroker = b;
                        break;
                    }
                }
            }

            const defaultPayMode = this.getDefaultPaymentMode();

            this.modalData = {
                broker_id: selectedBroker ? String(selectedBroker.id) : '',
                commission_entry_id: commissionEntryId ? String(commissionEntryId) : '',
                company_bank_account_id: firstBankId,
                amount: 0,
                payment_mode: defaultPayMode,
                reference_no: '',
                date: new Date().toISOString().split('T')[0],
                remarks: ''
            };

            this.modalData.amount = selectedBroker ? this.modalMaxPayable : 0;
            this.payoutModalOpen = true;
        },

        selectModalBroker(brokerId) {
            this.modalData.broker_id = String(brokerId);
            this.modalBrokerOpen = false;
            this.modalBrokerSearch = '';
            this.onModalBrokerChange();
        },

        selectModalSale(saleId) {
            if (!saleId) {
                this.modalData.commission_entry_id = '';
                this.modalSaleOpen = false;
                this.modalSaleSearch = '';
                this.onModalSaleChange();
                return;
            }

            const sale = this.modalBrokerSales.find(s => String(s.id) === String(saleId));
            if (sale) {
                if (sale.broker_id) {
                    this.modalData.broker_id = String(sale.broker_id);
                    delete this.modalErrors.broker_id;
                }
                this.modalData.commission_entry_id = String(sale.id);
                delete this.modalErrors.commission_entry_id;
                this.modalSaleOpen = false;
                this.modalSaleSearch = '';
                this.onModalSaleChange();
            }
        },

        onModalBrokerChange() {
            delete this.modalErrors.broker_id;
            delete this.modalErrors.commission_entry_id;
            delete this.modalErrors.amount;
            this.modalData.commission_entry_id = '';
            this.modalData.amount = this.modalMaxPayable;
        },

        onModalSaleChange() {
            delete this.modalErrors.commission_entry_id;
            delete this.modalErrors.amount;
            this.modalData.amount = this.modalMaxPayable;
        },

        validatePayoutForm(event) {
            this.modalErrors = {};
            let hasError = false;
            if (!this.modalData.broker_id) {
                this.modalErrors.broker_id = 'Broker selection is required';
                hasError = true;
            }
            if (!this.modalData.company_bank_account_id) {
                this.modalErrors.company_bank_account_id = 'Company bank account selection is required';
                hasError = true;
            }
            if (this.modalData.amount === '' || this.modalData.amount === null || isNaN(Number(this.modalData.amount)) || Number(this.modalData.amount) <= 0) {
                this.modalErrors.amount = 'Valid payout amount is required';
                hasError = true;
            } else if (this.isBankInsufficient) {
                this.modalErrors.amount = 'Payout amount exceeds available bank balance';
                hasError = true;
            } else if (this.isBrokerInsufficient) {
                const targetLabel = this.modalSelectedSale ? "selected sale's unpaid balance" : "available broker balance";
                this.modalErrors.amount = `Payout amount exceeds ${targetLabel}`;
                hasError = true;
            }
            if (!this.modalData.date) {
                this.modalErrors.date = 'Payment date is required';
                hasError = true;
            }
            if (!this.modalData.reference_no || !this.modalData.reference_no.trim()) {
                this.modalErrors.reference_no = 'Transaction / Cheque / UTR number is required';
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
                return false;
            }
        },

        get selectedFilterBrokerName() {
            if (!this.filters.broker_id) return 'All Brokers';
            const b = (this.brokers || []).find(m => String(m.id) === String(this.filters.broker_id));
            return b ? b.name : 'All Brokers';
        },

        get selectedFilterProjectName() {
            if (!this.filters.project_id) return 'All Projects';
            const p = (this.projects || []).find(proj => String(proj.id) === String(this.filters.project_id));
            return p ? p.name : 'All Projects';
        },

        get selectedFilterStatusName() {
            if (!this.filters.status) return 'All Statuses (Pending, Partial, Fully Paid)';
            if (this.filters.status === 'pending') return 'Pending (Unpaid / Payable Share)';
            if (this.filters.status === 'partial') return 'Partially Paid';
            if (this.filters.status === 'paid') return 'Fully Paid / Disbursed';
            return 'All Statuses (Pending, Partial, Fully Paid)';
        },

        get filteredSearchableBrokers() {
            const list = this.brokers || [];
            if (!this.brokerFilterSearch || !this.brokerFilterSearch.trim()) return list;
            const q = this.brokerFilterSearch.toLowerCase().trim();
            return list.filter(b => b.name && b.name.toLowerCase().includes(q));
        },

        get selectedBrokerName() {
            if (!this.modalData.broker_id) return 'Not Selected';
            const b = this.brokers.find(m => String(m.id) === String(this.modalData.broker_id));
            return b ? b.name : 'Broker';
        },

        get modalSelectedBrokerBalance() {
            if (!this.modalData.broker_id) return 0;
            const b = this.brokers.find(m => String(m.id) === String(this.modalData.broker_id));
            return b ? Number(b.payable_commission ?? b.available_balance ?? 0) : 0;
        },

        get modalSelectedBankAccount() {
            if (!this.modalData.company_bank_account_id) return null;
            return this.companyBankAccounts.find(b => String(b.id) === String(this.modalData.company_bank_account_id)) || null;
        },

        get modalSelectedBankBalance() {
            const b = this.modalSelectedBankAccount;
            if (!b) return 0;
            return Number(b.current_balance !== null && b.current_balance !== undefined ? b.current_balance : (b.opening_balance || 0));
        },

        get modalSelectedBankBalanceInWords() {
            if (!this.modalSelectedBankAccount) return '';
            return numberToWords(this.modalSelectedBankBalance);
        },

        get modalPayoutAmountInWords() {
            return numberToWords(this.modalData.amount);
        },

        get modalBankBalanceAfterPayout() {
            return this.modalSelectedBankBalance - (Number(this.modalData.amount) || 0);
        },

        get modalPayoutAmount() {
            return Number(this.modalData.amount || 0);
        },

        get modalBalanceAfterPayout() {
            return this.modalMaxPayable - this.modalPayoutAmount;
        },

        get isBankInsufficient() {
            if (!this.modalSelectedBankAccount) return false;
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > this.modalSelectedBankBalance;
        },

        get isBrokerInsufficient() {
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > (this.modalMaxPayable + 0.01);
        },

        get modalErrorMessage() {
            if (this.isBankInsufficient) {
                return `Insufficient Bank Funds! Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds available balance in ${this.modalSelectedBankAccount?.bank_name || 'selected bank'} (${this.formatCurrency(this.modalSelectedBankBalance)}).`;
            }
            if (this.isBrokerInsufficient) {
                const targetLabel = this.modalSelectedSale ? `selected sale's unpaid balance` : `available broker balance`;
                return `Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds ${targetLabel} (${this.formatCurrency(this.modalMaxPayable)}).`;
            }
            return '';
        },

        get filteredModalBankAccounts() {
            const accounts = this.companyBankAccounts || [];
            if (!this.modalBankSearch || !this.modalBankSearch.trim()) return accounts;
            const q = this.modalBankSearch.toLowerCase().trim();
            return accounts.filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q))
            );
        },

        get filteredLedger() {
            let list = [...this.rawLedger];

            if (this.filters.broker_id) {
                list = list.filter(r => String(r.broker_id) === String(this.filters.broker_id));
            }

            if (this.filters.project_id) {
                list = list.filter(r => r.project_id && String(r.project_id) === String(this.filters.project_id));
            }

            if (this.filters.status) {
                const st = this.filters.status;
                if (st === 'pending') {
                    list = list.filter(r => (r.status === 'pending' || r.status === 'payable') && Number(r.credit || 0) > 0);
                } else if (st === 'partial') {
                    list = list.filter(r => r.status === 'partial');
                } else if (st === 'paid') {
                    list = list.filter(r => r.status === 'paid' || Number(r.debit || 0) > 0);
                }
            }

            if (this.filters.from_date) {
                list = list.filter(r => r.date >= this.filters.from_date);
            }

            if (this.filters.to_date) {
                list = list.filter(r => r.date <= this.filters.to_date);
            }

            // Chronological sort: Date Ascending, then Credits before Debits, then ID
            list.sort((a, b) => {
                if (a.date !== b.date) return a.date.localeCompare(b.date);
                if (a.credit > 0 && b.debit > 0) return -1;
                if (a.debit > 0 && b.credit > 0) return 1;
                return String(a.id).localeCompare(String(b.id));
            });

            // Running balance per broker
            const brokerBalances = {};
            return list.map(item => {
                const bId = item.broker_id || 'other';
                if (!brokerBalances[bId]) brokerBalances[bId] = 0;
                brokerBalances[bId] += (Number(item.credit || 0) - Number(item.debit || 0));
                return {
                    ...item,
                    running_balance: brokerBalances[bId]
                };
            });
        },

        get pagedLedger() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredLedger.slice(start, start + this.pageSize);
        },

        get totalPages() {
            return Math.ceil(this.filteredLedger.length / this.pageSize) || 1;
        },

        get totalCredit() {
            return this.filteredLedger.reduce((sum, r) => sum + Number(r.credit || 0), 0);
        },

        get totalDebit() {
            return this.filteredLedger.reduce((sum, r) => sum + Number(r.debit || 0), 0);
        },

        get totalRunningBalance() {
            return this.totalCredit - this.totalDebit;
        },

        handleDateRangeChange() {
            const now = new Date();
            const y = now.getFullYear();
            const m = now.getMonth();

            if (this.filters.date_range === 'this_month') {
                const start = new Date(y, m, 1);
                const end = new Date(y, m + 1, 0);
                this.filters.from_date = start.toISOString().split('T')[0];
                this.filters.to_date = end.toISOString().split('T')[0];
            } else if (this.filters.date_range === 'this_quarter') {
                const q = Math.floor(m / 3);
                const start = new Date(y, q * 3, 1);
                const end = new Date(y, q * 3 + 3, 0);
                this.filters.from_date = start.toISOString().split('T')[0];
                this.filters.to_date = end.toISOString().split('T')[0];
            } else if (this.filters.date_range === 'this_fy') {
                const fyStartYear = m >= 3 ? y : y - 1;
                this.filters.from_date = `${fyStartYear}-04-01`;
                this.filters.to_date = `${fyStartYear + 1}-03-31`;
            } else if (this.filters.date_range === 'inception') {
                this.filters.from_date = '';
                this.filters.to_date = '';
            }
            this.currentPage = 1;
        },

        resetFilters() {
            this.filters.broker_id = '';
            this.filters.project_id = (this.projects.length > 0) ? String(this.projects[0].id) : '';
            this.filters.status = '';
            this.filters.date_range = 'inception';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.brokerFilterOpen = false;
            this.brokerFilterSearch = '';
            this.projectFilterOpen = false;
            this.statusFilterOpen = false;
            this.currentPage = 1;
        },

        formatCurrency(num) {
            const val = Number(num || 0);
            return 'Rs. ' + Math.abs(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        formatDate(d) {
            if (!d) return '-';
            const dt = new Date(d);
            if (isNaN(dt.getTime())) return d;
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const day = String(dt.getDate()).padStart(2, '0');
            const month = months[dt.getMonth()];
            const year = dt.getFullYear();
            return `${day}-${month}-${year}`;
        },

        downloadReceiptPdf(receipt) {
            if (!receipt) return;

            const escapeHtml = (str) => {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const isCredit = Number(receipt.credit || 0) > 0;
            const amountNum = isCredit ? Number(receipt.credit || 0) : Number(receipt.debit || 0);
            const amountFormatted = '₹' + amountNum.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            let amountWords = numberToWords(amountNum);

            const bObj = (this.brokers || []).find(b => String(b.id) === String(receipt.broker_id || ''));
            const brokerName = escapeHtml(receipt.broker_name || (bObj ? bObj.name : 'Broker'));
            
            const bankName = escapeHtml(receipt.company_bank_account_name || 'General Bank Account');
            const refNo = escapeHtml(receipt.ref_no || 'VOUCHER');
            const dateVal = this.formatDate(receipt.date);
            const payMode = escapeHtml(receipt.payment_mode || (isCredit ? 'Commission Allocation' : 'Bank Transfer'));
            const description = escapeHtml(receipt.description || (isCredit ? 'Broker Commission Share Allocation' : 'Broker Payout Release'));

            const printWin = window.open('', '_blank', 'width=940,height=960,top=30,left=100');
            if (!printWin) {
                alert('Please allow popups to preview and download the receipt PDF.');
                return;
            }

            const html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broker Receipt — ${refNo}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 24px 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .receipt-card {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2dcd0;
            overflow: hidden;
            box-shadow: 0 6px 24px -4px rgba(15, 23, 42, 0.08);
        }
        .company-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 22px 28px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #334155;
        }
        .company-name {
            font-size: 15.5px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .receipt-pill-title {
            font-size: 15px;
            font-weight: 900;
            color: #e2b855;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .receipt-body { padding: 22px 24px; }
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background: #fcfbf8;
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 20px;
        }
        .meta-item { display: flex; flex-direction: column; }
        .meta-label { font-size: 10px; font-weight: 700; color: #8a7522; text-transform: uppercase; letter-spacing: 0.5px; }
        .meta-value { font-size: 13px; font-weight: 800; color: #0f172a; margin-top: 2px; }
        .amount-box {
            background: #fdfbf7;
            border: 2px solid #a38c29;
            border-radius: 14px;
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .amount-lbl { font-size: 11px; font-weight: 800; color: #8a7522; text-transform: uppercase; }
        .amount-val { font-size: 24px; font-weight: 900; color: #a38c29; font-family: 'JetBrains Mono', monospace; }
        .words-val { font-size: 12px; font-weight: 700; color: #475569; margin-top: 4px; font-style: italic; }
        .table-section { margin-bottom: 24px; }
        .det-table { width: 100%; border-collapse: collapse; }
        .det-table th { background: #1e293b; color: #ffffff; font-size: 10.5px; font-weight: 800; text-transform: uppercase; padding: 10px 14px; text-align: left; }
        .det-table td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 600; }
        .footer-sig { display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-top: 40px; pt-6; border-top: 1.5px dashed #cbd5e1; }
        .sig-box { text-align: center; }
        .sig-line { border-top: 1.5px solid #94a3b8; margin-top: 40px; margin-bottom: 6px; }
        .sig-title { font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; }
    </style>
</head>
<body>
    <div style="max-width: 860px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 18px; font-weight: 800; color: #0f172a;">Broker Payout Voucher</div>
        <button onclick="window.print()" style="background: #a38c29; color: #fff; font-weight: 800; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer;">PRINT / SAVE PDF</button>
    </div>

    <div class="receipt-card">
        <div class="company-hero">
            <div>
                <div class="company-name">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</div>
                <div style="font-size: 10px; color: #94a3b8; font-weight: 600; margin-top: 2px;">BROKERAGE & COMMISSION MANAGEMENT SYSTEM</div>
            </div>
            <div style="text-align: right;">
                <div class="receipt-pill-title">${isCredit ? 'COMMISSION ALLOCATION RECEIPT' : 'BROKER PAYOUT DISBURSEMENT VOUCHER'}</div>
                <div style="font-size: 12px; color: #ffffff; font-weight: 800; margin-top: 4px;">REF: ${refNo}</div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="meta-strip">
                <div class="meta-item">
                    <span class="meta-label">TRANSACTION DATE</span>
                    <span class="meta-value">${dateVal}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">BROKER NAME</span>
                    <span class="meta-value">${brokerName}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">PAYMENT METHOD / BANK</span>
                    <span class="meta-value">${payMode} (${bankName})</span>
                </div>
            </div>

            <div class="amount-box">
                <div>
                    <div class="amount-lbl">${isCredit ? 'COMMISSION AMOUNT ALLOCATED' : 'NET PAYOUT AMOUNT RELEASED'}</div>
                    <div class="words-val">${amountWords}</div>
                </div>
                <div class="amount-val">${amountFormatted}</div>
            </div>

            <div class="table-section" style="margin-bottom: 0;">
                <table class="det-table">
                    <thead>
                        <tr>
                            <th>Voucher Ref</th>
                            <th>Description / Particulars</th>
                            <th style="text-align: right;">Amount (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-family: monospace; font-weight: 700;">${refNo}</td>
                            <td>${description}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 800; color: ${isCredit ? '#059669' : '#e11d48'};">${amountFormatted}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>`;

            printWin.document.open();
            printWin.document.write(html);
            printWin.document.close();
        }
    };
}
</script>

</x-erp-layout>
