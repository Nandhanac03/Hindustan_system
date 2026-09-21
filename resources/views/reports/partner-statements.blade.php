<x-erp-layout title="Partner Statement & Equity Ledger" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="partnerStatementApp()" x-init="init()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Header & Reports Export Bar --}}
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Partner Statement & Equity Ledger</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Track partner profit share, payouts and current balance owed.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                
                {{-- 1. Partner Statement Export --}}
                <button @click="exportExcel('partner_statement')" 
                        class="group px-3.5 py-2.5 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-250 hover:border-emerald-600 text-xs font-bold rounded-xl transition-all duration-200 shadow-2xs hover:shadow-xs flex items-center gap-2 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Partner Statement</span>
                </button>

                {{-- 2. Profit Sharing Summary Export --}}
                <button @click="exportExcel('profit_sharing_summary')" 
                        class="group px-3.5 py-2.5 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-250 hover:border-emerald-600 text-xs font-bold rounded-xl transition-all duration-200 shadow-2xs hover:shadow-xs flex items-center gap-2 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Profit Sharing Summary</span>
                </button>

                {{-- 3. Distribution History Log Export --}}
                <button @click="exportExcel('distribution_history_log')" 
                        class="group px-3.5 py-2.5 bg-white hover:bg-emerald-600 text-slate-700 hover:text-white border border-slate-250 hover:border-emerald-600 text-xs font-bold rounded-xl transition-all duration-200 shadow-2xs hover:shadow-xs flex items-center gap-2 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Distribution History Log</span>
                </button>

                {{-- 4. Record Partner Payout Button --}}
                <button @click="openPayoutModal()" 
                        class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Record Partner Payout</span>
                </button>

            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-[#a38c29]/10 border border-[#a38c29]/30 rounded-2xl flex items-center justify-between text-[#7c691c] text-xs font-bold shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- 4 Metric KPI Cards Grid (Placed Above Filter) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Agreed Profit Share --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Agreed Profit Share</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300" x-text="agreedProfitShareFormatted">
                        {{ number_format($agreedProfitShare, 1) }}%
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">As per Partnership Agreement</p>
                </div>
            </div>

            {{-- Card 2: Earned Profit Share --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Earned Profit Share</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300" x-text="formatCurrency(totalCredit)">
                        Rs. {{ number_format($earnedProfitShare, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Partner's share of current project net profit</p>
                </div>
            </div>

            {{-- Card 3: Total Payouts Released --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Payouts Released</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300" x-text="formatCurrency(totalDebit)">
                        Rs. {{ number_format($totalPayoutsReleased, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total profit payouts / drawings released to date</p>
                </div>
            </div>

            {{-- Card 4: Current Net Equity Balance --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Current Net Equity Balance</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-[#a38c29] font-mono tracking-tight block group-hover:text-[#8e7a23] transition-colors duration-300" x-text="formatCurrency(totalRunningBalance)">
                        Rs. {{ number_format($currentNetEquityBalance, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Earned Profit Share - Payouts Released</p>
                </div>
            </div>

        </div>

        {{-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (NO PAGE REFRESH NEEDED) ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 flex-1">
                    
                    {{-- 1. Partner Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select x-model="filters.partner_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Partners</option>
                            @foreach($partners as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 2. Project Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <select x-model="filters.project_id" @change="currentPage = 1"
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

                    {{-- 3. Date Range Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <select x-model="filters.date_range" @change="handleDateRangeChange()"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="inception">Project Inception to Date</option>
                            <option value="this_month">This Month</option>
                            <option value="this_quarter">This Quarter</option>
                            <option value="this_fy">This Financial Year</option>
                            <option value="custom">Custom Date Range</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 4. From Date --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.from_date" @change="filters.date_range = 'custom'; currentPage = 1"
                               title="From Date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer">
                    </div>

                    {{-- 5. To Date --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.to_date" @change="filters.date_range = 'custom'; currentPage = 1"
                               title="To Date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer">
                    </div>

                </div>

                {{-- Single Reset Filters Button (No Refresh, No Apply Button) --}}
                <div class="shrink-0 flex items-center">
                    <button type="button" @click="resetFilters()"
                            class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer whitespace-nowrap group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Section A: Individual Partner Statement of Account (Running Ledger) --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">A. Individual Partner Statement of Account (Running Ledger)</h2>
                </div>
                <span class="text-xs font-bold text-slate-400" x-text="filteredLedger.length + ' records found'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Transaction Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Partner Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Reference / Voucher No.</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Description / Transaction Type</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Profit Share Allocated<br><span class="text-[9px] font-normal text-white/80">(Credit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Payout Released<br><span class="text-[9px] font-normal text-white/80">(Debit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Running Payable Balance<br><span class="text-[9px] font-normal text-white/80">(Rs.)</span></th>
                            <th class="px-4 py-3.5 text-center text-white font-extrabold w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(entry, index) in pagedLedger" :key="index">
                            <tr class="hover:bg-slate-50 transition-colors font-medium">
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100" x-text="formatDate(entry.date)"></td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100 whitespace-nowrap" x-text="entry.partner_name"></td>
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
                                        {{-- PDF / Print Receipt Icon Button (Green Theme Style matching Cheque Receipt Entry) --}}
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
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'" 
                                class="w-8 h-8 rounded-lg font-bold text-xs flex items-center justify-center transition-colors cursor-pointer" 
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

        {{-- Section B: Project-Wide Equity & Profit Distribution Matrix --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">B. Project-Wide Equity & Profit Distribution Matrix</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Partner Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Role / Entity Type</th>
                            <th class="px-5 py-3.5 text-center text-white font-extrabold border-r border-[#8e7a23]">Agreed Share (%)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Total Allocated Net Profit (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Total Payouts Released (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Current Net Balance Owed (Rs.)</th>
                            <th class="px-4 py-3.5 text-center text-white font-extrabold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(pRow, pIdx) in filteredMatrixList" :key="pIdx">
                            <tr class="hover:bg-slate-50 transition-colors font-medium" :class="filters.partner_id && String(pRow.id) === String(filters.partner_id) ? 'bg-[#a38c29]/5' : ''">
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100">
                                    <span x-text="pRow.name"></span>
                                    <span class="text-xs text-slate-500 font-normal" x-text="' (' + Number(pRow.share_pct).toFixed(1) + '%)'"></span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100" x-text="pRow.role"></td>
                                <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100" x-text="Number(pRow.share_pct).toFixed(1) + '%'"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_allocated)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_payouts)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-black text-[#a38c29] border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.net_balance)"></td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <button type="button" @click="openPayoutModal(pRow.id)" class="px-3 py-1 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white font-extrabold text-[10px] uppercase rounded-lg border border-[#a38c29]/30 transition-all cursor-pointer">
                                        Record Payout
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#a38c29]/10 font-black text-slate-900 border-t-2 border-[#a38c29]/30">
                            <td colspan="2" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">PROJECT TOTALS</td>
                            <td class="px-5 py-3.5 text-center font-mono text-slate-900 border-r border-slate-200" x-text="totalMatrixAgreedPct.toFixed(1) + '%'"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixPayouts)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-slate-900 font-black text-sm border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated - totalMatrixPayouts)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Net Balance Owed = Total Allocated Net Profit - Total Payouts Released</span>
                </div>
                <div class="flex items-center gap-3">
                    <span x-text="'Showing ' + filteredMatrixList.length + ' entries'"></span>
                </div>
            </div>
        </div>

        {{-- ── RECORD PARTNER PAYOUT MODAL ── --}}
        <div x-show="showPayoutModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;">
            <div @click.away="showPayoutModal = false" 
                 class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col transform transition-all">
                
                {{-- Dark Header --}}
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30 flex-shrink-0">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">PARTNER PAYOUT SETUP</span>
                        <h2 class="font-black text-base uppercase tracking-wider text-white">Record Partner Payout</h2>
                    </div>
                    <button type="button" @click="showPayoutModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>

                {{-- Modal Body Form --}}
                <form action="{{ route('reports.partner_statements.payout') }}" method="POST" @submit="handlePayoutSubmit($event)" novalidate class="flex flex-col overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-4 overflow-y-auto max-h-[calc(95vh-130px)]">
                        
                        {{-- 1. Partner & Project Selection (2 Columns) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.partner_id ? 'text-rose-600' : 'text-slate-700'">SELECT PARTNER <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" :class="modalErrors.partner_id ? 'text-rose-400' : 'text-slate-400'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <select name="partner_id" x-model="modalData.partner_id" @change="delete modalErrors.partner_id"
                                            :class="modalErrors.partner_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-800'"
                                            class="w-full pl-10 pr-8 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold cursor-pointer focus:outline-none transition-all shadow-xs appearance-none">
                                        <option value="">-- Choose Partner --</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->role ?? 'Partner' }})</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" :class="modalErrors.partner_id ? 'text-rose-400' : 'text-slate-400'">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <span x-show="modalErrors.partner_id" x-text="modalErrors.partner_id" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.project_id ? 'text-rose-600' : 'text-slate-700'">SELECT PROJECT <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" :class="modalErrors.project_id ? 'text-rose-400' : 'text-slate-400'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <select name="project_id" x-model="modalData.project_id" @change="delete modalErrors.project_id"
                                            :class="modalErrors.project_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-800'"
                                            class="w-full pl-10 pr-8 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold cursor-pointer focus:outline-none transition-all shadow-xs appearance-none">
                                        <option value="">-- Choose Project --</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" :class="modalErrors.project_id ? 'text-rose-400' : 'text-slate-400'">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <span x-show="modalErrors.project_id" x-text="modalErrors.project_id" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                            </div>
                        </div>

                        {{-- 2. Payment Mode & Pay From Account (2 Columns) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.payment_mode ? 'text-rose-600' : 'text-slate-700'">PAYMENT MODE <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="payment_mode" x-model="modalData.payment_mode" @change="delete modalErrors.payment_mode"
                                            :class="modalErrors.payment_mode ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-800'"
                                            class="w-full pl-3.5 pr-8 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold cursor-pointer focus:outline-none transition-all shadow-xs appearance-none">
                                        <option value="">-- Choose Payment Mode --</option>
                                        @if(isset($paymentModes) && count($paymentModes) > 0)
                                            @foreach($paymentModes as $pm)
                                                <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank Transfer (NEFT / RTGS / IMPS)">Bank Transfer (NEFT / RTGS / IMPS)</option>
                                            <option value="UPI / Online Payment">UPI / Online Payment</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" :class="modalErrors.payment_mode ? 'text-rose-400' : 'text-slate-400'">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <span x-show="modalErrors.payment_mode" x-text="modalErrors.payment_mode" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                            </div>

                            <div class="space-y-1.5 relative" @click.outside="modalBankOpen = false">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.company_bank_account_id ? 'text-rose-600' : 'text-slate-700'">
                                    PAY FROM ACCOUNT <span class="text-rose-500">*</span>
                                </label>
                                
                                {{-- Hidden Input for Form Submission --}}
                                <input type="hidden" name="company_bank_account_id" :value="modalData.company_bank_account_id">

                                {{-- Dropdown Trigger Button --}}
                                <div @click="modalBankOpen = !modalBankOpen; if(modalBankOpen) { modalBankSearch = ''; $nextTick(() => $refs.modalBankSearchInput?.focus()); }"
                                     :class="modalErrors.company_bank_account_id ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20' : 'border-slate-200 hover:border-[#a38c29]/60 bg-slate-50 hover:bg-white'"
                                     class="w-full h-10 px-3.5 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-xs">
                                    <template x-if="modalSelectedBankAccount">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="px-2 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[10px]" x-text="modalSelectedBankAccount.bank_name"></span>
                                            <span class="font-bold text-slate-800 truncate" x-text="modalSelectedBankAccount.account_name || modalSelectedBankAccount.bank_name"></span>
                                            <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (modalSelectedBankAccount.account_number || '—') + ')'"></span>
                                        </div>
                                    </template>
                                    <template x-if="!modalSelectedBankAccount">
                                        <span :class="modalErrors.company_bank_account_id ? 'text-rose-400' : 'text-slate-400'" class="font-medium">Select Company Bank Account...</span>
                                    </template>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="modalBankOpen ? 'rotate-180 text-[#a38c29]' : (modalErrors.company_bank_account_id ? 'text-rose-400' : 'text-slate-400')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                <span x-show="modalErrors.company_bank_account_id" x-text="modalErrors.company_bank_account_id" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>

                                {{-- Selected Bank Balance in Words Only --}}
                                <div class="mt-1.5 flex items-baseline justify-between gap-2 text-[11px]" x-show="modalSelectedBankAccount">
                                    <span class="text-slate-500 font-medium shrink-0">Selected Bank Balance:</span>
                                    <span class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                          x-text="modalSelectedBankBalanceInWords"></span>
                                </div>

                                {{-- Dropdown Popover List --}}
                                <div x-show="modalBankOpen" 
                                     x-transition
                                     class="absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-56 flex flex-col"
                                     style="display: none;">
                                    
                                    {{-- Search Input inside Popover --}}
                                    <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                                        <div class="relative">
                                            <input type="text" 
                                                   x-model="modalBankSearch" 
                                                   x-ref="modalBankSearchInput"
                                                   placeholder="Search bank name, account no, branch..." 
                                                   class="w-full pl-7 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                            <svg class="w-3 h-3 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                    </div>

                                    {{-- Results List --}}
                                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                        <template x-for="acc in filteredModalBankAccounts" :key="acc.id">
                                            <div @click="modalData.company_bank_account_id = String(acc.id); delete modalErrors.company_bank_account_id; modalBankOpen = false; modalBankSearch = ''"
                                                 class="px-3 py-2 hover:bg-[#a38c29]/5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                                 :class="String(modalData.company_bank_account_id) === String(acc.id) ? 'bg-[#a38c29]/10 font-bold' : ''">
                                                <div class="flex flex-col min-w-0 pr-2">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                        <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                                    </div>
                                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5 truncate" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                                </div>
                                                <div class="text-right font-mono shrink-0">
                                                    <div class="text-[8px] text-slate-400 uppercase font-sans font-bold tracking-wider">Current Balance</div>
                                                    <div class="font-bold text-slate-800 text-[11px]" x-text="formatCurrency(acc.current_balance !== null && acc.current_balance !== undefined ? acc.current_balance : (acc.opening_balance || 0))"></div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="filteredModalBankAccounts.length === 0">
                                            <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Payout Amount & Payout Date (2 Columns) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.allocated_amount ? 'text-rose-600' : 'text-slate-700'">PAYOUT AMOUNT (RS.) <span class="text-rose-500">*</span></label>
                                <input type="number" name="allocated_amount" x-model="modalData.allocated_amount" @input="delete modalErrors.allocated_amount" step="0.01" placeholder="e.g. 50,000"
                                       :class="modalErrors.allocated_amount ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-900'"
                                       class="w-full px-3.5 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold focus:outline-none transition-all shadow-xs" />
                                <span x-show="modalErrors.allocated_amount" x-text="modalErrors.allocated_amount" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.date ? 'text-rose-600' : 'text-slate-700'">PAYOUT DATE <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" x-model="modalData.date" @change="delete modalErrors.date"
                                       :class="modalErrors.date ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-900'"
                                       class="w-full px-3.5 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold focus:outline-none transition-all shadow-xs cursor-pointer" />
                                <span x-show="modalErrors.date" x-text="modalErrors.date" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                            </div>
                        </div>

                        {{-- 4. Reference / Narration (Single Line) --}}
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider transition-colors" :class="modalErrors.remarks ? 'text-rose-600' : 'text-slate-700'">REFERENCE / NARRATION <span class="text-rose-500">*</span></label>
                            <input type="text" name="remarks" x-model="modalData.remarks" @input="delete modalErrors.remarks" placeholder="Partner Profit Payout - Q2 Distribution"
                                   :class="modalErrors.remarks ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-[#a38c29]/20 bg-slate-50 text-slate-800'"
                                   class="w-full px-3.5 py-2.5 hover:bg-white focus:bg-white border rounded-xl text-xs font-bold focus:outline-none transition-all shadow-xs" />
                            <span x-show="modalErrors.remarks" x-text="modalErrors.remarks" class="text-[10px] font-bold text-rose-600 mt-1 block"></span>
                        </div>

                        {{-- Live Error Banner --}}
                        <template x-if="modalErrorMessage">
                            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-700 text-xs font-bold shadow-xs">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="modalErrorMessage"></span>
                            </div>
                        </template>

                        {{-- 5. Symmetrical 2-Card Financial Summary Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                            {{-- Card 1: Bank Balance Summary --}}
                            <div class="bg-slate-50 border border-slate-200/90 rounded-xl p-3.5 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Source Bank Account</span>
                                    <span class="text-[11px] font-bold text-slate-800 truncate max-w-[150px]" x-text="modalSelectedBankAccount ? modalSelectedBankAccount.bank_name : 'Not Selected'"></span>
                                </div>
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-600 font-semibold text-[11px]">Current Bank Balance:</span>
                                        <span class="font-mono font-bold text-blue-600 text-xs" x-text="formatCurrency(modalSelectedBankBalance)">Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-600 font-semibold text-[11px]">Payout Deduction:</span>
                                        <span class="font-mono font-bold text-rose-500 text-xs" x-text="'- ' + formatCurrency(modalPayoutAmount)">- Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-200/80">
                                        <span class="font-black text-slate-900 uppercase text-[10px] tracking-wider">Bank Balance After:</span>
                                        <span class="font-mono font-black text-xs" :class="modalBankBalanceAfterPayout < 0 ? 'text-rose-600 font-black' : 'text-slate-900'" x-text="formatCurrency(modalBankBalanceAfterPayout)">Rs. 0</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card 2: Partner Balance Summary --}}
                            <div class="bg-slate-50 border border-slate-200/90 rounded-xl p-3.5 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Partner Equity Account</span>
                                    <span class="text-[11px] font-bold text-slate-800 truncate max-w-[150px]" x-text="selectedPartnerName"></span>
                                </div>
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-600 font-semibold text-[11px]">Available Partner Balance:</span>
                                        <span class="font-mono font-bold text-emerald-600 text-xs" x-text="formatCurrency(modalSelectedPartnerBalance)">Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-600 font-semibold text-[11px]">Payout Released:</span>
                                        <span class="font-mono font-bold text-rose-500 text-xs" x-text="'- ' + formatCurrency(modalPayoutAmount)">- Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-200/80">
                                        <span class="font-black text-slate-900 uppercase text-[10px] tracking-wider">Partner Balance After:</span>
                                        <span class="font-mono font-black text-slate-900 text-xs" x-text="formatCurrency(modalBalanceAfterPayout)">Rs. 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Actions Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/60 flex-shrink-0">
                        <button type="button" @click="showPayoutModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">
                            CANCEL
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-black uppercase tracking-wider rounded-xl transition cursor-pointer shadow-md">
                            CONFIRM & POST PAYOUT
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function partnerStatementApp() {
    return {
        showPayoutModal: false,
        filters: {
            partner_id: '{{ request('partner_id', '') }}',
            project_id: '{{ request('project_id', '') }}',
            date_range: '{{ request('date_range', 'inception') }}',
            from_date: '{{ $dateFrom ?? '' }}',
            to_date: '{{ $dateTo ?? '' }}',
        },
        pageSize: 10,
        currentPage: 1,

        rawLedger: @json($runningLedger) || [],
        matrixList: @json($matrixPartners) || [],

        partners: @json($partners) || [],
        projects: @json($projects) || [],
        bankAccounts: @json($bankAccounts) || [],
        companyBankAccounts: @json($companyBankAccounts ?? []) || [],
        paymentModes: @json($paymentModes ?? []) || [],

        modalBankOpen: false,
        modalBankSearch: '',

        get filteredModalBankAccounts() {
            const accounts = this.companyBankAccounts || [];
            if (!this.modalBankSearch || !this.modalBankSearch.trim()) {
                return accounts;
            }
            const q = this.modalBankSearch.toLowerCase().trim();
            return accounts.filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
        },

        modalErrors: {},

        modalData: {
            partner_id: '',
            project_id: '',
            payment_mode: 'Bank Transfer (NEFT / RTGS / IMPS)',
            company_bank_account_id: '',
            allocated_amount: '',
            date: '{{ date('Y-m-d') }}',
            remarks: ''
        },

        openPayoutModal(partnerId = null, projectId = null) {
            this.modalErrors = {};
            this.modalData.partner_id = partnerId ? String(partnerId) : (this.filters.partner_id ? String(this.filters.partner_id) : (this.partners[0] ? String(this.partners[0].id) : ''));
            this.modalData.project_id = projectId ? String(projectId) : (this.filters.project_id ? String(this.filters.project_id) : (this.projects[0] ? String(this.projects[0].id) : ''));
            
            const bankTransferMode = (this.paymentModes || []).find(pm => pm.name && pm.name.toLowerCase().includes('bank transfer'))
                                 || (this.paymentModes || []).find(pm => pm.name && pm.name.toLowerCase().includes('transfer'));
            this.modalData.payment_mode = bankTransferMode ? bankTransferMode.name : ((this.paymentModes && this.paymentModes[0]) ? this.paymentModes[0].name : 'Bank Transfer (NEFT / RTGS / IMPS)');

            this.modalData.company_bank_account_id = (this.companyBankAccounts && this.companyBankAccounts[0]) ? String(this.companyBankAccounts[0].id) : ((this.bankAccounts && this.bankAccounts[0]) ? String(this.bankAccounts[0].id) : '');
            this.modalData.allocated_amount = '';
            this.modalData.date = new Date().toISOString().split('T')[0];
            this.modalData.remarks = '';
            this.showPayoutModal = true;
        },

        handlePayoutSubmit(event) {
            this.modalErrors = {};
            let hasError = false;

            if (!this.modalData.partner_id) {
                this.modalErrors.partner_id = 'Please select a partner';
                hasError = true;
            }
            if (!this.modalData.project_id) {
                this.modalErrors.project_id = 'Please select a project';
                hasError = true;
            }
            if (!this.modalData.payment_mode) {
                this.modalErrors.payment_mode = 'Please choose a payment mode';
                hasError = true;
            }
            if (!this.modalData.company_bank_account_id) {
                this.modalErrors.company_bank_account_id = 'Please select a company bank account';
                hasError = true;
            }
            const amount = parseFloat(this.modalData.allocated_amount);
            if (!this.modalData.allocated_amount || isNaN(amount) || amount <= 0) {
                this.modalErrors.allocated_amount = 'Please enter a valid payout amount greater than ₹0.00';
                hasError = true;
            } else if (this.isBankInsufficient) {
                this.modalErrors.allocated_amount = `Payout amount exceeds available bank balance (${this.formatCurrency(this.modalSelectedBankBalance)})`;
                hasError = true;
            } else if (this.isPartnerInsufficient) {
                this.modalErrors.allocated_amount = `Payout amount exceeds available partner balance (${this.formatCurrency(this.modalSelectedPartnerBalance)})`;
                hasError = true;
            }

            if (!this.modalData.date) {
                this.modalErrors.date = 'Payout date is required';
                hasError = true;
            }
            if (!this.modalData.remarks || !this.modalData.remarks.trim()) {
                this.modalErrors.remarks = 'Reference or narration is required';
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
                return false;
            }
        },

        get selectedPartnerName() {
            if (!this.modalData.partner_id) return 'Not Selected';
            const p = this.partners.find(m => String(m.id) === String(this.modalData.partner_id));
            return p ? p.name : 'Partner';
        },

        get modalSelectedPartnerBalance() {
            if (!this.modalData.partner_id) return 0;
            const p = this.matrixList.find(m => String(m.id) === String(this.modalData.partner_id));
            return p ? Number(p.net_balance || 0) : 0;
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
            const bal = this.modalSelectedBankBalance;
            if (typeof window.convertNumberToWords === 'function') {
                return window.convertNumberToWords(bal);
            }
            return '';
        },

        get modalBankBalanceAfterPayout() {
            return this.modalSelectedBankBalance - this.modalPayoutAmount;
        },

        get modalPayoutAmount() {
            return Number(this.modalData.allocated_amount || 0);
        },

        get modalBalanceAfterPayout() {
            return this.modalSelectedPartnerBalance - this.modalPayoutAmount;
        },

        get isBankInsufficient() {
            if (!this.modalSelectedBankAccount) return false;
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > this.modalSelectedBankBalance;
        },

        get isPartnerInsufficient() {
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > this.modalSelectedPartnerBalance;
        },

        get modalErrorMessage() {
            if (this.isBankInsufficient) {
                return `Insufficient Bank Funds! Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds available balance in ${this.modalSelectedBankAccount?.bank_name || 'selected bank'} (${this.formatCurrency(this.modalSelectedBankBalance)}).`;
            }
            if (this.isPartnerInsufficient) {
                return `Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds available partner balance (${this.formatCurrency(this.modalSelectedPartnerBalance)}).`;
            }
            return '';
        },

        init() {
            // Live reactive initialization
        },

        get filteredLedger() {
            let list = this.rawLedger;

            if (this.filters.partner_id) {
                list = list.filter(r => String(r.partner_id) === String(this.filters.partner_id));
            }

            if (this.filters.from_date) {
                list = list.filter(r => r.date >= this.filters.from_date);
            }

            if (this.filters.to_date) {
                list = list.filter(r => r.date <= this.filters.to_date);
            }

            // Recalculate running balance live
            let bal = 0;
            return list.map(item => {
                bal += (Number(item.credit || 0) - Number(item.debit || 0));
                return {
                    ...item,
                    running_balance: bal
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

        get agreedProfitShareFormatted() {
            if (this.filters.partner_id) {
                const p = this.matrixList.find(m => String(m.id) === String(this.filters.partner_id));
                if (p) return Number(p.share_pct).toFixed(1) + '%';
            }
            return Number(this.totalMatrixAgreedPct || 0).toFixed(1) + '%';
        },

        get filteredMatrixList() {
            if (!this.filters.partner_id) return this.matrixList;
            return this.matrixList.filter(p => String(p.id) === String(this.filters.partner_id));
        },

        get totalMatrixAgreedPct() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.share_pct || 0), 0);
        },

        get totalMatrixAllocated() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.total_allocated || 0), 0);
        },

        get totalMatrixPayouts() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.total_payouts || 0), 0);
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
            this.filters.partner_id = '';
            this.filters.project_id = '';
            this.filters.date_range = 'inception';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.currentPage = 1;
        },

        formatCurrency(num) {
            return 'Rs. ' + Number(num || 0).toLocaleString('en-IN');
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
            let amountWords = typeof window.convertNumberToWords === 'function' ? window.convertNumberToWords(amountNum) : '';
            if (amountWords) {
                amountWords = amountWords.trim();
                if (amountWords.toLowerCase().endsWith('only')) {
                    amountWords = amountWords.slice(0, -4).trim();
                }
                amountWords = amountWords.charAt(0).toUpperCase() + amountWords.slice(1) + ' Only';
            }

            const pObj = (this.partners || []).find(p => String(p.id) === String(receipt.partner_id || '')) || 
                         (this.matrixList || []).find(p => String(p.id) === String(receipt.partner_id || ''));
            const partnerName = escapeHtml(receipt.partner_name || (pObj ? pObj.name : 'Partner'));
            const partnerPhone = escapeHtml((pObj && (pObj.phone || pObj.mobile)) ? (pObj.phone || pObj.mobile) : '—');
            const partnerRole = escapeHtml((pObj && pObj.role) ? pObj.role : (isCredit ? 'Profit Share' : 'Partner Equity'));
            
            const projObj = (this.projects || []).find(pj => String(pj.id) === String(this.filters.project_id || '')) || 
                            (this.projects && this.projects[0] ? this.projects[0] : null);
            const projectName = escapeHtml(projObj ? projObj.name : 'Tabasco Hindustan Infra Developers Pvt. Ltd.');
            const projectCompany = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.';

            const cBank = (this.companyBankAccounts || []).find(b => String(b.id) === String(this.modalData?.company_bank_account_id || '')) || 
                          (this.companyBankAccounts && this.companyBankAccounts[0] ? this.companyBankAccounts[0] : null);
            const bankName = escapeHtml(cBank ? (cBank.bank_name + (cBank.account_number ? ' (' + cBank.account_number + ')' : '')) : (receipt.company_bank_account_name || 'General Account'));

            const refNo = escapeHtml(receipt.ref_no || 'VOUCHER');
            const dateVal = typeof this.formatDate === 'function' ? this.formatDate(receipt.date) : escapeHtml(receipt.date || '—');
            const payMode = escapeHtml(receipt.payment_mode || (isCredit ? 'Profit Allocation (JV)' : 'Bank Transfer'));
            const description = escapeHtml(receipt.description || (isCredit ? 'Profit Share Allocation' : 'Partner Payout Disbursement'));

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
    <title>Payment Receipt — ${refNo}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    ` + '<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></scr' + 'ipt>' + `
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

        /* Top Page Navigation Bar */
        .top-nav {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            color: #334155;
        }
        .nav-title {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.3px;
        }
        .nav-sub {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #a38c29;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(163, 140, 41, 0.25);
            transition: all 0.2s ease;
        }
        .btn-download:hover {
            background: #8e7921;
            transform: translateY(-1px);
        }
        .btn-download:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        .btn-close {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-close:hover {
            background: #f1f5f9;
        }

        /* White Receipt Sheet Card */
        .receipt-card {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2dcd0;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 6px 24px -4px rgba(15, 23, 42, 0.08);
        }

        /* Sleek Slate Header Banner - Flush with top and side corners */
        .company-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 0;
            padding: 22px 28px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            border-bottom: 1.5px solid #334155;
        }
        .hero-left {
            display: flex;
            align-items: center;
            max-width: 65%;
        }
        .company-name {
            font-size: 15.5px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.35;
        }
        .hero-right {
            text-align: right;
        }
        .receipt-pill-title {
            font-size: 15px;
            font-weight: 900;
            color: #e2b855;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .receipt-no-row {
            margin-top: 4px;
            font-size: 12.5px;
        }
        .no-lbl {
            color: #94a3b8;
            font-weight: 500;
            margin-right: 6px;
        }
        .no-val {
            color: #ffffff;
            font-weight: 900;
            font-size: 14.5px;
        }

        /* Inner Receipt Padding Container */
        .receipt-body {
            padding: 22px 24px;
        }

        /* 3 Metadata Horizontal Strip */
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background: #fcfbf8;
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 18px;
        }
        .meta-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .meta-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }
        .meta-label {
            font-size: 8.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 2 Column Details Grid */
        .grid-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 18px;
        }
        .detail-box {
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }
        .box-head {
            background: #fcfbf8;
            padding: 10px 16px;
            font-size: 11.5px;
            font-weight: 800;
            color: #8c733e;
            border-bottom: 1.5px solid #ebe5d8;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .box-body {
            padding: 8px 16px;
        }
        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f6f2ea;
            font-size: 11.5px;
        }
        .field-row:last-child {
            border-bottom: none;
        }
        .f-lbl {
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
        }
        .f-val {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
            max-width: 62%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Metallic Golden Amount Received Banner */
        .amount-banner {
            background: linear-gradient(135deg, #dfb858 0%, #fae69e 45%, #d1a038 100%);
            border: 1.5px solid #c99b32;
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            box-shadow: 0 4px 12px rgba(184, 138, 37, 0.18);
        }
        .amount-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }
        .coin-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #fff2a8, #d4af37 45%, #96741b 85%, #634d10 100%);
            border: 1.5px solid #ffea88;
            box-shadow: 0 4px 8px rgba(150, 116, 27, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .coin-inner {
            font-size: 21px;
            font-weight: 900;
            color: #4a3809;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8), 0 -1px 1px rgba(0, 0, 0, 0.4);
        }
        .amt-words-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-words-val {
            font-size: 12.5px;
            font-weight: 800;
            color: #1c1505;
            font-style: italic;
            margin-top: 2px;
            line-height: 1.35;
        }
        .amount-divider {
            width: 1.5px;
            height: 42px;
            background: #a98020;
            margin: 0 20px;
            flex-shrink: 0;
        }
        .amount-right {
            text-align: right;
            flex-shrink: 0;
        }
        .amt-total-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-total-val {
            font-size: 25px;
            font-weight: 900;
            color: #110e05;
            margin-top: 2px;
            letter-spacing: -0.5px;
        }

        /* Print Media Styling */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #ffffff;
                padding: 0;
            }
            .top-nav {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                border: 1.5px solid #ebe5d8;
                border-radius: 14px;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <div class="nav-left">
            <div class="nav-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div>
                <h1 class="nav-title">Payment Receipt</h1>
                <p class="nav-sub">View and manage partner statement receipt details</p>
            </div>
        </div>
        <div class="nav-actions">
            <button onclick="downloadDirectPdf()" class="btn-download">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Download PDF</span>
            </button>
            <button onclick="window.close()" class="btn-close">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="receipt-card">
        <div class="company-hero">
            <div class="hero-left">
                <div class="company-name">${projectCompany}</div>
            </div>

            <div class="hero-right">
                <div class="receipt-pill-title">PAYMENT RECEIPT</div>
                <div class="receipt-no-row">
                    <span class="no-lbl">Receipt No.</span>
                    <span class="no-val mono">${refNo}</span>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="meta-strip">
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#ecfdf5; color:#059669;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">RECEIPT NUMBER</span>
                        <span class="meta-value mono">${refNo}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fff1f2; color:#f43f5e;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">RECEIPT DATE</span>
                        <span class="meta-value">${dateVal}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fffbeb; color:#d97706;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">PAYMENT MODE</span>
                        <span class="meta-value">${payMode}</span>
                    </div>
                </div>
            </div>

            <div class="grid-details">
                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Customer &amp; Property Details</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Received From</span>
                            <span class="f-val">${partnerName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Contact Number</span>
                            <span class="f-val">${partnerPhone}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Project / Site</span>
                            <span class="f-val">${projectName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Unit / Door No</span>
                            <span class="f-val">${partnerRole}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="5 10 12 3 19 10"></polyline><line x1="6" y1="10" x2="6" y2="21"></line><line x1="10" y1="10" x2="10" y2="21"></line><line x1="14" y1="10" x2="14" y2="21"></line><line x1="18" y1="10" x2="18" y2="21"></line></svg>
                        <span>Payment &amp; Banking Information</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Company Bank A/C</span>
                            <span class="f-val">${bankName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Cheque / Ref / UTR</span>
                            <span class="f-val mono">${refNo}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Transaction Particulars</span>
                            <span class="f-val">${description}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="amount-banner">
                <div class="amount-left">
                    <div class="coin-badge">
                        <div class="coin-inner">₹</div>
                    </div>
                    <div>
                        <div class="amt-words-lbl">Amount Received (in Words)</div>
                        <div class="amt-words-val">${amountWords || '—'}</div>
                    </div>
                </div>
                <div class="amount-divider"></div>
                <div class="amount-right">
                    <div class="amt-total-lbl">Total Received</div>
                    <div class="amt-total-val mono">${amountFormatted}</div>
                </div>
            </div>
        </div>
    </div>

    ` + '<scr' + 'ipt>' + `
    function downloadDirectPdf() {
        const element = document.querySelector('.receipt-card');
        const btn = document.querySelector('.btn-download');
        if (!element) return;

        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Downloading...</span>';
        btn.disabled = true;

        const opt = {
            margin: [8, 8, 8, 8],
            filename: 'Payment_Receipt_${refNo}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        if (typeof html2pdf !== 'undefined') {
            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = '<span>✓ Downloaded</span>';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2500);
            }).catch(err => {
                console.error('PDF Error:', err);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            alert('PDF generator is loading. Please try again.');
        }
    }
    ` + '</scr' + 'ipt>' + `
</body>
</html>`;

            printWin.document.open();
            printWin.document.write(html);
            printWin.document.close();
        },

        printReport(title) {
            window.print();
        },

        exportExcel(type = 'partner_statement') {
            if (typeof reportsApp === 'function') {
                const rApp = reportsApp();
                if (rApp && typeof rApp.exportCurrentTable === 'function') {
                    rApp.exportCurrentTable(type);
                    return;
                }
            }
        }
    }
}
</script>

{{-- ── HIDDEN EXCEL EXPORT TABLES (STYLED TO MATCH SALES REPORT EXCEL DESIGN) ── --}}
<div class="hidden" style="display: none;">

    {{-- 1. PARTNER STATEMENT EXCEL TABLE --}}
    <table id="partnerStatementExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="130" style="width: 98pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="140" style="width: 105pt;" />
            <col width="320" style="width: 240pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PARTNER STATEMENT & EQUITY LEDGER REPORT
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                    EXECUTIVE SUMMARY & METRIC KPIS
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Agreed Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)($agreedProfitShare ?? 0), 2) }}%</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Earned Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($earnedProfitShare ?? $totalCredit ?? 0) }}</th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Payouts Released:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalPayoutsReleased ?? $totalDebit ?? 0) }}</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Current Net Equity Balance:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($currentNetEquityBalance ?? $runningBalance ?? 0) }}</th>
            </tr>
            <tr height="15" style="height: 15pt;"><th colspan="8" bgcolor="#ffffff" style="border: none;"></th></tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                    A. INDIVIDUAL PARTNER STATEMENT OF ACCOUNT (RUNNING LEDGER)
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">TRANSACTION DATE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">VOUCHER / REF NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">DESCRIPTION / TRANSACTION TYPE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PROFIT SHARE ALLOCATED (CREDIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">RUNNING PAYABLE BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ (is_object($entry) ? $entry->description : ($entry['description'] ?? '')) . (!empty(is_object($entry) ? ($entry->payment_mode ?? '') : ($entry['payment_mode'] ?? '')) ? ' ('.(is_object($entry) ? $entry->payment_mode : $entry['payment_mode']).')' : '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL LEDGER BALANCE</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($runningBalance ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- 2. PROFIT SHARING SUMMARY EXCEL TABLE --}}
    <table id="profitSharingExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="120" style="width: 90pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="7" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PROJECT PROFIT SHARING & EQUITY DISTRIBUTION SUMMARY
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">ROLE / ENTITY TYPE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">AGREED SHARE (%)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">TOTAL ALLOCATED NET PROFIT</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">TOTAL PAYOUTS RELEASED</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">CURRENT NET BALANCE OWED</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrixPartners as $pIdx => $pRow)
                <tr height="25" style="height: 25pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $pIdx + 1 }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->name : ($pRow['name'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->role : ($pRow['role'] ?? '') }}</td>
                    <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)(is_object($pRow) ? $pRow->share_pct : ($pRow['share_pct'] ?? 0)), 2) }}%</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->total_allocated : ($pRow['total_allocated'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->total_payouts : ($pRow['total_payouts'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #17365D; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->net_balance : ($pRow['net_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="3" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">PROJECT TOTALS</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)($totalMatrixAgreedPct ?? 0), 2) }}%</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalMatrixAllocated ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalMatrixPayouts ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(($totalMatrixAllocated ?? 0) - ($totalMatrixPayouts ?? 0)) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- 3. DISTRIBUTION HISTORY LOG EXCEL TABLE --}}
    <table id="distributionHistoryExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="130" style="width: 98pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="150" style="width: 113pt;" />
            <col width="320" style="width: 240pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PARTNER PROFIT ALLOCATION & PAYOUT DISTRIBUTION HISTORY LOG
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">DATE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">REF / VOUCHER NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">DESCRIPTION</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">ALLOCATED PROFIT (CREDIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">RUNNING BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ (is_object($entry) ? $entry->description : ($entry['description'] ?? '')) . (!empty(is_object($entry) ? ($entry->payment_mode ?? '') : ($entry['payment_mode'] ?? '')) ? ' ('.(is_object($entry) ? $entry->payment_mode : $entry['payment_mode']).')' : '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL DISTRIBUTION LOG SUMMARY</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($runningBalance ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>

</div>

@include('reports.partials.script')

</x-erp-layout>
