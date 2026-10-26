<x-erp-layout title="Partner Statement Ledger & Capital Outflows" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6 font-sans print:p-0 print:m-0" 
     x-data="partnerOutflowLedgerApp()">

    {{-- ── 1. BREADCRUMBS & TOP BANNER BOX ── --}}
    <div class="space-y-2 print:hidden">
        <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            <a href="/" class="hover:text-slate-600 transition">HOME</a>
            <span>›</span>
            <span>FINANCE & ANALYTICS</span>
            <span>›</span>
            <span class="text-[#a38c29]">PARTNER OUTFLOW LEDGER</span>
        </nav>

        {{-- Banner Card (Matching EMI Collection Trends Box UI with Right-Side Outflow Stat Box) --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                        <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase tracking-wider text-slate-900">PARTNER STATEMENT LEDGER & CAPITAL OUTFLOWS</h3>
                        <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">EXECUTIVE OUTFLOW LEDGER</span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Track capital allocations, profit shares, and mapping of receipt distributions across project partners.</p>
            </div>

            {{-- Right side Action & Stat Box --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('reports.partner_statements', ['open_payout_modal' => 1]) }}"
                   class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Record Partner Payout</span>
                </a>
                <div class="relative z-10 bg-[#faf9f5]/90 border border-[#a38c29]/40 rounded-2xl px-5 py-2.5 text-right shadow-2xs min-w-[190px]">
                    <div class="text-[9.5px] font-black uppercase tracking-wider text-[#a38c29]">TOTAL ALLOCATED OUTFLOW</div>
                    <div class="text-lg sm:text-xl font-black text-slate-900 font-mono mt-0.5">
                        ₹{{ number_format($totalAllocatedOutflow, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. EXECUTIVE KPI METRICS CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- KPI 1: Total Allocated Outflow --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Payouts Released</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:bg-rose-100/60">
                    Disbursed
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                    ₹{{ number_format($totalAllocatedOutflow, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total profit payouts & drawings released</p>
            </div>
        </div>

        {{-- KPI 2: Total Capital Contributed --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Capital Contributed</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                    Capital Inflow
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                    ₹{{ number_format($totalCapitalContributed ?? 0, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total partner capital injected into projects</p>
            </div>
        </div>

        {{-- KPI 3: Active Participating Partners --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Partners</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300">
                    Active Stake
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    {{ $activePartnersCount }} <span class="text-xs font-extrabold text-slate-400 uppercase">Parties</span>
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total Registered: {{ $allPartners->count() }}</p>
            </div>
        </div>

        {{-- KPI 4: Primary Partner Outflow Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-teal-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-teal-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(20,184,166,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100/60 transition-all duration-300 group-hover:bg-teal-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">
                        {{ $topPartner ? strtoupper($topPartner->partner_name) . ' OUTFLOW' : 'PRIMARY PARTNER OUTFLOW' }}
                    </span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-teal-700 font-bold bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-teal-300 group-hover:bg-teal-100/60">
                    {{ $topPartner ? number_format($topPartner->percentage, 1) . '% Share' : '0% Share' }}
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-teal-600 font-mono tracking-tight block group-hover:text-teal-700 transition-colors duration-300">
                    ₹{{ number_format($topPartner?->amount ?? 0, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">
                    {{ $topPartner ? $topPartner->allocations_count . ' disbursements recorded' : 'Primary realized share' }}
                </p>
            </div>
        </div>

    </div>

    {{-- ── 4. VISUAL INTELLIGENCE CHARTS (2 COLUMNS) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Card: Monthly Capital Outflow Trend --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 mb-4 gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] border border-amber-200 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">MONTHLY CAPITAL OUTFLOW TREND</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">Allocations timeline across financial periods</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-wider">
                        Period: {{ $monthlyTrendData->keys()->first() ?? 'Current' }} - {{ $monthlyTrendData->keys()->last() ?? 'Current' }}
                    </span>
                </div>
            </div>

            <div class="w-full h-64 relative flex items-center justify-center">
                <div id="monthlyOutflowChart" class="w-full h-full"></div>
            </div>
        </div>

        {{-- Right Card: Partner Outflow Share --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 mb-4 gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">PARTNER OUTFLOW SHARE</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">Proportional capital allocation distribution</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider">
                    {{ $partnerShareData->count() }} Entities
                </span>
            </div>

            <div class="w-full h-56 relative flex items-center justify-center">
                <div id="partnerShareChart" class="w-full h-full"></div>
            </div>

            {{-- Custom Partner Distribution Progress Bars List --}}
            <div class="pt-3 border-t border-slate-100 mt-2 space-y-2">
                @php
                    $palette = ['#a38c29', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'];
                    $colorIdx = 0;
                @endphp
                @foreach($outflowList as $row)
                    @php 
                        $c = $palette[$colorIdx % count($palette)]; 
                        $colorIdx++; 
                    @endphp
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $c }};"></span>
                            <span class="font-bold text-slate-800 truncate">{{ $row->partner_name }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-slate-900">₹{{ number_format((float)$row->amount, 2) }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-black text-white" style="background-color: {{ $c }};">
                                {{ number_format($row->percentage, 1) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── 2. STANDARDIZED ERP FILTER BAR (CUSTOM POPOVERS & LIVE SEARCH) ── --}}
    <div class="erp-filter-card">
        <div class="erp-filter-container">
            <div class="erp-filter-grid-5">
                
                {{-- 1. Live Instant Search Input --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="filters.search" placeholder="Search partner, memo..." autocomplete="off"
                           class="w-full erp-search-input pl-10 pr-9">
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center" x-show="filters.search && filters.search.length > 0" style="display: none;">
                        <button type="button" @click="filters.search = ''" class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 2. Project Filter (Custom Popover) --}}
                <div class="relative w-full" 
                     x-data="{ 
                        open: false, 
                        search: '',
                        select(id) {
                            filters.project_id = id;
                            this.open = false;
                            this.search = '';
                        },
                        clear() {
                            filters.project_id = defaultProjectId;
                            this.open = false;
                            this.search = '';
                        }
                     }" 
                     @click.outside="open = false">
                    <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => $refs.projSearch?.focus()); }"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="filters.project_id && filters.project_id !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="getProjectName(filters.project_id)"></span>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="filters.project_id && String(filters.project_id) !== String(defaultProjectId)">
                                <span @click.stop="clear()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Reset to default project">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover" 
                         style="display: none;">
                        
                        {{-- Search Input inside Popover --}}
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10" x-show="projectsList && projectsList.length > 5">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="search" x-ref="projSearch" placeholder="Search project..." 
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="open = false">
                            </div>
                        </div>

                        {{-- All Projects Option --}}
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="select('all')" 
                                 class="erp-dropdown-option"
                                 :class="filters.project_id === 'all' || !filters.project_id ? 'selected-all' : ''">
                                <span>— All Projects —</span>
                            </div>
                            <template x-for="proj in (search ? projectsList.filter(p => (p.name || '').toLowerCase().includes(search.toLowerCase())) : projectsList)" :key="proj.id">
                                <div @click="select(proj.id)" 
                                     class="erp-dropdown-option"
                                     :class="String(filters.project_id) === String(proj.id) ? 'selected' : ''">
                                    <span class="truncate" x-text="proj.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 3. Partner Filter (Custom Popover) --}}
                <div class="relative w-full" 
                     x-data="{ 
                        open: false, 
                        search: '',
                        select(id) {
                            filters.partner_id = (id === 'all' ? '' : id);
                            this.open = false;
                            this.search = '';
                        },
                        clear() {
                            filters.partner_id = '';
                            this.open = false;
                            this.search = '';
                        }
                     }" 
                     @click.outside="open = false">
                    <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => $refs.partSearch?.focus()); }"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="filters.partner_id && filters.partner_id !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="getPartnerName(filters.partner_id)"></span>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="filters.partner_id && filters.partner_id !== 'all'">
                                <span @click.stop="clear()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover" 
                         style="display: none;">
                        
                        {{-- Search Input inside Popover --}}
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10" x-show="partnersList && partnersList.length > 5">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="search" x-ref="partSearch" placeholder="Search partner..." 
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="open = false">
                            </div>
                        </div>

                        {{-- All Partners Option --}}
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="select('all')" 
                                 class="erp-dropdown-option"
                                 :class="!filters.partner_id || filters.partner_id === 'all' ? 'selected-all' : ''">
                                <span>— All Partners —</span>
                            </div>
                            <template x-for="p in (search ? partnersList.filter(p => (p.name || '').toLowerCase().includes(search.toLowerCase())) : partnersList)" :key="p.id">
                                <div @click="select(p.id)" 
                                     class="erp-dropdown-option"
                                     :class="String(filters.partner_id) === String(p.id) ? 'selected' : ''">
                                    <span class="truncate" x-text="p.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 4. From Date Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" x-model="filters.from_date" id="filter_from_date"
                           title="From Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 5. To Date Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" x-model="filters.to_date" id="filter_to_date"
                           title="To Date"
                           class="w-full erp-input erp-date-input">
                </div>

            </div>

            {{-- Signature Gold RESET FILTERS Button --}}
            <div class="shrink-0 flex items-center">
                <button type="button" @click="resetFilters()"
                   class="theme-btn h-[38px] px-5 py-2 text-xs font-extrabold flex items-center justify-center gap-2 rounded-xl transition-all shadow-sm shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer text-white no-underline border-0">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── 5. DATA TABLE CARD (MATCHING REQUESTED DESIGN) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left border-collapse table-auto">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider sticky top-0 z-10">
                    <tr class="erp-table-header border-b border-slate-700 text-left">
                        <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 w-52 whitespace-nowrap">PARTNER ENTITY</th>
                        <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 whitespace-nowrap">ASSOCIATED PROJECT</th>
                        <th class="px-5 py-3.5 erp-table-header border-r border-slate-600">DESCRIPTION MEMO</th>
                        <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-left w-44 whitespace-nowrap">ALLOCATED OUTFLOW</th>
                        <th class="px-5 py-3.5 erp-table-header text-center w-56 whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800 font-semibold">
                    @forelse($outflowList as $index => $row)
                        <tr class="hover:bg-slate-50/80 transition-colors"
                            x-show="isRowVisible('{{ $row->partner_id }}', '{{ addslashes($row->partner_name) }}', '{{ addslashes($row->project_name) }}', '{{ addslashes($row->description) }}')">
                            <td class="px-5 py-4 font-extrabold text-slate-900 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                    <span>{{ $row->partner_name }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $row->project_name }}
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-600">
                                {{ $row->description }}
                            </td>
                            <td class="px-5 py-4 text-left font-mono font-black text-rose-600 text-sm whitespace-nowrap">
                                ₹{{ number_format((float)$row->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    @if(!empty($row->partner_id))
                                        <a href="{{ url('/reports/partner-statements?partner_id=' . $row->partner_id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#8a7522] hover:text-white rounded-xl font-extrabold text-[11px] transition-all duration-150 shadow-2xs group border border-[#a38c29]/30"
                                           title="View Partner Statement">
                                            <svg class="w-3.5 h-3.5 text-[#a38c29] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Partner Statement</span>
                                        </a>
                                    @endif

                                    <!-- View Partner Capital Outflow Modal Button -->
                                    <button type="button" 
                                            @click.stop="openViewModalByIndex({{ $index }})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 inline-flex items-center justify-center bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#8a7522] hover:text-white rounded-xl border border-[#a38c29]/30 transition-all duration-150 shadow-2xs group cursor-pointer active:scale-95"
                                            title="View Partner Outflow Details">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#a38c29] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-medium italic">
                                No capital outflow allocations found for the selected project.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── 6. HIDDEN EXCEL EXPORT TEMPLATE TABLE ── --}}
    <div class="hidden" style="display: none;">
        <table id="partnerOutflowExcelTable" border="1" style="border-collapse: collapse; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 10pt;">
            <thead>
                <tr height="40" style="height: 30pt;">
                    <th colspan="7" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle;">
                        TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - PARTNER STATEMENT LEDGER & CAPITAL OUTFLOWS
                    </th>
                </tr>
                <tr height="25" style="height: 20pt;">
                    <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #f8fafc; font-size: 9pt; text-align: left; padding: 6px;">
                        Report Scope: {{ $selectedProject ? $selectedProject->name : 'Consolidated (All Projects)' }} | Export Date: {{ date('d-m-Y H:i') }} | Total Outflow: INR {{ number_format($totalAllocatedOutflow, 2) }}
                    </th>
                </tr>
                <tr height="30" bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left;">
                    <th style="padding: 8px;">Partner Entity</th>
                    <th style="padding: 8px;">Associated Project</th>
                    <th style="padding: 8px;">Description Memo</th>
                    <th style="padding: 8px; text-align: center;">Allocations Count</th>
                    <th style="padding: 8px; text-align: center;">Last Allocation Date</th>
                    <th style="padding: 8px; text-align: right;">Allocated Outflow (₹)</th>
                    <th style="padding: 8px; text-align: center;">Share (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outflowList as $row)
                    <tr height="25">
                        <td style="padding: 6px; font-weight: bold;">{{ $row->partner_name }}</td>
                        <td style="padding: 6px;">{{ $row->project_name }}</td>
                        <td style="padding: 6px;">{{ $row->description }}</td>
                        <td style="padding: 6px; text-align: center;">{{ $row->allocations_count }}</td>
                        <td style="padding: 6px; text-align: center;">{{ $row->last_date }}</td>
                        <td style="padding: 6px; text-align: right; font-weight: bold;">{{ number_format((float)$row->amount, 2, '.', '') }}</td>
                        <td style="padding: 6px; text-align: center;">{{ number_format($row->percentage, 1) }}%</td>
                    </tr>
                @endforeach
                <tr height="30" bgcolor="#f1f5f9" style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="5" style="padding: 8px; text-align: right;">TOTAL CONSOLIDATED OUTFLOW:</td>
                    <td style="padding: 8px; text-align: right; font-weight: bold; color: #b91c1c;">{{ number_format($totalAllocatedOutflow, 2, '.', '') }}</td>
                    <td style="padding: 8px; text-align: center;">100.0%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- VIEW PARTNER CAPITAL OUTFLOW DETAIL MODAL -->
    <!-- ========================================== -->
    <div x-show="showViewModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs" 
         style="display: none;"
         @click.self="closeViewModal()"
         @keydown.escape.window="closeViewModal()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-3xl flex flex-col my-auto max-h-[92vh]"
             @click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Executive Dark Gradient Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-4 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-44 h-44 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[#a38c29] text-[10px] font-black uppercase tracking-widest bg-[#a38c29]/15 px-2 py-0.5 rounded border border-[#a38c29]/30">
                                PARTNER LEDGER
                            </span>
                            <span class="text-slate-400 text-[10px] font-mono" x-show="viewPartner?.partner_id" x-text="'ID #' + viewPartner?.partner_id"></span>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-white uppercase tracking-wider truncate" x-text="viewPartner?.partner_name || 'Partner Details'"></h2>
                        <p class="text-slate-300 text-xs mt-0.5 truncate flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-text="viewPartner?.project_name || 'Consolidated Projects Portfolio'"></span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <template x-if="viewPartner?.percentage !== undefined">
                            <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-mono font-bold bg-[#a38c29]/20 text-amber-300 border border-[#a38c29]/40 tracking-wider"
                                  x-text="(viewPartner?.percentage || 0) + '% Portfolio Share'">
                            </span>
                        </template>
                        <button type="button" @click="closeViewModal()" 
                                class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer"
                                title="Close">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Body (Light theme with gold/amber accents) --}}
            <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto bg-slate-50/60 text-xs flex-1 custom-scrollbar">

                {{-- 1. Top 3 KPI Summary Cards (Unified Partner Financial Logic) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    {{-- KPI 1: Total Payouts Released --}}
                    <div class="p-3.5 rounded-xl bg-gradient-to-br from-white via-white to-rose-50/40 border border-rose-200/80 shadow-2xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[9.5px] font-black uppercase tracking-wider text-slate-600">Total Payouts Released</span>
                            <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
                                Disbursed
                            </span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-rose-600 font-mono tracking-tight">
                            ₹<span x-text="formatCurrency(viewPartner?.amount)"></span>
                        </div>
                        <p class="text-[9px] text-slate-400 font-medium">Drawings &amp; profit payouts disbursed</p>
                    </div>

                    {{-- KPI 2: Total Capital Contributed --}}
                    <div class="p-3.5 rounded-xl bg-gradient-to-br from-white via-white to-emerald-50/40 border border-emerald-200/80 shadow-2xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[9.5px] font-black uppercase tracking-wider text-slate-600">Capital Contributed</span>
                            <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Inflow
                            </span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-emerald-600 font-mono tracking-tight">
                            ₹<span x-text="formatCurrency(viewPartner?.capital_contributed || 0)"></span>
                        </div>
                        <p class="text-[9px] text-slate-400 font-medium">Capital invested into projects</p>
                    </div>

                    {{-- KPI 3: Agreed Equity Share --}}
                    <div class="p-3.5 rounded-xl bg-gradient-to-br from-white via-white to-amber-50/40 border border-[#a38c29]/30 shadow-2xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[9.5px] font-black uppercase tracking-wider text-slate-600">Agreed Equity Share</span>
                            <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-amber-50 text-[#7c691c] border border-[#a38c29]/30">
                                Equity Stake
                            </span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-slate-900 font-mono tracking-tight">
                            <span x-text="viewPartner?.share_pct || 0"></span>%
                        </div>
                        <p class="text-[9px] text-slate-400 font-medium truncate" x-text="(viewPartner?.allocations_count || 0) + ' records (' + (viewPartner?.percentage || 0) + '% of outflows)'"></p>
                    </div>

                </div>

                {{-- 2. Partner Entity & Profile Information Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    
                    {{-- Partner Entity & Identifiers --}}
                    <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-2xs space-y-2.5">
                        <p class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Partner Entity Information</span>
                        </p>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[10px] uppercase font-bold">Partner Name:</span>
                                <span class="font-extrabold text-slate-900" x-text="viewPartner?.partner_name || '—'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[10px] uppercase font-bold">PAN Identifier:</span>
                                <template x-if="viewPartner?.partner_pan">
                                    <span class="font-mono font-bold text-slate-800 bg-amber-50/80 px-2 py-0.5 rounded border border-[#a38c29]/30" x-text="viewPartner?.partner_pan"></span>
                                </template>
                                <template x-if="!viewPartner?.partner_pan">
                                    <span class="text-slate-400 italic">Not Specified</span>
                                </template>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[10px] uppercase font-bold">Phone Number:</span>
                                <span class="font-bold text-slate-800 font-mono" x-text="viewPartner?.partner_phone || '—'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[10px] uppercase font-bold">Entity Type:</span>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-extrabold text-[9.5px] rounded border border-slate-200 uppercase">Capital Partner</span>
                            </div>
                        </div>
                    </div>

                    {{-- Allocation Context & Project --}}
                    <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-2xs space-y-2.5">
                        <p class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Allocation Scope & Details</span>
                        </p>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block mb-0.5">Associated Project:</span>
                                <span class="font-bold text-slate-900 block" x-text="viewPartner?.project_name || '—'"></span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block mb-0.5">Description Memo:</span>
                                <p class="text-slate-700 font-medium leading-relaxed bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[11px]" x-text="viewPartner?.description || 'No description memo specified for this partner.'"></p>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-slate-400 text-[10px] uppercase font-bold">Last Recorded Date:</span>
                                <span class="font-bold text-slate-800 font-mono" x-text="viewPartner?.last_date || '—'"></span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- 3. Allocations Breakdown Sub-Table (if items exist) --}}
                <template x-if="viewPartner?.items && viewPartner.items.length > 0">
                    <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="px-4 py-2.5 bg-slate-100/80 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                <span>Capital Outflow Allocation Records (<span x-text="viewPartner.items.length"></span>)</span>
                            </span>
                            <span class="text-[9.5px] font-extrabold text-[#8a7522] uppercase tracking-wider font-mono">Disbursed Records</span>
                        </div>
                        <div class="overflow-x-auto max-h-48 custom-scrollbar">
                            <table class="w-full text-left border-collapse text-[11px]">
                                <thead class="erp-table-header text-white text-[9px] font-black uppercase tracking-wider sticky top-0 z-10">
                                    <tr class="erp-table-header text-left">
                                        <th class="px-3 py-2 border-r border-slate-600">DATE</th>
                                        <th class="px-3 py-2 border-r border-slate-600">VOUCHER #</th>
                                        <th class="px-3 py-2 border-r border-slate-600">PAYMENT MODE & BANK</th>
                                        <th class="px-3 py-2 border-r border-slate-600">REMARKS / MEMO</th>
                                        <th class="px-3 py-2 text-left">AMOUNT (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    <template x-for="(item, idx) in viewPartner.items" :key="idx">
                                        <tr class="hover:bg-amber-50/30 transition-colors">
                                            <td class="px-3 py-2 font-mono font-bold text-slate-800 whitespace-nowrap" x-text="item.date"></td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono font-bold text-[9.5px] text-slate-700" x-text="item.voucher_no || '—'"></span>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <div class="font-bold text-slate-800" x-text="item.bank || 'Corporate Bank'"></div>
                                                <div class="text-[9px] text-slate-500 font-mono" x-text="item.payment_mode"></div>
                                            </td>
                                            <td class="px-3 py-2 text-slate-600 max-w-[220px] truncate" x-text="item.remarks || '—'"></td>
                                            <td class="px-3 py-2 text-left font-mono font-black text-rose-600 whitespace-nowrap">
                                                ₹<span x-text="formatCurrency(item.amount)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>

                <template x-if="!viewPartner?.items || viewPartner.items.length === 0">
                    <div class="p-6 text-center bg-white rounded-xl border border-slate-200/90 text-slate-400 text-xs font-medium italic">
                        No capital outflow records found for this partner under current filter selection.
                    </div>
                </template>

            </div>

            {{-- Modal Fixed Footer --}}
            <div class="px-6 py-3.5 border-t border-slate-200 flex items-center justify-between bg-slate-100 flex-shrink-0">
                <div>
                    <template x-if="viewPartner?.partner_id">
                        <a :href="'{{ url('/reports/partner-statements?partner_id=') }}' + viewPartner.partner_id"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#a38c29]/15 hover:bg-[#a38c29] text-[#8a7522] hover:text-white rounded-xl font-extrabold text-xs transition-all duration-150 shadow-2xs group border border-[#a38c29]/40 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Open Partner Statement</span>
                        </a>
                    </template>
                </div>
                
                <div class="flex items-center gap-2">
                    <button type="button" @click="closeViewModal()" 
                            class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer active:scale-95">
                        CLOSE
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ── 7. APEXCHARTS INTEGRATION SCRIPT ── --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Monthly Capital Outflow Trend Bar Chart
        const trendCategories = @json(array_keys($monthlyTrendData->toArray()));
        const trendValues = @json(array_values($monthlyTrendData->toArray()));

        const monthlyOptions = {
            noData: {
                text: 'No Outflow Transactions Recorded',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#94a3b8', fontSize: '13px', fontWeight: 600 }
            },
            series: [{
                name: 'Capital Outflow',
                data: trendValues
            }],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '38%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    dataLabels: { position: 'top' }
                }
            },
            colors: ['#a38c29'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    if (val >= 10000000) return '₹' + (val / 10000000).toFixed(2) + 'Cr';
                    if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                    if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                    return '₹' + val;
                },
                offsetY: -20,
                style: { 
                    fontSize: '11px', 
                    colors: ['#8a7522'], 
                    fontWeight: 800,
                    fontFamily: 'inherit'
                }
            },
            xaxis: {
                categories: trendCategories.length > 0 ? trendCategories : ['No Data'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: '#64748b', 
                        fontSize: '11px', 
                        fontWeight: 700 
                    } 
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        if (val >= 10000000) return '₹' + (val / 10000000).toFixed(1) + 'Cr';
                        if (val >= 100000) return '₹' + (val / 100000).toFixed(0) + 'L';
                        if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                        return '₹' + val;
                    },
                    style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };

        const monthlyChart = new ApexCharts(document.querySelector("#monthlyOutflowChart"), monthlyOptions);
        monthlyChart.render();

        // 2. Partner Outflow Share Donut Chart
        const shareLabels = @json(array_keys($partnerShareData->toArray()));
        const shareValues = @json(array_values($partnerShareData->toArray()));
        const totalOutflow = {{ (float)$totalAllocatedOutflow }};
        
        const totalFormatted = (totalOutflow >= 10000000) 
            ? '₹' + (totalOutflow / 10000000).toFixed(2) + 'Cr'
            : ((totalOutflow >= 100000) 
                ? '₹' + (totalOutflow / 100000).toFixed(1) + 'L' 
                : '₹' + totalOutflow.toLocaleString('en-IN'));

        const shareOptions = {
            noData: {
                text: 'No Outflow Data Recorded',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#94a3b8', fontSize: '13px', fontWeight: 600 }
            },
            series: shareValues,
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            labels: shareLabels,
            colors: ['#a38c29', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'],
            legend: { show: false },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + '%';
                },
                dropShadow: { enabled: false },
                style: {
                    fontSize: '11px',
                    fontWeight: '800'
                }
            },
            stroke: {
                width: 2,
                colors: ['#ffffff']
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            name: { 
                                show: true, 
                                fontSize: '11px', 
                                color: '#64748b', 
                                fontWeight: 700 
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 900,
                                color: '#0f172a',
                                formatter: function () {
                                    return totalFormatted;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Outflow',
                                fontSize: '10.5px',
                                color: '#64748b',
                                fontWeight: 700,
                                formatter: function () {
                                    return totalFormatted;
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };

        const shareChart = new ApexCharts(document.querySelector("#partnerShareChart"), shareOptions);
        shareChart.render();
    });

    function partnerOutflowLedgerApp() {
        const projectsList = @json($projects);
        const partnersList = @json($allPartners);
        const outflowList = @json($outflowList);
        const firstProjId = projectsList.length > 0 ? String(projectsList[0].id) : '';

        return {
            activeTab: 'summary',
            projectsList: projectsList,
            partnersList: partnersList,
            outflowList: outflowList,
            defaultProjectId: firstProjId,
            showViewModal: false,
            viewPartner: null,
            filters: {
                search: '',
                project_id: firstProjId,
                partner_id: '',
                from_date: '{{ $fromDate ?? '' }}',
                to_date: '{{ $toDate ?? '' }}'
            },

            openViewModalByIndex(index) {
                if (this.outflowList && this.outflowList[index]) {
                    this.viewPartner = this.outflowList[index];
                } else {
                    this.viewPartner = null;
                }
                this.showViewModal = true;
            },

            openViewModal(row) {
                this.viewPartner = row;
                this.showViewModal = true;
            },

            closeViewModal() {
                this.showViewModal = false;
                this.viewPartner = null;
            },

            formatCurrency(val) {
                if (val === null || val === undefined || isNaN(val)) return '0.00';
                return Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            getProjectName(id) {
                if (!id || id === 'all') return '— All Projects —';
                const p = this.projectsList.find(x => String(x.id) === String(id));
                return p ? p.name : '— All Projects —';
            },

            getPartnerName(id) {
                if (!id || id === 'all') return '— All Partners —';
                const p = this.partnersList.find(x => String(x.id) === String(id));
                return p ? p.name : '— All Partners —';
            },

            isRowVisible(partnerId, partnerName, projectName, description) {
                if (this.filters.partner_id && this.filters.partner_id !== 'all' && String(partnerId) !== String(this.filters.partner_id)) {
                    return false;
                }
                if (this.filters.search && this.filters.search.trim()) {
                    const q = this.filters.search.toLowerCase().trim();
                    const str = `${partnerName || ''} ${projectName || ''} ${description || ''}`.toLowerCase();
                    if (!str.includes(q)) return false;
                }
                return true;
            },

            resetFilters() {
                this.filters.search = '';
                this.filters.project_id = this.defaultProjectId;
                this.filters.partner_id = '';
                this.filters.from_date = '';
                this.filters.to_date = '';
            },

            setDatePreset(preset) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                const todayStr = `${yyyy}-${mm}-${dd}`;

                if (preset === 'all') {
                    this.filters.from_date = '';
                    this.filters.to_date = '';
                } else if (preset === 'this_month') {
                    this.filters.from_date = `${yyyy}-${mm}-01`;
                    this.filters.to_date = todayStr;
                } else if (preset === 'this_fy') {
                    const fyStartYear = today.getMonth() >= 3 ? yyyy : yyyy - 1;
                    this.filters.from_date = `${fyStartYear}-04-01`;
                    this.filters.to_date = todayStr;
                }
            },

            exportExcel() {
                const table = document.getElementById('partnerOutflowExcelTable');
                if (!table) return;
                const html = table.outerHTML;
                const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Partner_Capital_Outflow_Ledger_${new Date().toISOString().slice(0,10)}.xls`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            },

            exportCSV() {
                let csv = [];
                const rows = document.querySelectorAll('#summaryTable tbody tr:not(.empty-row)');
                csv.push(['Partner Entity', 'Associated Project', 'Description Memo', 'Transaction Count', 'Last Date', 'Allocated Outflow (INR)', 'Share %'].join(','));
                
                rows.forEach(row => {
                    const partner = row.querySelector('.partner-name')?.innerText.trim() || '';
                    const project = row.querySelector('.project-name')?.innerText.trim() || '';
                    const memo = (row.querySelector('.description-memo')?.innerText.trim() || '').replace(/,/g, ' ');
                    const count = row.querySelector('.trans-count')?.innerText.trim() || '0';
                    const lastDate = row.querySelector('.last-date')?.innerText.trim() || '—';
                    const amount = (row.querySelector('.allocated-amount')?.innerText.trim() || '').replace(/[₹,]/g, '');
                    const pct = (row.querySelector('.share-pct')?.innerText.trim() || '').replace(/%/g, '');
                    csv.push([`\"${partner}\"`, `\"${project}\"`, `\"${memo}\"`, count, `\"${lastDate}\"`, amount, pct].join(','));
                });

                const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Partner_Outflow_Summary_${new Date().toISOString().slice(0,10)}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        };
    }
</script>

</x-erp-layout>
